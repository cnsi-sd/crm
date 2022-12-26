<?php

namespace App\Console\Commands\Revival;

use App\Enums\Channel\ChannelEnum;
use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Enums\Ticket\TicketStateEnum;
use App\Jobs\SendMessage\ButSendMessage;
use App\Models\Channel\DefaultAnswer;
use App\Models\Ticket\Message;
use App\Models\Ticket\Revival\Revival;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use Cnsi\Logger\Logger;
use Exception;
use Illuminate\Console\Command;

class RevivalCommand extends Command
{

    protected $signature = 'revival:message:ticket';
    protected $description = 'Releance automatique de message';

    protected $logger;

    public function handle()
    {
        $this->logger = new Logger('Ticket/revival', true, true);
        $this->logger->info('----[ START ]----');
        try {
            $tickets = Ticket::all();
            $threads = Thread::query()->where('revival_id', '!=', null)->get();
            $this->logger->info('All ticket with revival are loaded');
            foreach ($threads as $thread) {
                $ticket_id = $thread->ticket_id;
                $ticket = Ticket::find($ticket_id);
                $this->logger->info('-- Processing thread ');
                $this->logger->info('Running revival for thread N.' . $thread->id);
                $this->logger->info('Loading revival N.' . $thread->revival->id);
                $revival = $thread->revival;
                try {
                    $this->logger->info('Checking if ticket is allowable for revival');
                    $this->isAllowableForRevival($thread, $ticket, $revival);
                    if ($thread->revival_message_count >= $revival->max_revival) {
                        $this->logger->info('--- Max count : stop revival');
                        $this->performLastRevivalAction($thread, $ticket, $revival);
                    } else {
                        $this->logger->info('Sending revival message');
                        $this->sendMessageOfRevival($ticket, $thread, $revival);
                    }
                } catch (Exception $exception) {
                    $this->logger->error($exception->getMessage());
                }
            }
            $this->logger->info('----[ DONE ]----');
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            return $e;
        }
    }


    public function isAllowableForRevival(Thread $thread, Ticket $ticket, $revival = null, $check_date = true)
    {
        try {
            if (is_null($revival))
                $revival = $thread->revival;

            $defaultReply = $revival->default_answer->id;
            if (empty($defaultReply))
                throw new Exception('Configuration de la relance auto incomplète : le champs `Réponse par défaut` es invalide');

            if ($revival->frequency <= 0)
                throw new Exception('Configuration de la relance auto incomplète : le champs `Fréquence des relances` es invalide');

            $lastMessage = $this->getLastMessage($thread);
            if (!$lastMessage || !$lastMessage->id || $lastMessage->author_type !== TicketMessageAuthorTypeEnum::ADMIN)
                throw new Exception('Le dernier message doit être écrit par un administrateur');

            if ($ticket->state !== TicketStateEnum::WAITING_CUSTOMER)
                throw new Exception('Le ticket doit être en Attente client');

        } catch (Exception $exception) {
            throw new Exception('Relance automatique non applicable au ticket N.' . $thread->id . ' : ' . $exception->getMessage());
        }
    }

    public function getLastMessage($thread): ?Message
    {
        // todo : voir tri object
        return Message::query()
            ->where('thread_id', $thread->id)
            ->orderBy('created_at', 'DESC')
            ->first();
    }

    private function performLastRevivalAction(Thread $thread, Ticket $ticket, Revival $revival)
    {
        //todo : make tag action

        $endReply = $revival->end_default_answer;
        if (!empty($endReply)) {
            $this->logger->info('Sending response name : ' . $endReply->name);
            $this->sendEndMessageOfRevival($thread, $endReply);
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

    private function sendEndMessageOfRevival(Thread $thread, $revival): void
    {
        $message = $revival->end_default_answer;
        $this->sendRevivalMessage($thread, $message);
    }

    private function sendMessageOfRevival(Ticket $ticket, Thread $thread, $revival): void
    {
        $ticket->state = TicketStateEnum::WAITING_CUSTOMER;
        $thread->revival_message_count = ++$thread->revival_message_count;
        $ticket->deadline = date('Y-m-d H:i:s', time() + $this->getFrequencyInSecond($revival));

        $this->logger->info('Save in DB of Ticket and Thread');
        $ticket->save();
        $thread->save();


        $message = $revival->default_answer;
        $this->sendRevivalMessage($thread, $message);
    }

    private function stopThreadRevival(Thread $thread)
    {
        $thread->revival = null;
        $thread->revival_message_count = 0;
        $thread->revival_start_date = null;
        $thread->save();
    }

    private function sendRevivalMessage(Thread $thread, DefaultAnswer $message)
    {
        $messageBD = new Message();
        $messageBD->thread_id = $thread->id;
        $messageBD->user_id = null;
        $messageBD->channel_message_number = null; // todo : definir comment recuperer
        $messageBD->author_type = TicketMessageAuthorTypeEnum::ADMIN;
        $messageBD->content = $message->content;
        $messageBD->save();
        $this->logger->info('Message save in DB');

        $channel = $thread->ticket->channel->name;
        match ($channel){
            ChannelEnum::BUT_FR => ButSendMessage::dispatch($messageBD),
        };

        return $messageBD;
    }


    private function getFrequencyInSecond(Revival $revival)
    {
        return $revival->frequency * 24 * 3600;
    }

}
