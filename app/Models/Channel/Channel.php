<?php

namespace App\Models\Channel;

use App\Enums\ColumnTypeEnum;
use App\Enums\FixedWidthEnum;
use App\Helpers\Builder\Table\TableColumnBuilder;
use App\Models\Tags\Tag;
use App\Models\Ticket\Revival\Revival;
use App\Models\Ticket\Ticket;
use App\Models\User\User;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property array $ext_names
 * @property ?string $order_url
 * @property int $user_id
 * @property bool $is_active
 * @property Datetime $created_at
 * @property Datetime $updated_at
 *
 * @property User $user
 * @property DefaultAnswer[] $defaultAnswers
 * @property Revival $revivals
 * @property Ticket[] $tickets
 * @property Order[] $orders
 * @property Tag[] $tags
 */
class Channel extends Model
{
    protected $fillable = [
        'name',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'ext_names' => 'json',
    ];

    public function getSnakeName(): array|string
    {
        return str_replace('.', '_', $this->name);
    }
    public function defaultAnswers(): BelongsToMany
    {
        return $this->belongsToMany(DefaultAnswer::class)->orderBy('name');
    }

    public function revivals(): BelongsToMany
    {
        return $this->belongsToMany(Revival::class)->orderBy('name');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public static function getChannelsNames(): array
    {
        return self::query()
            ->where('is_active', true)
            ->orderBy('name', 'ASC')
            ->pluck('name', 'id')
            ->toArray();
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public static function getByExtName(string $ext_name) : Channel|null
    {
        return Channel::query()
            ->where('ext_names', 'LIKE', '%' . $ext_name . '%')
            ->where('is_active', true)
            ->first();
    }

    /**
     * @throws Exception
     */
    public static function getByName(string $name, bool $filter_active = true) : Channel
    {
        /** @var Channel $channel */
        $channel = self::query()->where('name', $name)->first();
        if(!$channel)
            throw new Exception('Channel `' . $name . '` does not exists');

        if($filter_active && !$channel->is_active)
            throw new Exception('Channel `' . $name . '` is not active');

        return $channel;
    }

    /**
     * @return Collection|DefaultAnswer[]
     */
    public function getAuthorizedDefaultAnswers(array $listeId = null): Collection
    {
        $query = DefaultAnswer::query()
            ->select('default_answers.*')
            ->leftJoin('channel_default_answer', 'channel_default_answer.default_answer_id', 'default_answers.id')
            ->whereNull('channel_default_answer.id');

        if ($listeId)
            $query->orWhereIn('channel_default_answer.channel_id', $listeId);
        else
            $query->orWhere('channel_default_answer.channel_id', $this->id);

        return $query->groupBy('default_answers.id')
            ->orderBy('default_answers.name')
            ->get();
    }

    /**
     * @return Collection|Tag[]
     */
    public function getAuthorizedTags(array $listeId = null): Collection
    {
        $query = Tag::query()
            ->select('tags.*')
            ->leftJoin('channel_tags', 'channel_tags.tag_id', 'tags.id')
            ->whereNull('channel_tags.id');

        if ($listeId)
            $query->orWhereIn('channel_tags.channel_id', $listeId);
        else
            $query->orWhere('channel_tags.channel_id', $this->id);

        return $query->groupBy('tags.id')
                ->orderBy('tags.name')
                ->get();

    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getTableColumns(): array
    {
        $columns = [];

        $columns[] = TableColumnBuilder::id()
            ->setSearchable(false);
        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.channel.name'))
            ->setKey('name');
        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.channel.ext_names'))
            ->setCallback(function(Channel $channel) {
                return implode(', ', $channel->ext_names);
            })
            ->setKey('ext_names');
        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.ticket.owner'))
            ->setType(ColumnTypeEnum::SELECT)
            ->setOptions(User::getUsersNames())
            ->setCallback(function (Channel $channel) {
                return $channel->user->__toString();
            })
            ->setKey('user_id')
            ->setSortable(true);
        $columns[] = TableColumnBuilder::boolean()
                ->setFixedWidth(FixedWidthEnum::XL)
                ->setLabel(__('app.channel.is_active'))
                ->setKey('is_active')
                ->setSortable(false);
        $columns[] = TableColumnBuilder::actions()
            ->setCallback(function (Channel $channel) {
                return view('admin.channel.inline_table_actions')
                    ->with('channel', $channel);
            });

        return $columns;
    }
}
