<template>
    <div class="wrap-admin-link" v-if="auth.status=='success'">
        <div class="max">
            <div class="admin-link-text">
                <span>Вы авторизованы: <span>{{ userEmail }}</span></span>
                <div class="wrap-menu">
                    <span class="menu"><a class="link" href='/profile'>Профиль</a> </span>
                    <span class="menu" v-if="auth.permission=='admin'"><a class="admin-link" href='/admin/all/all/1'>Перейти в админ панель</a> </span>
                </div>
                <span class="logout link" @click="logout">Выйти из аккаунта </span>
            </div>
        </div>
    </div>
</template>


<script setup>
import axios from "axios";
import {onMounted, ref} from "vue";
import {headers} from "@/api.js";

let auth = ref('');
let userEmail = ref('');


defineExpose({
    auth
})


//check auth user
onMounted(async () => {
    //check local store
    if (localStorage.getItem("token") !== null && localStorage.getItem("user") !== null) {

        //set token in axios header
        await axios.get('/api/authorization', {
            headers: headers
        })
            .then(response => {
                auth.value = response.data;
                userEmail.value = JSON.parse(localStorage.getItem('token')).user;
            })
            .catch(err => {
                //delete an outdated token
                localStorage.removeItem('token');
            });
    }

});


//logout
async function logout() {

    //set token in axios header
    axios.get('/api/logout/', {
        headers: headers
    })
        .then(response => {
            if (response.data.status == 'success') {
                auth.value.status = '';
                localStorage.removeItem('token');
            }
        })
        .catch(err => {
            //delete an outdated token
            console.error(err);
        });;

}
</script>

<style scoped>
    .wrap-admin-link {
        background-color: #000;
        padding: 10px;
    }

    .wrap-admin-link span {
        color: #fff;
    }

    .admin-link-text {
        display: flex;
        justify-content: space-between;
    }

    .link {
        color: #fff;
        cursor: pointer;
    }

    .link:hover {
        color: #d8d8d8;
    }

    .admin-link {
        color: #6acb55;
    }

    .admin-link:hover {
        color: #48a235;
    }

    .menu {
        position: relative;
        margin-right: 20px;
    }

    .menu:after {
        position: absolute;
        content: "";
        top: 0px;
        bottom: 0px;
        right: -10px;
        width: 1px;
        height: 22px;
        background-color: #fff;
    }

</style>
