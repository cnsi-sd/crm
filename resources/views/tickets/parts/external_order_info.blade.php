<div class="row">
    @if($externalAdditionalOrderInfo)
    <div class="col-7">
        <div class="card">
            <div class="card-header">Commande</div>
            <div class="card-body top-cards-height">
                <div class="container">
                    <div class="row">
                        <div class="col">{{ __('app.order.id_order') }} :</label></div>
                        <div class="col">
                            {{$externalOrderInfo['id_order']}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">{{ __('app.order.status') }} :</label></div>
                        <div class="col">
                            {{$externalOrderInfo['state']}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">{{ __('app.order.date') }} :</label></div>
                        <div class="col">
                            {{$externalOrderInfo['date_add']}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">{{ __('app.order.carrier')}} :</label></div>
                        <div class="col">
                            {{$externalOrderInfo['carrier']}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">{{ __('app.order.tracking') }} :</label></div>
                        <div class="col">
                            {{ $externalAdditionalOrderInfo['tracking_info'][0]['tracking_number'] }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">{{ __('app.order.total_ttc') }} :</label></div>
                        <div class="col">
                            {{$externalOrderInfo['total_paid']}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">{{ __('app.order.margin_ht') }} :</label></div>
                        <div class="col">
                            {{ $externalAdditionalOrderInfo['margin'] }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-5">
        <div class="card">
            <div class="card-header">{{ __('app.order.private_comment') }}</div>
            <div class="card-body top-cards-height overflow-scroll">
                <div>{!! $externalOrderInfo['note'] !!}</div>
            </div>
        </div>
    </div>

    <div class="col-5">
        <div class="card">
            <div class="card-header">{{ __('app.customer') }}</div>
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col">{{ __('app.email') }} :</label></div>
                        <div class="col">
                            {{$externalOrderInfo['customer']['email']}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="card">
                                <div class="card-header">{{ __('app.order.billing') }}</div>
                                <div class="card-body">
                                    <div class="row">{{$externalOrderInfo['billing_address']['firstname']}} {{$externalOrderInfo['billing_address']['lastname']}}</div>
                                    <div class="row">{{$externalOrderInfo['billing_address']['address1']}}</div>
                                    <div class="row">{{$externalOrderInfo['billing_address']['address2']}}</div>
                                    <div class="row">{{$externalOrderInfo['billing_address']['postcode']}} {{$externalOrderInfo['billing_address']['city']}}</div>
                                    <div class="row">{{$externalOrderInfo['billing_address']['phone']}}</div>
                                    <div class="row">{{$externalOrderInfo['billing_address']['phone_mobile']}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card">
                                <div class="card-header">{{ __('app.order.shipping') }}</div>
                                <div class="card-body">
                                    <div class="row">{{$externalOrderInfo['shipping_address']['firstname']}} {{$externalOrderInfo['shipping_address']['lastname']}}</div>
                                    <div class="row">{{$externalOrderInfo['shipping_address']['address1']}}</div>
                                    <div class="row">{{$externalOrderInfo['shipping_address']['address2']}}</div>
                                    <div class="row">{{$externalOrderInfo['shipping_address']['postcode']}} {{$externalOrderInfo['shipping_address']['city']}}</div>
                                    <div class="row">{{$externalOrderInfo['shipping_address']['phone']}}</div>
                                    <div class="row">{{$externalOrderInfo['shipping_address']['phone_mobile']}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-7">
        <div class="card">
            <div class="card-header">{{ __('app.order.products') }}</div>
            <div class="card-body">
                <div class="row">
                    <div class="col fw-bold">{{ __('app.order.designation') }}</div>
                    <div class="col fw-bold">{{ __('app.order.qty') }}</div>
                    <div class="col fw-bold">{{ __('app.order.supplier') }}</div>
                </div>
                @foreach($externalOrderInfo['items'] as $item)
                    <div class="row">
                        <div class="col">{{ $item['product_name'] }} - {{ $item['product_reference'] }} - {{ $item['product_ean13'] }}</div>
                        <div class="col">{{ $item['product_quantity'] }}</div>
                        <div class="col">
                            @foreach($externalSuppliers as $supplier)
                                @if($supplier['id_supplier'] == $item['id_definitive_supplier'])
                                {{ $supplier['name'] }}
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
