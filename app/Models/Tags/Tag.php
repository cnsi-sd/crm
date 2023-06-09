<?php

namespace App\Models\Tags;

use App\Enums\AlignEnum;
use App\Enums\ColumnTypeEnum;
use App\Helpers\Builder\Table\TableColumnBuilder;
use App\Models\Channel\Channel;
use App\Models\Ticket\Revival\Revival;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property string $name
 * @property string $text_color
 * @property string $background_color
 * @property boolean $is_locked
 *
 * @property Channel[] $channels
 * @property TagList[] $tagLists
 * @property Revival[] $revivals
 *
 * @property Datetime $created_at
 * @property Datetime $updated_at
 * @property DateTime $deleted_at
 */
class Tag extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'text_color',
        'background_color',
        'is_locked',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'is_locked' => 'boolean',
    ];

    public function isChannelAuthorized(Channel $channel)
    {
        return $this->channels->keyBy('id')->has($channel->id);
    }

    public function channels(): BelongsToMany
    {
        return $this->belongsToMany(Channel::class, 'channel_tags', 'tag_id', 'channel_id');
    }

    public function tagLists(): BelongsToMany
    {
        return $this->belongsToMany(TagList::class, 'tag_tagLists', 'tag_id', 'tagList_id');
    }

    public function revivals(): HasMany
    {
        return $this->hasMany(Revival::class);
    }

    public static function getTagsNames(): array
    {
        return self::query()->orderBy('name', 'ASC')
            ->pluck('name', 'id')
            ->toArray();
    }

    public function getAuthorizedChannels()
    {
        return $this->channels->count() === 0 ? Channel::all() : $this->channels;
    }

    public static function getTableColumns(): array
    {
        $columns = [];

        $columns[] = TableColumnBuilder::id()
            ->setSearchable(false);
        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.tags.view'))
            ->setKey('name')
            ->setAlign(AlignEnum::CENTER)
            ->setCallback(function (Tag $tags) {
                return view('configuration.tags.preview')
                    ->with('tags', $tags);
            });
        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.default_answer.select_channel'))
            ->setClass('w-25')
            ->setCallback(function (Tag $tag) {
                $channels = $tag->getAuthorizedChannels();
                if (count($channels) === Channel::all()->count()) {
                    return __('app.all');
                } else {
                    return $channels->pluck('name')->implode(', ');
                }
            })
            ->setKey('channels')
            ->setSortable(false)
            ->setSearchable(false);
        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.tags.is_locked'))
            ->setkey('is_locked')
            ->settype(ColumnTypeEnum::BOOLEAN);
        $columns[] = TableColumnBuilder::actions()
            ->setCallback(function (Tag $tags) {
                return view('configuration.tags.inline_table_actions')
                    ->with('tags', $tags);
            });

        return $columns;
    }

    public static function getListTagWithTickets(Collection $tickets): array
    {
        $tags = Tag::all()->keyBy('id');
        $listeTicketId = $tickets->pluck('id');
        $queryTag= Tag::query()
            ->select('tags.id as tag_id', DB::raw('count(*) as tag_count'))
            ->join('tag_tagLists', 'tag_tagLists.tag_id', '=', 'tags.id')
            ->join('tagLists', 'tagLists.id', '=', 'tag_tagLists.taglist_id')
            ->join('tickets', 'tickets.id', '=', 'tagLists.ticket_id')
            ->whereIn('tickets.id', $listeTicketId)
            ->groupBy('tag_id')
            ->orderBy('tag_count', 'DESC')
            ->orderBy('tags.name', 'ASC')
            ->get();

        $listeTag = [];
        foreach ($queryTag as $tag){
            $listeTag[$tag['tag_id']] = [$tags[$tag['tag_id']], $tag['tag_count']];
        }
        return $listeTag;
    }

    public static function getListTagByTicket($ticket): array
    {
        $listeTag = [];
        foreach ($ticket->taglists as $tagList) {
            foreach ($tagList->tags as $tag) {
                if (!array_key_exists($tag->id, $listeTag))
                    $listeTag[$tag->id] = [$tag, 1];
                else
                    $listeTag[$tag->id][1]++;
            }
        }
        return $listeTag;
    }
}
