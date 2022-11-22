<?php

namespace App\Models\Channel;

use App\Models\Ticket\Ticket;
use DateTime;
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
 * @property Ticket[] $tickets
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

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }
}
