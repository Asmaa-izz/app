<script setup>
import {Button} from '@/Components/ui/button'
import {Card, CardContent, CardHeader,} from '@/Components/ui/card'
import {Input} from '@/Components/ui/input'
import {Label} from '@/Components/ui/label'

import AuthLayout from '@/Layouts/AuthLayout.vue'

import {useI18n} from 'vue-i18n'

const {t} = useI18n()

import {Head, useForm} from "@inertiajs/vue3";
import {CardDescription} from "@/Components/ui/card/index.js";

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};

</script>

<template>
    <Head title="Forgot Password" />

    <AuthLayout>

        <div class="flex flex-col gap-6">
            <Card>
                <CardHeader class="text-center">
                    <CardDescription>
                        Forgot your password? No problem. Just let us know your email
                        address and we will email you a password reset link that will allow
                        you to choose a new one.
                    </CardDescription>
                </CardHeader>
                <CardContent>

                    <div
                        v-if="status"
                        class="mb-4 text-sm font-medium text-green-600"
                    >
                        {{ status }}
                    </div>

                    <form @submit.prevent="submit">
                        <div class="grid gap-6">
                            <div class="grid gap-6">
                                <div class="grid gap-2">
                                    <Label html-for="email">{{ t('email') }}</Label>
                                    <Input id="email" required type="email" v-model="form.email"/>
                                    <div v-show="form.errors.email">
                                        <p class="text-sm text-red-600">
                                            {{ form.errors.email }}
                                        </p>
                                    </div>
                                </div>
                                <Button class="w-full" type="submit"
                                        :class="{ 'opacity-25': form.processing }"
                                        :disabled="form.processing">
                                    {{ t('Email Password Reset Link') }}
                                </Button>
                            </div>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AuthLayout>
</template>
