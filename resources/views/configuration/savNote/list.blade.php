@extends('layouts.horizontal', ["page_title"=> trans_choice('app.sav_note.sav_note', 2) ])

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                {{ trans_choice('app.sav_note.sav_note', 2) }}
                {!! $table->getLinesCountBadge() !!}
                @can('read', \App\Models\Channel\SavNote::class)
                    <a href="{{ route('create_sav_note') }}" class="btn btn-outline-primary btn-sm float-end">
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
