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


defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

</script>

<template>
    <Head title="Log in"/>

    <AuthLayout>

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <div class="flex flex-col gap-6">
            <Card>
                <CardHeader class="text-center">
                    <CardTitle class="text-xl">
                        {{ t('Welcome back') }}
                    </CardTitle>
                </CardHeader>
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
                                    <div class="flex items-center">
                                        <Label html-for="password">{{ t('password') }}</Label>
                                        <Link
                                            v-if="canResetPassword"
                                            :href="route('password.request')"
                                            class="ml-auto text-sm underline-offset-4 hover:underline"
                                        >
                                            {{ t('Forgot your password?') }}
                                        </Link>
                                    </div>
                                    <Input id="password" required type="password"   v-model="form.password"/>
                                    <div v-show="form.errors.password">
                                        <p class="text-sm text-red-600">
                                            {{ form.errors.password }}
                                        </p>
                                    </div>
                                </div>
                                <div class="grid gap-2">
                                    <div class="flex items-center space-x-2">
                                        <Checkbox id="remember" name="remember" v-model:checked="form.remember"  />
                                        <label
                                            for="remember"
                                            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                        >
                                            {{ t('Remember me') }}
                                        </label>
                                    </div>
                                </div>
                                <Button class="w-full" type="submit"
                                        :class="{ 'opacity-25': form.processing }"
                                        :disabled="form.processing">
                                    {{ t('login') }}
                                </Button>
                            </div>
                            <div class="text-center text-sm">
                                {{ t("don't-have-an-account?") }}
                                <Link class="underline underline-offset-4" :href="route('register')">
                                    {{ t('sign-up') }}
                                </Link>
                            </div>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AuthLayout>
</template>
