<script setup>
import { Check, Clock, X, Play } from 'lucide-vue-next'
import { usePage } from '@inertiajs/vue3'
const page= usePage()
const props = defineProps({
  data: {
    type: Array,
    default: () => [],
  },
})

// Map status → color
const statusColor = (status) => {
  switch ((status || '').toLowerCase()) {
    case 'approved':
      return 'bg-green-500'
    case 'pending':
      return 'bg-yellow-400'
    case 'rejected':
      return 'bg-red-500'
    default:
      return 'bg-gray-400'
  }
}
</script>

<template>
  <div class="p-4 bg-white shadow rounded">
    <h2 class="text-lg font-bold mb-6 text-gray-800">Approvers Timeline</h2>

    <div class="flex items-center overflow-x-auto space-x-8 relative px-4 pb-6">
      <!-- ✅ Start Node -->
      <div class="flex flex-col items-center relative min-w-[140px]">
        <!-- Connector to first approver -->
        <div
          v-if="props.data.length"
          class="absolute top-[18px] left-1/2 h-1 w-[calc(100%+2rem)] z-0 flex items-center"
        >
          <div class="flex-1 h-1 rounded-full bg-green-500"></div>
        </div>

        <!-- Start Icon -->
        <div
          class="flex items-center justify-center w-10 h-10 rounded-full z-10 border-4 border-white text-white shadow bg-green-500"
        >
          <Play class="w-5 h-5 text-white" />
        </div>

        <div class="text-center mt-3 w-full px-2">
          <p class="font-semibold text-gray-700 truncate">{{ page.props.auth.user.name }}</p>
           <p class="text-sm text-gray-500">{{ page.props.application.status }}</p>
          <p class="text-sm text-gray-500">--(Applicant)--</p>
             <p class="text-sm text-gray-500">{{ new Date(page.props.application.updated_at).toLocaleString() }}</p>
        </div>
      </div>

      <!-- ✅ Approvers Loop -->
      <template v-for="(approver, index) in props.data" :key="approver.id">
        <div class="flex flex-col items-center relative min-w-[140px]">
          <!-- Connector line (no arrowhead) -->
          <div
            v-if="index < props.data.length - 1"
            class="absolute top-[18px] left-1/2 h-1 w-[calc(100%+2rem)] z-0 flex items-center"
          >
            <div
              class="flex-1 h-1 rounded-full relative"
              :class="statusColor(approver.pivot.status)"
            ></div>
          </div>

          <!-- Step icon -->
          <div
            class="flex items-center justify-center w-10 h-10 rounded-full z-10 border-4 border-white text-white shadow"
            :class="statusColor(approver.pivot.status)"
          >
            <template v-if="approver.pivot.status === 'Approved'">
              <Check class="w-5 h-5 text-white" />
            </template>
            <template v-else-if="approver.pivot.status === 'Rejected'">
              <X class="w-5 h-5 text-white" />
            </template>
            <template v-else>
              <Clock class="w-5 h-5 text-white" />
            </template>
          </div>

          <!-- Approver Info -->
          <div class="text-center mt-3 w-full px-2">
            <p class="font-semibold text-gray-700 truncate">{{ approver.name }}</p>
            <p class="text-sm text-gray-500 capitalize">
              {{ approver.pivot.status || '—' }}
            </p>
            <p class="text-sm text-gray-500 capitalize">
              {{ approver.pivot.role || '—' }}
            </p>
            <p v-if="approver.pivot.remark" class="text-xs text-gray-400">
              <b class="text-gray-700">Remark:</b> {{ approver.pivot.remark ?? '-' }}
            </p>
            <p v-if="approver.pivot.acted_at" class="text-xs text-gray-400">
              {{ new Date(approver.pivot.acted_at).toLocaleString() }}
            </p>
          </div>
        </div>
      </template>
    </div>

    <div
      v-if="!props.data.length"
      class="text-gray-500 text-sm italic text-center py-4"
    >
      No approvers found.
    </div>
  </div>
</template>
