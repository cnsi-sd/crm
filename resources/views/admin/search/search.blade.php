@extends('layouts.horizontal', ["page_title"=> __('searchable::search.term', ["term" => $term]) ])

@section('content')
    <div class="container-fluid">
        <h1>
            {{ __('searchable::search.term', ["term" => $term]) }}
        </h1>
        <div class="row">
            @foreach ($results as $model => $model_results)
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            {{ trans_choice(sprintf('app.%s.%1$s',$model), 2) }}
                            <span class="badge bg-primary">{{ count($model_results) }}</span>
                        </div>
                        <div class="card-body">
                            <ul class="list-group-flush m-0 p-0 m-n3">
                                @foreach ($model_results as $result)
                                    <li class="list-group-item mx-0">
                                        <a href="{{ route($result->getShowRoute(), $result) }}">
                                            {{ $result->__toString() }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
