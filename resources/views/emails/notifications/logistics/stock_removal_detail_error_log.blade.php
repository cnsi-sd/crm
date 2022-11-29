@component('mail::message')
    # Annulation suite à une {{ strtolower(\App\Enums\Outputs\StockRemovalDetailStateEnum::getMessage($state)) }}

    Référence commande : {{ $stock_removal_detail->stockRemoval->reference }}

    Produit : {{ $stock_removal_detail->product->barcode }}

    Le produit a été annulé suite à une erreur logistique. Il est passé en statut 'Erreur logistique'.
@endcomponent
