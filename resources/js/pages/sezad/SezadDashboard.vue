<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { ChevronDown } from 'lucide-vue-next'
import { Link, usePage } from '@inertiajs/vue3'
import type { PageProps } from '@inertiajs/core'
import { ref, computed, reactive, nextTick } from "vue"
import Vue3EasyDataTable from 'vue3-easy-data-table'
import 'vue3-easy-data-table/dist/style.css'
import axios from 'axios'

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

interface TempUser {
  id: number
  name: string
  email: string
  business_type: string | null
  locator: string[] | null
  status: string | null
  remark: string | null
  created_at: string
}

interface CustomPageProps extends PageProps {
  auth: { user: AuthUser },
  usersTemp: TempUser[],
  businessTypes: { id: number, name: string, description: string }[]
}

// Page props
const page = usePage<CustomPageProps>()
const user = page.props.auth.user
const usersTemp = ref(page.props.usersTemp)
const businessTypes = ref(page.props.businessTypes)

// Business type map
const businessTypeMap = computed(() =>
  Object.fromEntries(businessTypes.value.map(bt => [bt.id, bt.description]))
)

// Navbar dropdowns
const openDropdown = ref<string | null>(null)
function toggleDropdown(name: string) {
  openDropdown.value = openDropdown.value === name ? null : name;
}

// Access control helpers
const hasAccess = (user: AuthUser, functionId: number) => {
  const d = user.details
  return d && d.role_id === 2 && d.permission_id === 2 && d.department_id === 12 && d.division_id === null && d.user_function_id === functionId
}

const accreditationCenter = hasAccess(user, 1)
const customsClearanceCenter = hasAccess(user, 2)
const laborCenter = hasAccess(user, 3)
const oneStopActionCenter = hasAccess(user, 4)
const sezadManager = hasAccess(user, 5)

interface NavLink { name: string, href?: string, children?: NavLink[] }

const fullNavItems: NavLink[] = [
  { name: "Accreditation Center", href: "sezad/accreditation" },
  { name: "Authority to Operate", href: "/authority-to-operate" },
  {
    name: "One Stop Action Center",
    children: [
      {
        name: "Clearances",
        children: [
          { name: "Gate Clearance", href: "/clearance/gatepass" },
          { name: "Bring-In Clearance", href: "/sezad/clearances/bring-in" },
          { name: "Bring-Out Clearance", href: "/clearance/bring-out" },
          { name: "Temporary Bring-Out Clearance", href: "/clearance/temp-bring-out" },
          { name: "Local Purchase Clearance", href: "/clearance/temp-bring-out" },
          { name: "Storage Clearance", href: "/clearance/storage" },
          { name: "Workflow Access Clearance", href: "/clearance/workflow" },
        ]
      }
    ]
  },
  {
    name: "Custom Clearance Center", children: [
      {
        name: "Clearances", children: [
          { name: "Gate Clearance", href: "/clearance/gatepass" },
          { name: "Bring-In Clearance", href: "/sezad/clearances/bring-in" },
          { name: "Bring-Out Clearance", href: "/clearance/bring-out" },
          { name: "Temporary Bring-Out Clearance", href: "/clearance/temp-bring-out" },
          { name: "Local Purchase Clearance", href: "/clearance/temp-bring-out" },
          { name: "Storage Clearance", href: "/clearance/storage" },
          { name: "Workflow Access Clearance", href: "/clearance/workflow" },
        ]
      }
    ]
  },
  {
    name: "Labor Center", children: [
      {
        name: "Clearances", children: [
          { name: "Gate Clearance", href: "/clearance/gatepass" },
          { name: "Bring-In Clearance", href: "/sezad/clearances/bring-in" },
          { name: "Bring-Out Clearance", href: "/clearance/bring-out" },
          { name: "Temporary Bring-Out Clearance", href: "/clearance/temp-bring-out" },
          { name: "Local Purchase Clearance", href: "/clearance/temp-bring-out" },
          { name: "Storage Clearance", href: "/clearance/storage" },
          { name: "Workflow Access Clearance", href: "/clearance/workflow" },
        ]
      }
    ]
  }
]

let navItems: NavLink[] = sezadManager ? fullNavItems : (oneStopActionCenter ? fullNavItems : [])

// Table headers
const headers = [
  { text: "Name", value: "name", sortable: true },
  { text: "Email", value: "email", sortable: true },
  { text: "Business Type", value: "business_type", sortable: true },
  { text: "Locator", value: "locator", sortable: true },
  { text: "Created At", value: "created_at", sortable: true },
  { text: "Status", value: "status", sortable: true },
  { text: "Remarks", value: "remark", sortable: true }
]

// Search query
const searchQuery = ref("")

// Selected user for modal
const selectedUser = ref<TempUser | null>(null)
const loading = ref(false)

const isRemarkValid = computed(() =>
  selectedUser.value?.remark ? selectedUser.value.remark.trim().length > 0 : false
)

// Filter users
const filteredUsers = computed(() => {
  const normalized = usersTemp.value.map(u => ({
    ...u,
    locator: Array.isArray(u.locator) ? u.locator : JSON.parse(u.locator || '[]')
  }))
  if (!searchQuery.value) return normalized
  const q = searchQuery.value.toLowerCase()
  return normalized.filter(u =>
    Object.values(u).some(v => String(v).toLowerCase().includes(q))
  )
})

// Open modal with deep copy


// Close modal
const closeModal = () => { selectedUser.value = null }

// Update status
usersTemp.value = usersTemp.value.map(u => ({
  ...u,
  locator: Array.isArray(u.locator) ? u.locator : JSON.parse(u.locator || '[]')
}))

// Open modal
const openRemarksModal = (user: TempUser) => {
  selectedUser.value = { ...user }; // clone to avoid direct mutation
};

// Update status
const updateStatus = async (status: string) => {
  if (!selectedUser.value) {
    alert("No user selected.");
    return;
  }

  loading.value = true;

  try {
    const response = await axios.post('/sezad/temp-users/update', {
      id: selectedUser.value.id,
      status,
      remark: selectedUser.value.remark
    });

    const updated = response.data.user;

    const index = usersTemp.value.findIndex(u => u.id === updated.id);
    if (index !== -1) usersTemp.value[index] = updated;

    closeModal();
  } catch (err: any) {
    // Handle Axios errors properly
    if (err.response) {
      console.error("Server error:", err.response.data);
      alert(err.response.data.message || "Server error.");
    } else {
      console.error("Client error:", err.message);
      alert("Failed to update user: " + err.message);
    }
  } finally {
    loading.value = false;
  }
};

// Status class
const statusClass = (status = '') => {
  switch (status?.toLowerCase()) {
    case 'approved': return 'bg-green-100 text-green-700';
    case 'disapproved': return 'bg-red-200 text-red-700';
    case 'new': return 'bg-blue-100 text-blue-700';
    default: return 'bg-gray-200 text-gray-700';
  }
}
</script>

<template>
  <AppLayout>
    <!-- Navigation Header -->
    <nav class="sticky top-0 left-0 right-0 bg-white shadow-md z-40">
      <div class="max-w-7xl mx-auto flex items-center justify-between px-4 py-3">
        <h1 class="text-xl font-bold text-blue-700">
          <Link href="/sezad">SEZAD</Link>
        </h1>

        <div class="hidden md:flex items-center space-x-4">
          <div v-for="item in fullNavItems" :key="item.name" class="relative">
            <Link v-if="!item.children" :href="item.href">{{ item.name }}</Link>

            <div v-else>
              <button @click="toggleDropdown(item.name)" class="flex items-center gap-1">
                {{ item.name }}
                <ChevronDown class="w-4 h-4" :class="{ 'rotate-180': openDropdown === item.name }" />
              </button>
              <div v-show="openDropdown === item.name" class="absolute bg-white shadow-lg mt-2 p-2 rounded">
                <template v-for="child in item.children">
                  <div v-if="child.children">
                    <p class="font-semibold">{{ child.name }}</p>
                    <ul>
                      <li v-for="sub in child.children" :key="sub.name">
                        <Link :href="sub.href">{{ sub.name }}</Link>
                      </li>
                    </ul>
                  </div>
                  <Link v-else :href="child.href">{{ child.name }}</Link>
                </template>
              </div>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="p-6 bg-gray-100 min-h-screen">
      <h2 class="text-2xl font-bold text-[#0f75bc] mb-6">SEZAD - New Users Application</h2>

      <!-- Search -->
      <div class="mb-4 flex justify-end">
        <input v-model="searchQuery" type="text" placeholder="Search..."
          class="w-1/3 px-4 py-2 border rounded focus:ring-2 focus:ring-blue-500" />
      </div>

      <!-- Users Table -->
      <EasyDataTable :headers="headers" :items="filteredUsers" :rows-per-page="10" show-index clickable
        @click-row="openRemarksModal" class="rounded-lg shadow border border-gray-200">
        <template #item-status="{ item, status }">
          <span :class="[statusClass(status), 'px-2 py-1 rounded-full text-xs font-medium']">{{ status }}</span>
        </template>
        <template #item-locator="{ item, locator }">{{ locator?.join(', ') || '-' }}</template>
        <template #item-business_type="{ item, business_type }">{{ businessTypeMap[business_type] || '-' }}</template>
        <template #item-remark="{ item, remark }">{{ remark || '-' }}</template>
      </EasyDataTable>

      <!-- Modal -->
      <transition name="fade">
        <div v-if="selectedUser" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
          <div class="bg-white p-6 rounded-2xl shadow-2xl w-full max-w-md">
            <h2 class="text-xl font-bold mb-4 text-center">Update Status for {{ selectedUser.name }}</h2>
            <label class="block text-sm font-medium mb-2">Remarks</label>
            <textarea v-model="selectedUser.remark" rows="4" class="w-full border p-2 rounded"></textarea>

            <div class="flex justify-end space-x-3 mt-4">
              <button @click="closeModal" class="px-4 py-2 bg-gray-200 rounded">Close</button>

              <button @click="updateStatus('Disapproved')" :disabled="!isRemarkValid || loading"
                class="px-4 py-2 rounded text-white"
                :class="(!isRemarkValid || loading) ? 'bg-red-300 cursor-not-allowed' : 'bg-red-500'">
                <span v-if="loading">Processing...</span>
                <span v-else>Disapprove</span>
              </button>

              <button @click="updateStatus('Approved')" :disabled="!isRemarkValid || loading"
                class="px-4 py-2 rounded text-white"
                :class="(!isRemarkValid || loading) ? 'bg-green-300 cursor-not-allowed' : 'bg-green-500'">
                <span v-if="loading">Processing...</span>
                <span v-else>Approve</span>
              </button>
            </div>
          </div>
        </div>
      </transition>
    </main>
  </AppLayout>
</template>

<style scoped>
.easy-data-table {
  --easy-table-header-bg: #f1f5f9;
  --easy-table-header-color: #0f75bc;
}
</style>
