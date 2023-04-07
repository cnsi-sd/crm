@extends('layouts.horizontal', ["page_title"=> trans_choice('app.config.channel', 1)])

@section('content')
    <div class="container-fluid">
        <form action="{{ route('edit_channel', [$channel->id]) }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            {{ trans_choice('app.config.channel', 1) }} #{{ $channel->id }}
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="name">
                                    {{__('app.channel.name')}}
                                </label>
                                <input
                                    type="text"
                                    name="name"
                                    class="form-control form-control-sm"
                                    value="{{ $channel->name }}"
                                    disabled
                                />
                            </div>

                            <div class="form-group mb-3">
                                <label for="ext_names">
                                    {{ __('app.channel.ext_names') }}
                                </label>
                                <select id="ext_names" name="ext_names[]"
                                        class="form-control form-control-sm form-select" multiple>
                                    @foreach($ext_channels as $ext_channel)
                                        <option
                                            value="{{ $ext_channel }}" @selected(in_array($ext_channel, $channel->ext_names))>
                                            {{ $ext_channel }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="order_url">
                                    {{ __('app.channel.order_url') }}
                                </label>
                                <input
                                    type="text"
                                    name="order_url"
                                    class="form-control form-control-sm"
                                    value="{{ old('order_url', $channel->order_url) }}"
                                />
                            </div>

                            <div class="form-group mb-3">
                                <label for="role">{{trans_choice('app.ticket.owner', 1)}}
                                </label>
                                <select
                                    name="user"
                                    class="form-control form-control-sm form-select"
                                    disabled
                                >
                                    <option value="">-- {{trans_choice('app.ticket.owner', 1)}} --</option>
                                    @foreach(\App\Models\User\User::all() as $user)
                                        <option value="{{$user->id}}"
                                                @if($channel->user_id === $user->id) selected @endif>{{$user->__toString()}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 mt-3">
                    <button type="submit" name="save_channel" class="btn btn-primary btn-block">
                        {{ __('app.save') }}
                    </button>
                </div>
            </div>
        </form>
    </div>

@endsection
