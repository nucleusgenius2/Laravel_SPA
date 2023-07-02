<template>
    <div class="wrap-admin-link" v-if="auth.status==='success'">
        <div class="max">
            <div class="admin-link-text">
                <span>Вы авторизованы: <span>{{ userEmail }}</span></span>
                <div class="wrap-menu">
                    <span class="menu"><a class="link" href='/profile'>Профиль</a> </span>
                    <span class="menu" v-if="auth.permission==='admin'"><a class="admin-link" href='/admin'>Перейти в админ панель</a> </span>
                </div>
                <span class="logout link" @click="logout">Выйти из аккаунта </span>
            </div>
        </div>
    </div>
</template>


<script setup>
import {onMounted, ref , watch} from "vue";
import {authRequest} from "@/api.js";


let auth = ref('');
let userEmail = ref('');

let props = defineProps({
    auth: String
});

//listen props
watch(() => props.auth, (selection, prevSelection) => {
    authorization();
})


onMounted(async () => {
    authorization();
});

async function authorization(){
    //check local store
    if (localStorage.getItem("token") !== null ) {
        let response = await authRequest('/api/authorization', 'get');
        if ( response.data.status === 'success' ){
            auth.value = response.data;
            userEmail.value = JSON.parse(localStorage.getItem('token')).user;
        }
        else {
            localStorage.removeItem('token');
        }
    }
}

//logout
async function logout() {
    let response = await authRequest('/api/logout/', 'get');
    if (response.data.status === 'success') {
        auth.value.status = '';
        localStorage.removeItem('token');
        window.location.replace("/login");
    }
    else {
        console.error(response.status);
    }
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
