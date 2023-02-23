<div class="row">
    @if($externalAdditionalOrderInfo)
    <div class="col-5">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">Commande</div>
                    <div class="col-6 text-end">
                        <a href="{{ env('PRESTASHOP_URL') }}index.php?fc=module&module=bmsmagentogateway&controller=order_redirect&reference={{$externalOrderInfo['reference']}}"
                           type="button" class="btn btn-primary rounded-pill btn-sm" target="_blank">Lien commande Prestashop <i class="uil-external-link-alt"></i></a>
                    </div>
                </div>
            </div>
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
                            {{ \Carbon\Carbon::parse($externalOrderInfo['date_add'])->translatedFormat('d/m/Y H:i') }}
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
                        <div class="col text-truncate">
                            <a href="{{ str_replace('@', $externalAdditionalOrderInfo['tracking_info'][0]['tracking_number'], $externalAdditionalOrderInfo['tracking_info'][0]['url']) }}" target="_blank">
                                {{ $externalAdditionalOrderInfo['tracking_info'][0]['tracking_number'] }}
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">{{ __('app.order.total_ttc') }} :</label></div>
                        <div class="col">
                            {{\App\Helpers\PriceConverter::floatToString($externalOrderInfo['total_paid'], '€', 2)}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">{{ __('app.order.margin_ht') }} :</label></div>
                        <div class="col">
                            {{\App\Helpers\PriceConverter::floatToString($externalAdditionalOrderInfo['margin'], '€', 2)}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">{{ __('app.email') }} :</label></div>
                        <div class="col">
                            {{$externalOrderInfo['customer']['email']}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-7">
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
                        <div class="col-6">
                            <div class="row fw-bold">{{ __('app.order.billing') }}</div>
                            <div class="row">{{$externalOrderInfo['billing_address']['firstname']}} {{$externalOrderInfo['billing_address']['lastname']}}</div>
                            <div class="row">{{$externalOrderInfo['billing_address']['address1']}}</div>
                            <div class="row">{{$externalOrderInfo['billing_address']['address2']}}</div>
                            <div class="row">{{$externalOrderInfo['billing_address']['postcode']}} {{$externalOrderInfo['billing_address']['city']}}</div>
                            <div class="row">{{$externalOrderInfo['billing_address']['phone']}}</div>
                            <div class="row">{{$externalOrderInfo['billing_address']['phone_mobile']}}</div>
                        </div>
                        <div class="col-6">
                            <div class="row fw-bold">{{ __('app.order.shipping') }}</div>
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
    <div class="col-7">
        <div class="card">
            <div class="card-header">{{ __('app.order.products') }}</div>
            <div class="card-body">
                <table class="table table-sm table-centered mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('app.order.designation') }}</th>
                            <th>{{ __('app.order.qty') }}</th>
                            <th>{{ __('app.order.supplier') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($externalOrderInfo['items'] as $item)
                        <tr>
                            <td>{{ $item['product_name'] }} - {{ $item['product_reference'] }} - {{ $item['product_ean13'] }}</td>
                            <td>{{ $item['product_quantity'] }}</td>
                            <td>
                                @foreach($externalSuppliers as $supplier)
                                    @if($supplier['id_supplier'] == $item['id_definitive_supplier'])
                                    {{ $supplier['name'] }}
                                    @endif
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
