<?php

namespace App\Models\Ticket;

use App\Enums\AlignEnum;
use App\Enums\ColumnTypeEnum;
use App\Enums\CrmDocumentTypeEnum;
use App\Enums\FixedWidthEnum;
use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Enums\Ticket\TicketPriorityEnum;
use App\Enums\Ticket\TicketStateEnum;
use App\Helpers\Builder\Table\TableColumnBuilder;
use App\Jobs\CreateEmailThread;
use App\Models\Channel\Channel;
use App\Models\Channel\DefaultAnswer;
use App\Models\Channel\Order;
use App\Models\Tags\Tag;
use App\Models\Tags\TagList;
use App\Models\User\User;
use Carbon\Carbon;
use Cnsi\Attachments\Trait\Documentable;
use Cnsi\Searchable\Trait\Searchable;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

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
 * @property string $customer_issue
 * @property string $direct_customer_email
 * @property Datetime $created_at
 * @property Datetime $updated_at
 *
 * @property Collection|Thread[] $threads
 * @property Collection|TagList[] $tagLists
 * @property Collection|Comment[] $comments
 * @property Collection|History[] $historicals
 * @property Channel $channel
 * @property Order $order
 * @property User $user
 */

class Ticket extends Model
{
    use Searchable;
    use Documentable;

    protected $searchable = [
        'id',
        'order.channel_order_number',
    ];

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

    protected $casts = [
        'deadline' => 'date',
        'delivery_date' => 'datetime'
    ];

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

    public function tagLists(): HasMany
    {
        return $this->hasMany(TagList::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->orderBy('id', 'DESC');
    }
    public function historicals(): HasMany
    {
        return $this->hasMany(TicketHistory::class)->orderBy('id', 'DESC');
    }

    public function getShowRoute(): string
    {
        return "ticket";
    }

    public function __toString(): string
    {
        $default_name = '#' . $this->id . ' - ' . $this->order->channel_order_number . ' - ' . $this->order->channel->name;
        return $default_name;
    }


    public static function getTicket(Order $order, Channel $channel, bool $createEmailThreadSync = false): Ticket
    {
        $ticket = Ticket::firstOrNew(
            [
                'order_id' => $order->id,
                'channel_id' => $channel->id,
            ],
            [
                'state' => TicketStateEnum::OPENED,
                'priority' => TicketPriorityEnum::P1,
                'deadline' => Ticket::getAutoDeadline(),
                'user_id' => Auth::hasUser() ? Auth::id() : $channel->user_id,
            ],
        );

        // If ticket does not exist, create it (save) and dispatch the job to create the EmailThread
        if(!$ticket->exists) {
            $ticket->save();

            $method = $createEmailThreadSync ? 'dispatchSync' : 'dispatch';
            CreateEmailThread::$method($ticket);
        }

        return $ticket;
    }

    /**
     * @throws Exception
     */
    public static function getLastApiMessageByTicket($threadNumber, $channelName): Model|Builder
    {
        $channel = Channel::getByName($channelName);
        $thread = Thread::firstWhere('channel_thread_number' , $threadNumber);

        return Ticket::query()
            ->select('ticket_thread_messages.content as messageContent',
                'ticket_thread_messages.channel_message_number as messageId',
                'ticket_threads.channel_thread_number as threadId')
            ->join('ticket_threads', 'ticket_threads.ticket_id' , 'tickets.id')
            ->join('ticket_thread_messages', 'ticket_thread_messages.thread_id', 'ticket_threads.id')
            ->where('ticket_thread_messages.thread_id', $thread->id)
            ->where('tickets.channel_id', $channel->id)
            ->where('author_type', TicketMessageAuthorTypeEnum::CUSTOMER)
            ->orderBy('ticket_thread_messages.id', 'desc')
            ->firstOrFail();
    }

    public static function getTableColumns($mode = 'all'): array
    {
        $columns = [];

        $columns[] = TableColumnBuilder::id()
            ->setSearchable(true)
            ->setSortable(true)
            ->setWhereKey('tickets.id');

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
                ->setLabel(__('app.ticket.user_id'))
                ->setType(ColumnTypeEnum::SELECT)
                ->setOptions(User::getUsersNames())
                ->setCallback(function (Ticket $ticket) {
                    return $ticket->user->__toString();
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
            ->setKey('name')
            ->setWhereKey('ticket_threads.name')
            ->setSortable(true);

        if ($mode != 'user') {
            $columns[] = (new TableColumnBuilder())
                ->setLabel(__('app.ticket.state'))
                ->setType(ColumnTypeEnum::SELECT)
                ->setOptions(TicketStateEnum::getTranslatedList(false))
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
            ->setLabel(trans_choice('app.admin.channel',1))
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
                    ->with('listTags', Tag::getListTagByTicket($ticket, $listeTag));
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

    public function getOpenedDays(): string
    {
        $now = new DateTime();
        return $now->diff($this->created_at)->format("%a");
    }

    public static function getAutoDeadline(): Carbon
    {
        if (Carbon::now()->hour < 16)
            return Carbon::today();
        else
            return Carbon::tomorrow();
    }

    public function close()
    {
        $this->state = TicketStateEnum::CLOSED;
        $this->save();
    }

    protected function getAllowedDocumentTypes(): array
    {
        return [
            CrmDocumentTypeEnum::CUSTOMER_SERVICE_REPORT,
            CrmDocumentTypeEnum::CUSTOMER_SERVICE_STATION,
            CrmDocumentTypeEnum::CLIENT_BANK_ACCOUNT_NUMBER,
            CrmDocumentTypeEnum::CUSTOMER_FILING,
            CrmDocumentTypeEnum::PRODUCT_PHOTO,
            CrmDocumentTypeEnum::OTHER,
        ];
    }

    public function hasTag(Tag $tag): bool
    {
        foreach($this->tagLists as $tagList) {
            $tagExists = $tagList->tags()->where('tags.id', $tag->id)->exists();
            if($tagExists)
                return true;
        }

        return false;
    }

    public function addTag(Tag $tag, ?TagList $tagList = null)
    {
        if (is_null($tagList)) {
            if ($this->tagLists->first()) {
                $tagList = $this->tagLists->first();
            } else {
                $tagList = new TagList();
                $tagList->ticket_id = $this->id;
                $tagList->save();
            }
        }
        $tagList->addTag($tag);
        $this->save();
        $this->refresh();
    }

    public static function getTicketByOrder(Channel $channel, string $order_number)
    {
        return Ticket::select('tickets.*')
            ->join('orders','tickets.order_id', 'orders.id')
            ->where('tickets.channel_id', $channel->id)
            ->where('orders.channel_order_number', $order_number)
            ->first();
    }

    protected $historisable = [
        'state',
        'user_id',
        'deadline',
        'direct_customer_email',
        'customer_issue',
        'delivery_date'
    ];

    protected function addHistory(string $column, mixed $value): void
    {
        $historical = new History();
        $historical->user_id = auth()->id();
        $historical->type = $column;
        $historical->modification = $value;
        $historical->ticket_id = $this->id;
        $historical->save();
    }

    public function save(array $options = [])
    {
        foreach($this->historisable as $column) {
            if($this->isDirty($column)) {
                $this->addHistory($column, $this->$column);
            }
        }

        return parent::save($options);
    }
}
