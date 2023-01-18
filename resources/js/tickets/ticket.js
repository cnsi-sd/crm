$( document ).ready(function() {
    $('.card-header').click(function () {
            $.get('/tickets/hide_comment/' + $(this).data("comment-id"), function(data) {
        });
    });
});
