<script setup>
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from '@/Components/ui/select'

import { ref } from 'vue'

const languages = [
    { code: 'en', label: 'English' },
    { code: 'ar', label: 'العربية' },
]

const selectedLanguage = ref(languages[0].code)

function onLanguageChange(value) {
    selectedLanguage.value = value
    console.log('Selected language:', value)
}
</script>

<template>
    <div class="flex items-center gap-2 px-4">
    <Select v-model="selectedLanguage" @change="onLanguageChange">
        <SelectTrigger class="w-[180px]">
            <SelectValue placeholder="select" />
        </SelectTrigger>
        <SelectContent>
            <SelectGroup>
                <SelectItem
                    v-for="lang in languages"
                    :key="lang.code"
                    :value="lang.code"
                >
                    {{ lang.label }}
                </SelectItem>
            </SelectGroup>
        </SelectContent>
    </Select>
    </div>
</template>
