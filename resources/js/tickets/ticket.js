$( document ).ready(function() {
    $('.card-header').click(function () {
        console.log($(this).data("comment-id"));
        $.get('/tickets/hide_comment/' + $(this).data("comment-id"), function(data) {
        });
    });
});
