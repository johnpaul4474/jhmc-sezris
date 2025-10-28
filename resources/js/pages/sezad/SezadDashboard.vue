<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { ChevronDown } from 'lucide-vue-next'
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import { Link, usePage } from '@inertiajs/vue3'
import type { PageProps } from '@inertiajs/core'

interface UserDetails {
  permission_id: number
  role_id: number
  department_id: number
  division_id: number
  user_function_id: number
}

interface AuthUser {
  id: number
  name: string
  email: string
  email_verified_at: string | null
  created_at: string
  updated_at: string
  details?: UserDetails
}

interface CustomPageProps extends PageProps {
  auth: {
    user: AuthUser
  }
}

const page = usePage<CustomPageProps>()
const user = page.props.auth.user

// ✅ Helper function to check user details
const hasAccess = (user: AuthUser, functionId: number) => {
  const d = user?.details;
  return (
    d &&
    d.role_id === 2 &&
    d.permission_id === 2 &&
    d.department_id === 12 &&
    d.division_id === null &&
    d.user_function_id === functionId
  );
};

// ✅ Define role flags
const accreditationCenter = hasAccess(user, 1);
const customsClearanceCenter = hasAccess(user, 2);
const laborCenter = hasAccess(user, 3);
const oneStopActionCenter = hasAccess(user, 4);
const sezadManager = hasAccess(user, 5);

// ✅ Define full menu items
const fullNavItems = [
  {
    name: "One Stop Action Center",
    children: [
      { name: "Accreditation of Locators", href: "/accreditation" },
      { name: "Certificate of Registration", href: "/certificate" },
      { name: "Application for Import Permit", href: "/import-permit" },
      { name: "Declaration of Admission of Articles", href: "/declaration" },
      {
        name: "Clearance",
        children: [
          { name: "Gatepass Clearance", href: "/clearance/gatepass" },
          { name: "Bring-In Clearance", href: "/sezad/clearances/bring-in" },
          { name: "Bring-Out Clearance", href: "/clearance/bring-out" },
          { name: "Temporary Bring-Out Clearance", href: "/clearance/temp-bring-out" },
          { name: "Overtime Clearance", href: "/clearance/overtime" },
          { name: "Local Purchase Clearance", href: "/clearance/local-purchase" },
          { name: "Vehicle Entry/Exit Pass", href: "/clearance/vehicle" },
          { name: "Workforce Access Clearance", href: "/clearance/workforce" },
        ],
      },
    ],
  },
  {
    name: "Labor Center",
    children: [
      { name: "Employment Report", href: "/labor/employment" },
      { name: "ID Processing Request", href: "/labor/id-processing" },
      { name: "Manpower Request", href: "/labor/manpower" },
    ],
  },
  {
    name: "Custom Compliance",
    children: [
      { name: "Inspection Report", href: "/custom/inspection" },
      { name: "Inventory Report", href: "/custom/inventory" },
      { name: "Monitoring of Imported Articles", href: "/custom/monitoring" },
      { name: "Quarterly Report (Zone to Zone)", href: "/custom/quarterly" },
      { name: "Special Permit Fees (Imported/Local)", href: "/custom/fees" },
      { name: "Locators", href: "/custom/locators" },
      { name: "Sales Report", href: "/custom/sales" },
      { name: "Monitoring of Violations", href: "/custom/violations" },
      { name: "Overtime Request", href: "/custom/overtime" },
      { name: "Certification of Articles Brought In", href: "/custom/certification" },
    ],
  },
];

// ✅ For One Stop Action Center only
const oneStopActionCenterOnly = [
  {
    name: "One Stop Action Center",
    children: [
     
      {
        name: "Clearance",
        children: [
          { name: "Bring-In Clearance", href: "sezad/clearances/bring-in" },
          { name: "Bring-Out Clearance", href: "/clearance/bring-out" },
          { name: "Temporary Bring-Out Clearance", href: "/clearance/temp-bring-out" },
          { name: "Gatepass Clearance", href: "/clearance/gatepass" },  
          { name: "Local Purchase Clearance", href: "/clearance/temp-bring-out" },          
        ],
      },
    ],
  },
];

// ✅ Build the final navItems dynamically
let navItems: any[] = [];

if (sezadManager) {
  // Manager sees all
  navItems = fullNavItems;
} else if (oneStopActionCenter) {
  navItems = oneStopActionCenterOnly;
} else if (laborCenter) {
  navItems = fullNavItems.filter((i) => i.name === "Labor Center");
} else if (customsClearanceCenter) {
  navItems = fullNavItems.filter((i) => i.name === "Custom Compliance");
} else if (accreditationCenter) {
  navItems = fullNavItems.filter((i) => i.name === "One Stop Action Center");
}


</script>

<template>
  <AppLayout>
<main class="flex-1 bg-gray-100 min-h-screen p-0">
  <!-- Header inside main -->
  <header
    class="w-full bg-white text-black flex flex-col md:flex-row items-center justify-between shadow-md rounded-none px-4 py-3"
  >
    <!-- Left: Title -->
    <div class="flex items-center">
      <h1 class="text-xl font-bold">SEZAD Dashboard</h1>
    </div>

    <!-- Navigation on the right -->
    <nav class="flex flex-wrap items-center justify-end space-x-4">
      <Disclosure
        v-for="item in navItems"
        :key="item.name"
        v-slot="{ open }"
        as="div"
        class="relative"
      >
        <DisclosureButton
          class="flex items-center gap-2 px-3 py-2 text-sm font-medium hover:bg-gray-200 rounded-md transition"
        >
          <span>{{ item.name }}</span>
          <ChevronDown class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" />
        </DisclosureButton>

        <DisclosurePanel
          class="absolute right-0 mt-2 bg-gray-100 rounded-md shadow-lg w-56 p-2 space-y-1 z-50"
        >
          <template v-for="child in item.children" :key="child.name">
            <div v-if="child.children" class="space-y-1">
              <p class="px-2 py-1 text-xs font-semibold text-gray-600">{{ child.name }}</p>
              <ul class="ml-2 space-y-1">
                <li v-for="sub in child.children" :key="sub.name">
                  <Link :href="sub.href" class="block rounded-md px-3 py-1 text-sm hover:bg-gray-200 text-black">
                    {{ sub.name }}
                  </Link>
                </li>
              </ul>
            </div>

            <Link v-else :href="child.href" class="block rounded-md px-3 py-1 text-sm hover:bg-gray-200 text-black">
              {{ child.name }}
            </Link>
          </template>
        </DisclosurePanel>
      </Disclosure>
    </nav>
  </header>

  <!-- Page content -->
  <section class="p-6">
    <h2 class="text-2xl font-bold text-gray-800">Main Content Here</h2>
    <p class="mt-2 text-gray-600">
      This is where your SEZAD dashboard content will be displayed.
    </p>
  </section>
</main>

  </AppLayout>
</template>
