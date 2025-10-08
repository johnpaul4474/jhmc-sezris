<template>
  <div class="space-y-4">
    <div>
      <label class="block font-medium text-gray-700 mb-1">Category</label>
      <select v-model="selectedCategory" class="w-full border border-gray-300 p-2">
        <option value="">-- Select Category --</option>
        <option v-for="cat in categories" :key="cat.id" :value="cat.id">
          {{ cat.name }}
        </option>
      </select>
    </div>

    <div v-if="options.length">
      <label class="block font-medium text-gray-700 mb-1">Options / Validity</label>
      <select v-model="selectedOption" class="w-full border border-gray-300 p-2">
        <option value="">-- Select Option --</option>
        <option v-for="opt in options" :key="opt.id" :value="opt.id">
           {{ opt.validity.label }}
        </option>
      </select>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch, defineProps, defineEmits } from 'vue'

const props = defineProps({
  categories: { type: Array, required: true },
  modelValue: { type: [String, Number], default: '' }
})
const emit = defineEmits(['update:modelValue'])

const selectedCategory = ref('')
const options = ref([])
const selectedOption = ref('')

// Watch category and update options
watch(selectedCategory, (catId) => {
  const cat = props.categories.find(c => c.id == catId)
  options.value = cat ? cat.options : []
  selectedOption.value = ''
  emit('update:modelValue', '')
})

// Watch selected option and update v-model
watch(selectedOption, (val) => emit('update:modelValue', val))
</script>
