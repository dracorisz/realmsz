<template>
  <div class="bg-dragon-dark-800/50 rounded-lg p-6">
    <h3 class="text-xl font-bold text-white mb-4">AI Image Generator</h3>
    
    <!-- Generation Form -->
    <form @submit.prevent="generateImage" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-300">Prompt</label>
        <textarea
          v-model="prompt"
          class="mt-1 block w-full rounded-md bg-dragon-dark-700 border-gray-600 text-white shadow-sm focus:border-dragon-primary focus:ring-dragon-primary"
          rows="3"
          placeholder="Describe the image you want to generate..."
        ></textarea>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-300">Width</label>
          <select
            v-model="width"
            class="mt-1 block w-full rounded-md bg-dragon-dark-700 border-gray-600 text-white shadow-sm focus:border-dragon-primary focus:ring-dragon-primary"
          >
            <option value="512">512px</option>
            <option value="768">768px</option>
            <option value="1024">1024px</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-300">Height</label>
          <select
            v-model="height"
            class="mt-1 block w-full rounded-md bg-dragon-dark-700 border-gray-600 text-white shadow-sm focus:border-dragon-primary focus:ring-dragon-primary"
          >
            <option value="512">512px</option>
            <option value="768">768px</option>
            <option value="1024">1024px</option>
          </select>
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-300">Style</label>
        <select
          v-model="style"
          class="mt-1 block w-full rounded-md bg-dragon-dark-700 border-gray-600 text-white shadow-sm focus:border-dragon-primary focus:ring-dragon-primary"
        >
          <option value="realistic">Realistic</option>
          <option value="fantasy">Fantasy</option>
          <option value="anime">Anime</option>
          <option value="cyberpunk">Cyberpunk</option>
          <option value="abstract">Abstract</option>
        </select>
      </div>

      <button
        type="submit"
        :disabled="isGenerating"
        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-dragon-primary hover:bg-dragon-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-dragon-primary disabled:opacity-50"
      >
        {{ isGenerating ? 'Generating...' : 'Generate Image' }}
      </button>
    </form>

    <!-- Results -->
    <div v-if="generatedImage" class="mt-6">
      <h4 class="text-lg font-medium text-white mb-2">Generated Image</h4>
      <div class="relative">
        <img
          :src="generatedImage"
          alt="Generated image"
          class="w-full rounded-lg"
        />
        <div class="absolute top-2 right-2 flex space-x-2">
          <button
            @click="downloadImage"
            class="p-2 bg-dragon-dark-700 rounded-full hover:bg-dragon-dark-600"
          >
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
          </button>
          <button
            @click="regenerateImage"
            class="p-2 bg-dragon-dark-700 rounded-full hover:bg-dragon-dark-600"
          >
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Error Message -->
    <div v-if="error" class="mt-4 p-4 bg-red-500/10 border border-red-500 rounded-md">
      <p class="text-red-500">{{ error }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const DEZGO_API_URL = 'https://api.dezgo.com/v1';
const DEZGO_API_KEY = 'YOUR_API_KEY'; // Replace with your actual API key

const prompt = ref('');
const width = ref('512');
const height = ref('512');
const style = ref('realistic');
const isGenerating = ref(false);
const generatedImage = ref(null);
const error = ref(null);

const generateImage = async () => {
  try {
    isGenerating.value = true;
    error.value = null;

    const response = await axios.post(
      `${DEZGO_API_URL}/text2image`,
      {
        prompt: prompt.value,
        width: parseInt(width.value),
        height: parseInt(height.value),
        style: style.value,
      },
      {
        headers: {
          'Authorization': `Bearer ${DEZGO_API_KEY}`,
          'Content-Type': 'application/json',
        },
      }
    );

    generatedImage.value = response.data.image;
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to generate image';
    console.error('Generation error:', err);
  } finally {
    isGenerating.value = false;
  }
};

const downloadImage = () => {
  if (!generatedImage.value) return;
  
  const link = document.createElement('a');
  link.href = generatedImage.value;
  link.download = `generated-image-${Date.now()}.png`;
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
};

const regenerateImage = () => {
  if (!prompt.value) return;
  generateImage();
};
</script> 