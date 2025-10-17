<script setup lang="ts">
import LocatorAppSidebarLayout from '@/layouts/locator/LocatorAppSidebarLayout.vue'
import { type BreadcrumbItem } from '@/types'
import locators from '@/routes/locators'
import applications from '@/routes/applications'
import { Image } from 'lucide-vue-next'

const baseUrl = typeof window !== 'undefined' ? window.location.origin : ''

// Props from controller
const props = defineProps<{
  application: {
    id: number
    status: string
    form_title: string
    user_id: number
    control_number: string | null
    form_number: string
    created_at: string
    updated_at: string
    article_details: any[]
    uploads: any[]
    selections: {
      id: number
      application_id: number
      user_id: number
      option_id: number
      Expired_at: string
      selected_at: string | null
      amount: string
      created_at: string
      updated_at: string
    }[]
  }
}>()

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Locator', href: locators.index.url() },
  { title: 'Applications', href: applications.index.url() },
  { title: `Application #${props.application.id}`, href: '#' },
]
</script>

<template>
  <LocatorAppSidebarLayout :breadcrumbs="breadcrumbs">
    <!-- Title -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
        {{ props.application.form_title }}
      </h1>
      <p class="text-gray-600 dark:text-gray-400">
        Application #{{ props.application.id }} • Status:
        <span
          :class="{
            'text-yellow-600 dark:text-yellow-400': props.application.status === 'pending',
            'text-green-600 dark:text-green-400': props.application.status === 'approved',
            'text-red-600 dark:text-red-400': props.application.status === 'rejected',
          }"
        >
          {{ props.application.status }}
        </span>
      </p>
    </div>

    <!-- Basic Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
      <div class="p-4 border rounded-lg bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 shadow-sm">
        <h2 class="font-semibold mb-2 text-gray-900 dark:text-gray-100">Basic Information</h2>
        <ul class="space-y-1 text-sm text-gray-700 dark:text-gray-300">
          <li><strong>Form Number:</strong> {{ props.application.form_number }}</li>
          <li><strong>Control Number:</strong> {{ props.application.control_number ?? '—' }}</li>
          <li><strong>User ID:</strong> {{ props.application.user_id }}</li>
          <li><strong>Created At:</strong> {{ new Date(props.application.created_at).toLocaleString() }}</li>
          <li><strong>Updated At:</strong> {{ new Date(props.application.updated_at).toLocaleString() }}</li>
        </ul>
      </div>
    </div>

    <!-- Selections Table -->
    <div class="mb-8">
      <h2 class="text-lg font-semibold mb-2 text-gray-900 dark:text-gray-100">Selections</h2>
      <div
        v-if="props.application.selections.length"
        class="overflow-x-auto border rounded-lg border-gray-200 dark:border-gray-700"
      >
        <table class="min-w-full text-sm">
          <thead class="bg-gray-100 dark:bg-gray-700 text-left text-gray-800 dark:text-gray-200">
            <tr>
              <th class="p-2">ID</th>
              <th class="p-2">Option ID</th>
              <th class="p-2">Amount</th>
              <th class="p-2">Expires At</th>
              <th class="p-2">Created At</th>
            </tr>
          </thead>
          <tbody class="text-gray-700 dark:text-gray-300">
            <tr
              v-for="sel in props.application.selections"
              :key="sel.id"
              class="border-t border-gray-200 dark:border-gray-700"
            >
              <td class="p-2">{{ sel.id }}</td>
              <td class="p-2">{{ sel.option_id }}</td>
              <td class="p-2">{{ sel.amount }}</td>
              <td class="p-2">{{ sel.Expired_at }}</td>
              <td class="p-2">{{ new Date(sel.created_at).toLocaleString() }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <p v-else class="text-gray-500 dark:text-gray-400 text-sm">No selections found.</p>
    </div>

    <!-- Uploads -->
    <div class="mb-8">
      <h2 class="text-lg font-semibold mb-2 text-gray-900 dark:text-gray-100">
        Uploads / Supporting Documents
      </h2>
      <div v-if="props.application.uploads.length">
        <ul class="list-none text-sm space-y-1">
          <li v-for="(file, index) in props.application.uploads" :key="file.id ?? index">
            <a
              :href="`${baseUrl}/storage/${file.url ?? file.file_path ?? ''}`"
              target="_blank"
              class="flex items-center gap-2 text-blue-600 dark:text-blue-400 hover:underline"
            >
              <Image class="w-4 h-4 shrink-0" />
              <span class="truncate">
                {{ file.filename ?? file.original_name ?? `Image #${index + 1}` }}
              </span>
            </a>
          </li>
        </ul>
      </div>
      <p v-else class="text-gray-500 dark:text-gray-400 text-sm">No uploads found.</p>
    </div>

    <!-- Article Details Table -->
    <div class="mb-8">
      <h2 class="text-lg font-semibold mb-2 text-gray-900 dark:text-gray-100">Article Details</h2>
      <div
        v-if="props.application.article_details.length"
        class="overflow-x-auto border rounded-lg border-gray-200 dark:border-gray-700"
      >
        <table class="min-w-full text-sm">
          <thead class="bg-gray-100 dark:bg-gray-700 text-left text-gray-800 dark:text-gray-200">
            <tr>
              <th class="p-2">#</th>
              <th class="p-2">Marks & Number</th>
              <th class="p-2">Quantity</th>
              <th class="p-2">Description</th>
              <th class="p-2">Gross Weight</th>
              <th class="p-2">Created At</th>
            </tr>
          </thead>
          <tbody class="text-gray-700 dark:text-gray-300">
            <tr
              v-for="(detail, index) in props.application.article_details"
              :key="detail.id"
              class="border-t border-gray-200 dark:border-gray-700"
            >
              <td class="p-2">{{ index + 1 }}</td>
              <td class="p-2">{{ detail.marks_and_number }}</td>
              <td class="p-2">{{ detail.qty }}</td>
              <td class="p-2">{{ detail.detailed_description_of_article }}</td>
              <td class="p-2">{{ detail.gross_weight }}</td>
              <td class="p-2">{{ new Date(detail.created_at).toLocaleString() }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <p v-else class="text-gray-500 dark:text-gray-400 text-sm">No article details found.</p>
    </div>
  </LocatorAppSidebarLayout>
</template>

<style scoped>
table th,
table td {
  white-space: nowrap;
}
</style>
