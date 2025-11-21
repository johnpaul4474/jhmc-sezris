<script setup>
const props = defineProps({
    ato:{
        type:String,
        required:true,
    }
})

</script>
<template><b>Successful</b> Application of Authority to Operate(ATO) with id:{{ props.ato }}</template>