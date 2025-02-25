<script setup>
defineProps({
  value: String,
});
</script>

<template>
  <label class="block mb-px ml-px text-xs text-white/70">
    <span v-if="value">{{ value }}</span>
    <span v-else>
      <slot />
    </span>
  </label>
</template>
