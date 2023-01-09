<?php

namespace App\Console\Commands\Revival;

use App\Enums\Channel\ChannelEnum;
use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Enums\Ticket\TicketStateEnum;
use App\Jobs\SendMessage\ButSendMessage;
use App\Jobs\SendMessage\CarrefourSendMessage;
use App\Jobs\SendMessage\ConforamaSendMesssage;
use App\Jobs\SendMessage\DartySendMessage;
use App\Jobs\SendMessage\IntermarcheSendMessage;
use App\Jobs\SendMessage\LaposteSendMessage;
use App\Jobs\SendMessage\LeclercSendMessage;
use App\Jobs\SendMessage\MetroSendMessage;
use App\Jobs\SendMessage\RueDuCommerceSendMessage;
use App\Jobs\SendMessage\ShowroomSendMessage;
use App\Jobs\SendMessage\UbaldiSendMessage;
use App\Models\Channel\DefaultAnswer;
use App\Models\Ticket\Message;
use App\Models\Ticket\Thread;
use App\SMS\SMS;
use Cnsi\Logger\Logger;
use DateInterval;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;

class RevivalCommand extends Command
{
    protected $signature = 'ticket:revival:send';
    protected $description = 'Send revival on eligible threads';

    protected Logger $logger;

    public function handle()
    {
        $this->logger = new Logger('ticket/revival/revival.log', true, true);
        $this->logger->info('----[ START ]----');

        try {
            DB::beginTransaction();
            /** @var Thread[] $threads */
            $threads = Thread::query()->whereNotNull('revival_id')->get();
            $this->logger->info('All ticket with revival are loaded');

            foreach ($threads as $thread) {
                $this->logger->info('-- Processing thread #' . $thread->id);
                $this->logger->info('Loading revival #' . $thread->revival->id);

                $this->logger->info('Checking if ticket is allowable for revival');
                if ($revivalError = $thread->getThreadRevivalError()) {
                    $this->logger->error($revivalError);
                } elseif ($thread->revival_message_count >= $thread->revival->max_revival) {
                    $this->logger->info('--- Max count : stop revival');
                    $this->performLastRevivalAction($thread);
                } else {
                    $this->logger->info('Sending revival message');
                    $this->sendMessageOfRevival($thread);
                }
            }
            DB::commit();
            $this->logger->info('----[ DONE ]----');
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            $this->logger->error('An error has occurred. Rolling back');
            DB::rollBack();
            \App\Mail\Exception::sendErrorMail($e, $this->getName(), $this->description, $this->output);
        }
    }

    private function performLastRevivalAction(Thread $thread)
    {
        //todo : make tag action
        $ticket = $thread->ticket;
        $revival = $thread->revival;

        $endReply = $revival->end_default_answer;
        if (!empty($endReply)) {
            $this->logger->info('Sending response name : ' . $endReply->name);
            $this->sendRevivalMessage($thread, $endReply);
        }

        $newStatus = $revival->end_state;
        if (!$newStatus) {
            $this->logger->info("Updating the status's ticket " . $ticket->state . " to : " . $newStatus);
            $ticket->state = $newStatus;
        }

        $deadLine = date('Y-m-d H:i:s', time() + (24 * 3600));
        $this->logger->info("Updating deadline's ticket " . $ticket->deadline->format('Y-m-d H:i:s') . " to : " . $deadLine);
        $ticket->deadline = $deadLine;

        $this->logger->info('Save ticket modification ...');
        $ticket->save();

        $this->logger->info('Stopping ticket revival ...');
        $this->stopThreadRevival($thread);
    }

    private function sendMessageOfRevival(Thread $thread): void
    {
        $revival = $thread->revival;

        $this->logger->info('Save ticket and Thread');

        // Update ticket
        $ticket = $thread->ticket;
        $ticket->state = TicketStateEnum::WAITING_CUSTOMER;

        //$lastDeadline = Carbon::createFromFormat('Y.m.d', $ticket->deadline)->addDays($revival->frequency);
        //$ticket->deadline = $lastDeadline;

        // Update thread
        $thread->revival_message_count = ++$thread->revival_message_count;
        $thread->save();

        $message = $revival->default_answer;
        $this->sendRevivalMessage($thread, $message);
    }

    private function stopThreadRevival(Thread $thread): void
    {
        $thread->revival = null;
        $thread->revival_message_count = 0;
        $thread->revival_start_date = null;
        $thread->save();
    }

    /**
     * @throws TwilioException
     * @throws ConfigurationException
     */
    private function sendRevivalMessage(Thread $thread, DefaultAnswer $message): void
    {
        $this->logger->info('Save message in DB');
        $messageBD = new Message();
        $messageBD->thread_id = $thread->id;
        $messageBD->user_id = null;
        $messageBD->channel_message_number = null;
        $messageBD->author_type = TicketMessageAuthorTypeEnum::ADMIN;
        $messageBD->content = $message->content;
        $messageBD->save();

        $revival_send_type = $thread->revival->send_type;

        if ($revival_send_type === 'CHANNEL') {
            $this->logger->info('Send message on API');
            $channel = $thread->ticket->channel->name;
            match ($channel) {
                ChannelEnum::BUT_FR => ButSendMessage::dispatch($messageBD),
                ChannelEnum::CARREFOUR_FR => CarrefourSendMessage::dispatch($messageBD),
                ChannelEnum::CONFORAMA_FR => ConforamaSendMesssage::dispatch($messageBD),
                ChannelEnum::DARTY_COM => DartySendMessage::dispatch($messageBD),
                ChannelEnum::INTERMARCHE_FR => IntermarcheSendMessage::dispatch($messageBD),
                ChannelEnum::LAPOSTE_FR => LaposteSendMessage::dispatch($messageBD),
                ChannelEnum::E_LECLERC => LeclercSendMessage::dispatch($messageBD),
                ChannelEnum::METRO_FR => MetroSendMessage::dispatch($messageBD),
                ChannelEnum::RUEDUCOMMERCE_FR => RueDuCommerceSendMessage::dispatch($messageBD),
                ChannelEnum::SHOWROOMPRIVE_COM => ShowroomSendMessage::dispatch($messageBD),
                ChannelEnum::UBALDI_COM => UbaldiSendMessage::dispatch($messageBD),
            };
        } elseif ($revival_send_type === 'SMS'){
            SMS::sendSMS($messageBD->content);
        }
    }
}
