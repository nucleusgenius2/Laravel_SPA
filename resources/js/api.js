let token = '';
if (localStorage.getItem("token") !== null ) {
    token = JSON.parse(localStorage.getItem('token'));
}
export const headers = {
    accept: 'application/json',
    Authorization: 'Bearer ' + token.token
}
