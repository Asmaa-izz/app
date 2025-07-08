<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {Head} from '@inertiajs/vue3';
import {useBreadcrumbStore} from '@/Stores/breadcrumb'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/Components/ui/table"
import { Button } from "@/Components/ui/button"
import {useI18n} from 'vue-i18n'

const {t} = useI18n()

const breadcrumbStore = useBreadcrumbStore()

breadcrumbStore.setItems([
    {label: t('home'), url: '/dashboard'},
    {label: t('users')}
])

const props = defineProps({
    users: Array
})
</script>

<template>
    <Head title="Dashboard"/>
    <AppLayout>
        <div class="p-4">
            <h1 class="text-xl font-semibold mb-4">Users</h1>

            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead class="text-start">Name</TableHead>
                        <TableHead class="text-end">Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="user in users" :key="user.id">
                        <TableCell class="text-start">{{ user.name }}</TableCell>
                        <TableCell class="flex justify-end space-x-2">
                            <Button v-if="user.can_update" variant="outline" size="sm">Edit</Button>
                            <Button v-if="user.can_delete" variant="destructive" size="sm">Delete</Button>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

    </AppLayout>
</template>
