<template>
    <MainLayout :layout = 'route.meta.layout' :auth='status'>
        <template #content>

            <div class="max">

                <form id="v-model-form" class="form-auth"  @submit.prevent="formSubmit">
                    <div class="heading-form">{{ $t('reset_password_3') }} </div>

                    <!-- Password -->
                    <div class="wrap-field">

                        <input id="email" v-model="email" :placeholder="$t('auth_your_email')" class="field-style"
                               name="email"
                               required />
                    </div>


                    <div class="wrap-button-submit">
                        <button class="button-style-1">
                            {{ $t('reset_password_4') }}
                        </button>
                    </div>
                </form>

                <div class="auth-text form-auth-true" v-if="status">{{ successMessage }}</div>
                <showing-errors :errors="error" />
            </div>

        </template>
    </MainLayout>
</template>


<script setup lang="ts">
import {ref} from 'vue';
import {useRoute} from "vue-router";
import {notAuthRequest} from "@/api.ts";
import {useI18n} from "vue-i18n";
import ShowingErrors from "@/components/ShowingErrors.vue"
const route = useRoute();
const {t} = useI18n({ useScope: 'global' })

let email = ref('');
let status = ref(false);
let successMessage = ref('');
let error = ref('');

if (localStorage.getItem("token")) {
    status.value = true;
}

async function formSubmit(){
    let response = await notAuthRequest('/api/reset_password', 'post', {
        'email' : email.value
    });
    if (response.data.status === 'success') {
        successMessage.value = t('reset_password_5');
        error.value ='';
        status.value = true;
    }
    else {

        try {
            error.value = JSON.parse(response.data.text);
        } catch (e) {
            error.value = response.data.text;
        }

        status.value = false;
    }

}

</script>



<style scoped>
.field-style {
    font-size: 13px;
    border-color: #c2c2bf;
    background-color: rgb(249, 249, 249);
    border-radius: 3px;
    border-width: 1px;
    height: 35px;
    padding: 1px 2px 1px 10px;
    outline: none ;
    transition: 0.3s;
    width: 100%;
    vertical-align: middle;
    box-shadow: 0 0px 0px rgba(0, 0, 0, 0.075) inset;
    border-style: solid;
    margin: 0px;
    box-sizing: border-box;
    max-width: 100%;
}

input:focus, textarea:focus {
    border-color: #4e41d9;
}
.wrap-button-submit {
    text-align: center;
}
.button-style-1 {
    font-size: 14px;
    line-height: 18px;
    font-weight: 400;
    font-style: normal;
    text-transform: none;
    letter-spacing: 0em;
    border-radius: 3px;
    padding: 1em 1.5em;
    background: #192B3B;
    border-color: transparent;
    color: #ffffff;
    display:inline-block;
    cursor:pointer;
    transition:0.3s;
    width:100%;
}
.button-style-1:hover {
    background: #4e41d9;
}

.heading-form {
    font-weight: 500;
    font-size: 22px;
    line-height: 28px;
    color: #11202E;
    margin-bottom:20px;
    text-align: center;
}

.form-auth-true, .form-auth-false {
    max-width: 500px;
    margin: auto;
    padding: 20px;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.25);
    color: #11b737;
    font-size: 18px;
    text-align: center;
}
.form-auth-false {
    color: #df3627;
}
</style>
