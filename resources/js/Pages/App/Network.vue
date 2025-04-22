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
  users: Object,
  organizations: Array,
  filters: Object,
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
  { id: "private", label: "Private" },
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

const fetchConnections = async () => {
  isLoading.value = true;
  try {
    const response = await axios.get(route("network.members"), {
      params: {
        type: activeTab.value,
      },
    });
    connections.value = response.data.users;
    console.log(connections.value);
  } catch (error) {
    console.error("Error fetching connections:", error);
    toast.error("Failed to load connections");
  } finally {
    isLoading.value = false;
  }
};

const filteredConnections = computed(() => {
  if (!searchQuery.value) return connections.value;

  // const query = searchQuery.value.toLowerCase();
  // return connections.value.filter((connection) => connection.name.toLowerCase().includes(query) || connection.email.toLowerCase().includes(query) || connection.role.toLowerCase().includes(query));
  return connections.value;
});

const updateVisibility = (show) => {
  useForm({ show_in_network: show }).post(route("network.visibility"), {
    preserveScroll: true,
  });
};

const addToTask = (userId) => {
  taskForm.user_id = userId;
  taskForm.post(route("network.add-to-task"), {
    preserveScroll: true,
  });
};

const removeFromTask = (userId, itemId) => {
  useForm({
    user_id: userId,
    item_id: itemId,
  }).post(route("network.remove-from-task"), {
    preserveScroll: true,
  });
};

const submitReview = () => {
  reviewForm.user_id = selectedUser.value.id;
  reviewForm.post(route("network.review"), {
    preserveScroll: true,
    onSuccess: () => {
      showReviewModal.value = false;
    },
  });
};

const toggleSort = (field) => {
  if (sortBy.value === field) {
    sortDirection.value = sortDirection.value === "asc" ? "desc" : "asc";
  } else {
    sortBy.value = field;
    sortDirection.value = "asc";
  }
};

const addMember = () => {
  form.post(route("network.members.store"), {
    preserveScroll: true,
    onSuccess: () => {
      showAddMemberModal.value = false;
      form.reset();
    },
  });
};

onMounted(() => {
  fetchConnections();
});

watch(activeTab, () => {
  fetchConnections();
});
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

    <div class="py-12">
      <div class="mx-auto max-w-7xl">
        <div class="overflow-hidden bg-gray-800 shadow-sm dark:bg-white sm:rounded-lg">
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
              <button @click="showAddMemberModal = true" class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-indigo-700 focus:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-indigo-900">Add Member</button>
            </div>

            <!-- Members Grid -->
            <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
              <div v-for="connection in filteredConnections" :key="connection.id" class="rounded-lg bg-white p-6 shadow dark:bg-gray-700">
                <div class="flex items-center space-x-4">
                  <img :src="connection.avatar || '/images/default-avatar.png'" :alt="connection.name" class="h-12 w-12 rounded-full" />
                  <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                      {{ connection.name }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                      {{ connection.email }}
                    </p>
                  </div>
                </div>
                <div class="mt-4">
                  <p class="text-sm text-gray-600 dark:text-gray-300">
                    {{ connection.role }}
                  </p>
                </div>
                <div class="mt-4 flex space-x-2">
                  <SecondaryButton
                    @click="
                      selectedUser = connection;
                      showAddToTaskModal = true;
                    "
                    >Add to Task
                  </SecondaryButton>
                  <PrimaryButton v-if="connection.organization" @click="activeTab = 'organization'"> View Organization </PrimaryButton>
                </div>
              </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
              <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700 dark:text-gray-300">Showing {{ users.from }} to {{ users.to }} of {{ users.total }} results</div>
                <div class="flex space-x-2">
                  <button v-if="users.prev_page_url" @click="$inertia.visit(users.prev_page_url)" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">Previous</button>
                  <button v-if="users.next_page_url" @click="$inertia.visit(users.next_page_url)" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">Next</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Add Member Modal -->
    <div v-if="showAddMemberModal" class="fixed inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>
        <div class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
          <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Add New Member</h3>
            <div class="mt-4">
              <form @submit.prevent="addMember">
                <div class="space-y-4">
                  <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" id="name" v-model="form.name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                  </div>
                  <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" v-model="form.email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                  </div>
                  <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select id="role" v-model="form.role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                      <option value="developer">Developer</option>
                      <option value="designer">Designer</option>
                      <option value="qa">QA Engineer</option>
                      <option value="other">Other</option>
                    </select>
                  </div>
                  <div>
                    <label for="organization" class="block text-sm font-medium text-gray-700">Organization</label>
                    <select id="organization" v-model="form.organization_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                      <option v-for="org in organizations" :key="org.id" :value="org.id">
                        {{ org.name }}
                      </option>
                    </select>
                  </div>
                </div>
                <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                  <button type="submit" class="inline-flex w-full justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:col-start-2 sm:text-sm">Add Member</button>
                  <button type="button" @click="showAddMemberModal = false" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:col-start-1 sm:mt-0 sm:text-sm">Cancel</button>
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

