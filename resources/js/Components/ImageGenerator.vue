<script setup>
import { ref } from 'vue';
import axios from 'axios';
import PrimaryButton from './PrimaryButton.vue';
import InputLabel from './InputLabel.vue';
import TextInput from './TextInput.vue';
import Loader from './Loader.vue';
import { useToast } from 'vue-toastification';

const props = defineProps({
  item: {
    type: Object,
    required: true
  }
});

const emit = defineEmits(['imageGenerated', 'close']);

const toast = useToast();
const isGenerating = ref(false);
const isUploading = ref(false);
const prompt = ref('');
const generatedImage = ref(null);

const generateImage = async () => {
  if (!prompt.value.trim()) {
    toast.error('Please enter a description for the image');
    return;
  }

  isGenerating.value = true;
  try {
    const response = await axios.post(route('images.generate'), {
      prompt: prompt.value,
      item_id: props.item.id
    });

    if (response.data.success) {
      generatedImage.value = response.data.image_url;
      emit('imageGenerated', response.data.image_url);
      toast.success('Image generated successfully');
      prompt.value = '';
    } else {
      toast.error(response.data.message || 'Failed to generate image');
    }
  } catch (error) {
    console.error('Error generating image:', error);
    const errorMessage = error.response?.data?.message || 'Error generating image';
    toast.error(errorMessage);
  } finally {
    isGenerating.value = false;
  }
};

const uploadImage = async (file) => {
  if (!file) return;
  
  // Validate file type
  const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
  if (!validTypes.includes(file.type)) {
    toast.error('Please upload a valid image file (JPEG, PNG, GIF, or WebP)');
    return;
  }

  // Validate file size (10MB)
  if (file.size > 10 * 1024 * 1024) {
    toast.error('File size must be less than 10MB');
    return;
  }

  isUploading.value = true;
  const formData = new FormData();
  formData.append('image', file);
  formData.append('item_id', props.item.id);

  try {
    const response = await axios.post(route('images.upload'), formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      },
      onUploadProgress: (progressEvent) => {
        const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
        console.log(`Upload progress: ${percentCompleted}%`);
      }
    });

    if (response.data.success) {
      generatedImage.value = response.data.image_url;
      emit('imageGenerated', response.data.image_url);
      toast.success('Image uploaded successfully');
    } else {
      toast.error(response.data.message || 'Failed to upload image');
    }
  } catch (error) {
    console.error('Error uploading image:', error);
    const errorMessage = error.response?.data?.message || 'Error uploading image';
    toast.error(errorMessage);
  } finally {
    isUploading.value = false;
  }
};

const handleFileUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    uploadImage(file);
    event.target.value = ''; // Clear the input
  }
};
</script>

<template>
  <div class="space-y-4">
    <div class="flex items-center gap-4">
      <div class="flex-1">
        <InputLabel for="prompt" value="Image Description" />
        <div class="relative">
          <TextInput
            id="prompt"
            v-model="prompt"
            type="text"
            class="mt-1 block w-full"
            placeholder="Describe the image you want to generate..."
            :disabled="isGenerating || isUploading"
          />
          <Loader v-if="isGenerating" class="absolute right-2 top-1/2 -translate-y-1/2" />
        </div>
      </div>
      <PrimaryButton
        @click="generateImage"
        :disabled="isGenerating || isUploading || !prompt.trim()"
        class="mt-6"
      >
        <template v-if="isGenerating">
          <Loader class="mr-2" />
        </template>
        <span>{{ isGenerating ? 'Generating...' : 'Generate' }}</span>
      </PrimaryButton>
    </div>

    <div class="relative">
      <InputLabel value="Or Upload Image" />
      <div class="relative">
        <input
          type="file"
          accept="image/*"
          @change="handleFileUpload"
          :disabled="isGenerating || isUploading"
          class="mt-1 block w-full text-sm text-gray-500
            file:mr-4 file:py-2 file:px-4
            file:rounded-full file:border-0
            file:text-sm file:font-semibold
            file:bg-violet-50 file:text-violet-700
            hover:file:bg-violet-100
            disabled:opacity-50 disabled:cursor-not-allowed"
        />
        <Loader v-if="isUploading" class="absolute right-2 top-1/2 -translate-y-1/2" />
      </div>
    </div>

    <div v-if="generatedImage" class="mt-4">
      <img 
        :src="generatedImage" 
        alt="Generated image" 
        class="max-w-full rounded-lg shadow-lg"
      />
    </div>
  </div>
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
</style> 