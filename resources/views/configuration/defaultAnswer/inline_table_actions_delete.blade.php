@can('edit', App\Models\User\User::class)

    <a
        data-bs-toggle="modal"
        data-bs-target="#ModalDelete{{ $defaultAnswer->id }}"
        class="btn btn-danger btn-sm"
        title="{{ __('app.delete') }}"
    >
        <i class="uil-trash-alt"></i>
    </a>
    @include('configuration.defaultAnswer.modal.delete')

@endcan
