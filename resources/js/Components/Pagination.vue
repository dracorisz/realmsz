<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
  links: Array,
});
</script>

<template>
  <div class="flex items-center justify-center paginator">
    <template v-for="(link, i) in links" :key="i">
      <Link v-if="link.url" :href="link.url" v-html="link.label.includes('Previous') ? '&#10094;' : link.label.includes('Next') ? '&#10095;' : link.label" :class="['pagination-buttons as-[36px]', link.active ? 'bg-white/10' : 'bg-white/5']" />
      <span v-else :href="link.url" v-html="link.label.includes('Previous') ? '&#10094;' : link.label.includes('Next') ? '&#10095;' : link.label" class="disabled bg-white/5 text-white/10 pagination-buttons as-[36px]" />
    </template>
  </div>
</template>

<style scoped lang="pcss">
.paginator *:first-child {
  @apply rounded-l-lg;
}

.paginator *:last-child {
  @apply rounded-r-lg;
}

.pagination-buttons {
  @apply flex items-center justify-center text-xs border transition-colors duration-300 ease-in-out border-white/5 hover:bg-white/10 text-white/70 hover:border-white/10 hover:text-white;
}
</style>
