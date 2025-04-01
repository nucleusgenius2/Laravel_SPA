<template>
    <MainLayout :layout = 'route.meta.layout'>
        <template #content>

            <div class="max m-top">
                <h1>{{ $t('news_header') }} </h1>
                <News total="10" pagination="true"/>
            </div>

        </template>
    </MainLayout>
</template>

<script setup lang="ts">
import News from '@/components/News.vue';
import {useRoute} from "vue-router";
const route = useRoute();
</script>

<style scoped>

</style>
