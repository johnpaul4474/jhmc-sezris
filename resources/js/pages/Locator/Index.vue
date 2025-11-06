<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import locatorAppSidebarLayout from '@/layouts/locator/LocatorAppSidebarLayout.vue';
import locators from '@/routes/locators';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head , usePage } from '@inertiajs/vue3';
import { ref } from "vue";
import { Users, FileText, UserPlus } from "lucide-vue-next"; // Lucide icons
import applications from '@/routes/applications';
import ApplicationTable from '@/components/common/ApplicationTable.vue';
import TopCard from '@/components/common/TopCard.vue';
import TimeLine from '@/components/locator/TimeLine.vue';
const page = usePage();
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
const msg= "hello123";
</script>

<template>
  <Head title="Locator Dashboard" />
    
  <locatorAppSidebarLayout :breadcrumbs="breadcrumbs">
    
      <!-- Apply New -->
        {{ page.props.auth.user }}
       <TopCard />
      
    <!---table-->
     <div class="mt-6 overflow-x-auto">
 
  <ApplicationTable
      :applications="props.applications"
      @view="handleView"
      @edit="handleEdit"
      @delete="handleDelete"
    />
</div>

<!--end table-->
  </locatorAppSidebarLayout>
</template>
