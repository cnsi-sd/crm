<?php

namespace App\Models\Ticket\Revival;

use App\Enums\ColumnTypeEnum;
use App\Enums\Ticket\TicketStateEnum;
use App\Helpers\Builder\Table\TableColumnBuilder;
use App\Models\Channel\Channel;
use App\Models\Channel\DefaultAnswer;
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
 * @property int $max_revival
 * @property string $end_state
 * @property DateTime $created_at
 * @property Datetime $updated_at
 *
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
        'default_answer_id',
        'max_revival',
        'end_default_answer_id',
        'end_state',
        'created_at',
        'updated_at'
    ];

    public function isChannelSelected(Channel $channel)
    {
        return $this->channels->keyBy('id')->has($channel->id);
    }

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
        return $this->belongsToMany(Channel::class, 'channel_revivals', 'revival_id', 'channel_id');
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
            ->setLabel(trans_choice('app.revival.channel', 1))
            ->setCallback(function (Revival $revival) {
                $channels = $revival->channels->pluck('name')->toArray();
                return implode(", ", $channels);
            })
            ->setKey('default_answer');
        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.revival.frequency'))
            ->setKey('frequency');
        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.revival.max_revival'))
            ->setKey('max_revival');
        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.revival.default_answer'))
            ->setType(ColumnTypeEnum::TEXT)
            ->setCallback(function (Revival $revival) {
                return $revival->default_answer->name;
            })
            ->setKey('default_answer_id');
        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.revival.end_default_answer'))
            ->setCallback(function (Revival $revival) {
                return $revival->end_default_answer->name;
            })
            ->setKey('end_default_answer_id');
        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.revival.end_state'))
            ->setType(ColumnTypeEnum::SELECT)
            ->setOptions(TicketStateEnum::getList())
            ->setKey('end_state');
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
