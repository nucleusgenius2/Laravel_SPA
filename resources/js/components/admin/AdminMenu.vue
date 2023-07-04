<template>
    <div class="wrap-admin-menu">
        <div>
            <router-link to="/admin/">Главная</router-link>
        </div>
        <div>
            <router-link to="/admin/posts">Новости</router-link>
        </div>
        <div>
            <router-link to="/admin/users">Пользователи</router-link>
        </div>
    </div>
</template>

<script>
export default {
    name: "adminMenu.vue"
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
