this code just adjust it add a margin to the right so it will look nice
<script setup lang="ts">
import { Users, FileText, UserPlus, FilePlus } from 'lucide-vue-next'

const props = defineProps({
  stats: {
    type: Object,
    required: true,
  },
})
</script>

<template>
  <!-- Full-width container -->
  <div class="grid gap-6 md:grid-cols-4 px-4 py-4">
    <div
      class="flex flex-col justify-center items-center rounded-2xl bg-blue-500 p-6 shadow-lg transition hover:shadow-xl dark:bg-[#1b1b18] cursor-pointer"
    >
      <div class="flex flex-col items-center gap-2">
        <div class="rounded-full bg-blue-100 p-3 dark:bg-blue-900/40">
          <FilePlus class="w-6 h-6 text-[#0F75BC]" />
        </div>
        <h3 class="text-lg font-semibold text-white dark:text-gray-200">
          ATO Certified
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
          Active Users
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
          SEZAD Requests
        </h3>
        
      </div>
    </div>

    <!-- BDD Created Users -->
    <div
      class="flex flex-col justify-center items-center rounded-2xl bg-yellow-500 p-6 shadow-lg transition hover:shadow-xl dark:bg-[#1b1b18]"
    >
      <div class="flex flex-col items-center gap-2">
        <div class="rounded-full bg-green-100 p-3 dark:bg-green-900/40">
          <UserPlus class="w-6 h-6 text-green-600"  />
        </div>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
          BDD Created Users
        </h3>
        
      </div>
    </div>
  </div>
</template>
