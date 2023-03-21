@can('edit', \App\Models\Channel\SavNote::class)
    <a
            href="{{ route('edit_sav_note', ['savNote' => $savNote]) }}"
            class="btn btn-secondary btn-sm"
            title="{{ __('app.edit') }}"
    >
        <i class="uil-edit"></i>
    </a>

    <a
            data-bs-toggle="modal"
            data-bs-target="#ModalDelete{{ $savNote->id }}"
            class="btn btn-danger btn-sm"
            title="{{ __('app.delete') }}"
    >
        <i class="uil-trash-alt"></i>
    </a>
    @include('configuration.savNote.modal.delete')

@endcan
