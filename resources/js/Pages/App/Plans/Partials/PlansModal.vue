<script setup>
import { ref, computed, watch, nextTick, onMounted, onBeforeUnmount } from "vue";
import { router } from "@inertiajs/vue3";
import Multiselect from "@/Components/Multiselect.vue";
import ChecklistModal from "./ChecklistModal.vue";
import axios from "axios";
import Datepicker from "@/Components/Datepicker.vue";
import { XMarkIcon, PhotoIcon, ArrowUpTrayIcon, TrashIcon, PlusIcon, SparklesIcon, ArrowPathIcon } from "@heroicons/vue/24/outline";
import { useToast } from "vue-toastification";
import { usePlans } from "@/Composables/usePlans";
import InputLabel from "@/Components/InputLabel.vue";
import DialogModal from "@/Components/DialogModal.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import AutoResizeTextarea from "@/Components/AutoResizeTextarea.vue";

const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  mode: {
    type: String,
    default: "create",
  },
  action: {
    type: String,
    default: "create",
  },
  item: {
    type: Object,
    default: null,
  },
  categories: {
    type: Array,
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
  icons: {
    type: Array,
    required: true,
  },
  users: {
    type: Array,
    required: true,
  },
});

const emit = defineEmits(["close", "update", "saved"]);

const toast = useToast();
const { isLoading } = usePlans();

// Initialize form with empty values or existing item data
const form = ref({
  id: props.item?.id || null,
  title: props.item?.title || "",
  description: props.item?.description || "",
  status_id: props.item?.status_id || null,
  priority_id: props.item?.priority_id || null,
  category_id: props.item?.category_id || null,
  date: props.item?.date || null,
  // assigned_users: props.item?.assigned_users || [],
  // attachments: props.item?.attachments || [],
  // checklists: props.item?.checklists || [],
  // image: props.item?.image || null,
  // recurring: props.item?.recurring || false,
  // recurring_interval: props.item?.recurring_interval || null,
});

// Track form errors and submission state
const formErrors = ref({});
const isSubmitting = ref(false);
const fieldLoading = ref({}); // Track loading state per field

// Modal state
const showAttachmentModal = ref(false);
const showChecklistModal = ref(false);
const currentAttachment = ref(null);

// Add image state
const imageFile = ref(null);
const imagePreview = ref(null);
const showImageGeneration = ref(false);
const generationPrompt = ref("");
const generatedImages = ref([]);
const selectedGeneratedImage = ref(null);

// User assignment state
const userSearchQuery = ref("");
const showUserAssignment = ref(false);

// Computed properties for user filtering
const filteredUsers = computed(() => {
  if (!userSearchQuery.value) return props.users;

  const query = userSearchQuery.value.toLowerCase();
  return props.users.filter((user) => user.name.toLowerCase().includes(query) || user.email.toLowerCase().includes(query));
});

const selectedUserIds = computed(() => form.value.assigned_users.map((user) => user.id));

watch(
  () => props.show,
  (value) => {
    if (value) {
      resetForm();
      nextTick(() => {
        document.querySelector("#title")?.focus();
      });
    }
  },
);

const resetForm = () => {
  imagePreview.value = null;
  imageFile.value = null;
  generationPrompt.value = "";
  generatedImages.value = [];
  selectedGeneratedImage.value = null;
  showImageGeneration.value = false;
  formErrors.value = {};
  fieldLoading.value = {};
  if (props.item) {
    form.id = props.item.id;
    form.title = props.item.title || "";
    form.description = props.item.description || "";
    form.status_id = props.item.status_id || null;
    form.priority_id = props.item.priority_id || null;
    form.category_id = props.item.category_id || null;
    form.date = props.item.date || null;
    // form.assigned_users = props.item.assigned_users || [];
    // form.attachments = props.item.attachments || [];
    // form.checklists = props.item.checklists || [];
    // form.image = props.item.image || null;
    // form.recurring = props.item.recurring || false;
    // form.recurring_interval = props.item.recurring_interval || null;
  } else {
    form.status_id = props.statuses.length ? props.statuses[0].id : null;
    form.priority_id = props.priorities.length ? props.priorities[0].id : null;
    form.category_id = props.categories.length ? props.categories[0].id : null;
  }
};

const closeModal = () => {
  emit("close");
};

const submitForm = async () => {
  isSubmitting.value = true;
  formErrors.value = {};

  let hasErrors = false;
  if (!form.value.title.trim()) {
    formErrors.value.title = "Title is required";
    hasErrors = true;
  }

  if (!form.value.date) {
    formErrors.value.date = "Invalid date format";
    hasErrors = true;
  }

  if (hasErrors) {
    isSubmitting.value = false;
    return;
  }

  let formData = new FormData();
  Object.entries(form.value).forEach(([key, value]) => {
    formData.append(key, value);
  });

  try {
    const response = await axios({
      method: "post",
      url: route(props.item ? "items.update" : "items.store"),
      data: formData,
      headers: { "Content-Type": "multipart/form-data" },
    });

    let successMessage = "";
    if (typeof response.data === "string") {
      successMessage = response.data;
    } else if (response.data.message) {
      successMessage = response.data.message;
    } else if (response.data.success) {
      successMessage = response.data.success;
    } else {
      successMessage = props.item ? "Item updated successfully" : "Item created successfully";
    }

    toast.success(successMessage);
    emit("saved");
    closeModal();
    router.reload({ only: ["items"] });
  } catch (err) {
    console.error("Operation failed:", err);
    let errorMessage = "";
    if (err.response) {
      if (typeof err.response.data === "string") {
        errorMessage = err.response.data;
      } else if (err.response.data.message) {
        errorMessage = err.response.data.message;
      } else if (err.response.data.error) {
        errorMessage = err.response.data.error;
      } else {
        errorMessage = "An error occurred";
      }
    } else {
      errorMessage = "Network error occurred";
    }

    toast.error(errorMessage);
    formErrors.value = err.response?.data?.errors[0] || { title: errorMessage };
  } finally {
    isSubmitting.value = false;
  }
};

const handleKeyDown = (e) => {
  if (props.show && e.key === "Escape") closeModal();
};

onMounted(() => {
  document.addEventListener("keydown", handleKeyDown);
});

onBeforeUnmount(() => {
  document.removeEventListener("keydown", handleKeyDown);
});

const modalTitle = computed(() => {
  const actionType = props.mode || props.action;

  switch (actionType) {
    case "create":
      return "Create Item";
    case "edit":
      return "Edit Item";
    case "delete":
      return "Delete Item";
    case "duplicate":
      return "Duplicate Item";
  }
});

// const handleUserAssign = async (userIds) => {
//   try {
//     const response = await axios.post(route("items.assign"), {
//       user_ids: userIds,
//     });

//     if (response.data.success) {
//       form.value.assigned_users = userIds;
//       toast.success("Users assigned successfully");
//     } else {
//       throw new Error(response.data.message || "Failed to assign users");
//     }
//   } catch (error) {
//     console.error("Error assigning users:", error);
//     toast.error(error.response?.data?.message || "Failed to assign users");
//   }
// };

// const handleFileUpload = async (event) => {
//   const file = event.target.files[0];
//   if (!file) return;

//   try {
//     isLoading.value = true;
//     const formData = new FormData();
//     formData.append("file", file);

//     const {
//       data: { url, key },
//     } = await axios.post(route("items.attachments.presign"), {
//       filename: file.name,
//       contentType: file.type,
//     });

//     await axios.post(url, file, {
//       headers: {
//         "Content-Type": file.type,
//       },
//     });

//     const response = await axios.post(route("items.attachments.store", { plan: props.item?.id }), {
//       key,
//       filename: file.name,
//       size: file.size,
//       type: file.type,
//     });

//     form.value.attachments = [...form.value.attachments, response.data];
//     toast.success("File uploaded successfully");
//   } catch (error) {
//     console.error("Error uploading file:", error);
//     toast.error(error.response?.data?.message || "Failed to upload file");
//   } finally {
//     isLoading.value = false;
//   }
// };

// const handleFileDelete = async (attachmentId) => {
//   try {
//     isLoading.value = true;
//     await axios.delete(
//       route("items.attachments.destroy", {
//         plan: props.item?.id,
//         attachment: attachmentId,
//       }),
//     );

//     form.value.attachments = form.value.attachments.filter((a) => a.id !== attachmentId);
//     toast.success("File deleted successfully");
//   } catch (error) {
//     console.error("Error deleting file:", error);
//     toast.error(error.response?.data?.message || "Failed to delete file");
//   } finally {
//     isLoading.value = false;
//   }
// };

// const handleChecklistUpdate = async (items) => {
//   try {
//     const response = await axios.post(route("items.checklists.store", { item: props.item.id }), {
//       items: items,
//     });

//     if (response.data.success) {
//       form.value.checklists = items;
//       toast.success("Checklist updated successfully");
//     } else {
//       throw new Error(response.data.message || "Failed to update checklist");
//     }
//   } catch (error) {
//     console.error("Error updating checklist:", error);
//     toast.error(error.response?.data?.message || "Failed to update checklist");
//   }
// };

// const handleImageUpload = async (event) => {
//   const file = event.target.files[0];
//   if (!file) return;

//   if (!file.type.startsWith("image/")) {
//     toast.error("Please upload an image file");
//     return;
//   }

//   imageFile.value = file;
//   const reader = new FileReader();
//   reader.onload = (e) => {
//     imagePreview.value = e.target.result;
//   };
//   reader.readAsDataURL(file);
// };

// const handleImageDrop = (event) => {
//   event.preventDefault();
//   const file = event.dataTransfer.files[0];
//   if (!file) return;

//   if (!file.type.startsWith("image/")) {
//     toast.error("Please drop an image file");
//     return;
//   }

//   imageFile.value = file;
//   const reader = new FileReader();
//   reader.onload = (e) => {
//     imagePreview.value = e.target.result;
//   };
//   reader.readAsDataURL(file);
// };

// const handleImageGenerate = async () => {
//   if (!generationPrompt.value) {
//     toast.error("Please enter a prompt for image generation");
//     return;
//   }

//   try {
//     isLoading.value = true;
//     const response = await axios.post(route("images.generate"), {
//       prompt: generationPrompt.value,
//     });

//     generatedImages.value = response.data.images;
//     showImageGeneration.value = true;
//   } catch (error) {
//     console.error("Error generating images:", error);
//     toast.error(error.response?.data?.message || "Failed to generate images");
//   } finally {
//     isLoading.value = false;
//   }
// };

// const selectGeneratedImage = async (image) => {
//   try {
//     isLoading.value = true;
//     const response = await axios.post(route("images.store"), {
//       url: image.url,
//       filename: "generated-image.png",
//       type: "image/png",
//     });

//     form.value.image = response.data.url;
//     imagePreview.value = image.url;
//     showImageGeneration.value = false;
//     toast.success("Generated image selected successfully");
//   } catch (error) {
//     console.error("Error selecting generated image:", error);
//     toast.error(error.response?.data?.message || "Failed to select generated image");
//   } finally {
//     isLoading.value = false;
//   }
// };

// const removeImage = () => {
//   form.value.image = null;
//   imagePreview.value = null;
//   imageFile.value = null;
// };
</script>

<template>
  <DialogModal :show="show">
    <template #header>
      <div class="mb-5 flex w-full items-center justify-between">
        <h3 id="modal-title" class="text-xl font-semibold text-white">{{ modalTitle }}</h3>
        <button type="button" class="rounded-md text-gray-400 transition-colors hover:text-white" @click="closeModal" aria-label="Close modal">
          <XMarkIcon class="h-6 w-6" />
        </button>
      </div>
    </template>
    <template #content>
      <form @submit.prevent="submitForm" class="w-full">
        <div class="mb-5">
          <InputLabel for="title" value="Title" />
          <TextInput id="title" v-model="form.title" type="text" class="mt-1 block w-full" :class="{ 'border-red-500': formErrors.title }" placeholder="Enter title" required />
          <InputError :message="formErrors.title" class="mt-1" />
        </div>

        <div class="mb-5">
          <InputLabel for="description" value="Description" />
          <AutoResizeTextarea id="description" v-model="form.description" class="mt-1 block w-full" placeholder="Enter description" />
        </div>

        <div class="mb-5 flex items-center gap-5">
          <div class="flex flex-col gap-2">
            <InputLabel for="status" value="Status" />
            <select id="status" v-model="form.status_id" class="w-full rounded-lg border border-gray-700 bg-gray-800 px-4 py-3 text-base text-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
              <option v-for="status in statuses" :key="status.id" :value="status.id">
                {{ status.name }}
              </option>
            </select>
          </div>

          <!-- Priority -->
          <div class="flex flex-col gap-2">
            <InputLabel for="priority" value="Priority" />
            <select id="priority" v-model="form.priority_id" class="w-full rounded-lg border border-gray-700 bg-gray-800 px-4 py-3 text-base text-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
              <option v-for="priority in priorities" :key="priority.id" :value="priority.id">
                {{ priority.name }}
              </option>
            </select>
          </div>
        </div>

        <div class="mb-5 flex items-center gap-5">
          <div class="flex flex-col gap-2">
            <InputLabel for="category" value="Category" />
            <select id="category" v-model="form.category_id" class="w-full rounded-lg border border-gray-700 bg-gray-800 px-4 py-3 text-base text-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name }}
              </option>
            </select>
          </div>

          <div class="flex flex-col gap-2">
            <InputLabel for="date" value="Due Date" />
            <input id="date" v-model="form.date" type="date" class="w-full rounded-lg border border-gray-700 bg-gray-800 px-4 py-3 text-base text-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500" :class="{ 'border-red-500': formErrors.date }" />
            <InputError :message="formErrors.date" class="mt-1" />
          </div>
        </div>

        <!-- Recurring Task -->
        <div class="mb-5 flex flex-col gap-2">
          <div class="flex items-center space-x-2">
            <input id="recurring-toggle" v-model="form.recurring" type="checkbox" class="h-5 w-5 rounded border-gray-600 bg-gray-800 text-indigo-500 transition-colors focus:ring-indigo-500 focus:ring-offset-gray-900" />
            <InputLabel for="recurring-toggle" value="Recurring task" />
          </div>
          <div v-if="form.recurring" class="mt-3">
            <select v-model="form.recurring_interval" class="w-full rounded-lg border border-gray-700 bg-gray-800 px-4 py-3 text-base text-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
              <option value="daily">Daily</option>
              <option value="weekly">Weekly</option>
              <option value="monthly">Monthly</option>
              <option value="yearly">Yearly</option>
            </select>
          </div>
        </div>

        <!-- Actions -->
        <div class="mt-2 flex justify-end space-x-3">
          <button type="button" class="focus-ring rounded-lg border border-gray-700 bg-gray-800 px-5 py-2.5 text-base text-gray-300 transition-colors hover:bg-gray-700 hover:text-white" @click="closeModal">Cancel</button>
          <button type="submit" class="focus-ring flex items-center rounded-lg bg-indigo-600 px-5 py-2.5 text-base font-semibold text-white transition-colors hover:bg-indigo-700" :disabled="isSubmitting">
            <svg v-if="isSubmitting" class="mr-2 h-5 w-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ item ? "Update" : "Create" }}
          </button>
        </div>
      </form>
    </template>
    <!-- <template #footer> -->
    <!-- <ChecklistModal v-if="showChecklistModal" :show="showChecklistModal" :item="item" :checklists="form.checklists" @close="showChecklistModal = false" @update="handleChecklistUpdate" /> -->
    <!-- </template> -->
  </DialogModal>
</template>

<style scoped>
.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

/* Improved dark mode input styles */
input[type="date"]::-webkit-calendar-picker-indicator {
  filter: invert(1);
  opacity: 0.5;
}

/* Transition styles */
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
  transform: translateY(20px);
  opacity: 0;
}

/* Custom focus styles */
.focus-ring {
  @apply focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-75 dark:focus:ring-indigo-400;
}

/* Dark mode specific styles */
.modal-backdrop {
  @apply bg-gray-900/75 dark:bg-black/80;
}

.modal-container {
  @apply m-auto flex h-auto w-full max-w-xl flex-col items-center justify-center rounded-2xl border border-gray-700 bg-gray-800 shadow-2xl;
}

/* Add styles for form inputs */
input[type="date"]::-webkit-calendar-picker-indicator {
  filter: invert(1);
  opacity: 0.5;
}

.image-preview {
  position: relative;
  width: 100%;
  padding-bottom: 56.25%; /* 16:9 aspect ratio */
}

.image-preview img {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 0.5rem;
}

.image-preview .remove-button {
  position: absolute;
  top: 0.5rem;
  right: 0.5rem;
  z-index: 10;
}

/* Add loading animation */
@keyframes pulse {
  0%,
  100% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

[required] + .text-white::after {
  content: " *";
  color: #ef4444;
}

.field-loading {
  position: relative;
}

.field-loading::after {
  content: "";
  position: absolute;
  right: 10px;
  top: 50%;
  width: 16px;
  height: 16px;
  margin-top: -8px;
  border-radius: 50%;
  border: 2px solid rgba(255, 255, 255, 0.2);
  border-top-color: white;
  animation: spin 1s linear infinite;
}

.error-field {
  border-color: #ef4444 !important;
  box-shadow: 0 0 0 1px #ef4444 !important;
}

</style>
