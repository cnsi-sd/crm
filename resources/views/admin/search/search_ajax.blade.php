<div class="dropdown-header noti-title">
    <h5 class="text-overflow mb-2">{{ __('searchable::search.results') }} <span class="text-danger">{{ $nb_results }}</span></h5>
</div>

@foreach ($results as $model => $model_results)
    <div class="dropdown-header noti-title">
        <p class="text-muted mb-0 border-bottom"> {{ trans_choice(sprintf('app.%s.%1$s',$model), 2) }}</p>
    </div>
    <div class="notification-list">
        @foreach ($model_results as $result)
            <a href="{{ route($result->getShowRoute(), $result) }}" class="dropdown-item notify-item">
                {{ $result->__toString() }}
            </a>
        @endforeach
    </div>
@endforeach
