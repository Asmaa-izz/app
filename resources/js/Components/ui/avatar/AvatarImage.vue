<script setup>
import { AvatarImage } from "reka-ui";

const props = defineProps({
  src: { type: String, required: true },
  referrerPolicy: { type: null, required: false },
  crossOrigin: { type: null, required: false },
  asChild: { type: Boolean, required: false },
  as: { type: null, required: false },
});
</script>

<template>
  <AvatarImage v-bind="props" class="h-full w-full object-cover">
    <slot />
  </AvatarImage>
</template>
