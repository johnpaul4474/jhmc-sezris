<script setup lang="ts">
import { ref, computed } from "vue";
import { useForm, Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Dialog, DialogPanel, TransitionRoot } from "@headlessui/vue";
import { type BreadcrumbItem } from '@/types';

const props = defineProps<{
  user: any;
  users: any;
  registered_locators: Array<{
    id: number;
    name: string;
    profile: {
      locator_name: string;
      owner_name: string;
      owner_email: string;
      representative_name?: string;
      representative_contact?: string;
      representative_email?: string;
      official_email_gmail?: string;
      applied_date?: string;
      applicant_name?: string;
      applicant_signature?: string;
      type_of_industry?: string;
      address_within_jhze?: string;
      company_mobile_number?: string;
      company_email?: string;
      category?: string;
    };
  }>;
}>();

interface Locator {
  id: number;
  locator: string;
  owner: string;
  email: string;
  category: string;
  status: boolean;
}

// --- STATE ---
const search = ref("");
const sortKey = ref<keyof Locator>("locator");
const sortAsc = ref(true);
const currentPage = ref(1);
const perPage = ref(5);
const isModalOpen = ref(false);
const isModalOpen2 = ref(false);
const selectedLocator = ref<Locator | null>(null);

// --- LOCATORS ARRAY BUILT FROM REGISTERED_LOCATORS ---
const locators = ref<Locator[]>(
  props.registered_locators.map(u => ({
    id: u.id,
    locator: u.profile.locator_name,
    owner: u.profile.owner_name,
    email: u.profile.owner_email,
    category: u.profile.category ?? '',
    status: true
  }))
);

// --- FORM STATE ---
const form = useForm({
  user_id: "",
  locator_name: "",
  owner_name: "",
  owner_contact_number: "",
  owner_email: "",
  representative_name: "",
  representative_contact: "",
  representative_email: "",
  official_email_gmail: "",
  applied_date: "",
  applicant_name: "",
  applicant_signature: "",
  type_of_industry: "",
  address_within_jhze: "",
  company_mobile_number: "",
  company_email: "",
  category: "",
  classification: ""
});

// --- FILTERED AND PAGINATED DATA ---
const filtered = computed(() => {
  return locators.value
    .filter(l =>
      Object.values(l).some(v =>
        String(v).toLowerCase().includes(search.value.toLowerCase())
      )
    )
    .sort((a, b) => {
      const aVal = a[sortKey.value];
      const bVal = b[sortKey.value];
      if (aVal < bVal) return sortAsc.value ? -1 : 1;
      if (aVal > bVal) return sortAsc.value ? 1 : -1;
      return 0;
    });
});

const paginated = computed(() => {
  const start = (currentPage.value - 1) * perPage.value;
  return filtered.value.slice(start, start + perPage.value);
});

const totalPages = computed(() => Math.ceil(filtered.value.length / perPage.value));

// --- FUNCTIONS ---
function toggleSort(key: keyof Locator) {
  if (sortKey.value === key) {
    sortAsc.value = !sortAsc.value;
  } else {
    sortKey.value = key;
    sortAsc.value = true;
  }
}

function toggleStatus(item: Locator) {
  item.status = !item.status;
}

function openModal() {
  isModalOpen.value = true;
}

function saveLocator() {
  form.post('/bdd/locator/saveProfile', {
    onSuccess: () => {
      // Close modal and reset form after success
      isModalOpen.value = false;
      form.reset();
    },
    onError: (errors) => {
      console.log('Validation errors:', errors);
    }
  });

  // Immediately update frontend
  const id = locators.value.length + 1;
  locators.value.push({
    id,
    locator: form.locator_name,
    owner: form.owner_name,
    email: form.owner_email,
    category: form.category,
    status: true
  });

  isModalOpen.value = false;
  form.reset();
}

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'BDD Dashboard', href: "/" },
];
function handleView(locator: Locator) {
    selectedLocator.value = locator;
    isModalOpen2.value = true;
}
function closeModal2() {
    isModalOpen2.value = false;
    selectedLocator.value = null;
}
</script>

<template>
  <Head title="BDD Dashboard" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6 space-y-6">

      <!-- Search + Add Button -->
      <div class="flex items-center justify-between mb-4">
        <input v-model="search" type="text" placeholder="Search..."
          class="border rounded-lg px-4 py-2 w-1/3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
        <button @click="openModal"
          class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700 transition">
          + Add Locator
        </button>
      </div>

      <!-- Table -->
      <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full text-sm">
          <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
            <tr>
              <th @click="toggleSort('locator')" class="cursor-pointer px-4 py-3 text-left">Locator</th>
              <th @click="toggleSort('owner')" class="cursor-pointer px-4 py-3 text-left">Owner</th>
              <th @click="toggleSort('email')" class="cursor-pointer px-4 py-3 text-left">Email</th>
              <th @click="toggleSort('category')" class="cursor-pointer px-4 py-3 text-left">Category</th>
              <th class="px-4 py-3 text-center">Status</th>
              <th class="px-4 py-3 text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in paginated" :key="item.id" class="border-t hover:bg-gray-50 transition">
              <td class="px-4 py-2">{{ item.locator }}</td>
              <td class="px-4 py-2">{{ item.owner }}</td>
              <td class="px-4 py-2">{{ item.email }}</td>
              <td class="px-4 py-2">{{ item.category }}</td>
              <td class="px-4 py-2 text-center">
                <label class="inline-flex items-center cursor-pointer">
                  <input type="checkbox" class="sr-only peer" v-model="item.status" @change="toggleStatus(item)" />
                  <div class="w-10 h-5 bg-gray-300 peer-checked:bg-green-500 rounded-full relative
                      after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:w-4 after:h-4
                      after:bg-white after:rounded-full after:transition-all peer-checked:after:translate-x-5"></div>
                </label>
              </td>
              <td class="px-4 py-2 text-center">
                <button 
                @click="handleView(item)"
                class="bg-indigo-600 text-white px-3 py-1 rounded-md shadow hover:bg-indigo-700 transition">
                  View
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="flex justify-between items-center mt-4">
        <p class="text-sm text-gray-600">Page {{ currentPage }} of {{ totalPages }}</p>
        <div class="space-x-2">
          <button @click="currentPage > 1 && currentPage--"
            class="px-4 py-1 border rounded-md bg-white shadow-sm hover:bg-gray-100">Prev</button>
          <button @click="currentPage < totalPages && currentPage++"
            class="px-4 py-1 border rounded-md bg-white shadow-sm hover:bg-gray-100">Next</button>
        </div>
      </div>

      <!-- Modal -->
      <TransitionRoot appear :show="isModalOpen" as="template">
        <Dialog as="div" class="relative z-10" @close="isModalOpen = false">
          <div class="fixed inset-0 bg-black/40" />
          <div class="fixed inset-0 flex items-center justify-center p-4">
            <DialogPanel class="bg-white rounded-xl shadow-2xl w-full max-w-4xl p-6 overflow-y-auto max-h-[90vh]">
              <h2 class="text-2xl font-bold mb-6 text-gray-800">Add New Locator</h2>
              <div class="grid grid-cols-2 gap-4 text-sm">
                <input v-model="form.locator_name" placeholder="Name of Locator" class="border p-2 rounded-lg shadow-sm" />
                <input v-model="form.owner_name" placeholder="Name of Owner" class="border p-2 rounded-lg shadow-sm" />
                <input v-model="form.owner_contact_number" placeholder="Owner Contact Number" class="border p-2 rounded-lg shadow-sm" />
                <input v-model="form.owner_email" placeholder="Owner Email Address" class="border p-2 rounded-lg shadow-sm" />
                <input v-model="form.representative_name" placeholder="Representative Name" class="border p-2 rounded-lg shadow-sm" />
                <input v-model="form.representative_contact" placeholder="Representative Contact" class="border p-2 rounded-lg shadow-sm" />
                <input v-model="form.representative_email" placeholder="Representative Email Address" class="border p-2 rounded-lg shadow-sm" />
                <input v-model="form.official_email_gmail" placeholder="Official Email Address (Gmail)" class="border p-2 rounded-lg shadow-sm" />
                <input type="date" v-model="form.applied_date" class="border p-2 rounded-lg shadow-sm" />
                <input v-model="form.applicant_name" placeholder="Applicant's Name" class="border p-2 rounded-lg shadow-sm" />
                <input v-model="form.applicant_signature" placeholder="Applicant's Signature" class="border p-2 rounded-lg shadow-sm" />
                <input v-model="form.type_of_industry" placeholder="Type of Industry" class="border p-2 rounded-lg shadow-sm" />
                <input v-model="form.address_within_jhze" placeholder="Address within JHZE" class="border p-2 rounded-lg shadow-sm" />
                <input v-model="form.company_mobile_number" placeholder="Company Mobile Number" class="border p-2 rounded-lg shadow-sm" />
                <input v-model="form.company_email" placeholder="Company Email Address" class="border p-2 rounded-lg shadow-sm" />
                <select v-model="form.category" class="border p-2 rounded-lg shadow-sm">
                  <option disabled value="">-- Select Category --</option>
                  <optgroup label="Business Enterprise">
                    <option>Primary Business</option>
                    <option>Secondary Business</option>
                  </optgroup>
                  <optgroup label="Service Providers and Suppliers">
                    <option>Regular Service Provider/Supplier</option>
                    <option>Seasonal Service Provider</option>
                  </optgroup>
                  <optgroup label="Commercial Event Operators">
                    <option>Commercial Event Organizers/Promoters</option>
                    <option>Commercial Event Concessionaries</option>
                  </optgroup>
                  <optgroup label="Authorized Entrepreneurs">
                    <option>Vendors</option>
                    <option>Accommodation Providers</option>
                    <option>Trade Fair Organizers</option>
                  </optgroup>
                  <optgroup label="Residents">
                    <option>Residents WITHOUT Business</option>
                    <option>Residents WITH Business</option>
                  </optgroup>
                </select>
              </div>
              <div class="flex justify-end space-x-3 mt-8">
                <button @click="isModalOpen = false" class="px-5 py-2 border rounded-lg bg-gray-100 hover:bg-gray-200">Cancel</button>
                <button @click="saveLocator" class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700">Save</button>
              </div>
            </DialogPanel>
          </div>
        </Dialog>
      </TransitionRoot>
      <!--modal View-->
      <TransitionRoot appear :show="isModalOpen2" as="template">
    <Dialog as="div" class="relative z-10" @close="closeModal2">
      <div class="fixed inset-0 bg-black/40" />
      <div class="fixed inset-0 flex items-center justify-center p-4">
        <DialogPanel class="bg-white rounded-xl shadow-2xl w-full max-w-2xl p-6">
          <h2 class="text-2xl font-bold mb-4">Locator Details</h2>

          <div v-if="selectedLocator">
            <p><strong>Locator Name:</strong> {{ selectedLocator.locator }}</p>
            <p><strong>Owner Name:</strong> {{ selectedLocator.owner }}</p>
            <p><strong>Owner Email:</strong> {{ selectedLocator.email }}</p>
            <p><strong>Category:</strong> {{ selectedLocator.category }}</p>
            <p><strong>Status:</strong> {{ selectedLocator.status ? 'Active' : 'Inactive' }}</p>
          </div>

          <div class="mt-6 text-right">
            <button 
              @click="closeModal2"
              class="px-5 py-2 border rounded-lg bg-gray-100 hover:bg-gray-200">
              Close
            </button>
          </div>
        </DialogPanel>
      </div>
    </Dialog>
  </TransitionRoot>

    </div>
  </AppLayout>
</template>
