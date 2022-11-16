<?php

namespace App\Models\Ticket;

use App\Models\Channel\Channel;
use App\Models\Channel\Order;
use App\Models\User\User;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Date;

/**
 * @property int $id
 * @property int $channel_id
 * @property int $order_id
 * @property int $user_id
 * @property string $state
 * @property string $priority
 * @property Date $deadline
 * @property Datetime $created_at
 * @property Datetime $updated_at
 *
 * @property Thread[] $threads
 * @property Channel $channel
 * @property Order $order
 * @property User $user
 */

class Ticket extends Model
{
    protected $fillable = [
        'channel_id',
        'order_id',
        'user_id',
        'state',
        'priority',
        'deadline',
        'created_at',
        'updated_at'
    ];

    public static function createTicket(Order $order, int $channelId, string $state, string $priority, $deadline, int $user)
    {
        return Ticket::firstOrCreate([
            'order_id' => $order->id,
        ], [
            'channel_id' => $channelId,
            'order_id' => $order->id,
            'state' => $state,
            'priority' => $priority,
            'deadline' => $deadline,
            'user_id' => $user
        ]);
    }

    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
