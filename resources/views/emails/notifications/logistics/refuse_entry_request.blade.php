@component('mail::message')
# Refus de rendez-vous

Entrepôt            : **{{ $entry_request->warehouse->name }}**

Propriétaire        : **{{ $entry_request->owner->name }}**

Fournisseur         : **{{ $entry_request->supplier->name }}**

Rendez-vous demandé pour le  : **{{$entry_request->deliverySlot->date_start->isoFormat('dddd DD MMMM YYYY')}} /
                                 {{$entry_request->deliverySlot->date_start->isoFormat('HH[h]mm')}}
                                 - {{$entry_request->deliverySlot->date_end->isoFormat('HH[h]mm')}}**

Motifs:
@foreach($reasons_for_refusal as $key => $reason)
- {{\App\Enums\Inputs\ReasonsForRefusalEnum::getMessage($reason) }}
@endforeach

@if($entry_request->warehouse_comment)
Commentaire: {{$entry_request->warehouse_comment}}
@endif

Veuillez refaire une demande de rendez-vous valide. Le créneau de rendez-vous choisi est à nouveau libre.

@component('mail::button', [ 'url' => route('show_entry_request', $entry_request) ])
    Voir la demande #{{ $entry_request->id }}
@endcomponent
@endcomponent
