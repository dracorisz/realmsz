<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from "vue";
import { router } from "@inertiajs/vue3";
import { debounce } from "lodash";
import axios from "axios";
import AppLayout from "@/Layouts/AppLayout.vue";
import PlansItem from "./Partials/PlansItem.vue";
import PlansSettings from "./Partials/PlansSettings.vue";
import Multiselect from "@/Components/Multiselect.vue";
import PlansModal from "./Partials/PlansModal.vue";
import { ArchiveBoxIcon, PlusIcon, Cog6ToothIcon, MagnifyingGlassIcon, ListBulletIcon, Squares2X2Icon, AdjustmentsHorizontalIcon, ArrowPathIcon, XMarkIcon, FunnelIcon, ArrowDownIcon, ArrowUpIcon, DocumentMagnifyingGlassIcon } from "@heroicons/vue/24/outline";
import { usePlans } from "@/Composables/usePlans";
import ToastManager from "@/Services/ToastManager";

const props = defineProps({
  items: {
    type: [Object, Array],
    required: true,
  },
  users: {
    type: [Object, Array],
    required: true,
  },
  statuses: {
    type: [Object, Array],
    required: true,
  },
  priorities: {
    type: [Object, Array],
    required: true,
  },
  categories: {
    type: [Object, Array],
    required: true,
  },
  icons: {
    type: [Object, Array],
    required: true,
  },
});

const { showSettings, gridView, loading, search, selectedItems, statusPopups, priorityPopups, datePopups, moreActions,  toggleGridView, toggleSettings, handleRefresh: plansRefresh, handleTogglePopup } = usePlans();

const showArchived = ref(false);
const showFilters = ref(false);
const selectedCategories = ref([]);
const selectedStatuses = ref([]);
const selectedPriorities = ref([]);
const dateRange = ref([null, null]);
const sorting = ref({
  field: "status_id",
  direction: "asc",
});

const settingsData = ref({
  categories: [],
  statuses: [],
  priorities: [],
});

const showAddModal = ref(false);
const modalMode = ref("create");
const selectedItem = ref(null);

const handleError = (error, defaultMessage = "An error occurred") => {
  console.error("Error:", error);
  const message = error.response?.data?.message || error.message || defaultMessage;
  ToastManager.error(message);
};

const handleRefresh = async () => {
  loading.value = true;
  try {
    const params = {
      search: search.value,
      categories: selectedCategories.value.map((c) => c.id),
      statuses: selectedStatuses.value.map((s) => s.id),
      priorities: selectedPriorities.value.map((p) => p.id),
      sort_by: sorting.value.field,
      sort_direction: sorting.value.direction,
      archived: showArchived.value,
    };

    await router.reload({
      only: ["items"],
      data: params,
      preserveScroll: true,
    });
  } catch (error) {
    handleError(error, "Failed to refresh items");
  } finally {
    loading.value = false;
  }
};

const handleSettingsRefresh = async () => {
  try {
    await loadData();
    await handleRefresh();
  } catch (error) {
    handleError(error, "Failed to refresh settings");
  }
};

const loadData = async () => {
  loading.value = true;
  try {
    const [categoriesRes, statusesRes, prioritiesRes] = await Promise.all([axios.get(route("category.index")), axios.get(route("status.index")), axios.get(route("priority.index"))]);
    settingsData.value = {
      categories: categoriesRes.data,
      statuses: statusesRes.data,
      priorities: prioritiesRes.data,
    };

    selectedCategories.value = selectedCategories.value.filter((cat) => settingsData.value.categories.some((c) => c.id === cat.id));
    selectedStatuses.value = selectedStatuses.value.filter((status) => settingsData.value.statuses.some((s) => s.id === status.id));
    selectedPriorities.value = selectedPriorities.value.filter((priority) => settingsData.value.priorities.some((p) => p.id === priority.id));
  } catch (error) {
    handleError(error, "Failed to load data");
  } finally {
    loading.value = false;
  }
};

const categoryOptions = computed(() =>
  Array.isArray(settingsData.value.categories)
    ? settingsData.value.categories.map((cat) => ({
        id: cat.id,
        name: cat.name,
        color: cat.color,
        icon_id: cat.icon_id,
      }))
    : [],
);

const statusOptions = computed(() =>
  Array.isArray(settingsData.value.statuses)
    ? settingsData.value.statuses.map((status) => ({
        id: status.id,
        name: status.name,
        color: status.color,
        icon_id: status.icon_id,
      }))
    : [],
);

const priorityOptions = computed(() =>
  Array.isArray(settingsData.value.priorities)
    ? settingsData.value.priorities.map((priority) => ({
        id: priority.id,
        name: priority.name,
        color: priority.color,
        icon_id: priority.icon_id,
      }))
    : [],
);

// Add sorting options
const sortOptions = [
  { id: "title", name: "Title" },
  { id: "status_id", name: "Status" },
  { id: "priority_id", name: "Priority" },
  { id: "category_id", name: "Category" },
  { id: "date", name: "Due Date" },
  { id: "created_at", name: "Created Date" },
];

// Data transformation helpers
const transformToArray = (obj) => {
  if (!obj) return [];
  if (Array.isArray(obj)) return obj.filter((item) => item !== null);
  if (typeof obj === "object") return Object.values(obj).filter((item) => item !== null);
  return [];
};

const transformItem = (item) => {
  if (!item || typeof item !== "object") return null;
  return {
    ...item,
    id: Number(item?.id) || 0,
    user_id: Number(item?.user_id) || 0,
    category_id: Number(item?.category_id) || 0,
    priority_id: Number(item?.priority_id) || 0,
    status_id: Number(item?.status_id) || 0,
    archived: Boolean(item?.archived),
  };
};

// Update search functionality
const handleSearchDebounced = debounce(() => {
  if (search.value.length >= 2) {
    handleRefresh();
  }
}, 300);

// Update the watch handlers
watch(
  [selectedCategories, selectedStatuses, selectedPriorities],
  () => {
    handleRefresh();
  },
  { deep: true },
);

watch(search, (newValue) => {
  if (newValue.length >= 2 || newValue.length === 0) {
    handleSearchDebounced();
  }
});

// Update the filter methods
const applyFilters = () => {
  showFilters.value = false;
  handleRefresh();
};

const clearFilters = () => {
  selectedCategories.value = [];
  selectedStatuses.value = [];
  selectedPriorities.value = [];
  dateRange.value = [null, null];
  search.value = "";
  showFilters.value = false;

  // Reset sorting to default
  sorting.value = {
    field: "status_id",
    direction: "asc",
  };

  // Trigger refresh
  handleRefresh();

  ToastManager.info("Filters have been reset");
};

const filteredItems = computed(() => {
  let filtered = transformToArray(props.items);

  if (search.value && search.value.length >= 2) {
    const query = search.value.toLowerCase();
    filtered = filtered.filter((item) => item.title?.toLowerCase().includes(query) || item.description?.toLowerCase().includes(query));
  }

  if (selectedCategories.value.length > 0) {
    filtered = filtered.filter((item) => selectedCategories.value.some((cat) => cat.id === item.category_id));
  }

  if (selectedStatuses.value.length > 0) {
    filtered = filtered.filter((item) => selectedStatuses.value.some((status) => status.id === item.status_id));
  }

  if (selectedPriorities.value.length > 0) {
    filtered = filtered.filter((item) => selectedPriorities.value.some((priority) => priority.id === item.priority_id));
  }

  if (!showArchived.value) {
    filtered = filtered.filter((item) => !item.archived);
  }

  return filtered;
});

// Update sorting
const sortedItems = computed(() => {
  return [...filteredItems.value].sort((a, b) => {
    let aVal = a[sorting.value.field];
    let bVal = b[sorting.value.field];

    // Handle special cases
    if (sorting.value.field === "date") {
      aVal = aVal ? new Date(aVal) : new Date(0);
      bVal = bVal ? new Date(bVal) : new Date(0);
    } else if (sorting.value.field === "priority_id") {
      aVal = settingsData.value.priorities.find((p) => p.id === aVal)?.order || 0;
      bVal = settingsData.value.priorities.find((p) => p.id === bVal)?.order || 0;
    } else if (sorting.value.field === "status_id") {
      aVal = settingsData.value.statuses.find((s) => s.id === aVal)?.order || 0;
      bVal = settingsData.value.statuses.find((s) => s.id === bVal)?.order || 0;
    }

    if (aVal === bVal) return 0;
    const comparison = aVal > bVal ? 1 : -1;
    return sorting.value.direction === "asc" ? comparison : -comparison;
  });
});

// Add computed property for active filters count
const activeFiltersCount = computed(() => {
  let count = 0;
  if (selectedCategories.value.length) count++;
  if (selectedStatuses.value.length) count++;
  if (selectedPriorities.value.length) count++;
  if (dateRange.value[0] || dateRange.value[1]) count++;
  if (search.value) count++;
  return count;
});

// Enhanced computed properties for better empty states
const emptyStateMessage = computed(() => {
  if (loading.value) return "Loading items...";
  if (filteredItems.value.length === 0) {
    if (activeFiltersCount.value > 0) {
      return "No items match your filters";
    }
    if (showArchived.value) {
      return "No archived items";
    }
    return "No items found";
  }
  return "";
});

const emptyStateDescription = computed(() => {
  if (loading.value) return "Please wait while we load your items...";
  if (filteredItems.value.length === 0) {
    if (activeFiltersCount.value > 0) {
      return "Try adjusting your filters or creating a new item";
    }
    if (showArchived.value) {
      return "There are no archived items in this view";
    }
    return "Create your first item to get started";
  }
  return "";
});

const emptyStateIcon = computed(() => {
  if (loading.value) return ArrowPathIcon;
  if (activeFiltersCount.value > 0) return AdjustmentsHorizontalIcon;
  if (showArchived.value) return ArchiveBoxIcon;
  return DocumentMagnifyingGlassIcon;
});

const handleAddItem = () => {
  modalMode.value = "create";
  selectedItem.value = null;
  showAddModal.value = true;
};

const handleEditItem = (item) => {
  modalMode.value = "edit";
  selectedItem.value = { ...item };
  showAddModal.value = true;
};

const handleModalClose = () => {
  showAddModal.value = false;
  selectedItem.value = null;
  handleRefresh();
};

const handleExport = async () => {
  loading.value = true;
  try {
    const response = await axios.post(
      route("items.export"),
      {
        ids: selectedItems.value.map((item) => item.id),
      },
      {
        responseType: "blob",
      },
    );

    const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement("a");
    link.href = url;
    link.setAttribute("download", "plans.csv");
    document.body.appendChild(link);
    link.click();
    link.remove();
    window.URL.revokeObjectURL(url);

    ToastManager.success("Export completed successfully");
  } catch (error) {
    handleError(error, "Failed to export items");
  } finally {
    loading.value = false;
  }
};

const handleFilterKeyDown = (event) => {
  if (event.key === "Escape") {
    showFilters.value = false;
  }
};

const handleClickOutside = (event) => {
  if (showFilters.value) {
    const filterPanel = document.querySelector(".filters-panel");
    const filterButton = document.querySelector(".filter-button");

    if (filterPanel && filterButton && !filterPanel.contains(event.target) && !filterButton.contains(event.target)) {
      showFilters.value = false;
    }
  }
};

const handleArchiveToggle = () => {
  showArchived.value = !showArchived.value;
  ToastManager.info(showArchived.value ? "Showing archived items" : "Hiding archived items");
  handleRefresh();
};

const handleReorder = async ({ fromId, toId }) => {
  if (!fromId || !toId || fromId === toId) return;

  loading.value = true;
  try {
    // Send the reorder request to the server
    await axios.post(route("items.reorder"), {
      from_id: fromId,
      to_id: toId,
    });

    // Refresh the items after reordering
    handleRefresh();
  } catch (error) {
    console.error("Error reordering item:", error);
    ToastManager.error(error.response?.data?.message || "Failed to reorder item");
  } finally {
    loading.value = false;
  }
};

const addModal = () => {
  modalMode.value = "create";
  selectedItem.value = null;
  showAddModal.value = true;
};

onMounted(() => {
  const savedLayout = localStorage.getItem("plansGridLayout");
  if (savedLayout !== null) gridView.value = savedLayout === "true";

  loadData();
  document.addEventListener("keydown", handleFilterKeyDown);
  document.addEventListener("click", handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener("keydown", handleFilterKeyDown);
  document.removeEventListener("click", handleClickOutside);
});
</script>

<template>
  <AppLayout title="Plans">
    <template #header>
      <div class="w-full rounded-xl border border-white/10 bg-gray-900/95 backdrop-blur-xl">
        <div class="w-full px-5">
          <div class="flex h-16 items-center justify-between">
            <div class="flex items-center space-x-6">
              <h2 class="text-xl font-semibold text-white">Plans</h2>
              <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                  <MagnifyingGlassIcon class="h-5 w-5 text-gray-500" />
                </div>
                <input v-model="search" type="text" placeholder="Search items..." />
              </div>
            </div>
            <template v-if="!emptyStateMessage">
              <div class="mx-auto flex items-center space-x-3">
                <span v-if="selectedItems.length" class="inline-flex items-center rounded-full bg-indigo-500/20 px-2.5 py-0.5 text-xs font-medium text-indigo-300"> {{ selectedItems.length }} selected </span>
                <span class="inline-flex items-center rounded-full bg-white/10 px-2.5 py-0.5 text-xs font-medium text-white/70"> {{ filteredItems.length }} visible </span>
                <span v-if="items.length - filteredItems.length > 0" class="inline-flex items-center rounded-full bg-white/10 px-2.5 py-0.5 text-xs font-medium text-white/70"> {{ items.length - filteredItems.length }} hidden </span>
              </div>
            </template>

            <div class="flex items-center space-x-3">
              <div class="flex max-w-[72px] items-center rounded-lg bg-white/5 p-1">
                <button class="rounded-md p-1.5 transition-colors" :class="!gridView ? 'bg-white/10 text-white' : 'text-white/50 hover:text-white'" @click="toggleGridView" title="List View">
                  <ListBulletIcon class="h-5 w-5" />
                </button>
                <button class="rounded-md p-1.5 transition-colors" :class="gridView ? 'bg-white/10 text-white' : 'text-white/50 hover:text-white'" @click="toggleGridView" title="Grid View">
                  <Squares2X2Icon class="h-5 w-5" />
                </button>
              </div>

              <button class="filter-button relative flex items-center space-x-2 rounded-lg bg-white/5 px-3 py-1.5 text-sm text-white/70 transition-colors hover:bg-white/10 hover:text-white" @click="showFilters = !showFilters" :class="{ 'bg-indigo-500/20 text-indigo-300 hover:bg-indigo-500/30 hover:text-white': showFilters || activeFiltersCount > 0 }">
                <FunnelIcon class="h-5 w-5" :class="{ 'text-indigo-400': activeFiltersCount > 0 }" />
                <span>Filters</span>
                <span v-if="activeFiltersCount > 0" class="animate-pulse-subtle absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-indigo-500 text-xs font-medium text-white shadow-lg">
                  {{ activeFiltersCount }}
                </span>
              </button>

              <button class="rounded-lg bg-white/5 p-2 text-white/70 transition-colors hover:bg-white/10 hover:text-white" @click="handleRefresh" :disabled="loading" title="Refresh">
                <svg v-if="loading" class="h-5 w-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <ArrowPathIcon v-else class="h-5 w-5" />
              </button>

              <button class="flex items-center space-x-2 rounded-lg bg-indigo-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-600" @click.stop="handleAddItem">
                <PlusIcon class="h-5 w-5" />
                <span>Add Item</span>
              </button>

              <!-- Settings -->
              <button class="rounded-lg bg-white/5 p-2 text-white/70 transition-colors hover:bg-white/10 hover:text-white" @click="toggleSettings" title="Settings">
                <Cog6ToothIcon class="h-5 w-5" />
              </button>
            </div>
          </div>

          <!-- Filters Panel -->
          <Teleport to="body">
            <div v-if="showFilters" class="fixed inset-0 z-[2000] overflow-hidden">
              <div class="absolute inset-0 bg-black/60" @click="showFilters = false"></div>

              <div class="relative flex items-start justify-center pt-16">
                <div class="filters-panel w-full max-w-6xl rounded-lg border border-white/10 bg-gray-900 p-6 shadow-2xl">
                  <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-medium text-white">Filters</h3>
                    <button class="rounded-lg p-2 text-white/50 transition-all duration-150 hover:bg-white/5 hover:text-white" @click="showFilters = false">
                      <XMarkIcon class="h-5 w-5" />
                    </button>
                  </div>

                  <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                    <div class="space-y-2">
                      <label class="flex items-center justify-between">
                        <span class="text-sm font-medium text-white/70">Categories</span>
                        <span v-if="selectedCategories.length" class="text-xs text-white/50"> {{ selectedCategories.length }} selected </span>
                      </label>
                      <Multiselect v-model="selectedCategories" :options="categoryOptions" :multiple="true" track-by="id" label="name" placeholder="Select categories" class="multiselect-dark">
                        <template #option="{ option }">
                          <div class="flex items-center space-x-2">
                            <div class="h-3 w-3 rounded-full" :style="{ backgroundColor: option.color }"></div>
                            <span>{{ option.name }}</span>
                          </div>
                        </template>
                        <template #selected-option="{ option }">
                          <div class="flex items-center space-x-2">
                            <div class="h-3 w-3 rounded-full" :style="{ backgroundColor: option.color }"></div>
                            <span>{{ option.name }}</span>
                          </div>
                        </template>
                      </Multiselect>
                    </div>

                    <div class="space-y-2">
                      <label class="flex items-center justify-between">
                        <span class="text-sm font-medium text-white/70">Statuses</span>
                        <span v-if="selectedStatuses.length" class="text-xs text-white/50"> {{ selectedStatuses.length }} selected </span>
                      </label>
                      <Multiselect v-model="selectedStatuses" :options="statusOptions" :multiple="true" track-by="id" label="name" placeholder="Select statuses" class="multiselect-dark">
                        <template #option="{ option }">
                          <div class="flex items-center space-x-2">
                            <div class="h-3 w-3 rounded-full" :style="{ backgroundColor: option.color }"></div>
                            <span>{{ option.name }}</span>
                          </div>
                        </template>
                        <template #selected-option="{ option }">
                          <div class="flex items-center space-x-2">
                            <div class="h-3 w-3 rounded-full" :style="{ backgroundColor: option.color }"></div>
                            <span>{{ option.name }}</span>
                          </div>
                        </template>
                      </Multiselect>
                    </div>

                    <div class="space-y-2">
                      <label class="flex items-center justify-between">
                        <span class="text-sm font-medium text-white/70">Priorities</span>
                        <span v-if="selectedPriorities.length" class="text-xs text-white/50"> {{ selectedPriorities.length }} selected </span>
                      </label>
                      <Multiselect v-model="selectedPriorities" :options="priorityOptions" :multiple="true" track-by="id" label="name" placeholder="Select priorities" class="multiselect-dark">
                        <template #option="{ option }">
                          <div class="flex items-center space-x-2">
                            <div class="h-3 w-3 rounded-full" :style="{ backgroundColor: option.color }"></div>
                            <span>{{ option.name }}</span>
                          </div>
                        </template>
                        <template #selected-option="{ option }">
                          <div class="flex items-center space-x-2">
                            <div class="h-3 w-3 rounded-full" :style="{ backgroundColor: option.color }"></div>
                            <span>{{ option.name }}</span>
                          </div>
                        </template>
                      </Multiselect>
                    </div>

                    <div class="space-y-2">
                      <label class="flex items-center justify-between">
                        <span class="text-sm font-medium text-white/70">Sort By</span>
                        <button class="text-xs text-white/50 transition-colors hover:text-white" @click="sorting.direction = sorting.direction === 'asc' ? 'desc' : 'asc'">
                          {{ sorting.direction === "asc" ? "↑ Ascending" : "↓ Descending" }}
                        </button>
                      </label>
                      <Multiselect v-model="sorting.field" :options="sortOptions" track-by="id" label="name" placeholder="Select sort field" class="multiselect-dark" />
                    </div>
                  </div>

                  <div class="mt-6 flex justify-end space-x-3">
                    <button class="group flex items-center space-x-2 rounded-lg bg-white/5 px-4 py-2 text-sm text-white/70 transition-all hover:bg-white/10 hover:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/50" @click="handleArchiveToggle">
                      <ArchiveBoxIcon class="h-5 w-5" />
                      <span>{{ showArchived ? "Hide Archived" : "Show Archived" }}</span>
                    </button>
                    <button class="group rounded-lg bg-white/5 px-4 py-2 text-sm text-white/70 transition-all hover:bg-white/10 hover:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/50" @click="clearFilters">
                      <span class="flex items-center space-x-2">
                        <XMarkIcon class="h-5 w-5" />
                        <span>Reset Filters</span>
                      </span>
                    </button>
                    <button class="group rounded-lg bg-indigo-500 px-4 py-2 text-sm font-medium text-white transition-all hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/50" @click="applyFilters">
                      <span class="flex items-center space-x-2">
                        <FunnelIcon class="h-5 w-5" />
                        <span>Apply Filters</span>
                      </span>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </Teleport>
        </div>
      </div>
    </template>

    <div class="mx-auto h-full w-full">
      <div class="h-full w-full rounded-lg border border-white/10 bg-[#0d0d0d]">
        <div class="h-full w-full p-3">
          <div :class="['grid h-full w-full flex-1 gap-3 overflow-y-auto items-start justify-start overflow-x-hidden pr-3', gridView ? 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3' : 'grid-cols-1', loading ? 'opacity-50' : '']">
            <div v-for="item in sortedItems" :key="item.id" class="relative z-20 w-full transition-all hover:border-white/20" :class="{ 'cursor-move': !gridView, }" :data-item-id="item.id">
              <PlansItem :item="item" :statuses="settingsData.statuses" :priorities="settingsData.priorities" :categories="settingsData.categories" :icons="icons" :show-status-popup="statusPopups[item.id]" :show-priority-popup="priorityPopups[item.id]" :show-date-popup="datePopups[item.id]" :show-more-actions="moreActions[item.id]" :grid-view="gridView" @edit="handleEditItem" @refresh="handleRefresh" @toggle-popup="handleTogglePopup" @reorder="handleReorder" />
            </div>
          </div>

          <!-- Enhanced empty state with better guidance -->
          <div v-if="emptyStateMessage" class="flex flex-col items-center justify-center p-12 text-center">
            <component :is="emptyStateIcon" class="mb-4 h-16 w-16 text-white/50" :class="{ 'animate-spin': loading }" />
            <h3 class="mb-2 text-xl font-semibold text-white">{{ emptyStateMessage }}</h3>
            <p class="mb-6 max-w-md text-white/70">{{ emptyStateDescription }}</p>

            <div class="flex space-x-4">
              <template v-if="activeFiltersCount > 0">
                <button @click="clearFilters" class="inline-flex items-center rounded-md border border-transparent bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-white/20 focus:outline-none">
                  <XMarkIcon class="mr-2 h-4 w-4" />
                  Clear All Filters
                </button>
              </template>

              <template v-if="!loading && filteredItems.length === 0 && !activeFiltersCount && !showArchived">
                <button @click="addModal" class="inline-flex items-center rounded-md border border-transparent bg-indigo-500 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-indigo-600 focus:outline-none">
                  <PlusIcon class="mr-2 h-4 w-4" />
                  Create New Item
                </button>
              </template>
            </div>
          </div>
        </div>
      </div>
    </div>

    <PlansModal v-if="showAddModal" :users="users" :show="showAddModal" :mode="modalMode" :item="selectedItem" :categories="settingsData.categories" :statuses="settingsData.statuses" :priorities="settingsData.priorities" :icons="icons" @close="handleModalClose" />
    <PlansSettings v-if="showSettings" :show="showSettings" :categories="settingsData.categories" :statuses="settingsData.statuses" :priorities="settingsData.priorities" :icons="icons" @close="toggleSettings" @refresh="handleSettingsRefresh" />
  </AppLayout>
</template>

<style scoped lang="pcss">
::deep(.multiselect) {
  @apply rounded-lg border border-white/10 bg-gray-900 backdrop-blur-xl;
}

::deep(.multiselect__content-wrapper) {
  @apply !z-[2000] border-white/10 bg-gray-900 shadow-xl;
}

.multiselect-dark .multiselect__placeholder {
  color: rgba(255, 255, 255, 0.4);
}

.multiselect-dark .multiselect__input {
  background: transparent;
  color: white;
}

.multiselect-dark .multiselect__tags {
  background: var(--ms-bg);
  border-color: var(--ms-border-color);
}

.multiselect-dark .multiselect__content-wrapper {
  background: var(--ms-dropdown-bg);
  border-color: var(--ms-border-color);
  z-index: 2000;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

.animate-spin {
  animation: spin 1s linear infinite;
}

input[type="checkbox"]:indeterminate {
  background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M3 8h10'/%3e%3c/svg%3e");
  border-color: transparent;
  background-color: currentColor;
  background-size: 100% 100%;
  background-position: center;
  background-repeat: no-repeat;
}

button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.animate-slideDown {
  animation: slideDown 0.3s ease-out;
  transform-origin: top;
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes pulse-subtle {
  0%,
  100% {
    opacity: 1;
  }
  50% {
    opacity: 0.8;
  }
}

.animate-pulse-subtle {
  animation: pulse-subtle 2s ease-in-out infinite;
}

.filter-button {
  @apply relative inline-flex max-w-[96px] items-center overflow-hidden px-3 py-1.5 text-sm transition-all;
}

.filter-button::after {
  content: "";
  position: absolute;
  inset: 0;
  background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.1), transparent);
  transform: translateX(-100%);
  transition: transform 0.3s ease;
  @apply max-w-[96px] overflow-hidden;
}

.filter-button:hover::after {
  transform: translateX(100%);
}

.multiselect-dark .multiselect__option {
  @apply transition-all duration-200;
}

.multiselect-dark .multiselect__option:hover {
  @apply scale-[1.02] transform bg-white/10 text-white;
}

.filter-count {
  @apply absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-indigo-500 text-xs font-medium text-white shadow-lg;
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fadeIn 0.3s ease-out;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>

