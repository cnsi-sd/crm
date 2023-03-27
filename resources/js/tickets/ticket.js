
$(document).ready(function () {
    $('.thread-comments').click(function () {
        const route = $(this).data("toggle-comment-route")
        $.get(route);
    })

    let externalOrderInfoLoaded = false;
    $('#order-info-tab').click(function () {
        if(externalOrderInfoLoaded)
            return;

        const route = $(this).data("get-external-infos-route")
        $.get(route, function (data) {
            $('#order-info').html(data)

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
})

let bodyCard = document.getElementById('card-body-tag');

document.getElementById('add').onclick = addListTagOnTicket;
$('.tags').on('select2:select', saveTicketTags);
$('.deleteTaglist').on("click", deleteTagLists);
$('.delete-tag').on("click", deleteTicketTag);

function addListTagOnTicket(e) {
    // create new line in db
    let lineId;
    let ticket_id = e.target.getAttribute("data-ticket_id")
    let url = e.target.getAttribute("data-url_add_tag")
    window.axios.post(url_add_tag_list, {
        ticket_id: ticket_id
    }).then(function (response) {
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
        $(selectTag).select2()
        $(selectTag).on('select2:select', saveTicketTags)

        //create div view tags
        let divView = document.createElement("div");
        divView.id = "view-" + lineId;
        divView.className = "mt-3 mb-2";
        divLine.appendChild(divView);
        $(divLine).append("<hr/>")
    })

}

function makeOption(select, channel_id){
    window.axios.post(url_show_tags, {
            channel_id: channel_id
        }).then(function (response){
        let json = response.data.data;
        let option = document.createElement('option')
        option.text = default_option_selected_tag;
        select.add(option)
        json.forEach(function (data) {
            let option = document.createElement('option')
            option.text = data.name;
            option.value = data.id;
            select.add(option)
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
        $('#tags-container').html(response.data)
        $('#tags-container select').select2();
        $('#tags-container .delete-tag').on('click', deleteTicketTag)

    })
}

function deleteTagLists(ticket_id, taglist_id) {
    window.axios.post(url_delete_tagList, {
        ticket_id: ticket_id,
        taglist_id: taglist_id
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
        deleteTagLists(ticket_id, taglist_id);
        $('#tags-container').html(response.data);
        $('#tags-container select').select2();
    }

    )
}

