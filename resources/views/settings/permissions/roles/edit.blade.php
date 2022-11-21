@extends('layouts.vertical', ["page_title"=> trans_choice('app.role.role', 1)])

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                {{ trans_choice('app.role.role', 1) }} #{{ $role->id }}
            </div>
            <div class="card-body">
                <form action="{{ isset($role->id) ? route('edit_role', [$role->id]) : route('create_role') }}" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="name">{{__('app.role.name')}}
                            <span class="required_field">*</span>
                        </label>
                        <input
                            type="text"
                            name="name"
                            class="form-control form-control-sm"
                            value="{{ old('name', $role->name ?? '') }}"
                            required
                        />
                    </div>

                    <div class="form-group mb-3">
                    </div>

                    <div class="d-grid gap-2 mt-3">
                        <button type="submit" name="save_role" class="btn btn-primary btn-block">
                            {{ __('app.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
