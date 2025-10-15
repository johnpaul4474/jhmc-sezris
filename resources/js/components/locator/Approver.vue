<script setup>
import { ref } from 'vue'

// Sample data — replace or fetch as needed
const approvers = ref([
  { id: 1, name: 'John Doe', position: 'Department Head', status: 'Approved', date: '2025-10-12' },
  { id: 2, name: 'Jane Smith', position: 'Supervisor', status: 'Pending', date: '2025-10-13' },
  { id: 3, name: 'Michael Lee', position: 'Manager', status: 'Rejected', date: '2025-10-14' },
])
</script>

<template>
  <div class="p-4">
    <div class="relative border-l-2 border-gray-300">
      <div
        v-for="approver in approvers"
        :key="approver.id"
        class="mb-8 ml-4"
      >
        <!-- Dot -->
        <div
          class="absolute -left-2.5 mt-1.5 w-4 h-4 rounded-full border-2"
          :class="{
            'bg-green-500 border-green-500': approver.status === 'Approved',
            'bg-yellow-500 border-yellow-500': approver.status === 'Pending',
            'bg-red-500 border-red-500': approver.status === 'Rejected',
          }"
        ></div>

        <!-- Content -->
        <div class="bg-white p-4 rounded-lg shadow-sm">
          <div class="flex justify-between items-center mb-1">
            <h3 class="text-sm font-semibold">
              {{ approver.name }}
            </h3>
            <span class="text-xs text-gray-500">
              {{ approver.date }}
            </span>
          </div>
          <p class="text-xs text-gray-600 mb-1">{{ approver.position }}</p>
          <span
            class="inline-block px-2 py-0.5 rounded text-xs font-medium"
            :class="{
              'bg-green-100 text-green-800': approver.status === 'Approved',
              'bg-yellow-100 text-yellow-800': approver.status === 'Pending',
              'bg-red-100 text-red-800': approver.status === 'Rejected',
            }"
          >
            {{ approver.status }}
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Optional: adjust spacing for mobile */
@media (max-width: 640px) {
  .timeline-item {
    margin-left: 2rem;
  }
}
</style>
