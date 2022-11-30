@component('mail::message')
    # Failed Jobs

    Des jobs sont en erreur.
    @foreach ($counters as $key => $count)
    - {{ $key }} (x{{ $count }})
    @endforeach

    Merci,
    {{ config('app.name') }}
@endcomponent
