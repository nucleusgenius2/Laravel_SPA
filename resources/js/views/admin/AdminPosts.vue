<template>
    <div class="wrap-news">
        <div class="news-list">
            <a class="add-new" href="/admin/posts/add">Добавить новость</a>

            <!-- фильтр -->
            <div class="wrap-filter">
                <div class="wrap-field">
                    <div class="heading-field text">Название</div>
                    <input class='field-admin' v-model="filter.name">
                </div>

                <div class="wrap-field">
                    <div class="heading-field text">Дата публикации от</div>
                    <VueDatePicker
                        v-model="filter.created_at_from"
                        :max-date="new Date()"
                        prevent-min-max-navigation
                        model-type="dd.MM.yyyy"
                        auto-apply
                        placeholder="ДД - MM - ГГГГ"
                        :enable-time-picker="false"
                        locale="ru"
                        format="dd/MM/yyyy"
                    />
                </div>


                <div class="wrap-field">
                    <div class="heading-field text">Дата публикации до</div>
                    <VueDatePicker  v-model="filter.created_at_to"
                                    :max-date="new Date()"
                                    prevent-min-max-navigation
                                    model-type="dd.MM.yyyy"
                                    auto-apply
                                    placeholder="ДД - MM - ГГГГ"
                                    :enable-time-picker="false"
                                    format="dd/MM/yyyy"
                                    locale="ru"
                    />
                </div>

                <div class="wrap-field">
                    <div class="heading-field text">Выборка</div>
                    <select class='field-admin' v-model="filter.date_fixed">
                        <option value="day">Новости за день</option>
                        <option value="week">Новости за неделю</option>
                        <option value="month">Новости за месяц</option>
                        <option value="year">новости за год</option>
                    </select>
                </div>

                <div class="wrap-button-submit" style="margin-right:20px;">
                    <div class="button-blue-all button-admin"  style="margin-bottom: 10px;" @click="paginationListing('filter')" >
                        Применить фильтр
                    </div>
                </div>

                <div class="wrap-button-submit">
                    <div class="button-blue-all-style-2 button-admin" style="margin-bottom: 10px;"  @click="clearFilter" >
                        Очистить
                    </div>
                </div>
            </div>

            <div class="post-heading-block">
                <div class="post-name">Название новости</div>
                <div class="post-author">Автор</div>
                <div class="wrap-date">
                    <div class="post-date-c"><span>Дата создания и обновления:</span> </div>
                </div>
            </div>

            <div class="post-el" v-for="(post) in arrayPosts">
                <a :href="'/admin/posts/'+post.id" class="post-name">{{ post.name }}</a>
                <div class="post-author">{{ post.author }}</div>
                <div class="wrap-date">
                    <div class="post-date-c"><span>Дата создания:</span> <span>{{ convertTime(post.created_at) }}</span></div>
                    <div class="post-date-u"><span>Дата обновления:</span> <span>{{ convertTime(post.updated_at) }}</span></div>
                </div>
                <div class="remove-post" @click="removePost" :data-id="post.id">Удалить</div>
            </div>


            <pagination v-model="pageModel" :records="pageTotal" :per-page="1" @paginate="paginationListing"/>

        </div>
    </div>
</template>


<script setup>

import {ref} from 'vue';
import { useRoute } from "vue-router";
import {authRequest} from "@/api.ts";
import Pagination from "v-pagination-3";
import {convertTime} from "@/script/convertTime";
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
let pageModel = ref(1)
let pageTotal = ref(1)
let filter = ref({
    'name' : '',
    'created_at_from' : '',
    'created_at_to' : '',
    'date_fixed' : ''
});

const route = useRoute();
let arrayPosts = ref([]);

async function paginationListing(filterClick = '') {
    if (filterClick === 'filter') {
        pageModel.value = 1;
    }
    let stringFilter = '&admin=true';
    if (filter.value.name !== '') {
        stringFilter += '&name=' + filter.value.name;
    }
    if (filter.value.created_at_from && filter.value.created_at_from !== '') {
        stringFilter += '&created_at_from=' + filter.value.created_at_from;
    }
    if (filter.value.created_at_to && filter.value.created_at_to !== '') {
        stringFilter += '&created_at_to=' + filter.value.created_at_to;
    }
    if (filter.value.date_fixed!== '') {
        stringFilter += '&date_fixed=' + filter.value.date_fixed;
    }


    let response = await authRequest('/api/posts?page=' + pageModel.value + stringFilter, 'get');

    if (response.data.status === 'success') {
        arrayPosts.value = response.data.json.data;
        pageTotal.value = response.data.json.last_page;
    }
    else{
        arrayPosts.value = []
    }
}
paginationListing();

function clearFilter (){
    filter.value.name = '';
    filter.value.created_at_from = '';
    filter.value.created_at_to = '';
    filter.value.chunk = '';
    paginationListing();
}


async function removePost(e){
    let id = e.target.getAttribute('data-id');

    let response = await authRequest('/api/posts/'+id, 'delete');
    if ( response.data.status==='success' ){
        e.target.closest('.post-el').remove();
    }
    else {
        console.error(response.data.status);
    }
}
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
    flex-basis: 40%;
    padding-left: 20px;
    color: #367c75;
    text-decoration: none;
    transition: 0.3s;
    font-size: 16px;
    font-weight: 600;
}

.post-author {
    flex-basis: 20%;
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

.wrap-date {flex-basis: 25%}

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

.add-new {
    background-color: #13af3b;
    display: inline-block;
    padding: 10px;
    color: #fff;
    text-decoration: none;
    transition: 0.3s;
    margin-bottom: 10px;
}

.news-list {
    margin-top:20px
}


</style>
