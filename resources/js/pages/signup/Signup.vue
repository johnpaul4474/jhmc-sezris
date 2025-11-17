<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-50 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-xl rounded-2xl w-full max-w-md p-6 sm:p-8">
      <h2 class="text-2xl sm:text-3xl font-bold text-center mb-6" style="color: #0f75bc;">
        Business Registration
      </h2>

      <form @submit.prevent="handleSubmit" class="space-y-5">
        <!-- Email -->
        <div>
          <label class="block text-gray-600 text-sm mb-1">Email</label>
          <input
            v-model="form.email"
            type="email"
            class="w-full border rounded-lg px-4 py-2 sm:py-3 focus:outline-none focus:ring-2 focus:ring-[#0f75bc]"
            placeholder="Enter your email"
            required
          />
        </div>

        <!-- Business Name -->
        <div>
          <label class="block text-gray-600 text-sm mb-1">Business Name</label>
          <input
            v-model="form.businessName"
            type="text"
            class="w-full border rounded-lg px-4 py-2 sm:py-3 focus:outline-none focus:ring-2 focus:ring-[#0f75bc]"
            placeholder="Enter business name"
            required
          />
        </div>

        <!-- Business Type -->
        <div>
          <label class="block text-gray-600 text-sm mb-1">Select Business Type</label>
          <select
            v-model="selectedBusinessType"
            class="w-full border rounded-lg px-4 py-2 sm:py-3 bg-white focus:outline-none focus:ring-2 focus:ring-[#0f75bc]"
          >
            <option value="">-- Choose Business Type --</option>
            <option v-for="type in businessTypes" :key="type.id" :value="type.id">
              {{ type.description }}
            </option>
          </select>
        </div>

        <!-- Locator Dropdown -->
        <div>
          <label class="block text-gray-600 text-sm mb-1">Select Locator</label>
          <select
            v-model="selectedLocator"
            @change="addLocator"
            class="w-full border rounded-lg px-4 py-2 sm:py-3 bg-white focus:outline-none focus:ring-2 focus:ring-[#3ab54a]"
          >
            <option value="">-- Choose a Locator --</option>
            <option v-for="locator in locators" :key="locator.id" :value="locator.name">
              {{ locator.name }} - {{ locator.email }}
            </option>
          </select>

          <!-- Selected Locators -->
          <div class="flex flex-wrap gap-2 mt-3">
            <div
              v-for="(item, index) in form.selectedLocators"
              :key="index"
              class="flex items-center gap-2 bg-[#e8f6ef] border border-[#3ab54a] text-[#0f75bc] px-3 py-1 rounded-full text-sm sm:text-base"
            >
              <span>{{ item }}</span>
              <button
                type="button"
                @click="removeLocator(index)"
                class="text-white bg-[#3ab54a] hover:bg-[#0f75bc] rounded-full w-5 h-5 sm:w-6 sm:h-6 flex items-center justify-center font-bold text-xs sm:text-sm"
              >
                ×
              </button>
            </div>
          </div>
        </div>

        <!-- Buttons -->
        <div class="flex flex-col sm:flex-row justify-between gap-3 mt-6">
          <button
            type="button"
            class="w-full sm:w-auto px-6 py-2 sm:py-3 rounded-lg border border-[#0f75bc] text-[#0f75bc] hover:bg-[#0f75bc] hover:text-white transition font-medium"
            @click="resetForm"
          >
            Cancel
          </button>

          <button
            type="submit"
            class="w-full sm:w-auto px-6 py-2 sm:py-3 rounded-lg bg-[#3ab54a] text-white hover:bg-[#0f75bc] transition font-medium"
          >
            Sign Up
          </button>
        </div>
      </form>

      <div class="mt-6 text-center sm:text-right text-sm">
        <span class="text-gray-600">Already signed up?</span>
        <a href="/login" class="font-semibold ml-1" style="color: #0f75bc;">Login here</a>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import axios from "axios";

type BusinessType = { id: number; description: string };
type Locator = { id: number; name: string; email: string };

const form = ref({
  email: "",
  businessName: "",
  selectedLocators: [] as string[],
});

const businessTypes = ref<BusinessType[]>([]);
const locators = ref<Locator[]>([]);
const selectedBusinessType = ref("");
const selectedLocator = ref("");

// Fetch Business Types
const fetchBusinessTypes = async () => {
  try {
    const res = await axios.get("/business-types");
    businessTypes.value = res.data;
  } catch (error) {
    console.error("Failed to load business types:", error);
  }
};

// Fetch Locators
const fetchLocators = async () => {
  try {
    const res = await axios.get("/locatorsSignUp");
    locators.value = res.data;
  } catch (error) {
    console.error("Failed to fetch locators:", error);
  }
};

onMounted(() => {
  fetchBusinessTypes();
  fetchLocators();
});

// Locator logic
const addLocator = () => {
  if (selectedLocator.value && !form.value.selectedLocators.includes(selectedLocator.value)) {
    form.value.selectedLocators.push(selectedLocator.value);
  }
  selectedLocator.value = "";
};

const removeLocator = (index: number) => {
  form.value.selectedLocators.splice(index, 1);
};

const handleSubmit = () => {
  console.log("Form submitted:", form.value);
  alert("Registration successful!");
};

const resetForm = () => {
  form.value = {
    email: "",
    businessName: "",
    selectedLocators: [],
  };
};
</script>
