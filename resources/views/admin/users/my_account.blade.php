@extends('layouts.horizontal', ["page_title"=> trans_choice('app.user.user', 1)])

@section('content')
    <div class="container-fluid">
        <form action="{{ route('my_account') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            {{ trans_choice('app.user.my_informations', 1) }} #{{ $user->id }}
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="lastname">
                                            {{ __('app.user.lastname') }}
                                        </label>
                                        <input
                                            type="text" name="lastname" class="form-control form-control-sm"
                                            value="{{ old('lastname', $user->lastname ?? '') }}"
                                            disabled
                                        />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="firstname">
                                            {{ __('app.user.firstname') }}
                                        </label>
                                        <input
                                            type="text" name="firstname" class="form-control form-control-sm"
                                            value="{{ old('firstname', $user->firstname ?? '') }}"
                                            disabled
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="email">
                                    {{ __('app.user.email') }}
                                </label>
                                <input
                                    type="email" name="email" class="form-control form-control-sm"
                                    value="{{ old('email', $user->email ?? '') }}"
                                    disabled
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            {{ __('app.user.password_change') }}
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="password">
                                    {{ __('app.user.password') }}
                                </label>
                                <input
                                    type="password" name="password" class="form-control form-control-sm"
                                    @if(!isset($user->id)) required @endif
                                />
                                <div class="form-text">{{ __('app.user.password_help') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 mt-3">
                    <button type="submit" name="save_my_account" class="btn btn-primary btn-block">
                        {{ __('app.save') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
