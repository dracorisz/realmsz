<script setup>
import { defineEmits, onMounted, watch, ref } from "vue";
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";

const props = defineProps({
  id: String,
  icons: Array,
  inlineSelect: Boolean,
  options: Array,
  valueProp: [String, Object, Number],
});

const emit = defineEmits(["update"]);

const getColor = (id) => {
  return props.options.find((i) => i.id === id)?.color;
};

const getIcon = (id) => {
  let iconId = props.options.find((i) => i.id === id)?.icon_id;
  return props.icons.find((i) => i.id === iconId)?.data;
};

const getOptionName = (id) => {
  return props.options.find((i) => i.id === id)?.name;
};

const modelValue = ref(props.valueProp);

const caretUp = ref(false);
const toggleCaret = () => {
  caretUp.value = !caretUp.value;
};

const updateSelection = (newValue) => {
  modelValue.value = newValue.id;
  emit("update", newValue.id);
};

watch(
  () => props.valueProp,
  (newValue) => {
    modelValue.value = newValue;
  },
);

onMounted(() => {
  if (!props.inlineSelect) {
    let container = document.getElementById(props.id).closest(".multiselect__tags");

    container.style.borderStyle = 'solid';
    container.style.borderColor = '#2a2a2a';
    container.style.borderWidth = '1px';
    container.style.backgroundColor = '#0d0d0d';

    container.addEventListener('mouseover', () => {
      container.style.backgroundColor = '#1a1a1a';
      container.style.borderColor = '#4d4d4d';
    });

    container.addEventListener('mouseleave', () => {
      container.style.backgroundColor = '#0d0d0d';
      container.style.borderColor = '#2a2a2a';
    });
  }
});
</script>

<template>
  <Multiselect :id="id" :optionHeight="43" @open="toggleCaret" @close="toggleCaret" trackBy="id" label="name" :maxHeight="500" v-model="modelValue" @update:modelValue="updateSelection" :allowEmpty="false" placeholder="" :hideSelected="false" :options="options" selectLabel="↵ Select">
    <template #beforeList>
      <span class="px-3 py-2 block text-white/50 text-xs">Search...</span>
    </template>
    <template #noResult>
      No options found.
    </template>
    <template #singleLabel="data">
      <div :style="{ color: inlineSelect && getColor(data.option) }" :class="['flex items-center gap-2 ah-[43px]', inlineSelect ? '!text-xs' : '!text-sm']">
        <span v-if="inlineSelect" v-html="getIcon(data.option)" />
        <span>{{ getOptionName(data.option) }}</span>
      </div>
    </template>
    <template #option="data">
      <div :style="{ color: inlineSelect && getColor(data.option.id) }" :class="['flex items-center gap-2 ah-[43px]', inlineSelect ? '!text-xs' : '!text-sm']">
        <span v-if="inlineSelect" v-html="getIcon(data.option.id)" />
        <span>{{ getOptionName(data.option.id) }}</span>
      </div>
    </template>
    <template #caret>
      <div class="absolute right-0 text-white/40 flex items-center justify-center as-[43px]">
        <svg :class="['icons will-change-transform transition-transform duration-500', caretUp && '-scale-y-100']" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
      </div>
    </template>
  </Multiselect>
</template>

<style scoped lang="pcss">
:deep(.multiselect__content),
:deep(.multiselect__content-wrapper),
:deep(.multiselect__input),
:deep(.multiselect__single),
:deep(.multiselect__option),
:deep(.multiselect__tags-wrap),
:deep(.multiselect__tags),
:deep(.multiselect__element),
:deep(.multiselect__tags-wrap),
:deep(.multiselect) {
  @apply ah-[43px] overflow-hidden rounded-xl text-white bg-transparent leading-none border-[#2a2a2a] flex items-center justify-center w-full font-normal;
}

:deep(.multiselect__content),
:deep(.multiselect__content-wrapper) {
  @apply bg-[#1a1a1a] ah-[initial] !p-0 -left-[.5px];
}

:deep(.multiselect__tags) {
  border: 0px;
}

:deep(.multiselect__single) {
  @apply justify-start;
}

:deep(.multiselect__element),
:deep(.multiselect__option) {
  @apply justify-start bg-[#1a1a1a] hover:bg-[#2a2a2a];
}

:deep(.multiselect__select),
:deep(.multiselect__spinner) {
  @apply before:hidden;
}
  
:deep(.multiselect__option--selected),
:deep(.multiselect__option--highlight) {
  @apply after:bg-transparent;
}
</style>