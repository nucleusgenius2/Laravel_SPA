<template>
    <div class="wrap-news">
        <div class="news-list">
            <div class="post-el" v-for="(value, name) in array_post">
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
                <div class="pagination-el" v-for="(value, name) in array_pagination">
                    <a :href="value['url']">{{ value['label'] }}</a>
                </div>
            </div>
        </div>
    </div>
</template>




<script setup>
import {onMounted, ref} from 'vue';
import {useRoute} from "vue-router";

let props = defineProps({
    total: String,
    pagination: String,
})


const route = useRoute();

let array_post = ref([]);
let array_pagination = ref([]);

onMounted(
    async () => {
        let response = await fetch('/api/news/' + props.total + '/all?page=' + route.params.page);
        if (response.ok) {
            let json = await response.json();
            //short description post
            for (let i = 0; i < json['data'].length; i++) {
                if (json['data'][i]['short_description'].length > 30) {
                    json['data'][i]['short_description'] = json['data'][i]['short_description'].slice(0, 80) + '...';
                }
            }
            //pagination array localization
            json['links'][0]['label'] = 'В начало';
            json['links'][json['links'].length - 1]['label'] = 'В конец';

            for (let i = 0; i < json['links'].length; i++) {
                let page = i;
                //first link pagination
                if (page == 0) {
                    page = 1;
                }
                //last link pagination
                else if (page == json['links'].length - 1) {
                    page = json['links'].length - 2;
                }
                json['links'][i]['url'] = '/news/10/' + page;
            }

            array_pagination.value = json['links'];
            array_post.value = json['data'];

        }
    });

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
