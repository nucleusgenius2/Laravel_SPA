<template>
    <div class="wrap-news">
        <div class="news-list">

            <div class="post-heading-block">
                <div class="post-name">Название новости</div>
                <div class="post-author">Автор</div>
                <div class="wrap-date">
                    <div class="post-date-c"><span>Дата создания и обновления:</span> </div>
                </div>
            </div>

            <div class="post-el" v-for="(value, name) in array_post">
                <a :href="'/admin/post/'+value['id']+'/edit'" class="post-name">{{ value['name'] }}</a>
                <div class="post-author">{{ value['autor'] }}</div>
                <div class="wrap-date">
                    <div class="post-date-c"><span>Дата создания:</span> <span>{{ value['created_at'] }}</span></div>
                    <div class="post-date-u"><span>Дата обновления:</span> <span>{{ value['updated_at'] }}</span></div>
                </div>
                <div class="remove-post" @click="removePost" :data-id="value['id']">Удалить</div>

            </div>

            <div class="pagination-post" v-if="props.pagination === 'true' ">
                <div class="pagination-el" v-for="(value, name) in array_pagination">
                    <a :href="value['url']">{{ value['label'] }}</a>
                </div>
            </div>

        </div>
    </div>
</template>


<script>
export default {
    name: "NewsList"
}
</script>


<script setup>

import {onMounted, ref} from 'vue';
import { useRoute } from "vue-router";
import axios from "axios";

let props = defineProps({
    total: String,
    pagination: String,
})


const route = useRoute();

let array_post = ref([]);
let array_pagination = ref([]);

onMounted(
    async () => {
        let response = await fetch('/api/news/'+props.total+'/all?page='+route.params.page);
        if (response.ok) {
            let json = await response.json();
            //short description post
            for (let i = 0; i < json['data'].length; i++) {
                //converting date
                json['data'][i]['created_at'] = new Date(json['data'][i]['created_at']).toLocaleString();
                json['data'][i]['updated_at'] = new Date(json['data'][i]['created_at']).toLocaleString();
            }

            //pagination array localization
            json['links'][0]['label'] = 'В начало';
            json['links'][json['links'].length - 1]['label'] = 'В конец';

            for (let i = 0; i < json['links'].length; i++) {
                let page = i;
                //first link pagination
                if( page == 0 ){page = 1;}
                //last link pagination
                else if ( page == json['links'].length -1 ){ page = json['links'].length -2 }
                json['links'][i]['url'] = '/admin/news/10/'+page ;
            }

            array_pagination.value = json['links'];

            array_post.value = json['data'];

        }
    }
);


//remove post
async function removePost(e){

    const token = JSON.parse(localStorage.getItem('token'));
    let headers = {
        accept: 'application/json',
        Authorization: 'Bearer ' + token.token
    }

    let id = e.target.getAttribute('data-id');
    //set token in axios header
    let response =  await axios.delete('/api/admin/delete/'+id, {
        headers: headers
    })
    if ( response.data=='success' ){
        e.target.closest('.post-el').remove();
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
