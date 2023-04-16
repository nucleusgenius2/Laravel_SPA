import './bootstrap';
import { createApp } from 'vue';

import App from './App.vue'
import router from '@/router/router.js'
import MainLayout from '@/layout/LayuotMain.vue';


createApp(App)
    .component(
        "MainLayout",  MainLayout //глобальное подключение компонента
    )
    .use(router)
    .mount('#app')



