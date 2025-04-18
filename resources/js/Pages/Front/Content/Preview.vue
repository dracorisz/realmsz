<template>
  <FrontLayout>
    <div class="relative">
      <!-- Hero Section -->
      <div class="bg-gradient-to-r from-indigo-900 to-purple-900 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="text-center">
            <h1 class="text-4xl font-extrabold text-white sm:text-5xl md:text-6xl">
              <span class="block">Content</span>
              <span class="block text-indigo-400">Preview</span>
            </h1>
            <p class="mt-3 max-w-md mx-auto text-base text-gray-300 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
              Preview and generate amazing content with AI-powered tools and Virtual Cosplays integration.
            </p>
          </div>
        </div>
      </div>

      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
              <!-- Preview Content -->
              <div class="mb-6">
                <h3 class="text-2xl font-bold text-gray-900">{{ preview.title }}</h3>
                <p class="mt-2 text-gray-600">{{ preview.description }}</p>
              </div>

              <!-- Image Preview Section -->
              <div v-if="preview.imageUrl" class="mb-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                  <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  Generated Image
                </h4>
                <div class="relative group">
                  <img 
                    :src="preview.imageUrl" 
                    :alt="preview.title"
                    class="w-full h-64 object-cover rounded-lg shadow-lg transition-transform duration-300 transform group-hover:scale-105"
                  />
                  <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg flex items-center justify-center">
                    <button 
                      @click="regenerateImage"
                      class="bg-white text-gray-900 px-4 py-2 rounded-md font-medium hover:bg-gray-100 transition-colors duration-200"
                      :disabled="processing"
                    >
                      Regenerate Image
                    </button>
                  </div>
                </div>
              </div>

              <!-- Image Generation Form -->
              <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                  <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                  </svg>
                  Image Generation Options
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="space-y-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Prompt</label>
                      <textarea
                        v-model="imageOptions.prompt"
                        rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Describe your image..."
                      ></textarea>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Negative Prompt</label>
                      <textarea
                        v-model="imageOptions.negative_prompt"
                        rows="2"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="What to avoid in the image..."
                      ></textarea>
                    </div>
                  </div>
                  <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                      <div>
                        <label class="block text-sm font-medium text-gray-700">Width</label>
                        <select
                          v-model="imageOptions.width"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                          <option value="512">512px</option>
                          <option value="768">768px</option>
                          <option value="1024">1024px</option>
                        </select>
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-gray-700">Height</label>
                        <select
                          v-model="imageOptions.height"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                          <option value="512">512px</option>
                          <option value="768">768px</option>
                          <option value="1024">1024px</option>
                        </select>
                      </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                      <div>
                        <label class="block text-sm font-medium text-gray-700">Steps</label>
                        <input
                          type="number"
                          v-model="imageOptions.steps"
                          min="20"
                          max="50"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-gray-700">CFG Scale</label>
                        <input
                          type="number"
                          v-model="imageOptions.cfg_scale"
                          min="1"
                          max="20"
                          step="0.5"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Virtual Cosplays Integration -->
              <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                  <img 
                    :src="`${assetUrl}/images/partners/virtual-cosplays.png`" 
                    alt="Virtual Cosplays"
                    class="w-5 h-5 mr-2"
                  />
                  Virtual Cosplays Integration
                </h4>
                <div class="bg-gray-50 rounded-lg p-4">
                  <div class="flex items-center justify-between">
                    <div>
                      <p class="text-sm text-gray-500">Export to Virtual Cosplays</p>
                      <p class="text-sm text-gray-600">Generate 3D models from your images</p>
                    </div>
                    <button
                      @click="exportToVirtualCosplays"
                      class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                      Export
                    </button>
                  </div>
                </div>
              </div>

              <!-- Features Section -->
              <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                  <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                  </svg>
                  Features
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div
                    v-for="feature in preview.features"
                    :key="feature.name"
                    class="bg-gray-50 p-4 rounded-lg feature-card hover:shadow-lg transition-shadow duration-300"
                  >
                    <div class="flex items-center">
                      <svg
                        v-if="feature.enabled"
                        class="h-5 w-5 text-green-500 mr-2"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M5 13l4 4L19 7"
                        />
                      </svg>
                      <div>
                        <p class="font-medium text-gray-900">{{ feature.name }}</p>
                        <p class="text-sm text-gray-500">{{ feature.description }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Action Buttons -->
              <div class="flex justify-end space-x-4">
                <PrimaryButton
                  @click="generateContent"
                  :class="{ 'opacity-25': processing }"
                  :disabled="processing"
                  class="generate-button"
                >
                  {{ processing ? 'Generating...' : 'Generate Content' }}
                </PrimaryButton>
                <Link
                  :href="route('content.show', package.slug)"
                  class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 view-button"
                >
                  View Generated Content
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </FrontLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import FrontLayout from '@/Layouts/FrontLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

const props = defineProps({
  preview: {
    type: Object,
    required: true,
  },
  package: {
    type: Object,
    required: true,
  },
});

const processing = ref(false);
const imageOptions = ref({
  prompt: '',
  negative_prompt: '',
  width: 512,
  height: 512,
  steps: 30,
  cfg_scale: 7.5
});

const formatDate = (date) => {
  return new Date(date).toLocaleString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
};

const generateContent = async () => {
  processing.value = true;
  try {
    await router.post(route('content.generate', props.package.slug), {
      ...imageOptions.value
    });
  } catch (error) {
    console.error('Error generating content:', error);
  } finally {
    processing.value = false;
  }
};

const regenerateImage = async () => {
  if (!imageOptions.value.prompt) {
    imageOptions.value.prompt = preview.value.title;
  }
  await generateContent();
};

const exportToVirtualCosplays = async () => {
  if (!preview.imageUrl) {
    alert('Please generate an image first');
    return;
  }

  try {
    // Implement Virtual Cosplays export logic here
    alert('Exporting to Virtual Cosplays...');
  } catch (error) {
    console.error('Error exporting to Virtual Cosplays:', error);
    alert('Failed to export to Virtual Cosplays. Please try again.');
  }
};

onMounted(() => {
  gsap.registerPlugin(ScrollTrigger);

  // Hero section animations
  const heroTimeline = gsap.timeline();
  heroTimeline
    .from("h1 span:first-child", {
      duration: 0.8,
      y: 30,
      opacity: 0,
      ease: "power2.out"
    })
    .from("h1 span:last-child", {
      duration: 0.8,
      y: 30,
      opacity: 0,
      ease: "power2.out"
    }, "-=0.4")
    .from(".text-gray-300", {
      duration: 0.8,
      y: 30,
      opacity: 0,
      ease: "power2.out"
    }, "-=0.4");

  // Content animations
  const contentTimeline = gsap.timeline({
    scrollTrigger: {
      trigger: ".text-2xl",
      start: "top center+=100",
      toggleActions: "play none none reverse"
    }
  });

  contentTimeline
    .from(".text-2xl", {
      duration: 0.8,
      y: 30,
      opacity: 0,
      ease: "power2.out"
    })
    .from(".text-gray-600", {
      duration: 0.8,
      y: 20,
      opacity: 0,
      ease: "power2.out"
    }, "-=0.4");

  // Features animations
  gsap.from(".feature-card", {
    scrollTrigger: {
      trigger: ".feature-card",
      start: "top center+=100",
      toggleActions: "play none none reverse"
    },
    duration: 0.8,
    y: 50,
    opacity: 0,
    stagger: 0.1,
    ease: "back.out(1.7)"
  });

  // Button animations
  gsap.from(".generate-button, .view-button", {
    scrollTrigger: {
      trigger: ".generate-button",
      start: "top center+=100",
      toggleActions: "play none none reverse"
    },
    duration: 0.8,
    y: 20,
    opacity: 0,
    stagger: 0.2,
    ease: "power2.out"
  });
});
</script>

<style scoped>
.feature-card {
  transition: all 0.3s ease;
}

.feature-card:hover {
  transform: translateY(-5px);
}

.hero-gradient {
  background: linear-gradient(45deg, #4f46e5, #7c3aed);
  transition: background 0.5s ease;
}

@keyframes pulse {
  0% {
    transform: scale(1);
    opacity: 0.6;
  }
  50% {
    transform: scale(1.2);
    opacity: 0.8;
  }
  100% {
    transform: scale(1);
    opacity: 0.6;
  }
}
</style> 