@extends('layouts.horizontal', ["page_title"=> trans_choice('app.admin.channel', 2) ])

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                {{ trans_choice('app.admin.channel', 2) }}
                {!! $table->getLinesCountBadge() !!}
            </div>
            <div class="card-body">
                {{ $table->render() }}
            </div>
        </div>
    </div>

@endsection
