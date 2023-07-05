<template>
    <MainLayout :layout = 'route.meta.layout'>
        <template #content>

            <div class="wrap-single-news max">
                <div class="wrap-news">
                    <h1>{{ post.name }}</h1>
                    <div class="content-news" v-html="post.content"></div>
                </div>
            </div>

        </template>
    </MainLayout>
</template>


<script setup>
import {onMounted, ref} from 'vue';
import {useRoute} from "vue-router";
import {authRequest} from "@/api.js";
import router from "@/router/router";

const route = useRoute();
let post = ref([]);

onMounted(
    async () => {
       let response = await authRequest('/api/posts/'+route.params.id, 'get' );
       if ( response.data.status === 'success' ){
           post.value = response.data.json[0];
       }
       else {
           return router.push({ name: '404',  query: { textError: encodeURIComponent(response.data.text) } })
       }
    }
);
</script>



<style scoped>

</style>
