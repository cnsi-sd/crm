<div class="p-4 d-flex justify-content-center" id="order-info-spinner">
    <div class="spinner-border text-primary" role="status"></div>
</div>
<div class="row" id="order-info-content">
    <div class="col-5">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">Commande</div>
                    <div class="col-6 text-end">
                        <a href="{{ env('PRESTASHOP_URL') }}index.php?fc=module&module=bmsmagentogateway&controller=order_redirect&reference="
                           type="button" class="btn btn-primary rounded-pill btn-sm" target="_blank" id="ext-order-link">Lien commande Prestashop <i class="uil-external-link-alt"></i></a>
                    </div>
                </div>
            </div>
            <div class="card-body top-cards-height">
                <div class="row">
                    <div class="col">{{ __('app.order.id_order') }} :</label></div>
                    <div id="ext-order-id" class="col"></div>
                </div>
                <div class="row">
                    <div class="col">{{ __('app.order.status') }} :</label></div>
                    <div id="ext-order-state" class="col"></div>
                </div>
                <div class="row">
                    <div class="col">{{ __('app.order.date') }} :</label></div>
                    <div id="ext-order-date" class="col"></div>
                </div>
                <div class="row">
                    <div class="col">{{ __('app.order.carrier')}} :</label></div>
                    <div id="ext-order-carrier" class="col"></div>
                </div>
                <div class="row">
                    <div class="col">{{ __('app.order.tracking') }} :</label></div>
                    <div class="col text-truncate">
                        <a href="#" target="_blank" id="ext-order-tracking"></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col">{{ __('app.order.total_ttc') }} :</label></div>
                    <div id="ext-order-total-paid" class="col"></div>
                </div>
                <div class="row">
                    <div class="col">{{ __('app.order.margin_ht') }} :</label></div>
                    <div id="ext-order-margin" class="col"></div>
                </div>
                <div class="row">
                    <div class="col">{{ __('app.email') }} :</label></div>
                    <div id="ext-order-customer-email" class="col"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-7">
        <div class="card">
            <div class="card-header">{{ __('app.order.private_comment') }}</div>
            <div class="card-body top-cards-height overflow-scroll">
                <div id="ext-order-note"></div>
            </div>
        </div>
    </div>

    <div class="col-5">
        <div class="card">
            <div class="card-header">{{ __('app.customer') }}</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="fw-bold">{{ __('app.order.billing') }}</div>
                        <div id="ext-order-billing-name"></div>
                        <div id="ext-order-billing-address1"></div>
                        <div id="ext-order-billing-address2"></div>
                        <div id="ext-order-billing-postcode-city"></div>
                        <div id="ext-order-billing-phone"></div>
                        <div id="ext-order-billing-phone-mobile"></div>
                    </div>
                    <div class="col-6">
                        <div class="fw-bold">{{ __('app.order.shipping') }}</div>
                        <div id="ext-order-shipping-name"></div>
                        <div id="ext-order-shipping-address1"></div>
                        <div id="ext-order-shipping-address2"></div>
                        <div id="ext-order-shipping-postcode-city"></div>
                        <div id="ext-order-shipping-phone"></div>
                        <div id="ext-order-shipping-phone-mobile"></div>
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
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
