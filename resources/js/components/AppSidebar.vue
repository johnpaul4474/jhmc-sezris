<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';

import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';

import { dashboard, usersDashboard, sezadDashboard, bddDashboard } from '@/routes';
import type { NavItem } from '@/types';

import { Link, usePage } from '@inertiajs/vue3';
import type { PageProps } from '@inertiajs/core';

import AppLogo from './AppLogo.vue';

import { LayoutGrid, SquareUserRound, Folder, BookOpen, Clock, Eye } from 'lucide-vue-next';
import { dashboard, usersDashboard, sezadDashboard} from '@/routes';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';

import AppLogo from './AppLogo.vue';
import { usePage } from '@inertiajs/vue3'
import type { PageProps } from '@inertiajs/core'
import { Users,UserRoundPen,BookOpen, Folder, LayoutGrid, SquareUserRound, Clock, Eye } from 'lucide-vue-next';
import { ref, onMounted } from "vue";
import * as Ably from "ably";

/* ===============================
    TYPES
   =============================== */

interface Permissions {
    isAdmin: boolean
    isLocator: boolean
    sezadManager: boolean
    accreditationSpsnbe: boolean
    accreditationCeoc: boolean
    accreditationTfbosta: boolean
    accreditationVme: boolean
    accreditationProvitional: boolean

}

/* ===============================
    PAGE & PERMISSIONS
   =============================== */

// Use usePage without a risky generic, then cast props where needed.
// This avoids trying to override Inertia's PageProps shape.
const page = usePage<PageProps>();

// page.props has unknown shape to TS by default; cast into our expected shape:
const propsAny = page.props as unknown as {
  auth?: { user?: any }
  permissions?: Partial<Permissions>
};

// Safely derive typed permissions and user with fallbacks
const permissions: Permissions = {
  isAdmin: !!propsAny.permissions?.isAdmin,
  isLocator: !!propsAny.permissions?.isLocator,
  sezadManager: !!propsAny.permissions?.sezadManager,
  accreditationSpsnbe: !!propsAny.permissions?.accreditationSpsnbe,
  accreditationCeoc: !!propsAny.permissions?.accreditationCeoc,
  accreditationTfbosta: !!propsAny.permissions?.accreditationTfbosta,
  accreditationVme: !!propsAny.permissions?.accreditationVme,
  accreditationProvitional: !!propsAny.permissions?.accreditationProvitional,
  
};

const user = propsAny.auth?.user ?? null;

/* ===============================
    MAIN NAVIGATION
   =============================== */

const BDD = 
     user?.details &&
    user.details.role_id === 2 &&
   // user.details?.position_id === 54 &&
    user.details.permission_id === 2 &&
    user.details.department_id === 5 &&
    user.details.division_id === null &&
    user.details.user_function_id === 6

const vendor = user?.details &&
    user.details.permission_id == 2 &&
    user.details.role_id == 7 &&
    user.details.department_id == null &&
    user.details.user_function_id == null
//serviceProvider
 const ServiceProvider = user?.details &&
   user.details.role_id == 4 &&
   user.details.permission_id == 2 &&
   user.details.department_id == null &&
   user.details.user_function_id == null   
const mainNavItems: NavItem[] = [];

/* ADMIN */
if (permissions.isAdmin) {
    mainNavItems.push(
        { title: 'Dashboard', href: '/dashboard ', icon: LayoutGrid },
        { title: 'Users', href: '/users', icon: SquareUserRound },
        { title: 'SEZAD', href: 'sezad', icon: Folder },
        { title: 'BDD Created Users', href: 'bdd', icon: BookOpen },

        // These are normal strings; keep as is
        { title: 'Locator', href: '/locator', icon: LayoutGrid },
        { title: 'Create Application', href: '/loctr/applications/create', icon: SquareUserRound },
        { title: 'Pending Application', href: '/loctr/applications/pending', icon: Clock },
        { title: 'Approved Applications', href: '/loctr/applications/approved', icon: Eye },
        {
            title: 'Dashboard',
            href: dashboard(),
            icon: LayoutGrid,
        },
        {
            title: 'Users',
            href: usersDashboard(),
            icon: SquareUserRound,
        },
        {
            title: 'SEZAD',
            href: sezadDashboard(),
            icon: Folder,
        },
        {
            title: 'BDD Created Users',
            href:'#',
            icon: BookOpen,
        }, {
        title: 'Locator',
        href: '/locator',
        icon: LayoutGrid,
    },
        {
            title: 'Create Application',
            href: '/loctr/applications/create',
            icon: SquareUserRound,
        }, {
        title: 'Pending Application',
        href: '/loctr/applications/pending',
        icon: Clock,
    }, {
        title: 'Approved Applications',
        href: '/loctr/applications/approved',
        icon: Eye,
    },
    );
}

/* SEZAD MANAGER */
else if (permissions.sezadManager) {
    mainNavItems.push({
        title: 'SEZAD Dashboard',
        href: '/sezad',
        icon: LayoutGrid,
    });
}

/* SPNSBE */
else if (permissions.accreditationSpsnbe) {
    mainNavItems.push({
        title: 'Service Provider/Supplier',
        href: '/dashboard',
        icon: LayoutGrid,
    });
}

else if (permissions.accreditationCeoc) {
    mainNavItems.push({
        title: 'Commercial Event Operator',
        href: '/dashboard',
        icon: LayoutGrid,
    });
}
        {
            title: 'DASHBOARD',
            href: sezadDashboard(),
            icon: LayoutGrid,
        }
    );
} else if(locator){
    mainNavItems.push(
        {
            title: 'Create Application',
            href: '/loctr/applications/create',
            icon: LayoutGrid,
        },
        {
            title: 'Apply For ATO',
            href: '/loctr/applications/create',
            icon: LayoutGrid,
        },
        {
            title: 'Vedor Requests',
            href:'/VendorVerify',
            icon: LayoutGrid,
        },
        {
            title:'My Vendors',
            href: '/MyVendors',
            icon: LayoutGrid,
        },
        
    );
}else if(BDD){
   mainNavItems.push(
    {
            title: 'registered Locators',
            href: '/bdd/dash',
            icon: Users,
            
        },
        {
            title: 'Locator with Profiles',
            href: '#',
            icon: UserRoundPen,
            
        },
   );
}else if(vendor){
    mainNavItems.push(

        {
            title: 'Vendor',
            href: '/',
            icon: LayoutGrid,
            
        },
        {
            title: 'Apply for Accreditation',
            href: '/',
            icon: Eye,
        }
    );

}else if(ServiceProvider){
    mainNavItems.push(

        {
            title: 'ServiceProvider',
            href: '/#',
            icon: LayoutGrid,
            
        },
        {
             title:'Acreditation',
             href: '/#',
             icon: LayoutGrid,
        }
    );
}else{
    mainNavItems.push(

else if (permissions.accreditationTfbosta) {
    mainNavItems.push({
        title: 'Traid fairs & Bazaars',
        href: '/dashboard',
        icon: LayoutGrid,
    });
}
else if (permissions.accreditationVme) {
    mainNavItems.push({
        title: 'Vendor and Micro Enterpreneurs',
        href: '/dashboard',
        icon: LayoutGrid,
    });
}


else if (permissions.accreditationProvitional) {
    mainNavItems.push({
        title: 'Provitional Grant',
        href: '/dashboard',
        icon: LayoutGrid,
    });
}

/* ===============================
    FOOTER NAV
   =============================== */

const footerNavItems: NavItem[] = [];

/* ===============================
    NOTIFICATIONS
   =============================== */

const hasNotification = ref(false);

onMounted(() => {
    const saved = localStorage.getItem("hasNotification");
    hasNotification.value = saved === "true";

    const ablyKey = import.meta.env.VITE_ABLY_KEY;
    if (!ablyKey) return;

    const client = new Ably.Realtime({ key: ablyKey });
    const channel = client.channels.get("notifications");

    channel.subscribe(() => {
        hasNotification.value = true;
        localStorage.setItem("hasNotification", "true");
    });
});

function clearNotification() {
    hasNotification.value = false;
    localStorage.setItem("hasNotification", "false");
}
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">

        <!-- HEADER -->
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link>
                            <AppLogo class="h-8 w-auto" />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <!-- CONTENT -->
        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <!-- FOOTER -->
        <SidebarFooter>
            <NavFooter :items="footerNavItems" />

            <NavUser
                :user="user"
                :has-notification="hasNotification"
                @clear-notification="clearNotification"
            />
        </SidebarFooter>

    </Sidebar>

    <!-- PAGE CONTENT -->
    <slot />
</template>
