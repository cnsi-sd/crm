<?php

namespace App\Models\Channel;

use App\Enums\ColumnTypeEnum;
use App\Helpers\Builder\Table\TableColumnBuilder;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTime;
use Illuminate\Support\Facades\DB;

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
class DefaultAnswer extends Model
{
    use SoftDeletes;
    protected $table = 'default_answers';

    protected $fillable = [
        'name',
        'content',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function channels(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Channel::class, 'channel_default_answer', 'default_answer_id','channel_id');
    }

    public static function getTableColumns(): array
    {
        $columns = [];

        $columns[] = TableColumnBuilder::id()
            ->setSearchable(false)
            ->setSortable(false);

        $columns[] = (new TableColumnBuilder())
            ->setLabel('name')
            ->setType(ColumnTypeEnum::TEXT)
            ->setKey('name')
            ->setSortable(false);

        $columns[] = (new TableColumnBuilder())
            ->setLabel('content')
            ->setType(ColumnTypeEnum::TEXT)
            ->setKey('content')
            ->setSortable(false);

        $columns[] = (new TableColumnBuilder())
            ->setLabel('channel')
            ->setType(ColumnTypeEnum::SELECT)
            ->setCallback(function (DefaultAnswer $defaultAnswer){
                $channels = $defaultAnswer->channels;
                $renderChannel = "";
                foreach ($channels as $channel){
                    $renderChannel = $renderChannel . $channel->name .", " ;
                }
                return $renderChannel;
            })
            ->setKey('name')
            ->setSortable(false);

        $columns[] = TableColumnBuilder::actions()
            ->setCallback(function (DefaultAnswer $defaultAnswer) {
                return view('configuration.defaultAnswer.inline_table_actions')
                    ->with('defaultAnswer', $defaultAnswer);
            });

        return $columns;
    }

}
