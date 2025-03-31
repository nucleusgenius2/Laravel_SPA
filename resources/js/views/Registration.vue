<template>
    <MainLayout :layout = 'route.meta.layout' :auth='status'>
        <template #content>

            <div class="max">

                <form id="v-model-form" class="form-auth"  @submit.prevent="formSubmit" v-if="status === 'noAuth'">
                    <div class="wrap-form">
                        <div class="heading-form">{{ $t('auth_registration') }}</div>
                        <!-- Name -->
                        <div class="wrap-field">
                            <input id="name" v-model="name"  class="field-style" autocomplete="off" :placeholder="$t('auth_nickname')" type="text" name="name"  required autofocus />
                        </div>

                        <!-- Email Address -->
                        <div class="wrap-field">
                            <input id="email" v-model="email" class="field-style" autocomplete="off" :placeholder="$t('auth_your_email')" type="email" name="email"  required />
                        </div>

                        <!-- Password -->
                        <div class="wrap-field">

                            <input id="password" v-model="password" :placeholder="$t('auth_possword')" class="field-style"
                                   type="password"
                                   name="password"
                                   required autocomplete="new-password" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="wrap-field">
                            <input id="password_confirmation" v-model="passwordConfirm" :placeholder="$t('auth_repeat_password')" class="field-style"
                                   type="password"
                                   name="password_confirmation" required />
                        </div>
                    </div>

                    <input style='display: none' v-model="location" class="field-style" autocomplete="off" name="location"  required />

                    <Checkbox v-model="captcha" />

                    <div class="wrap-button-submit">
                        <button class="button-style-1">
                            {{ $t('auth_registration_send') }}
                        </button>
                    </div>

                    <div class="auth-text form-auth-true"  v-if="status === 'auth'">{{ $t('register_page_6') }}</div>
                    <showing-errors :errors="error" />

                    <div class="wrap-already-reg">

                        <span>{{ $t('auth_already_account') }}</span>

                        <a class="already-reg" style="margin-left: 5px;" href="/login">
                            {{ $t('auth_login_2') }}
                        </a>
                    </div>

                    <div class="rule-register">
                        <p class="warning"> {{ $t('register_page_1') }}:</p>
                        <p>{{ $t('register_page_2') }}</p>
                        <p>{{ $t('register_page_3') }}</p>
                        <p>{{ $t('register_page_4') }}</p>
                    </div>
                </form>

                <div class="auth-text form-auth-true" v-if="status === 'auth'">
                    <p>{{ $t('register_page_8') }}</p>
                    <p>{{ $t('register_page_9') }}</p>
                </div>


            </div>
        </template>
    </MainLayout>
</template>


<script setup>
import {onMounted, ref} from 'vue';
import {notAuthRequest} from "@/api.ts";
import {useRoute} from "vue-router";
import {Checkbox} from 'vue-recaptcha'
import ShowingErrors from "@/components/ShowingErrors.vue";

const route = useRoute();
let name = ref('');
let email = ref('');
let password = ref('');
let passwordConfirm = ref('');
let status = ref('noAuth');
let error = ref('');
let captcha = ref('');
let location = ref(navigator.language);


if (localStorage.getItem("token") !== null) {
    status.value = 'auth';
}

async function formSubmit() {
    let data = {
        name: name.value,
        email: email.value,
        password: password.value,
        password_confirmation: passwordConfirm.value,
        'g-recaptcha-response': captcha.value,
        location: location.value
    }

    let response = await notAuthRequest('/api/registration', 'post', data);
    if (response.data.status === 'success') {
        status.value = 'auth';
        error.value = '';
        localStorage.setItem('token', JSON.stringify(response.data.json))
    } else {
        status.value = 'noAuth';
        error.value = response.data.text;
        if (!response.data.text) {
            error.value = response.data.message
        }
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
    outline: none;
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
    margin-top: 10px;
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
    display: inline-block;
    cursor: pointer;
    transition: 0.3s;
    width: 100%;
}

.button-style-1:hover {
    background: #4e41d9;
}

.wrap-field {
    margin-bottom: 20px;
}

.form-auth {
    max-width: 500px;
    margin: auto;
}

.heading-form {
    font-weight: 500;
    font-size: 22px;
    line-height: 28px;
    color: #11202E;
    margin-bottom: 20px;
    text-align: center;
}


</style>
