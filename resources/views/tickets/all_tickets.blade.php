@extends('layouts.horizontal', ["page_title"=> __('app.ticket.all_tickets') ])

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            {{ trans_choice('app.ticket.ticket', 2) }}
            {!! $table->getLinesCountBadge() !!}
        </div>
        <div class="card-body">
            {{ $table->render() }}
        </div>
    </div>
</div>
@endsection
