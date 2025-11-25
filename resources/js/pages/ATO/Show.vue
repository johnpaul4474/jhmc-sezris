<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { Image } from 'lucide-vue-next';

// Data passed from controller via Inertia
const props = defineProps({
  ATOapplication: {
    type: Object,
    required: true,
  },
})

//const app = computed(() => props.application)
</script>

<template>
  <div class="p-6 space-y-6">
    
    <h2 class="text-2xl font-bold text-center">ATO Application Details</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div class="border p-4 rounded-lg bg-white shadow">
        <p class="text-gray-600 text-sm">Application Type</p>
        <p class="font-semibold">{{ props.ATOapplication.application_type }}</p>
      </div>

      <div class="border p-4 rounded-lg bg-white shadow">
        <p class="text-gray-600 text-sm">Business Structure</p>
        <p class="font-semibold">{{ props.ATOapplication.business_structure }}</p>
      </div>

      <div class="border p-4 rounded-lg bg-white shadow">
        <p class="text-gray-600 text-sm">Trade Name</p>
        <p class="font-semibold">{{ props.ATOapplication.Trades_name }}</p>
      </div>

      <div class="border p-4 rounded-lg bg-white shadow">
        <p class="text-gray-600 text-sm">Parent Company</p>
        <p class="font-semibold">{{ props.ATOapplication.parent_company }}</p>
      </div>

      <div class="border p-4 rounded-lg bg-white shadow">
        <p class="text-gray-600 text-sm">Taxpayer Name</p>
        <p class="font-semibold">{{ props.ATOapplication.taxpayer_name }}</p>
      </div>

      <div class="border p-4 rounded-lg bg-white shadow">
        <p class="text-gray-600 text-sm">TIN</p>
        <p class="font-semibold">{{ props.ATOapplication.TIN }}</p>
      </div>

      <div class="border p-4 rounded-lg bg-white shadow">
        <p class="text-gray-600 text-sm">Primary Line</p>
        <p class="font-semibold">{{ props.ATOapplication.PrimaryLine }}</p>
      </div>

      <div class="border p-4 rounded-lg bg-white shadow">
        <p class="text-gray-600 text-sm">Secondary Line</p>
        <p class="font-semibold">{{ props.ATOapplication.SecondaryLine }}</p>
      </div>

      <div class="border p-4 rounded-lg bg-white shadow md:col-span-2">
        <p class="text-gray-600 text-sm">Nature of Contract</p>
        <p class="font-semibold">{{ props.ATOapplication.nature_of_contract }}</p>
      </div>

      <div class="border p-4 rounded-lg bg-white shadow">
        <p class="text-gray-600 text-sm">PCIC Primary Line</p>
        <p class="font-semibold">{{ props.ATOapplication.pcic_primary_line }}</p>
      </div>

      <div class="border p-4 rounded-lg bg-white shadow">
        <p class="text-gray-600 text-sm">PCIC Secondary Line</p>
        <p class="font-semibold">{{ props.ATOapplication.pcic_secondary_line }}</p>
      </div>

      <div class="border p-4 rounded-lg bg-white shadow">
        <p class="text-gray-600 text-sm">PCIC Primary Email</p>
        <p class="font-semibold">{{ props.ATOapplication.pcic_Primary_email }}</p>

      <div class="border p-4 rounded-lg bg-white shadow">
        <p class="text-gray-600 text-sm">PCIC Secondary Email</p>
        <p class="font-semibold">{{ props.ATOapplication.pcic_Secondary_email }}</p>
      </div>

      <div class="border p-4 rounded-lg bg-white shadow">
        <p class="text-gray-600 text-sm">PCIC Location</p>
        <p class="font-semibold">{{ props.ATOapplication.pcic_location }}</p>
      </div>

      <div class="border p-4 rounded-lg bg-white shadow md:col-span-2">
        <p class="text-gray-600 text-sm">PCIC Office Address</p>
        <p class="font-semibold">{{ props.ATOapplication.pcic_office_address }}</p>
      </div>

      <div class="border p-4 rounded-lg bg-white shadow">
        <p class="text-gray-600 text-sm">Contact Person</p>
        <p class="font-semibold">{{ props.ATOapplication.pcic_contact_person }}</p>
      </div>

      <div class="border p-4 rounded-lg bg-white shadow">
        <p class="text-gray-600 text-sm">Contact Number</p>
        <p class="font-semibold">{{ props.ATOapplication.pcic_contact_number }}</p>
      </div>

      <div class="border p-4 rounded-lg bg-white shadow md:col-span-2">
        <p class="text-gray-600 text-sm">Application Date</p>
        <p class="font-semibold">{{ props.ATOapplication.application_date }}</p>
      </div>

      <div class="border p-4 rounded-lg bg-white shadow md:col-span-2">
        <p class="text-gray-600 text-sm">Uploaded Supporting Documents Files</p>
        <div>
           
          <template v-if="props.ATOapplication.uploads && props.ATOapplication.uploads.length">
            <ul class="list-disc pl-5">
              <li v-for="(file, index) in props.ATOapplication.uploads" :key="index">
            
                <a :href="file.file_url" class="text-blue-500 underline" target="_blank"><Image />{{ file.file_name }}</a>
              </li>
            </ul>
          </template> 
          <p v-else class="text-gray-400 italic">No files uploaded</p>
        </div>
      </div>
    </div>
  </div>
  </div>
</template>