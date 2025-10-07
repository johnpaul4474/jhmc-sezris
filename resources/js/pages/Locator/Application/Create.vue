<script setup lang="ts">
import { ref, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import locators from '@/routes/locators'
import applications from '@/routes/applications'
import LocatorAppSidebarLayout from '@/layouts/locator/LocatorAppSidebarLayout.vue'
import DynamicFormRepeater from '@/components/locator/DynamicFormRepeater.vue'
import ApplicationOptionSelect from '@/components/locator/ApplicationOptionSelect.vue'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import { type BreadcrumbItem } from '@/types'

const props = defineProps({
  user: Object,
  application_form_id: [String, Number],
  articleDetail: { type: Object, default: () => null },
  categories: { type: Array, default: () => [] },
  options: { type: Array, default: () => [] },
})

const articles = ref([])

watch(() => props.articleDetail, (newVal) => {
  if (newVal) articles.value.push(newVal)
})

const applicationTypes = [
  'Gate Pass',
  'Permit-to-Bring-In',
  'Permit-to-Bring-out',
  'Internal Tool',
  'Data Pipeline',
]

const form = useForm({
  user_id: props.user?.id,
  type: '',
  description: '',
  application_category_option_id: '',
})

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Locator', href: locators.index.url() },
  { title: 'Create Permit', href: applications.create.url() },
]

const submit = () => {
  form.post('/loctr/applications')
}
</script>

<template>
  <LocatorAppSidebarLayout :breadcrumbs="breadcrumbs">
    <div class="p-6 w-full mx-6 bg-white shadow-xl rounded-xl border border-gray-100">
      <h1 class="text-3xl font-extrabold text-gray-900 mb-6 border-b pb-2">
        Create New Application
      </h1>
    
      <form @submit.prevent="submit" class="space-y-6">
        <!-- Application Creator -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Application Creator</label>
          {{ props.user?.name }}
        </div>

        <!-- Application Type (Dropdown) -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Application Type</label>
          <DropdownMenu>
            <DropdownMenuTrigger as-child>
              <Button variant="outline" class="w-full justify-between">
                <span>{{ form.type || '-- Select an application type --' }}</span>
                <svg
                  class="ml-2 h-4 w-4 opacity-50"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 9l-7 7-7-7" />
                </svg>
              </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent class="w-full">
              <DropdownMenuItem
                v-for="type in applicationTypes"
                :key="type"
                @click="form.type = type"
                class="cursor-pointer"
              >
                {{ type }}
              </DropdownMenuItem>
            </DropdownMenuContent>
          </DropdownMenu>
          <div v-if="form.errors.type" class="text-red-500 text-sm mt-1">
            {{ form.errors.type }}
          </div>
        </div>

        <!-- Declared Value & Validity (ApplicationOptionSelect Component) -->
        <div v-if="props.application_form_id">
          <ApplicationOptionSelect
              v-model="form.application_category_option_id"
              :options="props.options"
              :application-id="props.application_form_id"
            />
          <div v-if="form.errors.application_category_option_id" class="text-red-500 text-sm mt-1">
            {{ form.errors.application_category_option_id }}
          </div>
        </div>

        <!-- Description (Optional) -->
        <div class="hidden">
          <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
          <textarea
            v-model="form.description"
            rows="4"
            class="w-full border border-gray-300 rounded-md p-3 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 transition"
            placeholder="Optional description"
          ></textarea>
          <div v-if="form.errors.description" class="text-red-500 text-sm mt-1">
            {{ form.errors.description }}
          </div>
        </div>

        <!-- Dynamic Article Repeater -->
        <div v-if="props.application_form_id" class="mt-8">
          <DynamicFormRepeater
            :formId="props.application_form_id"
            v-model="articles"
            :title="`Article Details for Order # ${props.application_form_id}`"
          />
        </div>

        <!-- Submit -->
        <div class="pt-4 flex justify-center">
          <Button
            type="submit"
            class="px-5 py-2"
            :disabled="form.processing"
          >
            {{ form.processing ? 'Saving...' : 'Apply' }}
          </Button>
        </div>
      </form>
    </div>
  </LocatorAppSidebarLayout>
</template>
