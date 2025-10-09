<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import locatorAppSidebarLayout from '@/layouts/locator/LocatorAppSidebarLayout.vue';
import locators from '@/routes/locators';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref } from "vue";
import { Users, FileText, UserPlus } from "lucide-vue-next"; // Lucide icons
import applications from '@/routes/applications';

// ✅ Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Locator',
    href: locators.index.url(),
  },
];

// ❌ Your `defineProps` syntax is invalid.
// ✅ Correct `defineProps` object:
const props = defineProps({
  applications: {
    type: [Array, Object], // Array if not paginated, Object if paginated
    default: () => [],
  },
});

// ✅ Temporary stats object (mock data)
const stats = ref({
  activeUsers: 124,
  sezadRequests: {
    new: 12,
    pending: 8,
    declined: 3,
  },
  bddCreatedUsers: 56,
});
</script>

<template>
  <Head title="Locator Dashboard" />

  <locatorAppSidebarLayout :breadcrumbs="breadcrumbs">
    <div class="grid gap-6 md:grid-cols-4 px-4 py-4">
      <!-- Apply New -->
       
      <div
        class="flex flex-col items-center rounded-2xl bg-blue-500 p-4 shadow-lg transition hover:shadow-xl dark:bg-[#1b1b18]"
      >
        <div class="flex items-center gap-3">
          <div class="rounded-full bg-blue-100 p-3 dark:bg-blue-900/40">
            <Users class="w-6 h-6 text-[#0F75BC]" />
          </div>
          <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
            Apply New
          </h3>
        </div>
      </div>

      <!-- Active Users -->
      <div
        class="flex flex-col items-center rounded-2xl bg-blue-500 p-6 shadow-lg transition hover:shadow-xl dark:bg-[#1b1b18]"
      >
        <div class="flex items-center gap-3">
          <div class="rounded-full bg-blue-100 p-3 dark:bg-blue-900/40">
            <Users class="w-6 h-6 text-[#0F75BC]" />
          </div>
          <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
            Active Users
          </h3>
        </div>
      </div>

      <!-- SEZAD Requests -->
      <div
        class="flex flex-col rounded-2xl bg-green-300 p-6 shadow-lg transition hover:shadow-xl dark:bg-[#1b1b18]"
      >
        <div class="flex items-center gap-3">
          <div class="rounded-full bg-purple-100 p-3 dark:bg-purple-900/40">
            <FileText class="w-6 h-6 text-purple-600" />
          </div>
          <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
            SEZAD Requests
          </h3>
        </div>
      </div>

      <!-- BDD Created Users -->
      <div
        class="flex flex-col items-center rounded-2xl bg-yellow-500 p-6 shadow-lg transition hover:shadow-xl dark:bg-[#1b1b18]"
      >
        <div class="flex items-center gap-3">
          <div class="rounded-full bg-green-100 p-3 dark:bg-black-900/40">
            <UserPlus class="w-6 h-6 text-green-600" />
          </div>
          <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
            BDD Created Users
          </h3>
        </div>
      </div>
    </div>
    <!---table-->
    <div v-if="props.applications && props.applications.length" class="mt-6 overflow-x-auto">
  <table class="min-w-full divide-y divide-gray-200 border border-gray-300 rounded-lg">
    <thead class="bg-gray-100">
      <tr>
        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">#</th>
        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Form Title</th>
        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Created At</th>
        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Control Number</th>
        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Form Number</th>
        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-gray-200 bg-white">
      <tr v-for="(app, index) in props.applications" :key="app.id" class="hover:bg-gray-50">
        <td class="px-4 py-2 text-sm text-gray-600">{{ index + 1 }}</td>
        <td class="px-4 py-2 text-sm text-gray-800">{{ app.form_title }}</td>
        <td class="px-4 py-2 text-sm text-gray-600">{{ app.created_at }}</td>
        <td class="px-4 py-2 text-sm text-gray-600">{{ app.control_number }}</td>
        <td class="px-4 py-2 text-sm text-gray-600">{{ app.form_number }}</td>
        <td class="px-4 py-2 text-sm">
          <button
            class="text-blue-600 hover:text-blue-800 font-medium"
            @click="console.log('View', app.id)"
          >
            View
          </button>
        </td>
      </tr>
    </tbody>
  </table>
</div>

<div v-else class="mt-6 text-gray-500 text-sm italic">
  null
</div>
<!--end table-->
  </locatorAppSidebarLayout>
</template>
