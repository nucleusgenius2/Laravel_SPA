<template>
    <div class="wrap-single-news">
        <div class="wrap-news">

            <div class="wrap-field">
                <div class="heading-field">Имя</div>
                <input class='field-admin' v-model="user.name">
            </div>

            <div class="wrap-field">
                <div class="heading-field">Email</div>
                <input class='field-admin' v-model="user.email">
            </div>

            <div class="wrap-field">
                <div class="heading-field">Статус пользователя</div>
                <input class='field-admin' v-model="user.status">
            </div>

            <div class="wrap-field">
                <div class="heading-field">Дата регистрации</div>
                <span>{{ convertTime(user.created_at) }} </span>
            </div>



            <div class="wrap-save">
                <!--<div class="save" @click="save()">Сохранить</div> -->
                <div class="text-status" v-if="saveStatus==='success' || saveStatus==='save_success_redirect'">
                    <span>Успешно сохранено</span>
                </div>
            </div>

        </div>
    </div>
</template>



<script setup lang="ts">
import {onMounted, ref} from 'vue';
import router from "@/router/router";
import {useRoute} from "vue-router";
import {authRequest} from "@/api.ts";

import type { UserItem } from '@/types/user';
import {convertTime} from "@/script/convertTime.ts";
const route = useRoute();

let user = ref<UserItem >({
    id: 0,
    name: '',
    email: '',
    status: 0,
    icons: '',
    created_at: '',
    updated_at: '',
});
let textEditor = ref('');
let saveStatus = ref('');


onMounted(
    async () => {
        if (route.params.id !== 'add') {
            let response = await authRequest('/api/users/' + route.params.id, 'get');

            if ( response.data.status === 'success' ){
                console.log(response.data)
                user.value = response.data.json;
            }
            else {
                router.push({
                    name: '404',
                    query: { textError: encodeURIComponent(response.data.text) }
                });

            }
        }
    }
);


</script>


<style scoped>
.text-status {
    color: #09be92;
    padding: 10px;
    display: inline-block;
    cursor: pointer;
    font-weight:600
}
.wrap-save {
    display:flex;
}
.img-field img {
    max-width:100px;
    margin-right:30px;
}
.img-field .field-admin {
    margin-bottom: 0px;
}
.wrap-field {
    margin-bottom: 30px;
}
.field-admin {
    font-size: 13px;
    border-color: #c2c2bf;
    background-color: rgb(249, 249, 249);
    border-radius: 3px;
    border-width: 1px;
    height: 35px;
    padding: 1px 2px 1px 10px;
    outline: none !important;
    transition: 0.3s;
    width: 100%;
    vertical-align: middle;
    box-shadow: 0 0px 0px rgba(0, 0, 0, 0.075) inset !important;
    border-style: solid;
    margin: 0px;
    box-sizing: border-box;
    max-width: 100%;
}
input:focus, textarea:focus {
    border-color: #4e41d9;
}

.heading-field {
    margin-bottom: 5px;
    font-weight: 600;
}

.save {
    background-color: #09be92;
    color:#fff;
    padding:10px;
    display: inline-block;
    cursor: pointer;
}
.save:hover {
    background-color: #099d79;
}

.textarea-field {
    min-height: 80px;
    padding-top: 10px;
}



</style>
