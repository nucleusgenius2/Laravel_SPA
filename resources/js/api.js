import axios from "axios";

export async function authRequest (url ='', type='get', data={}){

    let token = '';
    let response = 'error';
    if (localStorage.getItem("token") !== null ) {
        token = JSON.parse(localStorage.getItem('token'));
    }
console.log( token)
    let headers = {
        accept: 'application/json',
       // 'Content-Type' : "multipart/form-data; charset=utf-8; boundary=" + Math.random().toString().substr(2),
        Authorization: 'Bearer ' + token.token
    }

    if (type === 'get') {
        try {
            response = await axios.get(url, {
                headers: headers
            });
        } catch (error) {
            response = error.response;
        }
    }

    if (type === 'post' ) {
        try {
            response = await axios.post(url, data, {
                headers: headers
            });
        } catch (error) {
            response = error.response;
        }
    }

    if (type === 'patch') {
        try {
            response = await axios.patch(url, data, {
                headers: headers
            });
        } catch (error) {
            response = error.response;
        }
    }

    if (type === 'delete' ) {
        try {
            response = await axios.delete(url, {
                headers: headers
            });
        } catch (error) {
            response = error.response;
        }
    }

    if (typeof response.data.text === 'object'){
        let string = '';

        for (let i in response.data.text) {
            string = string + response.data.text[i]+' ';
        }

        response.data.text = string;

    }
    return response;

}

export async function notAuthRequest (url ='', type='post', data={} ){

    let response = 'error';
    /* laravel csrf protect */
    await axios.get('/sanctum/csrf-cookie');

    if ( type === 'post' ) {
        try {
            response = await axios.post(url, data);
        } catch (error) {
            response = error.response;
        }
    }

    if (typeof response.data.text === 'object'){
        let string = '';

        for (let i in response.data.text) {
            string = string + response.data.text[i]+' ';
        }

        response.data.text = string;

    }
    return response;
}
