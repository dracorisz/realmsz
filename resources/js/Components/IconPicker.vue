<script setup>
import { ref, computed } from "vue";
import { useToast } from "vue-toastification";

const props = defineProps({
  modelValue: {
    type: [String, Number],
    default: null,
  },
  icons: {
    type: Array,
    default: () => [],
  },
});

const emit = defineEmits(["update:modelValue"]);
const toast = useToast();
const isOpen = ref(false);
const selectedIcon = ref(props.modelValue);

const selectIcon = (icon) => {
  selectedIcon.value = icon.id;
  emit("update:modelValue", icon.id);
  isOpen.value = false;
  toast.success("Icon selected successfully");
};

const togglePicker = () => {
  isOpen.value = !isOpen.value;
};

const filteredIcons = computed(() => {
  return props.icons.filter((icon) => icon.data);
});
</script>

<template>
  <div class="relative">
    <button type="button" class="flex h-8 w-8 items-center justify-center rounded-full border border-gray-700 bg-gray-800" @click="togglePicker">
      <span v-if="selectedIcon" v-html="icons.find((i) => i.id === selectedIcon)?.data" class="h-5 w-5" />
      <span v-else class="text-gray-400">Select Icon</span>
    </button>

    <div v-if="isOpen" class="absolute right-0 z-10 mt-2 w-20 rounded-md bg-gray-800 py-1 shadow-lg ring-1 ring-black ring-opacity-5">
      <div class="grid grid-cols-10 gap-3 p-2">
        <button v-for="icon in filteredIcons" :key="icon.id" class="flex h-5 w-5 items-center justify-center rounded-full transition-all duration-200 hover:bg-gray-700" :class="{ 'bg-gray-700': selectedIcon === icon.id }" @click="selectIcon(icon)">
          <span v-html="icon.data" class="h-5 w-5" />
        </button>
      </div>
    </div>
  </div>
</template>
