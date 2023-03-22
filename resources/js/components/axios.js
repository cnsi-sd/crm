import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios_response = {
    success : function (response) {
        window.swal.fire({
            icon: 'success',
            title: window.translations.app.alert_level.success,
            text: response.data,
            toast: true,
            timer: 2000,
            timerProgressBar: true,
            position: 'top-end',
        })
    },
    error : function (error) {
        let text;
        if (error.response) {
            // The request was made and the server responded with a status code
            // that falls out of the range of 2xx
            console.log(error.response)
            text = '[' + error.response.status + '] ' + error.response.statusText
        } else if (error.request) {
            // The request was made but no response was received
            // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
            // http.ClientRequest in node.js
            text = window.translations.app.axios.no_response_error;
        } else {
            // Something happened in setting up the request that triggered an Error
            text = window.translations.app.axios.setting_up_error;
        }

        console.log(error);

        window.swal.fire({
            icon: 'error',
            title: window.translations.app.alert_level.error,
            text: text,
        })
    }
}

