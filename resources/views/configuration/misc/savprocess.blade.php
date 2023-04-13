@extends('configuration.misc.home', ["page_title"=> __('app.config.misc.savprocess') ])

@section('misc_content')
    <div class="col-4">
        <div class="card">
            <div class="card-header">
                {{ __('app.config.config') }}
            </div>

            <div class="card-body">
                <form class="form-horizontal" method="post">
                    @csrf

                    @include('form_components.tag_select', [
                        'translation' => __('app.config.misc.savprocess_tag'),
                        'name' => 'savprocesscomplete_tag_id',
                        'value' => old('savprocesscomplete_tag_id', setting('savprocesscomplete_tag_id')),
                    ])

                    <div class="mb-3">
                        <label for="late_order_tag_id" class="form-label">
                            {{ __('app.config.misc.savprocess_stop_revival') }}
                        </label>
                        <select name="savprocess_stop_revival_ids[]" id="savprocess_stop_revival_ids" class="form-control form-select" required multiple>
                            @foreach(\App\Models\Ticket\Revival\Revival::all() as $revival)
                                <option value="{{ $revival->id }}" @selected(in_array($revival->id, explode(',',setting('savprocess_stop_revival_ids'))))>
                                    {{ $revival->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @include('form_components.submit')
                </form>
            </div>
        </div>
@endsection
