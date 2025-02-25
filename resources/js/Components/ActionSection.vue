<script setup>
import SectionTitle from "./SectionTitle.vue";

defineProps({
  name: String,
});
</script>

<template>
  <div class="flex flex-col gap-5 rounded-2xl p-10 border text-white" :class="name == 'Delete Account' ? 'bg-warning/10 border-warning/20' : 'bg-white/5 border-white/5'">
    <SectionTitle>
      <template #title>
        <slot name="title" />
      </template>
      <template #description>
        <slot name="description" />
      </template>
    </SectionTitle>
    <div class="rounded-2xl text-sm">
      <slot name="content" />
    </div>
  </div>
</template>

