<?php

namespace App\Console\Commands\Alert;

use App\Enums\Ticket\TicketStateEnum;
use App\Models\Ticket\Ticket;
use Cnsi\Csvgenerator\CSVGenerator;
use Cnsi\Lock\Lock;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class AlertTicketDelay extends Command
{
    protected $signature = 'alert:ticket:delay';
    protected $description = 'Alert recipient when the delay is more on 15 days';

    const ALERT_LOCKED_SINCE = 300;
    const KILL_LOCKED_SINCE = 600;

    public function handle()
    {
        $lock = new Lock($this->getName(), self::ALERT_LOCKED_SINCE, self::KILL_LOCKED_SINCE, env('ERROR_RECIPIENTS'));
        $lock->lock();

        $from_time = strtotime(date('Y-m-d H:m:s') . '- 14 days');
        $from_date = date('Y-m-d H:m:i', $from_time);

        $ticketQuery = Ticket::query()
            ->select('tickets.*','channels.name as channel_name', 'ticket_threads.name as thread_name', 'users.name as user_name')
            ->join('ticket_threads', 'ticket_threads.ticket_id', '=', 'tickets.id') // thread
            ->join('channels', 'channels.id', '=','tickets.channel_id')
            ->join('users','users.id', '=','tickets.user_id')
            ->where('tickets.updated_at', '<', $from_date)
            ->where('tickets.state', TicketStateEnum::OPENED)
            ->get()
        ;

        if($ticketQuery->count() > 0){
            $filename = "Alert-ticket-delay_" . date('d-m-Y');

            // Set header collunm
            $fields = ['TICKET_ID','RESPONSABLE','SUJET','STATUT','PRIORITÉ','CANAL DE DIFFUSION'];
            $listTicket = [];
            foreach ($ticketQuery as $ticket) {
                $listTicket[] = [$ticket['id'], $ticket['user_name'], $ticket['thread_name'], TicketStateEnum::getMessage($ticket['state']) , $ticket['priority'], $ticket['channel_name']];
            }
            $csvFile = CSVGenerator::createCsvFromArray($filename, $listTicket, $fields);
            $recipients = explode(',', env('TICKET_DELAY_RECIPIENTS'));
            $data["email"] = $recipients;
            $data["title"] = "Ticket décalé : " . date('d-m-Y') ;
            $data["body"] = "Vous avez ". $ticketQuery->count() . " ticket". ($ticketQuery->count() > 1 ? "s " : " " ) ."qui ont été repousser de plus de 15jours, vous les trouverez ci-joint au mail.";


            Mail::send('emails.ticketDelay', $data, function($message)use($data, $csvFile) {
                $message->to($data["email"], $data["email"])
                    ->subject($data["title"])
                    ->attach($csvFile);

            });
            print_r($csvFile);
        }

    }
}
