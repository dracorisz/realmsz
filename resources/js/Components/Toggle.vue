<script setup>
defineProps({
  name: {
    type: String,
    default: "Toggle",
  },
  label: {
    type: String,
    default: "Toggle",
  },
  active: {
    type: Boolean,
    default: true,
  },
});

const emit = defineEmits(["update:active"]);
</script>

<template>
  <div class="flex items-center gap-3" @click="emit('update:active', active)">
    <input type="checkbox" :id="name" :name="name" :checked="active" class="sr-only" aria-hidden="true" />
    <div class="relative inline-flex h-5 w-10 flex-shrink-0 cursor-pointer rounded-full border border-white/10 transition-colors duration-300 ease-in-out items-center justify-center" :class="[active ? 'bg-white/20' : 'bg-white/10']">
      <span aria-hidden="true" class="pointer-events-none inline-block h-3 w-3 transform rounded-full bg-white/70 shadow-lg ring-0 -ml-5 transition duration-300 ease-in-out" :class="[active ? 'translate-x-5' : 'translate-x-0']" />
    </div>
    <label :for="name" :class="['cursor-pointer text-xs', active ? 'text-white' : 'text-white/90']">{{ label }}</label>
  </div>
</template>
