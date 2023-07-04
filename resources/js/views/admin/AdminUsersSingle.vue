<template>
    <div className="wrap-single-news">
        <div className="wrap-news">

            <div class="wrap-field">
                <div class="heading-field">Имя</div>
                <input class='field-admin' v-model="array.name">
            </div>

            <div class="wrap-field">
                <div class="heading-field">Краткое описание новости</div>
                <textarea class='field-admin textarea-field' v-model="array.short_description"></textarea>
            </div>

            <div class="wrap-save">
                <div class="save news" @click="save()">Сохранить</div>
                <div class="text-status" v-if="saveStatus==='success' || saveStatus==='save_success_redirect'">
                    <span>Успешно сохранено</span>
                </div>
            </div>

        </div>
    </div>
</template>



<script setup>
import {onMounted, ref} from 'vue';
import router from "@/router/router";
import {useRoute} from "vue-router";
import CKEditor from '@ckeditor/ckeditor5-vue';
import {authRequest} from "@/api.js";

const ckeditor = CKEditor.component;

const route = useRoute();
let array = ref({
    name : '',
    img : '',
});
let textEditor = ref('');
let saveStatus = ref('');
let imgPreview = ref('');


function onChangeFile(event) {
    array.value.img = event.target.files[0];
    imgPreview.value = window.URL.createObjectURL( array.value.img );
}


//get post info
onMounted(
    async () => {
        if (route.params.id !== 'add') {
            let response = await authRequest('/api/posts/' + route.params.id, 'get');

            if ( response.data.status === 'success' ){
                array.value = response.data.json[0];
                textEditor.value = response.data.json[0].content;
                imgPreview.value = response.data.json[0].img;
            }
            else {
                return router.put({ name: '404',  query: { textError: encodeURIComponent(response.data.text) } })
            }
        }
    }
);

//update post
async function save(){

    //save or update
    let formData = new FormData();
    formData.append('id', route.params.id)
    formData.append('name', array.value.name)
    formData.append('img', array.value.img)
    formData.append('content', textEditor.value)
    formData.append('short_description', array.value.short_description)
    formData.append('author', JSON.parse(localStorage.getItem('token')).user);
    formData.append('seo_title', '');
    formData.append('seo_description', '');
    formData.append('id_category', '');
    //create post
    if ( route.params.id === 'add' ){
        let response = await authRequest('/api/posts', 'post', formData);

        if (response.data.status === 'success'){
            saveStatus.value = response.data.status;
            window.location.replace("/admin/posts/"+response.data.json);
        }
    }
    //update post
    else {
        formData.append('_method',"PATCH") //фикс бага ларавел(форм дата не работает в пут и патч), отправляем пост, но с методом PATCH, чтобы вызвался роут патч
        let response = await authRequest('/api/posts', 'post', formData );
        saveStatus.value = response.data.status;
    }

    if ( saveStatus.value === 'success') {
        setTimeout(() => {
            saveStatus.value = '';
        }, 3000);
    }
    else {
        console.log(saveStatus.value);
    }

}


</script>


<style scoped>
.text-status {
    color: #09be92;
    padding: 10px;
    display: inline-block;
    cursor: pointer;
    font-weight:600
}
.wrap-save {
    display:flex;
}
.img-field {
    display:flex;
    align-items: center;
}
.img-field img {
    max-width:100px;
    margin-right:30px;
}
.img-field .field-admin {
    margin-bottom: 0px;
}
.wrap-field {
    margin-bottom: 30px;
}
.field-admin {
    font-size: 13px;
    border-color: #c2c2bf;
    background-color: rgb(249, 249, 249);
    border-radius: 3px;
    border-width: 1px;
    height: 35px;
    padding: 1px 2px 1px 10px;
    outline: none !important;
    transition: 0.3s;
    width: 100%;
    vertical-align: middle;
    box-shadow: 0 0px 0px rgba(0, 0, 0, 0.075) inset !important;
    border-style: solid;
    margin: 0px;
    box-sizing: border-box;
    max-width: 100%;
}
input:focus, textarea:focus {
    border-color: #4e41d9;
}

.heading-field {
    margin-bottom: 5px;
    font-weight: 600;
}

.save {
    background-color: #09be92;
    color:#fff;
    padding:10px;
    display: inline-block;
    cursor: pointer;
}
.save:hover {
    background-color: #099d79;
}

.textarea-field {
    min-height: 80px;
    padding-top: 10px;
}



</style>
