@extends('layouts.horizontal', ["page_title"=> isset($revival->id) ? trans_choice('app.edit', 1) : trans_choice('app.create', 1)])

@section('content')
    <form
        action="{{ isset($revival->id) ? route('edit_revival', [$revival->id]) : route('create_revival') }}"
        method="POST"
    >
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            {{ isset($revival->id) ? trans_choice('app.revival.revival', 1) . " #$revival->id" : trans_choice('app.revival.revival', 1) }}
                        </div>
                        <div class="card-body">

                            @csrf
                            <div class="form-group mb-3">
                                <label for="name">{{__('app.defaultAnswer.name')}}
                                    <span class="required_field">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="name"
                                    class="form-control form-control-sm"
                                    value="{{ old('name', $revival->name ?? '') }}"
                                    required
                                />
                            </div>
                            <div class="form-group mb-3">
                                <label for="name">
                                    {{__('app.defaultAnswer.content')}}
                                </label>
                                <textarea
                                    class="form-control"
                                    id="content"
                                    name="content"
                                    value=""
                                    rows="5"
                                >{{ old('content', $revival->content) }}</textarea>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="m-2">
                            <label for="name">{{__('app.defaultAnswer.select_channel')}}

                                <span class="required_field">*</span>
                            </label>
                            {{--<select
                                name="channel[]"
                                id="select-mp"
                                class="form-control form-control-sm form-select"
                                multiple
                                required
                            >
                                <option value="">-- {{trans_choice('app.defaultAnswer.select_channel', 1)}} --
                                </option>
                                @foreach(\App\Models\Channel\Channel::all() as $channel)
                                    <option value="{{$revival->id}}"
                                            @if($revival->isChannelSelected($channel)) selected @endif>
                                        {{$channel->name}}
                                    </option>
                                @endforeach
                            </select>--}}
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="d-grid gap-2 mt-3">
            <button type="submit" name="save_default_answer" class="btn btn-primary btn-block">
                {{ __('app.save') }}
            </button>
        </div>
    </form>
@endsection
