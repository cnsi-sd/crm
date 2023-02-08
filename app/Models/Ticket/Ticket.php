<?php

namespace App\Models\Ticket;

use App\Enums\AlignEnum;
use App\Enums\ColumnTypeEnum;
use App\Enums\FixedWidthEnum;
use App\Enums\Ticket\TicketPriorityEnum;
use App\Enums\Ticket\TicketStateEnum;
use App\Helpers\Builder\Table\TableColumnBuilder;
use App\Models\Channel\Channel;
use App\Models\Channel\Order;
use App\Models\Tags\Tag;
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
 * @property int $last_thread_displayed
 * @property string $state
 * @property string $priority
 * @property Datetime $deadline
 * @property Datetime $delivery_date
 * @property string $direct_customer_email
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

    public static function getTableColumns($mode = 'all'): array
    {
        $columns = [];

        $columns[] = TableColumnBuilder::id()
            ->setSearchable(true)
            ->setSortable(true);

        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.ticket.deadline'))
            ->setType(ColumnTypeEnum::DATE)
            ->setKey('deadline')
            ->setSortable(true)
            ->setFixedWidth(FixedWidthEnum::SM)
            ->setCallback(function (Ticket $ticket) {
                return date('d/m/Y', strtotime($ticket->deadline));
            });

        if ($mode != 'user') {
            $columns[] = (new TableColumnBuilder())
                ->setLabel(__('app.ticket.owner'))
                ->setType(ColumnTypeEnum::SELECT)
                ->setOptions(User::getUsersNames())
                ->setCallback(function (Ticket $ticket) {
                    return $ticket->user->name;
                })
                ->setKey('user_id')
                ->setSortable(true);
        }

        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.ticket.subjects'))
            ->setType(ColumnTypeEnum::TEXT)
            ->setCallback(function (Ticket $ticket) {
                $displayThreads = '';
                foreach ($ticket->threads as $thread) {
                    $displayThreads .= $thread->name . "\n";
                }
                return $displayThreads;
            })
            ->setKey('deadline')
            ->setSortable(true);

        if ($mode != 'user') {
            $columns[] = (new TableColumnBuilder())
                ->setLabel(__('app.ticket.state'))
                ->setType(ColumnTypeEnum::SELECT)
                ->setOptions(TicketStateEnum::getTranslatedList())
                ->setKey('state')
                ->setSortable(true)
                ->setCallback(function (Ticket $ticket) {
                    return TicketStateEnum::getMessage($ticket->state);
                });
        }

        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.ticket.priority'))
            ->setType(ColumnTypeEnum::SELECT)
            ->setOptions(TicketPriorityEnum::getList())
            ->setKey('priority')
            ->setSortable(true);

        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.ticket.channel'))
            ->setType(ColumnTypeEnum::SELECT)
            ->setOptions(Channel::getChannelsNames())
            ->setCallback(function (Ticket $ticket) {
                return $ticket->channel->name;
            })
            ->setKey('channel_id')
            ->setSortable(true);
        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.tags.view'))
            ->setKey('tags_id')
            ->setWhereKey('tags.id')
            ->setType(ColumnTypeEnum::SELECT)
            ->setOptions(Tag::getTagsNames())
            ->setAlign(AlignEnum::CENTER)
            ->setFixedWidth(FixedWidthEnum::LG)
            ->setCallback(function (Ticket $ticket) {
                $listeTag = array();
                return view('tickets.tag.preview')
                    ->with('listTags', Tag::getListTagByThread($ticket, $listeTag, true));
            });
        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.ticket.created_at'))
            ->setType(ColumnTypeEnum::DATE)
            ->setKey('created_at')
            ->setSortable(true)
            ->setFixedWidth(FixedWidthEnum::SM)
            ->setCallback(function (Ticket $ticket) {
                return date('d/m/Y', strtotime($ticket->created_at));
            });

        $columns[] = TableColumnBuilder::actions()->setCallback(function (Ticket $ticket) {
            return view('tickets.inline_table_actions')
                ->with('ticket', $ticket);
        });

        return $columns;
    }
}
