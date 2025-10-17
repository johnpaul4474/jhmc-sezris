<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
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

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
     <div class="grid gap-6 md:grid-cols-3 px-6 py-6">
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
      <p class="mt-6 text-4xl font-bold text-black-500">
        {{ stats.activeUsers }}
      </p>
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
      <ul class="mt-6 space-y-3 text-sm text-gray-700 dark:text-gray-300">
        <li class="flex justify-between">
          <span>New</span>
          <span class="font-semibold text-green-600">
            {{ stats.sezadRequests.new }}
          </span>
        </li>
        <li class="flex justify-between">
          <span>Pending</span>
          <span class="font-semibold text-yellow-600">
            {{ stats.sezadRequests.pending }}
          </span>
        </li>
        <li class="flex justify-between">
          <span>Declined</span>
          <span class="font-semibold text-red-600">
            {{ stats.sezadRequests.declined }}
          </span>
        </li>
      </ul>
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
      <p class="mt-6 text-4xl font-bold text-black-600">
        {{ stats.bddCreatedUsers }}
      </p>
    </div>
  </div>
    </AppLayout>
</template>
