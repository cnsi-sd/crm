<div class="row">
    @if(is_array($orders) && count($orders) > 0)
        @if(count($orders) > 1)
            <div class="row pt-2 pb-2">
                <div class="col">
                    @foreach($orders as $order)
                        <button type="button" data-order-id="{{ $order['id_order'] }}" class="btn btn-outline-primary order-btn">
                            {{__('app.ticket.order')}} #{{ $order['id_order'] }}</button>
                    @endforeach
                </div>
            </div>
        @endif
        @foreach($orders as $order)
            <div class="row order-info" data-order-id="{{ $order['id_order'] }}">
                <div class="col-5">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-2">{{ __('app.order.order') }}</div>
                                @if($order['invoice_progress'] == 'generated')
                                    <div class="col-5 text-end">
                                        <a href="{{ route('download_invoice', [$ticket, $order['id_order']]) }}"
                                           type="button" class="btn btn-secondary rounded-pill btn-sm" target="_blank">{{ __('app.order.download_invoice') }} <i class="uil-external-link-alt"></i></a>
                                    </div>
                                @endif
                                <div class="col-5 text-end">
                                    <a href="{{ $external_link }}{{ $order['id_order'] }}"
                                       type="button" class="btn btn-primary rounded-pill btn-sm" target="_blank" id="ext-order-link">{{ __('app.order.external_link') }} <i class="uil-external-link-alt"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body top-cards-height">
                            <div class="row">
                                <div class="col">{{ __('app.order.id_order') }} :</div>
                                <div class="col">{{ $order['id_order'] }}</div>
                            </div>
                            <div class="row">
                                <div class="col">{{ __('app.order.status') }} :</div>
                                <div class="col">
                                    <span class="badge" style="background-color: {{ $order['state']['bg_color'] }}; color: {{ $order['state']['text_color'] }};">
                                        {{ $order['state']['name'] }}
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">{{ __('app.order.date') }} :</div>
                                <div class="col">{{ (new DateTime($order['date_add']))->format('d/m/y H:i') }}</div>
                            </div>
                            <div class="row">
                                <div class="col">{{ __('app.order.carrier')}} :</div>
                                <div class="col">{{ $order['shipping']['carrier'] }}</div>
                            </div>
                            <div class="row">
                                <div class="col">{{ __('app.order.tracking') }} :</div>
                                <div class="col text-truncate">
                                    @if($order['shipping']['url'])
                                        <a href="{{ $order['shipping']['url'] }}" target="_blank">{{ $order['shipping']['tracking_number'] }}</a>
                                    @else
                                       {{ $order['shipping']['tracking_number'] }}
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">{{ __('app.order.total_ttc') }} :</div>
                                <div class="col">{{ \App\Helpers\PriceConverter::withThousandSeparator($order['total_tax_incl']) }}</div>
                            </div>
                            <div class="row">
                                <div class="col">{{ __('app.order.margin_ht') }} :</div>
                                <div class="col">{{ \App\Helpers\PriceConverter::withThousandSeparator($order['margin_tax_excl']) }}</div>
                            </div>
                            <div class="row">
                                <div class="col">{{ __('app.email') }} :</div>
                                <div class="col">{{ $order['email'] }}</div>
                            </div>
                            @if($order['max_shipment_date'])
                                <div class="row">
                                    <div class="col">{{ __('app.order.max_shipment_date') }} :</div>
                                    <div class="col @if((new DateTime()) >= (new DateTime($order['max_shipment_date']))) max_shipment_date @endif">{{ (new DateTime($order['max_shipment_date']))->format('d/m/Y') }}</div>
                                </div>
                            @endif
                            <div class="row pt-2">
                                @if($order['is_havinggcpluscontrat'] == true)
                                    <div class="col"><span class="gcpluscontract order-badge">Contrat GC+</span></div>
                                @endif
                                @if($order['is_fulfillment'] == true)
                                    <div class="col"><span class="fulfillment order-badge">Fulfillment</span></div>
                                @endif
                                @if($order['is_sellerflex'] == true)
                                    <div class="col"><span class="sellerflex order-badge">SellerFlex</span></div>
                                @endif
                                @if($order['is_express'] == true)
                                    <div class="col"><span class="express order-badge">Livraison Express</span></div>
                                @endif
                                @if($order['delivery_shipping_mode'] == "DEBALLE")
                                    <div class="col"><span class="unpacked order-badge">DEBALLE</span></div>
                                @endif
                                @if($order['delivery_shipping_mode'] == "INSTALLE")
                                    <div class="col"><span class="installed order-badge">INSTALLE</span></div>
                                @endif
                            </div>
                            <div class="row pt-2">
                                @if($order['incidents'])
                                    @foreach($order['incidents'] as $incident)
                                        @if ($incident['incident_state'] == "INCIDENT_CLOSE")
                                            <div class="col"><span class="incidentclosed order-badge">{{ __('app.order.incident_closed') }} :<br/>
                                                    {{unserialize($incident['incident_reason'])['label']}}
                                            </span></div>
                                        @endif
                                        @if ($incident['incident_state'] == "INCIDENT_OPEN")
                                            <div class="col"><span class="incidentopened order-badge">{{ __('app.order.incident_opened') }} :<br/>
                                                    {{unserialize($incident['incident_reason'])['label']}}
                                            </span></div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-7">
                    <div class="card">
                        <div class="card-header">{{ __('app.order.private_comment') }}</div>
                        <div class="card-body top-cards-height overflow-auto">
                            {!! nl2br($order['private_comment']) !!}
                        </div>
                    </div>
                </div>

                <div class="col-5">
                    <div class="card">
                        <div class="card-header">{{ __('app.customer') }}</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <x-dynamic-component class="order-info-flag" component="flag-country-{{Str::lower($order['invoice_address']['iso_code'])}}" />
                                    <div class="fw-bold">{{ __('app.order.billing') }}</div>
                                    <div>{{ $order['invoice_address']['firstname'] }} {{ $order['invoice_address']['lastname'] }}</div>
                                    <div>{{ $order['invoice_address']['company'] }}</div>
                                    <div>{{ $order['invoice_address']['address1'] }}</div>
                                    <div>{{ $order['invoice_address']['address2'] }}</div>
                                    <div>{{ $order['invoice_address']['postcode'] }} {{ $order['invoice_address']['city'] }}</div>
                                    <div class="phone_number" title="{{ __('app.ticket.click_and_call.start') }}">
                                        {{ $order['invoice_address']['phone'] }}
                                    </div>
                                    <div class="phone_number" title="{{ __('app.ticket.click_and_call.start') }}">
                                        {{ $order['invoice_address']['phone_mobile'] }}
                                    </div>
                                </div>
                                <div class="col-6">
                                    <x-dynamic-component class="order-info-flag" component="flag-country-{{Str::lower($order['delivery_address']['iso_code'])}}" />
                                    <div class="fw-bold">{{ __('app.order.shipping') }}</div>
                                    <div>{{ $order['delivery_address']['firstname'] }} {{ $order['delivery_address']['lastname'] }}</div>
                                    <div>{{ $order['delivery_address']['company'] }}</div>
                                    <div>{{ $order['delivery_address']['address1'] }}</div>
                                    <div>{{ $order['delivery_address']['address2'] }}</div>
                                    <div>{{ $order['delivery_address']['postcode'] }} {{ $order['delivery_address']['city'] }}</div>
                                    <div class="phone_number" title="{{ __('app.ticket.click_and_call.start') }}">
                                        {{ $order['delivery_address']['phone'] }}
                                    </div>
                                    <div class="phone_number" title="{{ __('app.ticket.click_and_call.start') }}">
                                        {{ $order['delivery_address']['phone_mobile'] }}
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
                            <table class="table table-sm table-centered mb-0" id="ext-order-items">
                                <thead>
                                    <tr>
                                        <th>{{ __('app.order.designation') }}</th>
                                        <th>{{ __('app.order.qty') }}</th>
                                        <th>{{ __('app.order.supplier') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order['products'] as $product)
                                        <tr>
                                            <td>
                                                {{ $product['name'] }}
                                                <br>
                                                <b>{{ __('app.order.brand') }}</b> {{ $product['brand'] }}
                                                <br>
                                                <b>{{ __('app.order.reference') }}</b> {{ $product['reference'] }}
                                                <br>
                                                <b>{{ __('app.order.ean') }}</b> {{ $product['ean'] }}
                                            </td>
                                            <td>{{ $product['quantity'] }}</td>
                                            <td class="text-nowrap">
                                                {{ $product['supplier'] }}
                                                @if($product['is_definitive_supplier'])
                                                    <span class="badge bg-warning"><i class="uil-lock"></i></span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @elseif(is_array($orders) && count($orders) === 0)
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-warning" role="alert">
                        {{ __('app.order.empty_orders') }}
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-danger" role="alert">
                        {{ __('app.order.null_orders') }}
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
