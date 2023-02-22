<?php

namespace App\Models\Tags;

use App\Enums\AlignEnum;
use App\Enums\ColumnTypeEnum;
use App\Helpers\Builder\Table\TableColumnBuilder;
use App\Models\Channel\Channel;
use App\Models\Ticket\Ticket;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string $text_color
 * @property string $background_color
 *
 * @property Channel[] $channels
 * @property TagList[] $tagLists
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
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function isChannelSelected(Channel $channel)
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

    public static function getTagsNames(): array
    {
        return self::query()->orderBy('name', 'ASC')
            ->pluck('name', 'id')
            ->toArray();
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

        $columns[] = TableColumnBuilder::actions()
            ->setCallback(function (Tag $tags) {
                return view('configuration.tags.inline_table_actions')
                    ->with('tags', $tags);
            });

        return $columns;
    }

    public static function getlistTagWithTickets($tickets): array
    {
        $listeTag = array();
        foreach($tickets as $ticket){
            self::getListTagByThread($ticket, $listeTag);
        }
        return $listeTag;
    }

    public static function getListTagByThread($ticket, $listeTag, $returnList = false){
        foreach($ticket->threads as $thread) {
            foreach ($thread->taglist as $tagList) {
                foreach ($tagList->tags as $tag){
                    if (!array_key_exists($tag->name,$listeTag)){
                        $listeTag[$tag->name] = ['tag_id'=> $tag->id, 'background_color'=>$tag->background_color, 'text_color' => $tag->text_color, 'count' => 1];
                    } else {
                        $listeTag[$tag->name]['count']++;
                    }
                }
            }
        }
        if($returnList){
            return $listeTag;
        }
    }
}