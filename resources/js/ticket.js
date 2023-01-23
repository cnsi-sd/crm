let inputLine = document.getElementById('number-list');
let bodyCard = document.getElementById('card-body-tag');

document.getElementById('add').onclick = addListTagOnThread;
$('.tags').on('select2:select', saveTicketThreadTags);
$('.deleteTaglist').on("click", deleteTagLists)

function addListTagOnThread(e){
    // create new line in db
    let lineId;
    let thread_id = e.target.getAttribute("data-thread_id")
    window.axios.post('/addTagList', {
        thread_id: thread_id
    }).then(function (response) {
        /*console.log(response);
        lineId = response;

        //create div for create line
        let divLine = document.createElement("div");
        divLine.id = "line-" + lineId;
        bodyCard.appendChild(divLine);

        //create select tag
        let selectTag = document.createElement("select");
        selectTag.name = "thread-tags-" + lineId;
        selectTag.className = "form-select";
        selectTag.setAttribute("data-thread_id", thread_id);
        selectTag.setAttribute("data-taglist_id", lineId)
        makeOption(selectTag);
        divLine.appendChild(selectTag);

        //add select2 on selectTag
        $(selectTag).select2()
        $(selectTag).on('select2:select', saveTicketTags)*/
        location.reload()
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
    }).then(
        location.reload()
    )
}

function deleteTagLists(e){
    let thread_id = e.target.getAttribute("data-thread_id");
    let taglist_id = e.target.getAttribute("data-taglist_id");
    window.axios.post('/deleteTagList', {
        thread_id: thread_id,
        taglist_id: taglist_id
    }).then(
        location.reload()
    )
}
