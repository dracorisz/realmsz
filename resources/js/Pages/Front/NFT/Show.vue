<template>
  <FrontLayout title="NFT Details">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-indigo-900 to-purple-900 py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <h1 class="text-4xl font-extrabold text-white sm:text-5xl md:text-6xl">
            <span class="block">{{ nft.name }}</span>
            <span class="block text-indigo-400">NFT Details</span>
          </h1>
          <p class="mt-3 max-w-md mx-auto text-base text-gray-300 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
            Explore the details of this unique digital asset and its creation process.
          </p>
        </div>
      </div>
    </div>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-6">
            <!-- NFT Image Section -->
            <div class="space-y-6">
              <div class="relative aspect-square rounded-xl overflow-hidden shadow-2xl group">
                <img 
                  :src="nft.image_url" 
                  :alt="nft.name"
                  class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500"
                />
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                  <div class="absolute bottom-4 left-4 right-4 flex justify-between items-center">
                    <div class="text-white">
                      <p class="font-semibold">{{ nft.name }}</p>
                      <p class="text-sm opacity-75">Created by {{ nft.creator.name }}</p>
                    </div>
                    <button 
                      v-if="canEdit"
                      @click="editNFT"
                      class="bg-white/90 hover:bg-white text-gray-800 p-2 rounded-full shadow-lg transition-all duration-300"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
              
              <!-- Image Actions -->
              <div class="flex justify-center space-x-4">
                <button 
                  @click="downloadImage"
                  class="flex items-center space-x-2 px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-300 transform hover:scale-105 hover:shadow-lg"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                  </svg>
                  <span>Download</span>
                </button>
                <button 
                  v-if="canEdit"
                  @click="regenerateImage"
                  class="flex items-center space-x-2 px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-300 transform hover:scale-105 hover:shadow-lg"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                  </svg>
                  <span>Regenerate</span>
                </button>
              </div>

              <!-- Generation Parameters -->
              <div class="bg-gray-50 rounded-xl p-6 space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                  <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                  Generation Parameters
                </h3>
                <div class="grid grid-cols-2 gap-4">
                  <div class="p-4 bg-white rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Dimensions</p>
                    <p class="font-medium">{{ nft.width }}x{{ nft.height }}px</p>
                  </div>
                  <div class="p-4 bg-white rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Steps</p>
                    <p class="font-medium">{{ nft.steps }}</p>
                  </div>
                  <div class="p-4 bg-white rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">CFG Scale</p>
                    <p class="font-medium">{{ nft.cfg }}</p>
                  </div>
                  <div class="p-4 bg-white rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Model</p>
                    <p class="font-medium">{{ nft.model || 'Stable Diffusion' }}</p>
                  </div>
                </div>
                <div class="p-4 bg-white rounded-lg shadow-sm">
                  <p class="text-sm text-gray-500">Prompt</p>
                  <p class="font-medium mt-1">{{ nft.prompt }}</p>
                </div>
                <div v-if="nft.negative_prompt" class="p-4 bg-white rounded-lg shadow-sm">
                  <p class="text-sm text-gray-500">Negative Prompt</p>
                  <p class="font-medium mt-1">{{ nft.negative_prompt }}</p>
                </div>
              </div>
            </div>

            <!-- NFT Details Section -->
            <div class="space-y-6">
              <div class="bg-gray-50 rounded-xl p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">About this NFT</h2>
                <p class="text-gray-600">{{ nft.description }}</p>
              </div>

              <div class="bg-gray-50 rounded-xl p-6 space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                  <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                  </svg>
                  Blockchain Details
                </h3>
                <div class="grid grid-cols-1 gap-4">
                  <div class="p-4 bg-white rounded-lg shadow-sm">
                    <div class="flex items-center justify-between">
                      <div>
                        <p class="text-sm text-gray-500">Created by</p>
                        <p class="font-medium">{{ nft.creator.name }}</p>
                      </div>
                      <div class="text-right">
                        <p class="text-sm text-gray-500">Created on</p>
                        <p class="font-medium">{{ formatDate(nft.created_at) }}</p>
                      </div>
                    </div>
                  </div>

                  <div class="p-4 bg-white rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Token ID</p>
                    <p class="font-medium">{{ nft.token_id }}</p>
                  </div>

                  <div class="p-4 bg-white rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Blockchain</p>
                    <div class="flex items-center mt-1">
                      <img 
                        :src="`${assetUrl}/images/chains/${nft.blockchain.toLowerCase()}-logo.svg`"
                        :alt="nft.blockchain"
                        class="h-6 w-6 mr-2"
                      />
                      <p class="font-medium">{{ nft.blockchain }}</p>
                    </div>
                  </div>

                  <div class="p-4 bg-white rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Contract Address</p>
                    <div class="flex items-center justify-between mt-1">
                      <p class="font-medium break-all">{{ nft.contract_address }}</p>
                      <button
                        @click="copyContractAddress"
                        class="ml-2 text-indigo-600 hover:text-indigo-800"
                      >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Actions -->
              <div class="flex space-x-4">
                <button 
                  v-if="canEdit"
                  @click="editNFT"
                  class="flex-1 py-3 px-6 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 transform hover:scale-105"
                >
                  Edit NFT
                </button>
                <button 
                  v-if="!canEdit"
                  @click="purchaseNFT"
                  class="flex-1 py-3 px-6 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-300 transform hover:scale-105"
                >
                  Purchase NFT
                </button>
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
import { useForm } from '@inertiajs/vue3';
import FrontLayout from '@/Layouts/FrontLayout.vue';
import DezgoService from '@/Services/DezgoService';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

const assetUrl = import.meta.env.VITE_ASSET_URL;

const props = defineProps({
  nft: {
    type: Object,
    required: true
  },
  canEdit: {
    type: Boolean,
    default: false
  }
});

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

const downloadImage = () => {
  const link = document.createElement('a');
  link.href = props.nft.image_url;
  link.download = `${props.nft.name}.png`;
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
};

const regenerateImage = async () => {
  if (!confirm('Are you sure you want to regenerate this NFT image? This action cannot be undone.')) {
    return;
  }

  try {
    const imageBlob = await DezgoService.generateImage(props.nft.prompt, {
      width: props.nft.width,
      height: props.nft.height,
      steps: props.nft.steps,
      cfg: props.nft.cfg,
      negative_prompt: props.nft.negative_prompt
    });

    const form = useForm({
      image: imageBlob
    });

    await form.post(route('nft.update', props.nft.id), {
      preserveScroll: true,
      onSuccess: () => {
        // Refresh the page to show the new image
        window.location.reload();
      }
    });
  } catch (error) {
    console.error('Error regenerating image:', error);
    alert('Failed to regenerate image. Please try again.');
  }
};

const copyContractAddress = () => {
  navigator.clipboard.writeText(props.nft.contract_address);
  // Show a toast notification
  alert('Contract address copied to clipboard!');
};

const editNFT = () => {
  window.location.href = route('nft.edit', props.nft.id);
};

const purchaseNFT = () => {
  // Implement purchase logic
  alert('Purchase functionality coming soon!');
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
  gsap.from(".nft-image", {
    scrollTrigger: {
      trigger: ".nft-image",
      start: "top center+=100",
      toggleActions: "play none none reverse"
    },
    duration: 0.8,
    y: 50,
    opacity: 0,
    ease: "power2.out"
  });

  gsap.from(".nft-details", {
    scrollTrigger: {
      trigger: ".nft-details",
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

.nft-image img {
  transition: transform 0.5s ease;
}

.nft-image:hover img {
  transform: scale(1.05);
}

@keyframes pulse {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.05);
  }
  100% {
    transform: scale(1);
  }
}

.animate-pulse {
  animation: pulse 2s infinite;
}
</style> 