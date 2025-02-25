<script setup>
import { computed, useSlots } from "vue";
import SectionTitle from "./SectionTitle.vue";

defineEmits(["submitted"]);

const hasActions = computed(() => !!useSlots().actions);
</script>

<template>
  <div class="flex flex-col gap-5 bg-white/5 p-10 rounded-2xl">
    <SectionTitle>
      <template #title>
        <slot name="title" />
      </template>
      <template #description>
        <slot name="description" />
      </template>
    </SectionTitle>

    <form @submit.prevent="$emit('submitted')">
      <div class="" :class="hasActions ? 'sm:rounded-tl-lg sm:rounded-tr-lg' : 'sm:rounded-2xl'">
        <div class="grid grid-cols-6 gap-6">
          <slot name="form" />
        </div>
      </div>

      <div v-if="hasActions" class="flex items-center justify-end text-right sm:rounded-bl-lg sm:rounded-br-lg">
        <slot name="actions" />
      </div>
    </form>
  </div>
</template>
