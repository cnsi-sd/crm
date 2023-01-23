$( document ).ready(function() {
    $('.card-header').click(function () {
            $.get('/tickets/toggle_comment/' + $(this).data("comment-id"), function(data) {
        });
    });
});
