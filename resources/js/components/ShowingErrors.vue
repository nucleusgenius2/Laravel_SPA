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
        <span v-else> {{$t( removeQuotes(props.errors) )}}</span>
    </div>
</div>
</template>


<script setup>
let props = defineProps({
    errors: {
        type: [String, Object, Array],
        required: true,
    }
});

function checkObject(errors){
    console.log(typeof errors === 'object' && errors !== null)
    return typeof errors === 'object' && errors !== null;
}
function removeQuotes(str) {

    if ((str.startsWith('"') && str.endsWith('"')) || (str.startsWith("'") && str.endsWith("'"))) {
        return str.slice(1, -1);
    }
    return str;
}
</script>



<style scoped>

</style>
