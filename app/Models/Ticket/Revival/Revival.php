<?php

namespace App\Models\Ticket\Revival;

use App\Enums\AlignEnum;
use App\Enums\ColumnTypeEnum;
use App\Enums\FixedWidthEnum;
use App\Enums\Ticket\TicketStateEnum;
use App\Helpers\Builder\Table\TableColumnBuilder;
use App\Models\Channel\Channel;
use App\Models\Channel\DefaultAnswer;
use App\Models\Tags\Tag;
use App\Models\Ticket\Thread;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property int $frequency
 * @property string $send_type
 * @property int $default_answer_id
 * @property int $max_revival
 * @property int $end_default_answer_id
 * @property string $end_state
 * @property DateTime $created_at
 * @property Datetime $updated_at
 *
 * @property Tag $tag
 * @property DefaultAnswer $default_answer
 * @property DefaultAnswer $end_default_answer
 * @property Channel[] $channels
 * @property Thread[] $threads
 */
class Revival extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'name',
        'frequency',
        'send_type',
        'default_answer_id',
        'max_revival',
        'end_tag_id',
        'end_default_answer_id',
        'end_state',
        'created_at',
        'updated_at'
    ];

    public function isAnswerSelected(DefaultAnswer $defaultAnswer): bool
    {
        return $this->default_answer_id === $defaultAnswer->id;
    }

    public function isEndAnswerSelected(DefaultAnswer $defaultAnswer): bool
    {
        return $this->end_default_answer_id === $defaultAnswer->id;
    }

    public function isStateSelected($state): bool
    {
        return $this->end_state === $state;
    }

    public function default_answer(): BelongsTo
    {
        return $this->belongsTo(DefaultAnswer::class);
    }

    public function end_default_answer(): BelongsTo
    {
        return $this->belongsTo(DefaultAnswer::class);
    }

    public function channels(): BelongsToMany
    {
        return $this->belongsToMany(Channel::class, 'channel_revival', 'revival_id', 'channel_id');
    }

    public function tags(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }

    public function isChannelAuthorized(Channel $channel)
    {
        return $this->channels->keyBy('id')->has($channel->id);
    }

    public function getAuthorizedChannels()
    {
        return $this->channels->count() === 0 ? Channel::all() : $this->channels;
    }

    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }

    public static function getTableColumns(): array
    {
        $columns = [];

        $columns[] = TableColumnBuilder::id()
            ->setSearchable(false);
        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.revival.name'))
            ->setKey('name');
        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.revival.select_channel'))
            ->setCallback(function (Revival $tag) {
                $channels = $tag->getAuthorizedChannels();
                if (count($channels) === Channel::all()->count()) {
                    return __('app.all');
                } else {
                    return $channels->pluck('name')->implode(', ');
                }
            })
            ->setKey('default_answer');
        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.revival.frequency'))
            ->setKey('frequency');
        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.revival.max_revival'))
            ->setKey('max_revival');
        $columns[] = (new TableColumnBuilder())
            ->setLabel(trans_choice('app.default_answer.default_answer', 1))
            ->setType(ColumnTypeEnum::TEXT)
            ->setCallback(function (Revival $revival) {
                return $revival->default_answer->name;
            })
            ->setKey('default_answer_id');
        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.revival.end_default_answer'))
            ->setCallback(function (Revival $revival) {
                return !is_null($revival->end_default_answer_id) ? $revival->end_default_answer->name : __('app.revival.no_end_default_answer');
            })
            ->setKey('end_default_answer_id');
        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.revival.end_state'))
            ->setType(ColumnTypeEnum::SELECT)
            ->setOptions(TicketStateEnum::getTranslatedList(false))
            ->setCallback(function (Revival $revival) {
                return !is_null($revival->end_state) ? TicketStateEnum::getMessage($revival->end_state) : __('app.revival.no_end_state');
            })
            ->setKey('end_state');
        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.tags.view'))
            ->setKey('tags_id')
            ->setWhereKey('tags.id')
            ->setType(ColumnTypeEnum::SELECT)
            ->setOptions(Tag::getTagsNames())
            ->setAlign(AlignEnum::CENTER)
            ->setFixedWidth(FixedWidthEnum::LG)
            ->setCallback(function (Revival $revival) {
                return !is_null($revival->end_tag_id) ? view('configuration.revival.tag.preview')
                    ->with('tag', Tag::find($revival->end_tag_id)) : __('app.revival.no_end_tag');
            });
        $columns[] = TableColumnBuilder::actions()
            ->setCallback(function (Revival $revival) {
                return view('configuration.revival.inline_table_actions')
                    ->with('revival', $revival);
            });

        return $columns;
    }

    public function softDeleted()
    {
        return $this->delete();
    }
}
