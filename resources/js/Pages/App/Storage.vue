<script setup>
import AppLayout from "../../Layouts/AppLayout.vue";
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useToast } from 'vue-toastification';

const toast = useToast();
const images = ref([]);
const isLoading = ref(true);
const activeTab = ref('organization');

const tabs = [
  { id: 'organization', label: 'Organization' },
  { id: 'realmsz', label: 'Realmsz (Public)' },
  { id: 'private', label: 'Private' }
];

const fetchImages = async () => {
  try {
    const response = await axios.get(route('images.organization'), {
      withCredentials: false
    });
    
    if (response.data.success) {
      images.value = response.data.images.map(image => ({
        ...image,
        image: image.image_url // S3 URL is already provided by the backend
      }));
    } else {
      toast.error(response.data.message || 'Failed to load images');
    }
  } catch (error) {
    console.error('Error fetching images:', error);
    toast.error(error.response?.data?.message || 'Failed to load images');
  } finally {
    isLoading.value = false;
  }
};

const deleteImage = async (imageId) => {
  try {
    const response = await axios.delete(route('images.delete', { id: imageId }), {
      withCredentials: false
    });
    if (response.data.success) {
      images.value = images.value.filter(img => img.id !== imageId);
      toast.success('Image deleted successfully');
    }
  } catch (error) {
    console.error('Error deleting image:', error);
    toast.error('Failed to delete image');
  }
};

onMounted(() => {
  fetchImages();
});
</script>

<template>
  <AppLayout title="Storage">
    <div class="min-h-screen bg-gray-900 text-white">
      <!-- Tabs -->
      <div class="border-b border-gray-700">
        <nav class="flex space-x-8 px-4 py-4" aria-label="Tabs">
          <button
            v-for="tab in tabs"
            :key="tab.id"
            @click="activeTab = tab.id"
            :class="[
              'px-3 py-2 text-sm font-medium rounded-md',
              activeTab === tab.id
                ? 'bg-gray-800 text-white'
                : 'text-gray-400 hover:text-white hover:bg-gray-800'
            ]"
          >
            {{ tab.label }}
          </button>
        </nav>
      </div>

      <!-- Content -->
      <div class="p-6">
        <div v-if="isLoading" class="flex items-center justify-center h-64">
          <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-white"></div>
        </div>

        <div v-else-if="images.length === 0" class="text-center py-12">
          <p class="text-gray-400">No images found</p>
        </div>

        <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
          <div
            v-for="image in images"
            :key="image.id"
            class="group relative overflow-hidden rounded-lg bg-gray-800"
          >
            <img
              :src="image.image"
              :alt="image.title"
              class="w-full h-64 object-cover transition-transform duration-300 group-hover:scale-105"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
              <div class="absolute bottom-0 left-0 right-0 p-4">
                <h3 class="text-white font-medium">{{ image.title }}</h3>
                <p class="text-gray-400 text-sm">{{ new Date(image.created_at).toLocaleDateString() }}</p>
                <button
                  @click="deleteImage(image.id)"
                  class="mt-2 text-red-400 hover:text-red-300 text-sm font-medium"
                >
                  Delete
                </button>
              </div>
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
.border-gray-700 {
  border-color: #374151;
}
.text-gray-400 {
  color: #9ca3af;
}
.hover\:bg-gray-800:hover {
  background-color: #1f2937;
}
</style>

