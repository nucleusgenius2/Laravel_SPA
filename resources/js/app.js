import './bootstrap';
import { createApp } from 'vue';
import { VueRecaptchaPlugin } from 'vue-recaptcha'

import App from './App.vue'
import router from '@/router/router.js'
import MainLayout from '@/layout/LayuotMain.vue';;
import { createHead } from '@unhead/vue'
const head = createHead()

//для локализации
import {i18n} from "@/i18n/localization.js";


createApp(App)
    .component(
        "MainLayout",  MainLayout //глобальное подключение компонента
    )
    .use(router)
    .use( i18n )
    .use(head) //без этого капча не работает
    .use(VueRecaptchaPlugin, {
        v2SiteKey: '6LdkXbMnAAAAAB3OhZw6Ptg2zfDzG_y0nEfbORBy',
    })
    .mount('#app')



