$( document ).ready(function() {
    $('.thread-comments .card-header').click(function () {
            $.get($(this).data("toggle-comment-route"), function(data) {
        });
    });

    $('#order-info-tab').one("click", function () {
        $.get($(this).data("get-external-infos-route"), function(data) {
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
            $("#order-info-content").css("display", "flex");
            $("#order-info-spinner").remove();
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

let inputLine = document.getElementById('number-list');
let bodyCard = document.getElementById('card-body-tag');

document.getElementById('add').onclick = addListTagOnThread;
$('.tags').on('select2:select', saveTicketThreadTags);
$('.deleteTaglist').on("click", deleteTagLists);
$('.delete-tag').on("click", deleteThreadTag)

function addListTagOnThread(e){
    // create new line in db
    let lineId;
    let thread_id = e.target.getAttribute("data-thread_id")
    window.axios.post('/addTagList', {
        thread_id: thread_id
    }).then(function (response) {
        lineId = response.data;
        console.log(response);

        //create div for create line
        let divLine = document.createElement("div");
        divLine.id = "line-" + lineId;
        bodyCard.appendChild(divLine);

        //create button to delete tag list
        let buttonDeleteTaglist = document.createElement("button");
        buttonDeleteTaglist.type = "button";
        buttonDeleteTaglist.className = "deleteTaglist btn btn-danger";
        buttonDeleteTaglist.setAttribute("data-thread_id", thread_id);
        buttonDeleteTaglist.setAttribute("data-taglist_id", lineId);
        buttonDeleteTaglist.innerText = "x";
        $(buttonDeleteTaglist).on("click", deleteTagLists);
        divLine.appendChild(buttonDeleteTaglist);

        //create select tag
        let selectTag = document.createElement("select");
        selectTag.name = "thread-tags-" + lineId;
        selectTag.className = "form-select";
        selectTag.setAttribute("data-thread_id", thread_id);
        selectTag.setAttribute("data-taglist_id", lineId);
        makeOption(selectTag);
        divLine.appendChild(selectTag);

        //add select2 on selectTag
        $(selectTag).select2()
        $(selectTag).on('select2:select', saveTicketThreadTags)

        //create div view tags
        let divView = document.createElement("div");
        divView.id = "view-"+lineId;
        divView.className = "mt-3 mb-2";
        divLine.appendChild(divView);
        $(divLine).append("<hr/>")
    })

}

function makeOption(select){
    window.axios.get('/ajaxTags').then(function (response){
        let json = response.data.data;
        let option = document.createElement('option')
        option.text = "Aucune";
        select.add(option)
        json.forEach(function (data) {
            let option = document.createElement('option')
            option.text = data.name;
            option.value = data.id;
            select.add(option)
        })
    })
}
function saveTicketThreadTags(e) {
    let tag_id = e.target.options[e.target.options.selectedIndex].value;
    let taglist_id = e.target.getAttribute("data-taglist_id");
    window.axios.post('/saveTicketThreadTags', {
        taglist_id: taglist_id,
        tag_id: tag_id
    }).then(function(response) {
        let divViewTag = document.getElementById('view-'+ taglist_id)
        let span = document.createElement('span')

        let buttonDeleteTag = document.createElement('button')
        buttonDeleteTag.type = "button";
        buttonDeleteTag.innerText = "x";
        buttonDeleteTag.className = "btn delete-tag";
        buttonDeleteTag.setAttribute("data-tag_id", tag_id);
        buttonDeleteTag.setAttribute("data-taglist_id", taglist_id);
        buttonDeleteTag.style.color = response.data.text_color;

        span.className = "tags-style"
        span.textContent = response.data.name + " | ";
        span.style.background = response.data.background_color;
        span.style.color = response.data.text_color;
        span.appendChild(buttonDeleteTag)
        divViewTag.appendChild(span);
    })
}

function deleteTagLists(e){
    let thread_id = e.target.getAttribute("data-thread_id");
    let taglist_id = e.target.getAttribute("data-taglist_id");
    window.axios.post('/deleteTagList', {
        thread_id: thread_id,
        taglist_id: taglist_id
    }).then(
        e.target.parentNode.remove()
    )
}

function deleteThreadTag(e) {
    let tag_id = e.target.getAttribute('data-tag_id');
    let taglist_id = e.target.getAttribute("data-taglist_id");
    window.axios.post('/deleteThreadTagOnTagList', {
        tag_id: tag_id,
        taglist_id: taglist_id
    }).then(
        e.target.parentNode.remove()
    )
}

