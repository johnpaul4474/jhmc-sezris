<script setup lang="ts">
import { ref, watch, computed } from 'vue'
import { toast } from 'vue-sonner'
import { router } from '@inertiajs/vue3'

/** Props */
const props = defineProps<{
  mode: 'add' | 'renew'
  initialData?: Record<string, any>
}>()

/** Emits */
const emit = defineEmits<{
  (e: 'close'): void
  (e: 'saved', payload: any): void
}>()

/** Reactive form */
const form = ref({
  businessName: '',
  parentCompany: '',
  natureOfContract: '',
  tradeName: '',
  email: '',
  location: '',
  contactPerson: '',
  contactNumber: '',
  accreditation: '',
  taxpayerName: '',
  tin: '',
  psicPrimary: '',
  psicSecondary: '',
  mainOffice: '',
  documents: [] as File[],
})

/** Watch for incoming data to prefill */
watch(
  () => props.initialData,
  (val) => {
    if (val) {
      form.value = {
        ...form.value,
        ...val,
        documents: [],
      }
    }
  },
  { immediate: true }
)

/** Upload + drag/drop logic */
const isDragging = ref(false)

function handleFileChange(e: Event) {
  const input = e.target as HTMLInputElement
  if (input.files) addFiles(Array.from(input.files))
  input.value = ''
}
function handleDragOver(e: DragEvent) {
  e.preventDefault()
  isDragging.value = true
}
function handleDragLeave(e: DragEvent) {
  e.preventDefault()
  isDragging.value = false
}
function handleDrop(e: DragEvent) {
  e.preventDefault()
  isDragging.value = false
  const files = Array.from(e.dataTransfer?.files || [])
  addFiles(files)
}
function addFiles(files: File[]) {
  files.forEach((f) => form.value.documents.push(f))
}
function removeFile(idx: number) {
  form.value.documents.splice(idx, 1)
}
function formatFileSize(size: number) {
  if (size < 1024) return size + ' B'
  if (size < 1024 * 1024) return (size / 1024).toFixed(1) + ' KB'
  return (size / (1024 * 1024)).toFixed(2) + ' MB'
}

/** Computed title and color by mode */
const title = computed(() =>
  props.mode === 'add'
    ? '➕ Add New Service Provider / Supplier'
    : '♻️ Renew Service Provider / Supplier'
)
const headerColor = computed(() => (props.mode === 'add' ? '#3AB54A' : '#0F75BC'))

/** Save handler */
async function save() {
  try {
    const formData = new FormData()
    Object.entries(form.value).forEach(([key, val]) => {
      if (key === 'documents') {
        (val as File[]).forEach((file) => formData.append('documents[]', file))
      } else {
        formData.append(key, val as any)
      }
    })

    // Example: POST to Laravel endpoint
    await router.post('/service-providers', formData, {
      onSuccess: () => {
        toast.success(`${props.mode === 'add' ? 'Added' : 'Renewed'} successfully!`)
        emit('saved', form.value)
        emit('close')
      },
      onError: () => {
        toast.error('Something went wrong. Please try again.')
      },
    })
  } catch (err) {
    toast.error('Failed to save.')
  }
}
</script>

<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
    <div class="w-full max-w-4xl bg-white rounded-2xl shadow-xl overflow-auto max-h-[90vh]">
      <!-- HEADER BAR -->
      <div
        class="sticky top-0 flex items-center justify-between px-6 py-4"
        :style="{ backgroundColor: headerColor }"
      >
        <h3 class="text-lg font-semibold text-white">{{ title }}</h3>

        <button
          @click="emit('close')"
          class="text-white hover:text-white/80 ml-4 text-xl leading-none"
          aria-label="Close"
        >
          ✕
        </button>
      </div>

      <!-- BODY -->
      <form @submit.prevent="save" class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
        <input v-model="form.businessName" required placeholder="Business Name"
               class="col-span-3 border rounded px-3 py-2" />

        <input v-model="form.parentCompany" placeholder="Parent Company" class="border rounded px-3 py-2" />
        <input v-model="form.natureOfContract" placeholder="Nature of Contract" class="border rounded px-3 py-2" />
        <input v-model="form.tradeName" placeholder="Trade Name" class="border rounded px-3 py-2" />

        <input v-model="form.email" type="email" placeholder="Email" class="border rounded px-3 py-2" />
        <input v-model="form.location" placeholder="Location (JHSEZ)" class="border rounded px-3 py-2" />
        <input v-model="form.contactPerson" placeholder="Contact Person" class="border rounded px-3 py-2" />

        <input v-model="form.contactNumber" placeholder="Contact Number" class="border rounded px-3 py-2" />
        <input v-model="form.accreditation" placeholder="Accreditation" class="border rounded px-3 py-2" />
        <input v-model="form.taxpayerName" placeholder="Taxpayer's Name" class="border rounded px-3 py-2" />

        <div class="col-span-3 flex gap-3">
          <input v-model="form.psicSecondary" placeholder="PSIC Secondary" class="flex-1 border rounded px-3 py-2" />
          <input v-model="form.mainOffice" placeholder="Main Office Address" class="flex-1 border rounded px-3 py-2" />
        </div>

        <!-- Upload -->
        <div
          class="col-span-3"
          @dragover.prevent="handleDragOver"
          @dragleave.prevent="handleDragLeave"
          @drop.prevent="handleDrop"
        >
          <label class="block mb-2 text-sm font-medium text-gray-700">Upload Documents</label>
          <div
            :class="[
              'relative border-2 border-dashed rounded-lg p-6 flex flex-col items-center justify-center cursor-pointer',
              isDragging ? 'border-green-600 bg-green-50' : 'border-gray-300 bg-white'
            ]"
          >
            <svg class="w-8 h-8 mb-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M7 16v-4a4 4 0 014-4h1a4 4 0 014 4v4M7 16h10M7 16l-2 2m12-2 2 2" />
            </svg>
            <p class="text-sm text-gray-600 mb-2">Click or drag files here to upload</p>
            <input
              type="file"
              multiple
              @change="handleFileChange"
              class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
            />
          </div>

          <ul v-if="form.documents.length" class="mt-3 space-y-2">
            <li v-for="(file, idx) in form.documents" :key="idx"
                class="flex items-center justify-between bg-gray-50 border rounded px-3 py-2">
              <div class="truncate">
                <div class="font-medium truncate">{{ file.name }}</div>
                <div class="text-xs text-gray-500">{{ formatFileSize(file.size) }}</div>
              </div>
              <button type="button" @click="removeFile(idx)" class="text-red-600 hover:text-red-800 px-2 py-1 rounded">
                Remove
              </button>
            </li>
          </ul>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="col-span-3 flex justify-end gap-3 mt-3">
          <button type="button" @click="emit('close')" class="px-4 py-2 bg-gray-100 rounded text-gray-700">Cancel</button>
          <button type="submit" :class="[
            'px-5 py-2 text-white rounded font-medium',
            props.mode === 'add' ? 'bg-[#3AB54A]' : 'bg-[#0F75BC]'
          ]">
            {{ props.mode === 'add' ? 'Save' : 'Submit Renewal' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
