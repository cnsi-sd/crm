@extends('configuration.misc.home', ["page_title"=> __('app.config.misc.incidents') ])

@section('misc_content')
    <div class="col-4">
        <div class="card">
            <div class="card-header">
                {{ __('app.config.config') }}
            </div>

            <div class="card-body">
                <form class="form-horizontal" method="post">
                    @csrf

                    <div class="mb-3">
                        <label for="late_order_tag_id" class="form-label">
                            {{ __('app.config.misc.incident_tag') }}
                        </label>
                        <select name="incident_tag_id" class="form-control form-select" required>
                            @foreach(\App\Models\Tags\Tag::all() as $tag)
                                <option value="{{ $tag->id }}" @selected(setting('incident_tag_id') == $tag->id)>
                                    {{ $tag->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary" name="save">{{ __('app.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
@endsection
