<template>
    <MainLayout :layout = 'route.meta.layout'>
        <template #content>

            <div class="max m-top">
                <h1> {{ $t('maps_header') }} </h1>

                <!-- фильтр -->
                <div class="wrap-filter">
                    <div class="wrap-field">
                        <div class="heading-field text">{{ $t('maps_page_1') }}</div>
                        <input class='field-admin' v-model="filter.name">
                    </div>

                    <div class="wrap-field">
                        <div class="heading-field text">{{ $t('maps_page_2') }}</div>
                        <select class='field-admin' v-model="filter.total_player_from">
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="10">10</option>
                            <option value="12">12</option>
                            <option value="14">14</option>
                            <option value="16">16</option>
                        </select>
                    </div>

                    <div class="wrap-field">
                        <div class="heading-field text">{{ $t('maps_page_3') }}</div>
                        <select class='field-admin' v-model="filter.total_player_to">
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="10">10</option>
                            <option value="12">12</option>
                            <option value="14">14</option>
                            <option value="16">16</option>
                        </select>
                    </div>

                    <div class="wrap-field">
                        <div class="heading-field text">{{ $t('maps_page_4') }}</div>
                        <select class='field-admin' v-model="filter.size">
                            <option value="256x256">5 x 5</option>
                            <option value="512x512">10 x 10</option>
                            <option value="1024x1024">20 x 20</option>
                            <option value="2048x2048">40 x 40</option>
                            <option value="4096x4096">80 x 80</option>
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

                <div class="wrap-el-grid">
                    <div v-if="!errors" class="post-el" v-for="(map) in arrayMaps">
                        <div class="maps-img maps-el">
                            <img :src="typeof map.url_img === 'string' ? map.url_img : ''" alt="">
                            <div class="wrap-icons-action">
                                <a :href="'/api/mods/downland?name='+map.name_dir+'.zip'">
                                    <img  src="/images/downland.svg" alt="downland" class="downland-map">
                                </a>
                            </div>
                        </div>
                        <div class="wrap-info">
                            <div class="maps-name maps-el" v-html="nameMaxLength(map.name)"></div>

                            <div class="wrap-info-section">
                                <div class="wrap-dop-info">
                                    <div class="maps-el">
                                        <img style="margin-right:6px;" src="/images/total-player.svg" alt="total">{{ map.total_player}}
                                    </div>
                                    <div class="maps-el">
                                        <img style="margin-right:6px;" src="/images/sizemap.svg" alt="total">{{ sizeMap(map.size)}}
                                    </div>
                                </div>
                                <div class="rate-block">
                                    <div class="maps-el rate" v-for="(star) in rateMapsAndMods(map.map_rate )">
                                        <img src="/images/star.svg" alt="star"/>
                                    </div>
                                </div>
                            </div>

                            <div class="wrap-rate author">
                                <div style="margin-right:6px;">{{ $t('maps_page_5') }}:</div>
                                <div v-if="map.rate===0" class="maps-el not-rate">{{ $t('maps_page_6') }}</div>
                                <div v-else class="maps-el">{{ $t('maps_page_7') }}</div>
                            </div>

                            <div class="maps-el author" style="display:none"><span style="margin-right:6px;">{{ $t('maps_page_8') }}:</span>{{ map.author }}</div>

                        </div>
                    </div>

                </div>

                <div v-if="errors" class="text">{{ $t('not_result') }}</div>

                <pagination v-model="pageModel" :records="pageTotal" :per-page="1" @paginate="paginationListing"/>
            </div>

        </template>
    </MainLayout>
</template>

<script setup lang="ts">
import {useRoute} from "vue-router";
import {ref} from "vue";
//@ts-ignore
import Pagination from "v-pagination-3";
import {rateMapsAndMods} from "@/script/rateMapsAndMods.ts";
import {nameMaxLength} from "@/script/nameMaxLength.ts";
import {sizeMap} from "@/script/sizeMap.ts";
import {authRequest} from "@/api.ts";
import type { MapItem } from '@/types/map';


interface Filter {
    name: string;
    total_player_from: number | string;
    total_player_to: number | string;
    size: string;
}

const route = useRoute();
let pageModel = ref(1)
let pageTotal = ref(1)
let arrayMaps = ref<MapItem[]>([]);
let errors = ref(false)


let filter = ref<Filter>({
    'name' : '',
    'total_player_from' : '',
    'total_player_to' : '',
    'size' : '',
});


async function paginationListing(filterClick: string = ''): Promise<void>  {
    if (filterClick === 'filter') {
        pageModel.value = 1;
    }
    let stringFilter = '';
    if (filter.value.name !== '') {
        stringFilter += '&name=' + filter.value.name;
    }
    if (filter.value.total_player_from !== '') {
        stringFilter += '&total_player_from=' + filter.value.total_player_from;
    }
    if (filter.value.total_player_to !== '') {
        stringFilter += '&total_player_to=' + filter.value.total_player_to;
    }
    if (filter.value.size !== '') {
        stringFilter += '&size=' + filter.value.size;
    }
    let response = await authRequest('/api/maps?page=' + pageModel.value + stringFilter, 'get');

    if (response.data.status === 'success') {
        arrayMaps.value = response.data.json.data;
        pageTotal.value = response.data.json.last_page;
    }
}
paginationListing();

function clearFilter(): void {
    filter.value.name = '';
    filter.value.total_player_from = '';
    filter.value.total_player_to = '';
    filter.value.size = '';
    paginationListing();
}
</script>

<style scoped>
.downland-map {
    background-color: #48B771;
    padding:5px;
    display:flex
}

.wrap-dop-info {
    display:flex;
}


.rate-block {
    display: flex;
}

.not-rate {
    color: #E64B3D;
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
    top: 0px;
    right: 0px;
    cursor: pointer;
    display: flex;
    left: 0px;
    justify-content: space-between;
}

.wrap-icons-action > img {
    width:40px;
    height:40px;
    padding:10px;
}

.wrap-rate{
    display:flex;
}

.wrap-rate div {
    font-size: 12px;
}

.wrap-el-grid{
    display:flex;
    flex-wrap:wrap;
    margin-top:30px
}

.maps-img > img {
    width: 210px;
    height: 210px;
}


.wrap-maps-size .wrap-field:first-child{
    margin-right: 8px;
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
    overflow: hidden;
}


.post-heading-block div {
    padding-top: 5px;
    padding-bottom: 5px;
}

.post-heading-block .post-name {
    color: #fff;
    font-weight:400;
}

.pagination-el div {
    cursor: pointer;
}


.author {
    font-size:12px;
    line-height:12px;
}

</style>
