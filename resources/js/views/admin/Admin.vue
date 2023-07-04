<template>
    <MainLayout :layout = 'route.meta.layout'>
        <template #content>

            <div class="wrap-admin-link" v-if="auth">
                <div class="max">
                    <div class="admin-link-text">
                        <span>Вы авторизованы: <span>{{ userEmail }}</span></span>

                        <div class="wrap-menu">
                            <span class="menu"><a class="link" href='/profile'>Профиль</a> </span>
                            <span class="menu"><a class="admin-link" href='/'>Вернутся на сайт</a> </span>
                        </div>
                        <span class="logout" @click="logout">Выйти из аккаунта </span>

                    </div>
                </div>
            </div>

            <div class="wrap-dashboard admin-panel max">
                <AdminMenu/>
                <router-view />
            </div>

        </template>
    </MainLayout>
</template>




<script setup>
import AdminMenu from '@/components/admin/AdminMenu.vue';
import {onMounted, ref} from 'vue';
import { useRoute } from "vue-router";
import {authRequest} from "@/api.js";

let auth = ref('');
let userEmail = ref('');
const route = useRoute();

//check auth user
onMounted(async () => {
    //check local store
    if (localStorage.getItem("token") !== null) {

        //set token in axios header
        let response = await authRequest('/api/authorization', 'get');

        auth.value = response.data;
        userEmail.value = JSON.parse(localStorage.getItem('token')).user
    }
});


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
        background-color:#000;
        padding:10px;
    }

    .wrap-admin-link span {
        color:#fff;
    }

    .admin-link-text {
        display:flex;
        justify-content: space-between;
    }

    .dashboard-button a {
        padding: 10px;
    }
    .dashboard-button a:hover{
        background-color: #8b8b8b;
        color: #fff;
    }

    .logout {
        color:#fff;
        cursor:pointer;
    }
    .logout:hover{
        color:#d8d8d8;
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
