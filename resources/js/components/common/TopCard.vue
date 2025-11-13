<script setup lang="ts">
import { Crown,Users, FileText, UserPlus, FilePlus } from 'lucide-vue-next'
import { usePage } from '@inertiajs/vue3';
const page = usePage()
const props = defineProps({
  stats: {
    type: String,
    required: true,
    default:'',
  },
})

const status = {
  atoCertified: '',
  activeUsers: '',
  sezadRequests: '',
  bddUsers: '',
}



if (props.stats === 'Pending') {
  status.atoCertified = 'ATO Expired'
} else if (props.stats === 'Approved') {
  status.atoCertified = 'ATO Certified'
}

</script>

<template>
  <!-- Added right margin for spacing -->
  <div class="grid gap-6 md:grid-cols-4 px-4 py-4 mr-4">
    <!-- ATO Certified -->
   
    <div
      class="flex flex-col justify-center items-center rounded-2xl bg-blue-500 p-6 shadow-lg transition hover:shadow-xl dark:bg-[#1b1b18] cursor-pointer"
    >
      <div class="flex flex-col items-center gap-2">
        <div class="rounded-full bg-blue-100 p-3 dark:bg-blue-900/40">
          <Crown class="w-6 h-6 text-[#0F75BC]" />
        </div>
        <h3 class="text-lg font-semibold text-white dark:text-gray-200">
        {{ status?.atoCertified ? status.atoCertified : '' }}
        </h3>
      </div>
    </div>

    <!-- Active Users -->
    <div
      class="flex flex-col justify-center items-center rounded-2xl bg-blue-500 p-6 shadow-lg transition hover:shadow-xl dark:bg-[#1b1b18]"
    >
      <div class="flex flex-col items-center gap-2">
        <div class="rounded-full bg-blue-100 p-3 dark:bg-blue-900/40">
          <Users class="w-6 h-6 text-[#0F75BC]" />
        </div>
        <h3 class="text-lg font-semibold text-white dark:text-gray-200">
          {{ status.activeUsers  ? status.activeUsers : ''}}
        </h3>
      </div>
    </div>

    <!-- SEZAD Requests -->
    <div
      class="flex flex-col justify-center items-center rounded-2xl bg-green-400 p-6 shadow-lg transition hover:shadow-xl dark:bg-[#1b1b18]"
    >
      <div class="flex flex-col items-center gap-2">
        <div class="rounded-full bg-purple-100 p-3 dark:bg-purple-900/40">
          <FileText class="w-6 h-6 text-purple-600" />
        </div>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
          {{ status.sezadRequests ? status.sezadRequest : '' }}
        </h3>
      </div>
    </div>

    <!-- BDD Created Users -->
    <div
      class="flex flex-col justify-center items-center rounded-2xl bg-yellow-500 p-6 shadow-lg transition hover:shadow-xl dark:bg-[#1b1b18]"
    >
      <div class="flex flex-col items-center gap-2">
        <div class="rounded-full bg-green-100 p-3 dark:bg-green-900/40">
          <UserPlus class="w-6 h-6 text-green-600" />
        </div>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
          {{ status.bddUsers ? status.bddUsers: ''}}
        </h3>
      </div>
    </div>
  </div>
</template>
