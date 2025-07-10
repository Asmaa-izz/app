<script setup>
import {Button} from '@/Components/ui/button'
import {Card, CardContent, CardHeader, CardTitle,} from '@/Components/ui/card'
import {Input} from '@/Components/ui/input'
import {Label} from '@/Components/ui/label'
import AuthLayout from '@/Layouts/AuthLayout.vue'
import {Head, Link, useForm} from "@inertiajs/vue3";

import {useI18n} from 'vue-i18n'
const {t} = useI18n()

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

</script>

<template>
    <Head title="Register" />

    <AuthLayout>
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
                                    <Label html-for="name">{{ t('name') }}</Label>
                                    <Input
                                        id="name"
                                        v-model="form.name"
                                        placeholder="name"
                                        required
                                        type="text"
                                    />
                                    <div v-show="form.errors.name">
                                        <p class="text-sm text-red-600">
                                            {{ form.errors.name }}
                                        </p>
                                    </div>

                                </div>
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
                                    <Input id="password" required type="password"   v-model="form.password"/>
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
                                    {{ t('Register') }}
                                </Button>
                            </div>
                            <div class="text-center text-sm">

                                <Link class="underline underline-offset-4" :href="route('login')">
                                    {{ t("Already registered?") }}
                                </Link>
                            </div>

                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AuthLayout>
</template>
