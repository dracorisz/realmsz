<template>
  <FrontLayout title="Create NFT">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-indigo-900 to-purple-900 py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <h1 class="text-4xl font-extrabold text-white sm:text-5xl md:text-6xl">
            <span class="block">Create Your</span>
            <span class="block text-indigo-400">NFT Masterpiece</span>
          </h1>
          <p class="mt-3 max-w-md mx-auto text-base text-gray-300 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
            Generate unique NFT artwork using advanced AI technology and mint it on your preferred blockchain.
          </p>
        </div>
      </div>
    </div>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <form @submit.prevent="submitForm" class="space-y-8">
            <!-- Style Selection -->
            <div class="space-y-4">
              <h2 class="text-xl font-semibold flex items-center">
                <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Art Style
              </h2>
              <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <button
                  v-for="style in artStyles"
                  :key="style.name"
                  type="button"
                  @click="selectStyle(style)"
                  :class="[
                    'p-4 rounded-lg text-center transition-all duration-200 hover:transform hover:scale-105',
                    selectedStyle?.name === style.name
                      ? 'bg-indigo-100 border-2 border-indigo-500'
                      : 'bg-gray-50 border-2 border-transparent'
                  ]"
                >
                  <img :src="style.preview" :alt="style.name" class="w-full h-32 object-cover rounded mb-2">
                  <span class="block font-medium text-gray-900">{{ style.name }}</span>
                </button>
              </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
              <!-- Image Generation Section -->
              <div class="space-y-6">
                <h2 class="text-xl font-semibold flex items-center">
                  <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                  </svg>
                  Generate NFT Image
                </h2>
                
                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Prompt</label>
                    <div class="mt-1 relative">
                      <textarea
                        v-model="imagePrompt"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        rows="3"
                        placeholder="Describe your NFT image..."
                      ></textarea>
                      <button
                        type="button"
                        @click="enhancePrompt"
                        class="absolute right-2 bottom-2 text-sm text-indigo-600 hover:text-indigo-800"
                      >
                        Enhance prompt
                      </button>
                    </div>
                  </div>

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

                  <div class="space-y-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700">
                        Steps: {{ imageOptions.steps }}
                      </label>
                      <input
                        v-model="imageOptions.steps"
                        type="range"
                        min="20"
                        max="50"
                        class="mt-1 block w-full"
                      >
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">
                        CFG Scale: {{ imageOptions.cfg }}
                      </label>
                      <input
                        v-model="imageOptions.cfg"
                        type="range"
                        min="1"
                        max="20"
                        step="0.5"
                        class="mt-1 block w-full"
                      >
                    </div>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700">Negative Prompt</label>
                    <textarea
                      v-model="imageOptions.negative_prompt"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                      rows="2"
                      placeholder="What to avoid in the image..."
                    ></textarea>
                  </div>

                  <button
                    type="button"
                    @click="generateImage"
                    :disabled="generating"
                    class="w-full flex justify-center items-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
                  >
                    <svg
                      v-if="generating"
                      class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                      fill="none"
                      viewBox="0 0 24 24"
                    >
                      <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4"
                      />
                      <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                      />
                    </svg>
                    {{ generating ? 'Generating...' : 'Generate Image' }}
                  </button>
                </div>
              </div>

              <!-- Preview Section -->
              <div class="space-y-6">
                <h2 class="text-xl font-semibold flex items-center">
                  <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                  Preview
                </h2>
                <div
                  class="border-2 border-dashed border-gray-300 rounded-lg p-4 flex items-center justify-center min-h-[400px] relative group"
                >
                  <template v-if="previewImage">
                    <img :src="previewImage" class="max-w-full max-h-[400px] object-contain" />
                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                      <button
                        type="button"
                        @click="regenerateImage"
                        class="bg-white text-gray-900 px-4 py-2 rounded-md font-medium hover:bg-gray-100 transition-colors duration-200"
                      >
                        Regenerate
                      </button>
                    </div>
                  </template>
                  <template v-else>
                    <div class="text-center">
                      <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                      <p class="mt-2 text-gray-500">Generated image will appear here</p>
                    </div>
                  </template>
                </div>
              </div>
            </div>

            <!-- NFT Details -->
            <div class="space-y-6">
              <h2 class="text-xl font-semibold flex items-center">
                <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                NFT Details
              </h2>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Name</label>
                  <input
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required
                  >
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Description</label>
                  <textarea
                    v-model="form.description"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    rows="3"
                    required
                  ></textarea>
                </div>
              </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
              <button
                type="submit"
                :disabled="submitting"
                class="inline-flex items-center justify-center py-3 px-6 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
              >
                <svg
                  v-if="submitting"
                  class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                  fill="none"
                  viewBox="0 0 24 24"
                >
                  <circle
                    class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4"
                  />
                  <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                  />
                </svg>
                {{ submitting ? 'Creating NFT...' : 'Create NFT' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </FrontLayout>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { useForm } from '@inertiajs/vue3';
import FrontLayout from '@/Layouts/FrontLayout.vue';
import DezgoService from '@/Services/DezgoService';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

const generating = ref(false);
const submitting = ref(false);
const previewImage = ref(null);

const imagePrompt = ref('');
const imageOptions = reactive({
  width: 512,
  height: 512,
  steps: 30,
  cfg: 7.5,
  negative_prompt: '',
});

const form = useForm({
  name: '',
  description: '',
  image: null,
});

const artStyles = [
  {
    name: 'Realistic',
    preview: `${import.meta.env.VITE_ASSET_URL}/images/styles/realistic.jpg`,
    prompt: 'highly detailed, photorealistic, 8k resolution, professional photography',
  },
  {
    name: 'Anime',
    preview: `${import.meta.env.VITE_ASSET_URL}/images/styles/anime.jpg`,
    prompt: 'anime style, Studio Ghibli, detailed, vibrant colors',
  },
  {
    name: 'Digital Art',
    preview: `${import.meta.env.VITE_ASSET_URL}/images/styles/digital.jpg`,
    prompt: 'digital art, highly detailed, concept art, trending on artstation',
  },
  {
    name: 'Oil Painting',
    preview: `${import.meta.env.VITE_ASSET_URL}/images/styles/oil.jpg`,
    prompt: 'oil painting, masterpiece, detailed brushstrokes, classical style',
  },
];

const selectedStyle = ref(null);

const selectStyle = (style) => {
  selectedStyle.value = style;
  if (imagePrompt.value) {
    imagePrompt.value = `${style.prompt}, ${imagePrompt.value}`;
  } else {
    imagePrompt.value = style.prompt;
  }
};

const enhancePrompt = () => {
  if (!imagePrompt.value) return;
  
  const enhancements = [
    'highly detailed',
    '8k resolution',
    'professional lighting',
    'masterpiece',
    'trending on artstation',
  ];
  
  const randomEnhancements = enhancements
    .sort(() => 0.5 - Math.random())
    .slice(0, 2)
    .join(', ');
  
  imagePrompt.value = `${imagePrompt.value}, ${randomEnhancements}`;
};

const generateImage = async () => {
  if (!imagePrompt.value) {
    alert('Please enter a prompt');
    return;
  }

  generating.value = true;
  try {
    const imageBlob = await DezgoService.generateImage(imagePrompt.value, imageOptions);
    previewImage.value = URL.createObjectURL(imageBlob);
    form.image = imageBlob;
  } catch (error) {
    console.error('Error generating image:', error);
    alert('Failed to generate image. Please try again.');
  } finally {
    generating.value = false;
  }
};

const regenerateImage = () => {
  generateImage();
};

const submitForm = async () => {
  if (!form.image) {
    alert('Please generate an image first');
    return;
  }

  submitting.value = true;
  try {
    await form.post(route('nft.store'), {
      onSuccess: () => {
        form.reset();
        previewImage.value = null;
        imagePrompt.value = '';
        selectedStyle.value = null;
      },
    });
  } finally {
    submitting.value = false;
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
  gsap.from(".art-style-section", {
    scrollTrigger: {
      trigger: ".art-style-section",
      start: "top center+=100",
      toggleActions: "play none none reverse"
    },
    duration: 0.8,
    y: 50,
    opacity: 0,
    ease: "power2.out"
  });

  gsap.from(".preview-section", {
    scrollTrigger: {
      trigger: ".preview-section",
      start: "top center+=100",
      toggleActions: "play none none reverse"
    },
    duration: 0.8,
    y: 50,
    opacity: 0,
    ease: "power2.out"
  });
});
</script>

<style scoped>
.hero-gradient {
  background: linear-gradient(45deg, #4f46e5, #7c3aed);
  transition: background 0.5s ease;
}

input[type="range"] {
  -webkit-appearance: none;
  width: 100%;
  height: 4px;
  background: #e5e7eb;
  border-radius: 2px;
  outline: none;
}

input[type="range"]::-webkit-slider-thumb {
  -webkit-appearance: none;
  width: 16px;
  height: 16px;
  background: #4f46e5;
  border-radius: 50%;
  cursor: pointer;
  transition: all 0.2s ease;
}

input[type="range"]::-webkit-slider-thumb:hover {
  transform: scale(1.2);
}

.art-style-button {
  transition: all 0.2s ease;
}

.art-style-button:hover {
  transform: translateY(-2px);
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.animate-spin {
  animation: spin 1s linear infinite;
}
</style> 