<template>
    <MainLayout :layout = 'route.meta.layout'>
        <template #content>

            <div class="wrap-block-1">
                <div class="max">
                    <div class="two-swap">
                        <div class="two-block doverie">
                            <h2> {{ $t('home_page_1') }}</h2>
                            <p class="text">{{ $t('home_page_2') }}  </p>
                        </div>

                        <div class="two-block">
                            <p class="img-txt"><img src="/images/Group_5_2x-1.png" alt="img"> {{ $t('home_page_3') }}  </p>
                            <p class="img-txt"><img src="/images/Group_7_2x.png" alt="img"> {{ $t('home_page_4') }}</p>
                            <p class="img-txt"><img src="/images/Group_6_2x.png" alt="img"> {{ $t('home_page_5') }} </p>
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
                            <h2> {{ $t('home_page_6') }} </h2>
                            <p class="text"> <span class="step">1</span> {{ $t('home_page_7') }}</p>
                            <p class="text"> <span class="step">2</span> {{ $t('home_page_8') }}</p>
                            <p class="text"> <span class="step">3</span> {{ $t('home_page_9') }} <a href="https://disk.yandex.ru/d/p8kKNv3iEJAz7g" target="_bank"> {{ $t('home_page_10') }} </a></p>
                            <p class="text"> <span class="step">4</span> {{ $t('home_page_11') }} </p>
                            <p class="text"> <span class="step">5</span> {{ $t('home_page_12') }} </p>
                            <p class="text"> <span class="step">6</span> {{ $t('home_page_13') }}  <a href="https://discord.gg/4eaPAAjr95" target="_bank" rel="noopener">{{ $t('localization.discord') }} </a> {{ $t('localization.ru_server') }}</p>
                        </div>
                    </div>

                </div>
            </div>


            <div class="m-top max">
                <h2> {{ $t('home_page_14') }} </h2>
                <News page="1" total="6" pagination="false"/>
            </div>


        </template>
    </MainLayout>
</template>


<script setup lang="ts">
import News from '@/components/News.vue';
import {useRoute} from "vue-router";
const route = useRoute();
</script>


<style scoped>

</style>


