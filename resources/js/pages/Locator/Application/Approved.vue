<script setup lang="ts">
import LocatorAppSidebarLayout from '@/layouts/locator/LocatorAppSidebarLayout.vue'
import { type BreadcrumbItem } from '@/types'
import locators from '@/routes/locators'
import applications from '@/routes/applications'
import ApplicationTable from '@/components/common/ApplicationTable.vue'
import TopCard from '@/components/common/TopCard.vue'
import { ref } from "vue";
import { Head, router } from '@inertiajs/vue3';


const props = defineProps({
  applications: {
    type: Array,
    required: true,
    default: () => [],
  },
})

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Locator', href: locators.index.url() },
  { title: 'Create Permit', href: applications.create.url() },
  { title: 'Pending Applications', href: applications.pending.url() },
  { title: 'Approved Applications', href: applications.approved.url() },
]


// Optional table action handlers
function handleView(app: any) {
  router.visit(`/loctr/applications/${app?.id}/approved`)
}

function handleEdit(app: any) {
  console.log('Edit', app)
}

function handleDelete(app: any) {
  console.log('Delete', app)
}
</script>

<template>
  
  <LocatorAppSidebarLayout :breadcrumbs="breadcrumbs">
    <TopCard  />
    <h1 class="text-2xl font-bold mb-4">Approved Application Lists</h1>
    <ApplicationTable
      :applications="props.applications"
      @view="handleView"
      @edit="handleEdit"
      @delete="handleDelete"
    />
  </LocatorAppSidebarLayout>
</template>
