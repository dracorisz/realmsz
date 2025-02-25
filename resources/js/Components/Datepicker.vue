<script setup>
import { ref, watch, nextTick, onMounted } from "vue";
// import Toggle from "@/Components/Toggle.vue";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

const props = defineProps({
  date: {
    type: [Date, String, null],
    required: true,
  },
  item: {
    type: [Number, String],
    required: true,
  }
});

const modelDate = ref(props.date);
const datepicker = ref(null);
const emit = defineEmits(["update:date"]);

const updateDate = (modelData) => { 
  if (modelData instanceof Date) {
    const formattedDate = new Date(modelData).toISOString().split("T")[0];
    emit("update:date", formattedDate);
  } else emit("update:date", props.date);

  nextTick(() => {
    if (datepicker.value?.closeMenu) datepicker.value.closeMenu(true);
    else if (datepicker.value?.toggleMenu) datepicker.value.toggleMenu();
  });
};

watch(
  () => props.date,
  (newDate) => {
    modelDate.value = newDate;
  },
);

const position = ref('top');

onMounted(() => {
  let viewportOffsetTop = datepicker.value?.$el.getBoundingClientRect().top;
  if (viewportOffsetTop <= 550) position.value = 'bottom';
});

// const formatDate = (date, type) => {
//   return format(date, 'dd.MM.yyyy, HH:mm');
// };
</script>

<template>
  <div :class="['absolute right-0 -left-px z-50', position === 'top' ? '-bottom-px' : '-top-px']" v-outclick="updateDate">
    <Datepicker ref="datepicker" v-model="modelDate" :dark="true" :inline="true" :autoApply="true" :enableTimePicker="false" @update:modelValue="updateDate" :clearable="true">
      <!-- :autoApply="true"
      <template #action-row="{ internalModelValue, selectDate }">
        <div class="action-row">
          @update:active="toggleArchive"
          <Toggle v-model:active="item.recurring" label="Recurring" />
          <p class="current-selection">{{ formatDate(internalModelValue, day) }}</p>
        </div>
      </template> -->
    </Datepicker>
  </div>
</template>
