$(document).on('click', 'a[data-confirm]', function()
{
    let url       = $(this).attr('href');
    let title     = $(this).data('confirm');
    window.swal.fire({
        icon: 'warning',
        title: title,
        toast: false,
        showCancelButton: true,
        confirmButtonText: window.translations.app.confirm,
        cancelButtonText: window.translations.app.cancel,
    }).then((result) => {
        if(result.isConfirmed) {
            window.location.replace(url);
        }
    });
    return false;
});
