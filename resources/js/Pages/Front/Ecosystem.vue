<script setup>
import { Link } from '@inertiajs/vue3';
import FrontLayout from '@/Layouts/FrontLayout.vue';
import { onMounted, ref, computed } from 'vue';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import axios from 'axios';
import DezgoImageGenerator from '@/Components/DezgoImageGenerator.vue';

// Static data for modules
const modules = [
  {
    name: 'NFT Marketplace',
    description: 'Create, buy, and sell NFTs with our advanced marketplace',
    icon: '🎨',
    features: ['Batch Minting', 'Royalty Management', 'Cross-chain Support', 'Auction System', 'Dezgo AI Integration'],
    image: 'https://dracoscopia.com/images/ai/8.png'
  },
  {
    name: 'Gaming Platform',
    description: 'Play and create games in our immersive gaming environment',
    icon: '🎮',
    features: ['Play-to-Earn', 'Game Development', 'Asset Trading', 'Tournaments'],
    image: 'https://dracoscopia.com/images/ai/2.png'
  },
  {
    name: 'Social Hub',
    description: 'Connect with creators and gamers in our social network',
    icon: '🤝',
    features: ['Community Forums', 'Creator Profiles', 'Collaboration Tools', 'Event Calendar'],
    image: 'https://dracoscopia.com/images/ai/3.png'
  },
  {
    name: 'AI Studio',
    description: 'Generate art and content with our AI-powered tools',
    icon: '🤖',
    features: [
      'Stable Diffusion XL',
      'Flux AI Models',
      'Lightning Fast Generation',
      'Custom LoRA Support',
      'Batch Generation',
      'Image-to-Image',
      'Inpainting',
      'Background Removal'
    ],
    image: 'https://dracoscopia.com/images/ai/4.png'
  },
  {
    name: 'DeFi Integration',
    description: 'Access decentralized finance features and services',
    icon: '💰',
    features: ['Liquidity Pools', 'Staking', 'Yield Farming', 'Cross-chain Swaps'],
    image: 'https://dracoscopia.com/images/ai/5.png'
  },
  {
    name: 'Content Hub',
    description: 'Share and monetize your creative content',
    icon: '🎬',
    features: ['Content Management', 'Monetization Tools', 'Analytics', 'Distribution'],
    image: 'https://dracoscopia.com/images/ai/7.png'
  }
];

// Static data for integrations
const integrations = [
  {
    name: 'Ethereum',
    logo: 'https://dracoscopia.com/images/chains/ethereum-eth-logo.svg',
    description: 'Leading smart contract platform'
  },
  {
    name: 'Polygon',
    logo: 'https://dracoscopia.com/images/chains/polygon-matic-logo.svg',
    description: 'Scalable Ethereum scaling solution'
  },
  {
    name: 'Optimism',
    logo: 'https://dracoscopia.com/images/chains/optimism-op-logo.svg',
    description: 'Ethereum Layer 2 scaling'
  },
  {
    name: 'Solana',
    logo: 'https://dracoscopia.com/images/chains/solana-sol-logo.svg',
    description: 'High-performance blockchain'
  }
];

// Add Dezgo API features
const dezgoFeatures = [
  {
    name: 'Text-to-Image',
    description: 'Create stunning images from text descriptions using Stable Diffusion XL',
    icon: '✍️',
    endpoint: '/text2image_sdxl'
  },
  {
    name: 'Flux Generation',
    description: 'High-quality image generation with optimized performance',
    icon: '⚡',
    endpoint: '/text2image_flux'
  },
  {
    name: 'Lightning Fast',
    description: 'Ultra-fast image generation with minimal quality loss',
    icon: '⚡',
    endpoint: '/text2image_sdxl_lightning'
  },
  {
    name: 'Image Editing',
    description: 'Edit and enhance existing images with AI',
    icon: '🎨',
    endpoint: '/edit-image'
  },
  {
    name: 'Background Removal',
    description: 'Automatically remove backgrounds from images',
    icon: '✂️',
    endpoint: '/remove-background'
  },
  {
    name: 'Upscaling',
    description: 'Increase image resolution while maintaining quality',
    icon: '🔍',
    endpoint: '/upscaling'
  }
];

// Add Dezgo API configuration
const dezgoConfig = {
  apiUrl: 'https://api.dezgo.com',
  apiKey: 'DEZGO-C435BFF100D9194A967B1C199F2F5BACE688F4A861EBE2C8A9892536C1835AEDD5107C3B',
  endpoints: {
    text2image: '/text2image_sdxl',
    image2image: '/image2image_sdxl',
    inpainting: '/inpainting_sdxl',
    upscaling: '/upscaling_sdxl'
  }
};

// Add AI generation templates
const aiTemplates = [
  {
    name: 'Fantasy Art',
    prompt: 'A majestic dragon soaring through a mystical realm, surrounded by floating islands and ancient ruins, digital art, highly detailed, epic lighting, 8k resolution',
    negativePrompt: 'blurry, low quality, distorted, ugly, deformed',
    style: 'fantasy',
    image: 'https://dracoscopia.com/images/ai/7.png'
  },
  {
    name: 'Cyberpunk City',
    prompt: 'A futuristic neon-lit cityscape with flying cars and holographic advertisements, cyberpunk style, rain-slicked streets, 8k resolution',
    negativePrompt: 'blurry, low quality, distorted, ugly, deformed',
    style: 'cyberpunk',
    image: 'https://dracoscopia.com/tt.png'
  },
  {
    name: 'Abstract Art',
    prompt: 'Vibrant abstract composition with flowing colors and geometric shapes, digital art, high contrast, 8k resolution',
    negativePrompt: 'blurry, low quality, distorted, ugly, deformed',
    style: 'abstract',
    image: 'https://dracoscopia.com/images/ai/3.png'
  },
  {
    name: 'Portrait',
    prompt: 'A detailed portrait of a mystical character with glowing eyes and intricate jewelry, digital art, highly detailed, 8k resolution',
    negativePrompt: 'blurry, low quality, distorted, ugly, deformed',
    style: 'portrait',
    image: 'https://dracoscopia.com/images/ai/4.png'
  },
  {
    name: 'Landscape',
    prompt: 'A breathtaking fantasy landscape with floating mountains and cascading waterfalls, digital art, highly detailed, epic lighting, 8k resolution',
    negativePrompt: 'blurry, low quality, distorted, ugly, deformed',
    style: 'landscape',
    image: 'https://dracoscopia.com/images/ai/8.png'
  }
];

// Add AI generation state
const generationState = ref({
  loading: false,
  error: null,
  result: null,
  prompt: '',
  width: 1024,
  height: 1024,
  style: 'realistic',
  selectedTemplate: null
});

// Add AI generation methods
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
      `${dezgoConfig.apiUrl}${dezgoConfig.endpoints.text2image}`,
      {
        prompt: generationState.value.prompt,
        width: generationState.value.width,
        height: generationState.value.height,
        style: generationState.value.style,
        negative_prompt: 'blurry, low quality, distorted, deformed',
        num_images: 1,
        guidance_scale: 7.5,
        steps: 30
      },
      {
        headers: {
          'Content-Type': 'application/json',
          'X-Dezgo-Key': dezgoConfig.apiKey,
          'Access-Control-Allow-Origin': '*',
          'Access-Control-Allow-Methods': 'POST, GET, OPTIONS',
          'Access-Control-Allow-Headers': 'Content-Type, X-Dezgo-Key'
        },
        withCredentials: false,
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

gsap.registerPlugin(ScrollTrigger);

onMounted(() => {
  // Simple fade-in animation for hero section
  const hero = document.querySelector('.hero-section');
  if (hero) {
    gsap.from(hero, {
      opacity: 0,
      duration: 1,
      ease: 'power2.out'
    });
  }

  // Simple stagger animation for modules
  // const moduleCards = gsap.utils.toArray('.module-card');
  // if (moduleCards.length) {
  //   gsap.from(moduleCards, {
  //     y: 20,
  //     opacity: 0,
  //     duration: 0.5,
  //     stagger: 0.1,
  //     ease: 'power2.out',
  //     scrollTrigger: {
  //       trigger: '.modules-section',
  //       start: 'top bottom-=100',
  //       toggleActions: 'play none none reverse'
  //     }
  //   });
  // }
});
</script>

<template>
  <FrontLayout>
    <div class="min-h-screen bg-gradient-to-b from-dragon-dark-900 to-dragon-dark-800">
      <!-- Hero Section with Background -->
      <section class="hero-section relative py-20 px-4 sm:px-6 lg:px-8">
          <div class="absolute inset-0 overflow-hidden">
          <div class="absolute inset-0 z-0">
            <img src="https://dracoscopia.com/images/backgrounds/background01.jpg" alt="Hero Background" class="w-full h-full object-cover opacity-20" />
          </div>
          <div class="absolute inset-0 bg-gradient-to-b from-dragon-dark-900/80 to-dragon-dark-800/50"></div>
        </div>
        <div class="relative max-w-7xl mx-auto">
            <div class="text-center">
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold bg-gradient-to-r from-teal-400 via-blue-500 to-purple-600 bg-clip-text text-transparent">
              Our Ecosystem
              </h1>
            <p class="mt-6 text-xl sm:text-2xl text-blue-400 max-w-3xl mx-auto">
              A comprehensive platform for digital art, gaming, and blockchain innovation
            </p>
            <div class="mt-8 flex justify-center gap-4">
              <Link
                :href="route('register')"
                class="btn-dragon"
              >
                Get Started
              </Link>
              <Link
                :href="route('contact')"
                class="btn-dragon-outline"
              >
                Contact Us
              </Link>
            </div>
          </div>
        </div>
      </section>

        <!-- Modules Section -->
      <section class="modules-section py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
          <h2 class="text-3xl font-bold text-white mb-4 text-center">Core Modules</h2>
          <p class="text-xl text-gray-400 mb-12 text-center max-w-3xl mx-auto">
            Discover our comprehensive suite of tools and features designed to empower creators and users
          </p>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div v-for="(module, index) in modules" :key="index" class="module-card p-6 rounded-xl">
              <div class="relative h-48 mb-4 rounded-lg overflow-hidden">
                <img :src="module.image" :alt="module.name" class="w-full h-full object-cover" />
                <div class="absolute inset-0 bg-gradient-to-t from-dragon-dark-900/80 to-transparent"></div>
              </div>
              <div class="text-4xl mb-4">{{ module.icon }}</div>
              <h3 class="text-2xl font-semibold text-white mb-2">{{ module.name }}</h3>
              <p class="text-gray-400 mb-4">{{ module.description }}</p>
              <div class="mt-4">
                <h4 class="text-sm font-semibold text-teal-400 mb-2">Key Features:</h4>
                <ul class="space-y-1">
                  <li v-for="(feature, fIndex) in module.features" :key="fIndex" class="text-sm text-gray-400 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>
                    {{ feature }}
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Integrations Section -->
      <section class="py-20 px-4 sm:px-6 lg:px-8 bg-dragon-dark-800/50">
        <div class="max-w-7xl mx-auto">
          <h2 class="text-3xl font-bold text-white mb-4 text-center">Blockchain Integrations</h2>
          <p class="text-xl text-gray-400 mb-12 text-center max-w-3xl mx-auto">
            Seamlessly connect with leading blockchain networks
          </p>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div v-for="(integration, index) in integrations" :key="index" class="integration-card flex flex-col items-center justify-center p-6 rounded-xl">
              <img :src="integration.logo" :alt="integration.name" class="h-12 opacity-70 hover:opacity-100 transition-opacity duration-300" />
              <span class="text-white/50 font-black mt-3 hover:text-white transition-colors duration-300 text-xs tracking-widest uppercase">{{ integration.name }}</span>
              <p class="text-gray-500 text-sm mt-2 text-center">{{ integration.description }}</p>
            </div>
          </div>
        </div>
      </section>

      <!-- AI Studio Section -->
      <section class="py-16">
        <div class="container mx-auto px-4">
          <h2 class="text-3xl font-bold text-white mb-8">AI Studio</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-6">
              <p class="text-gray-300">
                Our AI Studio powered by Dezgo API offers state-of-the-art image generation capabilities.
                Create unique artwork, NFTs, and digital assets with our advanced AI models.
              </p>
              <ul class="space-y-2 text-gray-300">
                <li class="flex items-center">
                  <svg class="w-5 h-5 text-dragon-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                  Text-to-Image Generation
                </li>
                <li class="flex items-center">
                  <svg class="w-5 h-5 text-dragon-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                  Multiple Style Options
                </li>
                <li class="flex items-center">
                  <svg class="w-5 h-5 text-dragon-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                  Custom Resolution Support
                </li>
                <li class="flex items-center">
                  <svg class="w-5 h-5 text-dragon-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                  Direct NFT Integration
                </li>
              </ul>
            </div>
            <DezgoImageGenerator />
          </div>
        </div>
      </section>

      <!-- NFT Creation Section -->
      <section class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
          <h2 class="text-3xl font-bold text-white mb-4 text-center">NFT Creation</h2>
          <p class="text-xl text-gray-400 mb-12 text-center max-w-3xl mx-auto">
            Create unique NFTs with our integrated AI tools and blockchain technology
          </p>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
              <div class="feature-item flex items-start">
                <div class="text-2xl mr-4">🎨</div>
                <div>
                  <h3 class="text-xl font-semibold text-white mb-2">AI-Powered Generation</h3>
                  <p class="text-gray-400">Generate unique artwork using Stable Diffusion XL and Flux models</p>
                </div>
              </div>
              <div class="feature-item flex items-start">
                <div class="text-2xl mr-4">⚡</div>
                <div>
                  <h3 class="text-xl font-semibold text-white mb-2">Batch Processing</h3>
                  <p class="text-gray-400">Create multiple NFTs simultaneously with consistent style</p>
                </div>
              </div>
              <div class="feature-item flex items-start">
                <div class="text-2xl mr-4">🔗</div>
                <div>
                  <h3 class="text-xl font-semibold text-white mb-2">Blockchain Integration</h3>
                  <p class="text-gray-400">Mint directly to multiple blockchains with one click</p>
                </div>
              </div>
            </div>
            <div class="relative">
              <img src="https://dracoscopia.com/new.png" alt="AI NFT Generation" class="rounded-xl shadow-2xl" />
              <div class="absolute inset-0 bg-gradient-to-t from-dragon-dark-900/80 to-transparent rounded-xl"></div>
            </div>
          </div>
        </div>
      </section>

      <!-- AI Generation Section -->
      <section class="py-20 px-4 sm:px-6 lg:px-8 bg-dragon-dark-800/50">
        <div class="max-w-7xl mx-auto">
          <h2 class="text-3xl font-bold text-white mb-4 text-center">AI Image Generation</h2>
          <p class="text-xl text-gray-400 mb-12 text-center max-w-3xl mx-auto">
            Create stunning artwork with our AI-powered generation tools
          </p>

          <!-- Generation Form -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div class="space-y-6">
              <div class="form-group">
                <label class="block text-white mb-2">Custom Prompt</label>
                <textarea
                  v-model="generationState.prompt"
                  class="w-full p-3 bg-dragon-dark-700 border border-dragon-dark-600 rounded-lg text-white"
                  rows="4"
                  placeholder="Describe the image you want to generate..."
                ></textarea>
              </div>
              <div class="form-group">
                <label class="block text-white mb-2">Negative Prompt</label>
                <textarea
                  v-model="generationState.customNegativePrompt"
                  class="w-full p-3 bg-dragon-dark-700 border border-dragon-dark-600 rounded-lg text-white"
                  rows="2"
                  placeholder="Describe what you don't want in the image..."
                ></textarea>
              </div>
              <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                  <label class="block text-white mb-2">Width</label>
                  <input
                    v-model="generationState.width"
                    type="number"
                    class="w-full p-3 bg-dragon-dark-700 border border-dragon-dark-600 rounded-lg text-white"
                  />
                </div>
                <div class="form-group">
                  <label class="block text-white mb-2">Height</label>
                  <input
                    v-model="generationState.height"
                    type="number"
                    class="w-full p-3 bg-dragon-dark-700 border border-dragon-dark-600 rounded-lg text-white"
                  />
                </div>
              </div>
              <div>
                <label for="style" class="block text-sm font-medium text-gray-300">Style</label>
                <select
                  id="style"
                  v-model="generationState.style"
                  class="mt-1 block w-full rounded-md bg-dragon-dark-700 border-dragon-dark-600 text-white shadow-sm focus:border-dragon-primary-500 focus:ring-dragon-primary-500 sm:text-sm"
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
              <h3 class="text-xl font-semibold text-white mb-4">Quick Templates</h3>
              <div class="grid grid-cols-2 gap-4">
                <div
                  v-for="template in aiTemplates"
                  :key="template.name"
                  class="template-card p-4 rounded-lg cursor-pointer"
                  @click="generationState.prompt = template.prompt"
                >
                  <img :src="template.image" :alt="template.name" class="w-full h-32 object-cover rounded-lg mb-2" />
                  <h4 class="text-white font-semibold">{{ template.name }}</h4>
                  <p class="text-gray-400 text-sm">{{ template.style }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Result Display -->
          <div v-if="generationState.result" class="mt-12">
            <h3 class="text-xl font-semibold text-white mb-4">Generated Image</h3>
            <div class="relative">
              <img :src="generationState.result" alt="Generated Image" class="w-full max-w-2xl mx-auto rounded-lg shadow-2xl" />
              <div class="absolute inset-0 bg-gradient-to-t from-dragon-dark-900/80 to-transparent rounded-lg"></div>
            </div>
            <button
              @click="generationState.result = null"
              class="mt-2 w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-dragon-dark-700 hover:bg-dragon-dark-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-dragon-primary-500"
            >
              Clear Result
            </button>
          </div>

          <!-- Error Display -->
          <div v-if="generationState.error" class="mt-4 p-4 bg-red-500/10 border border-red-500 rounded-lg">
            <p class="text-red-500">{{ generationState.error }}</p>
          </div>
            </div>
      </section>

      <!-- CTA Section -->
      <section class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
          <h2 class="text-3xl sm:text-4xl font-bold text-white mb-6">
            Ready to Join Our Ecosystem?
          </h2>
          <p class="text-xl text-gray-400 mb-10 max-w-2xl mx-auto">
            Start your journey with Realmsz today and unlock the full potential of digital creation and blockchain technology
          </p>
          <div class="flex justify-center gap-4">
            <Link
              :href="route('register')"
              class="btn-dragon"
            >
              Get Started
            </Link>
            <Link
              :href="route('contact')"
              class="btn-dragon-outline"
            >
              Contact Sales
            </Link>
          </div>
        </div>
      </section>
      </div>
  </FrontLayout>
</template>

<style scoped>
.btn-dragon {
  @apply px-6 py-3 bg-gradient-to-r from-teal-500 to-blue-600 text-white font-semibold 
         rounded-lg shadow-xl hover:shadow-teal-500/25 transition-all duration-300;
}

.btn-dragon-outline {
  @apply px-6 py-3 border-2 border-teal-500 text-teal-500 font-semibold 
         rounded-lg hover:bg-teal-500/10 transition-all duration-300;
}

.module-card {
  @apply bg-dragon-dark-700/50 border border-dragon-dark-600 backdrop-blur-sm
         hover:border-teal-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-teal-500/10;
}

.module-card:hover {
  @apply transform -translate-y-1;
}

.integration-card {
  @apply bg-dragon-dark-700/30 border border-dragon-dark-600 backdrop-blur-sm
         hover:border-teal-500/50 transition-all duration-300;
}

.integration-card:hover {
  @apply transform -translate-y-1;
}

.ai-feature-card {
  @apply bg-dragon-dark-700/50 border border-dragon-dark-600 backdrop-blur-sm
         hover:border-teal-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-teal-500/10;
}

.ai-feature-card:hover {
  @apply transform -translate-y-1;
}

.feature-item {
  @apply p-4 rounded-lg bg-dragon-dark-700/30 border border-dragon-dark-600
         hover:border-teal-500/50 transition-all duration-300;
}

.feature-item:hover {
  @apply transform -translate-y-1;
}

.template-card {
  @apply bg-dragon-dark-700/50 border border-dragon-dark-600 backdrop-blur-sm
         hover:border-teal-500/50 transition-all duration-300;
}

.template-card:hover {
  @apply transform -translate-y-1;
}

.form-group {
  @apply space-y-2;
}

.form-group input,
.form-group textarea {
  @apply focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-all duration-300;
}
</style>
