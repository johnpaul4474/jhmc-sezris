<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import locatorAppSidebarLayout from '@/layouts/locator/LocatorAppSidebarLayout.vue';
import { locator } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Check } from "lucide-vue-next";

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Locator',
    href: locator.url(),
  },
];

// Props
const props = defineProps({
  vendor: {
    type: [Array, Object],
    default: () => [],
  },
});

// Helper to decode JSON array stored as string
const decodeLocator = (value: string) => {
  try {
    return JSON.parse(value);
  } catch {
    return [];
  }
};

// Approve action
const approveVendor = (item: any) => {
  router.post(`/locator/vendor/${item.id}/approve`, {}, {
    onSuccess: (page) => {
      console.log(page.props.flash?.success);
    }
  });
};
</script>

<template>
  <Head title="Locator Dashboard" />

  <locatorAppSidebarLayout :breadcrumbs="breadcrumbs">

    <h1 class="text-2xl font-bold mb-4 text-center">My Vendor's</h1>

    <div class="mt-6 overflow-x-auto bg-white rounded-lg shadow">
      <table class="min-w-full text-sm border">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-3 py-2 border">ID</th>
            <th class="px-3 py-2 border">Email</th>
            <th class="px-3 py-2 border">Name</th>
            <th class="px-3 py-2 border">Business Type</th>
            <th class="px-3 py-2 border">Status</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="(item,index) in props.vendor" :key="item.id" class="hover:bg-gray-50">
            <td class="px-3 py-2 border text-center">{{ index+1 }}</td>
            <td class="px-3 py-2 border text-center">{{ item.email }}</td>
            <td class="px-3 py-2 border text-center">{{ item.name }}</td>
            <td class="px-3 py-2 border text-center">{{ item.business_type }}</td>

            <!-- Decoded locators -->
            <!-- <td class="px-3 py-2 border">
              <div v-for="loc in decodeLocator(item.locator)" :key="loc">
                {{ loc }}
              </div>
            </td> -->

            <td class="px-3 py-2 border text-center capitalize">{{ item.status }}</td>
          </tr>
        </tbody>
      </table>
    </div>

  </locatorAppSidebarLayout>
</template>
