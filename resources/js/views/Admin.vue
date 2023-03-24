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
                <div class="dashboard-col-1">
                    <div class="wrap-dashboard-button">
                        <div class="dashboard-button news"><a href="/admin/news/10/1">Новости</a></div>
                    </div>
                </div>

                <div class="dashboard">
                    <!-- news list -->
                    <div class="tab-admin" v-if="route.params.edit =='news' && route.params.id !='1'">

                        <a class="add-new" href="/admin/post/add/edit">Добавить новость</a>
                        <div class="wrap-list-news">
                            <NewsList total="10" pagination="true"/>
                        </div>

                    </div>

                    <!-- single editor news -->
                    <div class="tab-admin" v-if="route.params.edit =='post'" data-tab="editor-news">

                        <div class="wrap-list-news">
                            <EditNews />
                        </div>

                    </div>

                </div>
            </div>

        </template>
    </MainLayout>
</template>




<script setup>
import NewsList from '@/components/admin/NewsList.vue';
import EditNews from '@/components/admin/EditNews.vue';
import axios from "axios";
import {onMounted, ref} from 'vue';
import { useRoute } from "vue-router";
import {headers} from "@/api.js";

let auth = ref('');
let userEmail = ref('');
const route = useRoute();

//check auth user
onMounted(async () => {
    //check local store
    if (localStorage.getItem("token") !== null && localStorage.getItem("user") !== null) {

        //set token in axios header
        let response = await axios.get('/api/authorization', {
            headers: headers
        })

        auth.value = response.data;
        userEmail.value = JSON.parse(localStorage.getItem('token')).user
    }

});

//logout
async function logout(){

    //set token in axios header
    axios.get('/api/logout/', {
        headers: headers
    }).then(response => {
        if ( response.data.status == 'success' ){
            window.location.replace("/")
        }
    });

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

    .add-new {
        background-color: #13af3b;
        display: inline-block;
        padding: 10px;
        color: #fff;
        text-decoration: none;
        transition: 0.3s;
        margin-bottom: 10px;
    }
    .dashboard-button {
        background-color: #ffffff;
        margin-top: 15px;
        margin-bottom: 30px;
        display: flex;
        border-bottom: 1px solid #5d5d5d;
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
