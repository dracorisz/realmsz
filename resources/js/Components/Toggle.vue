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
    default: false,
  },
  disabled: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(["update:active"]);

const toggleActive = () => {
  if (!props.disabled) {
    emit('update:active', !props.active);
  }
};
</script>

<template>
  <div 
    class="flex items-center gap-3" 
    @click="toggleActive"
    :class="[disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer']"
  >
    <input 
      type="checkbox" 
      :id="name" 
      :name="name" 
      :checked="active" 
      :disabled="disabled"
      class="sr-only" 
      aria-hidden="true" 
    />
    <div 
      class="relative inline-flex h-6 w-11 flex-shrink-0 rounded-full border border-white/10 transition-colors duration-200 ease-in-out items-center justify-center focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 focus-within:ring-offset-gray-900" 
      :class="[active ? 'bg-indigo-500' : 'bg-white/10']"
      role="switch"
      :aria-checked="active"
      tabindex="0"
      @keydown.space.prevent="toggleActive"
    >
      <span 
        aria-hidden="true" 
        class="pointer-events-none absolute left-0 inline-block h-5 w-5 transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out" 
        :class="[
          active ? 'translate-x-5' : 'translate-x-0',
          disabled ? 'bg-white/50' : 'bg-white'
        ]" 
      />
    </div>
    <label 
      :for="name" 
      :class="[
        'text-sm', 
        active ? 'text-white' : 'text-white/70',
        disabled ? 'cursor-not-allowed' : 'cursor-pointer'
      ]"
    >
      {{ label }}
    </label>
  </div>
</template>

<style scoped>
/* Animation for toggle switch */
.relative span {
  transition: transform 0.2s ease-in-out, background-color 0.2s ease-in-out;
}

.relative:active span {
  width: 28px;
  transform: translateX(1px);
}

.relative:active span.translate-x-5 {
  transform: translateX(8px);
}

/* Focus styles */
.relative:focus-within {
  outline: 2px solid transparent;
  outline-offset: 2px;
  box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.25);
}
</style>
