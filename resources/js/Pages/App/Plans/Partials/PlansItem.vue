<script setup>
import { ref, computed, nextTick, onMounted, onUnmounted, watch } from "vue";
import { ChevronDownIcon, EllipsisVerticalIcon, CalendarIcon, PaperClipIcon, PencilSquareIcon, ArchiveBoxIcon, TrashIcon } from "@heroicons/vue/24/outline";
import Datepicker from "@/Components/Datepicker.vue";
import { usePlans } from "@/Composables/usePlans";
import DialogModal from "@/Components/DialogModal.vue";
import { useToast } from "vue-toastification";

const emit = defineEmits(["toggle-popup", "edit", "refresh", "reorder"]);

const props = defineProps({
  item: {
    type: Object,
    required: true,
  },
  statuses: {
    type: Array,
    required: true,
  },
  priorities: {
    type: Array,
    required: true,
  },
  categories: {
    type: Array,
    default: () => [],
  },
  icons: {
    type: Object,
    default: () => ({}),
  },
  gridView: {
    type: Boolean,
    default: false,
  },
  isActive: {
    type: Boolean,
    default: false,
  },
  showStatusPopup: {
    type: Boolean,
    default: false,
  },
  showPriorityPopup: {
    type: Boolean,
    default: false,
  },
  showDatePopup: {
    type: Boolean,
    default: false,
  },
  showMoreActions: {
    type: Boolean,
    default: false,
  },
});

const { handleStatusChange, handlePriorityChange, handleDateChange, isLoading, handleDelete, handleArchive, handleError } = usePlans();
const toast = useToast();

const KEYS = {
  TAB: "Tab",
  ENTER: "Enter",
  ESCAPE: "Escape",
  SPACE: " ",
  ARROW_UP: "ArrowUp",
  ARROW_DOWN: "ArrowDown",
  ARROW_LEFT: "ArrowLeft",
  ARROW_RIGHT: "ArrowRight",
};

const selectedDate = ref(props.item.date ? new Date(props.item.date) : null);
const focusedButton = ref(null);
const focusedOption = ref(null);
const isHovering = ref(false);
const isRecurring = ref(false);
const recurringInterval = ref("monthly");

watch(
  () => props.item.date,
  (newDate) => {
    selectedDate.value = newDate ? new Date(newDate) : null;
  },
);

const dropdownPosition = ref({
  status: { left: "0px", top: "40px" },
  priority: { left: "0px", top: "40px" },
  date: { left: "0px", top: "40px" },
  actions: { right: "0px", top: "40px" },
});

const dropdownOpenOnTop = ref({
  status: false,
  priority: false,
  date: false,
  actions: false,
});

const actions = [
  { id: "edit", label: "Edit", icon: PencilSquareIcon, handler: (item) => emit("edit", item) },
  { id: "archive", label: props.item.archived && props.item.archived == 1 ? "Unarchive" : "Archive", icon: ArchiveBoxIcon, handler: (item) => archiveItemHandler(item) },
  { id: "delete", label: "Delete", icon: TrashIcon, handler: deleteItemHandler },
];

async function archiveItemHandler(item) {
  try {
    await handleArchive(item);
    emit("refresh");
  } catch (error) {
    handleError(error, "Failed to archive item");
  }
}

const showDeleteDialog = ref(false);
const itemToDelete = ref(null);
const deleteButtonRef = ref(null);

async function deleteItemHandler(item) {
  showDeleteDialog.value = true;
  itemToDelete.value = item;
  await nextTick();
  deleteButtonRef.value?.focus();
}

async function confirmDeleteItem() {
  if (!itemToDelete.value) return;
  try {
    await handleDelete(itemToDelete.value.id);
    emit("refresh");
    toast.success("Item deleted successfully");
  } catch (error) {
    toast.error(error?.response?.data?.message || "Failed to delete item");
  } finally {
    showDeleteDialog.value = false;
    itemToDelete.value = null;
  }
}

function handleDeleteDialogKeydown(e) {
  if (e.key === "Enter") {
    confirmDeleteItem();
  } else if (e.key === "Escape") {
    showDeleteDialog.value = false;
  }
}

function calculateDropdownPosition(type, itemId) {
  nextTick(() => {
    const button = document.getElementById(`${type}-button-${itemId}`);
    if (!button) return;

    const rect = button.getBoundingClientRect();
    const viewportHeight = window.innerHeight;
    const spaceBelow = viewportHeight - rect.bottom;
    const spaceAbove = rect.top;
    let openOnTop = false;

    // If not enough space below and more space above, open on top
    if (spaceBelow < 220 && spaceAbove > spaceBelow) {
      openOnTop = true;
    }

    if (type === "actions" || type === "date") {
      dropdownPosition.value[type] = {
        left: "auto",
        right: `${window.innerWidth - rect.right}px`,
        top: openOnTop ? "auto" : `${rect.bottom}px`,
        bottom: openOnTop ? `${viewportHeight - rect.top}px` : "auto",
      };
      dropdownOpenOnTop.value[type] = openOnTop;
      return;
    }

    dropdownPosition.value[type] = {
      left: `${rect.left}px`,
      right: "auto",
      top: openOnTop ? "auto" : `${rect.bottom}px`,
      bottom: openOnTop ? `${viewportHeight - rect.top}px` : "auto",
    };
  });
}

const itemStatusClass = computed(() => {
  if (!props.item.status) return { borderColor: "#cbd5e1", color: "#64748b" };

  return {
    backgroundColor: `${props.item.status.color}1A`,
    borderColor: props.item.status.color,
    color: props.item.status.color,
  };
});

const itemClasses = computed(() => {
  return {
    "border-l": true,
    "border-l-indigo-500": props.isActive,
    "bg-gray-700": isHovering.value,
    "no-date": !props.item.date,
    "has-attachments": hasAttachments.value,
    "grid-view": props.gridView,
  };
});

const itemStyles = computed(() => {
  return {
    backgroundColor: `${props.item.status.color}1d`,
    borderColor: props.item.status ? `${props.item.status.color}2d` : "transparent",
    borderWidth: props.item.status ? "1px" : "0",
  };
});

const hasAttachments = computed(() => {
  return props.item.attachments && props.item.attachments.length > 0;
});

const attachmentCount = computed(() => {
  return props.item.attachments ? props.item.attachments.length : 0;
});

const categoryName = computed(() => {
  if (!props.item.category_id) return "No category";
  const category = props.categories.find((c) => c.id === props.item.category_id);
  return category ? category.name : "No category";
});

function formatDate(date) {
  if (!date) return "No date";

  const dateObj = new Date(date);
  return new Intl.DateTimeFormat("en-US", {
    month: "short",
    day: "numeric",
    year: dateObj.getFullYear() !== new Date().getFullYear() ? "numeric" : undefined,
  }).format(dateObj);
}

function getItemStatusClass(status) {
  if (!status) return { borderColor: "#cbd5e1", color: "#64748b" };

  return {
    backgroundColor: `${status.color}1A`,
    borderColor: status.color,
    color: status.color,
  };
}

function getPriorityClass(priorityId) {
  const priority = props.priorities.find((p) => p.id === priorityId);
  if (!priority) return { borderColor: "#cbd5e1", color: "#64748b" };

  return {
    backgroundColor: `${priority.color}1A`,
    borderColor: priority.color,
    color: priority.color,
  };
}

async function updateStatus(statusId) {
  await handleStatusChange(props.item.id, statusId);
  emit("toggle-popup", null, null);
  emit("refresh");
}

async function updatePriority(priorityId) {
  await handlePriorityChange(props.item.id, priorityId);
  emit("toggle-popup", null, null);
  emit("refresh");
}

async function updateDate(date) {
  await handleDateChange(props.item.id, date, isRecurring.value, isRecurring.value ? recurringInterval.value : null);
  emit("toggle-popup", null, null);
  emit("refresh");
}

function handleClickOutside(event) {
  const popupTypes = ["status", "priority", "date", "actions"];
  const shouldClose = popupTypes.some((type) => {
    const isOpen = isPopupOpen(type);
    if (!isOpen) return false;

    const buttonId = `${type}-button-${props.item.id}`;
    const button = document.getElementById(buttonId);
    const dropdown = document.querySelector(`[data-dropdown="${type}-${props.item.id}"]`);

    return button && dropdown && !button.contains(event.target) && !dropdown.contains(event.target);
  });

  if (shouldClose) {
    emit("toggle-popup", null, null);
  }
}

function handleKeyDown(e, type) {
  if (e.key === KEYS.ESCAPE) {
    emit("toggle-popup", null, null);
    return;
  }

  if ((e.key === KEYS.ENTER || e.key === KEYS.SPACE) && !isPopupOpen(type)) {
    e.preventDefault();
    emit("toggle-popup", type, props.item.id);
    calculateDropdownPosition(type, props.item.id);
    return;
  }

  if (isPopupOpen(type)) {
    if (e.key === KEYS.ARROW_DOWN || e.key === KEYS.ARROW_UP) {
      e.preventDefault();
      navigateOptions(type, e.key === KEYS.ARROW_DOWN ? 1 : -1);
    }
  }

  if (e.key === KEYS.TAB && !e.shiftKey) focusedButton.value = getNextButtonType(type);
  else if (e.key === KEYS.TAB && e.shiftKey) focusedButton.value = getPrevButtonType(type);
}

function handleFocus(type) {
  focusedButton.value = type;
}

function handleOptionFocus(type, optionId) {
  focusedOption.value = { type, id: optionId };
}

function isPopupOpen(type) {
  switch (type) {
    case "status":
      return props.showStatusPopup;
    case "priority":
      return props.showPriorityPopup;
    case "date":
      return props.showDatePopup;
    case "actions":
      return props.showMoreActions;
    default:
      return false;
  }
}

function getNextButtonType(currentType) {
  const types = ["status", "priority", "date", "actions"];
  const currentIndex = types.indexOf(currentType);
  return types[(currentIndex + 1) % types.length];
}

function getPrevButtonType(currentType) {
  const types = ["status", "priority", "date", "actions"];
  const currentIndex = types.indexOf(currentType);
  return types[(currentIndex - 1 + types.length) % types.length];
}

function navigateOptions(type, direction) {
  let options;
  let currentId = focusedOption.value?.id;

  switch (type) {
    case "status":
      options = props.statuses;
      break;
    case "priority":
      options = props.priorities;
      break;
    case "actions":
      options = actions;
      break;
    default:
      return;
  }

  if (!options.length) return;

  let currentIndex = currentId ? options.findIndex((o) => o.id === currentId) : direction > 0 ? -1 : 0;
  const nextIndex = (currentIndex + direction + options.length) % options.length;
  const nextOption = options[nextIndex];

  focusedOption.value = { type, id: nextOption.id };
  nextTick(() => {
    const selector = `[data-option="${type}-${nextOption.id}"]`;
    const element = document.querySelector(selector);
    if (element) element.focus();
  });
}

const getIcon = (iconId) => {
  if (!iconId || !props.icons) return "";
  const icon = props.icons.find((i) => i.id === iconId);
  return icon ? icon.data : "";
};

onMounted(() => {
  document.addEventListener("click", handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener("click", handleClickOutside);
  focusedButton.value = null;
  focusedOption.value = null;
});
</script>

<template>
  <div :id="'item-' + item.id" :class="['flex min-h-[72px] w-full items-center justify-between rounded-lg p-3', itemClasses]" :style="itemStyles" @mouseenter="isHovering = true" @mouseleave="isHovering = false">
    <div class="flex-grow px-3">
      <h3 class="truncate text-base font-medium text-white">
        {{ item.title }}
      </h3>
      <p v-if="item.description" class="mt-1 text-xs text-gray-400">
        {{ item.description.slice(0, 55) + "..." }}
      </p>
      <div v-if="gridView && item.category_id" class="mt-2 flex items-center text-xs text-gray-400">
        <span>{{ categoryName }}</span>
      </div>
    </div>

    <div class="flex items-center space-x-3">
      <div class="relative">
        <button ref="statusBtn" :id="'status-button-' + item.id" type="button" :style="itemStatusClass" class="dropdown-button" @click.stop="emit('toggle-popup', 'status', item.id)" @focus="handleFocus('status')" @keydown="handleKeyDown($event, 'status')" aria-haspopup="true" :aria-expanded="showStatusPopup">
          <span v-html="item.status ? getIcon(item.status.icon_id) : ''"></span>
          <span>{{ item.status ? item.status.name : "No status" }}</span>
          <ChevronDownIcon class="h-3 w-3" />
        </button>

        <Teleport to="body" v-if="showStatusPopup">
          <div class="fixed inset-0 z-[2000]" @click.self="emit('toggle-popup', null, null)">
            <div
              class="dropdown-container"
              :style="{
                top: $refs.statusBtn ? $refs.statusBtn.getBoundingClientRect().bottom + 'px' : 'auto',
                left: $refs.statusBtn ? $refs.statusBtn.getBoundingClientRect().left + 'px' : 'auto',
              }"
            >
              <div v-if="isLoading('status')" class="px-4 py-2 text-sm text-gray-400">
                <div class="flex items-center justify-center">
                  <div class="h-4 w-4 animate-spin rounded-full border-2 border-gray-600 border-t-indigo-500"></div>
                  <span class="ml-2">Loading...</span>
                </div>
              </div>
              <div v-else>
                <button v-for="status in statuses" :key="status.id" :style="getItemStatusClass(status)" :data-option="`status-${status.id}`" class="flex w-full items-center space-x-2 px-4 py-2 text-left text-sm font-medium transition-colors hover:bg-gray-700" @click="updateStatus(status.id)" @focus="handleOptionFocus('status', status.id)">
                  <span v-html="status ? getIcon(status.icon_id) : ''"></span>
                  <span>{{ status ? status.name : "No status" }}</span>
                </button>
              </div>
            </div>
          </div>
        </Teleport>
      </div>

      <div class="relative">
        <button ref="priorityBtn" :id="'priority-button-' + item.id" type="button" :style="getPriorityClass(item.priority_id)" class="dropdown-button" @click.stop="emit('toggle-popup', 'priority', item.id)" @focus="handleFocus('priority')" @keydown="handleKeyDown($event, 'priority')" aria-haspopup="true" :aria-expanded="showPriorityPopup">
          <span v-html="item.priority ? getIcon(item.priority.icon_id) : ''"></span>
          <span>{{ item.priority ? item.priority.name : "No priority" }}</span>
          <ChevronDownIcon class="h-3 w-3" />
        </button>

        <Teleport to="body" v-if="showPriorityPopup">
          <div class="fixed inset-0 z-[2000]" @click.self="emit('toggle-popup', null, null)">
            <div class="dropdown-container" :style="{ top: $refs.priorityBtn ? $refs.priorityBtn.getBoundingClientRect().bottom + 'px' : 'auto', left: $refs.priorityBtn ? $refs.priorityBtn.getBoundingClientRect().left + 'px' : 'auto' }">
              <div v-if="isLoading('priority')" class="px-4 py-2 text-sm text-gray-400">
                <div class="flex items-center justify-center">
                  <div class="h-4 w-4 animate-spin rounded-full border-2 border-gray-600 border-t-indigo-500"></div>
                  <span class="ml-2">Loading...</span>
                </div>
              </div>
              <div v-else>
                <button v-for="priority in priorities" :key="priority.id" :style="getPriorityClass(priority.id)" :data-option="`priority-${priority.id}`" class="flex w-full items-center space-x-2 px-4 py-2 text-left text-sm font-medium transition-colors hover:bg-gray-700" @click="updatePriority(priority.id)" @focus="handleOptionFocus('priority', priority.id)">
                  <span v-html="priority ? getIcon(priority.icon_id) : ''"></span>
                  <span>{{ priority ? priority.name : "No priority" }}</span>
                </button>
              </div>
            </div>
          </div>
        </Teleport>
      </div>

      <div class="relative">
        <button ref="dateBtn" :id="'date-button-' + item.id" type="button" class="dropdown-button" @click.stop="emit('toggle-popup', 'date', item.id)" @focus="handleFocus('date')" @keydown="handleKeyDown($event, 'date')" aria-haspopup="true" :aria-expanded="showDatePopup">
          <CalendarIcon class="h-5 w-5" />
          <span>{{ formatDate(item.date) }}</span>
        </button>

        <Teleport to="body" v-if="showDatePopup">
          <div class="fixed inset-0 z-[2000]" @click.self="emit('toggle-popup', null, null)">
            <div class="dropdown-container" :style="{ top: $refs.dateBtn ? $refs.dateBtn.getBoundingClientRect().bottom + 'px' : 'auto', right: '106px' }">
              <div v-if="isLoading('date')" class="px-4 py-2 text-sm text-gray-400">
                <div class="flex items-center justify-center">
                  <div class="h-4 w-4 animate-spin rounded-full border-2 border-gray-600 border-t-indigo-500"></div>
                  <span class="ml-2">Loading...</span>
                </div>
              </div>
              <div v-else class="p-3">
                <div class="date-picker-wrapper space-y-3">
                  <input type="date" v-model="selectedDate" class="w-full rounded-lg border border-gray-700 bg-gray-800 px-3 py-2 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />

                  <div class="space-y-2">
                    <div class="flex items-center space-x-2">
                      <input id="recurring-toggle" v-model="isRecurring" type="checkbox" class="h-4 w-4 rounded border-gray-600 bg-gray-700 text-indigo-500 focus:ring-indigo-500 focus:ring-offset-gray-800" />
                      <label for="recurring-toggle" class="text-sm text-gray-300">Recurring task</label>
                    </div>

                    <div v-if="isRecurring" class="space-y-2">
                      <select v-model="recurringInterval" class="w-full rounded-lg border border-gray-700 bg-gray-800 px-3 py-2 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                        <option value="yearly">Yearly</option>
                      </select>
                    </div>
                  </div>

                  <div class="flex justify-between border-t border-gray-700 pt-3">
                    <button class="rounded-lg px-3 py-1.5 text-sm font-medium text-gray-300 transition-colors hover:bg-gray-700 hover:text-white" @click="updateDate(null)">Clear date</button>
                    <button class="rounded-lg bg-indigo-500 px-3 py-1.5 text-sm font-medium text-white transition-colors hover:bg-indigo-600" @click="updateDate(selectedDate)">Apply</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Teleport>
      </div>

      <!-- Attachments indicator -->
      <div v-if="hasAttachments" class="flex items-center space-x-1 text-gray-400">
        <PaperClipIcon class="h-4 w-4" />
        <span class="text-xs">{{ attachmentCount }}</span>
      </div>

      <!-- Actions Button -->
      <div class="relative">
        <button ref="actionsBtn" :id="'actions-button-' + item.id" type="button" :class="showMoreActions && 'bg-gray-700'" class="ah-[34px] hover:bg-gray-700 flex items-center justify-center !cursor-pointer rounded-full p-2 text-gray-400" @click.stop="emit('toggle-popup', 'actions', item.id)" @focus="handleFocus('actions')" @keydown="handleKeyDown($event, 'actions')" aria-haspopup="true" :aria-expanded="showMoreActions">
          <EllipsisVerticalIcon class="h-5 w-5" />
        </button>

        <Teleport to="body" v-if="showMoreActions">
          <div class="fixed inset-0 z-[2000]" @click.self="emit('toggle-popup', null, null)">
            <div class="dropdown-container !min-w-[150px]" :style="{ top: $refs.actionsBtn ? $refs.actionsBtn.getBoundingClientRect().bottom + 'px' : 'auto', right: '70px' }">
              <button v-for="action in actions" :key="action.id" :class="action.id === 'delete' && '!text-red-500'" :data-option="`action-${action.id}`" class="flex w-full items-center space-x-2 px-4 py-2 text-left text-sm text-white transition-colors hover:bg-gray-700" @click="action.handler(item)" @focus="handleOptionFocus('action', action.id)">
                <component :is="action.icon" class="h-5 w-5" />
                <span>{{ action.label }}</span>
              </button>
            </div>
          </div>
        </Teleport>
      </div>
    </div>

    <DialogModal :show="showDeleteDialog" @close="() => (showDeleteDialog = false)">
      <template #header>
        <div class="flex items-center space-x-2">
          <TrashIcon class="h-6 w-6 text-red-500" />
          <span class="text-lg font-semibold text-white">Delete Item</span>
        </div>
      </template>
      <template #content>
        <div class="py-2 text-white">Are you sure you want to delete this item? This action cannot be undone.</div>
      </template>
      <template #footer>
        <button class="rounded px-4 py-2 bg-gray-700 text-white mr-2" @click="showDeleteDialog = false">Cancel</button>
        <button ref="deleteButtonRef" class="rounded px-4 py-2 bg-red-600 text-white" @click="confirmDeleteItem" @keydown="handleDeleteDialogKeydown">Delete</button>
      </template>
    </DialogModal>
  </div>
</template>

<style lang="pcss" scoped>
button {
  @apply !outline-none !ring-0 focus:!outline-none focus:!ring-0;
}

.grid-view {
  @apply flex-col items-start;

  h3 {
    @apply mb-2;
  }

  .right-side {
    @apply mt-3 w-full flex-wrap;
  }
}

.dropdown-container {
  @apply fixed mt-2 min-w-[200px] rounded-md border border-gray-700 bg-gray-800 shadow-2xl;
  animation: dropdownFadeIn 0.15s ease-out forwards;
  transform-origin: var(--transform-origin, top);
  box-shadow:
    0 10px 15px -3px rgba(0, 0, 0, 0.5),
    0 4px 6px -2px rgba(0, 0, 0, 0.3);
  z-index: 999 !important;
}

.dropdown-button {
  @apply flex items-center space-x-2 rounded-full border px-3 py-1 text-xs font-medium text-white transition-colors ah-[34px];
}

@keyframes dropdownFadeIn {
  from {
    opacity: 0;
    transform: scale(0.9);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

@keyframes pulse-border {
  0%,
  100% {
    box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.5);
  }
  50% {
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.3);
  }
}

.loading-spinner {
  @apply h-5 w-5 animate-spin text-indigo-500;
}
</style>

