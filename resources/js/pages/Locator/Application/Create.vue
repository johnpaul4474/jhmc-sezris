<script setup lang="ts">
import { ref, watch, computed, onMounted } from 'vue'
import { useForm } from '@inertiajs/vue3'
import locators from '@/routes/locators'
import applications from '@/routes/applications'
import LocatorAppSidebarLayout from '@/layouts/locator/LocatorAppSidebarLayout.vue'
import DynamicFormRepeater from '@/components/locator/DynamicFormRepeater.vue'
import ApplicationOptionSelect from '@/components/locator/ApplicationOptionSelect.vue'
import UploadAttachment from '@/components/locator/UploadAttachment.vue'
import { Button } from '@/components/ui/button'
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
  expired_at: { type: String, default: null },
  control_number: [String, Number],
  form_number: [String, Number],
  form_title: [String],
})

const articles = ref([])
const uploadedFiles = ref([]) // store all uploaded files

watch(
  () => props.articleDetail,
  (newVal) => {
    if (newVal) articles.value.push(newVal)
  }
)

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

const applicationTypes = [
  'Gate Pass',
  'Permit to Bring In',
  'Permit to Bring Out',
  'Internal Tool',
  'Data Pipeline',
]

const selectedDeclaredValue = computed(() => {
  if (!props.options || !form.application_category_option_id) return null
  return props.options.find(
    (opt) => Number(opt.id) === Number(form.application_category_option_id)
  )
})

// Fetch all previously uploaded files for this application
const fetchUploads = async () => {
  if (!props.application_form_id) return
  try {
    const res = await fetch(`/loctr/uploads?application_form_id=${props.application_form_id}`)
    const data = await res.json()
    uploadedFiles.value = data.uploads
  } catch (err) {
    console.error('Failed to fetch uploads', err)
  }
}

onMounted(fetchUploads)

const onUploaded = (res: any) => {
  console.log('Uploaded:', res)
  uploadedFiles.value.push(...res.files) // add newly uploaded files
}

const onUploadError = (err: any) => {
  console.error('Upload error:', err)
}

const submit = () => {
  form.post('/loctr/applications')
}
</script>

<template>
  <LocatorAppSidebarLayout :breadcrumbs="breadcrumbs">
    <div class="p-6 w-full mx-6 bg-white shadow-xl rounded-xl border border-gray-100">
      <h1 class="text-3xl font-extrabold text-gray-900 mb-6 border-b pb-2">
        {{ props.form_title ? `Applying for ${props.form_title}` : 'Create New Application' }}
      </h1>

      <!-- Top Right Info -->
      <div v-if="props.application_form_id" class="text-right space-y-1 mb-4">
        <p v-if="props.form_number" class="text-sm text-gray-500">
          <b>Form Number:</b> {{ props.form_number }}
        </p>
        <p v-if="props.control_number" class="text-sm text-gray-500">
          <b>Control Number:</b> {{ props.control_number }}
        </p>
        <p v-if="selectedDeclaredValue" class="text-sm text-gray-500">
          <b>Declared Value:</b> {{ selectedDeclaredValue.name || selectedDeclaredValue.value }}
        </p>
        <p v-if="props.expired_at" class="text-sm text-gray-500">
          <b>Expires on:</b> {{ new Date(props.expired_at).toLocaleDateString() }}
        </p>
      </div>

      <form @submit.prevent="submit" class="space-y-6">
        <!-- Application Creator -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Application Creator</label>
          {{ props.user?.name }}
        </div>

        <!-- Application Type -->
        <div v-if="!props.form_title">
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
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
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

        <!-- Declared Value & Validity -->
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

        <!-- Upload Attachments -->
        <div v-if="props.expired_at" class="space-y-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">Upload Attachments</label>
          <UploadAttachment
            :application-form-id="props.application_form_id"
            upload-url="/loctr/uploads"
            accept="image/*"
            multiple
            @uploaded="onUploaded"
            @error="onUploadError"
          />

          <!-- Show uploaded files -->
          <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
            <div v-for="file in uploadedFiles" :key="file.id" class="border rounded p-2 bg-gray-50">
              <a :href="file.url" target="_blank" class="text-blue-600 hover:underline">
                {{ file.file_name }}
              </a>
            </div>
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
        <div v-if="!props.control_number" class="pt-4 flex justify-center">
          <Button type="submit" class="px-5 py-2" :disabled="form.processing">
            {{ form.processing ? 'Saving...' : 'Apply' }}
          </Button>
        </div>
      </form>
    </div>
  </LocatorAppSidebarLayout>
</template>
