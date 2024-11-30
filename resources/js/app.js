import './bootstrap';
import { createApp } from 'vue';

import App from './App.vue'
import router from '@/router/router.js'
import MainLayout from '@/layout/LayuotMain.vue';

//для локализации
import {i18n} from "@/i18n/localization.js";

createApp(App)
    .component(
        "MainLayout",  MainLayout //глобальное подключение компонента
    )
    .use(router)
    .use( i18n )
    .mount('#app')



