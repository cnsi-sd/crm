@can('edit', \App\Models\Tags\Tag::class)
    <a
            href="{{ route('edit_tags', ['tags' => $tags]) }}"
            class="btn btn-secondary btn-sm"
            title="{{ __('app.edit') }}"
    >
        <i class="uil-edit"></i>
    </a>

    <a
            data-bs-toggle="modal"
            data-bs-target="#ModalDelete{{ $tags->id }}"
            class="btn btn-danger btn-sm"
            title="{{ __('app.delete') }}"
    >
        <i class="uil-trash-alt"></i>
    </a>
    @include('configuration.tags.modal.delete')

@endcan
