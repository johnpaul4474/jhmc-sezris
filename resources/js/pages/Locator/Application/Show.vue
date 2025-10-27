<script setup lang="ts">
import { ref, computed } from 'vue'
import LocatorAppSidebarLayout from '@/layouts/locator/LocatorAppSidebarLayout.vue'
import { type BreadcrumbItem } from '@/types'
import locators from '@/routes/locators'
import applications from '@/routes/applications'
import { Image } from 'lucide-vue-next'
import { router } from '@inertiajs/vue3'

const baseUrl = typeof window !== 'undefined' ? window.location.origin : ''

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
    selections: any[]
  }
  approverGroup: {
    id: number
    name: string
    description: string | null
  } | null
  approvers: {
    id: number
    name: string
    email: string
    pivot: {
      role: string | null
      sequence: number
      status: string | null
      acted_at: string | null
      remark: string | null
    }
  }[]
}>()

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Locator', href: locators.index.url() },
  { title: 'Applications', href: applications.index.url() },
  { title: `Application #${props.application.id}`, href: '#' },
]

// reactive list for SPA update
const approvers = ref([...props.approvers])

// modal & remark for return
const showReturnModal = ref(false)
const returnRemark = ref('')
const currentApproverId = ref<number | null>(null)

// helper to check if an approver can act
const canAct = (approver: any) => {
  
  if ( approver.pivot.status === 'Approved') return false
  if (approver.pivot.sequence === 1) return true
  const prev = approvers.value.find(a => a.pivot.sequence === approver.pivot.sequence - 1)
  return prev && prev.pivot.status === 'Approved'
}

// SPA POST handlers
const handleApprove = (approverId: number) => {
  router.post(
    `/application-for-approval/${props.application.form_number}/approvers/${approverId}/approve`,
    {},
    {
      onSuccess: page => {
        // update local state
        const idx = approvers.value.findIndex(a => a.id === approverId)
        if (idx !== -1) approvers.value[idx].pivot.status = 'Approved'
      }
    }
  )
}

const handleReturnClick = (approverId: number) => {
  currentApproverId.value = approverId
  returnRemark.value = ''
  showReturnModal.value = true
}

const submitReturn = () => {
  if (!currentApproverId.value) return
  router.post(
    `/application-for-approval/${props.application.form_number}/approvers/${currentApproverId.value}/return`,
    { remark: returnRemark.value },
    {
      onSuccess: page => {
        const idx = approvers.value.findIndex(a => a.id === currentApproverId.value)
        if (idx !== -1) {
          approvers.value[idx].pivot.status = 'returned'
          approvers.value[idx].pivot.remark = returnRemark.value
        }
        showReturnModal.value = false
        returnRemark.value = ''
      }
    }
  )
}
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
            'text-red-600 dark:text-red-400': props.application.status === 'returned',
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

    <!-- Approver Group -->
    <div class="mb-8" v-if="props.approverGroup">
      <h2 class="text-lg font-semibold mb-2 text-gray-900 dark:text-gray-100">Approver Group</h2>
      <div class="p-4 border rounded-lg bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 shadow-sm">
        <p class="text-sm text-gray-700 dark:text-gray-300"><strong>Name:</strong> {{ props.approverGroup.name }}</p>
        <p class="text-sm text-gray-700 dark:text-gray-300"><strong>Description:</strong> {{ props.approverGroup.description ?? '—' }}</p>
      </div>
    </div>

    <!-- Approvers Table -->
    <div v-if="approvers.length">
      <h2 class="text-lg font-semibold mb-2 text-gray-900 dark:text-gray-100">Approvers</h2>
      <div class="overflow-x-auto border rounded-lg border-gray-200 dark:border-gray-700">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-100 dark:bg-gray-700 text-left text-gray-800 dark:text-gray-200">
            <tr>
              <th class="p-2">#</th>
              <th class="p-2">Name</th>
              <th class="p-2">Email</th>
              <th class="p-2">Role</th>
              <th class="p-2">Sequence</th>
              <th class="p-2">Status</th>
              <th class="p-2">Remark</th>
              <th class="p-2">Actions</th>
            </tr>
          </thead>
          <tbody class="text-gray-700 dark:text-gray-300">
            <tr v-for="(approver, index) in approvers" :key="approver.id" class="border-t border-gray-200 dark:border-gray-700">
              <td class="p-2">{{ index + 1 }}</td>
              <td class="p-2">{{ approver.name }}</td>
              <td class="p-2">{{ approver.email }}</td>
              <td class="p-2">{{ approver.pivot.role ?? '—' }}</td>
              <td class="p-2">{{ approver.pivot.sequence }}</td>
              <td class="p-2">{{ approver.pivot.status ?? 'Pending' }}</td>
              <td class="p-2">{{ approver.pivot.remark ?? '-' }}</td>
              <td class="p-2 flex gap-2">
                <button
                  v-if="canAct(approver)"
                  @click="handleApprove(approver.id)"
                  class="px-3 py-1 rounded bg-green-600 hover:bg-green-700 text-white text-xs font-medium"
                >
                  Approve
                </button>
              
                <button
                  v-if="canAct(approver)"
                  @click="handleReturnClick(approver.id)"
                  class="px-3 py-1 rounded bg-red-600 hover:bg-red-700 text-white text-xs font-medium"
                >
                  Return
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Return Modal -->
    <div v-if="showReturnModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black/50">
      <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-96">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Return Remark</h3>
        <textarea
          v-model="returnRemark"
          placeholder="Enter remark..."
          class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded mb-4 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
        ></textarea>
        <div class="flex justify-end gap-2">
          <button
            class="px-4 py-2 rounded bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-gray-100"
            @click="showReturnModal = false"
          >
            Cancel
          </button>
          <button
            class="px-4 py-2 rounded bg-red-600 hover:bg-red-700 text-white"
            @click="submitReturn"
          >
            Submit
          </button>
        </div>
      </div>
    </div>
  </LocatorAppSidebarLayout>
</template>

<style scoped>
table th,
table td {
  white-space: nowrap;
}
</style>
