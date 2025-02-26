<script setup>
defineProps({
  shortcut: {
    type: String,
    required: true
  },
  label: {
    type: String,
    required: true
  }
});
</script>

<template>
  <span class="inline-flex items-center gap-2 text-xs text-white/50">
    <span class="bg-white/10 rounded px-2 ah-[24px] flex items-center justify-center font-mono">{{ shortcut }}</span>
    <span>{{ label }}</span>
  </span>
</template>