@can('edit', \App\Models\Ticket\Revival\Revival::class)
    <a
            href="{{ route('edit_revival', ['revival' => $revival]) }}"
            class="btn btn-secondary btn-sm"
            title="{{ __('app.edit') }}"
    >
        <i class="uil-edit"></i>
    </a>

    <a
            data-bs-toggle="modal"
            data-bs-target="#ModalDelete{{ $revival->id }}"
            class="btn btn-danger btn-sm"
            title="{{ __('app.delete') }}"
    >
        <i class="uil-trash-alt"></i>
    </a>
    @include('configuration.revival.modal.delete')

@endcan
