<?php

namespace App\Http\Controllers\Settings\Permissions;

use App\Helpers\Alert;
use App\Helpers\Builder\Table\TableBuilder;
use App\Http\Controllers\AbstractController;
use App\Models\User\Role;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends AbstractController
{
    public function list(Request $request): View
    {
        $query = Role::query();
        $table = (new TableBuilder('roles', $request))
            ->setColumns(Role::getTableColumns())
            ->setExportable(false)
            ->setQuery($query);

        return view('admin.roles.list')
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

        /** get the role authorizations*/
        $permissions = $role->getPermissions();

        return view('admin.roles.edit')
            ->with('role', $role)
            ->with('permissions', $permissions);
    }

    public function save_role(Request $request, Role $role)
    {
        // Validate request
        $request->validate([
            'name' => 'required',
        ]);

        // Set name
        $role->name      = $request->input('name');

        /** get all check authorizations */
        $permissions_inputs = $request->except( ['name', '_token', 'save_role']);

        /** Save the setting */
        $permissions = array_keys($permissions_inputs);

        $permissions = implode(';', $permissions);
        $role->permissions = $permissions;

        // Enregistrement
        $role->save();
    }
}
