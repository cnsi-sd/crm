@if(isset($table) && $table->getQueryBeforePagination()->count() > 0)
    <div class="card">
        <div class="card-header">
            {{ trans_choice('app.historical.histories', 2) }}
            {!! $table->getLinesCountBadge() !!}
        </div>
        <div class="card-body">
            {!! $table->render() !!}
        </div>
    </div>
@elseif(isset($table) && $table->getQueryBeforePagination()->count() === 0)
    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="alert alert-warning" role="alert">
                    {{ __('app.historical.empty_historical') }}
                </div>
            </div>
        </div>
    </div>
@else
    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="alert alert-danger" role="alert">
                    {{ __('app.historical.null_historical') }}
                </div>
            </div>
        </div>
    </div>
@endif
