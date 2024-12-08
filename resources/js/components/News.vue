<template>
    <div class="wrap-news">
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


        <div class="news-list">
            <div class="post-el" v-for="(post) in arrayPosts">
                <div class="heading-post">{{ post.name }}</div>
                <div class="wrap-section-post">
                    <div class="thumb-post"><img :src="post.img" alt="new-thumb"></div>
                    <div class="post-short-text">
                        <div class="content-short-post">{{ post.short_description }}</div>
                    </div>
                </div>
                <a :href="'/posts/'+post.id">Читать далее</a>
            </div>

        </div>

        <pagination v-model="pageModel" :records="pageTotal" :per-page="10" @paginate="paginationListing"/>

        <div class="empty-list" v-if="emptyPage">По вашему запросу не найдено новостей</div>

    </div>
</template>




<script setup>
import {ref} from 'vue';
import {useRoute} from "vue-router";
import {authRequest} from "@/api.js";
import VueDatePicker from "@vuepic/vue-datepicker";
import '@vuepic/vue-datepicker/dist/main.css'
import Pagination from "v-pagination-3";
let props = defineProps({
    total: String,
    page: String,
    pagination : String,
});

const route = useRoute();

let arrayPosts = ref([]);
let emptyPage = ref(false);

let pageModel = ref(1)
let pageTotal = ref(1)
let filter = ref({
    'name' : '',
    'created_at_from' : '',
    'created_at_to' : '',
    'date_fixed' : ''
});

async function paginationListing(filterClick = '') {
    if (filterClick === 'filter') {
        pageModel.value = 1;
    }
    let stringFilter = '';
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
        emptyPage.value = false;
        arrayPosts.value = response.data.json.data;
        pageTotal.value = response.data.json.last_page * 10;
    }
    else{
        arrayPosts.value = []
        emptyPage.value = true;
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
</script>


<style scoped>
    .post-el {
        display: flex;
        flex-direction: column;
        width: 50%;
        margin-bottom: 30px;
    }

    .thumb-post img {
        max-width: 100px;
    }

    .news-list {
        display: flex;
        flex-wrap: wrap;
        margin-top: 30px;
    }

    .wrap-section-post {
        display: flex;
    }

    .post-el:nth-child(odd) {
        padding-right: 30px;
    }

    .post-el:nth-child( even) {
        padding-left: 30px;
    }

    .post-short-text {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .content-short-post {
        max-width: 400px;
        margin: 10px 0;
        padding-left: 20px;
    }

    .heading-post {
        font-size: 20px;
    }

    .pagination-post {
        display: flex;
    }

    .pagination-el {
        margin-left: 10px;
        margin-right: 10px;
    }

    .pagination-el:first-child {
        margin-left: 0px;
    }


</style>
