<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { router } from "@inertiajs/vue3";
import Toggle from "@/Components/Toggle.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import Multiselect from "@/Components/Multiselect.vue";
import DialogModal from "@/Components/DialogModal.vue";
import Datepicker from "@/Components/Datepicker.vue";
import AutoResizeTextarea from "@/Components/AutoResizeTextarea.vue";
import { useToast } from "vue-toastification";
import axios from "axios";
import colors from "tailwindcss/colors";

const props = defineProps({
  items: Array,
  statuses: Array,
  priorities: Array,
  categories: Array,
  icons: Array,
});

const toast = useToast();

const activeRow = ref({
  id: 0,
  category_id: 4,
  priority_id: 1,
  status_id: 1,
  title: "",
  description: "",
  date: "",
  recurring: 0,
  recurring_interval: "",
  image: "",
  archived: 0,
});

const modal = ref({
  active: false,
  title: "",
  text: "",
  button: "",
});

const request = ref("");
const errors = ref({ title: "" });

const actions = (evt, row, action, status) => {
  if (evt) {
    evt.stopImmediatePropagation();
    evt.stopPropagation();
  }

  if (row !== -1) activeRow.value = props.items.find((i) => i.id === row);

  switch (action) {
    case "create":
      modal.value.title = "Add Item";
      modal.value.button = "Create";
      request.value = "item.store";
      break;
    case "duplicate":
      request.value = "item.duplicate";
      break;
    case "delete":
      console.log(row);

      modal.value.title = "Deleting:";
      modal.value.text = activeRow.value.archived != 1 && activeRow.value.archived != "1" ? "Perhaps archive instead?" : "";
      modal.value.button = "Delete";
      request.value = "item.destroy";
      break;
    case "update":
      modal.value.title = "Editing:";
      modal.value.button = "Update";
      request.value = "item.update";
      break;
    case "status":
      request.value = "item.status";
      break;
    case "date":
      request.value = "item.date";
      break;
    case "priority":
      request.value = "item.priority";
      break;
    case "archive":
      request.value = "item.archive";
      break;
  }

  if (status) {
    activeRow.value.status_id = status;
    toggleStatus(null, row);
  }

  if (!["status", "archive", "duplicate", "date", "priority"].includes(action)) modal.value.active = true;
  else callAction();
};

const callAction = () => {
  event.preventDefault();
  let formData = new FormData();
  Object.entries(activeRow.value).forEach(([key, value]) => {
    formData.append(key, value);
  });

  axios({
    method: "post",
    url: route(request.value),
    data: formData,
    headers: { "Content-Type": "multipart/form-data" },
  })
    .then((response) => {
      toast.success(response.data);
      closeModal();
    })
    .catch((err) => {
      errors.value.title = err.response.data.message;
    });
};

const search = ref("");
const showArchived = ref(false);
const categoriesToggle = ref([]);

const sorting = ref({
  field: "status_id",
  direction: "asc",
});

const finalItems = computed(() => {
  let base = props.items.slice();

  if (!showArchived.value) {
    base = base.filter((item) => +item.archived === 0);
  }

  const activeCategories = props.categories.filter((cat) => categoriesToggle.value[cat.id]);
  if (activeCategories.length > 0) {
    base = base.filter((item) => activeCategories.some((cat) => cat.id === item.category_id));
  }

  if (search.value.trim() !== "") {
    const s = search.value.toLowerCase();
    base = base.filter((item) => item.title.toLowerCase().includes(s) || item.description && item.description.toLowerCase().includes(s));
  }

  let sorted = base.slice();
  switch (sorting.value.field) {
    case "date":
      sorted.sort((a, b) => new Date(a.date) - new Date(b.date));
      break;
    case "title":
      sorted.sort((a, b) => a.title.localeCompare(b.title));
      break;
    case "priority_id":
      sorted.sort((a, b) => a.priority_id - b.priority_id);
      break;
    default:
      sorted.sort((a, b) => a[sorting.value.field] - b[sorting.value.field]);
      break;
  }

  if (sorting.value.direction === "desc") sorted.reverse();
  base = sorted;

  return base;
});

// const saveState = () => {
//   axios.post(route("items.save"), { items: finalItems.value }).then((respons) => {
//       toast.success("State saved");
//     }).catch((error) => {
//       console.error("Error saving state");
//     });
// };

const updateSearch = () => {
  // saveState();
};

const toggleArchive = () => {
  showArchived.value = !showArchived.value;
  // saveState();
};

const toggleCategory = (id) => {
  categoriesToggle.value[id] = !categoriesToggle.value[id];
  // saveState();
};

const applySort = (field) => {
  sorting.value.field = field;
  if (sorting.value.direction == "asc") sorting.value.direction = "desc";
  else sorting.value.direction = "asc";
  // saveState();
};

const isDragging = ref(false);
const ghost = ref(null);

const dragStart = (evt, item) => {
  let tr = evt.target.closest("tr");
  if (!tr || tr.classList.contains("head-row")) return;

  let clone = tr.cloneNode(true);
  clone.classList.add("ghost");
  clone.innerHTML = "Release to drop...";
  const layout = document.querySelector("#dragref");
  layout.appendChild(clone);
  evt.dataTransfer.setDragImage(clone, clone.offsetWidth / 2, clone.offsetHeight / 2);
  ghost.value = clone;

  isDragging.value = true;
  evt.dataTransfer.effectAllowed = "move";
  evt.dataTransfer.setData("text/plain", item.id);
  tr.classList.add("drag-element");
};

const dragDrop = (evt, targetIndex) => {
  evt.preventDefault();
  const draggedItemId = parseInt(evt.dataTransfer.getData("text/plain"));
  const sourceIndex = finalItems.value.findIndex((item) => item.id === draggedItemId);

  if (sourceIndex !== targetIndex) {
    const itemToMove = finalItems.value[sourceIndex];
    finalItems.value.splice(sourceIndex, 1);
    finalItems.value.splice(targetIndex, 0, itemToMove);
  }

  clearDragClasses();
};

const dragOver = (evt) => {
  evt.preventDefault();
  const dropZone = evt.target.closest("tr");
  if (dropZone) {
    document.querySelectorAll(".drop-zone").forEach((el) => {
      if (el !== dropZone) el.classList.remove("drop-zone");
    });
  }
  dropZone.classList.add("drop-zone");
};

const dragEnd = () => {
  isDragging.value = false;
  const layout = document.querySelector("#dragref");
  layout.removeChild(ghost.value);

  clearDragClasses();
};

const dragLeave = (evt) => {
  evt.preventDefault();
  const dropZone = evt.target.closest("tr");
  if (dropZone) dropZone.classList.remove("drop-zone");
};

const clearDragClasses = () => {
  document.querySelectorAll(".drag-element").forEach((el) => {
    el.classList.remove("drag-element");
  });
  document.querySelectorAll(".drop-zone").forEach((el) => {
    el.classList.remove("drop-zone");
  });
};

const closeModal = () => {
  modal.value = { active: false, title: "", text: "", button: "" };
  errors.value = { title: "" };
  activeRow.value = {
    id: 0,
    category_id: 4,
    priority_id: 1,
    status_id: 1,
    title: "",
    description: "",
    date: "",
    recurring: 0,
    recurring_interval: "",
    image: "",
    archived: 0,
  };
  router.reload({ only: ["items"] }, { preserveState: true });
};

const exportCSV = () => {
  axios.post(route("item.export")).then((response) => {
    let blob = new Blob([response.data], { type: "text/csv;charset=utf-8;" });
    let url = URL.createObjectURL(blob);
    let pom = document.createElement("a");
    pom.href = url;
    pom.setAttribute("download", "Items.csv");
    pom.click();
  });
};

const getIcon = (id) => {
  return props.icons.find((i) => i.id === id).data;
};

const moreActions = ref([]);
const toggleMoreActions = (evt, id) => {
  evt.stopPropagation();
  moreActions.value[id] = !moreActions.value[id];
};

const statusPopups = ref([]);
const toggleStatus = (evt, id) => {
  if (evt) evt.stopPropagation();
  statusPopups.value[id] = !statusPopups.value[id];
};

const rowDatepicker = ref([]);
const toggleDatepicker = (evt, id) => {
  evt.stopPropagation();
  for (let index = 0; index < rowDatepicker.value.length; index++) {
    if (rowDatepicker.value[index] !== rowDatepicker.value[id]) rowDatepicker.value[index] = false;
  }

  rowDatepicker.value[id] = !rowDatepicker.value[id];
};

const turnOffDatepicker = (item, newDate) => {
  rowDatepicker.value[item.id] = false;
  if (newDate === "" || item.date === newDate) return;
  item.date = newDate;
  actions(null, item.id, "date");
};

const offModalDatepicker = (item, newDate) => {
  rowDatepicker.value[item.id] = false;
  if (newDate === "" || item.date === newDate) return;
  item.date = newDate;
};

const gridLayout = ref(false);
const toggleGrid = (val) => {
  gridLayout.value = val;
};

const rowClass = (col) => {
  let background = "bg-" + col.substring(5, col.length) + "/10";
  let grid = gridLayout.value ? "flex flex-col" : "";
  let draggingOn = isDragging.value ? "opacity-50" : "";
  return `${grid} ${draggingOn} ${background}`.trim();
};

const settingsModal = ref(false);
const editModal = ref({ shown: false });
const editModel = ref("");
const editError = ref(false);

const change = (typ, pro, row) => {
  editModal.value = {
    shown: true,
    typ: typ,
    pro: pro,
    row: row,
  };
};

const changeCall = () => {
  if (!editModel.value || editModel.value === "") {
    editCleanup();
    return;
  }

  let typ = editModal.value.typ;
  let pro = editModal.value.pro;
  let row = editModal.value.row;
  switch (typ) {
    case "nam":
      row.name = editModel.value;
      break;
    case "des":
      row.description = editModel.value;
      break;
    case "col":
      row.color = editModel.value;
      break;
    case "ico":
      row.icon_id = editModel.value;
      break;
  }

  let rout;
  switch (pro) {
    case "cat":
      rout = "category.update";
      break;
    case "pri":
      rout = "priority.update";
      break;
    case "sta":
      rout = "status.update";
      break;
  }

  let formData = new FormData();
  Object.entries(row).forEach(([key, value]) => {
    formData.append(key, value);
  });

  axios({
    method: "post",
    url: route(rout),
    data: formData,
    headers: { "Content-Type": "multipart/form-data" },
  })
    .then(() => {
      editCleanup();
      router.reload({ only: ["items", "statuses", "categories", "priorities"] }, { preserveState: true });
    })
    .catch((err) => {
      console.log(err);
    });
};

const editCleanup = () => {
  editModal.value = { shown: false };
  editModel.value = "";
  editError.value = false;
};

const activeTab = ref("cat");
const setActiveTab = (tab) => {
  activeTab.value = tab;
};

const disableColours = (index) => {
  return ["red", "orange", "yellow", "emerald", "green", "teal", "blue", "zinc", "pink", "slate", "sky", "violet", "purple", "rose"].includes(index);
};

const infoText = computed(() => {
  let p, rl;
  switch (editModal.value.typ) {
    case "nam":
      p = "name";
      break;
    case "des":
      p = "description";
      break;
    case "ico":
      p = "icon";
      break;
    case "col":
      p = "colour";
      break;
  }

  switch (editModal.value.pro) {
    case "cat":
      rl = "category";
      break;
    case "pri":
      rl = "priority";
      break;
    case "sta":
      rl = "status";
      break;
  }

  return `You're now editing ${p} of ${rl}: ${editModal.value.row.name}`;
});

const imageIcons = ref(false);

const fadeOpacity = ref(1);
const threshold = 200;
const scrollContainer = ref(null);

const updateFadeOpacity = () => {
  if (!scrollContainer.value) return;
  const { scrollTop, clientHeight, scrollHeight } = scrollContainer.value;
  const distance = scrollHeight - (scrollTop + clientHeight);
  fadeOpacity.value = distance < threshold ? distance / threshold : 1;
};

onMounted(() => {
  if (scrollContainer.value) {
    scrollContainer.value.addEventListener("scroll", updateFadeOpacity);
  }
});
onUnmounted(() => {
  if (scrollContainer.value) {
    scrollContainer.value.removeEventListener("scroll", updateFadeOpacity);
  }
});
</script>

<template>
  <AppLayout title="Plans">
    <template v-if="editModal.shown">
      <Teleport to="body">
        <div class="fixed inset-0 z-50 flex h-screen w-screen flex-col items-center justify-center gap-5 bg-black/90 backdrop-blur-sm" @keyup.esc="editCleanup">
          <p class="text-sm text-white">{{ infoText }}</p>
          <TextInput v-if="editModal.typ == 'nam' || editModal.typ == 'des'" v-model="editModel" type="text" autofocus="true" />
          <div v-if="editModal.typ == 'ico'" class="mx-auto flex w-[900px] flex-wrap items-center justify-center gap-3">
            <template v-for="i in icons">
              <PrimaryButton :onlyIcon="true" @click="editModel = i.id" :toggled="editModel == i.id">
                <template #icon>
                  <span :id="i.id" v-html="i.data" />
                </template>
              </PrimaryButton>
            </template>
          </div>
          <div v-if="editModal.typ == 'col'" class="mx-auto flex w-[300px] flex-wrap items-center justify-center gap-3">
            <template v-for="(c, index) in colors">
              <div v-if="disableColours(index)" class="flex items-center gap-3">
                <div v-for="ci in 8" :style="{ background: c[(ci + 1) * 100] }" class="sc cursor-pointer rounded-full text-white transition-transform as-5 hover:scale-110" :class="editModel == c[(ci + 1) * 100] && '!scale-125 border border-white/20'" @click="editModel = c[(ci + 1) * 100]" />
              </div>
            </template>
          </div>
          <PrimaryButton :onlyIcon="true" color="#fff" opacity="5" hoverOpacity="10" @click="changeCall" tooltip="Save">
            <template #icon>
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="icons" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                <polyline points="22 4 12 14.01 9 11.01" />
              </svg>
            </template>
          </PrimaryButton>
          <InputError v-if="editError" class="mt-2" message="Please type one or more characters or press Esc to exit." />
        </div>
      </Teleport>
    </template>
    <template #header>
      <div class="flex w-full items-center justify-start gap-3">
        <PrimaryButton :onlyIcon="true" color="#fff" opacity="5" hoverOpacity="10" @click="console.log('toggle all categories')">
          <template #icon>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="icons" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
              <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
              <polyline points="22 4 12 14.01 9 11.01" />
            </svg>
          </template>
        </PrimaryButton>
        <PrimaryButton v-for="c in categories" :key="c.id" :toggled="categoriesToggle[c.id]" @click="toggleCategory(c.id)" :color="c.color">
          <template #icon>
            <span v-html="getIcon(c.icon_id)" />
          </template>
          <span>{{ c.name }}</span>
        </PrimaryButton>
        <div class="ml-auto flex items-center gap-2">
          <PrimaryButton :onlyIcon="true" color="#fff" :toggled="!gridLayout" opacity="5" hoverOpacity="10" @click="toggleGrid(false)">
            <template #icon>
              <svg width="24" height="24" class="icons" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor"><path d="M3 12H7.5H12H16.5H21M3 12V16.5M3 12V7.5M21 12V16.5M21 12V7.5M3 16.5V20.4C3 20.7314 3.26863 21 3.6 21H7.5H12H16.5H20.4C20.7314 21 21 20.7314 21 20.4V16.5M3 16.5H7.5H12H16.5H21M21 7.5V3.6C21 3.26863 20.7314 3 20.4 3H16.5H12H7.5H3.6C3.26863 3 3 3.26863 3 3.6V7.5M21 7.5H16.5H12H7.5H3" stroke="currentColor" stroke-width="2"></path></svg>
            </template>
          </PrimaryButton>
          <PrimaryButton :onlyIcon="true" color="#fff" :toggled="gridLayout" opacity="5" hoverOpacity="10" @click="toggleGrid(true)">
            <template #icon>
              <svg width="24" height="24" class="icons" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor">
                <path d="M14 20.4V14.6C14 14.2686 14.2686 14 14.6 14H20.4C20.7314 14 21 14.2686 21 14.6V20.4C21 20.7314 20.7314 21 20.4 21H14.6C14.2686 21 14 20.7314 14 20.4Z" stroke="currentColor" stroke-width="2"></path>
                <path d="M3 20.4V14.6C3 14.2686 3.26863 14 3.6 14H9.4C9.73137 14 10 14.2686 10 14.6V20.4C10 20.7314 9.73137 21 9.4 21H3.6C3.26863 21 3 20.7314 3 20.4Z" stroke="currentColor" stroke-width="2"></path>
                <path d="M14 9.4V3.6C14 3.26863 14.2686 3 14.6 3H20.4C20.7314 3 21 3.26863 21 3.6V9.4C21 9.73137 20.7314 10 20.4 10H14.6C14.2686 10 14 9.73137 14 9.4Z" stroke="currentColor" stroke-width="2"></path>
                <path d="M3 9.4V3.6C3 3.26863 3.26863 3 3.6 3H9.4C9.73137 3 10 3.26863 10 3.6V9.4C10 9.73137 9.73137 10 9.4 10H3.6C3.26863 10 3 9.73137 3 9.4Z" stroke="currentColor" stroke-width="2"></path>
              </svg>
            </template>
          </PrimaryButton>
        </div>
      </div>
      <div class="flex w-full flex-wrap items-center justify-between gap-3">
        <div class="relative">
          <TextInput id="search" type="text" placeholder="Search" v-model="search" @change="updateSearch" />
          <span v-if="search && search.length > 1" class="icons-container !absolute right-2 top-[10px]" tooltip="Clear" @click="search = ''">
            <svg width="24" height="24" class="icons" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor">
              <path d="M9.17218 14.8284L12.0006 12M14.829 9.17157L12.0006 12M12.0006 12L9.17218 9.17157M12.0006 12L14.829 14.8284" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
              <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
          </span>
        </div>
        <Toggle v-model:active="showArchived" label="Show Archived" @update:active="toggleArchive" />
        <div class="ml-auto flex items-center gap-3">
          <PrimaryButton :onlyIcon="true" color="#fff" opacity="5" hoverOpacity="10" @click="settingsModal = true" tooltip="Settings">
            <template #icon>
              <svg width="24" class="icons" height="24" viewBox="0 0 24 24" stroke-width="2" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor">
                <path d="M10.0503 10.6066L2.97923 17.6777C2.19818 18.4587 2.19818 19.7251 2.97923 20.5061V20.5061C3.76027 21.2872 5.0266 21.2872 5.80765 20.5061L12.8787 13.4351" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M17.1927 13.7994L21.071 17.6777C21.8521 18.4587 21.8521 19.7251 21.071 20.5061V20.5061C20.29 21.2872 19.0236 21.2872 18.2426 20.5061L12.0341 14.2977" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M6.73267 5.90381L4.61135 6.61092L2.49003 3.07539L3.90424 1.66117L7.43978 3.78249L6.73267 5.90381ZM6.73267 5.90381L9.5629 8.73404" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M10.0503 10.6066C9.2065 8.45359 9.37147 5.62861 11.111 3.8891C12.8505 2.14958 16.0607 1.76778 17.8285 2.82844L14.7878 5.86911L14.5052 8.98015L17.6162 8.69754L20.6569 5.65686C21.7176 7.42463 21.3358 10.6349 19.5963 12.3744C17.8567 14.1139 15.0318 14.2789 12.8788 13.435" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
              </svg>
            </template>
          </PrimaryButton>
          <PrimaryButton @click="exportCSV" color="#fff" opacity="5" hoverOpacity="10">
            <template #icon>
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icons">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="7 10 12 15 17 10"></polyline>
                <line x1="12" y1="15" x2="12" y2="3"></line>
              </svg>
            </template>
            <span>Export</span>
          </PrimaryButton>
          <PrimaryButton @click="actions($event, -1, 'create')" color="#fff" opacity="5" hoverOpacity="10">
            <template #icon>
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icons">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
              </svg>
            </template>
            <span>Add Item</span>
          </PrimaryButton>
        </div>
      </div>
    </template>
    <div ref="scrollContainer" class="common-class mx-auto flex h-full w-full flex-col overflow-x-hidden">
      <div v-if="finalItems.length > 0" class="rounded-2xl">
        <table class="relative table border-separate border-spacing-0 rounded-2xl text-sm">
          <thead :class="gridLayout && 'hidden'">
            <tr class="head-row">
              <th class="mr-auto">
                <div class="m-0 flex items-center justify-start gap-1 p-0">
                  <div class="-ml-2 inline-flex w-min flex-col justify-around text-sm ah-[18px]">
                    <span class="inline-block cursor-pointer" @click="applySort('title')">
                      <svg :class="['!ah-[7px]', sorting.field === 'title' && sorting.direction === 'asc' ? 'fill-white text-white' : 'fill-white/50 text-white/50']" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icons">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                      </svg>
                    </span>
                    <span class="inline-block cursor-pointer" @click="applySort('title')">
                      <svg :class="['-scale-y-100 !ah-[7px]', sorting.field === 'title' && sorting.direction === 'desc' ? 'fill-white text-white' : 'fill-white/50 text-white/50']" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icons">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                      </svg>
                    </span>
                  </div>
                  <span>Title</span>
                </div>
              </th>
              <th class="aw-[200px]">
                <div class="m-0 flex items-center justify-start gap-1 p-0">
                  <div class="-ml-2 inline-flex w-min flex-col justify-around text-sm ah-[18px]">
                    <span class="inline-block cursor-pointer" @click="applySort('date')">
                      <svg :class="['!ah-[7px]', sorting.field === 'date' && sorting.direction === 'asc' ? 'fill-white text-white' : 'fill-white/50 text-white/50']" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icons">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                      </svg>
                    </span>
                    <span class="inline-block cursor-pointer" @click="applySort('date')">
                      <svg :class="['-scale-y-100 !ah-[7px]', sorting.field === 'date' && sorting.direction === 'desc' ? 'fill-white text-white' : 'fill-white/50 text-white/50']" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icons">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                      </svg>
                    </span>
                  </div>
                  <span>Due Date</span>
                </div>
              </th>
              <th class="aw-[200px]">
                <div class="m-0 flex items-center justify-start gap-1 p-0">
                  <div class="-ml-2 inline-flex w-min flex-col justify-around text-sm ah-[18px]">
                    <span class="inline-block cursor-pointer" @click="applySort('priority_id')">
                      <svg :class="['!ah-[7px]', sorting.field === 'priority_id' && sorting.direction === 'asc' ? 'fill-white text-white' : 'fill-white/50 text-white/50']" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icons">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                      </svg>
                    </span>
                    <span class="inline-block cursor-pointer" @click="applySort('priority_id')">
                      <svg :class="['-scale-y-100 !ah-[7px]', sorting.field === 'priority_id' && sorting.direction === 'desc' ? 'fill-white text-white' : 'fill-white/50 text-white/50']" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icons">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                      </svg>
                    </span>
                  </div>
                  <span>Priority</span>
                </div>
              </th>
            </tr>
          </thead>
          <TransitionGroup name="list" tag="tbody" :class="['relative', gridLayout && 'grid grid-cols-3 gap-5']">
            <tr v-for="(item, index) in finalItems" :key="item.id" draggable="true" @dragstart="dragStart($event, item)" @dragleave="dragLeave($event)" @dragover="dragOver($event)" @dragend="dragEnd" @drop="dragDrop($event, index)" :class="rowClass(item.status.color)" @click="actions($event, item.id, 'update')">
              <td :class="['mr-auto', gridLayout && 'flex w-full min-w-full items-center justify-start border-l border-t py-5']">
                <span :class="['flex w-full items-center', gridLayout ? 'justify-start' : 'justify-between']">
                  <div class="relative -ml-1 flex items-center justify-between">
                    <span :tooltip="item.status.name" :style="{ color: item.status.color }" class="relative mr-3 flex cursor-pointer items-center justify-center overflow-hidden aw-[24px]" @click="toggleStatus($event, item.id)">
                      <span v-html="getIcon(item.status.icon_id)" />
                    </span>
                    <div class="-ml-1 mr-3 flex items-center gap-2" v-if="statusPopups[item.id]">
                      <template v-for="s in statuses" :key="s.id">
                        <span :tooltip="s.name" v-if="s.id != item.status.id" :style="{ color: s.color }" class="relative flex cursor-pointer items-center justify-center overflow-hidden text-xs aw-[24px]" @click="actions($event, item.id, 'status', s.id)">
                          <span v-html="getIcon(s.icon_id)" />
                        </span>
                      </template>
                    </div>
                  </div>
                  <span v-if="!gridLayout" class="mr-auto">{{ item.title.length < 89 ? item.title : item.title.substring(0, 89) + "..." }}</span>
                  <span class="icons-container" tooltip="Assign">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="as-[18px]" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                      <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                      <circle cx="8.5" cy="7" r="4" />
                      <line x1="20" x2="20" y1="8" y2="14" />
                      <line x1="23" x2="17" y1="11" y2="11" />
                    </svg>
                  </span>
                  <div :class="['relative flex items-center justify-between', gridLayout ? 'ml-auto' : 'ml-2']">
                    <span class="icons-container" @click="toggleMoreActions($event, item.id)" :class="moreActions[item.id] && 'mr-2'" tooltip="Options">
                      <svg v-if="!moreActions[item.id]" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icons">
                        <polyline points="11 17 6 12 11 7"></polyline>
                        <polyline points="18 17 13 12 18 7"></polyline>
                      </svg>
                      <svg v-else :class="'-scale-x-100'" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icons">
                        <polyline points="11 17 6 12 11 7"></polyline>
                        <polyline points="18 17 13 12 18 7"></polyline>
                      </svg>
                    </span>
                    <div class="flex items-center gap-3" v-if="moreActions[item.id]">
                      <span class="icons-container" @click="actions($event, item.id, 'duplicate')" tooltip="Duplicate">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="as-[18px]" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                          <rect height="13" rx="2" ry="2" width="13" x="9" y="9" />
                          <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
                        </svg>
                      </span>
                      <span class="icons-container" @click="actions($event, item.id, 'archive')" tooltip="Archive">
                        <svg v-if="!item.archived" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="as-[18px]">
                          <polyline points="21 8 21 21 3 21 3 8"></polyline>
                          <rect x="1" y="3" width="22" height="5"></rect>
                          <line x1="10" y1="12" x2="14" y2="12"></line>
                        </svg>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="as-[18px]">
                          <path d="M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2V9M9 21H5a2 2 0 0 1-2-2V9m0 0h18"></path>
                        </svg>
                      </span>
                      <span class="icons-container" @click="actions($event, item.id, 'delete')" tooltip="Delete">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="as-[18px]">
                          <polyline points="3 6 5 6 21 6"></polyline>
                          <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                          <line x1="10" y1="11" x2="10" y2="17"></line>
                          <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg>
                      </span>
                    </div>
                  </div>
                </span>
              </td>
              <td v-if="gridLayout" class="flex w-full min-w-full items-center justify-between border-l py-5">{{ item.title.length < 30 ? item.title : item.title.substring(0, 30) + "..." }}</td>
              <td :class="['relative', gridLayout ? 'aw-full flex items-center justify-between border-l py-5' : 'aw-[200px]']" @click="toggleDatepicker($event, item.id)">
                <Datepicker v-if="rowDatepicker[item.id]" :date="item.date" :item="item.id" @update:date="(newDate) => turnOffDatepicker(item, newDate)" />
                <div class="flex w-full items-center justify-between">
                  <span>{{ item.date ? item.date : "Unknown" }}</span>
                  <span class="icons-container" tooltip="Edit Date">
                    <svg width="24" height="24" viewBox="0 0 24 24" stroke-width="2" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor" class="as-[18px]">
                      <path d="M13 21H5C3.89543 21 3 20.1046 3 19V10H21V15M15 4V2M15 4V6M15 4H10.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M3 10V6C3 4.89543 3.89543 4 5 4H7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M7 2V6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M21 10V6C21 4.89543 20.1046 4 19 4H18.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M16 20L18 22L22 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                  </span>
                </div>
              </td>
              <td :class="['relative !px-0', gridLayout ? 'flex w-full min-w-full items-center justify-between border-b border-l py-5' : 'aw-[200px]']">
                <Multiselect :id="`priority_id-inline${item.id}`" :value="item.priority" :options="priorities" @click="$event.stopPropagation()" :icons="icons" :inlineSelect="true" />
              </td>
            </tr>
          </TransitionGroup>
        </table>
      </div>
      <template v-else>
        <p class="mb-5 text-center text-sm text-white">No items found.</p>
        <div class="mx-auto flex w-full items-center justify-center text-center text-sm text-white">
          <span>Press</span>
          <PrimaryButton @click="actions($event, -1, 'create')" class="mx-2" color="#fff" opacity="5" hoverOpacity="10">
            <template #icon>
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icons">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
              </svg>
            </template>
            <span>Add Item</span>
          </PrimaryButton>
          <span>button to start.</span>
        </div>
        <template v-if="search != ''">
          <div class="mx-auto my-8 flex h-px w-[300px] items-start justify-center bg-white/10 text-sm text-white">
            <p class="-mt-3 mb-0 bg-transparent px-3">or</p>
          </div>
          <div class="mx-auto flex w-full flex-col items-center justify-center gap-3 text-center text-sm text-white">
            <p>Refine your search</p>
            <div class="relative">
              <TextInput id="search" type="text" placeholder="Search" v-model="search" @change="updateSearch" />
              <span v-if="search && search.length > 1" class="icons-container !absolute right-2 top-[10px]" tooltip="Clear" @click="search = ''">
                <svg width="24" height="24" class="icons" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor">
                  <path d="M9.17218 14.8284L12.0006 12M14.829 9.17157L12.0006 12M12.0006 12L9.17218 9.17157M12.0006 12L14.829 14.8284" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                  <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
              </span>
            </div>
          </div>
        </template>
      </template>
    </div>
    <div class="fade-mask" :style="{ opacity: fadeOpacity }"></div>

    <DialogModal :show="modal.active" @close="closeModal">
      <template #header>
        <span>{{ modal.title + " " + activeRow.title }}</span>
      </template>
      <template #content>
        <form id="dialogForm">
          <input name="id" v-model="activeRow.id" type="number" hidden disabled />
          <div class="flex flex-col gap-5 text-sm text-white">
            <span v-if="modal.text && modal.text.length > 0">{{ modal.text }}</span>
            <template v-if="modal.button != 'Delete'">
              <div class="flex w-full items-center gap-5">
                <div class="flex items-center justify-center border border-[#2a2a2a] bg-[#0d0d0d] transition-colors as-[144px] hover:border-[#4d4d4d] hover:bg-[#1a1a1a]" @mouseenter="imageIcons = true" @mouseleave="imageIcons = false">
                  <Transition name="fade">
                    <div v-if="imageIcons" class="flex flex-col items-center gap-1">
                      <div class="flex items-center gap-1">
                        <PrimaryButton tooltip="Upload" :onlyIcon="true">
                          <template #icon>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="icons" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                              <line x1="12" x2="12" y1="5" y2="19"></line>
                              <line x1="5" x2="19" y1="12" y2="12"></line>
                            </svg>
                          </template>
                        </PrimaryButton>
                        <PrimaryButton tooltip="Camera" :onlyIcon="true">
                          <template #icon>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="icons" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                              <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z" />
                              <circle cx="12" cy="13" r="4" />
                            </svg>
                          </template>
                        </PrimaryButton>
                      </div>
                      <div class="flex items-center gap-1">
                        <PrimaryButton tooltip="Generate" :onlyIcon="true">
                          <template #icon>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="icons" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                              <path d="M10.5 9C10.5 9 10.5 7 9.5 5C13.5 5 16 7.49997 16 7.49997C16 7.49997 19.5 7 22 12C21 17.5 16 18 16 18L12 20.5C12 20.5 12 19.5 12 17.5C9.5 16.5 6.99998 14 7 12.5C7.00001 11 10.5 9 10.5 9ZM10.5 9C10.5 9 11.5 8.5 12.5 8.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                              <path d="M2 9.5L3 12.5L2 15.5C2 15.5 7 15.5 7 12.5C7 9.5 2 9.5 2 9.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                              <path d="M17 12.01L17.01 11.9989" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                          </template>
                        </PrimaryButton>
                        <PrimaryButton tooltip="Delete" :onlyIcon="true">
                          <template #icon>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="as-[18px]">
                              <polyline points="3 6 5 6 21 6"></polyline>
                              <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                              <line x1="10" y1="11" x2="10" y2="17"></line>
                              <line x1="14" y1="11" x2="14" y2="17"></line>
                            </svg>
                          </template>
                        </PrimaryButton>
                      </div>
                    </div>
                  </Transition>
                </div>
                <div class="flex w-full flex-col gap-5">
                  <div class="flex w-full flex-col gap-1">
                    <InputLabel for="title" value="Title" />
                    <TextInput id="title" v-model="activeRow.title" type="text" />
                    <InputError :message="errors.title" class="mt-2" />
                  </div>
                  <div class="flex w-full flex-col gap-1">
                    <InputLabel for="category_id" value="Category" />
                    <Multiselect id="category_id" :value="activeRow.category_id" :options="categories" @click="$event.stopPropagation()" :inlineSelect="false" />
                  </div>
                </div>
              </div>
              <div class="relative flex w-full flex-col gap-1">
                <InputLabel for="description" value="Description" />
                <AutoResizeTextarea id="description" v-model="activeRow.description" />
                <PrimaryButton :tooltip="activeRow.description && activeRow.description.length > 5 ? 'Improve' : 'Generate'" class="!absolute bottom-2 right-2 z-30" color="#fff" opacity="5" hoverOpacity="10" :onlyIcon="true">
                  <template #icon>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="icons" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                      <path d="M10.5 9C10.5 9 10.5 7 9.5 5C13.5 5 16 7.49997 16 7.49997C16 7.49997 19.5 7 22 12C21 17.5 16 18 16 18L12 20.5C12 20.5 12 19.5 12 17.5C9.5 16.5 6.99998 14 7 12.5C7.00001 11 10.5 9 10.5 9ZM10.5 9C10.5 9 11.5 8.5 12.5 8.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M2 9.5L3 12.5L2 15.5C2 15.5 7 15.5 7 12.5C7 9.5 2 9.5 2 9.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M17 12.01L17.01 11.9989" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                  </template>
                </PrimaryButton>
              </div>
              <div class="grid w-full grid-cols-3 gap-5">
                <div class="flex flex-col gap-1">
                  <InputLabel for="priority_id" value="Priority" />
                  <Multiselect id="priority_id" :value="activeRow.priority_id" :options="priorities" @click="$event.stopPropagation()" :inlineSelect="false" />
                </div>
                <div class="flex flex-col gap-1">
                  <InputLabel for="status_id" value="Status" />
                  <Multiselect id="status_id" :value="activeRow.status_id" :options="statuses" @click="$event.stopPropagation()" :inlineSelect="false" />
                </div>
                <div class="flex flex-col gap-1">
                  <InputLabel for="date" value="Due Date" />
                  <div class="relative flex w-full items-center justify-between" type="date">
                    <Datepicker v-if="rowDatepicker[activeRow.id]" :date="activeRow.date" :item="activeRow.id" @update:date="(newDate) => offModalDatepicker(activeRow, newDate)" />
                    <TextInput id="date" v-model="activeRow.date" type="text" class="absolute left-0 w-full !border-transparent !bg-transparent focus:!bg-white/5" />
                    <div class="flex w-full items-center justify-end">
                      <span class="icons-container" @click="toggleDatepicker($event, activeRow.id)" tooltip="Edit Date">
                        <svg width="24" height="24" viewBox="0 0 24 24" stroke-width="2" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor" class="as-[18px]">
                          <path d="M13 21H5C3.89543 21 3 20.1046 3 19V10H21V15M15 4V2M15 4V6M15 4H10.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                          <path d="M3 10V6C3 4.89543 3.89543 4 5 4H7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                          <path d="M7 2V6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                          <path d="M21 10V6C21 4.89543 20.1046 4 19 4H18.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                          <path d="M16 20L18 22L22 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </template>
          </div>
        </form>
      </template>
      <template #footer>
        <PrimaryButton type="button" color="#ff0033" class="mr-auto" @click="closeModal">Close</PrimaryButton>
        <PrimaryButton v-if="modal.button == 'Delete' && !activeRow.archived" type="button" @click="actions($event, activeRow.id, 'archive')" class="mr-3">Archive</PrimaryButton>
        <PrimaryButton type="button" v-if="modal.button != ''" @click="callAction">{{ modal.button }}</PrimaryButton>
      </template>
    </DialogModal>
    <DialogModal :show="settingsModal" @close="settingsModal = false">
      <template #header>
        <span>Plans Settings</span>
      </template>
      <template #content>
        <div class="flex w-full flex-col">
          <div class="mb-5 flex w-full items-center justify-stretch">
            <span class="w-full cursor-pointer rounded-l-2xl border border-white/10 py-3 text-center text-xs transition-colors hover:bg-white/5" :class="activeTab == 'cat' && 'bg-white/10'" @click="setActiveTab('cat')">Categories</span>
            <span class="w-full cursor-pointer border-y border-white/10 py-3 text-center text-xs transition-colors hover:bg-white/5" :class="activeTab == 'sta' && 'bg-white/10'" @click="setActiveTab('sta')">Statuses</span>
            <span class="w-full cursor-pointer rounded-r-2xl border border-white/10 py-3 text-center text-xs transition-colors hover:bg-white/5" :class="activeTab == 'pri' && 'bg-white/10'" @click="setActiveTab('pri')">Priorities</span>
          </div>
          <div v-if="activeTab == 'cat'" class="flex flex-col gap-3">
            <div v-for="i in categories" :key="i.id" class="flex items-center justify-start gap-3 border-white/5 ah-[24px]">
              <span v-html="i.icon.data" @click="change('ico', 'cat', i)" />
              <span :style="{ background: i.color }" class="rounded as-[24px]" @click="change('col', 'cat', i)" />
              <span @click="change('nam', 'cat', i)">{{ i.name.length > 0 ? i.name : "None" }}</span>
            </div>
          </div>
          <div v-if="activeTab == 'sta'" class="flex flex-col gap-3">
            <div v-for="i in statuses" :key="i.id" class="flex items-center justify-start gap-3 border-white/5 ah-[24px]">
              <span v-html="i.icon.data" @click="change('ico', 'sta', i)" />
              <span :style="{ background: i.color }" class="rounded as-[24px]" @click="change('col', 'sta', i)" />
              <span @click="change('nam', 'sta', i)">{{ i.name.length > 0 ? i.name : '<em>None</em>' }}</span>
            </div>
          </div>
          <div v-if="activeTab == 'pri'" class="flex flex-col gap-3">
            <div v-for="i in priorities" :key="i.id" class="flex items-center justify-start gap-3 border-white/5 ah-[24px]">
              <span v-html="i.icon.data" @click="change('ico', 'pri', i)" />
              <span :style="{ background: i.color }" class="rounded as-[24px]" @click="change('col', 'pri', i)" />
              <span @click="change('nam', 'pri', i)">{{ i.name.length > 0 ? i.name : "None" }}</span>
            </div>
          </div>
        </div>
      </template>
      <template #footer>
        <PrimaryButton type="button" class="ml-auto mr-3" @click="settingsModal = false">Close</PrimaryButton>
      </template>
    </DialogModal>
  </AppLayout>
</template>

<style scoped lang="pcss">
.common-class::-webkit-scrollbar {
  width: 20px;
}

.common-class::-webkit-scrollbar-track {
  background: #000;
}

.common-class::-webkit-scrollbar-thumb {
  background: #333;
  border-left: 8px solid #000;
  border-right: 8px solid #000;
  border-radius: 8px;
}

.common-class::-webkit-scrollbar-thumb:hover {
  background-color: #555;
  cursor: pointer;
}

.ghost {
  @apply absolute inset-y-0 right-[100vw] flex h-[89px] min-w-[200vw] items-center justify-center bg-white/5 text-xs text-white opacity-50;
}

table,
thead,
tbody,
tr {
  @apply w-full min-w-full;
}

thead tr {
  @apply bg-white/10 text-xs text-white;
}

tbody tr {
  @apply bg-white/5 text-white/80 hover:bg-white/10;
}

tr:last-child td:first-child {
  @apply rounded-bl-xl;
}

tr:last-child td:last-child {
  @apply rounded-br-xl;
}

th:first-child {
  @apply rounded-tl-xl;
}

th:last-child {
  @apply rounded-tr-xl;
}

td,
th {
  @apply border-b border-r border-white/10 px-3 text-left text-xs font-normal ah-[43px];
}

td:last-child,
th:last-child {
  @apply text-right;
}

.drop-zone {
  @apply bg-success/20;
}

.drag-element {
  @apply transition-colors duration-300 ease-in-out;
}

tr:not(.head-row) {
  @apply transition-all ease-in-out;
}

tr:active:not(.head-row) {
  @apply bg-primary/30;
}

tr:not(.head-row) {
  animation: enter;
  @apply duration-300 ease-in-out;
}

@keyframes enter {
  from {
    @apply opacity-0;
  }
  to {
    @apply opacity-100;
  }
}

.list-move {
  @apply transition-all duration-300 ease-in-out;
}

.list-enter-active,
.list-leave-active {
  @apply transition-all duration-300 ease-in-out;
}

.list-enter-from,
.list-leave-to {
  @apply translate-y-5 opacity-50;
}

.icons-container {
  @apply cursor-pointer p-px text-sm text-white/50 transition hover:text-white focus:text-white focus:ring-0 md:text-base lg:text-lg;
}

.fade-mask {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  height: 200px;
  pointer-events: none;
  background: linear-gradient(to top, rgba(0, 0, 0, 1), rgba(0, 0, 0, 0));
  z-index: 10;
}
</style>

