<script setup>
import { ref, onMounted, computed, nextTick } from "vue";
import axios from "axios";
import { useToast } from "vue-toastification";
import DialogModal from "@/Components/DialogModal.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import ColorPicker from "@/Components/ColorPicker.vue";
import Multiselect from "@/Components/Multiselect.vue";
import { PlusIcon, PencilIcon, TrashIcon, XMarkIcon } from "@heroicons/vue/24/outline";

const toast = useToast();

const props = defineProps({
  show: {
    type: Boolean,
    required: true,
  },
  icons: {
    type: Array,
    required: true,
  },
  categories: {
    type: Array,
    required: true,
    default: () => [],
  },
  statuses: {
    type: Array,
    required: true,
    default: () => [],
  },
  priorities: {
    type: Array,
    required: true,
    default: () => [],
  },
});

const emit = defineEmits(["close", "refresh", "update:categories", "update:statuses", "update:priorities"]);

// Keyboard constants
const KEYS = {
  ENTER: "Enter",
  ESCAPE: "Escape",
  TAB: "Tab",
  ARROW_UP: "ArrowUp",
  ARROW_DOWN: "ArrowDown",
  ARROW_LEFT: "ArrowLeft",
  ARROW_RIGHT: "ArrowRight",
  SPACE: " ",
};

const activeTab = ref("cat");
const showModal = ref(false);
const modalType = ref("create");
const loading = ref({
  form: false,
  delete: false,
  update: false,
});
const errors = ref({});
const updateQueue = ref(new Set());
const editingId = ref(null);
const nameInput = ref(null);
const focusedItemIndex = ref(-1);
const tabs = ["cat", "sta", "pri"];
const closeButton = ref(null);

const form = ref({
  name: "",
  description: "",
  color: "#3B82F6",
  icon: "",
  icon_id: null,
  type: "",
  order: 0,
});

// Computed properties
const currentList = computed(() => {
  switch (activeTab.value) {
    case "cat":
      return props.categories;
    case "sta":
      return props.statuses;
    case "pri":
      return props.priorities;
    default:
      return [];
  }
});

const currentType = computed(() => {
  switch (activeTab.value) {
    case "cat":
      return "category";
    case "sta":
      return "status";
    case "pri":
      return "priority";
    default:
      return "";
  }
});

const tabName = computed(() => {
  switch (activeTab.value) {
    case "cat":
      return "Category";
    case "sta":
      return "Status";
    case "pri":
      return "Priority";
    default:
      return "";
  }
});

// Focus management
onMounted(() => {
  if (props.show && closeButton.value) {
    nextTick(() => {
      closeButton.value.focus();
    });
  }
});

const focusFirstItem = () => {
  focusedItemIndex.value = 0;
  focusItem(0);
};

const focusItem = (index) => {
  nextTick(() => {
    const items = document.querySelectorAll(".settings-item");
    if (items.length > index) {
      items[index].focus();
    }
  });
};

const focusModalInput = () => {
  nextTick(() => {
    if (nameInput.value) {
      nameInput.value.focus();
    }
  });
};

// Methods
const getIcon = (iconId) => {
  if (!iconId || !props.icons) return "";
  const icon = props.icons.find((i) => i.id === iconId);
  return icon ? icon.data : "";
};

const resetForm = () => {
  form.value = {
    name: "",
    description: "",
    color: "#3b82f6",
    icon: "",
    icon_id: null,
    type: currentType.value,
    order: currentList.value.length + 1,
  };
  errors.value = {};
  editingId.value = null;
};

const handleAdd = () => {
  resetForm();
  modalType.value = "create";
  showModal.value = true;
  focusModalInput();
};

const handleEdit = (item) => {
  resetForm();
  modalType.value = "edit";
  editingId.value = item.id;
  updateQueue.value.add(item.id);

  form.value = {
    name: item.name || "",
    description: item.description || "",
    color: item.color || "#3b82f6",
    icon: item.icon || "",
    icon_id: item.icon_id || null,
    type: currentType.value,
    order: item.order || 0,
  };

  showModal.value = true;
  focusModalInput();
};

const handleKeyNavigation = (e, type, index) => {
  // Handle keyboard navigation for tabs
  if (type === "tabs") {
    if (e.key === KEYS.ARROW_RIGHT) {
      e.preventDefault();
      const currentIndex = tabs.indexOf(activeTab.value);
      const nextIndex = (currentIndex + 1) % tabs.length;
      activeTab.value = tabs[nextIndex];

      // Focus the new tab
      nextTick(() => {
        const tabButtons = document.querySelectorAll(".tab-button");
        if (tabButtons.length > nextIndex) {
          tabButtons[nextIndex].focus();
        }
      });
    } else if (e.key === KEYS.ARROW_LEFT) {
      e.preventDefault();
      const currentIndex = tabs.indexOf(activeTab.value);
      const prevIndex = (currentIndex - 1 + tabs.length) % tabs.length;
      activeTab.value = tabs[prevIndex];

      // Focus the new tab
      nextTick(() => {
        const tabButtons = document.querySelectorAll(".tab-button");
        if (tabButtons.length > prevIndex) {
          tabButtons[prevIndex].focus();
        }
      });
    } else if (e.key === KEYS.ARROW_DOWN) {
      e.preventDefault();
      focusFirstItem();
    }
  }

  // Handle keyboard navigation for items
  if (type === "item") {
    if (e.key === KEYS.ARROW_UP) {
      e.preventDefault();
      if (index > 0) {
        focusItem(index - 1);
      } else {
        // Focus the active tab if at top
        nextTick(() => {
          const currentIndex = tabs.indexOf(activeTab.value);
          const tabButtons = document.querySelectorAll(".tab-button");
          if (tabButtons.length > currentIndex) {
            tabButtons[currentIndex].focus();
          }
        });
      }
    } else if (e.key === KEYS.ARROW_DOWN) {
      e.preventDefault();
      if (index < currentList.value.length - 1) {
        focusItem(index + 1);
      } else {
        // Focus the add button if at bottom
        nextTick(() => {
          const addButton = document.querySelector(".add-button");
          if (addButton) {
            addButton.focus();
          }
        });
      }
    } else if (e.key === KEYS.ENTER || e.key === KEYS.SPACE) {
      e.preventDefault();
      handleEdit(currentList.value[index]);
    }
  }
};

const handleSave = async () => {
  if (!validateForm()) return;

  loading.value.form = true;
  errors.value = {};

  try {
    const formData = new FormData();

    Object.keys(form.value).forEach((key) => {
      if (form.value[key] !== null && form.value[key] !== undefined) {
        if (Array.isArray(form.value[key]) || typeof form.value[key] === "object") {
          formData.append(key, JSON.stringify(form.value[key]));
        } else {
          formData.append(key, form.value[key]);
        }
      }
    });

    let response;

    if (editingId.value) {
      updateQueue.value.add(editingId.value);
      response = await axios.post(`${route(`${currentType.value}.update`, { id: editingId.value })}`, formData);
      updateQueue.value.delete(editingId.value);
    } else {
      response = await axios.post(route(`${currentType.value}.store`), formData);
    }

    if (response.data.success) {
      showModal.value = false;
      resetForm();
      emit("refresh");
      toast.success(`${currentType.value.charAt(0).toUpperCase() + currentType.value.slice(1)} ${editingId.value ? "updated" : "added"} successfully`, { timeout: 3000 });

      if (activeTab.value === "cat") {
        emit("update:categories", response.data.categories || props.categories);
      } else if (activeTab.value === "sta") {
        emit("update:statuses", response.data.statuses || props.statuses);
      } else if (activeTab.value === "pri") {
        emit("update:priorities", response.data.priorities || props.priorities);
      }
    } else if (response.data.message) {
      toast.error(response.data.message, { timeout: 5000 });
    } else {
      toast.error("Failed to save. Please try again.", { timeout: 5000 });
    }
  } catch (error) {
    handleError(error);
  } finally {
    loading.value.form = false;
  }
};

const handleDelete = async (item, e) => {
  if (e) e.stopPropagation();

  if (!confirm(`Are you sure you want to delete this ${currentType.value}? This action cannot be undone and will affect all associated items.`)) return;

  loading.value.delete = true;
  updateQueue.value.add(item.id);

  try {
    const response = await axios.delete(route(`${currentType.value}.destroy`, { id: item.id }));

    if (response.data.success) {
      emit("refresh");
      toast.success(`${currentType.value.charAt(0).toUpperCase() + currentType.value.slice(1)} deleted successfully`, { timeout: 3000 });

      if (activeTab.value === "cat") {
        emit("update:categories", response.data.categories || props.categories.filter((c) => c.id !== item.id));
      } else if (activeTab.value === "sta") {
        emit("update:statuses", response.data.statuses || props.statuses.filter((s) => s.id !== item.id));
      } else if (activeTab.value === "pri") {
        emit("update:priorities", response.data.priorities || props.priorities.filter((p) => p.id !== item.id));
      }
    } else {
      throw new Error(response.data.message || "Failed to delete item");
    }
  } catch (error) {
    handleError(error);
  } finally {
    loading.value.delete = false;
    updateQueue.value.delete(item.id);
  }
};

const validateForm = () => {
  errors.value = {};
  let valid = true;

  if (!form.value.name || form.value.name.trim() === "") {
    errors.value.name = "Name is required";
    valid = false;
  }

  if (!form.value.color || form.value.color.trim() === "") {
    errors.value.color = "Color is required";
    valid = false;
  }

  if (activeTab.value === "cat" && !form.value.icon_id) {
    errors.value.icon = "Icon is required";
    valid = false;
  }

  if (form.value.name.length > 50) {
    errors.value.name = "Name must be less than 50 characters";
    valid = false;
  }

  if (form.value.description?.length > 500) {
    errors.value.description = "Description must be less than 500 characters";
    valid = false;
  }

  return valid;
};

const handleError = (error) => {
  console.error("Error:", error);

  if (error.response && error.response.data && error.response.data.errors) {
    errors.value = error.response.data.errors;

    const firstErrorField = Object.keys(error.response.data.errors)[0];
    const firstError = error.response.data.errors[firstErrorField][0];
    toast.error(firstError, { timeout: 5000 });
    return;
  }

  // Extract a meaningful error message if possible
  const errorMessage = error.response?.data?.message || error.message || "An unexpected error occurred";
  toast.error(errorMessage, { timeout: 5000 });
};

const handleIconKeyDown = (e, icon) => {
  if (e.key === KEYS.ENTER || e.key === KEYS.SPACE) {
    e.preventDefault();
    form.value.icon_id = icon.id;
  }
};

const handleModalKeyDown = (e) => {
  if (e.key === KEYS.ESCAPE) {
    e.preventDefault();
    showModal.value = false;
  }
};

// Handle closing the modal
const closeModal = () => {
  showModal.value = false;
};
</script>

<template>
  <Teleport to="body">
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center" aria-modal="true" @keydown="handleModalKeyDown">
      <div class="absolute inset-0 bg-black/70 backdrop-blur-lg" @click="emit('close')" />
      <div class="animate-fade-in relative w-full max-w-4xl rounded-lg border border-white/10 bg-[#0d0d0d] p-6 shadow-lg">
        <!-- Header -->
        <div class="mb-4 flex items-center justify-between pb-3">
          <h3 id="settings-title" class="text-lg font-medium text-white">Settings</h3>
          <button ref="closeButton" class="rounded-lg p-2 text-white/50 transition-all duration-150 hover:bg-white/5 hover:text-white" @click="emit('close')" :disabled="loading.form || loading.update || loading.delete" aria-label="Close settings">
            <XMarkIcon class="h-5 w-5" />
          </button>
        </div>

        <!-- Tabs -->
        <div class="mb-6 grid grid-cols-3 gap-2">
          <button v-for="(tab, index) in tabs" :key="tab" :id="`tab-${tab}`" class="tab-button rounded-lg px-4 py-2 text-sm font-medium transition-all duration-150" :class="activeTab === tab ? 'bg-white/10 text-white' : 'text-white/50 hover:bg-white/5 hover:text-white'" @click="activeTab = tab" @keydown="handleKeyNavigation($event, 'tabs', index)" :aria-controls="`panel-${tab}`" :aria-selected="activeTab === tab" :tabindex="activeTab === tab ? 0 : -1">
            {{ tab === "cat" ? "Categories" : tab === "sta" ? "Statuses" : "Priorities" }}
          </button>
        </div>

        <!-- Content -->
        <div v-for="tab in tabs" :key="`panel-${tab}`" :id="`panel-${tab}`" class="custom-scrollbar max-h-[60vh] space-y-3 overflow-y-auto rounded-lg bg-[#0d0d0d] w-full" :class="{ hidden: activeTab !== tab }" tabindex="0">
          <!-- List Items -->
          <div class="space-y-2">
            <div v-for="(item, index) in currentList" :key="item.id" class="settings-item flex items-center justify-between rounded-lg border border-white/10 bg-[#1a1a1a] p-3 transition-all hover:border-white/20" :style="{ borderColor: `${item.color}55` }" tabindex="0" @click="handleEdit(item)" @keydown="handleKeyNavigation($event, 'item', index)" :aria-label="`${item.name} ${tab === 'cat' ? 'category' : tab === 'sta' ? 'status' : 'priority'}`">
              <div class="flex items-center space-x-4">
                <span v-if="item.icon_id" v-html="getIcon(item.icon_id)" class="h-5 w-5 mb-1" :style="{ color: item.color }" aria-hidden="true"></span>
                <div class="flex flex-col">
                  <span class="text-white">{{ item.name }}</span>
                  <span v-if="item.description" class="text-sm text-white/50">{{ item.description }}</span>
                </div>
              </div>
              <div class="flex items-center space-x-2">
                <button class="rounded p-2 text-white/50 transition-all duration-150 hover:bg-white/5 hover:text-white" @click.stop="handleEdit(item)" :disabled="loading.update || updateQueue.has(item.id)" aria-label="Edit item">
                  <div v-if="updateQueue.has(item.id)" class="h-5 w-5 animate-spin rounded-full border-2 border-white/20 border-t-white"></div>
                  <PencilIcon v-else class="h-5 w-5" />
                </button>
                <button class="rounded p-2 text-red-500 transition-all duration-150 hover:bg-red-500/10" @click.stop="handleDelete(item, $event)" :disabled="loading.delete" aria-label="Delete item">
                  <div v-if="loading.delete" class="h-5 w-5 animate-spin rounded-full border-2 border-red-500/20 border-t-red-500"></div>
                  <TrashIcon v-else class="h-5 w-5" />
                </button>
              </div>
            </div>

            <!-- Empty state -->
            <div v-if="currentList.length === 0" class="flex flex-col items-center justify-center rounded-lg border border-dashed border-white/10 p-6 text-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="mb-3 h-12 w-12 text-white/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              <p class="text-sm text-white/50">No {{ tab === "cat" ? "categories" : tab === "sta" ? "statuses" : "priorities" }} found</p>
              <p class="mt-1 text-xs text-white/30">Add one to get started</p>
            </div>
          </div>
        </div>

        <!-- Add Button -->
        <div class="mt-4 flex justify-end pt-3">
          <button @click="handleAdd" :disabled="loading.form" class="add-button flex items-center gap-2 rounded-lg bg-indigo-500 px-4 py-2 text-white transition-all duration-150 hover:bg-indigo-600" aria-label="Add new item">
            <PlusIcon class="h-4 w-4" />
            Add {{ tabName }}
          </button>
        </div>

        <!-- Add/Edit Modal -->
        <DialogModal :show="showModal" @close="closeModal">
          <template #hea>
            <div id="modal-title" class="text-lg font-medium text-white">{{ modalType === "create" ? "Add" : "Edit" }} {{ currentType }}</div>
          </template>

          <template #content>
            <div class="space-y-4 rounded-lg bg-[#0d0d0d] p-4">
              <div>
                <InputLabel for="name" value="Name" class="text-white" />
                <TextInput id="name" ref="nameInput" v-model="form.name" type="text" class="mt-1 block w-full border-white/10 bg-[#1a1a1a] text-white" :error="errors.name" required autocomplete="off" />
                <InputError :message="errors.name" />
              </div>

              <div>
                <InputLabel for="description" value="Description" class="text-white" />
                <TextInput id="description" v-model="form.description" type="text" class="mt-1 block w-full border-white/10 bg-[#1a1a1a] text-white" aria-label="Description" autocomplete="off" />
              </div>

              <div>
                <InputLabel for="color" value="Color" class="text-white" />
                <ColorPicker id="color" v-model="form.color" class="mt-1" :error="errors.color" aria-label="Select color" required />
                <InputError :message="errors.color" />
              </div>

              <div>
                <InputLabel for="icon" value="Icon" class="text-white" />
                <div class="custom-scrollbar mt-4 grid max-h-48 grid-cols-8 gap-2 overflow-y-auto rounded-lg border border-white/10 bg-[#1a1a1a] p-4" aria-label="Select an icon">
                  <button v-for="icon in props.icons" :key="icon.id" class="flex h-8 w-8 items-center justify-center rounded-lg border border-white/10 transition-all duration-150" :class="form.icon_id === icon.id ? 'border-white bg-white/10' : 'hover:bg-white/5'" @click="form.icon_id = icon.id" @keydown="handleIconKeyDown($event, icon)" type="button" :aria-selected="form.icon_id === icon.id" tabindex="0">
                    <span v-html="icon.data" class="h-5 w-5" :style="{ color: form.color }"></span>
                  </button>
                </div>
                <InputError :message="errors.icon" />
              </div>
            </div>
          </template>

          <template #footer>
            <div class="flex justify-end space-x-3 bg-[#0d0d0d] px-4 py-3">
              <button class="rounded-lg bg-white/5 px-4 py-2 text-sm font-medium text-white/70 transition-all duration-150 hover:bg-white/10 hover:text-white" @click="closeModal" type="button">Cancel</button>
              <button class="flex items-center space-x-2 rounded-lg bg-indigo-500 px-4 py-2 text-sm font-medium text-white transition-all duration-150 hover:bg-indigo-600" @click="handleSave" :disabled="loading.form" type="button">
                <svg v-if="loading.form" class="h-4 w-4 animate-spin" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" />
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                </svg>
                <span>{{ modalType === "create" ? "Add" : "Save Changes" }}</span>
              </button>
            </div>
          </template>
        </DialogModal>
      </div>
    </div>
  </Teleport>
</template>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.2s ease-in-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.slide-enter-active,
.slide-leave-active {
  transition:
    transform 0.3s ease,
    opacity 0.3s ease;
}

.slide-enter-from,
.slide-leave-to {
  transform: translateY(-10px);
  opacity: 0;
}

/* Dark mode form styles */
:deep(.form-input),
:deep(.form-textarea) {
  background-color: #1a1a1a;
  border-color: rgba(255, 255, 255, 0.1);
  color: white;
}

:deep(.form-input:focus),
:deep(.form-textarea:focus) {
  border-color: rgba(99, 102, 241, 0.5);
  box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.25);
}

:deep(.form-label) {
  color: rgba(255, 255, 255, 0.7);
}

/* Scrollbar styles */
.custom-scrollbar {
  scrollbar-width: thin;
  scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
}

.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: rgba(255, 255, 255, 0.1);
  border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background-color: rgba(255, 255, 255, 0.2);
}

/* Focus styles */
.tab-button:focus-visible,
.settings-item:focus-visible,
button:focus-visible {
  outline: 2px solid rgb(99, 102, 241);
  outline-offset: 2px;
}
</style>
