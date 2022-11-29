<?php

namespace App\Console\Commands\SendMessage;

use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Models\Ticket\Message;
use App\Models\Ticket\Ticket;
use Exception;
use Illuminate\Console\Command;
use Mirakl\MMP\Common\Domain\Collection\Message\Thread\ThreadRecipientCollection;
use Mirakl\MMP\Common\Domain\Message\Thread\ThreadRecipient;
use Mirakl\MMP\Common\Domain\Message\Thread\ThreadReplyMessageInput;
use Mirakl\MMP\Common\Request\Message\ThreadReplyRequest;
use Mirakl\MMP\Shop\Client\ShopApiClient;

abstract class ShowroomPriveeSendMessage extends Command
{
    const HTTP_CONNECT_TIMEOUT = 15;

    const CUSTOMER = 'CUSTOMER';
    const OPERATOR = 'OPERATOR';

    const FROM_SHOP_TYPE = 'SHOP_USER';

    protected $signature = '%s:send:messages {--S|sync} {--T|thread=} {--only_best_prices} {--only_updated_offers} {--exclude_supplier=*} {--only_best_sellers} {--part=}';
    protected $description = 'Importing competing offers from Mirakl.';

    /**
     * Execute the console command.
     *
     * @throws Exception
     */
    public function handle()
    {
        //$this->initApiClient();
        //dd($this->importThread());
        $message1 = \App\Models\Ticket\Message::find(3);
        //dd($message1);
        dd($this->sendThreadReply([self::CUSTOMER], '07b62278-0faa-41da-9278-d19550eda712', $message1));

        //dd(\App\Mail\Exception::sendErrorMail($e, $this->getName(), $this->description, $this->output));
        //dd($this->isImported('883c3c6f-4c53-4be1-8928-322f282ae70b'));

        return Command::SUCCESS;
    }

    public function replyToTicket(Ticket $ticket, $channel_thread, $message){
        try{
            //$mpOrderId = $ticket->order_id;
            $thread_by_ticket = \App\Models\Ticket\Thread::getThread($ticket, $channel_thread,'Topic message','');

            $action = $message->type;
            switch ($action) {
                case 1:
                    $send_to = [self::CUSTOMER];
                    break;
                case 2:
                    $send_to = [self::OPERATOR];
                    break;
                case 3:
                    $send_to = [self::OPERATOR, self::CUSTOMER];
                    break;
                default:
                    throw new Exception("Action " . $action . " is not associated.");
                    break;
            }
            $this->sendThreadReply($send_to, $thread_by_ticket->id, $message);
        }catch (Exception $e) {
            echo 'Error : ' . $e->getMessage() . "\n";
        }
    }

    /*public function sendMessage($thread_id,$user_id,$channel){
        $message = Message::firstOrCreate([
            'channel_message_number' => $channel->getId(),
        ],
            [
                'thread_id' => $thread_id,
                'user_id' => 1, // TODO : null
                'channel_message_number' => $channel->getId(),
                'author_type' => TicketMessageAuthorTypeEnum::OPERATEUR, // TODO : à faire (opérateur / client)
                'content' => strip_tags($channel->getBody()),
            ],
        );

    }*/

    public function sendThreadReply(array $send_to, $thread_id, Message $message){
        $client = $this->initApiClient();

        $recipients = new ThreadRecipientCollection();
        foreach ( $send_to as $type){
            $rec = new ThreadRecipient();
            $rec->setType($type);
            $recipients->add($rec);
        }

        $messageToAnswer = new ThreadReplyMessageInput();
        $messageToAnswer->setTo($recipients);
        $messageToAnswer->setBody($message->content);

        $request = new ThreadReplyRequest($thread_id, $messageToAnswer);

        return $client->replyToThread($request);
    }
}
