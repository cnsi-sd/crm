@extends('layouts.horizontal', ["page_title"=> trans_choice('app.autoReply.autoReply', 2) ])

@section('content')
    <form
        method="POST"
    >
        @csrf
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            {{ __('app.autoReply.autoReplyWeek') }}
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <label>{{ __('app.autoReply.validate') }}</label>
                                </div>
                                <div class="col">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" name="autoReplyActivate" @checked( @setting('autoReplyActivate', false))>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col">
                                    <label>{{ __('app.autoReply.MessageToReply') }}</label>
                                </div>
                                <div class="col">
                                    <select
                                        name="reply"
                                        id="reply"
                                        class="form-control form-control-sm form-select"
                                        required
                                    >
                                        @foreach(\App\Models\Channel\DefaultAnswer::all() as $reply)
                                            <option value="{{$reply->id}}" @selected( @setting('autoReply') == $reply->id)>{{ $reply->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="d-grid gap-2 mt-3">
            <button type="submit" name="auto_reply" class="btn btn-primary btn-block">
                {{ __('app.save') }}
            </button>
        </div>
    </form>
@endsection
