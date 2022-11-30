@component('mail::message')
    # Annulation en phase {{ \App\Enums\Outputs\StockRemovalDetailStateEnum::getMessage($state) }}

    Référence commande : {{ $stock_removal_detail->stockRemoval->reference }}

    Produit : {{ $stock_removal_detail->product->barcode }}

    Le produit a été déplacé en zone {{ $stock_removal_detail->warehouse->cancellationLocation->code }}. Pensez à le ranger.
@endcomponent
