<template>
    <MainLayout :layout = 'route.meta.layout' :auth='status'>
        <template #content>

            <div class="max">

                <form id="v-model-form" class="form-auth"  @submit.prevent="formSubmit" v-if="status === 'noAuth'">
                    <div class="heading-form">{{ $t('auth_entry') }}</div>
                    <!-- Email Address -->
                    <div class="wrap-field">
                        <input id="email" v-model="email" class="field-style" autocomplete="off" :placeholder="$t('auth_your_email')" type="email" name="email"  required />
                    </div>

                    <!-- Password -->
                    <div class="wrap-field">

                        <input id="password" v-model="password"  :placeholder="$t('auth_possword')" class="field-style"
                               type="password"
                               name="password"
                               required autocomplete="new-password" />
                    </div>

                    <div class="wrap-button-submit">
                        <button class="button-style-1">
                            {{ $t('auth_login_2') }}
                        </button>
                    </div>

                    <div class="reset-password"><router-link to="/reset-email">{{ $t('register_page_5') }}</router-link></div>
                </form>

                <div class="auth-text form-auth-true"  v-if="status === 'auth'">{{ $t('register_page_6') }}</div>
                <showing-errors :errors="error" />
            </div>

        </template>
    </MainLayout>
</template>


<script setup>
import {ref} from 'vue';
import {useRoute} from "vue-router";
import {notAuthRequest} from "@/api.js";
const route = useRoute();
import {useI18n} from "vue-i18n";
import ShowingErrors from "@/components/ShowingErrors.vue";
const {t} = useI18n({ useScope: 'global' })


let email = ref('');
let password = ref('');
let status = ref('noAuth');
let error =  ref('');

if (localStorage.getItem("token") !== null) {
    status.value = 'auth';
}

async function formSubmit(){

    let data = {
        email: email.value,
        password: password.value,
    }

    let response = await notAuthRequest('/api/login', 'post', data);
    if (response.data.status === 'success') {
        status.value = 'auth';
        error.value ='';
        localStorage.setItem('token', JSON.stringify(response.data.json))
    }
    else {
        error.value = response.data.text;
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
    margin-top:10px
}
.form-auth-false {
    color: #df3627;
}
.reset-password {
    margin-top:20px;
    cursor: pointer;
}
.reset-password:hover{
    color: #4e41d9;
}


</style>
