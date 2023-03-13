window.onload = function() {
    document.getElementById("message_to_customer").onkeyup = function() {
        resizeTexteArea( this, 700 )
    };
}

function resizeTexteArea(id, maxHeight)
{
    var text = id && id.style ? id : document.getElementById(id);
    if ( !text )
        return;

    var adjustedHeight = text.clientHeight;
    if ( !maxHeight || maxHeight > adjustedHeight )
    {
        adjustedHeight = Math.max(text.scrollHeight, adjustedHeight);
        if ( maxHeight )
            adjustedHeight = Math.min(maxHeight, adjustedHeight);
        if ( adjustedHeight > text.clientHeight )
            text.style.height = adjustedHeight + "px";
    }
}
