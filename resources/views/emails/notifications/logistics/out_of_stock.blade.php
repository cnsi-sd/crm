@component('mail::message')
    # Rupture de stock

    Une sortie a été automatiquement annulée pour cause de rupture de stock sur un des produits concernés.

    Référence : **{{ $stock_removal->reference }}**

    Détail :
    @foreach ($stock_removal->getQuantitiesByProduct() as $product)
        - {{ $product['product']->__toString() }} (x{{ $product['quantity'] }})
    @endforeach
@endcomponent
