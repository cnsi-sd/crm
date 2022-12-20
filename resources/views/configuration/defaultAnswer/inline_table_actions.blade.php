@can('edit', App\Models\Channel\DefaultAnswers::class)
    <a
            href="{{ route('edit_defaultAnswer', ['defaultAnswer' => $defaultAnswer]) }}"
            class="btn btn-secondary btn-sm"
            title="{{ __('app.edit') }}"
    >
        <i class="uil-edit"></i>
    </a>
@endcan
@can('edit', App\Models\Channel\DefaultAnswers::class)

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
