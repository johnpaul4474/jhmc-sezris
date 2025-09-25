<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { usersDashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref, computed, reactive, onMounted } from 'vue';
import { Dialog, DialogPanel, DialogTitle, TransitionRoot } from '@headlessui/vue';

// Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Users Dashboard', href: usersDashboard().url },
];

// --- State ---
const users = ref([
    {
        id: 1,
        employee_id: "EMP001",
        first_name: "Juan",
        middle_name: "Dela",
        last_name: "Cruz",
        email_address: "juan@example.com",
        status: "Active",
        department: "IT",
        division: "Development",
        roles: "Admin",
        birth_date: "1990-01-01",
        sex: "Male",
        phone: "09171234567",
        address: {
            street: "123 Main St",
            barangay: "Barangay 1",
            municipality: "Quezon City",
            province: "Metro Manila",
            region: "NCR",
        },
    },
    {
        id: 2,
        employee_id: "EMP002",
        first_name: "Maria",
        middle_name: "Santos",
        last_name: "Reyes",
        email_address: "maria@example.com",
        status: "Inactive",
        department: "HR",
        division: "Recruitment",
        roles: "User",
        birth_date: "1992-05-12",
        sex: "Female",
        phone: "09987654321",
        address: {
            street: "456 Second St",
            barangay: "Barangay 2",
            municipality: "Makati",
            province: "Metro Manila",
            region: "NCR",
        },
    },
]);

// Filters
const search = ref('');
const filterDept = ref('');
const filterDiv = ref('');

// Sorting
const sortField = ref('first_name');
const sortAsc = ref(true);
function sortBy(field: string) {
    if (sortField.value === field) sortAsc.value = !sortAsc.value;
    else {
        sortField.value = field;
        sortAsc.value = true;
    }
}

// --- Modal state ---
const showDetails = ref(false);
const showAddUser = ref(false);
const editableUser = reactive({
    id: null as number | null,
    employee_id: "",
    first_name: "",
    middle_name: "",
    last_name: "",
    email_address: "",
    status: "",
    department: "",
    division: "",
    roles: "",
    birth_date: "",
    sex: "",
    phone: "",
    address: {
        street: "",
        barangay: "",
        municipality: "",
        province: "",
        region: "",
    },
});

// --- Functions ---
function openUser(user: any) {
    Object.assign(editableUser, JSON.parse(JSON.stringify(user))); // deep copy
    showDetails.value = true;
}

function updateUser() {
    const index = users.value.findIndex((u) => u.id === editableUser.id);
    if (index !== -1) {
        users.value[index] = JSON.parse(JSON.stringify(editableUser));
    }
    showDetails.value = false;
}

// Add User
const newUser = reactive({
    id: 0,
    employee_id: "",
    first_name: "",
    middle_name: "",
    last_name: "",
    email_address: "",
    department: "",
    division: "",
    roles: "",
    status: "Active",
    birth_date: "",
    sex: "",
    phone: "",
    address: {
        region: "",
        province: "",
        municipality: "",
        barangay: "",
        street: "",
    },
});

function addUser() {
    newUser.id = users.value.length ? Math.max(...users.value.map(u => u.id)) + 1 : 1;
    users.value.push(JSON.parse(JSON.stringify(newUser)));
    Object.assign(newUser, {
        id: 0,
        employee_id: "",
        first_name: "",
        middle_name: "",
        last_name: "",
        email_address: "",
        department: "",
        division: "",
        roles: "",
        status: "Active",
        birth_date: "",
        sex: "",
        phone: "",
        address: {
            region: "",
            province: "",
            municipality: "",
            barangay: "",
            street: "",
        },
    });
    showAddUser.value = false;
}

// Pagination
const currentPage = ref(1);
const pageSize = ref(5);

const filteredUsers = computed(() => {
    return users.value
        .filter((u) => {
            const fullName = `${u.first_name} ${u.middle_name} ${u.last_name}`.toLowerCase();
            const matchesSearch =
                fullName.includes(search.value.toLowerCase()) ||
                (u.email_address && u.email_address.toLowerCase().includes(search.value.toLowerCase()));

            const matchesDept = filterDept.value ? u.department === filterDept.value : true;
            const matchesDiv = filterDiv.value ? u.division === filterDiv.value : true;

            return matchesSearch && matchesDept && matchesDiv;
        })
        .sort((a, b) => {
            let valA: string | number = '';
            let valB: string | number = '';

            switch (sortField.value) {
                case 'name':
                    valA = `${a.first_name} ${a.last_name}`.toLowerCase();
                    valB = `${b.first_name} ${b.last_name}`.toLowerCase();
                    break;
                case 'email_address':
                    valA = (a.email_address || '').toLowerCase();
                    valB = (b.email_address || '').toLowerCase();
                    break;
                case 'department':
                    valA = (a.department || '').toLowerCase();
                    valB = (b.department || '').toLowerCase();
                    break;
                case 'division':
                    valA = (a.division || '').toLowerCase();
                    valB = (b.division || '').toLowerCase();
                    break;
                case 'roles':
                    valA = (a.roles || '').toLowerCase();
                    valB = (b.roles || '').toLowerCase();
                    break;
                case 'status':
                    valA = (a.status || '').toLowerCase();
                    valB = (b.status || '').toLowerCase();
                    break;
                default:
                    valA = '';
                    valB = '';
            }

            if (valA < valB) return sortAsc.value ? -1 : 1;
            if (valA > valB) return sortAsc.value ? 1 : -1;
            return 0;
        });
});

const paginatedUsers = computed(() => {
    const start = (currentPage.value - 1) * pageSize.value;
    const end = start + pageSize.value;
    return filteredUsers.value.slice(start, end);
});

const totalPages = computed(() => Math.ceil(filteredUsers.value.length / pageSize.value));

function goToPage(page: number) {
    if (page >= 1 && page <= totalPages.value) currentPage.value = page;
}


// --- Location Data ---
const regions = ref<any[]>([]);
const provinces = ref<any[]>([]);
const municipalities = ref<any[]>([]);
const barangays = ref<any[]>([]);

// Fetch regions on load
onMounted(async () => {
    const res = await fetch("https://psgc.cloud/api/regions");
    regions.value = await res.json();
});

// When region changes → fetch provinces
async function loadProvinces(regionCode: string) {
    newUser.address.province = "";
    newUser.address.municipality = "";
    newUser.address.barangay = "";
    municipalities.value = [];
    barangays.value = [];

    const res = await fetch(`https://psgc.cloud/api/regions/${regionCode}/provinces`);
    provinces.value = await res.json();
}

// When province changes → fetch municipalities
async function loadMunicipalities(provinceCode: string) {
    newUser.address.municipality = "";
    newUser.address.barangay = "";
    barangays.value = [];

    const res = await fetch(`https://psgc.cloud/api/provinces/${provinceCode}/cities-municipalities`);
    municipalities.value = await res.json();
}

// When municipality changes → fetch barangays
async function loadBarangays(muniCode: string) {
    newUser.address.barangay = "";
    const res = await fetch(`https://psgc.cloud/api/cities-municipalities/${muniCode}/barangays`);
    barangays.value = await res.json();
}
</script>


<template>

    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b px-6 py-4">
                    <h2 class="text-xl font-bold text-gray-800">👥 Users Dashboard</h2>
                    <button @click="showAddUser = true"
                        class="flex items-center gap-2 rounded-md bg-[#0F75BC] px-4 py-2 text-white shadow hover:bg-blue-700 transition">
                        ➕ Add User
                    </button>
                </div>

                <!-- Filters -->
                <div class="flex flex-wrap gap-4 p-6">
                    <input v-model="search" type="text" placeholder="🔍 Search by name or email"
                        class="w-64 rounded-md border px-3 py-2 shadow-sm focus:border-[#0F75BC] focus:ring-[#0F75BC]" />
                    <select v-model="filterDept" class="rounded-md border px-3 py-2 shadow-sm">
                        <option value="">All Departments</option>
                        <option>IT</option>
                        <option>HR</option>
                    </select>
                    <select v-model="filterDiv" class="rounded-md border px-3 py-2 shadow-sm">
                        <option value="">All Divisions</option>
                        <option>Development</option>
                        <option>Recruitment</option>
                    </select>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm m-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 cursor-pointer"
                                    @click="sortBy('first_name')">
                                    Name
                                </th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 cursor-pointer"
                                    @click="sortBy('email_address')">
                                    Email
                                </th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 cursor-pointer"
                                    @click="sortBy('department')">
                                    Department
                                </th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 cursor-pointer"
                                    @click="sortBy('division')">
                                    Division
                                </th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 cursor-pointer"
                                    @click="sortBy('roles')">
                                    Role
                                </th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 cursor-pointer"
                                    @click="sortBy('status')">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="user in paginatedUsers" :key="user.id"
                                class="cursor-pointer odd:bg-white even:bg-gray-50 hover:bg-blue-50"
                                @click="openUser(user)">
                                <td class="px-4 py-3 font-medium text-gray-800">
                                    {{ user.first_name }} {{ user.last_name }}
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ user.email_address }}</td>
                                <td class="px-4 py-3">{{ user.department }}</td>
                                <td class="px-4 py-3">{{ user.division }}</td>
                                <td class="px-4 py-3">{{ user.roles }}</td>
                                <td>
                                    <span class="inline-block rounded-full px-3 py-1 text-xs font-medium"
                                        :class="user.status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                                        {{ user.status }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="flex justify-center items-center gap-2 p-4">
                        <button @click="goToPage(currentPage - 1)" :disabled="currentPage === 1"
                            class="px-3 py-1 rounded border bg-gray-100 hover:bg-gray-200 disabled:opacity-50">
                            ‹ Prev
                        </button>

                        <button v-for="page in totalPages" :key="page" @click="goToPage(page)"
                            class="px-3 py-1 rounded border" :class="page === currentPage
                                ? 'bg-[#0F75BC] text-white border-[#0F75BC]'
                                : 'bg-white hover:bg-gray-100 text-gray-700 border-gray-300'">
                            {{ page }}
                        </button>

                        <button @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages"
                            class="px-3 py-1 rounded border bg-gray-100 hover:bg-gray-200 disabled:opacity-50">
                            Next ›
                        </button>
                    </div>
                </div>
            </div>

            <!-- User Details Modal (3-column layout) -->
            <TransitionRoot appear :show="showDetails" as="template">
                <Dialog as="div" class="relative z-50" @close="showDetails = false">
                    <div class="fixed inset-0 bg-black/40" />
                    <div class="fixed inset-0 flex items-center justify-center p-4">
                        <DialogPanel class="w-full max-w-6xl rounded-lg bg-white shadow-xl">
                            <!-- Header -->
                            <div class="flex items-center justify-between border-b px-6 py-4">
                                <DialogTitle class="text-lg font-bold">✏️ Edit User</DialogTitle>
                                <button @click="showDetails = false"
                                    class="text-gray-400 hover:text-gray-600">✕</button>
                            </div>

                            <!-- Form -->
                            <form v-if="editableUser" @submit.prevent="updateUser">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6 text-sm">
                                    <!-- Column 1 -->
                                    <div class="space-y-3">
                                        <div>
                                            <label class="block font-medium">Employee ID</label>
                                            <input v-model="editableUser.employee_id" type="text"
                                                class="w-full rounded-md border-gray-300 shadow-sm" />
                                        </div>
                                        <div>
                                            <label class="block font-medium">First Name</label>
                                            <input v-model="editableUser.first_name" type="text"
                                                class="w-full rounded-md border-gray-300 shadow-sm" />
                                        </div>
                                        <div>
                                            <label class="block font-medium">Middle Name</label>
                                            <input v-model="editableUser.middle_name" type="text"
                                                class="w-full rounded-md border-gray-300 shadow-sm" />
                                        </div>
                                        <div>
                                            <label class="block font-medium">Last Name</label>
                                            <input v-model="editableUser.last_name" type="text"
                                                class="w-full rounded-md border-gray-300 shadow-sm" />
                                        </div>
                                        <div>
                                            <label class="block font-medium">Email</label>
                                            <input v-model="editableUser.email_address" type="email"
                                                class="w-full rounded-md border-gray-300 shadow-sm" />
                                        </div>
                                    </div>

                                    <!-- Column 2 -->
                                    <div class="space-y-3">
                                        <div>
                                            <label class="block font-medium">Status</label>
                                            <select v-model="editableUser.status"
                                                class="w-full rounded-md border-gray-300 shadow-sm">
                                                <option value="Active">Active</option>
                                                <option value="Inactive">Inactive</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block font-medium">Department</label>
                                            <input v-model="editableUser.department" type="text"
                                                class="w-full rounded-md border-gray-300 shadow-sm" />
                                        </div>
                                        <div>
                                            <label class="block font-medium">Division</label>
                                            <input v-model="editableUser.division" type="text"
                                                class="w-full rounded-md border-gray-300 shadow-sm" />
                                        </div>
                                        <div>
                                            <label class="block font-medium">Role</label>
                                            <input v-model="editableUser.roles" type="text"
                                                class="w-full rounded-md border-gray-300 shadow-sm" />
                                        </div>
                                        <div>
                                            <label class="block font-medium">Birth Date</label>
                                            <input v-model="editableUser.birth_date" type="date"
                                                class="w-full rounded-md border-gray-300 shadow-sm" />
                                        </div>
                                    </div>

                                    <!-- Column 3 -->
                                    <div class="space-y-3">
                                        <div>
                                            <label class="block font-medium">Sex</label>
                                            <select v-model="editableUser.sex"
                                                class="w-full rounded-md border-gray-300 shadow-sm">
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block font-medium">Phone</label>
                                            <input v-model="editableUser.phone" type="text"
                                                class="w-full rounded-md border-gray-300 shadow-sm" />
                                        </div>
                                        <div>
                                            <label class="block font-medium">Street</label>
                                            <input v-model="editableUser.address.street" type="text"
                                                class="w-full rounded-md border-gray-300 shadow-sm" />
                                        </div>
                                        <div>
                                            <label class="block font-medium">Barangay</label>
                                            <input v-model="editableUser.address.barangay" type="text"
                                                class="w-full rounded-md border-gray-300 shadow-sm" />
                                        </div>
                                        <div>
                                            <label class="block font-medium">Municipality</label>
                                            <input v-model="editableUser.address.municipality" type="text"
                                                class="w-full rounded-md border-gray-300 shadow-sm" />
                                        </div>
                                        <div>
                                            <label class="block font-medium">Province</label>
                                            <input v-model="editableUser.address.province" type="text"
                                                class="w-full rounded-md border-gray-300 shadow-sm" />
                                        </div>
                                        <div>
                                            <label class="block font-medium">Region</label>
                                            <input v-model="editableUser.address.region" type="text"
                                                class="w-full rounded-md border-gray-300 shadow-sm" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer -->
                                <div class="flex justify-end gap-3 border-t px-6 py-4 bg-white sticky bottom-0">
                                    <button type="button" @click="showDetails = false"
                                        class="px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2 rounded-md bg-[#0F75BC] text-white hover:bg-blue-700">
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </DialogPanel>
                    </div>
                </Dialog>
            </TransitionRoot>

            <!-- Add User Modal -->
            <TransitionRoot appear :show="showAddUser" as="template">
                <Dialog as="div" class="relative z-50" @close="showAddUser = false">
                    <div class="fixed inset-0 bg-black/30" />
                    <div class="fixed inset-0 flex items-center justify-center p-4">
                        <DialogPanel class="w-full max-w-5xl rounded-lg bg-white p-6 shadow-xl">
                            <DialogTitle class="text-lg font-bold mb-4">Add User</DialogTitle>

                            <form @submit.prevent="addUser" class="grid grid-cols-3 gap-4">
                                <!-- Basic Info -->
                                <input v-model="newUser.employee_id" placeholder="Employee ID"
                                    class="border px-2 py-1 rounded" />
                                <input v-model="newUser.email_address" type="email" placeholder="Email"
                                    class="border px-2 py-1 rounded" />
                                <select v-model="newUser.status" class="border px-2 py-1 rounded">
                                    <option value="">Select Status</option>
                                    <option>Active</option>
                                    <option>Inactive</option>
                                </select>

                                <input v-model="newUser.first_name" placeholder="First Name"
                                    class="border px-2 py-1 rounded" />
                                <input v-model="newUser.middle_name" placeholder="Middle Name"
                                    class="border px-2 py-1 rounded" />
                                <input v-model="newUser.last_name" placeholder="Last Name"
                                    class="border px-2 py-1 rounded" />

                                <input v-model="newUser.department" placeholder="Department"
                                    class="border px-2 py-1 rounded" />
                                <input v-model="newUser.division" placeholder="Division"
                                    class="border px-2 py-1 rounded" />
                                <input v-model="newUser.roles" placeholder="Role" class="border px-2 py-1 rounded" />

                                <input v-model="newUser.birth_date" type="date" class="border px-2 py-1 rounded" />
                                <input v-model="newUser.sex" placeholder="Sex" class="border px-2 py-1 rounded" />
                                <input v-model="newUser.phone" placeholder="Phone" class="border px-2 py-1 rounded" />

                                <!-- Address -->
                                <select v-model="newUser.address.region" @change="loadProvinces(newUser.address.region)"
                                    class="border px-2 py-1 rounded col-span-1">
                                    <option value="">Select Region</option>
                                    <option v-for="r in regions" :key="r.code" :value="r.code">{{ r.name }}</option>
                                </select>

                                <select v-model="newUser.address.province"
                                    @change="loadMunicipalities(newUser.address.province)"
                                    class="border px-2 py-1 rounded col-span-1" :disabled="!provinces.length">
                                    <option value="">Select Province</option>
                                    <option v-for="p in provinces" :key="p.code" :value="p.code">{{ p.name }}</option>
                                </select>

                                <select v-model="newUser.address.municipality"
                                    @change="loadBarangays(newUser.address.municipality)"
                                    class="border px-2 py-1 rounded col-span-1" :disabled="!municipalities.length">
                                    <option value="">Select Municipality</option>
                                    <option v-for="m in municipalities" :key="m.code" :value="m.code">{{ m.name }}
                                    </option>
                                </select>

                                <select v-model="newUser.address.barangay" class="border px-2 py-1 rounded col-span-1"
                                    :disabled="!barangays.length">
                                    <option value="">Select Barangay</option>
                                    <option v-for="b in barangays" :key="b.code" :value="b.name">{{ b.name }}</option>
                                </select>

                                <input v-model="newUser.address.street" placeholder="Street"
                                    class="border px-2 py-1 rounded col-span-2" />

                                <!-- Buttons -->
                                <div class="col-span-3 flex justify-end mt-4">
                                    <button type="button" @click="showAddUser = false"
                                        class="px-4 py-2 bg-gray-300 rounded-md mr-2">Cancel</button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-[#0F75BC] text-white rounded-md">Save</button>
                                </div>
                            </form>

                        </DialogPanel>
                    </div>
                </Dialog>
            </TransitionRoot>

        </div>
    </AppLayout>
</template>
