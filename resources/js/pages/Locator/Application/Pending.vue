
<script setup lang="ts">
import LocatorAppSidebarLayout from '@/layouts/locator/LocatorAppSidebarLayout.vue'
import { type BreadcrumbItem } from '@/types'
import locators from '@/routes/locators'
import applications from '@/routes/applications'


const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Locator',
    href: locators.index.url(),
  },
  {
    title: 'Create Permit',
    href: applications.create.url(),
  },
  {
    title: 'Pending Applications',
    href: applications.pending.url(),
  },
]
</script>

<template>
  <LocatorAppSidebarLayout :breadcrumbs="breadcrumbs">
    <h1 class="text-2xl font-bold mb-4">Pending Application Lists</h1>
    <!-- Your table or content goes here -->
  </LocatorAppSidebarLayout>
</template>
