<script setup>
import CcoAppSidebarLayout from '@/layouts/Cco/CcoAppSidebarLayout.vue';
const props = defineProps({
  application: {
    type: String,
    required: true,
  },
})
</script>
<template>
<CcoAppSidebarLayout>
CCO/CCA Show Application id: {{ props.application }}
</CcoAppSidebarLayout>
</template>