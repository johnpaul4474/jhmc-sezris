<script setup>
import { ref, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'

import locatorAppSidebarLayout from '@/layouts/locator/locator-AppSidebarLayout.vue'
import { ChevronDown, Check } from 'lucide-vue-next'
import DynamicFormRepeater from '@/components/locator/DynamicFormRepeater.vue'

// UI dropdown imports
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'

// Props from Laravel controller
const props = defineProps({
  user: Object,
  application_form_id: [String, Number],
  articleDetail: {
    type: Object,
    default: () => null,
  },
})

// Local state for articles (linked with DynamicFormRepeater via v-model)
const articles = ref([])

// Watch for new article pushed back from backend
watch(
  () => props.articleDetail,
  (newVal) => {
    if (newVal) articles.value.push(newVal)
  }
)

// Application types
const applicationTypes = [
  'Gate Pass',
  'Permit-to-Bring-In',
  'Permit-to-Bring-out',
  'Internal Tool',
  'Data Pipeline',
]

// Inertia form state
const form = useForm({
  user_id: props.user.id,
  type: '',
  description: '',
})

// Handlers
const selectType = (type) => {
  form.type = type
}

const submit = () => {
  form.post('/applications')
}
</script>

<template>
  <locatorAppSidebarLayout>
    <!-- Application Card -->
    <div class="p-6 w-full mx-6 bg-white shadow-xl rounded-xl border border-gray-100">
      <h1 class="text-3xl font-extrabold text-gray-900 mb-6 border-b pb-2">
        Create New Application
      </h1>
      
      <form @submit.prevent="submit" class="space-y-6">
        
        <!-- Creator Name (Read-only) -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Application Creator
          </label>
          <input
            type="text"
            :value="props.user.name"
            readonly
            class="w-full bg-gray-50 border-gray-200 cursor-not-allowed text-gray-600 rounded-md p-2.5 shadow-sm focus:ring-0 focus:border-gray-200"
          />
        </div>

        <!-- Type (Dropdown Select) -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Application Type
          </label>
          
          <DropdownMenu>
            <DropdownMenuTrigger as-child>
              <button
                type="button"
                class="w-full justify-between inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                :class="{ 'text-gray-500': !form.type, 'border-red-500': form.errors.type }"
              >
                {{ form.type || 'Select an application type' }}
                <ChevronDown class="ml-2 h-4 w-4 text-gray-400" />
              </button>
            </DropdownMenuTrigger>
            
            <DropdownMenuContent class="w-64 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
              <DropdownMenuItem 
                v-for="type in applicationTypes" 
                :key="type"
                @click="selectType(type)"
                class="flex items-center justify-between cursor-pointer hover:bg-gray-100 p-2 transition duration-100"
                :class="{ 'bg-gray-100 font-semibold text-blue-600': form.type === type }"
              >
                {{ type }}
                <Check v-if="form.type === type" class="h-4 w-4 text-blue-600" />
              </DropdownMenuItem>
            </DropdownMenuContent>
          </DropdownMenu>
          <div v-if="form.errors.type" class="text-red-500 text-sm mt-1">
            {{ form.errors.type }}
          </div>
        </div>

        <!-- Description -->
        <div>
          <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
            Description
          </label>
          <textarea
            id="description"
            v-model="form.description"
            rows="4"
            class="w-full border border-gray-300 rounded-md p-3 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 transition"
            :class="{ 'border-red-500': form.errors.description }"
            placeholder="Enter a detailed description of the application's purpose and features"
          ></textarea>
          <div v-if="form.errors.description" class="text-red-500 text-sm mt-1">
            {{ form.errors.description }}
          </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
          <button
            type="submit"
            class="w-full bg-blue-600 text-white px-4 py-2.5 rounded-lg font-semibold text-base hover:bg-blue-700 disabled:opacity-50 transition duration-150"
            :disabled="form.processing"
          >
            {{ form.processing ? 'Saving...' : 'Apply' }}
          </button>
        </div>
      </form>
    </div>

    <!-- Articles Repeater -->
    <div v-if="props.application_form_id" class="mt-8 mx-6">
      <DynamicFormRepeater 
        :formId="props.application_form_id" 
        v-model="articles" 
        :title="`Article Details for Order # ${props.application_form_id}`" 
      />
     {{ articles }}
    </div>
  </locatorAppSidebarLayout>
  
</template>
