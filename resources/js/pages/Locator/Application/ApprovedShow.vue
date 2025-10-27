<script setup lang="ts">
import LocatorAppSidebarLayout from '@/layouts/locator/LocatorAppSidebarLayout.vue'
import { type BreadcrumbItem } from '@/types'
import locators from '@/routes/locators'
import applications from '@/routes/applications'
import ApplicationTable from '@/components/common/ApplicationTable.vue'
import { ref } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import { formatDate } from '@vueuse/core'

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

function handleView(app: any) {
  router.visit(`/loctr/applications/${app?.id}/approved`)
}

function handleEdit(app: any) {
  console.log('Edit', app)
}

function handleDelete(app: any) {
  console.log('Delete', app)
}
function formatDate(date: string | null) {
if (!date) return '—'
try {
return new Date(date).toLocaleDateString('en-US', {
year: 'numeric',
month: 'long',
day: 'numeric',
})
} catch {
return date
}
}
</script>

<template>
  <LocatorAppSidebarLayout :breadcrumbs="breadcrumbs">
    <div class="p-4">
      <h1 class="text-2xl font-bold mb-4 text-gray-900 dark:text-gray-100">Approved Application Details</h1>
    
      <section class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <h2 class="text-lg font-medium mb-3 text-gray-800 dark:text-gray-100">Basic Information</h2>
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <dt class="font-medium text-gray-600 dark:text-gray-300">Application ID</dt>
            <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ props.applications.id }}</dd>
          </div>
          <div>
            <dt class="font-medium text-gray-600 dark:text-gray-300">Status</dt>
            <dd class="mt-1 text-gray-900 dark:text-gray-100 capitalize">{{ props.applications.status }}</dd>
          </div>
          <div>
            <dt class="font-medium text-gray-600 dark:text-gray-300">Applying For</dt>
            <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ props.applications.form_title }}</dd>
          </div>
          <div>
            <dt class="font-medium text-gray-600 dark:text-gray-300">Form Number</dt>
            <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ props.applications.form_number }}</dd>
          </div>
          <div class="md:col-span-2">
            <dt class="font-medium text-gray-600 dark:text-gray-300">Validity</dt>
            <dd class="mt-1 text-gray-900 dark:text-gray-100">{{formatDate(props.applications.created_at) }} to {{formatDate(props.applications.selections[0].Expired_at) }}</dd>
          </div>
        </dl>
      </section>

      <section
  class="mt-5 bg-white border border-gray-200 rounded-lg p-4 shadow-sm 
         dark:bg-gray-800 dark:border-gray-700"
>
  <h2 class="text-lg font-medium mb-3 text-gray-800 dark:text-gray-100 flex items-center gap-2">
    <span
      class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-amber-400 text-black text-xs font-semibold"
    >
      {{ props.applications.article_details?.length ?? 0 }}
    </span>
    Article Details
  </h2>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
      <thead class="bg-gray-50 dark:bg-gray-900">
        <tr>
          <th class="px-3 py-2 text-left font-medium text-gray-600 dark:text-gray-300">
            Marks & Number
          </th>
          <th class="px-3 py-2 text-left font-medium text-gray-600 dark:text-gray-300">
            Quantity
          </th>
          <th class="px-3 py-2 text-left font-medium text-gray-600 dark:text-gray-300">
            Description
          </th>
          <th class="px-3 py-2 text-left font-medium text-gray-600 dark:text-gray-300">
            Gross Weight
          </th>
        </tr>
      </thead>

      <tbody
        v-if="props.applications.article_details?.length"
        class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
      >
        <tr
          v-for="(item, index) in props.applications.article_details"
          :key="index"
          class="hover:bg-gray-50 dark:hover:bg-gray-900 transition"
        >
          <td class="px-3 py-2 align-top text-gray-900 dark:text-gray-100">
            {{ item.marks_and_number ?? '—' }}
          </td>
          <td class="px-3 py-2 align-top text-gray-900 dark:text-gray-100">
            {{ item.qty ?? '—' }}
          </td>
          <td class="px-3 py-2 align-top text-gray-900 dark:text-gray-100">
            {{ item.detailed_description_of_article ?? '—' }}
          </td>
          <td class="px-3 py-2 align-top text-gray-900 dark:text-gray-100">
            {{ item.gross_weight ?? '—' }}
          </td>
        </tr>
      </tbody>

      <tbody v-else>
        <tr>
          <td
            colspan="4"
            class="px-3 py-3 text-center text-gray-500 dark:text-gray-400"
          >
            No article details available.
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</section>


     <section class="mt-5 bg-white border border-gray-200 rounded-lg p-4 shadow-sm dark:bg-gray-800 dark:border-gray-700">
  <h2 class="text-lg font-medium mb-3 text-gray-800 dark:text-gray-100 flex items-center gap-2">
    <span
      class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-amber-400 text-black text-xs font-semibold"
    >
      {{ props.applications.uploads.length }}
    </span>
    Uploaded Supporting Documents
  </h2>
  <div class="overflow-x-auto">
    <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
      <thead class="bg-gray-50 dark:bg-gray-900">
        <tr>
          <th class="px-3 py-2 text-left font-medium text-gray-600 dark:text-gray-300">File Name</th>
          <th class="px-3 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Type</th>
          <th class="px-3 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Size</th>
          <th class="px-3 py-2 text-left font-medium text-gray-600 dark:text-gray-300">Actions</th>
        </tr>
      </thead>

      <tbody class="bg-white dark:bg-gray-800">
        <tr v-if="props.applications.uploads.length > 0">
          <td class="px-3 py-2 text-gray-900 dark:text-gray-100">
            {{ props.applications.uploads[0].file_name }}
          </td>
          <td class="px-3 py-2 text-gray-900 dark:text-gray-100">
            {{ props.applications.uploads[0].file_type ?? '—' }}
          </td>
          <td class="px-3 py-2 text-gray-900 dark:text-gray-100">
            {{ props.applications.uploads[0].file_size ?? '—' }}
          </td>
          <td class="px-3 py-2">
            <a
  :href="`${$page.props.appUrl}/storage/${props.applications.uploads[0]?.file_path ?? '#'}`"
  target="_blank"
  class="text-blue-600 dark:text-blue-400 hover:underline"
>
  Open
</a>
          </td>
        </tr>

        <tr v-else>
          <td
            class="px-3 py-2 text-gray-500 dark:text-gray-400 text-center"
            colspan="4"
          >
            No supporting documents.
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</section>


    </div>
  </LocatorAppSidebarLayout>
</template>