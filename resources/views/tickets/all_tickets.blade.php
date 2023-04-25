@extends('layouts.horizontal', ["page_title"=> __('app.ticket.all_tickets') ])

@section('content')
<div class="container-fluid">
    <div class="mt-3 mb-2">
        <button type="button" class="btn btn-danger" id="resetTagFilter" hidden>{{ __('app.ticket.reset_filter') }}</button>
        @foreach($listTags as $value)
            <button type="button" class="btn tags-style list-tag"
                    data-tag_id="{{$value[0]->id}}"
                    style="background-color: {{ $value[0]->background_color }}; color: {{ $value[0]->text_color }};">{{ $value[0]->name }} <span class="tags-style-count">{{$value[1]}}</span></button>
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
