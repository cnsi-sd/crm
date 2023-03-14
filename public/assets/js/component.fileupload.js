/**
 * Theme: Hyper - Responsive Bootstrap 5 Admin Dashboard
 * Author: Coderthemes
 * Component: Dragula component
 */

!function ($) {
    "use strict";

    var FileUpload = function () {
        this.$body = $('body')
        this.modal = $('#dropzone-loading-modal')
    };


    /* Initializing */
    FileUpload.prototype.init = function () {
        // Disable auto discovery
        Dropzone.autoDiscover = false;

        $('[data-plugin="dropzone"]').each(function () {
            const actionUrl = $(this).attr('action');
            const previewContainer = $(this).data('previewsContainer');

            let opts = { url: actionUrl };
            if (previewContainer) {
                opts['previewsContainer'] = previewContainer;
            }

            const uploadPreviewTemplate = $(this).data('uploadPreviewTemplate');
            if (uploadPreviewTemplate) {
                opts['previewTemplate'] = $(uploadPreviewTemplate).html();
            }

            opts['init'] = function() {
                this.on('addedfile', function(file) {
                    // Close the add document modal
                    $('#addDocumentModal').modal('hide')

                    // Open an unclosable modal
                    var myModal = new bootstrap.Modal(document.getElementById('dropzone-upload-modal'), {
                        'keyboard' : false,
                        'backdrop' : 'static',
                    })
                    myModal.show()

                    // Init progress bar to 0
                    $('#dropzone-upload-progress')
                        .css('width', '0%')
                        .attr('aria-valuenow', 0)
                        .text('0%');
                });

                this.on('uploadprogress', function(file, progress, bytesSent) {
                    // Growth progressbar
                    $('#dropzone-upload-progress')
                        .css('width', progress + '%')
                        .attr('aria-valuenow', progress)
                        .text(progress + '%');
                });

                this.on('complete', function(file) {
                    location.reload()
                });
            }

            const dropzoneEl = $(this).dropzone(opts);
        });
    },

    //init fileupload
    $.FileUpload = new FileUpload, $.FileUpload.Constructor = FileUpload

}(window.jQuery),

//initializing FileUpload
    function ($) {
        "use strict";
        $.FileUpload.init()
    }(window.jQuery);
