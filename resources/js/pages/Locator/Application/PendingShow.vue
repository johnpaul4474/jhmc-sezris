<script setup lang="ts">
import LocatorAppSidebarLayout from '@/layouts/locator/LocatorAppSidebarLayout.vue'
import { type BreadcrumbItem } from '@/types'
import locators from '@/routes/locators'
import applications from '@/routes/applications'
import { computed } from 'vue'
import { Head, router } from '@inertiajs/vue3'

/* ---------------------------
   Props Definition
--------------------------- */
const props = defineProps({
  applications: {
    type: Array,
    required: true,
    default: () => [],
  },
})

/* ---------------------------
   Breadcrumbs
--------------------------- */
const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Locator', href: locators.index.url() },
  { title: 'Create Permit', href: applications.create.url() },
  { title: 'Pending Applications', href: applications.pending.url() },
  { title: 'Approved Applications', href: applications.approved.url() },
]

/* ---------------------------
   Computed Helpers
--------------------------- */
// Get the first application (if any)
const app = computed(() => props.applications[0])

// Get the first selection (if any)
const selection = computed(() => app.value?.selections?.[0])

/* ---------------------------
   Utility Functions
--------------------------- */
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

/* ---------------------------
   Handlers
--------------------------- */
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
    <div v-if="app" class="p-4">
      <h1 class="text-2xl font-bold mb-4 text-gray-900 dark:text-gray-100">
        Pending Application Details
      </h1>

      <section
        class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm dark:bg-gray-800 dark:border-gray-700"
      >
        <h2 class="text-lg font-medium mb-3 text-gray-800 dark:text-gray-100">
          Basic Information
        </h2>

        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Application ID -->
          <div>
            <dt class="font-medium text-gray-600 dark:text-gray-300">
              Application ID
            </dt>
            <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ app.id }}</dd>
          </div>

          <!-- Status -->
          <div>
            <dt class="font-medium text-gray-600 dark:text-gray-300">Status</dt>
            <dd class="mt-1 text-gray-900 dark:text-gray-100 capitalize">
              {{ app.status }}
            </dd>
          </div>

          <!-- Applying For -->
          <div>
            <dt class="font-medium text-gray-600 dark:text-gray-300">
              Applying For
            </dt>
            <dd class="mt-1 text-gray-900 dark:text-gray-100">
              {{ app.form_title }}
            </dd>
          </div>

          <!-- Form Number -->
          <div>
            <dt class="font-medium text-gray-600 dark:text-gray-300">
              Form Number
            </dt>
            <dd class="mt-1 text-gray-900 dark:text-gray-100">
              {{ app.form_number }}
            </dd>
          </div>

          <!-- Validity -->
          <div class="md:col-span-2">
            <dt class="font-medium text-gray-600 dark:text-gray-300">
              Validity
            </dt>
            <dd class="mt-1 text-gray-900 dark:text-gray-100">
              {{ formatDate(app.created_at) }} to
              {{ formatDate(selection?.Expired_at) }}
            </dd>
          </div>
        </dl>
      </section>
    </div>

    <!-- Fallback if no application exists -->
    <div v-else class="p-4 text-gray-500 dark:text-gray-400">
      No application data available.
    </div>
  </LocatorAppSidebarLayout>
</template>
