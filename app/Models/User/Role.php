<?php

namespace App\Models\User;

use App\Enums\ColumnTypeEnum;
use App\Enums\Dashboard;
use App\Helpers\Builder\Table\TableColumnBuilder;
use DateTime;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $permissions
 * @property Datetime $created_at
 * @property Datetime $updated_at
 *
 * @property User[] $users
 */

class Role extends Model
{
    protected $fillable = [
        'name',
        'permissions',
        'created_at',
        'updated_at'
    ];

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
                return view('admin.roles.inline_table_actions')
                    ->with('role', $role);
            });

        return $columns;
    }

    public static function getById(int $role_id)
    {
        return self::query()->where('id', $role_id)->firstOrFail();
    }

    public function getPermissions(): array
    {
        return explode(';', $this->permissions);
    }

}
