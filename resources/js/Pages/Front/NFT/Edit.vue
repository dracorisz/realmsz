<template>
  <FrontLayout title="Edit NFT">
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <h1 class="text-3xl font-bold mb-8">Edit NFT</h1>
          
          <form @submit.prevent="submitForm" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Image Section -->
              <div class="space-y-4">
                <h2 class="text-xl font-semibold">NFT Image</h2>
                <div class="relative aspect-square rounded-lg overflow-hidden">
                  <img 
                    :src="previewImage || nft.image_url" 
                    :alt="form.name"
                    class="w-full h-full object-cover"
                  />
                  <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-10 transition-all duration-300">
                    <button 
                      type="button"
                      @click="regenerateImage"
                      class="absolute top-4 right-4 bg-white bg-opacity-90 hover:bg-opacity-100 text-gray-800 p-2 rounded-full shadow-lg transition-all duration-300"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                      </svg>
                    </button>
                  </div>
                </div>

                <!-- Image Generation Options -->
                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Prompt</label>
                    <textarea
                      v-model="imageOptions.prompt"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                      rows="3"
                      placeholder="Describe your NFT image..."
                    ></textarea>
                  </div>

                  <div class="grid grid-cols-2 gap-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Width</label>
                      <input
                        v-model="imageOptions.width"
                        type="number"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                      >
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Height</label>
                      <input
                        v-model="imageOptions.height"
                        type="number"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                      >
                    </div>
                  </div>

                  <div class="grid grid-cols-2 gap-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Steps</label>
                      <input
                        v-model="imageOptions.steps"
                        type="number"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                      >
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">CFG Scale</label>
                      <input
                        v-model="imageOptions.cfg"
                        type="number"
                        step="0.1"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
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
                    @click="regenerateImage"
                    :disabled="generating"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  >
                    {{ generating ? 'Regenerating...' : 'Regenerate Image' }}
                  </button>
                </div>
              </div>

              <!-- NFT Details -->
              <div class="space-y-4">
                <h2 class="text-xl font-semibold">NFT Details</h2>
                <div class="space-y-4">
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

                <!-- Submit Button -->
                <div class="flex justify-end">
                  <button
                    type="submit"
                    :disabled="submitting"
                    class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  >
                    {{ submitting ? 'Saving...' : 'Save Changes' }}
                  </button>
                </div>
              </div>
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

const props = defineProps({
  nft: {
    type: Object,
    required: true
  }
});

const generating = ref(false);
const submitting = ref(false);
const previewImage = ref(null);

const imageOptions = reactive({
  prompt: props.nft.prompt,
  width: props.nft.width || 512,
  height: props.nft.height || 512,
  steps: props.nft.steps || 30,
  cfg: props.nft.cfg || 7.5,
  negative_prompt: props.nft.negative_prompt || ''
});

const form = useForm({
  name: props.nft.name,
  description: props.nft.description,
  image: null
});

const regenerateImage = async () => {
  if (!imageOptions.prompt) {
    alert('Please enter a prompt');
    return;
  }

  generating.value = true;
  try {
    const imageBlob = await DezgoService.generateImage(imageOptions.prompt, {
      width: imageOptions.width,
      height: imageOptions.height,
      steps: imageOptions.steps,
      cfg: imageOptions.cfg,
      negative_prompt: imageOptions.negative_prompt
    });
    previewImage.value = URL.createObjectURL(imageBlob);
    form.image = imageBlob;
  } catch (error) {
    console.error('Error generating image:', error);
    alert('Failed to generate image. Please try again.');
  } finally {
    generating.value = false;
  }
};

const submitForm = async () => {
  submitting.value = true;
  try {
    await form.post(route('nft.update', props.nft.id), {
      preserveScroll: true,
      onSuccess: () => {
        window.location.href = route('nft.show', props.nft.id);
      }
    });
  } finally {
    submitting.value = false;
  }
};
</script> 