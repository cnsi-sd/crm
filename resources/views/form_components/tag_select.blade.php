<div class="mb-3">
    <label for="{{ $name }}" class="form-label">
        {{ $translation }}
    </label>
    <select id="{{ $name }}" name="{{ $name }}" class="form-control form-select" required>
        @foreach(\App\Models\Tags\Tag::all() as $tag)
            <option value="{{ $tag->id }}" @selected($value == $tag->id)>
                {{ $tag->name }}
            </option>
        @endforeach
    </select>
</div>
