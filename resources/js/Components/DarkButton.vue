<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { LoadingSpinner } from '@/Components'

const props = defineProps({
  type: {
    type: String,
    default: 'button',
  },
  href: {
    type: String,
    default: null,
  },
  variant: {
    type: String,
    default: 'primary', // 'primary', 'secondary', 'outline', 'danger', 'success', 'link'
  },
  size: {
    type: String,
    default: 'md', // 'sm', 'md', 'lg'
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  loading: {
    type: Boolean,
    default: false,
  },
  full: {
    type: Boolean,
    default: false,
  },
  rounded: {
    type: Boolean,
    default: false,
  },
  icon: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['click'])

const handleClick = (event) => {
  if (props.disabled || props.loading) {
    event.preventDefault()
    return
  }
  emit('click', event)
}

const classes = computed(() => {
  const base = 'inline-flex items-center justify-center transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-offset-gray-900 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed'
  
  // Size classes
  const sizeClasses = {
    'sm': 'text-xs px-2.5 py-1.5',
    'md': 'text-sm px-4 py-2',
    'lg': 'text-base px-6 py-3',
  }
  
  // Icon button sizes
  const iconSizeClasses = {
    'sm': 'p-1',
    'md': 'p-2',
    'lg': 'p-3',
  }
  
  // Variant classes
  const variantClasses = {
    'primary': 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm',
    'secondary': 'bg-gray-700 hover:bg-gray-600 text-white shadow-sm',
    'outline': 'border border-gray-600 hover:bg-gray-700 text-gray-300 bg-transparent',
    'danger': 'bg-red-600 hover:bg-red-700 text-white shadow-sm',
    'success': 'bg-green-600 hover:bg-green-700 text-white shadow-sm',
    'link': 'bg-transparent text-indigo-400 hover:text-indigo-300 hover:underline shadow-none',
  }
  
  // Round shape
  const roundedClass = props.rounded ? 'rounded-full' : 'rounded-md'
  
  // Full width
  const fullClass = props.full ? 'w-full' : ''
  
  return [
    base,
    props.icon ? iconSizeClasses[props.size] : sizeClasses[props.size],
    variantClasses[props.variant],
    roundedClass,
    fullClass,
  ].join(' ')
})
</script>

<template>
  <component 
    :is="href ? Link : 'button'" 
    :href="href" 
    :type="href ? undefined : type"
    :disabled="disabled || loading"
    :class="classes"
    @click="handleClick"
  >
    <LoadingSpinner 
      v-if="loading" 
      :size="size === 'sm' ? 'xs' : size === 'md' ? 'sm' : 'md'" 
      class="mr-2" 
      :color="variant === 'outline' ? 'gray' : 'white'"
    />
    
    <slot></slot>
  </component>
</template> 