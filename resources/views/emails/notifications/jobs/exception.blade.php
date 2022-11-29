@component('mail::message')
**Nom du script : {{ $commandName }}**<br>
Description du script : {{ $commandDescription }}

**Code erreur : {{ $code }}**<br>
Message d'erreur : {{ $exception->getMessage() }}

**Trace :**<br>
{!!  nl2br($exception->getTraceAsString()) !!}
@endcomponent
