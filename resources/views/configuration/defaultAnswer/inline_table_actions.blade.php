@can('edit', $defaultAnswer)
    <a
        href="{{ route('edit_default_answer', ['defaultAnswer' => $defaultAnswer]) }}"
        class="btn btn-secondary btn-sm"
        title="{{ __('app.edit') }}"
    >
        <i class="uil-edit"></i>
    </a>

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
