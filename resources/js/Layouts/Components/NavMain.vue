<script setup>
import { ChevronRight, ChevronLeft } from 'lucide-vue-next'
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from '@/Components/ui/collapsible'

import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
} from '@/Components/ui/sidebar'

import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

// props القادِمة من الأب
const props = defineProps({
    items: Array,
})

// معلومات الصفحة الحالية
const page = usePage()
const currentUrl = computed(() => page.url)  // مثل: "/projects" أو "/settings"

// RTL or LTR
const isLTR = computed(() => page.props.locale === 'en');

// نحسب nav items مع isActive تلقائيًا
const navItemsWithActive = computed(() => {
    return props.items.map(item => {
        const hasActive = item.items.some(subItem =>
            currentUrl.value.startsWith(subItem.url)
        )
        return { ...item, isActive: hasActive }
    })
})
</script>

<template>
    <SidebarGroup>
        <SidebarGroupLabel>List</SidebarGroupLabel>
        <SidebarMenu>
            <Collapsible
                v-for="item in navItemsWithActive"
                :key="item.title"
                as-child
                :default-open="item.isActive"
                class="group/collapsible"
            >
                <SidebarMenuItem>
                    <CollapsibleTrigger as-child>
                        <SidebarMenuButton :tooltip="item.title">
                            <component :is="item.icon" v-if="item.icon" />
                            <span>{{ item.title }}</span>
                            <ChevronRight
                                v-if="isLTR"
                                class="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90"
                            />
                            <ChevronLeft
                                v-else
                                class="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90"
                            />
                        </SidebarMenuButton>
                    </CollapsibleTrigger>
                    <CollapsibleContent>
                        <SidebarMenuSub>
                            <SidebarMenuSubItem
                                v-for="subItem in item.items"
                                :key="subItem.title"
                            >
                                <SidebarMenuSubButton as-child>
                                    <a :href="subItem.url">
                                        <span>{{ subItem.title }}</span>
                                    </a>
                                </SidebarMenuSubButton>
                            </SidebarMenuSubItem>
                        </SidebarMenuSub>
                    </CollapsibleContent>
                </SidebarMenuItem>
            </Collapsible>
        </SidebarMenu>
    </SidebarGroup>
</template>
