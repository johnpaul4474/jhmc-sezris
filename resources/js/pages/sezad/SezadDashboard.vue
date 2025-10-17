<script setup lang="ts">
import { ChevronDown, ArrowLeft } from "lucide-vue-next"
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue"
import { Link } from "@inertiajs/vue3" // for Inertia navigation

// Navigation Items
const navItems = [
  {
    name: "One Stop Action Center",
    children: [
      { name: "Accreditation of Letters", href: "/accreditation" },
      { name: "Certificate of Registration", href: "/certificate" },
      { name: "Application for Import Permit", href: "/import-permit" },
      { name: "Declaration of Admission of Articles", href: "/declaration" },
      {
        name: "Clearance",
        children: [
          { name: "Gatepass Clearance", href: "/clearance/gatepass" },
          { name: "Bring-In Clearance", href: "/clearance/bring-in" },
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
]
</script>

<template>
  <div class="flex min-h-screen bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-72 bg-[#0F75BC] text-white flex flex-col">
      <!-- Top Section -->
      <div class="px-6 py-4 border-b border-white/20">
        <h1 class="text-xl font-bold">SEZAD Dashboard</h1>
      </div>

      <!-- Back Button -->
      <div class="px-4 py-3">
        <Link
          href="/dashboard"
          class="flex w-full items-center gap-2 rounded-md bg-blue-800 px-3 py-2 text-sm font-medium hover:bg-blue-700 transition"
        >
          <ArrowLeft class="w-4 h-4" />
          Back to Dashboard
        </Link>
      </div>

      <!-- Navigation -->
      <nav class="flex-1 overflow-y-auto px-4 py-4 space-y-2">
        <Disclosure
          v-for="item in navItems"
          :key="item.name"
          v-slot="{ open }"
          as="div"
          class="space-y-1"
        >
          <DisclosureButton
            class="flex w-full items-center justify-between rounded-md px-3 py-2 text-sm font-medium hover:bg-blue-800"
          >
            <span>{{ item.name }}</span>
            <ChevronDown
              class="w-4 h-4 transition-transform"
              :class="{ 'rotate-180': open }"
            />
          </DisclosureButton>

          <DisclosurePanel class="ml-4 mt-1 space-y-1">
            <template v-for="child in item.children" :key="child.name">
              <!-- Submenu -->
              <div v-if="child.children" class="space-y-1">
                <p class="px-2 py-1 text-xs font-semibold text-gray-200">
                  {{ child.name }}
                </p>
                <ul class="ml-2 space-y-1">
                  <li v-for="sub in child.children" :key="sub.name">
                    <Link
                      :href="sub.href"
                      class="block w-full rounded-md px-3 py-1 text-sm hover:bg-blue-700"
                    >
                      {{ sub.name }}
                    </Link>
                  </li>
                </ul>
              </div>

              <!-- Regular Item -->
              <Link
                v-else
                :href="child.href"
                class="block w-full rounded-md px-3 py-1 text-sm hover:bg-blue-700"
              >
                {{ child.name }}
              </Link>
            </template>
          </DisclosurePanel>
        </Disclosure>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6">
      <h2 class="text-2xl font-bold text-gray-800">Main Content Here</h2>
      <p class="mt-2 text-gray-600">
        This is where your SEZAD dashboard content will be displayed.
      </p>
    </main>
  </div>
</template>
