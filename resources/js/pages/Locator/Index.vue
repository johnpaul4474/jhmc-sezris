<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import locatorAppSidebarLayout from '@/layouts/locator/locator-AppSidebarLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref } from "vue"
import { Users, FileText, UserPlus } from "lucide-vue-next" // Lucide icons
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];



// Example data (later replace with Inertia or API call)
const stats = ref({
    activeUsers: 124,
    sezadRequests: {
        new: 12,
        pending: 8,
        declined: 3,
    },
    bddCreatedUsers: 56,
})
</script>

<template>

    <Head title="Locator Dashboard" />

    <locatorAppSidebarLayout :breadcrumbs="breadcrumbs">
     <div class="grid gap-6 md:grid-cols-4 px-4 py-4">
        <div
      class="flex flex-col items-center rounded-2xl bg-blue-500 p-4 shadow-lg transition hover:shadow-xl dark:bg-[#1b1b18]"
    >
    
      <div class="flex items-center gap-3">
        <div class="rounded-full bg-blue-100 p-3 dark:bg-blue-900/40">
          <Users class="w-6 h-4 text-[#0F75BC]" />
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
    </locatorAppSidebarLayout>
</template>
