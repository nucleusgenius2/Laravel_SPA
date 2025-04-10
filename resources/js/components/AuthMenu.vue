<template>
    <div class="wrap-admin-link" v-if="authUser.status==='success'">
        <div class="max">
            <div class="admin-link-text">
                <span>{{ $t('register_page_6') }}: <span>{{ userEmail }}</span></span>
                <div class="wrap-menu">
                    <span class="menu"><a class="link" href='/profile'>{{ $t('profile_auth_menu') }}</a> </span>
                    <span class="menu" v-if="authUser.permission==='admin'"><a class="admin-link" href='/admin'>{{ $t('admin_panel_auth_menu') }}</a> </span>
                </div>
                <span class="logout link" @click="logout">{{ $t('auth_logout') }} </span>
            </div>
        </div>
    </div>
</template>


<script setup lang="ts">
import {onMounted, ref , watch} from "vue";
import {authRequest} from "@/api.ts";
import type { authObject } from '@/types/auth';

let authUser = ref<authObject>({
    status: '',
    permission: ''
});

let userEmail = ref('');

let props = defineProps({
    auth: Boolean
});


watch(() => props.auth, () => {
    authorization();
});


onMounted(async () => {
    authorization();
});

async function authorization(){

    if (localStorage.getItem("token") ) {
        let response = await authRequest('/api/authorization', 'get');
        if ( response.data.status === 'success' ){
            authUser.value = response.data;

            let token = localStorage.getItem('token');
            if (token) {
                userEmail.value = JSON.parse(token).user;
            }
        }
        else {
            localStorage.removeItem('token');
        }
    }
}


async function logout() {
    let response = await authRequest('/api/logout/', 'get');
    if (response.data.status === 'success') {
        authUser.value.status = '';
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
