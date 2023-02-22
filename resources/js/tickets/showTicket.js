$('.list-tag').on('click', filtreTag)
$('#resetTagFilter').on('click', resetTagFilter)
window.addEventListener('DOMContentLoaded', function() {
    console.log('DOM fully loaded and parsed');
    let select = $('select[name="search[tags_id]"]').find(":selected").val();
    console.log(select);
    if(select !== '')
        document.getElementById("resetTagFilter").removeAttribute("hidden");

});
function filtreTag(e) {
    let tag_id = e.target.getAttribute("data-tag_id")
    let select = document.getElementsByName("search[tags_id]")
    select[0].forEach(function (data) {
        if(data.value === tag_id){
            data.selected = true
        }
    })
    let buttonSearchFilter = document.getElementsByName("submit_search")
    document.getElementById("resetTagFilter").removeAttribute("hidden");
    $(buttonSearchFilter).click()
}

function resetTagFilter(){
    let buttonResetFilter = document.getElementsByName("reset_search");
    document.getElementById("resetTagFilter").setAttribute("hidden","");
    $(buttonResetFilter).click()
}


