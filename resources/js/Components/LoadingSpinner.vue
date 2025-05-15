<script setup>
import { computed } from 'vue'

const props = defineProps({
  size: {
    type: String,
    default: 'md', // 'xs', 'sm', 'md', 'lg'
  },
  color: {
    type: String,
    default: 'white', // 'white', 'gray', 'indigo', 'primary'
  },
  inline: {
    type: Boolean,
    default: false
  }
})

const sizeClasses = computed(() => {
  const sizes = {
    'xs': 'w-3 h-3',
    'sm': 'w-4 h-4',
    'md': 'w-5 h-5',
    'lg': 'w-6 h-6',
  }
  return sizes[props.size] || sizes.md
})

const colorClasses = computed(() => {
  const colors = {
    'white': 'text-white',
    'gray': 'text-gray-400',
    'indigo': 'text-indigo-500',
    'primary': 'text-indigo-600',
  }
  return colors[props.color] || colors.white
})
</script>

<template>
  <div
    :class="[
      'loading-spinner',
      inline ? 'inline-flex' : 'flex',
      inline ? 'mr-2' : ''
    ]"
  >
    <svg
      :class="[sizeClasses, colorClasses, 'animate-spin']"
      xmlns="http://www.w3.org/2000/svg"
      fill="none"
      viewBox="0 0 24 24"
      aria-hidden="true"
    >
      <circle
        class="opacity-25"
        cx="12"
        cy="12"
        r="10"
        stroke="currentColor"
        stroke-width="4"
      ></circle>
      <path
        class="opacity-75"
        fill="currentColor"
        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
      ></path>
    </svg>
  </div>
</template>

<style scoped>
@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

.animate-spin {
  animation: spin 1s linear infinite;
}

.loading-spinner {
  align-items: center;
  justify-content: center;
}
</style> 