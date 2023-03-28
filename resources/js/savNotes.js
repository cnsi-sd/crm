$(document).ready(function () {
    const gcPlusCheckbox = $('#gc_plus_checkbox')
    const gcPlusDelay = $('#gc_plus_delay')

    if(gcPlusCheckbox.is(":checked")) {
        gcPlusDelay.children('input').prop('disabled', false)
        gcPlusDelay.children('input').prop('required', true)
        gcPlusDelay.children('label').children('span').prop('hidden', false)
    } else {
        gcPlusDelay.children('input').prop('disabled', true)
        gcPlusDelay.children('input').prop('required', false)
        gcPlusDelay.children('label').children('span').prop('hidden', true)
    }

    gcPlusCheckbox.change(function () {
        if($(this).is(":checked")) {
            gcPlusDelay.children('input').prop('disabled', false)
            gcPlusDelay.children('input').prop('required', true)
            gcPlusDelay.children('label').children('span').prop('hidden', false)
        } else {
            gcPlusDelay.children('input').prop('disabled', true)
            gcPlusDelay.children('input').prop('required', false)
            gcPlusDelay.children('input').val('')
            gcPlusDelay.children('label').children('span').prop('hidden', true)
        }
    })
})
