<template>
    <AuthMenu :auth='auth' />

    <div class="wrap-header max">
        <div class="header-col-1">
            <div class="logo">
                <img src="/images/ED_logo_4_2x.png" alt="logo">
            </div>
        </div>

        <div class="header-col-2">
            <div class="logo">
                <div class="title-1">Русский сервер</div>
                <div class="title-2">Supreme Commander Forged Alliance</div>
            </div>
        </div>

        <div class="header-col-3">
            <div>
                <div class="wrap-button">
                    <div class="button-01 hover-purple">
                        <a :href="'/client/RUSC1.zip?v'+getRandomNumber(0, 10000)">{{ $t('download_client') }}</a>
                    </div>
                    <div class="pop-up-download">
                        <div><a :href="'/client/RUSC1.zip?v'+getRandomNumber(0, 10000)">{{ $t('installer_1') }} </a></div>
                        <div><a :href="'/client/RUSC2.zip?v'+getRandomNumber(0, 10000)">{{ $t('installer_2') }}</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>



<script setup>

import AuthMenu from '@/components/AuthMenu.vue';
import {useRoute} from "vue-router";
import {useI18n} from "vue-i18n";
const { t, locale } = useI18n({ useScope: 'global' })

let props = defineProps({
    auth: Boolean
});

function getRandomNumber(min, max) {
    return Math.round(Math.random() * (max - min) + min);
}

const route = useRoute();

//изменение языка локализация
if (route.query.lang){
    if (route.query.lang === 'en'){
        locale.value  = 'en'
    }
    else if (route.query.lang === 'ru'){
        locale.value  = 'ru'
    }
}


</script>



<style scoped>
.problem-download {
    margin-top:10px;
}
.title-1 {
    color: #7A7A7A;
    font-size: 20px;
    text-transform:uppercase;
    text-align:center;
    line-height:20px;
}
.title-2{
    font-size: 34px;
    text-align:center;
    color: #4E41D9;
}

.button-01 {
    position: relative;
    overflow: hidden;
    display: inline-block;
    font-weight: bold;
    font-size: 14px;
    line-height: 19px;
    color: #4E41D9;
    border: 1px solid #4E41D9;
    box-sizing: border-box;
    border-radius: 4px;
    padding: 10px 25px;
    transition: 0.3s;
    cursor: pointer;
}

.button-01:before {
    content: "";
    height: 70px;
    width: 20px;
    background: #4E41D9;
    position: absolute;
    top: -21px;
    transform: rotate(24deg);
    left: -20%;
    transition: 0.4s;
}
.button-01:hover:before {
    left: 120%;
}
.button-01.hover-purple {
    background: #4E41D9;
    color: #fff;
}

.button-01.hover-purple:before { background: #fff; }
.button-01:hover {color: #4E41D9;}
.button-01.hover-purple:hover {color: #fff;}

.logo img {
    max-height: 150px;
    width: auto;
}

.wrap-header {
    display:flex;
}

.wrap-header > div {
    align-items:center;
    display: flex;
}

.wrap-header .header-col-1 {
    flex-basis:20%;
    padding-top:15px;
    padding-bottom:15px;
}
.wrap-header .header-col-2 {
    flex-basis:60%;
    padding-left:20px;
    padding-right:20px;
    justify-content: center;
    padding-top:15px;
    padding-bottom:15px;
}
.wrap-header .header-col-3 {
    flex-basis:20%;
    justify-content: right;
    padding-top:15px;
    padding-bottom:15px;
}

.wrap-admin-link span {
    color:#fff;
}

.button-01 a {
    color:#fff
}

.pop-up-download {
    position: absolute;
    /* background-color: #c2c2c2; */
    color: #fff;
    padding: 10px;
    border-radius: 3px;
    margin-top: 0px;
    border: 1px solid #4e41d9;
    display: none;
}
.pop-up-download a {
    color: #353535;
    line-height: 20px;
}
.pop-up-download a:hover {
    color: #4e41d9;
}

.wrap-button:hover  .pop-up-download{
    display: block;
}



</style>
