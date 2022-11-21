@can('edit', App\Models\User\Role::class)
    <a
        href="{{ route('edit_role', ['role' => $role]) }}"
        class="btn btn-secondary btn-sm"
        title="{{ __('app.edit') }}"
    >
        <i class="uil-edit"></i>
    </a>
@endcan
