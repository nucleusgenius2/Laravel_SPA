<template>
    <Header v-if="props.layout == 'mainLayout'" />
    <Menu v-if="props.layout == 'mainLayout'" />

    <div class="content">
        <slot name="content"></slot>
    </div>

    <Footer v-if="props.layout == 'mainLayout'" />
</template>


<script setup>
import Menu from '@/components/Menu.vue';
import Footer from '@/layout/Footer.vue';
import Header from '@/layout/Header.vue';
let props = defineProps({
    layout: String,
})


</script>

<style scoped>

</style>
