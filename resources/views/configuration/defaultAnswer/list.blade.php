@extends('layouts.horizontal', ["page_title"=> trans_choice('app.configuration.defaultAnswer', 2) ])

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                {{ trans_choice('app.configuration.defaultAnswer', 2) }}
                {!! $table->getLinesCountBadge() !!}

                @can('edit', \App\Models\Channel\Default_Answer::class)
                    <a href="{{ route('create_defaultAnswer') }}" class="btn btn-outline-primary btn-sm float-end">
                        {{ 'new'}}
                    </a>
                @endcan

            </div>
            <div class="card-body">
                {{ $table->render() }}
            </div>
        </div>
    </div>
@endsection
