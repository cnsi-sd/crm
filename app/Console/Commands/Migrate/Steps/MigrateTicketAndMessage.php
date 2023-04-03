<?php

namespace App\Console\Commands\Migrate\Steps;

use App\Console\Commands\Migrate\Tools\MagentoStores;
use App\Console\Commands\Migrate\Tools\MigrateDTO;
use App\Enums\Ticket\TicketCommentTypeEnum;
use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Enums\Ticket\TicketPriorityEnum;
use App\Enums\Ticket\TicketStateEnum;
use App\Helpers\TinyMCE;
use App\Models\Channel\Channel;
use App\Models\Channel\Order;
use App\Models\Tags\Tag;
use App\Models\Ticket\Comment;
use App\Models\Ticket\Message;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use App\Models\User\User;
use Closure;
use Illuminate\Support\Facades\DB;
use stdClass;

class MigrateTicketAndMessage extends AbstractMigrateStep
{
    private array $magentoOrders;
    private array $magentoApiMessages;
    private array $magentoAdminMessages;
    private array $orderByTickets = [];
    private array $ticketConversion = [];
    private array $users;
    private array $magentoTicketTags;
    private array $magentoPrivateComments;

    public function handle(MigrateDTO $dto, Closure $next)
    {
        Order::truncate();
        Ticket::truncate();
        Thread::truncate();
        Message::truncate();
        Comment::truncate();
        DB::table('tag_tagLists')->truncate();
        DB::table('tagLists')->truncate();

        $this->loadUsers();
        $this->loadMagentoOrders($dto);

        $this->loadMagentoCustomerApiMessages($dto);
        $this->insertCustomerApiMessages($dto);

        $this->loadMagentoAdminMessages($dto);
        $this->insertAdminMessages($dto);

        $this->loadMagentoTicketTags($dto);
        $this->insertTicketTags($dto);

        $this->loadMagentoTicketComments($dto);
        $this->insertTicketComments($dto);

        return $next($dto);
    }

    private function loadUsers()
    {
        $this->users = User::query()
            ->select('id', 'email')
            ->get()
            ->pluck('id', 'email')
            ->toArray();
    }

    private function loadMagentoOrders(MigrateDTO $dto)
    {
        $this->magentoOrders = $dto->connection->select('
            SELECT entity_id, marketplace_order_id
            FROM sales_flat_order
        ');

        $this->magentoOrders = $this->toArray($this->magentoOrders);

        $this->magentoOrders = array_column($this->magentoOrders, 'marketplace_order_id', 'entity_id');

        $this->magentoOrders = array_filter($this->magentoOrders);
    }

    private function loadMagentoCustomerApiMessages(MigrateDTO $dto)
    {
        $this->magentoApiMessages = $dto->connection->select('
            SELECT
                t.ct_id,
                t.ct_status,
                t.ct_priority,
                t.ct_deadline,
                a.email,
                m.cam_ticket_id,
                m.cam_message_id,
                m.cam_marketplace,
                m.cam_marketplace_order_id,
                m.cam_thread_id,
                m.cam_message_from,
                m.cam_message_subject,
                m.cam_message_description,
                m.cam_message_date
            FROM crm_api_message m
            INNER JOIN crm_ticket t ON t.ct_id = m.cam_ticket_id
            LEFT JOIN admin_user a ON a.user_id = t.ct_manager
        ');
    }

    private function insertCustomerApiMessages(MigrateDTO $dto)
    {
        $mapping = MagentoStores::getApiMarketplaceMapping();
        $inserted = 0;

        foreach ($this->magentoApiMessages as $magentoApiMessage) {
            $channel = $mapping[$magentoApiMessage->cam_marketplace];
            $threadNumber = $magentoApiMessage->cam_thread_id ?: $magentoApiMessage->cam_marketplace_order_id;
            $author = in_array($magentoApiMessage->cam_message_from, ['Operator', 'Service client Cdiscount', 'CALLCENTER']) ? TicketMessageAuthorTypeEnum::OPERATOR : TicketMessageAuthorTypeEnum::CUSTOMER;

            $order = Order::getOrder($magentoApiMessage->cam_marketplace_order_id, $channel);
            $ticket = Ticket::firstOrCreate(
                [
                    'order_id'   => $order->id,
                    'channel_id' => $channel->id,
                ],
                [
                    'state'    => TicketStateEnum::isValid($magentoApiMessage->ct_status) ? $magentoApiMessage->ct_status : TicketStateEnum::CLOSED,
                    'priority' => TicketPriorityEnum::isValid('P' . $magentoApiMessage->ct_priority) ? 'P' . $magentoApiMessage->ct_priority : TicketPriorityEnum::P1,
                    'deadline' => $magentoApiMessage->ct_deadline,
                    'user_id'  => $this->users[$magentoApiMessage->email] ?? $channel->user_id,
                ],
            );
            $thread = Thread::getOrCreateThread(
                $ticket,
                $threadNumber,
                $magentoApiMessage->cam_message_subject
            );

            $this->orderByTickets[$magentoApiMessage->cam_ticket_id] = $order;
            $this->ticketConversion[$magentoApiMessage->ct_id] = $ticket;

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
                ]
            );

            $inserted++;
        }

        $dto->logger->info($inserted . ' API messages inserted');
    }

    private function loadMagentoAdminMessages(MigrateDTO $dto)
    {
        $this->magentoAdminMessages = $dto->connection->select('
            SELECT
                ct_id,
                ct_subject,
                ct_object_id,
                a.email,
                s.code as store_code,
                ctm_ticket_id,
                ctm_author,
                ctm_content,
                ctm_admin_user_id,
                ctm_source_type,
                ctm_created_at
            FROM crm_ticket_message
            INNER JOIN crm_ticket ON ct_id = ctm_ticket_id
            INNER JOIN core_store s ON s.store_id = ct_store_id
            LEFT JOIN admin_user a ON a.user_id = ctm_admin_user_id
            WHERE ctm_author = "admin"
            OR (ctm_author = "customer" AND ctm_content_type = "mail")
        ');
    }

    private function insertAdminMessages(MigrateDTO $dto)
    {
        $inserted = 0;

        foreach ($this->magentoAdminMessages as $magentoAdminMessage) {
            if (isset($this->ticketConversion[$magentoAdminMessage->ct_id])) {
                $ticket = $this->ticketConversion[$magentoAdminMessage->ct_id];
            } else {
                $order = $this->getTicketOrder($magentoAdminMessage->ctm_ticket_id, $magentoAdminMessage->store_code, $magentoAdminMessage->ct_object_id);
                if (!$order) {
                    $dto->logger->error('Can not find order for ticket #' . $magentoAdminMessage->ctm_ticket_id);
                    continue;
                }

                $channel = $order->channel;
                $ticket = Ticket::getTicket($order, $channel);
                $this->ticketConversion[$magentoAdminMessage->ct_id] = $ticket;
            }

            $thread = Thread::query()
                ->where('ticket_id', $ticket->id)
                ->where('channel_thread_number', $magentoAdminMessage->ctm_source_type)
                ->first();

            if (!$thread) {
                $thread = Thread::query()
                    ->where('ticket_id', $ticket->id)
                    ->where('name', $magentoAdminMessage->ct_subject)
                    ->first();
            }

            if (!$thread) {
                $thread = Thread::getOrCreateThread($ticket, Thread::DEFAULT_CHANNEL_NUMBER, 'Fil de discussion principal');
            }

            if ($magentoAdminMessage->email === 'support@boostmyshop.com') {
                $user_id = null;
                $author_type = TicketMessageAuthorTypeEnum::SYSTEM;
            } elseif ($magentoAdminMessage->ctm_author === 'admin') {
                $user_id = $this->users[$magentoAdminMessage->email] ?? null;
                $author_type = TicketMessageAuthorTypeEnum::ADMIN;
            } else {
                $user_id = null;
                $author_type = TicketMessageAuthorTypeEnum::CUSTOMER;
            }

            Message::insert([
                'thread_id'   => $thread->id,
                'created_at'  => $magentoAdminMessage->ctm_created_at,
                'user_id'     => $user_id,
                'author_type' => $author_type,
                'content'     => TinyMCE::toText($magentoAdminMessage->ctm_content),
            ]);

            $inserted++;
        }

        $dto->logger->info($inserted . ' mails inserted');
    }

    private function getTicketChannel(string $store_code): ?Channel
    {
        $channelMapping = MagentoStores::getStoreMapping();

        if (!isset($channelMapping[$store_code]))
            return null;

        return $channelMapping[$store_code];
    }

    private function getTicketOrder($ct_id, $store_code, $ct_object_id): ?Order
    {
        if (isset($this->orderByTickets[$ct_id]))
            return $this->orderByTickets[$ct_id];

        $ct_object_id = str_replace('order_', '', $ct_object_id);
        if (!isset($this->magentoOrders[$ct_object_id]))
            return null;

        $channel = $this->getTicketChannel($store_code);
        if (!$channel)
            return null;

        $marketplace_order_number = $this->magentoOrders[$ct_object_id];
        return Order::getOrder($marketplace_order_number, $channel);
    }

    private function loadMagentoTicketTags(MigrateDTO $dto)
    {
        $this->magentoTicketTags = $dto->connection->select('
            SELECT
                ctt_ct_id as ct_id,
                ctg_name as tag_name
            FROM crm_ticket_tag
            INNER JOIN crm_tag ON ctg_id = ctt_ctg_id
        ');
    }

    private function insertTicketTags(MigrateDTO $dto)
    {
        $tags = Tag::all()->keyBy('name');
        $inserted = 0;

        foreach ($this->magentoTicketTags as $magentoTicketTag) {
            if (!$tags->offsetExists($magentoTicketTag->tag_name))
                continue;

            if (!isset($this->ticketConversion[$magentoTicketTag->ct_id]))
                continue;

            $ticket = $this->ticketConversion[$magentoTicketTag->ct_id];
            $tag = $tags->offsetGet($magentoTicketTag->tag_name);
            if (!$ticket->hasTag($tag)) {
                $ticket->addTag($tag);
                $inserted++;
            }
        }

        $dto->logger->info($inserted . ' tags associated to tickets');
    }

    private function loadMagentoTicketComments(MigrateDTO $dto)
    {
        $this->magentoPrivateComments = $dto->connection->select('
            SELECT ct_id, ct_private_comments
            FROM crm_ticket
            WHERE ct_private_comments != ""
        ');
    }

    private function insertTicketComments(MigrateDTO $dto)
    {
        $comments = [];
        foreach ($this->magentoPrivateComments as $magentoPrivateComment) {
            if(!isset($this->ticketConversion[$magentoPrivateComment->ct_id])) {
                $dto->logger->error('Comment of ticket #' . $magentoPrivateComment->ct_id . ' can not be imported');
                continue;
            }

            $comments[] = [
                'ticket_id' => $this->ticketConversion[$magentoPrivateComment->ct_id]->id,
                'content' => TinyMCE::toText($magentoPrivateComment->ct_private_comments),
                'displayed' => true,
                'type' => TicketCommentTypeEnum::OTHERS,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $chunks = array_chunk($comments, 1000);
        foreach ($chunks as $chunk)
            Comment::insert($chunk);

        $dto->logger->info(count($comments) . ' comments inserted');
    }
}
