@can('edit', App\Models\Channel\Channel::class)
    <a
        href="{{ route('edit_channel', ['channel' => $channel]) }}"
        class="btn btn-secondary btn-sm"
        title="{{ __('app.edit') }}"
    >
        <i class="uil-edit"></i>
    </a>
@endcan
