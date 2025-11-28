<script setup>
const props = defineProps({
  application: {
    type: Object,
    required: true
  }
})
</script>

<template>
  <div class="bg-white rounded-xl shadow p-6 space-y-8">

    <!-- Header -->
    <div class="border-b pb-4">
        <h3 class="text-lg bg-gray-500 text-white text-center font-semibold mb-2">Application Basic Information</h3>
      <h2 class="text-xl font-bold text-gray-900">
        {{ props.application.form_title }}
      </h2>
      <p class="text-sm text-gray-600">
        Form No: <span class="font-semibold">{{ props.application.form_number }}</span>
      </p>
      <p class="text-sm text-gray-600">
        Status: 
        <span class="font-semibold" :class="props.application.status === 'Pending' ? 'text-yellow-600' : 'text-green-600'">
          {{ props.application.status }}
        </span>
      </p>
    </div>

    <!-- Article Details Table -->
    <div>
      <h3 class="text-lg  bg-gray-500 text-white text-center font-semibold mb-2">Article Details</h3>
      <div class="overflow-x-auto">
        <table class="min-w-full table-auto border border-gray-200 rounded-lg">
          <thead class="bg-gray-100">
            <tr class="text-left">
              <th class="px-4 py-2 border-b">Marks & Number</th>
              <th class="px-4 py-2 border-b">Quantity</th>
              <th class="px-4 py-2 border-b">Description</th>
              <th class="px-4 py-2 border-b">Gross Weight</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="detail in props.application.article_details" :key="detail.id" class="bg-white hover:bg-gray-50">
              <td class="px-4 py-2 border-b">{{ detail.marks_and_number }}</td>
              <td class="px-4 py-2 border-b">{{ detail.qty }}</td>
              <td class="px-4 py-2 border-b">{{ detail.detailed_description_of_article }}</td>
              <td class="px-4 py-2 border-b">{{ detail.gross_weight }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Selected Option -->
    <div v-if="props.application.options?.length">
      <h3 class="text-lg  bg-gray-500 text-white text-center font-semibold mb-2">Selected Options and Validity Period</h3>
      <div class="border rounded-lg p-4 bg-gray-50">
        <p><strong>Option:</strong> {{ props.application.options[0].name }}</p>
        <p><strong>Price:</strong> ₱{{ props.application.options[0].price }}</p>
        <p><strong>Validity:</strong> {{ props.application.options[0].validity }}</p>
      </div>
    </div>

    <!-- Uploads -->
    <!-- Uploads -->
<div v-if="props.application.uploads?.length">
  <h3 class="text-lg bg-gray-500 text-white text-center font-semibold mb-2">Supporting Document'/s</h3>
  <div v-for="file in props.application.uploads" :key="file.id" class="flex items-center gap-4 border rounded-lg p-3 bg-gray-50">
    
    <a :href="`/${file.file_path}`" target="_blank" class="flex items-center gap-4">
      <img 
        v-if="file.file_type.includes('image')" 
        :src="`/${file.file_path}`"
        alt="Upload Preview"
        class="w-20 h-20 object-cover rounded-lg border"
      />
      <div>
        <p class="font-medium underline text-blue-600 hover:text-blue-800">{{ file.file_name }}</p>
        <p class="text-xs text-gray-500">{{ (file.file_size / 1024).toFixed(1) }} KB</p>
      </div>
    </a>

  </div>

    </div>

    <!-- Approval -->
    <div>
      <h3 class="text-lg  bg-gray-500 text-white text-center font-semibold mb-2">Approval Info</h3>
      <div class="border rounded-lg p-4 bg-gray-50">
        <p><strong>Status:</strong> {{ props.application.approval.status }}</p>
        <p><strong>Group ID:</strong> {{ props.application.approval.approver_group_id }}</p>
        <p v-if="props.application.approval.remark"><strong>Remark:</strong> {{ props.application.approval.remark }}</p>
      </div>
    </div>

  </div>
</template>
