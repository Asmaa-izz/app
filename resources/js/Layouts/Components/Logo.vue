<script setup>
import {
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/Components/ui/sidebar'

import {
    BookOpen,
} from 'lucide-vue-next'

const props = defineProps({
    data: Object,
})
</script>

<template>
    <SidebarMenu>
        <SidebarMenuItem>
            <SidebarMenuButton
                size="lg"
                class="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground"
            >
                <div class="flex aspect-square size-8 items-center justify-center rounded-lg bg-sidebar-primary text-sidebar-primary-foreground">
                    <component :is="BookOpen" class="size-4" />
                </div>
                <a href="/dashboard" class="grid flex-1 text-left text-sm leading-tight">
                    <span class="truncate font-semibold">
                        Laravel
                    </span>
                    <span class="truncate text-xs">by asmaa</span>
                </a>
            </SidebarMenuButton>
        </SidebarMenuItem>
    </SidebarMenu>
</template>
