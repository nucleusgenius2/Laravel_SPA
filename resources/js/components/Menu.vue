<template>
    <div class="wrap-menu">
        <div class="main-menu max">
            <div class="menu-el">
                <router-link to="/">Главная</router-link>
            </div>

            <div class="menu-el sub-menu">
                <span class="menu-el">Топ игроки</span>
                <div class="wrap-sub-menu">
                    <div class="menu-el">
                        <a href="/">Топ игроки дня</a>
                    </div>
                    <div class="menu-el">
                        <a href="/">Топ игроки месяца</a>
                    </div>
                </div>
            </div>

            <div class="menu-el">
                <router-link to="/post-list/1">Новости</router-link>
            </div>

            <div class="menu-el">
                <router-link to="/registration">Регистрация</router-link>
            </div>

            <div class="menu-el">
                <router-link to="/login">Логин</router-link>
            </div>

        </div>
    </div>
</template>


<style scoped>
    .wrap-menu {
        background-color: #151515;
    }
    .main-menu {
        display:flex;
        justify-content: center;
    }

    .sub-menu {
        position: relative;
    }
    .wrap-sub-menu {
        display:none;
        position: absolute;
        background-color: #fff;
        flex-direction: column;
        width: 250px;
        left: 0px;
        border-top: 2px solid #999;
        box-shadow: 0 1px 6px rgb(0 0 0 / 40%);
    }
    .sub-menu:hover  .wrap-sub-menu {
        display:flex;
    }

    .menu-el a, .menu-el span{
        padding: 20px 25px;
        color: #fff;
        text-transform: uppercase;
        border-radius: 10px;
        text-decoration: none;
        display: inline-block;
    }
    .menu-el {
        border-radius: 10px;
        margin-left: 10px;
        margin-right: 10px;
    }

    .sub-menu a{
        color:#000;
        border-radius:0px;
        transition: 0s;
    }

    .wrap-sub-menu .menu-el {
        border-radius: 0px;
        margin-left: 0px;
        margin-right: 0px;
    }

    .menu-el:hover{
        background-color:#4e41d9;
    }
    .menu-el a:hover{
        color:#fff;
    }
    .wrap-menu {
        margin-bottom: 30px;
    }

</style>
