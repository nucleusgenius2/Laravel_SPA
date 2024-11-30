<script setup>
import {useRoute} from "vue-router";
const route = useRoute();

import { useRecaptchaProvider } from 'vue-recaptcha'
useRecaptchaProvider()
</script>


<template>
    <RouterView />
</template>

<style scoped>
</style>


