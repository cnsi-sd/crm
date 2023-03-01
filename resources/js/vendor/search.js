$('input#top-search').on('keyup', function()
{
    let term = $(this).val();
    if(term.length < 1) { return; }

    let url = $(this).parents('form').attr('action');
    window.axios.get(url, { params: { term: term }})
        .then((response) => {
            $('#ajax_search').html(response.data);
            $('#search-dropdown .loading-ring').hide();
        })
        .catch((response) => {
            $('#ajax_search').html("");
            $('#search-dropdown .loading-ring').show();
        });
});
