import axios from "axios";

interface Token {
    token: string | null;
}

export async function authRequest (url: string ='', type: 'get' | 'post' | 'patch' | 'delete' = 'get', data: any ={}) {

    let token: Token | null = null;
    let response: any | string = 'error';

    const storedToken = localStorage.getItem("token");

    if (storedToken !== null) {
        token = JSON.parse(storedToken) as Token | null;
    }
    else{
        console.log('ошибка получения токена')
        return response
    }

    if (token === null) {
        console.log('ошибка получения токена')
        return response
    }

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
            if (axios.isAxiosError(error) && error.response) {
                response = error.response;
            } else {
                response = 'Ошибка запроса';
            }
        }
    }

    if (type === 'post' ) {
        try {
            response = await axios.post(url, data, {
                headers: headers
            });
        } catch (error) {
            if (axios.isAxiosError(error) && error.response) {
                response = error.response;
            } else {
                response = 'Ошибка запроса';
            }
        }
    }

    if (type === 'patch') {
        try {
            response = await axios.patch(url, data, {
                headers: headers
            });
        } catch (error) {
            if (axios.isAxiosError(error) && error.response) {
                response = error.response;
            } else {
                response = 'Ошибка запроса';
            }
        }
    }

    if (type === 'delete' ) {
        try {
            response = await axios.delete(url, {
                headers: headers
            });
        } catch (error) {
            if (axios.isAxiosError(error) && error.response) {
                response = error.response;
            } else {
                response = 'Ошибка запроса';
            }
        }
    }

    if (typeof response.data.text === 'object'){
        let string: string = '';

        for (let i in response.data.text) {
            string = string + response.data.text[i]+' ';
        }

        response.data.text = string;
    }

    return response;
}

export async function notAuthRequest (url: string ='', type: 'get' | 'post' | 'patch' | 'delete' = 'get', data={} ){

    let response: any | string = 'error';
    /* laravel csrf protect */
    await axios.get('/sanctum/csrf-cookie');

    if ( type === 'post' ) {
        try {
            response = await axios.post(url, data);
        } catch (error) {
            if (axios.isAxiosError(error) && error.response) {
                response = error.response;
            } else {
                response = 'Ошибка запроса';
            }
        }
    }

    if (typeof response.data.text === 'object'){
        let string: string  = '';

        for (let i in response.data.text) {
            string = string + response.data.text[i]+' ';
        }

        response.data.text = string;

    }

    return response;
}
