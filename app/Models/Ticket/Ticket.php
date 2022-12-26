<?php

namespace App\Models\Ticket;

use App\Enums\Ticket\TicketPriorityEnum;
use App\Enums\Ticket\TicketStateEnum;
use App\Models\Channel\Channel;
use App\Models\Channel\Order;
use App\Models\User\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $channel_id
 * @property int $order_id
 * @property int $user_id
 * @property string $state
 * @property string $priority
 * @property Datetime $deadline
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
    protected $table = 'tickets';

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

    public static function getTicket(Order $order, Channel $channel)
    {
        return Ticket::firstOrCreate(
            [
                'order_id' => $order->id,
                'channel_id' => $channel->id,
            ],
            [
                'channel_id' => $channel->id,
                'order_id' => $order->id,
                'state' => TicketStateEnum::WAITING_ADMIN,
                'priority' => TicketPriorityEnum::P1,
                'deadline' => Carbon::now()->addHours(24), // TODO : JJ ou J+1
                'user_id' => $channel->user_id,
            ],
        );
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
