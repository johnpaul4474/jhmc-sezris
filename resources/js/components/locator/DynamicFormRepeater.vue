<script setup lang="ts">
import { ref, watch } from 'vue'
import axios from 'axios'
import { Plus, X, Trash2, Save, Box } from 'lucide-vue-next'

// --- Props & Emits ---
const props = defineProps({
  modelValue: { type: Array, default: () => [] },
  title: { type: String, default: 'Submit New Shipping Article' },
  formId: { type: [String, Number], required: true }
})

const emit = defineEmits(['update:modelValue'])

// --- State ---
const savedArticles = ref([...props.modelValue])
const isOpen = ref(false)
const form = ref({
  application_form_id: props.formId,
  marks_and_number: '',
  qty: null,
  detailed_description_of_article: '',
  gross_weight: ''
})

// --- Modal Handlers ---
const openModal = () => {
  form.value = { application_form_id: props.formId, marks_and_number: '', qty: null, detailed_description_of_article: '', gross_weight: '' }
  isOpen.value = true
}
const closeModal = () => { isOpen.value = false }

// --- Submit Article ---
const submitArticle = async () => {
  try {
    const res = await axios.post('/loctr/articles', form.value)
    const newArticle = res.data.article
    if (newArticle) {
      savedArticles.value.push(newArticle)
      emit('update:modelValue', savedArticles.value)
    }
    closeModal()
  } catch (err) {
    console.error(err.response?.data?.errors || err)
    alert('Failed to save article')
  }
}

// --- Remove Article ---
const removeArticle = async (id: number) => {
  const index = savedArticles.value.findIndex(a => a.id === id)
  if (index === -1) return

  const [deletedArticle] = savedArticles.value.splice(index, 1)
  emit('update:modelValue', savedArticles.value)

  try {
    await axios.delete(`/loctr/articles/${id}`)
  } catch (err) {
    savedArticles.value.splice(index, 0, deletedArticle)
    emit('update:modelValue', savedArticles.value)
    alert('Failed to delete article')
  }
}

// --- Sync with parent ---
watch(() => props.modelValue, (newVal) => {
  if (JSON.stringify(newVal) !== JSON.stringify(savedArticles.value)) {
    savedArticles.value = [...newVal]
  }
}, { deep: true })
</script>

<template>
  <div class="space-y-6">
    <!-- Add Button -->
    <button @click="openModal"
      class="flex items-center space-x-2 px-3 py-1 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 transition">
      <Plus class="w-5 h-5" />
      <span>list Article</span>
      </button>

    <!-- Article Table -->
    <div class="overflow-x-auto shadow-lg rounded-xl border border-gray-200">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-indigo-50">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-bold text-indigo-700 uppercase">#</th>
            <th class="px-4 py-3 text-left text-xs font-bold text-indigo-700 uppercase">Marks & Number</th>
            <th class="px-4 py-3 text-left text-xs font-bold text-indigo-700 uppercase">Quantity</th>
            <th class="px-4 py-3 text-left text-xs font-bold text-indigo-700 uppercase">Description</th>
            <th class="px-4 py-3 text-left text-xs font-bold text-indigo-700 uppercase">Weight</th>
            <th class="px-4 py-3 text-right text-xs font-bold text-indigo-700 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-if="savedArticles.length === 0">
            <td colspan="6" class="px-4 py-8 text-center text-gray-500 italic">No articles yet.</td>
          </tr>
          <tr v-for="(article, index) in savedArticles" :key="article.id" class="hover:bg-gray-50">
            <td class="px-4 py-3">{{ index + 1 }}</td>
            <td class="px-4 py-3 font-semibold text-blue-800">{{ article.marks_and_number }}</td>
            <td class="px-4 py-3">{{ article.qty }}</td>
            <td class="px-4 py-3 max-w-xs truncate">{{ article.detailed_description_of_article }}</td>
            <td class="px-4 py-3">{{ article.gross_weight || '—' }}</td>
            <td class="px-4 py-3 text-right">
              <button type="button" @click="removeArticle(article.id)" class="p-1 rounded-full text-red-500 hover:bg-red-100">
                <Trash2 class="w-4 h-4" />
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal -->
    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70" @click.self="closeModal">
      <div class="relative w-full max-w-xl max-h-[90vh] overflow-y-auto bg-white rounded-xl shadow-2xl" @click.stop>
        <form @submit.prevent="submitArticle" class="p-6 space-y-6">
          <div class="flex justify-between items-center border-b pb-4">
            <h2 class="text-2xl font-extrabold text-gray-900">{{ title }}</h2>
            <button type="button" @click="closeModal" class="p-2 rounded-full text-gray-500 hover:bg-gray-100">
              <X class="w-6 h-6" />
            </button>
          </div>

          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Marks and number</label>
              <input v-model="form.marks_and_number" type="text" required class="w-full border p-3 rounded-lg" placeholder="e.g., BOX-001 / Fragile" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
              <input v-model.number="form.qty" type="number" min="1" required class="w-full border p-3 rounded-lg" placeholder="e.g., 10" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
              <input v-model="form.detailed_description_of_article" type="text" required class="w-full border p-3 rounded-lg" placeholder="Full description" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Gross weight (optional)</label>
              <input v-model="form.gross_weight" type="text" class="w-full border p-3 rounded-lg" placeholder="e.g., 50.5 kg" />
            </div>
          </div>

          <div class="pt-4 border-t flex justify-end space-x-3">
            <button type="button" @click="closeModal" class="px-1 py-1 bg-gray-500 text-white rounded-lg">Close</button>
            <button type="submit" class="px-1 py-1 bg-green-600 text-white rounded-lg flex items-center space-x-2">
              <Save class="w-4 h-4" />
              <span>Save Article</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
