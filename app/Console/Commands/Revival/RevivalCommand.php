<?php

namespace App\Console\Commands\Revival;

use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Enums\Ticket\TicketStateEnum;
use App\Models\Ticket\Message;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\Revival\Revival;
use App\Models\Channel\DefaultAnswer;
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
        $this->logger = new Logger('test/ticket', true, true);
        $this->logger->info('----------------------------------');
        $this->logger->info('--------------[ START ]-----------');
        $this->logger->info('----------------------------------');
        $this->logger->info('----------[ LOADING Ticket]-------');
        $this->logger->info("----------------------------------\n");
        try {
            $tickets = Ticket::all();
            $this->logger->info('All ticket is loading');
            $this->logger->info('----');
            foreach ($tickets as $ticket) {
                $threads = $ticket->threads;
                $this->logger->info('-- Processing ticket ...');

                $this->processTicket($ticket);
            }
            $this->logger->info("|| SCRIPT TERMINATED ||\n");
            $this->logger->info('----------------------------------');
            $this->logger->info('---------------[ END ]------------');
            $this->logger->info('----------------------------------');
        } catch (Exception $e) {
            $this->logger->error($e);
            return $e;
        }
    }

    private function processTicket(Ticket $ticket)
    {
        $log_indentation = '---- ';
        $threads = $ticket->threads;
        foreach ($threads as $thread) {
            if ($thread->revival !== null) {
                $this->logger->info('Running revival for thread N.' . $thread->id . ' ...');
                $this->logger->info('-- Loading revival N.' . $thread->revival->id . ' ---');
                $revival = $thread->revival;
                try {
                    $this->logger->info($log_indentation . 'Checking if ticket is allowable for revival ...');
                    $this->isAllowableForRevival($thread, $ticket, $revival);
                    if ($thread->revival_message_count >= $revival->max_revival){
                        $this->logger->info($log_indentation . 'Performing last revival action ...');
                        $this->performLastRevivalAction($thread, $ticket, $revival);
                    } else {
                        $this->logger->info($log_indentation . 'Sending revival message ...');
                        $this->sendRevivalMessage($thread, $revival);
                    }
                } catch (Exception $exception) {
                    $this->logger->info($log_indentation . $exception->getMessage());
                }

            }
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
        $log_indentation = '------ ';
        //todo : make tag action

        $endReply = $revival->end_default_answer;
        if(!empty($endReply)){
            $this->logger->info($log_indentation .'Sending response name : ' . $endReply->name);
            $this->sendEndMessageOfRevival($thread, $endReply);
        }

        $newStatus = $revival->end_state;
        if (!$newStatus){
            $this->logger->info($log_indentation . "Updating the status's ticket " . $ticket->state . " to : " . $newStatus);
            $ticket->state = $newStatus;
        }

        $deadLine = date('Y-m-d H:i:s', time() + (24 * 3600));
        $this->logger->info($log_indentation . "Updating deadline's ticket " . $ticket->deadline->format('Y-m-d H:i:s') . " to : " . $deadLine);
        $ticket->deadline = $deadLine;

        $this->logger->info($log_indentation . 'Save ticket modification ...');
        $ticket->save();

        $this->logger->info($log_indentation . 'Stopping ticket revival ...');
        $this->stopThreadRevival($thread);
    }

    private function sendEndMessageOfRevival(Thread $thread, DefaultAnswer $endReply)
    {
        return $this->addMessageToThreads($thread, $endReply);
    }

    private function addMessageToThreads(Thread $thread, DefaultAnswer $endReply)
    {
        return;
    }

    private function stopThreadRevival(Thread $thread)
    {
        $thread->revival = null;
        $thread->revival_message_count = 0;
        $thread->revival_start_date = null;
        $thread->save();
    }

    private function sendRevivalMessage(Ticket $ticket, Thread $thread, Revival $revival)
    {
        $newMessageId = $this->addMessageToThreads($thread, $revival->default_answer);

        if ($newMessageId) {
            $ticket->state = TicketStateEnum::WAITING_CUSTOMER;
            $thread->revival_message_count = ++$thread->revival_message_count;
            $ticket->deadline = date('Y-m-d H:i:s', time() + $this->getFrequencyInSecond($revival));
        }
    }

    private function getFrequencyInSecond(Revival $revival)
    {
        return $revival->frequency * 24 * 3600;
    }

}
