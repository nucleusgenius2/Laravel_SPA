<template>
    <div class="wrap-menu">
        <div class="main-menu max">
            <div class="menu-el adaptive-menu">
                <router-link to="/"> {{ $t('home-menu') }} </router-link>
            </div>

            <div class="menu-el adaptive-menu">
                <router-link to="/question">{{ $t('answers-menu') }}</router-link>
            </div>

             <div class="menu-el">
                 <router-link to="/post-list/1">{{ $t('news-menu') }}</router-link>
             </div>

            <div class="menu-el adaptive-menu">
                <router-link to="/registration">{{ $t('registration') }} </router-link>
            </div>

            <div class="menu-el adaptive-menu">
                <router-link to="/login">{{ $t('login') }} </router-link>
            </div>

            <div class="lang-menu adaptive-menu">
                <div :class="{ 'active': locale === 'ru'}"  @click="locale = 'ru'"><img src="/images/ru.png" alt="logo" width="40" height="40" /></div>
                <div :class="{ 'active': locale === 'en'}"  @click="locale = 'en'"><img src="/images/sh.jpg" alt="logo" width="40" height="40" /></div>
            </div>



            <div class="menu-el sub-menu mobile-menu">
                <span class="menu-el"><span class="mobile-toggle"><span></span><span></span><span></span></span></span>
                <div class="wrap-sub-menu">
                    <div class="menu-el">
                        <router-link to="/">{{ $t('home-menu') }}</router-link>
                    </div>

                    <div class="menu-el">
                        <router-link to="/question">{{ $t('answers-menu') }}</router-link>
                    </div>

                    <div class="menu-el">
                        <router-link to="/post-list/1">{{ $t('news-menu') }}</router-link>
                    </div>

                    <div class="menu-el">
                        <router-link to="/login">{{  $t('login') }} </router-link>
                    </div>

                    <div class="menu-el">
                        <router-link to="/registration">{{ $t('registration') }}</router-link>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {useI18n} from "vue-i18n";
const { t, locale } = useI18n({ useScope: 'global' })
//locale.value = 'en' //смена языка

</script>


<style scoped>

.lang-menu {
    display: flex;
    align-items: center;
}

.lang-menu div {
    margin: 5px;
    cursor: pointer;
}

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

.mobile-toggle {
    position: relative;
    display: flex!important;
    flex-direction: column;
    padding: 0px!important
}
.mobile-toggle span {
    margin-bottom:3px;
    width:20px;
    height:2px;
    background-color: #fff;
    padding: 0px
}
.mobile-menu {
    display: none;
}
.mobile-menu .wrap-sub-menu {
    top: 53px;
}

.lang-menu div {
    border:1px solid transparent;
}

.lang-menu .active{
    border-color:#fff;
}

@media(max-width: 880px){
    .adaptive-menu {
        display:none
    }
    .mobile-menu{
        display:flex;
    }
    .main-menu {
        justify-content: flex-start;
    }
}


</style>

