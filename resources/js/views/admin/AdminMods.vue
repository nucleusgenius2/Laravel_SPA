<template>
    <div class="wrap-news">
        <div class="news-list">
            <div class="add-new" @click="openUploadPanel">Загрузить мод</div>

            <div v-if="showUploadPanel" class="wrap-downland-mods">

                <div class="wrap-mods-field">
                    <div class="wrap-field">
                        <div class="heading-field">Имя мода</div>
                        <input class='field-admin' v-model="mod.name">
                    </div>

                    <div class="wrap-field">
                        <div class="heading-field">Имя папки с модом</div>
                        <input class='field-admin' v-model="mod.dir">
                    </div>

                    <div class="wrap-field">
                        <div class="heading-field">Версия мода</div>
                        <input class='field-admin' v-model="mod.version">
                    </div>

                    <div class="wrap-field">
                        <div class="heading-field">Тип мода</div>
                        <select class='field-admin' v-model="mod.type">
                            <option value="1">UI мод</option>
                            <option value="0">Sim мод</option>
                        </select>
                    </div>

                    <div class="wrap-field">
                        <div class="mod-field">
                            <div class="heading-field">Архив с модом</div>
                            <input class='file' type="file" @change="onChangeFileMod">
                        </div>
                    </div>

                    <div class="wrap-field">
                        <div class="mod-field">
                            <div class="heading-field">Изображение мода</div>
                            <input class='file' type="file" @change="onChangeFileImg">
                        </div>
                    </div>


                </div>


                <div class="wrap-mods-field">
                    <div class="wrap-field">
                        <div class="heading-field">Описание мода</div>
                        <textarea class='field-admin textarea-field' v-model="mod.description"></textarea>
                    </div>

                </div>
                <ButtonSave ref="saveButtonRef" @click="saveMods">Сохранить</ButtonSave>
                <div v-if="errors">{{ errors }}</div>
            </div>



            <div class="post-heading-block">
                <div class="mods-img mods-el">Превью</div>
                <div class="mods-name mods-el">Название мода</div>
                <div class="mods-el description">Описание</div>
                <div class="mods-el">Тип мода</div>
                <div class="wrap-date">
                    <div class="post-date-c"><span>Даты:</span> </div>
                </div>
                <div class="mods-el">Удаление</div>
            </div>

            <div class="post-el" v-for="(mods) in arrayMods">
                <div class="mods-img mods-el "><img :src="mods.url_img" alt=""></div>
                <div class="mods-name mods-el">{{ mods.name }}</div>
                <div class="mods-el description">{{ mods.description }}</div>
                <div v-if="mods.type===0" class="mods-el">SIM Мод</div>
                <div v-else class="mods-el">IU Мод</div>
                <div class="wrap-date">
                    <div class="post-date-c"><span>Создание:</span> <span>{{ convertTime(mods.created_at) }}</span></div>
                    <div class="post-date-u"><span>Обновление:</span> <span>{{ convertTime(mods.updated_at) }}</span></div>
                </div>
                <div class="remove-post" @click="removeMods" :data-id="mods.id">Удалить</div>
            </div>


            <pagination v-model="pageModel" :records="pageTotal" :per-page="10" @paginate="paginationListing"/>
        </div>
    </div>
</template>


<script setup>
import {onMounted, ref} from 'vue';
import { useRoute } from "vue-router";
import {authRequest} from "@/api.ts";
import ButtonSave from "@/components/admin/ButtonSave.vue";
import Pagination from 'v-pagination-3';
import {convertTime} from '@/script/convertTime.ts'
let errors = ref(false)
const route = useRoute();
let showUploadPanel = ref(false);
let saveButtonRef = ref(null)
let pageModel = ref(1)
let pageTotal = ref(1)
let arrayMods = ref([]);

let mod = ref({
    'name' : '',
    'version' : '',
    'description' : '',
    'type' : '',
    'url_img' : '',
    'mod_archive' : '',
    'dir' : ''
});

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

function openUploadPanel(){
    showUploadPanel.value = true;
}


function onChangeFileImg(event) {
    mod.value.url_img = event.target.files[0];
}

function onChangeFileMod(event) {
    mod.value.mod_archive = event.target.files[0];
}


async function saveMods(){

    let formData = new FormData();
    formData.append('name', mod.value.name);
    formData.append('name_dir', mod.value.dir);
    formData.append('version', mod.value.version);
    formData.append('description', mod.value.description);
    formData.append('type', mod.value.type);
    formData.append('url_img',  mod.value.url_img);
    formData.append('mod_archive',  mod.value.mod_archive);

    let response = await authRequest('/api/mods', 'post', formData);

    if ( response.data.status  === 'success') {
        saveButtonRef.value.save()
    }
    else {
        saveButtonRef.value.error()
        errors.value = response.data.text;
    }
}



async function removeMods(e){
    let id = e.target.getAttribute('data-id');

    let response = await authRequest('/api/mods/'+id, 'delete');

    if ( response.data.status==='success' ){
        e.target.closest('.post-el').remove();
    }
    else {
        console.error(response.data.status);
    }
}
</script>


<style scoped>

.mods-el {
    flex-basis: 10%
}

.mods-img img {
    width: 60px;
    height: auto;
    max-height: 70px;
}

.mods-img {
    margin-left:10px;
    flex-basis: 8%;
}

.author {
    flex-basis: 14%
}

.wrap-mods-size .wrap-field:first-child{
    margin-right: 8px;
}

.size {
    flex-basis: 7%
}

.wrap-mods-size {
    display:flex;
}
.wrap-mods-field {
    display:flex;
}
.wrap-mods-field {
    margin-bottom: 20px;
}

.wrap-mods-field .wrap-field {
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

.mods-name {
    flex-basis: 18%;
    padding-left: 20px;
    transition: 0.3s;
    font-size: 16px;
    font-weight: 600;
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

.textarea-field {
    min-height: 80px;
    padding-top: 10px;
    min-width: 385px;
}

.description {
    flex-basis: 25%;
}

</style>
