<script setup>
import { ChevronRight, ChevronLeft } from "lucide-vue-next";
import { cn } from "@/lib/utils";

import { usePage } from '@inertiajs/vue3'
import {computed} from "vue";
const page = usePage()
const locale = computed(() => page.props.locale)

const props = defineProps({
  class: { type: null, required: false },
});
</script>

<template>
  <li
    role="presentation"
    aria-hidden="true"
    :class="cn('[&>svg]:w-3.5 [&>svg]:h-3.5', props.class)"
  >
    <slot>
      <ChevronRight v-if="locale === 'en'"/>
      <ChevronLeft v-else/>
    </slot>
  </li>
</template>
