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
import {useI18n} from "vue-i18n";
const { t } = useI18n()

const props = withDefaults(defineProps(), {
    collapsible: 'icon',
})

// This is sample data.
const data = {
    navMain: [
        {
            title: t('users-manage'),
            url: '#',
            icon: Presentation,
            isActive: true,
            items: [
                { title: t('all'), url: '/users' },
                { title: t('create-new'), url: 'users/create' },
            ],
        },
        {
            title: t('settings'),
            url: '#',
            icon: Settings,
            items: [
                { title: t('settings-general'), url: '/settings' },
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
