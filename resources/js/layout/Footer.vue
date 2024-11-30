<template>
    <div class="wrap-footer">
        <div class="footer max">
            <div class="footer-col-1">{{ $t('footer_1') }}</div>

            <div class="footer-col-2">
                <p><a class="soc" href="https://vk.com/ru_faf" target="_blank" rel="noopener"><br />
                    <img src="/images/vk_1.png" alt="vk" /></a></p>
                <p><a class="soc" href="https://www.youtube.com/channel/UCkk4R0JY-4tYBIa92JxAzcg" target="_blank" rel="noopener"><br />
                    <img src="/images/youtube_1.png" alt="youtube" /></a></p>
                <p><a class="soc" href="https://discord.gg/4eaPAAjr95" target="_blank" rel="noopener"><br />
                    <img src="/images/discord.png" alt="discord" /></a></p>
                <div class="text-soc">{{ $t('footer_2') }} </div>
            </div>

            <div class="footer-col-3">
                <div class="wrap-button">
                    <div class="button-01 hover-purple">
                        <a :href="'/client/RUSC1.zip?v'+getRandomNumber(0, 10000)">{{ $t('download_client') }}</a>
                    </div>
                    <div class="pop-up-download">
                        <div><a :href="'/client/RUSC1.zip?v'+getRandomNumber(0, 10000)">{{ $t('installer_1') }} </a></div>
                        <div><a :href="'/client/RUSC2.zip?v'+getRandomNumber(0, 10000)">{{ $t('installer_2') }}</a></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
function getRandomNumber(min, max) {
    return Math.round(Math.random() * (max - min) + min);
}
</script>



<style scoped>


</style>
