<?php

namespace App\Http\Controllers\Settings\Permissions;

use App\Helpers\Alert;
use App\Helpers\Builder\Table\TableBuilder;
use App\Http\Controllers\Controller;
use App\Models\User\Role;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function list(Request $request): View
    {
        $query = Role::query();
        $table = (new TableBuilder('roles', $request))
            ->setColumns(Role::getTableColumns())
            ->setExportable(false)
            ->setQuery($query);

        return view('settings.permissions.roles.list')
            ->with('table', $table);
    }

    public function edit(Request $request, ?Role $role)
    {
        if (!$role)
            $role = new Role();

        if ($request->exists('save_role')) {
            $this->save_role($request, $role);
            Alert::toastSuccess(__('app.role.saved'));
            return redirect()->route('roles');
        }

        return view('settings.permissions.roles.edit')
            ->with('role', $role);
    }

    public function save_role(Request $request, Role $role)
    {
        // Validate request
        $request->validate([
            'name' => 'required',
        ]);

        // Set name
        $role->name      = $request->input('name');

        // Enregistrement
        $role->save();
    }
}
