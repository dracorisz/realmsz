<script setup>
import { onMounted, ref } from "vue";

defineProps({
  modelValue: [String, Number],
  placeholder: String,
  type: {
    type: String,
    default: 'text'
  }
});

defineEmits(["update:modelValue"]);

const input = ref(null);

onMounted(() => {
  if (input.value.hasAttribute("autofocus")) {
    input.value.focus();
  }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
  <input 
    ref="input" 
    :value="modelValue" 
    @input="$emit('update:modelValue', type === 'number' ? Number($event.target.value) : $event.target.value)" 
    :placeholder="placeholder"
    :type="type"
  />
</template>
