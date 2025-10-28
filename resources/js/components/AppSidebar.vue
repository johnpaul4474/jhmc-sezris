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
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';

import AppLogo from './AppLogo.vue';
import { usePage } from '@inertiajs/vue3'
import type { PageProps } from '@inertiajs/core'
import { BookOpen, Folder, LayoutGrid, SquareUserRound, Clock, Eye } from 'lucide-vue-next';


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


const admin =
    user?.details &&
    user.details.role_id === 1 &&
    user.details.permission_id === 1 &&
    user.details.department_id === 9 &&
    user.details.division_id === 3 &&
    user.details.user_function_id === null

//change this for other roles
const sezadOSAC =
    user?.details &&
    user.details.role_id === 2 &&
    user.details.permission_id === 2 &&
    user.details.department_id === 12 &&
    user.details.division_id === null &&
    user.details.user_function_id === 4

const mainNavItems: NavItem[] = [];

if (admin) {
    mainNavItems.push(
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
            href: bddDashboard(),
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
} else if (sezadOSAC) {
    mainNavItems.push(

        {
            title: 'DASHBOARD',
            href: sezadDashboard(),
            icon: LayoutGrid,
        }
    );
} else {
    mainNavItems.push(

        {
            title: 'Others',
            href: sezadDashboard(),
            icon: LayoutGrid,
        }
    );
}

console.log(user.details)
const footerNavItems: NavItem[] = [
    // {
    //     title: 'Github Repo',
    //     href: 'https://github.com/laravel/vue-starter-kit',
    //     icon: Folder,
    // },
    // {
    //     title: 'Documentation',
    //     href: 'https://laravel.com/docs/starter-kits#vue',
    //     icon: BookOpen,
    // },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">

                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
