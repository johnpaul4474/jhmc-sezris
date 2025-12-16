<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import locatorAppSidebarLayout from '@/layouts/locator/LocatorAppSidebarLayout.vue'
import { locator } from '@/routes'
import { type BreadcrumbItem } from '@/types'
import { Head, usePage, router } from '@inertiajs/vue3'
import { computed } from 'vue'
import { Check } from 'lucide-vue-next'

/* ===============================
   PAGE
================================ */

const page = usePage()

/* ===============================
   FLASH MESSAGES (IMPORTANT)
================================ */

 const errorMessage = computed(() => page.props.flash.error ?? null)
 //const successMessage = computed(() => page.props.value.flash?.success ?? null)

/* ===============================
   PROPS
================================ */

const props = defineProps<{
  vendor: any[]
}>()

/* ===============================
   BREADCRUMBS
================================ */

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Locator',
    href: locator.url(),
  },
]

/* ===============================
   COMPUTED
================================ */

const isEmpty = computed(() => props.vendor.length === 0)

/* ===============================
   ACTIONS
================================ */

const approveVendor = (item: any) => {
  router.post(
    `/locator/vendor/${item.id}/approve`,
    {},
    {
      preserveScroll: true,
    }
  )
}
</script>

<template>
  <Head title="Vendor Requests" />

  <locatorAppSidebarLayout :breadcrumbs="breadcrumbs">
    <h1 class="text-2xl font-bold mb-4 text-center">Vendor Requests</h1>

    <!-- FLASH ERROR -->
    <div
      v-if="errorMessage"
      class="mb-4 rounded text-center bg-red-100 border border-red-400 text-red-700 px-4 py-2"
    >
     {{ errorMessage }}
    </div>

    <!-- FLASH SUCCESS -->
    <div
      v-if="successMessage"
      class="mb-4 rounded bg-green-100 border border-green-400 text-green-700 px-4 py-2"
    >
      {{ successMessage }}
    </div>

    <!-- TABLE -->
    <div class="mt-6 overflow-x-auto bg-white rounded-lg shadow">
      <table class="min-w-full text-sm border">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-3 py-2 border">#</th>
            <th class="px-3 py-2 border">Email</th>
            <th class="px-3 py-2 border">Name</th>
            <th class="px-3 py-2 border">Business Type</th>
            <th class="px-3 py-2 border">Status</th>
            <th class="px-3 py-2 border">Action</th>
          </tr>
        </thead>

        <tbody>
          <!-- EMPTY STATE -->
          <tr v-if="isEmpty">
            <td colspan="6" class="text-center py-6 text-gray-500">
              No vendor requests found.
            </td>
          </tr>

          <!-- DATA ROWS -->
          <tr
            v-else
            v-for="(item, index) in props.vendor"
            :key="item.id"
            class="hover:bg-gray-50"
          >
            <td class="px-3 py-2 border">{{ index + 1 }}</td>
            <td class="px-3 py-2 border">{{ item.email }}</td>
            <td class="px-3 py-2 border">{{ item.name }}</td>
            <td class="px-3 py-2 border">{{ item.business_type }}</td>
            <td class="px-3 py-2 border capitalize">
              {{ item.status }}
            </td>

            <td class="px-3 py-2 border">
              <button
                @click="approveVendor(item)"
                :disabled="item.status === 'approved'"
                class="flex items-center gap-1 bg-green-600 text-white px-3 py-1 rounded shadow
                       hover:bg-green-700 transition
                       disabled:bg-gray-300 disabled:cursor-not-allowed"
              >
                <Check class="w-4 h-4" />
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </locatorAppSidebarLayout>
</template>
