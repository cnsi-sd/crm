<?php

namespace App\Console\Commands\Migrate\Steps;

use App\Console\Commands\Migrate\Tools\MigrateDTO;
use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Helpers\TinyMCE;
use App\Models\Ticket\Message;
use App\Models\Ticket\Thread;
use Closure;
use Exception;

class MigrateTicketApiMessages extends AbstractMigrateStep
{
    public function handle(MigrateDTO $dto, Closure $next)
    {
        $magentoApiMessages = $dto->connection->select('
            SELECT
                m.cam_id,
                m.cam_ticket_id,
                m.cam_message_id,
                m.cam_marketplace_order_id,
                m.cam_thread_id,
                m.cam_message_from,
                m.cam_message_subject,
                m.cam_message_description,
                m.cam_message_date,
                t.ct_object_id,
                c.email as customer_email,
                s.code as store_code
            FROM crm_api_message m
            INNER JOIN crm_ticket t ON t.ct_id = m.cam_ticket_id
            LEFT JOIN core_store s ON s.store_id = t.ct_store_id
            LEFT JOIN customer_entity c ON c.entity_id = t.ct_customer_id
            WHERE t.ct_customer_guest_id = 0
        ');

        $inserted = 0;
        foreach ($magentoApiMessages as $magentoApiMessage) {
            try {
                $ticket = $dto->ticketFinder->findOrCreateTicket(
                    $magentoApiMessage->cam_ticket_id,
                    $magentoApiMessage->ct_object_id,
                    $magentoApiMessage->customer_email,
                    $magentoApiMessage->store_code,
                );

                $threadNumber = $magentoApiMessage->cam_thread_id ?: $magentoApiMessage->cam_marketplace_order_id; // TODO : ensure this
                $author = match ($magentoApiMessage->cam_message_from) {
                    'Operator', 'Service client Cdiscount', 'CALLCENTER' => TicketMessageAuthorTypeEnum::OPERATOR,
                    default                                              => TicketMessageAuthorTypeEnum::CUSTOMER,
                };

                $thread = Thread::getOrCreateThread(
                    $ticket,
                    $threadNumber,
                    $magentoApiMessage->cam_message_subject
                );

                Message::firstOrCreate(
                    [
                        'thread_id'              => $thread->id,
                        'channel_message_number' => $magentoApiMessage->cam_message_id,
                    ],
                    [
                        'created_at'  => $magentoApiMessage->cam_message_date,
                        'user_id'     => null,
                        'author_type' => $author,
                        'content'     => TinyMCE::toText($magentoApiMessage->cam_message_description),
                    ],
                );

                $inserted++;
            } catch (Exception $e) {
                $dto->logger->error('API message #' . $magentoApiMessage->cam_id . ' not imported : ' . $e->getMessage());
            }
        }

        $dto->logger->info($inserted . '/' . count($magentoApiMessages) . ' API messages inserted');
        return $next($dto);
    }
}
