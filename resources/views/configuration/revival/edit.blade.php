@extends('layouts.horizontal', ["page_title"=> isset($revival->id) ? trans_choice('app.edit', 1) : trans_choice('app.create', 1)])

@section('content')
    <form action="{{ isset($revival->id) ? route('edit_revival', [$revival->id]) : route('create_revival') }}"
          method="POST">
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
                                <label for="name">
                                    {{__('app.revival.name')}}
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
                                <label for="frequency">
                                    {{__('app.revival.frequency')}}
                                    <span class="required_field">*</span>
                                </label>
                                <input
                                    type="number"
                                    name="frequency"
                                    class="form-control form-control-sm"
                                    min="1"
                                    value="{{ old('name', $revival->frequency ?? '') }}"
                                    required
                                />
                            </div>
                            <div class="form-group mb-3">
                                <label for="max_revival">
                                    {{__('app.revival.max_revival')}}
                                    <span class="required_field">*</span>
                                </label>
                                <input
                                    type="number"
                                    name="max_revival"
                                    min="1"
                                    class="form-control form-control-sm"
                                    value="{{ old('name', $revival->max_revival ?? '') }}"
                                    required
                                />
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="m-2 ">
                            <label for="channels">
                                {{__('app.revival.select_channel')}}
                            </label>
                            <select
                                name="channels[]"
                                id="channels"
                                class="form-control form-control-sm form-select no-sort"
                                multiple
                            >
                                @foreach(\App\Models\Channel\Channel::all() as $channel)
                                    <option value="{{$channel->id}}" @selected($revival->isChannelAuthorized($channel))>
                                        {{$channel->name}}
                                    </option>
                                @endforeach
                            </select>
                            <span class="help-block">
                                <small>
                                    {{ __('app.revival.select_all_channel') }}
                                </small>
                            </span>
                        </div>
                        <div class="m-2">
                            <label for="default_answer_id">
                                {{trans_choice('app.default_answer.default_answer', 1)}}
                                <span class="required_field">*</span>
                            </label>
                            <select
                                name="default_answer_id"
                                id="select-default_answer_id"
                                class="form-control form-control-sm form-select"
                                required
                            >
                                <option value=""></option>
                                @foreach(\App\Models\Channel\DefaultAnswer::all() as $answer)
                                    <option value="{{$answer->id}}" @selected($revival->isAnswerSelected($answer))>
                                        {{$answer->name}}
                                    </option>
                                @endforeach
                            </select>

                            @if (strlen($revival?->default_answer?->content) > 160 && $revival->send_type === \App\Enums\TableBuilder\Revival\RevivalSendTypeEnum::SMS)
                                <div class="row mt-2">
                                    <div class="col">
                                        <div class="alert alert-warning" role="alert">
                                            {{ __('app.revival.warningLengthSMS',['nbMessage' => ceil(strlen($revival?->default_answer?->content) / 160)])}}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="m-2">
                            <label for="end_default_answer_id">
                                {{trans_choice('app.revival.end_default_answer', 1)}}
                                <span class="required_field">*</span>
                            </label>
                            <select
                                name="end_default_answer_id"
                                id="select-end_default_answer_id"
                                class="form-control form-control-sm form-select"
                                required
                            >
                                <option value=""></option>
                                @foreach(\App\Models\Channel\DefaultAnswer::all() as $answer)
                                    <option value="{{$answer->id}}"
                                        @selected($revival->isEndAnswerSelected($answer))>
                                        {{$answer->name}}
                                    </option>
                                @endforeach
                            </select>
                            @if (strlen($revival?->end_default_answer?->content) > 160 && $revival->send_type === \App\Enums\TableBuilder\Revival\RevivalSendTypeEnum::SMS)
                                <div class="row mt-2">
                                    <div class="col">
                                        <div class="alert alert-warning" role="alert">
                                            {{ __('app.revival.warningLengthSMS',['nbMessage' => ceil(strlen($revival->default_answer->content) / 160)])}}
                                        </div>
                                    </div>
                                </div>

                            @endif
                        </div>
                        <div class="m-2">
                            <label for="end_state">
                                {{trans_choice('app.revival.end_state', 1)}}
                                <span class="required_field">*</span>
                            </label>
                            <select
                                name="end_state"
                                id="select-end_state"
                                class="form-control form-control-sm form-select"
                                required
                            >
                                <option value=""></option>
                                @foreach(\App\Enums\Ticket\TicketStateEnum::getTranslatedList() as $key => $value)
                                    <option value="{{ $key }}"
                                        @selected($revival->isStateSelected($key))>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="m-2">
                            <label for="revivalEndTag">
                                {{trans_choice('app.revival.endTag', 1)}}
                                <span class="required_field">*</span>
                            </label>

                            <select
                                name="revivalEndTag"
                                id="select-revivalEndTag"
                                class="form-control form-control-sm form-select"
                                required
                            >
                                <option value="">-- {{trans_choice('app.revival.select_revival', 1)}} --</option>
                                @foreach( \App\Models\Tags\Tag::all() as $tag)
                                    <option value="{{ $tag->id }}"
                                        @selected($revival->end_tag_id == $tag->id)>
                                        {{ $tag->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="m-2">
                            <label for="revivalType">
                                {{trans_choice('app.revival.sendType', 1)}}
                                <span class="required_field">*</span>
                            </label>
                            <select
                                name="revivalType"
                                id="select-revivalType"
                                class="form-control form-control-sm form-select"
                                required
                            >
                                @foreach( \App\Enums\TableBuilder\Revival\RevivalSendTypeEnum::getList() as $type)
                                    <option value="{{ $type }}"
                                        @selected($revival->send_type == $type)>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-grid gap-2 mt-3">
            <button type="submit" name="save_revival" class="btn btn-primary btn-block">
                {{ __('app.save') }}
            </button>
        </div>
    </form>
@endsection
