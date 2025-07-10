<script setup>
import {Button} from '@/Components/ui/button'
import {Card, CardContent, CardHeader, CardTitle,} from '@/Components/ui/card'
import {Input} from '@/Components/ui/input'
import {Label} from '@/Components/ui/label'

import AuthLayout from '@/Layouts/AuthLayout.vue'
import { Checkbox } from "@/Components/ui/checkbox";

import {useI18n} from 'vue-i18n'
const {t} = useI18n()
import {Head, Link, useForm} from "@inertiajs/vue3";

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

</script>

<template>
    <Head title="Reset Password" />

    <AuthLayout>

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <div class="flex flex-col gap-6">
            <Card>
                <CardContent>
                    <form @submit.prevent="submit">
                        <div class="grid gap-6">
                            <div class="grid gap-6">
                                <div class="grid gap-2">
                                    <Label html-for="email">{{ t('email') }}</Label>
                                    <Input
                                        id="email"
                                        v-model="form.email"
                                        placeholder="m@example.com"
                                        required
                                        type="email"
                                    />
                                    <div v-show="form.errors.email">
                                        <p class="text-sm text-red-600">
                                            {{ form.errors.email }}
                                        </p>
                                    </div>

                                </div>
                                <div class="grid gap-2">
                                    <Label html-for="password">{{ t('password') }}</Label>
                                    <Input id="password" required type="password" v-model="form.password"/>
                                    <div v-show="form.errors.password">
                                        <p class="text-sm text-red-600">
                                            {{ form.errors.password }}
                                        </p>
                                    </div>
                                </div>
                                <div class="grid gap-2">
                                    <Label html-for="password_confirmation">{{ t('password_confirmation') }}</Label>
                                    <Input id="password_confirmation" required type="password"   v-model="form.password_confirmation"/>
                                    <div v-show="form.errors.password_confirmation">
                                        <p class="text-sm text-red-600">
                                            {{ form.errors.password_confirmation }}
                                        </p>
                                    </div>
                                </div>

                                <Button class="w-full" type="submit"
                                        :class="{ 'opacity-25': form.processing }"
                                        :disabled="form.processing">
                                    {{ t(' Reset Password') }}
                                </Button>
                            </div>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AuthLayout>
</template>
