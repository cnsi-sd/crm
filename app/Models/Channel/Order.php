<?php

namespace App\Models\Channel;

use App\Helpers\Prestashop\CrmLinkGateway;
use App\Models\Ticket\Ticket;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $channel_id
 * @property string $channel_order_number
 * @property Datetime $created_at
 * @property Datetime $updated_at
 *
 * @property Ticket[]|Collection $tickets
 * @property Channel $channel
 */

class Order extends Model
{
    protected $fillable = [
        'channel_id',
        'channel_order_number',
        'created_at',
        'updated_at'
    ];

    protected ?array $_prestashopOrders = null;

    /**
     * @param string $orderId
     * @param string $channelId
     * @return Order
     */
    public static function getOrder(string $orderId, Channel $channel): Order
    {
        return Order::firstOrCreate(
            [
                'channel_order_number' => $orderId,
                'channel_id' => $channel->id,
            ],
            [
                'channel_id' => $channel->id,
                'channel_order_number' => $orderId,
            ],
        );
    }

    public function getPrestashopOrders(): ?array
    {
        if(!$this->_prestashopOrders) {
            $prestashopGateway = new CrmLinkGateway();
            $this->_prestashopOrders = $prestashopGateway->getOrderInfo($this->channel_order_number, $this->channel->ext_names);
        }

        return $this->_prestashopOrders;
    }

    public function getPrestashopExternalLink(): string
    {
        $prestashopGateway = new CrmLinkGateway();
        $externalLink = $prestashopGateway->getExternalLink();
        return $externalLink;
    }
    public function getInvoiceExternalLink(): string
    {
        $prestashopGateway = new CrmLinkGateway();
        $externalLink = $prestashopGateway->getInvoiceExternalLink();
        return $externalLink;
    }

    public function getFirstPrestashopOrder(): ?array
    {
        return $this->getPrestashopOrders() ? $this->getPrestashopOrders()[0] : null;
    }

    // TODO : This method should be in a PrestashopOrder class.
    public static function getOrderDelay(array $prestashopOrder): ?int
    {
        $now = new DateTime();
        $max_shipment_date = new DateTime($prestashopOrder['max_shipment_date']);

        if ($max_shipment_date->getTimestamp() > 0) {
            if($now < $max_shipment_date) {
                $diff = $now->diff($max_shipment_date);
                $days_diff = $diff->format('%a');
                return (int)$days_diff;
            }
            else {
                return 0; // Deadline exceeded
            }
        }

        return null;
    }

    // TODO : This method should be in a PrestashopOrder class.
    public static function getOrderCreatedSinceNbDays(array $prestashopOrder): int
    {
        $now = new DateTime();
        $order_date = new DateTime($prestashopOrder['date_add']);

        $diff = $now->diff($order_date);
        $days_diff = $diff->format('%a');

        return (int)$days_diff;
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }
}
