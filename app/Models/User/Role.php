<?php

namespace App\Models\User;

use App\Enums\ColumnTypeEnum;
use App\Enums\Dashboard;
use App\Helpers\Builder\Table\TableColumnBuilder;
use DateTime;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property Datetime $created_at
 * @property Datetime $updated_at
 */

class Role extends Model
{

    public static function getRolesNames(): array
    {
        return self::query()->orderBy('name', 'ASC')
            ->pluck('name', 'id')
            ->toArray();
    }

    public static function getTableColumns(): array
    {
        $columns = [];

        $columns[] = TableColumnBuilder::id()
            ->setSearchable(false)
            ->setSortable(false);

        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.role.name'))
            ->setType(ColumnTypeEnum::TEXT)
            ->setKey('name')
            ->setSortable(false);

        $columns[] = TableColumnBuilder::actions()
            ->setCallback(function (Role $role) {
                return view('settings.permissions.roles.inline_table_actions')
                    ->with('role', $role);
            });

        return $columns;
    }

    public static function getById(int $role_id)
    {
        return self::query()->where('id', $role_id)->firstOrFail();
    }

}
