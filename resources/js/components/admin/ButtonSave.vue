<template>
    <div class="wrap-save">
        <div class="save news"><slot></slot></div>
        <div class="text-status" v-if="success">
            <span>Успешно сохранено</span>
        </div>
        <div class="text-status error" v-if="err">
            <span>Ошибка при сохранении</span>
        </div>
    </div>
</template>

<script setup>
import {ref} from "vue";
let success = ref(false);
let err = ref(false);

function save () {
    success.value = true;
    setTimeout(() => {
        success.value = false;
    }, 3000);
}

function error(){
    err.value = true;
    setTimeout(() => {
        err.value = false;
    }, 3000);
}

defineExpose({
    save,
    error
})
</script>

<style scoped>
    .wrap-save {
        display:inline-flex;
    }
    .text-status {
        color: #09be92;
        padding: 10px;
        display: inline-block;
        cursor: pointer;
        font-weight:600
    }
    .save {
        background-color: #09be92;
        display: inline-block;
        padding: 10px;
        color: #fff;
        text-decoration: none;
        transition: 0.3s;
        cursor: pointer;
    }
    .save:hover {
        background-color: #099d79;
    }
    .error{
        color:#be0926;
    }
</style>
