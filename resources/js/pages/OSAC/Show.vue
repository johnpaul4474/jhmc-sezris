<script setup>
import OsacAppSidebarLayout from '@/layouts/Osac/OsacAppSidebarLayout.vue'
import osacApplicationTable from './OsacComponents/osacApplicationTable.vue'

const props = defineProps({
  application: {
    type: Array,  
    required: true,
  },
})

const handleView = (app) => {
  onsole.log("Viewing:", JSON.parse(JSON.stringify(app)))
}

const handleEdit = (app) => {
 onsole.log("Editing:", JSON.parse(JSON.stringify(app)))
}

const handleDelete = (app) => {
  onsole.log("Deleting:", JSON.parse(JSON.stringify(app)))
}
</script>

<template>
  <OsacAppSidebarLayout>
    <osacApplicationTable
      :applications="[props.application]"
      @view="handleView"
      @edit="handleEdit"
      @delete="handleDelete"
    />
  </OsacAppSidebarLayout>
</template>
