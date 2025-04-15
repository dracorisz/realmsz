<script setup>
import { Head } from "@inertiajs/vue3";
import Navigation from "@/Components/Navigation.vue";
import FrontFooter from "@/Components/FrontFooter.vue";
import process from 'process';

const assetUrl = import.meta.env.VITE_ASSET_URL;

defineProps({
  title: String,
  hasHero: {
    type: Boolean,
    default: false,
  },
});
</script>

<template>
  <Head :title="title" />
  <div class="relative flex h-full flex-col items-start justify-start bg-black">
    <img v-if="hasHero" :src="`${assetUrl}/images/backgrounds/background04.jpg`" alt="" class="absolute h-full w-full object-cover opacity-20" />
    <Navigation :canRegister="true" :canLogin="true" />
    <div class="flex w-full flex-1 h-full flex-col items-center justify-start bg-black">
      <slot name="content" />
    </div>
    <FrontFooter />
  </div>
</template>

