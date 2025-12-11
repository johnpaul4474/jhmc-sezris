<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import locatorAppSidebarLayout from '@/layouts/locator/LocatorAppSidebarLayout.vue';
import { locator } from '@/routes';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage, router } from '@inertiajs/vue3';
import { ref } from "vue";
import { Check } from "lucide-vue-next"; // icon for approve button

const page = usePage();

// Breadcrumbs
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

// Decode helper
const decodeLocator = (value: string) => {
  try {
    return JSON.parse(value);
  } catch {
    return [];
  }
};

// APPROVE ACTION
const approveVendor = (item : any) => {
    router.post(`/locator/vendor/${item.id}/approve`, {}, {
    onSuccess: (page) => {
      console.log(page.props.flash.success);
    }
  });
};
</script>

<template>
  <Head title="Locator Dashboard" />

  <locatorAppSidebarLayout :breadcrumbs="breadcrumbs">

    <h1 class="text-2xl font-bold mb-4">Vendor Requests</h1>
    
    <div class="mt-6 overflow-x-auto bg-white rounded-lg shadow">
      <table class="min-w-full text-sm border">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-3 py-2 border">ID</th>
            <th class="px-3 py-2 border">Email</th>
            <th class="px-3 py-2 border">Name</th>
            <th class="px-3 py-2 border">Business Type</th>
            <th class="px-3 py-2 border">Status</th>
            <th class="px-3 py-2 border">Action</th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="(item, index) in props.vendor"
            :key="item.id"
            class="hover:bg-gray-50"
          >
            <td class="px-3 py-2 border">{{ index+1 }}</td>
            <td class="px-3 py-2 border">{{ item.email }}</td>
            <td class="px-3 py-2 border">{{ item.name }}</td>
            <td class="px-3 py-2 border">{{ item.business_type }}</td>

            <!-- Decode JSON locator -->
            <!-- <td class="px-3 py-2 border">
              <div v-for="loc in decodeLocator(item.locator)" :key="loc">
                {{ loc }}
              </div>
            </td> -->

            <td class="px-3 py-2 border capitalize">{{ item.status }}</td>

            <!-- ACTION BUTTON -->
            <td class="px-3 py-2 border">
              <button
                @click="approveVendor(item)"
                :disabled="item.status === 'approved'"
                class="flex items-center gap-1 bg-green-600 text-white px-3 py-1 rounded shadow hover:bg-green-700 transition disabled:bg-gray-300 disabled:cursor-not-allowed"
              >
                <Check class="w-4 h-4 rounded-b-full" />
               
              </button>
            </td>

          </tr>
        </tbody>
      </table>
    </div>

  </locatorAppSidebarLayout>
</template>
