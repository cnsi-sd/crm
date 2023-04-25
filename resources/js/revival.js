$(document).on('change', '#channels', function (e) {
        let listChannelId = [];
        for (let i = 0; i < document.getElementById('channels').options.length; i++){
            if (document.getElementById('channels').options[i].selected === true){
                listChannelId.push(document.getElementById('channels').options[i].value)
            }
        }
        replaceSelect(defaultAnswerList,listChannelId,'select-default_answer_id',"default_answer_id",default_answer);
        replaceSelect(defaultAnswerList,listChannelId,'select-end_default_answer_id',"end_default_answer_id",end_default_answer);
        replaceSelect(url_show_tags_revival,listChannelId,'select-revivalEndTag',"revivalEndTag",end_tag_option);
    }
)

function replaceSelect(link,list_channel_id, selectID,selectNAME,defaultOPTION){
    let oldSelect = document.getElementById(selectID);
    let newSelect = document.createElement('select');
    newSelect.name = selectNAME;
    newSelect.id = selectID;
    newSelect.className = "form-control form-control-sm form-select";
    newSelect.required = true;

    window.axios.post(link, {
        channel_id: list_channel_id
    }).then(function (response){
        let json = response.data.data;
        let option = document.createElement('option')
        option.text = defaultOPTION;
        newSelect.add(option);
        json.forEach(function (data) {
            makeOption(newSelect, data)
        })
    })
    let parent = oldSelect.parentNode;
    parent.replaceChild(newSelect, oldSelect);
    $(newSelect).select2();
}

function makeOption(select, data) {
    let option = document.createElement('option')
    option.text = data.name;
    option.value = data.id;
    select.add(option);
}
