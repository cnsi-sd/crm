@extends('layouts.horizontal', ["page_title"=> __('app.ticket.all_tickets') ])

@section('content')
<div class="container-fluid">
    <div class="mt-3 mb-2">
        <button type="button" class="btn btn-danger" id="resetTagFilter" hidden>Reset filter</button>
        @foreach($liste as $key => $value)
            <button type="button" class="btn tags-style list-tag"
                    data-tag_id="{{$value['tag_id']}}"
                    style="background-color: {{ $value['background_color'] }}; color: {{ $value['text_color'] }};">{{ $key }} <span class="tags-style-count">{{$value['count']}}</span></button>
        @endforeach
    </div>
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

@section('script-bottom')
    <script src="{{ Vite::asset('resources/js/tickets/showTicket.js') }}"></script>
@endsection
