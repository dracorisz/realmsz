<script setup>
import { ref } from "vue";
import axios from "axios";
import ImageService from "@/Utils/ImageService";
import { useToast } from "vue-toastification";
import InputLabel from "@/Components/InputLabel.vue";
import Loader from './Loader.vue';
import { useTemplates } from "@/Utils/useTemplates";

const toast = useToast();
const imageService = ImageService;

const props = defineProps({
  apiKey: {
    type: String,
    default: "DEZGO-C435BFF100D9194A967B1C199F2F5BACE688F4A861EBE2C8A9892536C1835AEDD5107C3B",
  },
  apiUrl: {
    type: String,
    default: "https://api.dezgo.com",
  },
});

const emit = defineEmits(["image-generated"]);

const generationState = ref({
  loading: false,
  downloading: false,
  error: null,
  result: null,
  prompt: "",
  negativePrompt: "blurry, low quality, distorted, ugly, deformed, bad anatomy, duplicate, cropped, extra limbs, poorly drawn hands, poorly drawn feet, poorly drawn face, out of frame, extra limbs, disfigured, deformed, cut off, distorted face",
  width: 1024,
  height: 1024,
  style: "realistic",
  model: "flux",
  guidance_scale: 7.5,
  steps: 30,
  seed: null,
  selectedTemplate: null,
});

const { templates } = useTemplates();

const selectTemplate = (template) => {
  if (generationState.value.loading) return;
  
  generationState.value = {
    ...generationState.value,
    prompt: template.prompt,
    style: template.style,
    width: template.width,
    height: template.height,
    selectedTemplate: template.name,
    error: null
  };
};

// Expose methods to parent component
defineExpose({
  selectTemplate,
  templates
});

const uploadToS3 = async (imageUrl) => {
  try {
    console.log('Attempting to fetch image from:', imageUrl);
    const response = await fetch(imageUrl);
    
    if (!response.ok) {
      throw new Error(`Failed to fetch image: ${response.statusText}`);
    }
    
    const blob = await response.blob();
    const timestamp = Math.floor(Date.now() / 1000);
    const filename = `gen_${timestamp}.png`;
    
    const file = new File([blob], filename, { type: "image/png" });
    const formData = new FormData();
    formData.append("image", file);
    formData.append("title", `AI Generated - ${timestamp}`);
    formData.append("type", "generated");
    
    console.log('Uploading to S3:', { filename, type: file.type });
    
    const uploadResponse = await axios.post(route("images.upload"), formData, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
      withCredentials: false,
    });

    console.log("S3 upload response:", uploadResponse.data);

    if (!uploadResponse.data.success) {
      throw new Error(uploadResponse.data.message || "Upload failed");
    }

    return uploadResponse.data;
  } catch (error) {
    console.error("S3 upload error:", error);
    throw error;
  }
};

const generateImage = async () => {
  if (!generationState.value.prompt) {
    generationState.value.error = "Please enter a prompt";
    toast.error("Please enter a prompt");
    return;
  }

  generationState.value.loading = true;
  generationState.value.error = null;
  generationState.value.result = null;

  try {
    console.log('Starting image generation with prompt:', generationState.value.prompt);
    
    const response = await imageService.generateImage(generationState.value.prompt, {
      style: generationState.value.style.toLowerCase(),
      negative_prompt: generationState.value.negativePrompt,
      model: "flux",
      width: generationState.value.width,
      height: generationState.value.height,
    });

    console.log("Generation response:", response);

    if (!response.success || !response.image) {
      throw new Error(response.message || "Failed to generate image");
    }

    try {
      // Upload to S3
      const s3Response = await uploadToS3(response.image);
      console.log("S3 upload completed:", s3Response);

      if (!s3Response.success || !s3Response.image_url) {
        throw new Error("Failed to get image URL from upload");
      }

      // Update the UI with the new image
      generationState.value.result = s3Response.image_url;
      emit("image-generated", s3Response.image_url);
      toast.success("Image generated and uploaded successfully!");
    } catch (uploadError) {
      console.error("Upload error:", uploadError);
      throw new Error("Failed to upload generated image: " + uploadError.message);
    }
  } catch (error) {
    console.error("Generation error:", error);
    const errorMessage = error.message || "An error occurred during image generation";
    generationState.value.error = errorMessage;
    toast.error(errorMessage);
  } finally {
    generationState.value.loading = false;
  }
};

const download = async () => {
  try {
    if (!generationState.value.result) {
      toast.error("No image to download");
      return;
    }

    const link = document.createElement("a");
    generationState.value.downloading = true;
    link.href = generationState.value.result;
    link.download = `realmsz_gen_${Date.now()}.png`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    toast.success("Download started!");
  } catch (error) {
    toast.error("Failed to download image");
  } finally {
    generationState.value.downloading = false;
  }
};

const copyToClipboard = (text) => {
  const input = document.createElement("input");
  input.value = text;
  document.body.appendChild(input);
  input.select();
  document.execCommand("copy");
  document.body.removeChild(input);
  toast.success("URL copied to clipboard");
};
</script>

<template>
  <div>
    <!-- Generation Form -->
    <div class="grid h-full flex-1 grid-cols-1 items-start gap-5 lg:grid-cols-2">
      <div class="space-y-5">
        <div class="form-group">
          <InputLabel>Custom Prompt</InputLabel>
          <textarea v-model="generationState.prompt" class="w-full rounded-lg border border-dragon-dark-600 p-3 text-white" rows="4" placeholder="Describe the image you want to generate..." :disabled="generationState.loading"></textarea>
        </div>
        <div class="form-group">
          <InputLabel>Negative Prompt</InputLabel>
          <textarea v-model="generationState.negativePrompt" class="w-full rounded-lg border border-dragon-dark-600 p-3 text-white" rows="4" placeholder="Describe what you don't want in the image..." :disabled="generationState.loading"></textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div class="form-group">
            <InputLabel>Width</InputLabel>
            <input v-model="generationState.width" type="number" class="w-full rounded-lg border border-dragon-dark-600 p-3 text-white" :disabled="generationState.loading" min="256" max="2048" step="64" />
          </div>
          <div class="form-group">
            <InputLabel>Height</InputLabel>
            <input v-model="generationState.height" type="number" class="w-full rounded-lg border border-dragon-dark-600 p-3 text-white" :disabled="generationState.loading" min="256" max="2048" step="64" />
          </div>
        </div>
        <!-- <div class="grid grid-cols-2 gap-4">
          <div class="form-group">
            <InputLabel>Guidance Scale</InputLabel>
            <input v-model="generationState.guidance_scale" type="number" class="w-full rounded-lg border border-dragon-dark-600 p-3 text-white" :disabled="generationState.loading" min="1" max="20" step="0.5" />
          </div>
          <div class="form-group">
            <InputLabel>Steps</InputLabel>
            <input v-model="generationState.steps" type="number" class="w-full rounded-lg border border-dragon-dark-600 p-3 text-white" :disabled="generationState.loading" min="10" max="50" step="1" />
          </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div class="form-group">
            <InputLabel>Model</InputLabel>
            <select v-model="generationState.model" class="w-full rounded-lg border border-dragon-dark-600 p-3 text-white" :disabled="generationState.loading">
              <option value="flux">Flux</option>
              <option value="stable-diffusion-v1-5">Stable Diffusion 1.5</option>
              <option value="stable-diffusion-xl">Stable Diffusion XL</option>
            
              <option value="0">Flux</option>
              <option value="1">Stable Diffusion 1.5</option>
              <option value="2">Stable Diffusion XL</option>
            </select>
          </div>
          <div class="form-group">
            <InputLabel>Style</InputLabel>
            <select v-model="generationState.style" class="w-full rounded-lg border border-dragon-dark-600 p-3 text-white" :disabled="generationState.loading">
              <option value="realistic">Realistic</option>
              <option value="anime">Anime</option>
              <option value="fantasy">Fantasy</option>
              <option value="cyberpunk">Cyberpunk</option>
              <option value="landscape">Landscape</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label>Seed (Optional)</label>
          <input v-model="generationState.seed" type="number" class="w-full rounded-lg border border-dragon-dark-600 p-3 text-white" :disabled="generationState.loading" placeholder="Leave empty for random" />
        </div> -->
        <button @click="generateImage" :disabled="generationState.loading" class="btn-dragon relative w-full overflow-hidden flex items-center justify-center gap-2">
          <span>{{ generationState.loading ? "" : "Generate Image" }}</span>
          <span v-if="generationState.loading" class="p-5 absolute inset-0 flex items-center justify-center">
            <Loader />
          </span>
        </button>
        <!-- Result Display -->
        <div v-if="generationState.result" class="flex flex-col space-y-5 mt-5">
          <InputLabel>Your image is saved in gallery! ✨</InputLabel>
          <div class="flex flex-col gap-5">
            <div class="relative h-[256px] w-full">
              <img :src="generationState.result" alt="Generated Image" class="mx-auto w-full rounded-lg object-cover shadow-2xl ah-[256px]" />
              <div class="absolute inset-0 rounded-lg bg-gradient-to-t from-dragon-dark-900/80 to-transparent"></div>
            </div>
            <div class="flex flex-col">
              <div class="flex flex-col">
                <div class="flex items-center justify-center gap-3">
                  <button @click="download" :disabled="generationState.downloading" class="border-white flex w-full justify-center rounded-md border px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-dragon-dark-600 focus:outline-none focus:ring-2 focus:ring-dragon-primary-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                    <span v-if="generationState.downloading" class="flex items-center">
                      <div class="mr-2 h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent"></div>
                      Downloading...
                    </span>
                    <span v-else>Download</span>
                  </button>
                  <button @click="generationState.result = null" class="border-white flex w-full justify-center rounded-md border px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-dragon-dark-600 focus:outline-none focus:ring-2 focus:ring-dragon-primary-500 focus:ring-offset-2">Clear Result</button>
                  <button @click="copyToClipboard(generationState.result)" class="border-white rounded-lg w-full px-4 py-2 border text-sm text-white hover:bg-dragon-dark-600">Copy URL</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Error Display -->
        <div v-if="generationState.error" class="mt-4 rounded-lg border border-red-500 bg-red-500/10 p-4">
          <p class="text-red-500">{{ generationState.error }}</p>
        </div>
      </div>

      <!-- Templates Grid -->
      <div>
        <div class="grid grid-cols-2 gap-5">
          <div v-for="template in templates" :key="template.name" 
            @click="selectTemplate(template)"
            class="template-card cursor-pointer rounded-lg p-4" 
            :class="{ 
              'cursor-not-allowed opacity-50': generationState.loading,
              'border-dragon-primary': generationState.selectedTemplate === template.name,
              'border-dragon-dark-600': generationState.selectedTemplate !== template.name
            }"
          >
            <img :src="template.image" :alt="template.name" class="mb-2 aspect-square w-full rounded-lg object-cover" />
            <h4 class="font-semibold text-sm text-white">{{ template.name }}</h4>
            <p class="text-sm capitalize text-gray-400">{{ template.style }}</p>
            <p class="mt-3 text-[10px] text-gray-400">{{ template.prompt.slice(0, 64) }}...</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.btn-dragon {
  @apply ah-[43px] rounded-lg bg-gradient-to-r from-teal-500 to-blue-600 px-6 py-3 font-semibold text-white shadow-xl transition-all duration-300 hover:shadow-teal-500/25;
}

.template-card {
  @apply border bg-dragon-dark-700/30 p-3 text-xs backdrop-blur-sm transition-all duration-300 hover:border-teal-500/50;
}

.template-card.border-dragon-primary {
  @apply border-2;
}

.form-group {
  @apply space-y-2;
}

.form-group input,
.form-group textarea {
  @apply transition-all duration-300 focus:border-teal-500 focus:ring-1 focus:ring-teal-500;
}

.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
</style>
