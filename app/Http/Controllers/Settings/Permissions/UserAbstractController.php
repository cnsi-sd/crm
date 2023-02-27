<?php

namespace App\Http\Controllers\Settings\Permissions;

use App\Enums\ColumnTypeEnum;
use App\Enums\FixedWidthEnum;
use App\Helpers\Alert;
use App\Helpers\Builder\Table\TableBuilder;
use App\Helpers\Builder\Table\TableColumnBuilder;
use App\Http\Controllers\AbstractController;
use App\Models\User\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use function __;
use function redirect;
use function view;

class UserAbstractController extends AbstractController
{
    public function list(Request $request): View
    {
        $query = User::query();
        $table = (new TableBuilder('users', $request))
            ->setColumns(User::getTableColumns())
            ->setExportable(false)
            ->setQuery($query);

        return view('admin.users.list')
            ->with('table', $table);
    }

    /**
     * @param Request $request
     * @param User|null $user
     * @return View|RedirectResponse
     */
    public function edit(Request $request, ?User $user)
    {
        if (!$user)
            $user = new User();

        if ($request->exists('save_user')) {
            $this->save_user($request, $user);
            Alert::toastSuccess(__('app.user.saved'));
            return redirect()->route('users');
        }

        return view('admin.users.edit')
            ->with('user', $user);
    }

public function save_user(Request $request, User $user)
{
    // Validate request
    $validation_rules = [
        'name'          => 'required',
        'email'         => ['nullable', 'email'],
        'role'          => 'required',
    ];
    if (!$user->exists) {
        $validation_rules['password'] = ['required', Password::default()];
    }
    $request->validate($validation_rules);

    // Set name, email, phone number
    $user->name = $request->input('name');
    $user->email = $request->input('email');
    $user->role_id = $request->input('role');
    $user->active = $request->input('active') === 'on';

    // Set password
    $password = $request->input('password');
    if (!empty($password)) {
        $user->password = Hash::make($request->input('password'));
    }

    // Enregistrement
    $user->save();

}
}
