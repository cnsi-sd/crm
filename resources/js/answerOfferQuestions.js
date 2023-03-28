$(window).on('load', function (){
    let channelName = $('#channelName')
    let messageContent = $('#message-content')
    getMessageContent()


    channelName.change(function(){
        getMessageContent()
    })

    function getMessageContent(){
        const url = channelName.data('get-message-content')

        window.axios.get(url, {params: {
            channelName: channelName.val()
            }}
        )
        .then((response)=> {
            messageContent.val(response.data)
        })
    }
})


