<template>
    <MainLayout :layout = 'route.meta.layout'>
        <template #content>

            <div class="max">

                <form id="v-model-form" class="form-auth"  @submit.prevent="formSubmit">
                    <div class="heading-form">{{ $t('reset_password_1') }}</div>


                    <div class="wrap-field">

                        <input id="password" v-model="password" :placeholder="$t('auth_possword')" class="field-style"
                               type="password"
                               name="password"
                               required autocomplete="new-password" />
                    </div>


                    <div class="wrap-field">
                        <input id="password_confirmation" v-model="passwordConfirm" :placeholder="$t('auth_repeat_password')" class="field-style"
                               type="password"
                               name="password_confirmation" required />
                    </div>

                    <div class="wrap-button-submit">
                        <button class="button-style-1">
                            {{ $t('auth_login_2') }}
                        </button>
                    </div>
                </form>

                <div class="auth-text form-auth-true"  v-if="status"> {{ $t('reset_password_2') }} </div>
                <showing-errors :errors="error" />
            </div>

        </template>
    </MainLayout>
</template>


<script setup lang="ts">
import {ref} from 'vue';
import {useRoute} from "vue-router";
import {notAuthRequest} from "@/api.ts";
import ShowingErrors from "@/components/ShowingErrors.vue";
const route = useRoute();
let password = ref('');
let passwordConfirm = ref('');
let status = ref(false);
let error =  ref('');

async function formSubmit(){

    let data = {
        password: password.value,
        password_confirmation: passwordConfirm.value,
        token: route.params.token,
        email :route.query.email,
        '_method': "PATCH"
    }

    let response = await notAuthRequest('/api/reset_password', 'post', data);
    if (response.data.status === 'success') {
        status.value = true;
        error.value ='';
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
}
.form-auth-false {
    color: #df3627;
}
</style>
