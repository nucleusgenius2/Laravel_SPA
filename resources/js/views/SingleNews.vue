<template>
    <MainLayout :layout = 'route.meta.layout'>
        <template #content>

            <div class="wrap-single-news max">
                <div class="wrap-news">
                    <h1>{{ array['name'] }}</h1>
                    <div class="content-news" v-html="array['content']"></div>
                </div>
            </div>

        </template>
    </MainLayout>
</template>


<script setup>
import {onMounted, ref} from 'vue';
import { useRoute } from "vue-router";

const route = useRoute();
let array = ref([]);

onMounted(
    async () => {
        let response = await fetch('/api/news/1/'+route.params.id );
        if (response.ok) {
            let json = await response.json();
            array.value = json[0];
        }
    }
);
</script>



<style scoped>

</style>
