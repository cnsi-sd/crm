<?php

namespace App\Models\Channel;

use App\Enums\ColumnTypeEnum;
use App\Helpers\Builder\Table\TableColumnBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTime;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property string $content
 * @property Channel[] $channels
 *
 * @property DateTime $created_at
 * @property DateTime $updated_at
 * @property DateTime $deleted_at
 */
class DefaultAnswers extends Model
{
    use SoftDeletes;
    protected $table = 'answers';

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

    public static function getTableColumns(): array
    {
        $columns = [];

        $columns[] = TableColumnBuilder::id()
            ->setSearchable(false);
        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.defaultAnswer.name'))
            ->setKey('name')
            ->setSortable(false);
        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.defaultAnswer.content'))
            ->setKey('content')
            ->setSortable(false);
        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.defaultAnswer.select_channel'))
            ->setType(ColumnTypeEnum::SELECT)
            ->setCallback(function (DefaultAnswers $defaultAnswer){
                $channels = $defaultAnswer->channels;
                $Channel = [];
                foreach ($channels as $channel){
                    $Channel[] = $channel->name;
                }
                return implode(", ", $Channel);
            })
            ->setKey('channels')
            ->setSortable(false);
        $columns[] = TableColumnBuilder::actions()
            ->setCallback(function (DefaultAnswers $defaultAnswer) {
                return view('configuration.defaultAnswer.inline_table_actions')
                    ->with('defaultAnswer', $defaultAnswer);
            });

        return $columns;
    }

    public function softDeleted(){
        return $this->delete();
    }

}
