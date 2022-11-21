@can('edit', App\Models\User\User::class)
    <a
        href="{{ route('edit_user', ['user' => $user]) }}"
        class="btn btn-secondary btn-sm"
        title="{{ __('app.edit') }}"
    >
        <i class="uil-edit"></i>
    </a>
@endcan
