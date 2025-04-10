<template>
    <div class="wrap-news">
        <!-- фильтр -->
        <div class="wrap-filter">
            <div class="wrap-field">
                <div class="heading-field text">{{ $t('news_page_1') }}</div>
                <input class='field-admin' v-model="filter.name">
            </div>

            <div class="wrap-field">
                <div class="heading-field text">{{ $t('news_page_2') }}</div>
                <VueDatePicker
                    v-model="filter.created_at_from"
                    :max-date="new Date()"
                    prevent-min-max-navigation
                    model-type="dd.MM.yyyy"
                    auto-apply
                    :placeholder="$t('DD - MM - YYYY')"
                    :enable-time-picker="false"
                    :locale="locale"
                    format="dd/MM/yyyy"
                />
            </div>


            <div class="wrap-field">
                <div class="heading-field text">{{ $t('news_page_3') }}</div>
                <VueDatePicker
                    v-model="filter.created_at_to"
                    :max-date="new Date()"
                    prevent-min-max-navigation
                    model-type="dd.MM.yyyy"
                    auto-apply
                    :placeholder="$t('DD - MM - YYYY')"
                    :enable-time-picker="false"
                    format="dd/MM/yyyy"
                    :locale="locale"
                />
            </div>

            <div class="wrap-field">
                <div class="heading-field text">{{ $t('news_page_4') }}</div>
                <select class='field-admin' v-model="filter.date_fixed">
                    <option value="day">{{ $t('news_page_5') }}</option>
                    <option value="week">{{ $t('news_page_6') }}</option>
                    <option value="month">{{ $t('news_page_7') }}</option>
                    <option value="year">{{ $t('news_page_8') }}</option>
                </select>
            </div>


            <div class="wrap-button-submit" style="margin-right:20px;">
                <div class="button-blue-all button-admin"  style="margin-bottom: 10px;" @click="paginationListing('filter')" >
                    {{ $t('apply_filter') }}
                </div>
            </div>

            <div class="wrap-button-submit">
                <div class="button-blue-all-style-2 button-admin" style="margin-bottom: 10px;"  @click="clearFilter" >
                    {{ $t('clear_filter') }}
                </div>
            </div>
        </div>


        <div class="news-list">
            <div class="post-el" v-for="(post) in arrayPosts">
                <div class="heading-post">{{ post.name }}</div>
                <div class="wrap-section-post">
                    <div class="thumb-post"><img v-if="typeof post.img==='string'" :src="post.img" alt="new-thumb"></div>
                    <div class="post-short-text">
                        <div class="content-short-post">{{ post.short_description }}</div>
                    </div>
                </div>
                <a :href="'/posts/'+post.id"> {{ $t('news_page_9') }}</a>
            </div>

        </div>

        <pagination v-model="pageModel" :records="pageTotal" :per-page="1" @paginate="paginationListing"/>

        <div class="empty-list" v-if="emptyPage">{{ $t('news_page_10') }}</div>

    </div>
</template>




<script setup lang="ts">
import {ref} from 'vue';
import {authRequest} from "@/api.ts";
import VueDatePicker from "@vuepic/vue-datepicker";
import '@vuepic/vue-datepicker/dist/main.css'
//@ts-ignore
import Pagination from "v-pagination-3";
import {useI18n} from "vue-i18n";
const { t, locale } = useI18n({ useScope: 'global' })
import {PostFilter, PostItem} from '@/types/post';

let arrayPosts = ref<PostItem[]>([]);
let emptyPage = ref(false);
let pageModel = ref(1)
let pageTotal = ref(1)
let filter = ref<PostFilter>({
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

    if (typeof response !== 'string' && response.data.status === 'success') {
        emptyPage.value = false;
        arrayPosts.value = response.data.json.data;
        pageTotal.value = response.data.json.last_page;
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
    filter.value.date_fixed = '';
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

</style>
