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
    makeOption(selectTag);
    divLine.appendChild(selectTag);
}

function makeOption(select){

    let option = document.createElement('option')
    select.add(option)
}
