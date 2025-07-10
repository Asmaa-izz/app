<script setup>
import {Button} from '@/Components/ui/button'
import {Card, CardContent, CardHeader, CardTitle,} from '@/Components/ui/card'
import {Input} from '@/Components/ui/input'
import {Label} from '@/Components/ui/label'

import AuthLayout from '@/Layouts/AuthLayout.vue'

import {useI18n} from 'vue-i18n'
const {t} = useI18n()

import {Head, useForm} from "@inertiajs/vue3";
import {CardDescription} from "@/Components/ui/card/index.js";

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};

</script>

<template>
    <Head title="Confirm Password" />

    <AuthLayout>

        <div class="flex flex-col gap-6">
            <Card>
                <CardHeader class="text-center">
                   <CardDescription>
                       {{ t('This is a secure area of the application. Please confirm your password before continuing.') }}
                   </CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit">
                        <div class="grid gap-6">
                            <div class="grid gap-6">
                                <div class="grid gap-2">
                                    <Label html-for="password">{{ t('password') }}</Label>
                                    <Input id="password" required type="password"   v-model="form.password"/>
                                    <div v-show="form.errors.password">
                                        <p class="text-sm text-red-600">
                                            {{ form.errors.password }}
                                        </p>
                                    </div>
                                </div>
                                <Button class="w-full" type="submit"
                                        :class="{ 'opacity-25': form.processing }"
                                        :disabled="form.processing">
                                    {{ t('Confirm') }}
                                </Button>
                            </div>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AuthLayout>
</template>
