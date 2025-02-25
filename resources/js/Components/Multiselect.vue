<script setup>
import { defineEmits, onMounted, ref } from "vue";
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";

const props = defineProps({
  id: String,
  icons: Array,
  value: {
    type: [String, Object, Number],
    default: "",
  },
  options: {
    type: Array,
    required: true,
  },
  inlineSelect: {
    type: Boolean,
    default: true,
  }
});

const emit = defineEmits(["update:selected"]);

const getIcon = (id) => {
  return props.icons.find((i) => i.id === id).data;
};

const getOptionName = (id) => {
  return props.options.find((i) => i.id === id).name;
};

const caretUp = ref(false);
const toggleCaret = () => {
  caretUp.value = !caretUp.value;
};

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
  <Multiselect :id="id" :optionHeight="43" @open="toggleCaret" @close="toggleCaret" :maxHeight="500" :modelValue="value" track-by="id" label="name" :allowEmpty="false" placeholder="" :hideSelected="false" :options="options" selectLabel="↵ Select">
    <template #beforeList>
      <span class="px-3 py-2 block text-white/50 text-xs">Search...</span>
    </template>
    <template #noResult>
      No options found.
    </template>
    <template #singleLabel="data">
      <div :style="{ color: inlineSelect && data.option.color }" :class="['flex items-center gap-2 ah-[43px]', inlineSelect ? '!text-xs' : '!text-sm']">
        <span v-if="inlineSelect" v-html="getIcon(data.option.icon_id)" />
        <span v-if="inlineSelect">{{ data.option.name }}</span>
        <span v-else>{{ getOptionName(value) }}</span>
      </div>
    </template>
    <template #option="data">
      <div :style="{ color: inlineSelect && data.option.color }" :class="['flex items-center gap-2 ah-[43px]', inlineSelect ? '!text-xs' : '!text-sm']">
        <span v-if="inlineSelect" v-html="getIcon(data.option.icon_id)" />
        <span>{{ data.option.name }}</span>
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
  @apply ah-[43px] overflow-hidden rounded-none text-white bg-transparent leading-none border-[#2a2a2a] flex items-center justify-center w-full font-normal;
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