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
import axios from 'axios'
import { ref, watch } from 'vue'
import { usePage } from "@inertiajs/vue3";

const page = usePage()
const locale = ref(page.props.locale)

const languages = [
    { code: 'en', label: 'English' },
    { code: 'ar', label: 'العربية' },
]

// اربط القيمة الافتراضية:
const selectedLanguage = ref(locale.value)

function onLanguageChange(value) {
    console.log('Changing language to:', value)
    axios.post(`/change-language`, { language: value }, {
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
            "Content-type": "application/json",
        },
    })
        .then(response => {
            console.log('Language changed response:', response)
            // عادةً تعملي reload حتى تُطبق الترجمة
            window.location.reload()
        })
        .catch(error => {
            console.error('Error changing language:', error)
        });
}

// نراقب selectedLanguage:
watch(selectedLanguage, (newVal) => {
    onLanguageChange(newVal)
})
</script>

 <template>
     <div class="flex items-center gap-2 px-4">
         <Select v-model="selectedLanguage">
             <SelectTrigger class="w-[180px]">
                 <SelectValue placeholder="Select language" />
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

