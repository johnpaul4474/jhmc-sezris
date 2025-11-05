<script setup lang="ts">
import { defineProps } from 'vue'

const props = defineProps<{
  title?: string
  details: Array<{
    id: number | string
    application_form_id?: number
    user_id?: number
    marks_and_number: string
    qty: number
    detailed_description_of_article: string
    gross_weight: string
    created_at?: string
  }>
  showActions?: boolean
}>()
</script>

<template>
  <div class="w-full my-6 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <!-- Header -->
    <div v-if="title" class="px-4 py-3 border-b border-gray-100 bg-gray-50">
      <h2 class="text-lg font-semibold text-gray-700">{{ title }}</h2>
    </div>

    <!-- Table -->
    <table class="min-w-full divide-y divide-gray-100">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Marks & Numbers</th>
          <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Qty</th>
          <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Description</th>
          <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Gross Weight</th>
          <th v-if="showActions" class="px-4 py-2 text-right text-sm font-medium text-gray-600">Actions</th>
        </tr>
      </thead>

      <tbody class="divide-y divide-gray-100">
        <tr v-for="item in details" :key="item.id">
          <td class="px-4 py-2 text-sm text-gray-700">{{ item.marks_and_number }}</td>
          <td class="px-4 py-2 text-sm text-gray-700">{{ item.qty }}</td>
          <td class="px-4 py-2 text-sm text-gray-600">{{ item.detailed_description_of_article }}</td>
          <td class="px-4 py-2 text-sm text-gray-700">{{ item.gross_weight }}</td>

          <td v-if="showActions" class="px-4 py-2 text-right">
            <button
              class="text-blue-600 hover:underline text-sm"
              @click="$emit('view', item)"
            >
              View
            </button>
            <button
              class="ml-3 text-indigo-600 hover:underline text-sm"
              @click="$emit('edit', item)"
            >
              Edit
            </button>
            <button
              class="ml-3 text-red-600 hover:underline text-sm"
              @click="$emit('delete', item)"
            >
              Delete
            </button>
          </td>
        </tr>

        <tr v-if="details.length === 0">
          <td colspan="6" class="px-4 py-3 text-center text-gray-400 text-sm">
            No article details found.
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
