@component('mail::message')
# Confirmation de rendez-vous

Entrepôt            : **{{ $entry_request->warehouse->name }}**

Propriétaire        : **{{ $entry_request->owner->name }}**

Fournisseur         : **{{ $entry_request->supplier->name }}**

Date du rendez-vous : **{{$entry_request->deliverySlot->date_start->isoFormat('dddd DD MMMM YYYY')}} /
                        {{$entry_request->deliverySlot->date_start->isoFormat('HH[h]mm')}}
                        - {{$entry_request->deliverySlot->date_end->isoFormat('HH[h]mm')}}**

@component('mail::button', [ 'url' => route('show_entry_request', $entry_request) ])
    Voir la demande #{{ $entry_request->id }}
@endcomponent
@endcomponent
