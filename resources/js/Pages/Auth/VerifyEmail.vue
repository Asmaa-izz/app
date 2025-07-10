<script setup>
import {Button} from '@/Components/ui/button'
import {Card, CardContent, CardHeader,} from '@/Components/ui/card'
import {Input} from '@/Components/ui/input'
import {Label} from '@/Components/ui/label'

import AuthLayout from '@/Layouts/AuthLayout.vue'

import {useI18n} from 'vue-i18n'

const {t} = useI18n()

import {Head, Link, useForm} from "@inertiajs/vue3";
import {CardDescription} from "@/Components/ui/card/index.js";
import {computed} from "vue";

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);

</script>

<template>
    <Head title="Email Verification" />

    <AuthLayout>

        <div class="flex flex-col gap-6">
            <Card>
                <CardHeader class="text-center">
                    <CardDescription>
                        Thanks for signing up! Before getting started, could you verify your
                        email address by clicking on the link we just emailed to you? If you
                        didn't receive the email, we will gladly send you another.
                    </CardDescription>
                </CardHeader>
                <CardContent>

                    <div
                        class="mb-4 text-sm font-medium text-green-600"
                        v-if="verificationLinkSent"
                    >
                        A new verification link has been sent to the email address you
                        provided during registration.
                    </div>

                    <form @submit.prevent="submit">
                        <div class="grid gap-6">
                            <div class="grid gap-6">
                                <Button class="w-full" type="submit"
                                        :class="{ 'opacity-25': form.processing }"
                                        :disabled="form.processing">
                                    {{ t('Resend Verification Email') }}
                                </Button>
                            </div>
                            <div class="text-center text-sm">
                                <Link class="underline underline-offset-4" :href="route('logout')">
                                    {{ t('Log Out') }}
                                </Link>
                            </div>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AuthLayout>
</template>
