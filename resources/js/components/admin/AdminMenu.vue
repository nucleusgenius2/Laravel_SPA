<template>
    <div class="wrap-admin-menu">
        <div>
            <router-link :class="{'active': tab.tab1 }" @click="active('tab1')" to="/admin/">Главная</router-link>
        </div>
        <div>
            <router-link :class="{'active': tab.tab2 }" @click="active('tab2')" to="/admin/posts">Новости</router-link>
        </div>
        <div>
            <router-link :class="{'active': tab.tab3 }" @click="active('tab3')"  to="/admin/users">Пользователи</router-link>
        </div>
        <div>
            <router-link :class="{'active': tab.tab4 }" @click="active('tab4')" to="/admin/mods">Моды</router-link>
        </div>
        <div>
            <router-link :class="{'active': tab.tab5 }" @click="active('tab5')" to="/admin/maps">Карты</router-link>
        </div>
    </div>
</template>

<script setup>
import {ref} from "vue";

let tab = ref({
    tab1 : false,
    tab2 : false,
    tab3 : false,
    tab4 : false,
    tab5 : false,
});

function active(e) {
    tab.value.tab1 = false;
    tab.value.tab2 = false;
    tab.value.tab3 = false;
    tab.value.tab4 = false;
    tab.value.tab5 = false;
}
</script>

<style scoped>
    .wrap-admin-menu {
        display:flex;
        margin-top:30px;
    }
    .wrap-admin-menu a{
        margin-right: 10px;
        padding:10px;
    }
    .wrap-admin-menu a {
        transition: 0s;
    }
    .wrap-admin-menu a:hover{
        background-color: #8b8b8b;
    }
    .wrap-admin-menu a:hover  {
        color:#fff;
    }
</style>
