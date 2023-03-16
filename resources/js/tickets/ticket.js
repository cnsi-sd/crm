$(document).ready(function () {
    $('.thread-comments .card-header').click(function () {
        const route = $(this).data("toggle-comment-route")
        $.get(route);
    });

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
})

let inputLine = document.getElementById('number-list');
let bodyCard = document.getElementById('card-body-tag');

document.getElementById('add').onclick = addListTagOnThread;
$('.tags').on('select2:select', saveTicketThreadTags);
$('.deleteTaglist').on("click", deleteTagLists);
$('.delete-tag').on("click", deleteThreadTag)

function addListTagOnThread(e) {
    // create new line in db
    let lineId;
    let thread_id = e.target.getAttribute("data-thread_id")
    let url = e.target.getAttribute("data-url_add_tag")
    window.axios.post(url_add_tag_list, {
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
        divView.id = "view-" + lineId;
        divView.className = "mt-3 mb-2";
        divLine.appendChild(divView);
        $(divLine).append("<hr/>")
    })

}

function makeOption(select){
    window.axios.get(url_show_tags).then(function (response){
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
    window.axios.post(url_save_tag_on_ticketThread, {
        taglist_id: taglist_id,
        tag_id: tag_id
    }).then(function (response) {
        let divViewTag = document.getElementById('view-' + taglist_id)
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

function deleteTagLists(e) {
    let thread_id = e.target.getAttribute("data-thread_id");
    let taglist_id = e.target.getAttribute("data-taglist_id");
    window.axios.post(url_delete_tagList, {
        thread_id: thread_id,
        taglist_id: taglist_id
    }).then(
        e.target.parentNode.remove()
    )
}

function deleteThreadTag(e) {
    let tag_id = e.target.getAttribute('data-tag_id');
    let taglist_id = e.target.getAttribute("data-taglist_id");
    window.axios.post(url_delete_TagList_On_Thread, {
        tag_id: tag_id,
        taglist_id: taglist_id
    }).then(
        e.target.parentNode.remove()
    )
}

