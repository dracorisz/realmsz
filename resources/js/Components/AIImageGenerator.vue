<script setup>
import { ref } from 'vue';
import axios from 'axios';

const props = defineProps({
  apiKey: {
    type: String,
    required: true
  },
  apiUrl: {
    type: String,
    default: 'https://api.dezgo.com'
  }
});

const generationState = ref({
  loading: false,
  error: null,
  result: null,
  prompt: '',
  negativePrompt: 'blurry, low quality, distorted, ugly, deformed',
  width: 1024,
  height: 1024,
  style: 'realistic',
  selectedTemplate: null
});

const templates = [
  {
    name: 'Fantasy Art',
    prompt: 'A majestic dragon soaring through a mystical realm, surrounded by floating islands and ancient ruins, digital art, highly detailed, epic lighting, 8k resolution',
    style: 'fantasy',
    image: 'https://dracoscopia.com/images/ai/7.png'
  },
  {
    name: 'Cyberpunk City',
    prompt: 'A futuristic neon-lit cityscape with flying cars and holographic advertisements, cyberpunk style, rain-slicked streets, 8k resolution',
    style: 'cyberpunk',
    image: 'https://dracoscopia.com/tt.png'
  },
  {
    name: 'Abstract Art',
    prompt: 'Vibrant abstract composition with flowing colors and geometric shapes, digital art, high contrast, 8k resolution',
    style: 'abstract',
    image: 'https://dracoscopia.com/images/ai/3.png'
  },
  {
    name: 'Portrait',
    prompt: 'A detailed portrait of a mystical character with glowing eyes and intricate jewelry, digital art, highly detailed, 8k resolution',
    style: 'portrait',
    image: 'https://dracoscopia.com/images/ai/4.png'
  },
  {
    name: 'Landscape',
    prompt: 'A breathtaking fantasy landscape with floating mountains and cascading waterfalls, digital art, highly detailed, epic lighting, 8k resolution',
    style: 'landscape',
    image: 'https://dracoscopia.com/images/ai/8.png'
  }
];

const generateImage = async () => {
  if (!generationState.value.prompt) {
    generationState.value.error = 'Please enter a prompt';
    return;
  }

  generationState.value.loading = true;
  generationState.value.error = null;
  generationState.value.result = null;

  try {
    const response = await axios.post(
      `${props.apiUrl}/text2image_sdxl`,
      {
        prompt: generationState.value.prompt,
        width: generationState.value.width,
        height: generationState.value.height,
        style: generationState.value.style,
        negative_prompt: generationState.value.negativePrompt,
        num_images: 1,
        guidance_scale: 7.5,
        steps: 30
      },
      {
        headers: {
          'Content-Type': 'application/json',
          'X-Dezgo-Key': props.apiKey
        },
        responseType: 'blob'
      }
    );

    const imageUrl = URL.createObjectURL(response.data);
    generationState.value.result = imageUrl;
  } catch (error) {
    console.error('Generation error:', error);
    if (error.response) {
      generationState.value.error = error.response.data.detail || 'An error occurred during image generation';
    } else if (error.request) {
      generationState.value.error = 'Network error. Please check your connection.';
    } else {
      generationState.value.error = error.message || 'An unexpected error occurred';
    }
  } finally {
    generationState.value.loading = false;
  }
};

const selectTemplate = (template) => {
  generationState.value.prompt = template.prompt;
  generationState.value.style = template.style;
};
</script>

<template>
  <div class="space-y-8">
    <!-- Generation Form -->
    <div class="grid grid-cols-1 gap-12 lg:grid-cols-2">
      <div class="space-y-6">
        <div class="form-group">
          <label class="mb-2 block text-white">Custom Prompt</label>
          <textarea
            v-model="generationState.prompt"
            class="w-full rounded-lg border border-dragon-dark-600 bg-dragon-dark-700 p-3 text-white"
            rows="4"
            placeholder="Describe the image you want to generate..."
          ></textarea>
        </div>
        <div class="form-group">
          <label class="mb-2 block text-white">Negative Prompt</label>
          <textarea
            v-model="generationState.negativePrompt"
            class="w-full rounded-lg border border-dragon-dark-600 bg-dragon-dark-700 p-3 text-white"
            rows="2"
            placeholder="Describe what you don't want in the image..."
          ></textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div class="form-group">
            <label class="mb-2 block text-white">Width</label>
            <input
              v-model="generationState.width"
              type="number"
              class="w-full rounded-lg border border-dragon-dark-600 bg-dragon-dark-700 p-3 text-white"
            />
          </div>
          <div class="form-group">
            <label class="mb-2 block text-white">Height</label>
            <input
              v-model="generationState.height"
              type="number"
              class="w-full rounded-lg border border-dragon-dark-600 bg-dragon-dark-700 p-3 text-white"
            />
          </div>
        </div>
        <div>
          <label for="style" class="block text-sm font-medium text-gray-300">Style</label>
          <select
            id="style"
            v-model="generationState.style"
            class="mt-1 block w-full rounded-md border-dragon-dark-600 bg-dragon-dark-700 text-white shadow-sm focus:border-dragon-primary-500 focus:ring-dragon-primary-500 sm:text-sm"
          >
            <option value="realistic">Realistic</option>
            <option value="anime">Anime</option>
            <option value="fantasy">Fantasy</option>
            <option value="cyberpunk">Cyberpunk</option>
          </select>
        </div>
        <button
          @click="generateImage"
          :disabled="generationState.loading"
          class="btn-dragon w-full"
        >
          <span v-if="generationState.loading">Generating...</span>
          <span v-else>Generate Image</span>
        </button>
      </div>

      <!-- Templates -->
      <div>
        <h3 class="mb-4 text-xl font-semibold text-white">Quick Templates</h3>
        <div class="grid grid-cols-2 gap-4">
          <div
            v-for="template in templates"
            :key="template.name"
            class="template-card cursor-pointer rounded-lg p-4"
            @click="selectTemplate(template)"
          >
            <img
              :src="template.image"
              :alt="template.name"
              class="mb-2 h-32 w-full rounded-lg object-cover"
            />
            <h4 class="font-semibold text-white">{{ template.name }}</h4>
            <p class="text-sm text-gray-400">{{ template.style }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Result Display -->
    <div v-if="generationState.result" class="mt-12">
      <h3 class="mb-4 text-xl font-semibold text-white">Generated Image</h3>
      <div class="relative">
        <img
          :src="generationState.result"
          alt="Generated Image"
          class="mx-auto w-full max-w-2xl rounded-lg shadow-2xl"
        />
        <div class="absolute inset-0 rounded-lg bg-gradient-to-t from-dragon-dark-900/80 to-transparent"></div>
      </div>
      <button
        @click="generationState.result = null"
        class="mt-2 flex w-full justify-center rounded-md border border-transparent bg-dragon-dark-700 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-dragon-dark-600 focus:outline-none focus:ring-2 focus:ring-dragon-primary-500 focus:ring-offset-2"
      >
        Clear Result
      </button>
    </div>

    <!-- Error Display -->
    <div v-if="generationState.error" class="mt-4 rounded-lg border border-red-500 bg-red-500/10 p-4">
      <p class="text-red-500">{{ generationState.error }}</p>
    </div>
  </div>
</template>

<style scoped>
.btn-dragon {
  @apply rounded-lg bg-gradient-to-r from-teal-500 to-blue-600 px-6 py-3 font-semibold text-white shadow-xl transition-all duration-300 hover:shadow-teal-500/25;
}

.template-card {
  @apply border border-dragon-dark-600 bg-dragon-dark-700/50 backdrop-blur-sm transition-all duration-300 hover:border-teal-500/50;
}

.template-card:hover {
  @apply -translate-y-1 transform;
}

.form-group {
  @apply space-y-2;
}

.form-group input,
.form-group textarea {
  @apply transition-all duration-300 focus:border-teal-500 focus:ring-1 focus:ring-teal-500;
}
</style> 