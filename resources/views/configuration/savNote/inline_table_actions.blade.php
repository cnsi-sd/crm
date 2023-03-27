@can('read',\App\Models\Channel\SavNote::class)
    <a
        href="{{ route('show_sav_note', ['savNote' => $savNote]) }}"
        class="btn btn-secondary btn-sm"
        title="{{ __('app.show') }}"
    >
        <i class="uil-eye"></i>
    </a>
@endcan
@can('edit', \App\Models\Channel\SavNote::class)
    <a
        href="{{ route('edit_sav_note', ['savNote' => $savNote]) }}"
        class="btn btn-secondary btn-sm"
        title="{{ __('app.edit') }}"
    >
        <i class="uil-edit"></i>
    </a>
@endcan
