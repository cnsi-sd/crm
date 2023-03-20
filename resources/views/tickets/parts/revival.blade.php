<div class="card">
    <div class="card-header">{{ trans_choice('app.revival.revival', 1) }}</div>
    <div class="card-body">
        <select form="saveTicket" name="ticket-revival" class="form-select no-sort">
            <option value="">{{ __('app.revival.select_revival') }}</option>
            @foreach ($thread->ticket->channel->revivals as $revival)
                <option value="{{ $revival->id }}" @if(!empty($thread->revival->id))
                    @selected($revival->id == $thread->revival->id)
                    @endif>
                    {{ $revival->name }}
                </option>
            @endforeach
        </select>
        @if($thread->revival)
            @php($revival = $thread->revival)
            <div class="row mt-2">
                <div class="col"><label>{{ __('app.revival.start_revival') }}</label></div>
                <div class="col">
                    <input form="saveTicket" name="revival-delivery_date" class="form-control"
                           type="date"
                           value="{{ date('Y-m-d', strtotime($thread->revival_start_date)) }}"
                    />
                </div>
            </div>
        @endif
    </div>
    @if($thread->revival)
        <div class="card-footer">
            @php($revival = $thread->revival)
            <div class="row">
                <div class="col">
                    <label>
                        {{ __('app.revival.frequency') }}
                    </label>
                </div>
                <div class="col">
                    {{ trans_choice('app.revival.frequency_details', $revival->frequency, ['freq' => $revival->frequency]) }}
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label>
                        {{ __('app.revival.MaxRevival') }}
                    </label>
                </div>
                <div class="col">
                    {{ $thread->revival_message_count }}/{{ $revival->max_revival }}
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label>
                        {{ __('app.revival.nextReply') }}
                    </label>
                </div>
                <div class="col">
                    {{ $thread->getNextRevivalDate()->format('d/m/y') }}
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label>
                        {{ __('app.revival.sendType') }}
                    </label>
                </div>
                <div class="col">
                    {{$thread->revival->send_type}}
                </div>
            </div>
            @if($revivalError = $thread->getThreadRevivalError(false))
                <div class="row mt-2">
                    <div class="col">
                        <div
                            class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show"
                            role="alert">
                            {{ $revivalError }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>
