<template>
    <div class="wrap-news">
        <div class="news-list">
            <div class="add-new" @click="openUploadPanel">Загрузить карту</div>

            <div v-if="showUploadPanel" class="wrap-downland-maps">

                <div class="wrap-maps-field">
                    <div class="wrap-field">
                        <div class="heading-field">Имя карты</div>
                        <input class='field-admin' v-model="map.name">
                    </div>

                    <div class="wrap-maps-size">
                        <div class="wrap-field">
                            <div class="heading-field">Размер карты</div>
                            <select class='field-admin' v-model="map.size">
                                <option value="256x256">5 на 5 (256 на 256)</option>
                                <option value="512x512">10 на 10 (512 на 512)</option>
                                <option value="1024x1024">20 на 20 (1024 на 1024)</option>
                            </select>
                        </div>

                        <div class="wrap-field">
                            <div class="heading-field">Произвольный размер</div>
                            <input class='field-admin' v-model="map.size">
                        </div>
                    </div>


                    <div class="wrap-field">
                        <div class="heading-field">Версия карты</div>
                        <input class='field-admin' v-model="map.version">
                    </div>

                    <div class="wrap-field">
                        <div class="heading-field">Количество игроков</div>
                        <select class='field-admin' v-model="map.total_player">
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
                </div>


                <div class="wrap-maps-field">
                    <div class="wrap-field">
                        <div class="heading-field">Рейтинговая карта</div>
                        <select class='field-admin' v-model="map.rate">
                            <option value="1">Рейтинговая</option>
                            <option value="0">Не рейтинговая</option>
                        </select>
                    </div>

                    <div class="wrap-field">
                        <div class="map-field">
                            <div class="heading-field">Архив с картой</div>
                            <input class='file' type="file" @change="onChangeFileMap">
                        </div>
                    </div>

                    <div class="wrap-field">
                        <div class="map-field">
                            <div class="heading-field">Изображение карты</div>
                            <input class='file' type="file" @change="onChangeFileImg">
                        </div>
                    </div>

                </div>
                <ButtonSave ref="saveButtonRef" @click="saveMap">Сохранить</ButtonSave>
                <div class="error" v-if="error!==''">{{ error }}</div>
            </div>


            <!-- фильтр -->
            <div class="wrap-filter">
                <div class="wrap-field">
                    <div class="heading-field text">Название карты</div>
                    <input class='field-admin' v-model="filter.name">
                </div>

                <div class="wrap-field">
                    <div class="heading-field text">Количество игроков мин</div>
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
                    <div class="heading-field text">Количество игроков макс</div>
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
                    <div class="heading-field text">Размер карты</div>
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
                        Применить фильтр
                    </div>
                </div>

                <div class="wrap-button-submit">
                    <div class="button-blue-all-style-2 button-admin" style="margin-bottom: 10px;"  @click="clearFilter" >
                        Очистить
                    </div>
                </div>

            </div>


            <div class="post-heading-block">
                <div class="maps-img maps-el">Превью</div>
                <div class="maps-name maps-el">Название карты</div>
                <div class="maps-el">Игроков</div>
                <div class="maps-el">Рейтинговая</div>
                <div class="maps-el size">Размер</div>
                <div class="wrap-date">
                    <div class="post-date-c"><span>Даты:</span> </div>
                </div>
                <div class="maps-el">Удаление</div>
            </div>

            <div class="post-el" v-for="(map) in arrayMaps">
                <div class="maps-img maps-el ">
                    <img :src="typeof map.url_img === 'string' ? map.url_img : ''" alt="">
                </div>
                <div class="maps-name maps-el">{{ map.name }}</div>
                <div class="maps-el">{{ map.total_player }}</div>
                <div v-if="map.rate===0" class="maps-el">Нет</div>
                <div v-else class="maps-el">Да</div>
                <div class="maps-el size">{{ map.size}}</div>
                <div class="wrap-date">
                    <div class="post-date-c"><span>Создание:</span> <span v-if="map.created_at">{{ convertTime(map.created_at) }}</span></div>
                    <div class="post-date-u"><span>Обновление:</span> <span v-if="map.updated_at">{{  convertTime(map.updated_at) }}</span></div>
                </div>
                <div class="remove-post" @click="removeMaps" :data-id="map.id">Удалить</div>
            </div>


            <pagination v-model="pageModel" :records="pageTotal" :per-page="15" @paginate="paginationListing"/>

        </div>
    </div>


</template>


<script setup lang="ts">
import {ref} from 'vue';

import {authRequest} from "@/api.ts";
import ButtonSave from "@/components/admin/ButtonSave.vue";
//@ts-ignore
import Pagination from 'v-pagination-3';
import {convertTime} from '@/script/convertTime.ts'
import type { MapItem } from '@/types/map';

let error = ref('');
let showUploadPanel = ref(false);
let saveButtonRef = ref(null);

let filter = ref({
    'name' : '',
    'total_player_from' : '',
    'total_player_to' : '',
    'size' : '',
});
let pageModel = ref(1)
let pageTotal = ref(1)


let map = ref<MapItem>({
    id: 0,
    name: '',
    name_dir: '',
    url_img: '',
    map_archive: '',
    total_player: 0,
    size: '',
    map_rate: 0,
    rate: 0,
    version: 0,
});


let arrayMaps = ref<MapItem[]>([]);

function openUploadPanel(){
    showUploadPanel.value = true;
}

function onChangeFileImg(event: Event) {
    const target = event.target as HTMLInputElement;

    if (target.files && target.files[0]) {
        map.value.url_img = target.files[0];
    }
}


function onChangeFileMap(event: Event) {
    const target = event.target as HTMLInputElement;

    if (target.files && target.files[0]) {
        map.value.map_archive = target.files[0];
        map.value.name = map.value.map_archive.name.replace('.zip', '')
    }
}


async function saveMap(){
    let formData = new FormData();
    formData.append('name', map.value.name);
    formData.append('map_size', map.value.size);
    formData.append('version', map.value.version.toString());
    formData.append('total_player', map.value.total_player.toString());
    formData.append('rate', map.value.rate.toString());

    // изображение карты
    if (map.value.url_img instanceof File) {
        formData.append('url_img', map.value.url_img, map.value.url_img.name);
    } else {
        formData.append('url_img', map.value.url_img);
    }

    //архив с картой
    if(map.value.map_archive) {
        if (map.value.map_archive instanceof File) {
            formData.append('map_archive', map.value.map_archive, map.value.map_archive.name);
        } else {
            formData.append('map_archive', map.value.map_archive);
        }
    }

    let response = await authRequest('/api/maps', 'post', formData);

    if ( response.data.status  === 'success') {
        //@ts-ignore
        saveButtonRef.value.save()
        error.value = ''
    }
    else {
        //@ts-ignore
         saveButtonRef.value.error()
        error.value = response.data.text;
        error.value = error.value.replace(/map archive/g, 'Архив с картой' );
        error.value = error.value.replace(/Имя/g, 'Имя карты' );
        error.value = error.value.replace(/url img/g, 'Изображение карты' );
    }
}


async function paginationListing(filterClick = '') {
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
        pageTotal.value = response.data.json.last_page * 15;
    }
}
paginationListing();

async function removeMaps(e: MouseEvent){
    let target = e.target as HTMLElement;

    let id = target.getAttribute('data-id');

    let response = await authRequest('/api/maps/'+id, 'delete');

    if ( response.data.status==='success' ){
        target.closest('.post-el')?.remove();
    }
    else {
        console.error(response.data.status);
    }
}

function clearFilter (){
    filter.value.name = '';
    filter.value.total_player_from = '';
    filter.value.total_player_to = '';
    filter.value.size = '';
    paginationListing();
}

</script>


<style scoped>
.maps-el {
    flex-basis: 10%
}

.maps-img img {
    width: 60px;
    height: auto;
    max-height: 70px;
}

.maps-img {
    margin-left:10px;
    flex-basis: 8%;
}

.author {
    flex-basis: 14%
}

.wrap-maps-size .wrap-field:first-child{
    margin-right: 8px;
}

.size {
    flex-basis: 7%
}

.wrap-maps-size {
    display:flex;
}
.wrap-maps-field {
    display:flex;
}
.wrap-maps-field {
    margin-bottom: 20px;
}

.wrap-maps-field .wrap-field {
    margin-right:20px;
}
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

.maps-name {
    flex-basis: 30%;
    padding-left: 20px;
    transition: 0.3s;
    font-size: 16px;
    font-weight: 600;
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
    color: #fff;
    margin-top: 30px;
}

.post-heading-block div {
    padding-top: 5px;
    padding-bottom: 5px;
}

.post-heading-block .post-name {
    color: #fff;
    font-weight:400;
}

.wrap-date {flex-basis: 22%}

.remove-post {
    padding-left: 20px;
    text-align: right;
    font-size: 12px;
    color: #b50707;
    cursor: pointer;
}

.pagination-el div {
    cursor: pointer;
}

.add-new {
    background-color: #13af3b;
    display: inline-block;
    padding: 10px;
    color: #fff;
    text-decoration: none;
    transition: 0.3s;
    margin-bottom: 10px;
    cursor: pointer;
}

.news-list {
    margin-top:20px
}

.error{
    padding-bottom: 20px;
    font-size: 12px;
    color: #b50707;
    cursor: pointer;
}




</style>
