<template>
    <MainLayout :layout = 'route.meta.layout'>
        <template #content>

            <div class="wrap-block-1">
                <div class="max">
                    <div class="two-swap">
                        <div class="two-block">
                            <h2> Почему стоит играть на RU сервере?</h2>
                            <p class="text"> Удобный сервер с русскоязычной техподдержкой и возможность перенести свой
                                рейтинг. </p>
                        </div>

                        <div class="two-block">
                            <p class="img-txt"><img src="/images/Group_5_2x-1.png" alt="img"> Не нужно покупать игру в стим
                                (покупка невозможна для РФ) </p>
                            <p class="img-txt"><img src="/images/Group_7_2x.png" alt="img"> Выбор между классикой (faf) и новым
                                балансом.</p>
                            <p class="img-txt"><img src="/images/Group_6_2x.png" alt="img"> Нет перманентных банов, лояльное
                                отношение к игрокам </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="fon-02 home-rate-block">
                <div class="max">

                    <div class="two-swap">
                        <div class="two-block">
                            <img src="/images/Group-223234.png" alt="img">
                        </div>

                        <div class="two-block">
                            <h2> Хотите сохранить свой рейтинг? </h2>
                            <p class="text"> 1. Зарегистрируйтесь на <a href="https://faforever.ru/" target="_bank"
                                                                        rel="noopener">RU сервере</a>.</p>
                            <p class="text"> 2. Зайдите в официальный <a href="https://discord.gg/rDhgNbUPqf" target="_bank"
                                                                         rel="noopener">дискорд</a> RU сервера. </p>
                            <p class="text"> 3. Напишите сообщение в ЛС nucleus_genius</p>
                            <p class="text"> 4. В сообщении укажите ваш Username (ник) на RU сервере и приложите скриншот где
                                виден ваш ник и рейтинг на оригинальном сервере. Ваш никнейм дискорда должен совпадать или
                                частично совпадать с вашим игровым ником на оригинальном сервере (или с ником из истории ваших
                                имен на оригинальном сервере). За предоставление ложных данных или попытку назваться чужим
                                именем рейтинг будет обнулен, без возможности повторной заявки.
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            <div class="m-top max">
                <h2> Последние новости </h2>
                <News page="1" total="6" pagination="false"/>
            </div>

        </template>
    </MainLayout>
</template>


<script setup>
import News from '@/components/News.vue';
import {useRoute} from "vue-router";
const route = useRoute();
</script>


<style scoped>
    .two-swap {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        margin: 35px 0px;
        margin-top: 5px;
    }

    .two-block {
        width: 49%;
    }

    .wrap-block-1 h2 {
        max-width: 410px;
        margin-bottom: 15px;
    }

    .wrap-block-1 .text {
        color: #7A7A7A;
        font-size: 20px;
        max-width: 365px;
        line-height: 1.5;
    }

    .img-txt {
        display: flex;
        align-items: center;
        font-weight: 600;
        text-transform: uppercase;
        color: #212121;
        max-width: 380px;
        margin-bottom: 25px;
        line-height: 1.4;
    }

    .img-txt img {
        margin-right: 20px;
    }

    .fon-02 {
        background: #F9F9F9;
        padding: 1px 0;
        margin-top: 46px;
        width: 100%;
        margin-left: 0px;
        margin-right: 0px;
        max-width: 100%;
        padding-top: 20px;
    }

    .fon-02 .text {
        font-size: 17px;
        color: #7A7A7A;
    }

    .m-top {
        margin-top: 30px;
    }

    .title a {
        text-decoration: none;
        color: #000;
    }
</style>


