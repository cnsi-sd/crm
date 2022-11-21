@extends('layouts.vertical', ["page_title"=> trans_choice('app.user.user', 2) ])

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                {{ trans_choice('app.user.user', 2) }}
                {!! $table->getLinesCountBadge() !!}

                @can('edit', App\Models\User\User::class)
                    <a href="{{ route('create_user') }}" class="btn btn-outline-primary btn-sm float-end">
                        {{ __('app.user.new') }}
                    </a>
                @endcan

            </div>
            <div class="card-body">
                {{ $table->render() }}
            </div>
        </div>
    </div>
@endsection
