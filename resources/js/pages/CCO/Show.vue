<script setup>
import CcoAppSidebarLayout from '@/layouts/Cco/CcoAppSidebarLayout.vue';
import CcoApplicationTable from './CcoComponents/CcoApplicationTable.vue';
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
<CcoApplicationTable
      :applications="[props.application]"
      @view="handleView"
      @edit="handleEdit"
      @delete="handleDelete"
    />

</CcoAppSidebarLayout>
</template>