@extends('layouts.horizontal', ["page_title"=> trans_choice('app.role.role', 1)])

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                #{{ $defaultAnswer->id }}
                <form
                    action="{{ isset($defaultAnswer->id) ? route('edit_defaultAnswer', [$defaultAnswer->id]) : route('create_defaultAnswer') }}"
                    method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <div class="m-2 d-flex justify-content-around">
                            <div class="d-flex col-sm-5 m-1">
                                <label for="name">{{__('app.defaultAnswer.name')}}

                                    <span class="required_field">*</span>

                                </label>
                                <input
                                    type="text"
                                    name="name"
                                    class="form-control form-control-sm"
                                    value="{{ old('name', $defaultAnswer->name ?? '') }}"
                                    required
                                />
                            </div>
                            <div class="d-flex col-sm-5 m-1">
                                <label for="name">{{__('app.defaultAnswer.channel')}}

                                    <span class="required_field">*</span>
                                </label>
                                <select
                                    name="channel[]"
                                    id="select-mp"
                                    class="form-control form-control-sm form-select"
                                    multiple
                                    required
                                >
                                    <option value="">-- {{trans_choice('app.configuration.channel', 1)}} --</option>
                                    @foreach(\App\Models\Channel\Channel::all() as $channel)
                                        @foreach($defaultAnswer->channels as $channelUse)
                                            @if($channelUse->id === $channel->id)
                                                <option value="{{$channel->id}}" selected>{{$channel->name}}</option>
                                            @else
                                                <option value="{{$channel->id}}">{{$channel->name}}</option>
                                            @endif
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <label for="name">
                            {{__('app.defaultAnswer.content')}}
                        </label>
                        <textarea
                            class="form-control"
                            id="content"
                            name="content"
                            value=""
                            rows="10"
                        >{{ old('content', $defaultAnswer->content) }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                    </div>

                    <div class="d-grid gap-2 mt-3">
                        <button type="submit" name="save_default_answer" class="btn btn-primary btn-block">
                            {{ __('app.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ Vite::asset('resources/js/configuration/defaultAnswer.js') }}"></script>

@endsection
