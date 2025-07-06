<script>
export const description = 'A sidebar that collapses to icons.'
export const iframeHeight = '800px'
export const containerClass = 'w-full h-full'
</script>

<script setup>
import AppSidebar from '@/Layouts/Components/AppSidebar.vue'
import SelectedLanguage from '@/Layouts/Components/SelectedLanguage.vue'
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from '@/Components/ui/breadcrumb'
import { Separator } from '@/Components/ui/separator'
import {
    SidebarInset,
    SidebarProvider,
    SidebarTrigger,
} from '@/Components/ui/sidebar'

import { useBreadcrumbStore } from '@/Stores/breadcrumb'
import { computed } from 'vue'

const breadcrumbStore = useBreadcrumbStore()
const breadcrumbItems = computed(() => breadcrumbStore.items)

</script>

<template>
    <SidebarProvider>
        <AppSidebar />
        <SidebarInset>
            <header class="flex h-16 shrink-0 items-center gap-2 transition-[width,height] ease-linear group-has-[[data-collapsible=icon]]/sidebar-wrapper:h-12
                justify-between">
                <div class="flex items-center gap-2 px-4">
                    <SidebarTrigger class="-ml-1" />
                    <Separator orientation="vertical" class="mr-2 h-4" />
                    <Breadcrumb>
                        <BreadcrumbList>
                            <BreadcrumbItem v-for="(item, idx) in breadcrumbItems" :key="idx">
                                <BreadcrumbLink v-if="item.url" :href="item.url">
                                    {{ item.label }}
                                </BreadcrumbLink>
                                <BreadcrumbPage v-else>
                                    {{ item.label }}
                                </BreadcrumbPage>
                                <BreadcrumbSeparator v-if="idx !== breadcrumbItems.length - 1" class="hidden md:block" />
                            </BreadcrumbItem>
                        </BreadcrumbList>
                    </Breadcrumb>
                </div>
                <SelectedLanguage/>
            </header>
            <slot/>
        </SidebarInset>

    </SidebarProvider>
</template>
