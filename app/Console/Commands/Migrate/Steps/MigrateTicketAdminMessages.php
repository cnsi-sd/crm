<?php

namespace App\Console\Commands\Migrate\Steps;

use App\Console\Commands\Migrate\Tools\MigrateDTO;
use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Helpers\TinyMCE;
use App\Models\Ticket\Message;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use Closure;
use DateInterval;
use DateTime;
use Exception;
use Illuminate\Support\Str;

class MigrateTicketAdminMessages extends AbstractMigrateStep
{
    public function handle(MigrateDTO $dto, Closure $next)
    {
        $magentoAdminMessages = $dto->connection->select('
            SELECT
                m.ctm_id,
                t.ct_id,
                t.ct_subject,
                t.ct_object_id,
                a.email as admin_email,
                m.ctm_author,
                m.ctm_content,
                m.ctm_source_type,
                m.ctm_created_at,
                c.email as customer_email,
                s.code as store_code
            FROM crm_ticket_message m
            INNER JOIN crm_ticket t ON t.ct_id = m.ctm_ticket_id
            LEFT JOIN core_store s ON s.store_id = t.ct_store_id
            LEFT JOIN admin_user a ON a.user_id = m.ctm_admin_user_id
            LEFT JOIN customer_entity c ON c.entity_id = t.ct_customer_id
            WHERE t.ct_customer_guest_id = 0
            AND (
                m.ctm_author = "admin"
                OR (m.ctm_author = "customer" AND m.ctm_content_type = "mail")
            )
        ');

        $inserted = 0;
        foreach ($magentoAdminMessages as $magentoAdminMessage) {
            try {
                $ticket = $dto->ticketFinder->findOrCreateTicket(
                    $magentoAdminMessage->ct_id,
                    $magentoAdminMessage->ct_object_id,
                    $magentoAdminMessage->customer_email,
                    $magentoAdminMessage->store_code,
                );

                // Add one hour to magento dates
                $message_date = new DateTime($magentoAdminMessage->ctm_created_at);
                $message_date->add(new DateInterval('PT1H'));

                $thread_number = $this->getThreadNumber($ticket, $magentoAdminMessage->ctm_source_type, $message_date);
                $thread = Thread::getOrCreateThread($ticket, $thread_number, Thread::DEFAULT_NAME);

                // Manage author and author type
                if ($magentoAdminMessage->admin_email === 'support@boostmyshop.com') {
                    $user_id = null;
                    $author_type = TicketMessageAuthorTypeEnum::SYSTEM;
                } elseif ($magentoAdminMessage->ctm_author === 'admin') {
                    $user_id = $this->users[$magentoAdminMessage->admin_email] ?? null;
                    $author_type = TicketMessageAuthorTypeEnum::ADMIN;
                } else {
                    $user_id = null;
                    $author_type = TicketMessageAuthorTypeEnum::CUSTOMER;
                }

                // Insert message
                Message::insert([
                    'thread_id'   => $thread->id,
                    'created_at'  => $message_date,
                    'user_id'     => $user_id,
                    'author_type' => $author_type,
                    'content'     => TinyMCE::toText($magentoAdminMessage->ctm_content),
                ]);

                $inserted++;
            } catch (Exception $e) {
                $dto->logger->error('Admin message #' . $magentoAdminMessage->ctm_id . ' not imported : ' . $e->getMessage());
            }
        }

        $dto->logger->info($inserted . '/' . count($magentoAdminMessages) . ' Admin messages inserted');
        return $next($dto);
    }

    private function getThreadNumber(Ticket $ticket, string $ctm_source_type, DateTime $ctm_created_at)
    {
        $threads = $ticket->threads;

        // Identifier les ctm_source_type à 9 chiffres comme étant des ID de discussion Cdiscount (exemple 263727689)
        if (preg_match('/\d{9}/', $ctm_source_type))
            return $ctm_source_type; // TODO : convert to new Cdiscount IDs

        // Thread par défaut pour tous les channels qui ne gèrent pas les fils de discussion
        if (Str::contains($ctm_source_type, ['fnac', 'icoza', 'mail']))
            return Thread::DEFAULT_CHANNEL_NUMBER;

        // Identifier les thread/customer comme des discussions Rakuten.
        // On récupère la fin pour chercher un thread déjà créé.
        if (Str::contains($ctm_source_type, ['thread/customer'])) {
            $thread_number = str_replace('thread/customer/', '', $ctm_source_type);
            foreach ($threads as $thread) {
                if (str_starts_with($thread->channel_thread_number, $thread_number)) {
                    return $thread->channel_thread_number;
                }
            }
        }

        if($threads->count() === 1) {
            return $threads->first()->channel_thread_number;
        }
        elseif($threads->count() > 1) {
            // Si un ou plusieurs threads existent, on va essayer de trouver le message auquel on répond (par date)
            // Et on ajoute notre message sur le même thread
            $previousMessage = Message::query()
                ->select('ticket_thread_messages.*')
                ->join('ticket_threads', 'ticket_threads.id', 'ticket_thread_messages.id')
                ->where('ticket_threads.ticket_id', $ticket->id)
                ->where('ticket_thread_messages.created_at', '<', $ctm_created_at)
                ->orderBy('ticket_thread_messages.created_at', 'DESC')
                ->first();

            if($previousMessage) {
                return $previousMessage->thread->channel_thread_number;
            }
        }

        // En dernier lieu, si le ticket n'a aucun thread, on utilise le thread par défaut.
        // (Si le ticket n'a aucun thread ça veut dire qu'on n'a importé aucun crm_api_message, donc aucun message API, donc pas de gestion des Fils de discussions)
        return Thread::DEFAULT_CHANNEL_NUMBER;
    }
}
