<template>

<div>
    <div class="auth-text form-auth-false" v-if="props.errors">
       <div v-if="checkObject(props.errors)">
               <div v-for="(el, key) in props.errors" :key="key">
                   <div v-if="Array.isArray(el)" v-for="(text, key) in el">
                       {{ $t(text) }}
                   </div>
                  <span v-else>{{ $t(el) }}</span>
               </div>
        </div>
        <span v-else-if="typeof props.errors === 'string'"> {{$t(removeQuotes(props.errors))}}</span>
        <span v-else>{{ $t('Произошла ошибка') }}</span>
    </div>
</div>
</template>


<script setup lang="ts">
let props = defineProps({
    errors: {
        type: [String, Object, Array],
        required: true,
    }
});

function checkObject(errors: any){
    return typeof errors === 'object' && errors !== null;
}
function removeQuotes(str: string) {
    if ((str.startsWith('"') && str.endsWith('"')) || (str.startsWith("'") && str.endsWith("'"))) {
        return str.slice(1, -1);
    }
    return str;
}
</script>



<style scoped>

</style>
