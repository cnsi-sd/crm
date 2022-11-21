@extends('layouts.vertical', ["page_title"=> trans_choice('app.role.role', 2) ])

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                {{ trans_choice('app.role.role', 2) }}
                {!! $table->getLinesCountBadge() !!}

                @can('edit',  App\Models\Role::class)
                    <a href="{{ route('create_role') }}" class="btn btn-outline-primary btn-sm float-end">
                        {{ __('app.role.new') }}
                    </a>
                @endcan

            </div>
            <div class="card-body">
                {{ $table->render() }}
            </div>
        </div>
    </div>
@endsection
