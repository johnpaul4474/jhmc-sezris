<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import { ref } from "vue"
import { Users, FileText, UserPlus } from "lucide-vue-next" // Lucide icons
import users from '@/routes/users';
import type { PageProps } from '@inertiajs/core'
const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: dashboard().url,
  },
];



// Example data (later replace with Inertia or API call)

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
// Example data (later replace with Inertia or API call)

</script>

<template>

  <Head title="Dashboard" />

  <AppLayout :breadcrumbs="breadcrumbs">


  </AppLayout>
</template>
