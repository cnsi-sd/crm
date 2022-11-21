@extends('layouts.vertical', ["page_title"=> trans_choice('app.user.user', 1)])

@section('content')
    <div class="container-fluid">
        <form action="{{ isset($user->id) ? route('edit_user', [$user->id]) : route('create_user') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            {{ trans_choice('app.user.user', 1) }} #{{ $user->id }}
                        </div>
                        <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="name">{{__('app.user.name')}}
                                        <span class="required_field">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        name="name"
                                        class="form-control form-control-sm"
                                        value="{{ old('name', $user->name ?? '') }}"
                                        required
                                    />
                                </div>

                                <div class="form-group mb-3">
                                    <label for="email">
                                        {{ __('app.user.email') }}
                                    </label>
                                    <input
                                        type="email"
                                        name="email"
                                        class="form-control form-control-sm"
                                        value="{{ old('email', $user->email ?? '') }}"
                                    />
                                </div>

                                <div class="form-group mb-3">
                                    <label for="password">{{__('app.user.password')}}
                                        @if(!isset($user->id))
                                            <span class="required_field">*</span>
                                        @endif
                                    </label>
                                    <input
                                        type="password"
                                        name="password"
                                        class="form-control form-control-sm"
                                        @if(!isset($user->id)) required @endif
                                    />
                                    <div class="form-text">{{ __('app.user.password_help') }}</div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="role">{{trans_choice('app.role.role', 1)}}
                                        <span class="required_field">*</span>
                                    </label>
                                    <select
                                        name="role"
                                        class="form-control form-control-sm form-select"
                                        required
                                    >
                                        <option value="">-- {{trans_choice('app.role.role', 1)}} --</option>
                                        @foreach(\App\Models\User\Role::all() as $role)
                                            <option value="{{$role->id}}" @if($user->role_id === $role->id) selected @endif>{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="active">{{__('app.user.active')}}
                                        <span class="required_field">*</span>
                                    </label>
                                    <div class="form-check form-switch">
                                        <input
                                            type="checkbox"
                                            name="active"
                                            class="form-check-input"
                                            @if(old('active', $user->active ?? false )) checked @endif>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 mt-3">
                    <button type="submit" name="save_user" class="btn btn-primary btn-block">
                        {{ __('app.save') }}
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>

@endsection
