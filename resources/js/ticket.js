let inputLine = document.getElementById('number-list');
let bodyCard = document.getElementById('card-body-tag');

document.getElementById('add').onclick = updateValue;

function updateValue(){

    inputLine.value = Number(inputLine.value) + 1;
    let divLine = document.createElement("div");
    divLine.id = "line-" + inputLine.value;
    bodyCard.appendChild(divLine);

    let selectTag = document.createElement("select");
    selectTag.name = "thread-tags" + inputLine.value;
    selectTag.className = "form-select";
    makeOption(selectTag);
    divLine.appendChild(selectTag);

    $(selectTag).select2()
    $(selectTag).on('select2:select', saveTicketTags)
}

function makeOption(select){
    window.axios.get('/ajaxTags').then(function (response){
        response.json().then(function (json) {
            let option = document.createElement('option')
            option.text = "Aucune";
            select.add(option)
            json.data.forEach(function (data) {
                let option = document.createElement('option')
                option.text = data.name;
                option.value = data.id;
                select.add(option)
            })


        })
    })
}

function saveTicketTags(e) {
    console.log(e)
}
