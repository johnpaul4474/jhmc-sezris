<script setup>
import { ref, watch } from 'vue'
import { useForm } from '@inertiajs/vue3' 
import { Plus, X, Box, Save, Trash2 } from 'lucide-vue-next'

// --- Props and Emits for v-model functionality ---
const props = defineProps({
  modelValue: {
    type: Array,
    default: () => ([]),
  },
  title: {
    type: String,
    default: 'Submit New Shipping Article',
  },
  formId: {
    type: [String, Number],
    required: true,
  }
})

const emit = defineEmits(['update:modelValue'])

// --- State ---
const savedArticles = ref(props.modelValue || []) 
const isOpen = ref(false) 

// Inertia form state, aligned to DB fields
const form = useForm({
  application_form_id: props.formId,
  marks_and_number: '',
  qty: null,
  detailed_description_of_article: '',
  gross_weight: '',
})

// --- Logic ---
const openModal = () => {
  form.reset()
  isOpen.value = true
}

const closeModal = () => {
  isOpen.value = false
}

const submitArticle = () => {
  form.post('/articles', {
    onSuccess: (page) => {
      // If Laravel returns JSON, grab the article from response
      const newArticle = page.props?.article || page?.article;
      if (newArticle) {
        savedArticles.value.push(newArticle);
        emit('update:modelValue', savedArticles.value);
      }
      form.reset();
      closeModal();
    },
    onError: (errors) => {
      console.error('Validation Errors:', errors);
    },
    preserveScroll: true,
    preserveState: true,
  });
};

const removeArticle = (id) => {
  console.log(`SIMULATION: Deleting Article with ID ${id}. Use Inertia.delete('/articles/${id}') for real.`)
  const index = savedArticles.value.findIndex(a => a.id === id)
  if (index !== -1) {
    savedArticles.value.splice(index, 1)
    emit('update:modelValue', savedArticles.value)
  }
}

// keep parent v-model in sync
watch(() => props.modelValue, (newVal) => {
  savedArticles.value = newVal ? newVal.map(a => ({ id: a.id || crypto.randomUUID(), ...a })) : []
}, { deep: true, immediate: true })
</script>

<template>
  <div class="font-sans antialiased p-4 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto p-6 bg-white rounded-xl shadow-2xl space-y-6">
      <!-- Title -->
      <h1 class="text-3xl font-extrabold text-gray-900 border-b pb-4 flex items-center space-x-3">
        <Box class="w-7 h-7 text-indigo-600" />
        <span>Article Submission Tracker <code>( Form ID: {{ props.formId }})</code></span>
      </h1>

      <!-- Add Button -->
      <button 
        @click="openModal" 
        class="flex items-center space-x-2 px-6 py-3 bg-indigo-600 text-white font-semibold text-lg rounded-xl shadow-lg hover:bg-indigo-700 transition duration-200 transform hover:scale-[1.02] active:scale-100 disabled:opacity-50"
        :disabled="isOpen"
      >
        <Plus class="w-5 h-5" />
        <span>Add New Article</span>
      </button>

      <!-- Table -->
      <h2 class="text-2xl font-bold text-gray-800 pt-4">Saved Articles ({{ savedArticles.length }})</h2>
      <div class="overflow-x-auto shadow-xl rounded-xl border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-indigo-50">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider w-1/12">#</th>
              <th class="px-4 py-3 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider w-2/12">Marks & Number</th>
              <th class="px-4 py-3 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider w-1/12">Quantity</th>
              <th class="px-4 py-3 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider w-4/12">Description</th>
              <th class="px-4 py-3 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider w-3/12">Weight</th>
              <th class="px-4 py-3 text-right text-xs font-bold text-indigo-700 uppercase tracking-wider w-1/12">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-if="savedArticles.length === 0">
              <td colspan="6" class="px-4 py-8 text-center text-gray-500 italic bg-white">
                No articles have been submitted yet. Click 'Add New Article' to begin.
              </td>
            </tr>
            <tr 
              v-for="(article, index) in savedArticles" 
              :key="article.id"
              class="hover:bg-gray-50 transition duration-100"
            >
              <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ index + 1 }}</td>
              <td class="px-4 py-3 text-sm font-semibold text-blue-800">{{ article.marks_and_number }}</td>
              <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ article.qty }}</td>
              <td class="px-4 py-3 text-sm text-gray-700 max-w-xs truncate">{{ article.detailed_description_of_article }}</td>
              <td class="px-4 py-3 text-sm text-gray-500">{{ article.gross_weight || '—' }}</td>
              <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                <button
                  type="button"
                  @click="removeArticle(article.id)"
                  aria-label="Remove item"
                  class="p-1 rounded-full text-red-500 hover:bg-red-100 transition shadow-sm"
                >
                  <Trash2 class="w-4 h-4" />
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal -->
    <div 
      v-if="isOpen" 
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70"
      aria-modal="true"
      role="dialog"
      @click.self="closeModal"
    >
      <div 
        class="relative w-full max-w-xl max-h-[90vh] overflow-y-auto bg-white rounded-xl shadow-2xl"
        @click.stop
      >
        <form @submit.prevent="submitArticle" class="p-6 sm:p-8 space-y-6">
          <div class="flex justify-between items-center border-b pb-4">
            <h2 class="text-2xl font-extrabold text-gray-900">{{ title }}</h2>
            <button type="button" @click="closeModal" aria-label="Close modal"
              class="p-2 rounded-full text-gray-500 hover:bg-gray-100 hover:text-gray-900 transition">
              <X class="w-6 h-6" />
            </button>
          </div>

          <p class="text-xs text-gray-500 italic">Submitting article for parent ID: {{ form.application_form_id }}</p>

          <!-- Form Fields -->
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Marks and number</label>
              <input v-model="form.marks_and_number" type="text" required
                placeholder="e.g., BOX-001 / Fragile"
                class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-indigo-500 focus:border-indigo-500" />
              <div v-if="form.errors.marks_and_number" class="mt-1 text-red-500 text-xs">{{ form.errors.marks_and_number }}</div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
              <input v-model.number="form.qty" type="number" min="1" required
                placeholder="e.g., 10"
                class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-indigo-500 focus:border-indigo-500" />
              <div v-if="form.errors.qty" class="mt-1 text-red-500 text-xs">{{ form.errors.qty }}</div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
              <input v-model="form.detailed_description_of_article" type="text" required
                placeholder="Full description (material, model, function)"
                class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-indigo-500 focus:border-indigo-500" />
              <div v-if="form.errors.detailed_description_of_article" class="mt-1 text-red-500 text-xs">{{ form.errors.detailed_description_of_article }}</div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Gross weight (optional)</label>
              <input v-model="form.gross_weight" type="text"
                placeholder="e.g., 50.5 kg / TEKU1234567"
                class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-indigo-500 focus:border-indigo-500" />
              <div v-if="form.errors.gross_weight" class="mt-1 text-red-500 text-xs">{{ form.errors.gross_weight }}</div>
            </div>
          </div>

          <!-- Actions -->
          <div class="pt-4 border-t border-gray-200 flex justify-end space-x-3">
            <button type="button" @click="closeModal"
              class="px-4 py-2 text-sm bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600">Close</button>
            <button type="submit" :disabled="form.processing"
              class="px-4 py-2 text-sm bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 flex items-center space-x-2 disabled:bg-green-400">
              <Save class="w-4 h-4" />
              <span v-if="form.processing">Saving...</span>
              <span v-else>Save Article</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
