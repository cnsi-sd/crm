$( document ).ready(function() {
    $('.card-header').click(function () {
            $.get('/tickets/toggle_comment/' + $(this).data("comment-id"), function(data) {
        });
    });

    $('#order-info-tab').one("click", function () {
        $.get('/tickets/get_external_infos/' + $(this).data("order-id"), function(data) {
            $("#ext-order-link").attr('href', $("#ext-order-link").attr('href') + data.externalOrderInfo.reference);
            $("#ext-order-id").text(data.externalOrderInfo.id_order);
            $("#ext-order-state").text(data.externalOrderInfo.state);
            var order_date = new Date(data.externalOrderInfo.date_add);
            $("#ext-order-date").text(order_date.toLocaleDateString("fr") + ' ' + order_date.toLocaleTimeString("fr")); //d/m/Y H:i
            $("#ext-order-carrier").text(data.externalOrderInfo.carrier);
            $("#ext-order-tracking").attr('href', data.externalAdditionalOrderInfo.tracking_info[0]['url']
                .replace('@', data.externalAdditionalOrderInfo.tracking_info[0]['tracking_number']));
            $("#ext-order-tracking").text(data.externalAdditionalOrderInfo.tracking_info[0]['tracking_number']);
            $("#ext-order-total-paid").text(floatToString(data.externalOrderInfo.total_paid, "€", 2));
            $("#ext-order-margin").text(floatToString(data.externalAdditionalOrderInfo.margin, "€", 2));
            $("#ext-order-customer-email").text(data.externalOrderInfo.customer.email);
            $("#ext-order-note").append(data.externalOrderInfo.note);
            $("#ext-order-billing-name").text(data.externalOrderInfo.billing_address.firstname + " " + data.externalOrderInfo.billing_address.lastname);
            $("#ext-order-billing-address1").text(data.externalOrderInfo.billing_address.address1);
            $("#ext-order-billing-address2").text(data.externalOrderInfo.billing_address.address2);
            $("#ext-order-billing-postcode-city").text(data.externalOrderInfo.billing_address.postcode + " " + data.externalOrderInfo.billing_address.city);
            $("#ext-order-billing-phone").text(data.externalOrderInfo.billing_address.phone);
            $("#ext-order-billing-phone-mobile").text(data.externalOrderInfo.billing_address.phone_mobile);
            $("#ext-order-shipping-name").text(data.externalOrderInfo.shipping_address.firstname + " " + data.externalOrderInfo.shipping_address.lastname);
            $("#ext-order-shipping-address1").text(data.externalOrderInfo.shipping_address.address1);
            $("#ext-order-shipping-address2").text(data.externalOrderInfo.shipping_address.address2);
            $("#ext-order-shipping-postcode-city").text(data.externalOrderInfo.shipping_address.postcode + " " + data.externalOrderInfo.shipping_address.city);
            $("#ext-order-shipping-phone").text(data.externalOrderInfo.shipping_address.phone);
            $("#ext-order-shipping-phone-mobile").text(data.externalOrderInfo.shipping_address.phone_mobile);
            data.externalOrderInfo.items.forEach(function(item){
                $('#ext-order-items tbody').append('<tr><td>'+item.product_name+' - '+item.product_reference+' - '+
                    item.product_ean13+'</td><td>'+item.product_quantity+'</td><td>'+
                    data.externalSuppliers.find(element => element.id_supplier == item.id_definitive_supplier).name+
                    '</td></tr>');
            });
        });
    });
    function floatToString(value, currency, round) {
        if(!value)
            return '--';
        if(round) {
            round = Math.pow(10, round);
            value = Math.round(value * round) / round;
        }
        return value.toString().replace('.',',') + ' ' + currency;
    }
});
