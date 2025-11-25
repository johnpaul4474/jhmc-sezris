<script setup>
import OsacAppSidebarLayout from '@/layouts/Osac/OsacAppSidebarLayout.vue';
const props = defineProps({
  application: {
    type: String,
    required: true,
  },
})

</script>
<template>
<OsacAppSidebarLayout>
OSAC Show Application id: {{ props.application }}
</OsacAppSidebarLayout>
</template>