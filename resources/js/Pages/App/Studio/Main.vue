<script setup>
import { ref, onMounted, watch, nextTick } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import AIImageGenerator from "@/Components/AIImageGenerator.vue";
import { useTemplates } from "@/Utils/useTemplates";
import { useToast } from "vue-toastification";
import axios from "axios";
import Loader from "@/Components/Loader.vue";

const toast = useToast();
const activeTab = ref("generate");
const isLoading = ref(false);
const images = ref([]);
const error = ref(null);
const generatedImage = ref(null);
const aiGeneratorRef = ref(null);
const { templates } = useTemplates();

const tabs = [
  { id: "generate", label: "Generate", icon: "🎨", description: "Create AI-generated images" },
  { id: "gallery", label: "Gallery", icon: "🖼️", description: "View your images" },
  { id: "templates", label: "Templates", icon: "📋", description: "Use pre-made templates" },
];

const fetchImages = async () => {
  isLoading.value = true;
  error.value = null;
  try {
    const response = await axios.get(route("images.organization"), {
      withCredentials: false,
    });

    if (response.data.success) {
      images.value = response.data.images.map((image) => ({
        ...image,
        image: image.image_url,
      }));
    } else {
      error.value = response.data.message || "Failed to fetch images";
      toast.error(error.value);
    }
  } catch (error) {
    console.error("Error fetching images:", error);
    error.value = error.response?.data?.message || "Failed to load images";
    toast.error(error.value);
  } finally {
    isLoading.value = false;
  }
};

const handleTemplateSelect = (template) => {
  activeTab.value = "generate";
  nextTick(() => {
    if (aiGeneratorRef.value?.selectTemplate) {
      aiGeneratorRef.value.selectTemplate(template);
    } else {
      console.error('AIGenerator component or selectTemplate method not available');
      toast.error('Failed to apply template. Please try again.');
    }
  });
};

const handleImageGenerated = async (imageUrl) => {
  try {
    generatedImage.value = imageUrl;
    toast.success("Image saved successfully");
    if (activeTab.value === "gallery") {
      fetchImages();
    }
  } catch (error) {
    console.error("Error saving image:", error);
    toast.error(error.response?.data?.message || "Failed to save image");
  }
};

const deleteImage = async (imageId) => {
  try {
    const response = await axios.delete(route('images.delete', { id: imageId }));
    if (response.data.success) {
      images.value = images.value.filter(img => img.id !== imageId);
      toast.success('Image deleted successfully');
    } else {
      throw new Error(response.data.message || 'Failed to delete image');
    }
  } catch (error) {
    console.error('Error deleting image:', error);
    toast.error(error.response?.data?.message || 'Failed to delete image');
    fetchImages();
  }
};

const generationState = ref({
  loading: false,
  downloading: false,
  error: null,
  result: null,
  prompt: "",
  negativePrompt: "blurry, low quality, distorted, ugly, deformed, bad anatomy, bad proportions, poorly drawn face, duplicate, cropped, extra limbs, poorly drawn hands, poorly drawn feet, poorly drawn face, out of frame, extra limbs, disfigured, deformed, body out of frame, bad anatomy, cut off, distorted face",
  width: 1024,
  height: 1024,
  style: "realistic",
  model: "flux",
  guidance_scale: 7.5,
  steps: 30,
  seed: null,
  selectedTemplate: null,
});

watch(activeTab, (newTab) => {
  if (newTab === "gallery") {
    fetchImages();
  }
});

onMounted(() => {
  if (activeTab.value === "gallery") {
    fetchImages();
  }
});
</script>

<template>
  <AppLayout title="Studio">
    <div class="flex w-full items-start justify-start p-5">
      <div class="flex flex-col items-start justify-start gap-2">
        <h2 class="text-2xl font-bold leading-tight text-dragon-primary">Creative Studio</h2>
        <p class="mt-1 text-sm text-gray-400">Create stunning AI-generated images</p>
      </div>
    </div>

    <div class="flex h-full flex-1 items-start justify-start px-5">
      <div class="flex h-full w-full flex-1 flex-col items-start justify-start">
        <div class="flex-start mb-5 w-full justify-start">
          <nav class="flex items-start justify-start space-x-4" aria-label="Tabs">
            <button v-for="tab in tabs" :key="tab.id" @click="activeTab = tab.id" :class="['group flex items-start justify-start space-x-2 rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200', activeTab === tab.id ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/20' : 'text-gray-400 hover:bg-gray-800 hover:text-white']">
              <span class="text-xl">{{ tab.icon }}</span>
              <div class="flex flex-col items-start justify-start text-left">
                <span>{{ tab.label }}</span>
                <span class="text-xs opacity-70">{{ tab.description }}</span>
              </div>
            </button>
          </nav>
        </div>

        <div class="flex h-full w-full flex-1 items-start justify-start overflow-y-scroll rounded-xl bg-white/10 pb-40 shadow-xl">
          <div class="p-5 text-gray-200">
            <!-- Generate Tab -->
            <div v-if="activeTab === 'generate'" class="space-y-5">
              <div class="grid grid-cols-1 gap-5 lg:grid-cols-1">
                <div class="space-y-5">
                  <AIImageGenerator 
                    ref="aiGeneratorRef"
                    @image-generated="handleImageGenerated" 
                  />
                </div>
              </div>
            </div>

            <!-- Gallery Tab -->
            <div v-if="activeTab === 'gallery'" class="space-y-5">
              <div v-if="isLoading" class="flex w-full h-full items-center justify-center">
                <Loader />
              </div>

              <div v-else-if="error" class="rounded-lg bg-red-500/10 p-4 text-center">
                <p class="text-red-400">{{ error }}</p>
                <button @click="fetchImages" class="mt-4 rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Try Again</button>
              </div>

              <div v-else-if="images.length === 0" class="py-10 text-center">
                <p class="text-gray-400">No images found</p>
              </div>

              <div v-else class="gap-5 flex items-start justify-start flex-wrap">
                <div v-for="image in images" :key="image.id" class="group relative overflow-hidden rounded-lg bg-gray-700">
                  <div class="w-full h-44 max-w-44">
                    <img :src="image.image" :alt="image.title" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                  </div>
                  <button @click="deleteImage(image.id)" class="absolute right-2 top-2 z-30 flex items-center justify-center rounded-full bg-red-500 hover:bg-red-600 p-2 text-white opacity-0 transition-opacity group-hover:opacity-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                  </button>
                  <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                    <div class="absolute bottom-0 left-0 right-0 p-2">
                      <h3 class="text-sm font-medium text-white">{{ image.title }}</h3>
                      <p class="text-xs text-gray-400">{{ new Date(image.created_at).toLocaleDateString() }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Templates Tab -->
            <div v-if="activeTab === 'templates'" class="space-y-5">
              <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="template in templates" :key="template.name" 
                  class="group cursor-pointer rounded-lg bg-gray-700/20 p-4 transition-all duration-300 hover:bg-gray-600" 
                  @click="handleTemplateSelect(template)"
                >
                  <img :src="template.image" :alt="template.name" class="mb-3 h-48 w-full rounded-lg object-cover transition-transform duration-300 group-hover:scale-105" />
                  <h3 class="text-lg font-semibold text-white">{{ template.name }}</h3>
                  <p class="mt-1 text-sm text-gray-400">{{ template.style }}</p>
                  <p class="mt-2 line-clamp-2 text-xs text-gray-500">{{ template.prompt }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
