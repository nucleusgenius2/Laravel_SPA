<template>
    <MainLayout :layout = 'route.meta.layout'>
        <template #content>

            <div class="max error-404">
                <h1> Ошибка 404 </h1>
                <div class="error-text" v-if="error">{{ error }}</div>
            </div>

        </template>
    </MainLayout>
</template>

<script setup>
import {useRoute} from "vue-router";
import {ref} from "vue";

const route = useRoute();
let error = ref(null);
if (route.query.textError){
    error = ref( decodeURIComponent(route.query.textError) );
}

</script>

<style scoped>
    .error-404 {
        text-align:center;
    }
    .error-text {
        color: #df3627;
    }
</style>
