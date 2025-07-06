<script setup>
import {
    Presentation,
    Settings,
} from 'lucide-vue-next'

import NavMain from '@/Layouts/Components/NavMain.vue'
import NavUser from '@/Layouts/Components/NavUser.vue'
import Logo from '@/Layouts/Components/Logo.vue'

import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarRail,
} from '@/Components/ui/sidebar'

const props = withDefaults(defineProps(), {
    collapsible: 'icon',
})

// This is sample data.
const data = {
    navMain: [
        {
            title: 'Project',
            url: '#',
            icon: Presentation,
            isActive: true,
            items: [
                { title: 'All', url: '/projects' },
                { title: 'Create', url: 'projects/create' },
            ],
        },
        {
            title: 'Settings',
            url: '#',
            icon: Settings,
            items: [
                { title: 'General', url: '#' },
                { title: 'Team', url: '#' },
                { title: 'Billing', url: '#' },
                { title: 'Limits', url: '#' },
            ],
        },
    ],
}
</script>

<template>
    <Sidebar v-bind="props">
        <SidebarHeader>
            <Logo/>
        </SidebarHeader>
        <SidebarContent>
            <NavMain :items="data.navMain" />
        </SidebarContent>
        <SidebarFooter>
            <NavUser/>
        </SidebarFooter>
        <SidebarRail />
    </Sidebar>
</template>
