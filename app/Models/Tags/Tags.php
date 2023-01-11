<?php

namespace App\Models\Tags;

use App\Enums\AlignEnum;
use App\Enums\ColumnTypeEnum;
use App\Helpers\Builder\Table\TableColumnBuilder;
use App\Models\Channel\Channel;
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
 */
class Tags extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'text_color',
        'background_color',
        'created_at',
        'updated_at'
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
        return $this->belongsToMany(Channel::class, 'tag_tagLists', 'tag_id', 'taglist_id');
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
            ->setCallback(function (Tags $tags) {
                return view('configuration.tags.preview')
                    ->with('tags', $tags);
            });

        $columns[] = TableColumnBuilder::actions()
            ->setCallback(function (Tags $tags) {
                return view('configuration.tags.inline_table_actions')
                    ->with('tags', $tags);
            });

        return $columns;
    }

    public function softDeleted(): ?bool
    {
        return $this->delete();
    }
}
