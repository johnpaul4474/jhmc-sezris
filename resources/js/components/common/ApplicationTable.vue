<script setup lang="ts">
import { Eye, Pencil, Trash2 } from 'lucide-vue-next' // 👈 Lucide icons

const props = defineProps({
  applications: {
    type: Array,
    required: true,
    default: () => [],
  },
})

const emit = defineEmits(['view', 'edit', 'delete'])
</script>

<template>
  <div class="overflow-x-auto bg-white border border-gray-300 rounded-lg shadow-sm">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-100">
        <tr>
          <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">#</th>
          <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Form Title</th>
          <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Control Number</th>
          <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Form Number</th>
          <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Status</th>
          <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 text-center">Actions</th>
        </tr>
      </thead>

      <tbody v-if="applications.length" class="divide-y divide-gray-200 bg-white">
        <tr v-for="(app, index) in applications" :key="app.id" class="hover:bg-gray-50">
          <td class="px-4 py-2 text-sm text-gray-600">{{ index + 1 }}</td>
          <td class="px-4 py-2 text-sm text-gray-800">{{ app.form_title }}</td>
          <td class="px-4 py-2 text-sm text-gray-600">{{ app.control_number ?? 'N/A' }}</td>
          <td class="px-4 py-2 text-sm text-gray-600">{{ app.form_number ?? '—' }}</td>
          <td class="px-4 py-2 text-sm text-gray-600 capitalize">{{ app.status ?? 'N/A' }}</td>

          <td class="px-4 py-2 text-sm flex items-center justify-center gap-2">
            <button
              @click="emit('view', app)"
              class="text-blue-600 hover:text-blue-800 p-1 rounded-full hover:bg-blue-50 transition"
              title="View"
            >
              <Eye class="w-5 h-5" />
            </button>
            <button
              @click="emit('edit', app)"
              class="text-green-600 hover:text-green-800 p-1 rounded-full hover:bg-green-50 transition"
              title="Edit"
            >
              <Pencil class="w-5 h-5" />
            </button>
            <button
              @click="emit('delete', app)"
              class="text-red-600 hover:text-red-800 p-1 rounded-full hover:bg-red-50 transition"
              title="Delete"
            >
              <Trash2 class="w-5 h-5" />
            </button>
          </td>
        </tr>
      </tbody>

      <tbody v-else>
        <tr>
          <td colspan="6" class="text-center text-gray-500 py-4">No applications found.</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
