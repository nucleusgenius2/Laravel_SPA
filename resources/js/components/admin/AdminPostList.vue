<template>
    <div class="wrap-news">
        <div class="news-list">

            <a class="add-new" href="/admin/posts/add">Добавить новость</a>

            <div class="post-heading-block">
                <div class="post-name">Название новости</div>
                <div class="post-author">Автор</div>
                <div class="wrap-date">
                    <div class="post-date-c"><span>Дата создания и обновления:</span> </div>
                </div>
            </div>

            <div class="post-el" v-for="(value, name) in arrayPostEl">
                <a :href="'/admin/post/'+value['id']" class="post-name">{{ value['name'] }}</a>
                <div class="post-author">{{ value['author'] }}</div>
                <div class="wrap-date">
                    <div class="post-date-c"><span>Дата создания:</span> <span>{{ value['created_at'] }}</span></div>
                    <div class="post-date-u"><span>Дата обновления:</span> <span>{{ value['updated_at'] }}</span></div>
                </div>
                <div class="remove-post" @click="removePost" :data-id="value['id']">Удалить</div>
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
import router from "@/router/router";
import { useRoute } from "vue-router";
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
        let response = await authRequest('/api/post-list/'+ route.params.page, 'get' );

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

                arrayLink[i]['url'] = '/admin/post-list/' + page;
            }

            arrayPagination.value = arrayLink;
            arrayPostEl.value = arrayPost;
        }

    }
);


//remove post
async function removePost(e){
    let id = e.target.getAttribute('data-id');

    let response = await authRequest('/api/admin/delete/'+id, 'delete');

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
        margin-top: -1px;
        color: #fff;
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

</style>
