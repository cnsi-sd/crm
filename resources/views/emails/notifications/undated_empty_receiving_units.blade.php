@component('mail::message')
    # Undated empty receiving units

    Des unités de vente sont vides et sans date de sortie :
    @foreach ($undated_empty_receiving_units as $undated_empty_receiving_unit)
        {{$undated_empty_receiving_unit->_barcode()->getBarcode()}}
    @endforeach

    Merci,
    {{ config('app.name') }}
@endcomponent
