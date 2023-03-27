$(window).on('load', function (){
    let channelName = $('#channelName')
    let messageContent = $('#message-content')
    getMessageContent()


    channelName.change(function(){
        getMessageContent()
    })

    function getMessageContent(){
        const url = channelName.data('get-message-content')

        console.log(channelName.val())
        // console.log(url)
        console.log(messageContent.val())

        window.axios.get(url, {params: {
            channelName: channelName.val()
            }}
        )
        .then((response)=> {
            console.log(response.data)
            messageContent.val(response.data)
        })
    }
})


