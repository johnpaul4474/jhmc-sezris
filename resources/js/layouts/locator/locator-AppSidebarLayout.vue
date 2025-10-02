<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppShell from '@/components/AppShell.vue';
import locatorAppsidebar from '@/components/locator/locator-Appsidebar.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import { type BreadcrumbItem } from '@/types';
import locators from '@/routes/locators';
import applications from '@/routes/applications';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'locators',
        href: locators.index.url(),
    },
    {
        title: 'create Application',
        href: applications.create.url(),
    },
    {
        title: 'pending Application',
        href: applications.pending.url(),
    },
    {
        title: 'approved Application',
        href: applications.approved.url(),
    },
];

// withDefaults(defineProps<Props>(), {
//     breadcrumbs: () => [],
// });
</script>

<template>
    <AppShell variant="sidebar">
        
        <locatorAppsidebar :breadcrumbs="breadcrumbs"/>
        <AppContent variant="sidebar" class="overflow-x-hidden">
            <AppSidebarHeader :breadcrumbs="breadcrumbs" />
            <slot />
        </AppContent>
    </AppShell>
</template>
