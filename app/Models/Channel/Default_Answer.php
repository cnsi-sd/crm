<?php

namespace App\Models\Channel;

use App\Enums\ColumnTypeEnum;
use App\Helpers\Builder\Table\TableColumnBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTime;

/**
 * @property int $id
 * @property string $name
 * @property string $content
 *
 * @property DateTime $created_at
 * @property DateTime $updated_at
 * @property DateTime $deleted_at
 */
class Default_Answer extends Model
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

        return $columns;
    }
}
