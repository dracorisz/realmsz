<script setup>
import { ref, onMounted } from "vue";
import axios from 'axios';
import ActionSection from "@/Components/ActionSection.vue";
import { useToast } from 'vue-toastification';

const toast = useToast();
const activities = ref([]);
const isLoading = ref(true);

const fetchActivities = async () => {
  try {
    const response = await axios.get(route('user.activities'));
    activities.value = response.data.activities;
  } catch (error) {
    console.error('Error fetching activities:', error);
    toast.error('Failed to load activities');
  } finally {
    isLoading.value = false;
  }
};

onMounted(() => {
  fetchActivities();
});
</script>

<template>
  <ActionSection>
    <template #title>Recent Activity</template>
    <template #description>View your recent network interactions and profile updates.</template>
    <template #content>
      <div v-if="isLoading" class="flex items-center justify-center py-8">
        <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-primary"></div>
      </div>
      
      <div v-else-if="activities.length === 0" class="text-center py-8">
        <p class="text-gray-500">No recent activity</p>
      </div>

      <div v-else class="space-y-4">
        <div v-for="activity in activities" :key="activity.id" class="flex items-start space-x-4 p-4 rounded-lg bg-white/5">
          <div class="flex-shrink-0">
            <div class="h-10 w-10 rounded-full bg-primary/20 flex items-center justify-center">
              <span class="text-primary">{{ activity.icon }}</span>
            </div>
          </div>
          <div class="flex-1">
            <p class="text-sm text-white">{{ activity.description }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ activity.created_at }}</p>
          </div>
        </div>
      </div>
    </template>
  </ActionSection>
</template>

