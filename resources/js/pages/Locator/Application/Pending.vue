<script setup lang="ts">
import LocatorAppSidebarLayout from '@/layouts/locator/LocatorAppSidebarLayout.vue'
import { type BreadcrumbItem } from '@/types'
import locators from '@/routes/locators'
import applications from '@/routes/applications'
import ApplicationTable from '@/components/common/ApplicationTable.vue'
import TopCard from '@/components/common/TopCard.vue'
import { router, usePage } from '@inertiajs/vue3'


const page = usePage()
//import Approver from '@/components/locator/Approver.vue'

const props = defineProps({
  applications: {
    type: Array,
    required: true,
    default: () => [],
  },
  ATO: {
    type: String,
    required: true,
    default: '', // optional default
  }
})
const stats = {
  atoCertified: '',
  activeUsers: '',
  sezadRequests: '',
  bddUsers: '',
}

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Locator', href: locators.index.url() },
  { title: 'Create Permit', href: applications.create.url() },
  { title: 'Pending Applications', href: applications.pending.url() },
]

// Optional table action handlers
function handleView(app: any) {
  console.log(app.application_id)
  router.visit(`/loctr/applications/${app.application_id}`)
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
    <TopCard :stats="props.ATO[0].status"/>
   
    <h1 class="text-2xl font-bold mb-4">Pending Application Lists</h1>
  
  
    <ApplicationTable
      :applications="props.applications"
      @view="handleView"
      @edit="handleEdit"
      @delete="handleDelete"
    />
   
  </LocatorAppSidebarLayout>
</template>
