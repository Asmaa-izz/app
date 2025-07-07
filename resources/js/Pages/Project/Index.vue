<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useBreadcrumbStore } from '@/Stores/breadcrumb'
import { useI18n } from 'vue-i18n'
const { t } = useI18n()

const breadcrumbStore = useBreadcrumbStore()

breadcrumbStore.setItems([
    { label: t('home'), url: '/dashboard' },
    { label: 'Projects' }
])
</script>

<template>
    <Head title="Dashboard" />
    <AppLayout>
        <div class="flex flex-1 flex-col gap-4 p-4 pt-0">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="aspect-video rounded-xl bg-muted/50" />
                <div class="aspect-video rounded-xl bg-muted/50" />
                <div class="aspect-video rounded-xl bg-muted/50" />
            </div>
            <div class="min-h-[100vh] flex-1 rounded-xl bg-muted/50 md:min-h-min" />
        </div>
    </AppLayout>
</template>
