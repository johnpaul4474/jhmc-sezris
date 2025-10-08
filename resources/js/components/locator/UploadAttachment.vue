<script setup>
import { ref } from 'vue'

const props = defineProps({
  applicationFormId: {
    type: [String, Number],
    required: true,
  },
  uploadUrl: {
    type: String,
    required: true,
  },
  accept: {
    type: String,
    default: '*/*',
  },
  multiple: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['uploaded', 'error'])

const files = ref([]) // store selected files
const fileInput = ref(null)
const isUploading = ref(false)

const onFileChange = (e) => {
  files.value = Array.from(e.target.files)
}

const triggerFileSelect = () => {
  fileInput.value.click()
}

const uploadFiles = async () => {
  if (!files.value.length) return

  isUploading.value = true
  const formData = new FormData()

  files.value.forEach((file) => {
    formData.append('files[]', file)
  })

  formData.append('application_form_id', props.applicationFormId)

  try {
    const response = await fetch(props.uploadUrl, {
      method: 'POST',
      body: formData,
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      },
    })

    if (!response.ok) throw new Error('Upload failed')

    const result = await response.json()
    emit('uploaded', result)
    files.value = []
    fileInput.value.value = '' // reset input
  } catch (error) {
    emit('error', error)
  } finally {
    isUploading.value = false
  }
}
</script>

<template>
  <div class="flex items-center gap-3">
    <!-- Hidden File Input -->
    <input
      type="file"
      ref="fileInput"
      class="hidden"
      :accept="accept"
      :multiple="multiple"
      @change="onFileChange"
    />

    <!-- Choose File Button -->
    <button
      type="button"
      class="px-3 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300"
      @click="triggerFileSelect"
    >
      Choose File
    </button>

    <!-- Upload Button -->
    <button
      v-if="files.length > 0"
      type="button"
      class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:bg-gray-400"
      :disabled="isUploading"
      @click="uploadFiles"
    >
      {{ isUploading ? 'Uploading...' : 'Upload' }}
    </button>

    <!-- Optional filename preview -->
    <div v-if="files.length" class="text-sm text-gray-500">
      {{ files.map(f => f.name).join(', ') }}
    </div>
  </div>
</template>
