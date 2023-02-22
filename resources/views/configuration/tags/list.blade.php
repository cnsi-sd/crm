@extends('layouts.horizontal', ["page_title"=> trans_choice('app.tags.tags', 2) ])

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                {{ trans_choice('app.revival.revival', 2) }}
                {!! $table->getLinesCountBadge() !!}

            @can('edit', App\Models\User\User::class)
                    <a href="{{ route('create_tags') }}" class="btn btn-outline-primary btn-sm float-end">
                        {{ trans_choice('app.new', 2) }}
                    </a>
                @endcan

            </div>
            <div class="card-body">
                {{ $table->render() }}
            </div>
        </div>
    </div>

@endsection
