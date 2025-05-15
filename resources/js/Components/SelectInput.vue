<script setup>
import { computed } from 'vue'

const props = defineProps({
  modelValue: {
    type: [String, Number, Array],
    default: ''
  },
  id: {
    type: String,
    default: null
  },
  name: {
    type: String,
    default: null
  },
  placeholder: {
    type: String,
    default: null
  },
  disabled: {
    type: Boolean,
    default: false
  },
  required: {
    type: Boolean,
    default: false
  },
  multiple: {
    type: Boolean,
    default: false
  },
  class: {
    type: String,
    default: ''
  }
})

const emit = defineEmits(['update:modelValue'])

const value = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value)
})
</script>

<template>
  <select
    :id="id"
    :name="name"
    :placeholder="placeholder"
    :disabled="disabled"
    :required="required"
    :multiple="multiple"
    v-model="value"
    class="block w-full rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-white placeholder-white/50 focus:border-white/20 focus:outline-none disabled:opacity-50"
    :class="class"
  >
    <slot />
  </select>
</template> 