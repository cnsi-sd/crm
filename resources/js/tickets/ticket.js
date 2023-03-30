$(document).ready(function () {
    $('.thread-comments').click(function () {
        const route = $(this).data("toggle-comment-route")
        $.get(route);
    })

    $('#default_answer_select').on('change', function () {
        if($(this).find(':selected').data("answer-content")) {
            let answerContent = $(this).find(':selected').data("answer-content")
            tinymce.get('message_to_customer').insertContent("<br/>" + answerContent);
        }
    });

    let externalOrderInfoLoaded = false;
    $('#order-info-tab').click(function () {
        if(externalOrderInfoLoaded)
            return;

        const route = $(this).data("get-external-infos-route")
        $.get(route, function (data) {
            $('#order-info').html(data)

            $('.order-info:not(:first)').hide();
            $('.order-btn:first').removeClass("btn-outline-primary");
            $('.order-btn:first').addClass("btn-primary");

            $('.phone_number').click(function() {
                window.axios.post(url_click_and_call, {
                    'phone_number' : $(this).text(),
                })
                .then(function (response) {
                    window.swal.fire({
                        icon: response.data.status,
                        title: response.data.message,
                        toast: true,
                        timer: 2000,
                        timerProgressBar: true,
                        position: 'top-end',
                    })
                })
                .catch(window.axios_response.error)
            });

            externalOrderInfoLoaded = true;
        });
    })

    var attachmentIndex = 1;
    $('#addAttachment').on("click", function () {
        $( ".attachment_bloc" ).first().clone().appendTo( ".attachments" );
        attachmentIndex = attachmentIndex+1;
        $( ".attachment_type" ).last().attr('name', "attachment_type_"+attachmentIndex);
        $( ".attachment_file" ).last().attr('name', "attachment_file_"+attachmentIndex);
        $( ".attachment_file" ).last().val('');
    });

    $('body').on("click", '.order-btn', function() {
        $('.order-btn').removeClass("btn-primary");
        $('.order-btn').addClass("btn-outline-primary");
        $('.order-btn[data-order-id=' + $(this).data("order-id") + ']').removeClass("btn-outline-primary");
        $('.order-btn[data-order-id=' + $(this).data("order-id") + ']').addClass("btn-primary");
        $('.order-info').hide();
        $('[data-order-id=' + $(this).data("order-id") + ']').show();
    })

    let ticket_id = $('#addTagLine').data('ticket_id');
    checkHasTaglist(ticket_id);

    let myButton = document.getElementById("btn-back-to-top");

    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function () {
        scrollFunction(myButton);
    };
    // When the user clicks on the button, scroll to the top of the document
    myButton.addEventListener("click", backToTop);

    var channelInMessage = 0;

    function checkChannelInMessage(channel){
        if(tinymce.get('message_to_customer').getContent().includes(channel)) {
            channelInMessage++;
        }
    }

    $('button[type=submit][form=saveTicket]').on("click", function(event) {
        let othersChannels = $("#others-channels").data("others-channels").split(',');
        othersChannels.forEach(element => checkChannelInMessage(element));
        if(channelInMessage > 0) {
            if(!confirm($("#others-channels").data("confirm-message"))){
                event.preventDefault();
            };
        }
    });
})

$(document).on('click', '#addTagLine', function (e){
    e.preventDefault()
    addListTagOnTicket(e);
})
$(document).on('select2:select', '.tags', function (e){
    saveTicketTags(e);
})
$(document).on('click', '.delete-tag', function (e){
    deleteTicketTag(e);
})

function addListTagOnTicket(e) {
    // create new line in db
    let lineId;
    let ticket_id = e.target.getAttribute("data-ticket_id");
    window.axios.post(url_add_tag_list, {
        ticket_id: ticket_id
    }).then(function (response) {
        let bodyCard = document.getElementById('card-body-tag');

        lineId = response.data;

        //create div for create line
        let divLine = document.createElement("div");
        divLine.id = "line-" + lineId;
        bodyCard.appendChild(divLine);

        //create select tag
        let selectTag = document.createElement("select");
        selectTag.name = "ticket-tags-" + lineId;
        selectTag.className = "form-select";
        selectTag.setAttribute("data-ticket_id", ticket_id);
        selectTag.setAttribute("data-taglist_id", lineId);
        makeOption(selectTag,e.target.getAttribute("data-channel_id"));
        divLine.appendChild(selectTag);

        //add select2 on selectTag
        $(selectTag).select2();
        $(selectTag).on('select2:select', saveTicketTags);

        //create div view tags
        let divView = document.createElement("div");
        divView.id = "view-" + lineId;
        divView.className = "mt-3 mb-2";
        divLine.appendChild(divView);
        $(divLine).append("<hr/>");
    })

}

function makeOption(select, channel_id){
    window.axios.post(url_show_tags, {
            channel_id: channel_id
        }).then(function (response){
        let json = response.data.data;
        let option = document.createElement('option')
        option.text = default_option_selected_tag;
        select.add(option);
        json.forEach(function (data) {
            let option = document.createElement('option')
            option.text = data.name;
            option.value = data.id;
            select.add(option);
        })
    })
}

function saveTicketTags(e) {
    let tag_id = e.target.options[e.target.options.selectedIndex].value;
    let taglist_id = e.target.getAttribute("data-taglist_id");
    let ticket_id = e.target.getAttribute("data-ticket_id");
    window.axios.post(url_add_tag_on_ticket, {
        ticket_id: ticket_id,
        taglist_id: taglist_id,
        tag_id: tag_id
    }).then(function (response) {
        $('#tags-container').html(response.data);
        $('#tags-container select').select2();

    })
}

function deleteTicketTag(e) {
    let tag_id = e.target.getAttribute('data-tag_id');
    let taglist_id = e.target.getAttribute("data-taglist_id");
    let ticket_id = e.target.getAttribute("data-ticket_id");
    window.axios.post(url_delete_tag_on_ticket, {
        ticket_id: ticket_id,
        tag_id: tag_id,
        taglist_id: taglist_id
    }).then(function (response){
        $('#tags-container').html(response.data);
        $('#tags-container select').select2();
        checkHasTaglist(ticket_id);
    }

    )
}

function checkHasTaglist(ticket_id){
    window.axios.post(checkHasTag,{
        ticket_id: ticket_id
    }).then(function (response){
        if (!response.data){
            $('#addTagLine').click();
        }
    })
}

function scrollFunction(myButton) {
    if (
        document.body.scrollTop > 20 ||
        document.documentElement.scrollTop > 20
    ) {
        myButton.style.display = "flex";
    } else {
        myButton.style.display = "none";
    }
}


function backToTop() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}
