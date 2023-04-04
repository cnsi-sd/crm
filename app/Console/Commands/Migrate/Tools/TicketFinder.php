<?php

namespace App\Console\Commands\Migrate\Tools;

use App\Enums\Ticket\TicketPriorityEnum;
use App\Enums\Ticket\TicketStateEnum;
use App\Models\Channel\Channel;
use App\Models\Channel\Order;
use App\Models\Ticket\Ticket;
use App\Models\User\User;
use Exception;
use Illuminate\Database\Connection;

class TicketFinder
{
    /** @var array */
    private array $users;

    /** @var array */
    private array $marketplaceOrderIdsByEntityId;

    /** @var array */
    private array $storesByMarketplaceOrderIds;

    /** @var array */
    private array $marketplaceOrderIdsByEmail;

    private Connection $connection;

    /**
     * Conversion des tickets
     * En clé : l'id ticket magento, en valeur : l'instance du ticket CRM
     * @var array
     */
    public static array $ticketsByMagentoId = [];

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->loadUsers();
        $this->loadMagentoOrders();
    }

    private function loadUsers(): void
    {
        $this->users = User::query()
            ->select('id', 'email')
            ->get()
            ->pluck('id', 'email')
            ->toArray();
    }

    private function loadMagentoOrders(): void
    {
        // Associative array (entity_id => marketplace_order_id)
        $data = $this->connection->select('
            SELECT entity_id, marketplace_order_id
            FROM sales_flat_order
        ');
        $data = json_decode(json_encode($data), 1);
        $data = array_column($data, 'marketplace_order_id', 'entity_id');
        $this->marketplaceOrderIdsByEntityId = array_filter($data);

        // Associative array (user_email => marketplace_order_id[])
        $datas = $this->connection->select('
            SELECT c.email, o.marketplace_order_id
            FROM sales_flat_order o
            INNER JOIN customer_entity c ON c.entity_id = o.customer_id
        ');
        $this->marketplaceOrderIdsByEmail = [];
        foreach ($datas as $data) {
            if (!isset($this->marketplaceOrderIdsByEmail[$data->email])) {
                $this->marketplaceOrderIdsByEmail[$data->email] = [];
            }

            $this->marketplaceOrderIdsByEmail[$data->email][] = $data->marketplace_order_id;
        }

        // Associative array (marketplace_order_id => store_code)
        $data = $this->connection->select('
            SELECT s.code, o.marketplace_order_id
            FROM sales_flat_order o
            INNER JOIN core_store s ON s.store_id = o.store_id
        ');
        $data = json_decode(json_encode($data), 1);
        $data = array_column($data, 'code', 'marketplace_order_id');
        $this->storesByMarketplaceOrderIds = array_filter($data);
    }

    public function findOrCreateTicket(int $ct_id, ?string $ct_object_id, ?string $customer_email, ?string $store_code): Ticket
    {
        if (isset(self::$ticketsByMagentoId[$ct_id])) {
            return self::$ticketsByMagentoId[$ct_id];
        }

        // Identify marketplace order number
        $marketplace_order_number = $this->getMarketplaceOrderNumber($ct_object_id, $customer_email);

        // Identify channel
        $channel = $this->getChannel($store_code, $marketplace_order_number);

        // Get or create order
        $order = Order::getOrder($marketplace_order_number, $channel);

        if($order->tickets->count() > 0) {
            $ticket = $order->tickets->first();
        }
        else {
            $ticket = $this->createTicket($ct_id, $order);
        }

        // Save public mapping
        self::$ticketsByMagentoId[$ct_id] = $ticket;
        return $ticket;
    }

    protected function createTicket(int $ct_id, Order $order): Ticket
    {
        // Get ticket additional data
        $magentoTicket = $this->connection->selectOne('
            SELECT
                t.ct_status,
                t.ct_priority,
                t.ct_deadline,
                a.email as admin_email
            FROM crm_ticket t
            LEFT JOIN admin_user a ON a.user_id = t.ct_manager
            WHERE t.ct_id = ' . $ct_id . '
        ');

        // Manage state
        $state = match ($magentoTicket->ct_status) {
            'waiting_for_admin'  => TicketStateEnum::WAITING_ADMIN,
            'waiting_for_client' => TicketStateEnum::WAITING_CUSTOMER,
            'closed'             => TicketStateEnum::CLOSED,
            default              => throw new Exception('Unknown status, got `' . $magentoTicket->ct_status . '`'),
        };

        // Manager priority
        $priority = 'P' . $magentoTicket->ct_priority;
        $priority = TicketPriorityEnum::isValid($priority) ? $priority : TicketPriorityEnum::P2;

        // Create ticket
        $ticket = new Ticket();
        $ticket->fill([
            'order_id'   => $order->id,
            'channel_id' => $order->channel_id,
            'state'      => $state,
            'priority'   => $priority,
            'deadline'   => $magentoTicket->ct_deadline,
            'user_id'    => $this->users[$magentoTicket->admin_email] ?? $order->channel->user_id,
        ]);
        $ticket->save();

        return $ticket;
    }

    protected function getMarketplaceOrderNumber(?string $ct_object_id, ?string $customer_email): string
    {
        // try to find order following the relation with ct_object_id
        if ($ct_object_id) {
            $ct_object_id = str_replace('order_', '', $ct_object_id);
            if (!isset($this->marketplaceOrderIdsByEntityId[$ct_object_id]))
                throw new Exception('Order not found : unknown entity id');

            return $this->marketplaceOrderIdsByEntityId[$ct_object_id];
        } // try to find order regarding the user email
        elseif ($customer_email) {
            if (!isset($this->marketplaceOrderIdsByEmail[$customer_email]))
                throw new Exception("Order not found : user has no orders");

            if (count($this->marketplaceOrderIdsByEmail[$customer_email]) > 1)
                throw new Exception("Order not found : user has multiple orders");

            return $this->marketplaceOrderIdsByEmail[$customer_email][0];
        } else {
            throw new Exception("Order not found : undefined ct_object_id and customer_email");
        }
    }

    protected function getChannel(?string $store_code, string $marketplace_order_number): Channel
    {
        $mapping = MagentoStores::getStoreMapping();
        if ($store_code && isset($mapping[$store_code])) {
            return $mapping[$store_code];
        } elseif (isset($this->storesByMarketplaceOrderIds[$marketplace_order_number])) {
            $order_store_code = $this->storesByMarketplaceOrderIds[$marketplace_order_number];
            if (isset($mapping[$order_store_code])) {
                return $mapping[$order_store_code];
            }
        }

        throw new Exception('Unknown store, got `' . $store_code . '`');
    }
}
