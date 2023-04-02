<template>
    <div class="wrap-news">
        <div class="news-list">
            <div class="post-el" v-for="(value, name) in arrayPostEl">
                <div class="heading-post">{{ value['name'] }}</div>
                <div class="wrap-section-post">
                    <div class="thumb-post"><img :src="value['img']" alt="new-thumb"></div>
                    <div class="post-short-text">
                        <div class="content-short-post">{{ value['short_description'] }}</div>
                    </div>
                </div>
                <a :href="'/post/'+value['id']">Читать далее</a>
            </div>

            <div class="pagination-post" v-if="props.pagination === 'true' ">
                <div class="pagination-el" v-for="(value, name) in arrayPagination">
                    <a :href="value['url']">{{ value['label'] }}</a>
                </div>
            </div>
        </div>
    </div>
</template>




<script setup>
import {onMounted, ref} from 'vue';
import {useRoute} from "vue-router";
import {authRequest} from "@/api.js";

let props = defineProps({
    total: String,
    pagination: String,
})


const route = useRoute();

let arrayPostEl = ref([]);
let arrayPagination = ref([]);

onMounted(
    async () => {
        let response = await authRequest('/api/post-list/' + props.total + '/all?page=' + route.params.page, 'get' );
        let arrayPost = response.data.data;
        let arrayLink = response.data.links;

        //short description post
        for (let i = 0; i < arrayPost.length; i++) {
            if (arrayPost[i]['short_description'].length > 30) {
                arrayPost[i]['short_description'] = arrayPost[i]['short_description'].slice(0, 80) + '...';
            }
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
            arrayLink[i]['url'] = '/post-list/10/' + page;
        }

        arrayPagination.value = arrayLink;
        arrayPostEl.value = arrayPost;

    }
);

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
