@component('mail::message')
**Nom du script : {{ $commandName }}**<br>
Description du script : {{ $commandDescription }}

Message d'erreur :
{!! $message !!}
@endcomponent
