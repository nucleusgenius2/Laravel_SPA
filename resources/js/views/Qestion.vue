<template>
    <MainLayout :layout = 'route.meta.layout'>
        <template #content>
            <div class="max">
                <p>{{ $t('qestion_page_1') }} <a href='https://discord.gg/4eaPAAjr95' target='blank' rel="noopener">{{ $t('in.discord') }}</a>, {{ $t('qestion_page_2') }}</p>

                <div class='help-block'>
                    <p>{{ $t('qestion_page_3') }}:</p>
                    <p>1) {{ $t('qestion_page_4') }} <b>{{ $t('qestion_page_5') }}</b></p>
                    <p>2) {{ $t('qestion_page_6') }} <b>{{ $t('qestion_page_7') }}  </b>, {{ $t('qestion_page_8') }} </p>
                    <p>3) {{ $t('qestion_page_9') }} <b>{{ $t('qestion_page_10') }}</b> {{ $t('qestion_page_11') }}</p>
                    <p>4) {{ $t('qestion_page_12') }} <b> {{ $t('qestion_page_13') }} </b>, {{ $t('qestion_page_14') }}. </p>
                    <p>5) {{ $t('qestion_page_15') }} <b>{{ $t('qestion_page_16') }} </b></p>
                    <p>6) {{ $t('qestion_page_17') }} <b>{{ $t('qestion_page_18') }}</b></p>
                    <p>7) {{ $t('qestion_page_19') }}   <b>{{ $t('qestion_page_20') }}</b></p>
                </div>

                <div class='help-block'>
                    <p>{{ $t('qestion_page_22') }}:</p>
                    <p><a href="https://disk.yandex.ru/d/p8kKNv3iEJAz7g">7) {{ $t('qestion_page_22') }}</a></p>
                </div>

            </div>

        </template>
    </MainLayout>
</template>


<script setup>
    import {useRoute} from "vue-router";
    const route = useRoute();
</script>



<style scoped>

</style>
