@component('mail::message')
    # Notification

    De nouveaux documents liés à "{{ $documentable }}" sont disponibles.

    Merci,
    {{ config('app.name') }}
@endcomponent
