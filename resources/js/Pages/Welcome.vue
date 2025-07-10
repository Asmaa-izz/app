<script setup>
import {usePage, Link, router} from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { Button } from '@/Components/ui/button'

const { t } = useI18n()
const page = usePage()
const isAuthenticated = page.props.auth?.user !== null
</script>

<template>
    <div class="flex flex-col min-h-screen">

        <!-- NavBar -->
        <nav class="flex items-center justify-between px-6 py-4 shadow-md bg-white">
            <div class="text-xl font-bold">MyApp</div>
            <div>
                <Link v-if="isAuthenticated" :href="route('dashboard')">
                    <Button>{{ t('dashboard') }}</Button>
                </Link>
                <Link v-else :href="route('login')">
                    <Button variant="outline">{{ t('login') }}</Button>
                </Link>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="flex flex-col items-center justify-center text-center py-20 bg-gradient-to-b from-gray-100 to-white flex-grow">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ t('welcome_title') }}</h1>
            <p class="text-lg md:text-xl text-gray-600 mb-6">{{ t('welcome_description') }}</p>
            <Button size="lg">{{ t('start_now') }}</Button>
        </section>

        <!-- Features Section -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6 px-6 py-16 max-w-6xl mx-auto">
            <div class="p-6 shadow rounded-2xl bg-white text-center">
                <h3 class="text-xl font-semibold mb-2">{{ t('feature_easy') }}</h3>
                <p class="text-gray-600">{{ t('feature_easy_desc') }}</p>
            </div>
            <div class="p-6 shadow rounded-2xl bg-white text-center">
                <h3 class="text-xl font-semibold mb-2">{{ t('feature_powerful') }}</h3>
                <p class="text-gray-600">{{ t('feature_powerful_desc') }}</p>
            </div>
            <div class="p-6 shadow rounded-2xl bg-white text-center">
                <h3 class="text-xl font-semibold mb-2">{{ t('feature_integration') }}</h3>
                <p class="text-gray-600">{{ t('feature_integration_desc') }}</p>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="text-center py-16 bg-gray-50">
            <h2 class="text-3xl font-bold mb-4">{{ t('cta_title') }}</h2>
            <p class="text-gray-600 mb-6">{{ t('cta_description') }}</p>
            <Button size="lg">{{ t('start_now') }}</Button>
        </section>

    </div>
</template>
