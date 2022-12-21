<?php

namespace App\Models\Channel;

use App\Enums\ColumnTypeEnum;
use App\Helpers\Builder\Table\TableColumnBuilder;
use App\Models\Ticket\Revival\Revival;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTime;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property string $content
 * @property DateTime $created_at
 * @property DateTime $updated_at
 * @property DateTime $deleted_at
 *
 * @property Channel[] $channels
 * @property Revival[] $revivals
 */
class DefaultAnswer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'content',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function isChannelSelected(Channel $channel) {
        return $this->channels->keyBy('id')->has($channel->id);
    }

    public function channels(): BelongsToMany
    {
        return $this->belongsToMany(Channel::class, 'channel_default_answer', 'default_answer_id','channel_id');
    }

    public function revivals(): HasMany
    {
        return $this->hasMany(Revival::class);
    }

    public static function getTableColumns(): array
    {
        $columns = [];

        $columns[] = TableColumnBuilder::id()
            ->setSearchable(false);
        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.defaultAnswer.name'))
            ->setKey('name');
        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.defaultAnswer.content'))
            ->setKey('content');
        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.defaultAnswer.select_channel'))
            ->setCallback(function (DefaultAnswer $defaultAnswer){
                $channels = $defaultAnswer->channels->pluck('name')->toArray();
                return implode(", ", $channels);
            })
            ->setKey('channels')
            ->setSortable(false)
            ->setSearchable(false);
        $columns[] = TableColumnBuilder::actions()
            ->setCallback(function (DefaultAnswer $defaultAnswer) {
                return view('configuration.defaultAnswer.inline_table_actions')
                    ->with('defaultAnswer', $defaultAnswer);
            });

        return $columns;
    }

    public function softDeleted(){
        return $this->delete();
    }

}
