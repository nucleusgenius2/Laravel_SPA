<template>
    <div class="wrap-news">
        <div class="news-list">
            <div>Фильтр юзеров надо сделать</div>

            <div class="post-heading-block table-users">
                <div class="post-name">Ник</div>
                <div class="user-email">Email</div>
                <div class="user-ip">Последний ip</div>
                <div class="user-status">Статус</div>
                <div class="wrap-date">Дата регистрации</div>
                <div class="wrap-rate">Рейтинг</div>
                <div class="wrap-icons">Иконки</div>
            </div>

            <div class="post-el table-users" v-for="(user, name) in arrayPostEl">
                <div><a :href="'/admin/users/'+user.id" class="post-name">{{ user.name }}</a></div>
                <div class="user-email">{{ user.email }}</div>
                <div class="user-ip">сделать вывод</div>
                <div class="user-status">{{ user.status }}</div>
                <div class="wrap-date">{{ user.created_at  }}</div>
                <div class="wrap-rate">сделать вывод</div>
                <div class="wrap-icons">сделать вывод</div>
            </div>

            <div class="pagination-post">
                <div class="pagination-el" v-for="(value, name) in arrayPagination">
                    <div @click="getPostsList(value['url'])" >{{ value['label'] }}</div>
                </div>
            </div>

        </div>
    </div>
</template>


<script setup>

import {onMounted, ref} from 'vue';
import router from "@/router/router";
import { useRoute } from "vue-router";
import {authRequest} from "@/api.js";

let props = defineProps({
    total: String,
})

const route = useRoute();

let arrayPostEl = ref([]);
let arrayPagination = ref([]);

async function getPostsList (page){
    let response = await authRequest('/api//users?page='+page, 'get' );

    if ( response.data.status === 'success' ){
        let arrayPost = response.data.json.data;
        let arrayLink = response.data.json.links;

        //short description post
        for (let i = 0; i < arrayPost.length; i++) {
            //converting date
            arrayPost[i]['created_at'] = new Date(arrayPost[i]['created_at']).toLocaleString();
            arrayPost[i]['updated_at'] = new Date(arrayPost[i]['updated_at']).toLocaleString();
        }

        //pagination array localization
        arrayLink[0]['label'] = 'В начало';
        arrayLink[arrayLink.length - 1]['label'] = 'В конец';

        for (let i = 0; i < arrayLink.length; i++) {
            let page = i;
            //first link pagination
            if (page === 0) {
                page = 1;
            }
            //last link pagination
            else if (page === arrayLink.length - 1) {
                page = arrayLink.length - 2;
            }

            arrayLink[i]['url'] = page;
        }

        arrayPagination.value = arrayLink;
        arrayPostEl.value = arrayPost;
    }

}
getPostsList(1);


</script>


<style scoped>

.post-el {
    display: flex;
    width: 100%;
    border: 1px solid #c1c0c0;
    align-items: center;
    background-color: #fcfcfc;
    margin-top: -1px;
}

.post-el:nth-child(odd) {
    background-color: #ededed;
}

.post-el:hover {
    background-color: #d4d4d4;
}

.thumb-post img {
    max-width: 100px;
}

.post-name {
    padding-left: 20px;
    color: #367c75;
    text-decoration: none;
    transition: 0.3s;
    font-size: 16px;
    font-weight: 600;
}

.table-users > div  {
    flex-basis: 16%;
}


.pagination-post {
    display: flex;
    margin-top:20px;
}

.pagination-el {
    margin-left: 10px;
    margin-right: 10px;
}
.pagination-el:first-child{
    margin-left: 0px;
}

.post-heading-block {
    display: flex;
    width: 100%;
    border: 1px solid #c1c0c0;
    align-items: center;
    background-color: #383838;
    color: #fff;
    margin-top: 30px;
}

.post-heading-block div {
    padding-top: 5px;
    padding-bottom: 5px;
}

.post-heading-block .post-name {
    color: #fff;
    font-weight:400;
}

.remove-post {
    padding-left: 20px;
    text-align: right;
    font-size: 12px;
    color: #b50707;
    cursor: pointer;
}

.pagination-el div {
    cursor: pointer;
}


.news-list {
    margin-top:20px
}


</style>
