<div class="mb-3">
    <label for="{{ $name }}" class="form-label">
        {{ $translation }}
    </label>
    <select id="{{ $name }}" name="{{ $name }}" class="form-control form-select" required>
        @foreach(\App\Models\Channel\DefaultAnswer::all() as $reply)
            <option value="{{ $reply->id }}" @selected($value == $reply->id)>
                {{ $reply->name }}
            </option>
        @endforeach
    </select>
</div>
