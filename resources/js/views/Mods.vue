
<template>
    <MainLayout :layout = 'route.meta.layout'>
        <template #content>

            <div class="max m-top">
                <h1> {{ $t('mods_header') }} </h1>
                <div class="wrap-el-grid">
                    <div v-if="!errors" class="post-el" v-for="(mod, key) in arrayMods">
                        <div class="maps-img maps-el">
                            <img :src="mod.url_img" alt="">
                            <div class="wrap-icons-action">
                                <a :href="'/api/mods/downland?name='+mod.name_dir+'.zip'">
                                    <img  src="/images/downland.svg" alt="downland" class="downland-map">
                                </a>
                            </div>
                        </div>
                        <div class="wrap-info">
                            <div class="maps-name maps-el" v-html="nameMaxLength(mod.name)"></div>

                            <div class="wrap-info-section">
                                <div class="wrap-dop-info">
                                    <div class="maps-el description">
                                        <p>{{ mod.description }} </p>
                                    </div>
                                    <div class="maps-el">
                                        <span v-if="mod.type === 1">{{ $t('UI Мод') }}</span>
                                        <span v-else>{{ $t('UI Мод') }}</span>
                                    </div>

                                    <div class="rate-block">
                                        <div class="maps-el rate" v-for="(star) in rateMapsAndMods(mod.mod_rate )">
                                            <img src="/images/star.svg" alt="star"/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div v-if="errors" class="text">По вашему запросу нет результатов</div>

                    <pagination v-model="pageModel" :records="pageTotal" :per-page="10" @paginate="paginationListing"/>
                </div>
            </div>

        </template>
    </MainLayout>
</template>

<script setup>
import {useRoute} from "vue-router";
import {authRequest} from "@/api";
import {ref} from "vue";
import Pagination from "v-pagination-3";
import {rateMapsAndMods} from "@/script/rateMapsAndMods";
import {nameMaxLength} from "@/script/nameMaxLength";
const route = useRoute();
let pageModel = ref(1)
let pageTotal = ref(1)
let arrayMods = ref([]);
let errors = ref(false)

async function paginationListing(){
    let response = await authRequest('/api/mods?page='+ pageModel.value, 'get' );
    if ( response.data.status === 'success' ) {
        arrayMods.value  = response.data.json.data;
        pageTotal.value = response.data.json.last_page * 10;
    }
    else {
        errors.value = response.data;
    }
}
paginationListing();




</script>

<style scoped>
.wrap-filter .wrap-field{
    margin-right:20px;
    margin-bottom: 10px;
}

.rate-block {
    display: flex;
}

.rate-block{
    margin-right: 0px
}


.wrap-info-section {
    display:flex;
}
.wrap-dop-info > .maps-el {
    margin-right:15px;
}

.wrap-info {
    padding:10px;
    padding-top: 0px;
}
.maps-el {
    position: relative;
}
.wrap-icons-action {
    position: absolute;
    top: 10px;
    right: 10px;
    cursor: pointer;
}

.wrap-icons-action > img {
    width:40px;
    height:40px;
    padding:10px;
}

.downland-map {
    background-color: #48B771;
    padding:5px;
    display:flex
}

.wrap-rate div {
    font-size: 12px;
}

.wrap-el-grid{
    display:flex;
    flex-wrap:wrap;
    align-items: flex-start;
    margin-top:30px
}

.maps-img > img {
    width: 210px;
    height: 210px;
}


.wrap-maps-field .wrap-field {
    margin-right:20px;
    margin-bottom: 10px;

}
.post-el {
    display: flex;
    align-items: center;
    background-color:rgba(44, 154, 255, 0);
    width:212px;
    min-width: 212px;
    flex-wrap: wrap;
    margin-right:20px;
    border: 1px solid #a3a3a3;
    margin-bottom: 20px;
}

.post-el:hover {
    background-color:rgba(44, 154, 255, 0.30);
}

.thumb-post img {
    max-width: 100px;
}

.maps-name {
    transition: 0.3s;
    font-size: 16px;
    font-weight: 600;
}


.post-heading-block div {
    padding-top: 5px;
    padding-bottom: 5px;
}


.pagination-el div {
    cursor: pointer;
}


.description {
    font-size:15px;
    min-height:120px
}


</style>
