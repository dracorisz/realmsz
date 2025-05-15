<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import axios from "axios";
import { useToast } from "vue-toastification";

const props = defineProps({
  users: [Object, Array],
  organizations: Array,
  // filters: Object,
});

const toast = useToast();
const activeTab = ref("organization");
const showAddMemberModal = ref(false);
const showAddToTaskModal = ref(false);
const showReviewModal = ref(false);
const selectedUser = ref(null);
const searchQuery = ref("");
const sortBy = ref("name");
const sortDirection = ref("asc");
const isLoading = ref(false);
const connections = ref([]);

const tabs = [
  { id: "organization", label: "Organization" },
  { id: "realmsz", label: "Realmsz (Public)" },
];

const form = useForm({
  name: "",
  email: "",
  role: "developer",
  organization_id: "",
});

const taskForm = useForm({
  item_id: "",
  user_id: "",
});

const reviewForm = useForm({
  rating: 5,
  comment: "",
  user_id: "",
});

// const fetchConnections = async () => {
//   isLoading.value = true;
//   try {
//     const response = await axios.get(route("network.users"), {
//       params: {
//         type: activeTab.value,
//         organization_id: form.organization_id,
//       },
//     });
//     connections.value = response.data.users;
//     console.log(connections.value);
//   } catch (error) {
//     console.error("Error fetching connections:", error);
//     toast.error("Failed to load connections");
//   } finally {
//     isLoading.value = false;
//   }
// };

// const filteredConnections = computed(() => {
//   if (!searchQuery.value) return connections.value;

//   const query = searchQuery.value.toLowerCase();
//   return connections.value.filter((connection) => connection.name.toLowerCase().includes(query) || connection.email.toLowerCase().includes(query) || connection.role.toLowerCase().includes(query) || (connection.organization?.name?.toLowerCase().includes(query) ?? false));
// });

// const sortedConnections = computed(() => {
//   return [...filteredConnections.value].sort((a, b) => {
//     const direction = sortDirection.value === "asc" ? 1 : -1;
//     if (sortBy.value === "name") {
//       return direction * a.name.localeCompare(b.name);
//     } else if (sortBy.value === "email") {
//       return direction * a.email.localeCompare(b.email);
//     } else if (sortBy.value === "role") {
//       return direction * a.role.localeCompare(b.role);
//     } else if (sortBy.value === "organization") {
//       return direction * (a.organization?.name ?? "").localeCompare(b.organization?.name ?? "");
//     }
//     return 0;
//   });
// });

const updateVisibility = async (show) => {
  try {
    const response = await axios.post(
      route("network.visibility"),
      {
        show_in_network: show,
      },
      {
        withCredentials: false,
      },
    );

    if (response.data.success) {
      toast.success("Visibility updated successfully");
    }
  } catch (error) {
    console.error("Error updating visibility:", error);
    toast.error("Failed to update visibility");
  }
};

const addToTask = async (userId) => {
  try {
    const response = await axios.post(
      route("network.add-to-task"),
      {
        user_id: userId,
        item_id: taskForm.item_id,
      },
      {
        withCredentials: false,
      },
    );

    if (response.data.success) {
      toast.success("User added to task successfully");
      showAddToTaskModal.value = false;
    }
  } catch (error) {
    console.error("Error adding user to task:", error);
    toast.error("Failed to add user to task");
  }
};

const addMember = async () => {
  try {
    const response = await axios.post(route("network.members.store"), form, {
      withCredentials: false,
    });

    if (response.data.success) {
      toast.success("Member added successfully");
      showAddMemberModal.value = false;
      form.reset();
      // fetchConnections();
    }
  } catch (error) {
    console.error("Error adding member:", error);
    toast.error("Failed to add member");
  }
};

const toggleSort = (field) => {
  if (sortBy.value === field) {
    sortDirection.value = sortDirection.value === "asc" ? "desc" : "asc";
  } else {
    sortBy.value = field;
    sortDirection.value = "asc";
  }
};

// onMounted(() => {
//   fetchConnections();
// });

// watch(activeTab, () => {
//   fetchConnections();
// });
</script>

<template>
  <Head title="Network" />

  <AppLayout>
    <template #header>
      <div class="flex w-full items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-200">Network</h2>
        <div class="flex items-center space-x-4">
          <label class="flex items-center space-x-2">
            <input type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" @change="updateVisibility($event.target.checked)" />
            <span class="text-sm text-gray-600 dark:text-gray-400">Show me in network</span>
          </label>
        </div>
      </div>
    </template>

    <div class="flex h-full flex-1 flex-col">
      <div class="mx-auto h-full w-full">
        <div class="flex h-full flex-1 flex-col overflow-hidden bg-white/10 rounded-xl">
          <div class="p-6 text-gray-100 dark:text-gray-900">
            <!-- Tabs -->
            <div class="border-b border-gray-700 dark:border-gray-200">
              <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button v-for="tab in tabs" :key="tab.id" @click="activeTab = tab.id" :class="[activeTab === tab.id ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:border-gray-600 dark:hover:text-gray-300', 'whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium']">
                  {{ tab.label }}
                </button>
              </nav>
            </div>

            <!-- Add Member Button -->
            <div class="mt-4 flex justify-end">
              <PrimaryButton @click="showAddMemberModal = true">Add Member</PrimaryButton>
            </div>

            <!-- Members Grid -->
            <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
              <div v-for="connection in users" :key="connection.id" class="rounded-lg bg-white p-6 shadow dark:bg-gray-700">
                <div class="flex items-center space-x-4">
                  <img :src="connection.avatar || 'https://dracoscopia.com/logo.png'" :alt="connection.name" class="h-12 w-12 rounded-full" />
                  <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                      {{ connection.name }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                      {{ connection.email }}
                    </p>
                    <p v-if="connection.organization" class="text-sm capitalize text-indigo-600 dark:text-indigo-400">
                      <!-- {{ connection.organization.name }} -->
                      {{ connection.role }}
                    </p>
                  </div>
                </div>
                <div class="mt-4 flex space-x-2">
                  <PrimaryButton
                    color="accent"
                    @click="
                      selectedUser = connection;
                      showAddToTaskModal = true;
                    "
                    >Add to Task</PrimaryButton
                  >
                  <PrimaryButton v-if="connection.organization" @click="activeTab = 'organization'"> View Organization </PrimaryButton>
                </div>
              </div>
            </div>

            <!-- Search and Sort -->
            <div class="mt-2 flex items-center justify-between">
              <div class="flex items-center space-x-4">
                <div class="relative">
                  <input type="text" v-model="searchQuery" placeholder="Search members..." class="rounded-lg border border-gray-700 bg-gray-800 px-4 py-2 text-white focus:border-indigo-500 focus:ring-indigo-500" />
                  <svg class="absolute right-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                  </svg>
                </div>
                <select v-model="sortBy" class="rounded-lg border border-gray-700 bg-gray-800 px-4 py-2 text-white focus:border-indigo-500 focus:ring-indigo-500">
                  <option value="name">Name</option>
                  <option value="email">Email</option>
                  <option value="role">Role</option>
                  <option value="organization">Organization</option>
                </select>
                <button @click="sortDirection = sortDirection === 'asc' ? 'desc' : 'asc'" class="rounded-lg border border-gray-700 bg-gray-800 p-2 text-white hover:bg-gray-700">
                  <svg class="h-5 w-5" :class="{ 'rotate-180': sortDirection === 'desc' }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Add Member Modal -->

    <div v-if="showAddMemberModal" class="fixed inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-black/20 bg-opacity-50 backdrop-blur-xl transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>
        <div class="inline-block transform overflow-hidden rounded-lg bg-gray-600 text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
          <div class="bg-gray-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
            <h3 class="text-center text-sm font-bold text-gray-300" id="modal-title">Add New Member</h3>
            <div class="mt-4">
              <form @submit.prevent="addMember">
                <div class="space-y-4">
                  <div>
                    <InputLabel for="name" value="Name" />
                    <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                  </div>
                  <div>
                    <InputLabel for="email" value="Email" />
                    <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                  </div>
                  <div>
                    <InputLabel for="role" value="Role" />
                    <select id="role" v-model="form.role" class="mt-1 block w-full">
                      <option value="developer">Developer</option>
                      <option value="designer">Designer</option>
                      <option value="qa">QA Engineer</option>
                      <option value="other">Other</option>
                    </select>
                  </div>
                  <div>
                    <InputLabel for="organization" value="Organization" />
                    <select id="organization" v-model="form.organization_id" class="mt-1 block w-full">
                      <option v-for="org in organizations" :key="org.id" :value="org.id">
                        {{ org.name }}
                      </option>
                    </select>
                  </div>
                </div>
                <div class="mt-5 grid w-full grid-cols-2 items-center justify-center gap-3">
                  <PrimaryButton class="flex w-full items-center justify-center text-center" color="dragon-secondary" type="button" @click="showAddMemberModal = false">Cancel</PrimaryButton>
                  <PrimaryButton class="flex w-full items-center justify-center text-center" type="submit">Add Member</PrimaryButton>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Add to Task Modal -->
    <div v-if="showAddToTaskModal" class="fixed inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity dark:bg-gray-900" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>
        <div class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all dark:bg-gray-800 sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
          <div class="bg-white px-4 pb-4 pt-5 dark:bg-gray-800 sm:p-6 sm:pb-4">
            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100" id="modal-title">Add {{ selectedUser?.name }} to Task</h3>
            <div class="mt-4">
              <form @submit.prevent="addToTask(selectedUser.id)">
                <div>
                  <InputLabel for="item_id" value="Select Task" />
                  <select id="item_id" v-model="taskForm.item_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700">
                    <!-- Task options will be populated dynamically -->
                  </select>
                  <InputError :message="taskForm.errors.item_id" class="mt-2" />
                </div>
                <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                  <PrimaryButton type="submit" class="w-full sm:col-start-2"> Add to Task </PrimaryButton>
                  <SecondaryButton type="button" @click="showAddToTaskModal = false" class="mt-3 sm:col-start-1 sm:mt-0"> Cancel </SecondaryButton>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
.bg-gray-900 {
  background-color: #111827;
}
.bg-gray-800 {
  background-color: #1f2937;
}
.bg-gray-700 {
  background-color: #374151;
}
.border-gray-700 {
  border-color: #374151;
}
.text-gray-400 {
  color: #9ca3af;
}
.text-gray-500 {
  color: #6b7280;
}
.hover\:bg-gray-800:hover {
  background-color: #1f2937;
}
</style>

