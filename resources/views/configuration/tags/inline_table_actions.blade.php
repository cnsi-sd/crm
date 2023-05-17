@can('edit', \App\Models\Tags\Tag::class)
    @if(\Illuminate\Support\Facades\Auth::user()->hasPermission(\App\Enums\PermissionEnum::TAG_EDIT_LOCKED) || !$tags->is_locked)
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
    @endif
@endcan
