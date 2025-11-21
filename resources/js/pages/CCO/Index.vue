<script setup>
import CcoAppSidebarLayout from '@/layouts/Cco/CcoAppSidebarLayout.vue';
import CcoApplicationTable from './CcoComponents/CcoApplicationTable.vue';
const p = defineProps({
  applications:{
      type:Object,
      required: true
 }
})
const handleView = (application)=>{
  console.log(application.application)
}
const handleEdit = (application) =>{
  console.log(application)
}
const handleDelete =(application) =>{
  console.log(application)
}
</script>

<template>
    <CcoAppSidebarLayout>
  <div>
    <h1 class="text-2xl font-bold text-center mb-4">CCO/CCA Dashboard </h1>
   
    <CcoApplicationTable 
     :applications="p.applications"
      @view="handleView"
      @edit="handleEdit"
      @delete="handleDelete"
    />
    
  </div>
  </CcoAppSidebarLayout>
</template>
