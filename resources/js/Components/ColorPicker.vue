<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import { EyeDropperIcon, CheckIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
  modelValue: {
    type: String,
    default: '#4f46e5' // Default indigo color
  },
  colors: {
    type: Array,
    default: () => [
      '#ef4444', // red
      '#f97316', // orange
      '#f59e0b', // amber
      '#eab308', // yellow
      '#84cc16', // lime
      '#22c55e', // green
      '#10b981', // emerald
      '#14b8a6', // teal
      '#06b6d4', // cyan
      '#0ea5e9', // sky
      '#3b82f6', // blue
      '#6366f1', // indigo
      '#8b5cf6', // violet
      '#a855f7', // purple
      '#d946ef', // fuchsia
      '#ec4899', // pink
      '#f43f5e', // rose
      '#0f172a', // slate-900
      '#18181b', // zinc-900
      '#ffffff', // white
    ]
  },
  label: {
    type: String,
    default: 'Color'
  },
  size: {
    type: String,
    default: 'md' // sm, md, lg
  },
  disabled: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['update:modelValue', 'change']);

const isOpen = ref(false);
const colorPickerRef = ref(null);
const customColor = ref(props.modelValue);
const previewColor = computed(() => props.modelValue);

// Size classes based on prop
const sizeClasses = computed(() => {
  switch(props.size) {
    case 'sm': return 'h-6 w-6';
    case 'lg': return 'h-10 w-10';
    default: return 'h-8 w-8';
  }
});

// Watch for external changes
watch(() => props.modelValue, (newValue) => {
  customColor.value = newValue;
});

// Handle picking a preset color
const selectColor = (color) => {
  emit('update:modelValue', color);
  emit('change', color);
  isOpen.value = false;
};

// Handle custom color input
const updateCustomColor = () => {
  emit('update:modelValue', customColor.value);
  emit('change', customColor.value);
};

// Toggle the color picker dropdown
const toggleColorPicker = () => {
  if (props.disabled) return;
  isOpen.value = !isOpen.value;
};

// Handle clicking outside to close the dropdown
const handleClickOutside = (event) => {
  if (colorPickerRef.value && !colorPickerRef.value.contains(event.target)) {
    isOpen.value = false;
  }
};

// Handle keyboard events
const handleKeyDown = (event) => {
  if (event.key === 'Escape') {
    isOpen.value = false;
  } else if (event.key === 'Enter' || event.key === ' ') {
    if (!isOpen.value) {
      event.preventDefault();
      toggleColorPicker();
    }
  }
};

// Use browser's eyedropper API if available
const useEyeDropper = async () => {
  if (!window.EyeDropper) {
    alert('EyeDropper API is not supported in your browser');
    return;
  }
  
  try {
    const eyeDropper = new window.EyeDropper();
    const result = await eyeDropper.open();
    customColor.value = result.sRGBHex;
    updateCustomColor();
  } catch (e) {
    console.error('EyeDropper error:', e);
  }
};

// Lifecycle hooks
onMounted(() => {
  document.addEventListener('mousedown', handleClickOutside);
  document.addEventListener('keydown', handleKeyDown);
});

onUnmounted(() => {
  document.removeEventListener('mousedown', handleClickOutside);
  document.removeEventListener('keydown', handleKeyDown);
});
</script>

<template>
  <div ref="colorPickerRef" class="relative">
    

        <div class="flex items-center w-full flex-wrap gap-2 mb-3">
          <button
            v-for="color in colors"
            :key="color"
            type="button"
            :style="{ backgroundColor: color }"
            class="h-6 w-6 rounded border border-gray-300 dark:border-gray-600 transition-shadow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 dark:focus:ring-offset-gray-800"
            :class="{ 'ring-2 ring-offset-2 ring-indigo-500 dark:ring-indigo-400 dark:ring-offset-gray-800': color === modelValue }"
            @click="selectColor(color)"
            :aria-label="`Select color ${color}`"
            :aria-selected="color === modelValue"
          >
            <CheckIcon 
              v-if="color === modelValue" 
              class="h-4 w-4 text-white dark:text-black mx-auto"
              :class="{ 'text-black': isLightColor(color) }"
            />
          </button>
        </div>
        
        <!-- Custom Color Input -->
        <div class="flex items-center space-x-2">
          <input
            v-model="customColor"
            type="text"
            class="px-2 py-1 text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400"
            @input="updateCustomColor"
            maxlength="9"
            aria-label="Custom color hexadecimal value"
          />
          
          <input
            v-model="customColor"
            type="color"
            class="h-8 w-8 rounded overflow-hidden cursor-pointer"
            @input="updateCustomColor"
            aria-label="Color picker"
          />
          
          <button
            v-if="typeof window !== 'undefined' && 'EyeDropper' in window"
            type="button"
            class="h-8 w-8 flex items-center justify-center rounded-md text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400"
            @click="useEyeDropper"
            aria-label="Pick color from screen"
          >
            <EyeDropperIcon class="h-5 w-5" />
          </button>
        </div>
      </div>
    
</template>

<script>
export default {
  methods: {
    isLightColor(hexColor) {
      // Remove # if present
      hexColor = hexColor.replace('#', '');
      
      // Convert to RGB
      let r = parseInt(hexColor.substr(0, 2), 16) || 0;
      let g = parseInt(hexColor.substr(2, 2), 16) || 0;
      let b = parseInt(hexColor.substr(4, 2), 16) || 0;
      
      // Calculate perceived brightness using the formula: (R * 299 + G * 587 + B * 114) / 1000
      const brightness = (r * 299 + g * 587 + b * 114) / 1000;
      
      // Return true if the color is light (brightness > 128)
      return brightness > 128;
    }
  }
}
</script>

<style scoped>
/* Hide browser's default color picker UI */
input[type="color"] {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  background-color: transparent;
  border: none;
  cursor: pointer;
}

input[type="color"]::-webkit-color-swatch-wrapper {
  padding: 0;
}

input[type="color"]::-webkit-color-swatch {
  border: none;
  border-radius: 4px;
}

input[type="color"]::-moz-color-swatch {
  border: none;
  border-radius: 4px;
}

/* Add sliding animation for dropdown */
[role="dialog"] {
  animation: slideDown 0.15s ease-out forwards;
  transform-origin: top center;
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-5px) scale(0.98);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}
</style>
