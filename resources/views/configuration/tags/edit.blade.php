@extends('layouts.horizontal', ["page_title"=> isset($tags->id) ? trans_choice('app.edit', 1) : trans_choice('app.create', 1)])

@section('content')
    <form
        action="{{ isset($tags->id) ? route('edit_tags', [$tags->id]) : route('create_tags') }}"
        method="POST"
    >
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            {{ isset($tags->id) ? trans_choice('app.tags.tags', 1) . " #$tags->id" : trans_choice('app.tags.tags', 1) }}
                            @can('lock', $tags)
                                <div class="form-check form-switch float-end">
                                    <input type="checkbox" class="form-check-input" id="is_locked" name="isLocked" @if($tags->is_locked == 1) checked @endif>
                                    <label class="form-check-label" for="is_locked">{{ __('app.tags.lock') }} ?</label>
                                </div>
                            @endcan
                        </div>
                        <div class="card-body">
                            @csrf
                            <div class="row form-group mb-3">
                                <div class="col-4">
                                    <label for="name">
                                        {{__('app.tags.name')}}
                                        <span class="required_field">*</span>
                                    </label>
                                </div>
                                <div class="col-8">
                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        class="form-control form-control-sm"
                                        value="{{ old('name', $tags->name ?? '') }}"
                                        required
                                    />
                                </div>
                            </div>
                            <div class="row form-group mb-3">
                                <div class="col-4">
                                    <label for="name">
                                        {{__('app.tags.backgroundColor')}}
                                        <span class="required_field">*</span>
                                    </label>
                                </div>
                                <div class="col-8">
                                    <input
                                        type="color"
                                        id="background_color"
                                        name="background_color"
                                        class="form-control form-control-sm"
                                        min="1"
                                        value="{{ old('name', $tags->background_color ?? '#000000') }}"
                                        required
                                    />
                                </div>
                            </div>
                            <div class="row form-group mb-3">
                                <div class="col-4">
                                    <label for="name">
                                        {{__('app.tags.textColor')}}
                                        <span class="required_field">*</span>
                                    </label>
                                </div>
                                <div class="col-8">
                                    <input
                                        type="color"
                                        id="text_color"
                                        name="text_color"
                                        min="1"
                                        class="form-control form-control-sm"
                                        value="{{ old('name', $tags->text_color ?? '#ffffff') }}"
                                        required
                                    />
                                </div>
                            </div>
                            <div class="row form-group mb-3">
                                <div class="col-4">
                                    <label for="channels">{{__('app.tags.select_channel')}}</label>
                                </div>
                                <div class="col-8">
                                    <select
                                        name="channels[]"
                                        id="channels"
                                        class="form-control form-control-sm form-select no-sort"
                                        multiple
                                    >
                                        @foreach(\App\Models\Channel\Channel::all() as $channel)
                                            <option value="{{$channel->id}}" @selected($tags->isChannelAuthorized($channel))>
                                                {{$channel->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="help-block">
                                        <small>
                                            {{ __('app.tags.select_all_channel') }}
                                        </small>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="row card-body">
                            <div class="col-4">
                                <label for="name">{{__('app.tags.show')}}</label>
                            </div>
                            <div class="text-center">
                                <span id="showResult" class="tags-style"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-grid gap-2 mt-3">
            <button type="submit" name="save_tags" class="btn btn-primary btn-block">
                {{ __('app.save') }}
            </button>
        </div>
    </form>
@endsection

@section('script-bottom')
    <script src="{{ Vite::asset('resources/js/tags.js') }}"></script>
@endsection
