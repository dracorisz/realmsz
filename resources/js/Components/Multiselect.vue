<script setup>
import { ref, computed, watch } from 'vue';
import Multiselect from 'vue-multiselect';
import 'vue-multiselect/dist/vue-multiselect.css';

const props = defineProps({
  modelValue: {
    type: [Array, Object, String, Number],
    default: () => []
  },
  options: {
    type: Array,
    required: true,
    default: () => []
  },
  multiple: {
    type: Boolean,
    default: false
  },
  searchable: {
    type: Boolean,
    default: true
  },
  placeholder: {
    type: String,
    default: 'Select option'
  },
  trackBy: {
    type: String,
    default: 'id'
  },
  label: {
    type: String,
    default: 'name'
  },
  disabled: {
    type: Boolean,
    default: false
  },
  loading: {
    type: Boolean,
    default: false
  },
  error: {
    type: Boolean,
    default: false
  },
  required: {
    type: Boolean,
    default: false
  },
  clearOnSelect: {
    type: Boolean,
    default: true
  },
  closeOnSelect: {
    type: Boolean,
    default: true
  }
});

const emit = defineEmits(['update:modelValue', 'select', 'remove', 'search-change', 'open', 'close']);

const localValue = ref(props.modelValue);

// Computed properties
const computedOptions = computed(() => {
  // Ensure options are properly formatted
  if (!props.options) {
    console.warn('Multiselect: options is null or undefined', props.options);
    return [];
  }
  
  // Convert options to array if needed
  let optionsArray = [];
  
  // Handle null or undefined case
  if (props.options === null || props.options === undefined) {
    return [];
  }
  
  // Convert to array if not already
  if (!Array.isArray(props.options)) {
    try {
      // If it's a string that looks like JSON array
      if (typeof props.options === 'string' && 
          (props.options.trim().startsWith('[') && props.options.trim().endsWith(']'))) {
        optionsArray = JSON.parse(props.options);
      } else if (typeof props.options === 'string' && 
                (props.options.trim().startsWith('{') && props.options.trim().endsWith('}'))) {
        // Handle single object JSON string
        const parsed = JSON.parse(props.options);
        optionsArray = [parsed];
      } else {
        // If it's a single value, wrap in array
        optionsArray = [props.options];
      }
    } catch (e) {
      console.error('Error parsing options:', e);
      // If parsing fails, use as a single item array
      optionsArray = [props.options];
    }
  } else {
    optionsArray = props.options;
  }
  
  // Ensure we have an array
  if (!Array.isArray(optionsArray)) {
    return [];
  }
  
  // Process the options array
  return processOptions(optionsArray);
});

// Helper function to process options to the right format
const processOptions = (optionsArray) => {
  if (!Array.isArray(optionsArray)) {
    console.warn('processOptions: not an array', optionsArray);
    return [];
  }
  
  return optionsArray
    .filter(option => option !== null && option !== undefined)
    .map(option => {
      // Special case: Handle option being a string that looks like JSON
      if (typeof option === 'string' && option.trim().startsWith('{') && option.trim().endsWith('}')) {
        try {
          const parsed = JSON.parse(option);
          if (typeof parsed === 'object' && parsed !== null) {
            // Check if it has needed properties
            return {
              value: parsed.value || parsed.id || parsed[props.trackBy] || option,
              label: parsed.label || parsed[props.label] || parsed.name || parsed.value || String(option),
              $original: parsed
            };
          }
        } catch (e) {
          // Silently handle parse errors - just use the string as is
        }
      }
      
      // Handle case where option is a plain object
      if (typeof option === 'object' && option !== null) {
        return {
          value: option.id || option.value || option[props.trackBy] || JSON.stringify(option),
          label: option[props.label] || option.name || option.label || String(option.id || option.value || ''),
          $original: option
        };
      }
      
      // Simple string/number/boolean case
      return {
        value: option,
        label: String(option)
      };
    });
};

const selectedLabel = computed(() => {
  if (!localValue.value) return '';
  
  if (props.multiple) {
    return localValue.value
      .map(val => props.options.find(opt => opt[props.trackBy] === val)?.[props.label])
      .filter(Boolean)
      .join(', ');
  }
  
  const selected = props.options.find(opt => opt[props.trackBy] === localValue.value);
  return selected ? selected[props.label] : '';
});

// Improved getOptionLabel to handle more edge cases
const getOptionLabel = (option) => {
  if (!option) return '';
  
  // Handle string options
  if (typeof option === 'string') return option;
  
  // Handle number options
  if (typeof option === 'number') return String(option);
  
  // Handle object options
  if (typeof option === 'object' && option !== null) {
    // First check explicit label fields
    if (option.label) return option.label;
    
    // Then check the original object using the label prop
    if (option.$original) {
      if (option.$original[props.label]) return option.$original[props.label];
      if (option.$original.name) return option.$original.name;
      if (option.$original.label) return option.$original.label;
      if (option.$original.value) return String(option.$original.value);
      if (option.$original.id) return String(option.$original.id);
    }
    
    // Then check direct properties
    if (option[props.label]) return option[props.label];
    if (option.name) return option.name;
    if (option.value) return String(option.value);
    if (option.id) return String(option.id);
    
    // Try to stringify as last resort
    try {
      return JSON.stringify(option);
    } catch (e) {
      return '[Object]';
    }
  }
  
  // Last resort, convert to string
  return String(option);
};

// Methods
const handleSelect = (option) => {
  if (!option) {
    localValue.value = props.multiple ? [] : null;
  } else if (props.multiple) {
    // Fix for "option.map is not a function" error
    if (Array.isArray(option)) {
      localValue.value = option.map(opt => opt?.value !== undefined ? opt.value : opt);
    } else {
      // If a single option is somehow passed in a multiple context
      localValue.value = [option?.value !== undefined ? option.value : option];
    }
  } else {
    // Handle both object with value property and primitive values
    localValue.value = option?.value !== undefined ? option.value : option;
  }
  
  emit('update:modelValue', localValue.value);
  emit('select', option);
};

const handleRemove = (option) => {
  if (props.multiple) {
    const valueToRemove = option?.value !== undefined ? option.value : option;
    
    if (Array.isArray(localValue.value)) {
      localValue.value = localValue.value.filter(val => val !== valueToRemove);
    } else {
      localValue.value = [];
    }
  } else {
    localValue.value = null;
  }
  
  emit('update:modelValue', localValue.value);
  emit('remove', option);
};

const handleSearchChange = (query) => {
  emit('search-change', query);
};

const handleOpen = () => {
  emit('open');
};

const handleClose = () => {
  emit('close');
};

// Watch for external changes
watch(() => props.modelValue, (newValue) => {
  localValue.value = newValue;
}, { deep: true });
</script>

<template>
  <div class="multiselect-wrapper">
    <Multiselect
      v-model="localValue"
      :options="computedOptions"
      :multiple="multiple"
      :searchable="searchable"
      :placeholder="placeholder"
      :disabled="disabled"
      :loading="loading"
      :required="required"
      :clear-on-select="clearOnSelect"
      :close-on-select="closeOnSelect"
      :class="{ 'is-invalid': error }"
      class="multiselect-dark"
      @select="handleSelect"
      @remove="handleRemove"
      @search-change="handleSearchChange"
      @open="handleOpen"
      @close="handleClose"
    >
      <template #option="{ option }">
        <slot name="option" :option="option.$original || option">
          <span class="option-label">{{ getOptionLabel(option) }}</span>
        </slot>
      </template>
      
      <template #selected-option="{ option }">
        <slot name="selected-option" :option="option.$original || option">
          <span class="selected-option">{{ getOptionLabel(option) }}</span>
        </slot>
      </template>
      
      <template #no-options>
        <slot name="no-options">
          <div class="no-options">No options available</div>
        </slot>
      </template>
      
      <template #no-results>
        <slot name="no-results">
          <div class="no-results">No results found</div>
        </slot>
      </template>
      
      <template #placeholder>
        <slot name="placeholder">
          <span class="placeholder">{{ placeholder }}</span>
        </slot>
      </template>
      
      <template #loading>
        <slot name="loading">
          <div class="loading">Loading...</div>
        </slot>
      </template>
    </Multiselect>
  </div>
</template>

<style lang="postcss">
.multiselect-wrapper {
  @apply relative w-full;
}

.multiselect-dark {
  --ms-bg: theme('colors.gray.900');
  --ms-border-color: theme('colors.white / 10%');
  --ms-dropdown-bg: theme('colors.gray.900');
  --ms-option-bg-selected: theme('colors.indigo.500 / 10%');
  --ms-option-color-selected: theme('colors.white');
  --ms-tag-bg: theme('colors.indigo.500 / 10%');
  --ms-tag-color: theme('colors.white');
  --ms-option-bg-selected-pointed: theme('colors.indigo.500 / 20%');
  --ms-option-bg-pointed: theme('colors.white / 5%');
  --ms-option-color-pointed: theme('colors.white');
  --ms-option-color: theme('colors.white / 70%');
  --ms-spinner-color: theme('colors.indigo.500');

  @apply rounded-lg border border-white/10 bg-gray-900;

  .multiselect-search {
    @apply bg-transparent text-white placeholder-white/50;
  }

  .multiselect-tags {
    @apply bg-transparent border-0 min-h-[42px] py-0 px-3;
  }

  .multiselect-tag {
    @apply bg-indigo-500/10 text-white border-0 rounded;
  }

  .multiselect-tag-remove {
    @apply hover:bg-white/10 rounded;
  }

  .multiselect-placeholder {
    @apply text-white/50;
  }

  .multiselect-dropdown {
    @apply bg-gray-900 border border-white/10 rounded-lg mt-1;
  }

  .multiselect-options {
    @apply py-1;
  }

  .multiselect-option {
    @apply text-white/70 py-2 px-3;

    &.is-pointed {
      @apply bg-white/5 text-white;
    }

    &.is-selected {
      @apply bg-indigo-500/10 text-white;

      &.is-pointed {
        @apply bg-indigo-500/20;
      }
    }
  }

  .multiselect-no-options,
  .multiselect-no-results {
    @apply text-white/50 py-2 px-3;
  }

  .multiselect-spinner {
    @apply border-t-indigo-500;
  }

  &.is-disabled {
    @apply opacity-50 cursor-not-allowed;
  }

  &.is-invalid {
    @apply border-red-500;
  }
}

/* Custom scrollbar */
.multiselect-dropdown {
  scrollbar-width: thin;
  scrollbar-color: theme('colors.white / 10%') transparent;

  &::-webkit-scrollbar {
    @apply w-1.5;
  }

  &::-webkit-scrollbar-track {
    @apply bg-transparent;
  }

  &::-webkit-scrollbar-thumb {
    @apply bg-white/10 rounded-full hover:bg-white/20;
  }
}

/* Animations */
.multiselect-tags-enter-active,
.multiselect-tags-leave-active {
  @apply transition-all duration-200;
}

.multiselect-tags-enter-from,
.multiselect-tags-leave-to {
  @apply opacity-0 scale-95;
}

.multiselect-dropdown-enter-active,
.multiselect-dropdown-leave-active {
  @apply transition-all duration-200;
}

.multiselect-dropdown-enter-from,
.multiselect-dropdown-leave-to {
  @apply opacity-0 -translate-y-1;
}

/* Loading animation */
@keyframes multiselect-spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.multiselect-spinner {
  animation: multiselect-spin 1s linear infinite;
}
</style>