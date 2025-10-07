<script setup lang="ts">
import { defineProps, defineEmits } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps<{
  options: Array<{ id: number; name: string; value: number; validity: string }>
  modelValue: string | number | null
  applicationId: number | string
}>()

const emit = defineEmits(['update:modelValue'])

const onSelect = (event: Event) => {
  const target = event.target as HTMLSelectElement
  const selectedValue = target.value

  emit('update:modelValue', selectedValue)

  if (selectedValue) {
    router.post('/loctr/applications/option-selection', {
      application_id: props.applicationId,
      option_id: selectedValue,
    }, {
      preserveScroll: true,
      preserveState: true,
      onSuccess: () => {
        console.log('✅ Option selection saved')
      },
      onError: (errors) => {
        console.error('❌ Error saving selection:', errors)
      }
    })
  }
}
</script>

<template>
  <div>
    <label class="block text-sm font-medium text-gray-700 mb-1">
      Declared Value & Validity
    </label>
    <select
      :value="modelValue"
      @change="onSelect"
      class="w-full border border-gray-300 py-3 px-2 text-gray-800 focus:ring focus:ring-blue-300"
    >
      <option value="">-- Select an option --</option>
      <option
        v-for="option in options"
        :key="option.id"
        :value="option.id"
      >
        {{ option.name }} - {{ option.value }} ({{ option.validity }})
      </option>
    </select>
  </div>
</template>
