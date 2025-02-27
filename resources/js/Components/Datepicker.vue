<script setup>
import { ref, watch, onMounted } from "vue";
import Toggle from "@/Components/Toggle.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Datepicker from "@vuepic/vue-datepicker";
import Multiselect from "@/Components/Multiselect.vue";
import "@vuepic/vue-datepicker/dist/main.css";
import { useToast } from "vue-toastification";

const props = defineProps({
  id: [Date, String, null, Number],
  date: [Date, String, null],
  recurring: [Number, String, null],
  recurringInterval: [Number, String],
});

const toast = useToast();
const modelRecurringInterval = ref(props.recurringInterval ? props.recurringInterval : 'yearly');
const modelRecurring = ref(props.recurring == 1 ? true : false);
const modelDate = ref(props.date);
const datepicker = ref(null);
const skipUpdate = ref(false);
const emit = defineEmits(["update:date", "close"]);

const updateRecurring = () => {
  modelRecurring.value = !modelRecurring.value;
};

const updateSelection = (nv) => {
  modelRecurringInterval.value = nv;
};

const updateDate = (modelData) => {
  event.stopPropagation();

  if (skipUpdate.value) {
    skipUpdate.value = false;
    totalClose();
    return;
  }

  if (!(modelData instanceof Date)) {
    totalClose();
    return;
  }

  const getFormatted = (d) => (d instanceof Date ? new Date(d).toISOString().split("T")[0] : d);
  const origDate = getFormatted(props.date);
  const newDate = getFormatted(modelData);
  const origRecurring = props.recurring == 1 ? true : false;
  const newRecurring = modelRecurring.value;
  const origInterval = props.recurringInterval;
  const newInterval = modelRecurringInterval.value;

  if (newDate === origDate && newRecurring === origRecurring && newInterval === origInterval) {
    totalClose();
    return;
  }

  if (newRecurring == true && (!newInterval || newInterval == 0)) {
    toast.warning("Please select an interval.");
    return;
  }

  emit("update:date", newDate, newRecurring, newInterval);
  totalClose();
};

const totalClose = () => {
  skipUpdate.value = true;
  emit("close");
};

watch(
  () => [props.date, props.recurring, props.recurringInterval],
  (newDate, newRecurring, newRI) => {
    modelDate.value = newDate;
    modelRecurring.value = newRecurring == 1 ? true : false;
    modelRecurringInterval.value = newRI;
  },
);

const position = ref("top");

onMounted(() => {
  let viewportOffsetTop = datepicker.value?.$el.getBoundingClientRect().top;
  if (viewportOffsetTop <= 520) position.value = "bottom";
});

const formatDate = (date) => {
  const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
  const dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

  const getOrdinal = (n) => {
    if (n % 100 >= 11 && n % 100 <= 13) return "th";
    switch (n % 10) {
      case 1:
        return "st";
      case 2:
        return "nd";
      case 3:
        return "rd";
      default:
        return "th";
    }
  };

  const dayNumber = date.getDate();
  const ordinal = getOrdinal(dayNumber);
  const monthName = monthNames[date.getMonth()];
  const dayName = dayNames[date.getDay()];

  let r = "Every ";
  switch (modelRecurringInterval.value) {
    case "yearly":
      r = `Yearly on ${dayNumber}${ordinal} of ${monthName}.`;
      break;
    case "monthly":
      r += `month on ${dayNumber}${ordinal}.`;
      break;
    case "weekly":
      r += `week on ${dayName}.`;
      break;
    case "daily":
      r += `day.`;
      break;
    default:
      break;
  }

  return r;
};
</script>

<template>
  <div :class="['absolute -left-px right-0 z-[100]', position === 'top' ? '-bottom-px' : '-top-px']" v-outclick="totalClose">
    <Datepicker :id="`rdp_${id}`" ref="datepicker" v-model="modelDate" :dark="true" :inline="true" :enableTimePicker="false" @update:modelValue="updateDate">
      <template #action-row="{ internalModelValue, selectDate }">
        <div class="-mt-2 flex w-full flex-col items-center justify-center gap-3 pb-3">
          <div class="flex items-center gap-3">
            <PrimaryButton @click="selectDate">Select</PrimaryButton>
            <PrimaryButton color="#0d0d0d" opacity="100" @click="totalClose">Cancel</PrimaryButton>
          </div>
          <Toggle v-model:active="modelRecurring" label="Recurring" @update:active="updateRecurring" />
          <Multiselect v-if="modelRecurring" :id="`ridp_${id}`" @update="(nv) => updateSelection(nv)" :valueProp="modelRecurringInterval" :options="['yearly', 'monthly', 'weekly', 'daily']" :datepickerSelect="true" />
          <span v-if="modelRecurringInterval && modelRecurring" class="flex">{{ formatDate(internalModelValue) }}</span>
        </div>
      </template>
    </Datepicker>
  </div>
</template>
