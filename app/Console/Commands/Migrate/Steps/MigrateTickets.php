<?php

namespace App\Console\Commands\Migrate\Steps;

use App\Console\Commands\Migrate\Tools\MigrateDTO;
use App\Models\Channel\Order;
use App\Models\Ticket\Message;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use Closure;
use Exception;

class MigrateTickets extends AbstractMigrateStep
{
    protected array $results = [];

    public function handle(MigrateDTO $dto, Closure $next)
    {
        Order::truncate();
        Ticket::truncate();
        Thread::truncate();
        Message::truncate();

        $magentoTickets = $dto->connection->select('
            SELECT
                t.ct_id,
                t.ct_object_id,
                c.email as customer_email,
                s.code as store_code
            FROM crm_ticket t
            LEFT JOIN core_store s ON s.store_id = t.ct_store_id
            LEFT JOIN customer_entity c ON c.entity_id = t.ct_customer_id
            LEFT JOIN crm_ticket_message m ON m.ctm_ticket_id = t.ct_id
            LEFT JOIN crm_ticket_tag tt ON tt.ctt_ct_id = t.ct_id
            WHERE t.ct_customer_guest_id = 0
            AND (
                NOT m.ctm_id IS NULL
                OR NOT tt.ctt_id IS NULL
                OR NOT t.ct_private_comments = ""
            )
            GROUP BY t.ct_id
        ');

        $inserted = 0;
        foreach ($magentoTickets as $magentoTicket) {
            try {
                $ticket = $dto->ticketFinder->findOrCreateTicket(
                    $magentoTicket->ct_id,
                    $magentoTicket->ct_object_id,
                    $magentoTicket->customer_email,
                    $magentoTicket->store_code,
                );

                $inserted++;
            } catch (Exception $e) {
                $dto->logger->error('Ticket #' . $magentoTicket->ct_id . ' not imported : ' . $e->getMessage());
            }
        }

        $dto->logger->info($inserted . '/' . count($magentoTickets) . ' tickets inserted');
        return $next($dto);
    }
}
