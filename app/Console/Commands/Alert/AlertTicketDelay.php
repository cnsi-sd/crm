<?php

namespace App\Console\Commands\Alert;

use App\Enums\Ticket\TicketStateEnum;
use App\Helpers\CSVGenerator;
use App\Models\Ticket\Ticket;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class AlertTicketDelay extends Command
{
    protected $signature = 'alert:ticket:delay';
    protected $description = 'Alert receipient when the delay is more on 15 days';

    public function handle()
    {
        $from_time = strtotime(date('Y-m-d H:m:s') . '15 days');
        $from_date = date('Y-m-d H:m:i', $from_time);

        $ticketquery = Ticket::query()
            ->select('tickets.*','channels.name as channel_name', 'ticket_threads.name as thread_name', 'users.name as user_name')
            ->join('ticket_threads', 'ticket_threads.ticket_id', '=', 'tickets.id') // thread
            ->leftJoin('ticket_thread_comments', 'ticket_thread_comments.thread_id', '=', 'ticket_threads.id') // ticket
            ->join('channels', 'channels.id', '=','tickets.channel_id')
            ->join('users','users.id', '=','tickets.user_id')
            ->where('tickets.updated_at', '<', $from_date)
            ->get()
        ;

        if($ticketquery->count() > 0){
            $filename = "TicketDelay/Alert-ticket-delay_" . date('d-m-Y H:i:s');

            // Set header collunm
            $fields = array('TICKET_ID','RESPONSABLE','SUJET','STATUS','PRIORITÉ','CANAL DE DIFFUSION');
            $listTicket = array();
            foreach ($ticketquery as $ticket) {
                if($ticket['state'] === TicketStateEnum::WAITING_CUSTOMER)
                    $state = 'ATTENTE CLIENT';
                elseif ($ticket['state'] === TicketStateEnum::WAITING_ADMIN)
                    $state = 'ATTENTE ADMIN';
                if($ticket['state'] === TicketStateEnum::WAITING_CUSTOMER || $ticket['state'] === TicketStateEnum::WAITING_ADMIN){
                    $listTicket[] = array($ticket['id'], $ticket['user_name'], $ticket['thread_name'], $state , $ticket['priority'], $ticket['channel_name']);
                }
            }
            $csvFile = (new CSVGenerator())->createCsvFromArray($filename, $listTicket, $fields, false);
            $recipients = explode(',', env('TICKET_DELAY_RECIPIENTS'));
            $data["email"] = $recipients;
            $data["title"] = "Ticket décalé : " . date('d-m-Y') ;
            $data["body"] = "Vous avez ". $ticketquery->count() . " ticket". ($ticketquery->count() > 1 ? "s " : " " ) ."qui ont été repousser de plus de 15jours, vous les trouverez ci-joint au mail.";


            Mail::send('emails.ticketDelay', $data, function($message)use($data, $csvFile) {
                $message->to($data["email"], $data["email"])
                    ->subject($data["title"])
                    ->attach($csvFile);

            });
            print_r($csvFile);
        }

    }
}
