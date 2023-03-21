@extends('layouts.horizontal', ["page_title"=> trans_choice('app.ticket.ticket', 1)])

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    {{ trans_choice('app.sav_note.sav_note', 1) }}
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route() }}">

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
