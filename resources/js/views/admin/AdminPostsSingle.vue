<template>
    <div class="wrap-single-news">
        <div class="wrap-news">

            <div class="wrap-field">
                <div class="heading-field">Название новости</div>
                <input class='field-admin' v-model="post.name">
            </div>

            <div class="document-editor__toolbar"></div>
            <div class="wrap-field">
                <div class="heading-field">Контент новости</div>
                <ckeditor :editor="editor" v-model="textEditor" :config="editorConfig" @ready="onReady"></ckeditor>
            </div>

            <div class="document-editor__toolbar"></div>
            <div class="wrap-field">
                <div class="heading-field">Краткое описание новости</div>
                <textarea class='field-admin textarea-field' v-model="post.short_description"></textarea>
            </div>

            <div class="wrap-field">
                <div class="heading-field">Изображение новости</div>
                <div class="img-field">
                    <img v-if="typeof post.img==='string'" :src="imgPreview">
                    <input class='file' type="file" @change="onChangeFile">
                </div>
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



<script setup lang="ts">
import {onMounted, ref} from 'vue';
import router from "@/router/router";
import {useRoute} from "vue-router";
//@ts-ignore
import CKEditor from '@ckeditor/ckeditor5-vue';
import {authRequest} from "@/api.ts";
import type { PostItem } from '@/types/post';
//@ts-ignore
import DecoupledEditor from '@ckeditor/ckeditor5-build-decoupled-document';

const editor = ref(DecoupledEditor);
const ckeditor = CKEditor.component;

const route = useRoute();
let post = ref<PostItem>({
    name: '',
    content:  '',
    short_description: '',
    seo_title: '',
    seo_description: '',
    img: '',
    category_id: 0,
    author: 0,
    created_at: '',
    updated_at: '',
});
let textEditor = ref('');
let saveStatus = ref('');
let imgPreview = ref<string>('');


function onChangeFile(event: Event) {
    const target = event.target as HTMLInputElement;

    if (target.files && target.files[0]) {
        post.value.img = target.files[0];
    }

    if (post.value.img instanceof File) {
        imgPreview.value = URL.createObjectURL(post.value.img);
    } else if (typeof post.value.img === 'string') {
        imgPreview.value = post.value.img;
    }
}



let editorConfig = {
    fontSize: {
        options: [
            9,
            10,
            12,
            13,
            14,
            'default',
            16,
            17,
            18,
            19,
            21,
            25
        ]
    },

    toolbar: {
        items: [
            'heading', '|',
            'fontsize', '|',
            'alignment', '|',
            'fontColor', 'fontBackgroundColor', '|',
            'bold', 'italic',
            'underline',
            'link', '|',
            'outdent', 'indent', '|',
            'bulletedList', 'numberedList',
            '|',
            'undo', 'redo'
        ],
        shouldNotGroupWhenFull: true
    }

}

// 👇 Здесь то, что было в методе `onReady`
function onReady(editorInstance: any) {
    const editableElement = editorInstance.ui.getEditableElement();
    if (editableElement && editableElement.parentElement) {
        editableElement.parentElement.insertBefore(
            editorInstance.ui.view.toolbar.element,
            editableElement
        );
    }
}


onMounted(
    async () => {
        if (route.params.id !== 'add') {
            let response = await authRequest('/api/posts/' + route.params.id, 'get');

            if ( response.data.status === 'success' ){
                post.value = response.data.json[0];
                textEditor.value = response.data.json[0].content;
                imgPreview.value = response.data.json[0].img;
            }
            else {
                router.push({
                    name: '404',
                    query: { textError: encodeURIComponent(response.data.text) }
                });
            }
        }
    }
);


async function save(){
    let formData = new FormData();
    formData.append('id', route.params.id.toString())
    formData.append('name', post.value.name)
    if (post.value.img && typeof(post.value.img) !=='string') {
        formData.append('img', post.value.img)
    }
    formData.append('content', textEditor.value)
    formData.append('short_description', post.value.short_description)
    formData.append('seo_title', '');
    formData.append('seo_description', '');
    formData.append('id_category', '');
     //create post
    if ( route.params.id === 'add' ){
        let response = await authRequest('/api/posts', 'post', formData);

        if (response.data.status === 'success'){
            saveStatus.value = response.data.status;
            window.location.replace("/admin/posts/"+response.data.json.id);
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
.ck-editor__editable {
    border: 1px solid #cecece!important;
    min-height: 300px;
}
.ck-editor__editable:focus {
    border: 1px solid #2977ff!important;
}
.textarea-field {
    min-height: 80px;
    padding-top: 10px;
}

.wrap-news {
    margin-top:30px;
}

</style>
