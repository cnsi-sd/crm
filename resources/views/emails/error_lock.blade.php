<div>
    @switch($action)
        @case('alert')
            <p>{{__('lock.lock_error.process')}} "{{$signature}}" {{__('lock.lock_error.not_started')}} {{$locker_process_duration}} {{trans_choice('lock.second', 2)}}.</p>
            @break
        @case('kill')
            <p>{{__('lock.lock_error.process')}} "{{$signature}}" {{__('lock.lock_error.run_since')}} {{$locker_process_duration}} {{trans_choice('lock.second', 2)}}. {{__('lock.lock_error.kill_to_new')}}.</p>
            @break
    @endswitch
    <h3>{{__('lock.lock_error.lock_parameter')}} :</h3>
    @if($alert_locked_since)
        <p>{{__('lock.lock_error.alert')}} : {{__('lock.lock_error.just_since')}} {{$alert_locked_since}} {{trans_choice('app.second', 2)}}.</p>
    @else
        <p>{{__('lock.lock_error.alert')}} : {{__('lock.lock_error.never')}}</p>
    @endif
    @if($kill_locked_since)
        <p>{{__('lock.lock_error.kill')}} : {{__('lock.lock_error.just_since')}} {{$kill_locked_since}} {{trans_choice('app.second', 2)}}.</p>
    @else
        <p>{{__('lock.lock_error.kill')}} : {{__('lock.lock_error.never')}}</p>
    @endif
    <h3>{{__('lock.lock_error.lock_details')}} :</h3>
    <p>{{__('lock.lock_error.file')}} : {{$lock_path}}</p>
    <p>{{__('lock.lock_error.create_date')}} : {{$create_date->format('d/m/Y H:i:s')}}</p>
    <p>{{__('lock.lock_error.process_id')}} : {{$locked_process_pid}}</p>
</div>
