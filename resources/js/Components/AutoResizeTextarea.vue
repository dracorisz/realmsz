<script setup>
import { onMounted, ref, watch, nextTick } from "vue";

const props = defineProps({
  modelValue: String,
  placeholder: String,
  disabled: Boolean,
  readonly: Boolean,
});

const emit = defineEmits(["update:modelValue", "enter"]);

const resetHeight = () => {
  if (textarea.value) {
    textarea.value.style.height = "50px";
    nextTick(adjustHeight);
  }
};

defineExpose({
  focus: () => textarea.value?.focus(),
  resetHeight,
});
const textarea = ref(null);

const debounce = (fn, delay) => {
  let timeoutId;
  return (...args) => {
    if (timeoutId) {
      clearTimeout(timeoutId);
    }
    timeoutId = setTimeout(() => {
      fn(...args);
    }, delay);
  };
};

const adjustHeight = debounce(() => {
  const el = textarea.value;
  if (!el) return;

  el.style.height = "50px";
  const scrollHeight = el.scrollHeight;
  el.style.height = Math.min(scrollHeight, 200) + "px";
}, 10);

watch(
  () => props.modelValue,
  () => {
    nextTick(adjustHeight);
  },
);

const heightFunction = (e) => {
  const cursorPosition = e.target.selectionStart;
  if (e.target.value && e.target.value != "" && e.target.value != null) emit("update:modelValue", e.target.value);
  nextTick(() => {
    e.target.setSelectionRange(cursorPosition, cursorPosition);
  });
  adjustHeight();
};

const onShiftEnter = (e) => {
  e.preventDefault();
  const el = textarea.value;
  if (!el) return;
  const cursorPosition = el.selectionStart;
  const currentValue = props.modelValue || "";
  const textBefore = currentValue.slice(0, cursorPosition);
  const textAfter = currentValue.slice(cursorPosition);
  const newValue = textBefore + "\n" + textAfter;
  emit("update:modelValue", newValue);
  nextTick(() => {
    el.setSelectionRange(cursorPosition + 1, cursorPosition + 1);
  });
};

onMounted(() => {
  adjustHeight();
});
</script>

<template>
  <textarea ref="textarea" :value="modelValue" @input="(e) => heightFunction(e)" :class="['autoresize-class', { 'cursor-not-allowed': disabled || readonly }]" :placeholder="placeholder" :disabled="disabled" :readonly="readonly" @keydown.enter.exact.prevent="$emit('enter')" @keydown.shift.enter.prevent="onShiftEnter" />
</template>

<style scoped lang="pcss">
.autoresize-class {
  scrollbar-gutter: 20px;
  @apply font-normal max-h-[200px] min-h-[50px] w-full resize-none overflow-x-hidden rounded-xl bg-[#0d0d0d] px-4 py-3 pr-[40px] text-white placeholder:text-white/50 focus:bg-[#1a1a1a] focus:outline-none focus:ring-0;
}

.autoresize-class::-webkit-scrollbar {
  width: 20px;
}

.autoresize-class::-webkit-scrollbar-track {
  @apply rounded-xl bg-[#0d0d0d] focus:bg-[#1a1a1a];
}

.autoresize-class::-webkit-scrollbar-thumb {
  @apply cursor-pointer rounded-xl bg-[#333] hover:bg-[#555];
  border: 8px solid #0d0d0d;
}

.autoresize-class:focus::-webkit-scrollbar-thumb {
  border: 8px solid #1a1a1a;
}
</style>

