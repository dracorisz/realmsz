<script setup>
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import FrontLayout from '@/Layouts/FrontLayout.vue';
import { 
  SparklesIcon,
  PhotoIcon,
  DocumentTextIcon,
  CodeBracketIcon,
  MusicalNoteIcon,
  VideoCameraIcon
} from '@heroicons/vue/24/outline';

const tools = [
  {
    title: 'Text to Image',
    description: 'Generate stunning images from text descriptions',
    icon: PhotoIcon,
    endpoint: '/api/ai/text-to-image'
  },
  {
    title: 'Text to Code',
    description: 'Generate code from natural language descriptions',
    icon: CodeBracketIcon,
    endpoint: '/api/ai/text-to-code'
  },
  {
    title: 'Text to Music',
    description: 'Create unique music tracks from text prompts',
    icon: MusicalNoteIcon,
    endpoint: '/api/ai/text-to-music'
  },
  {
    title: 'Text to Video',
    description: 'Generate video content from text descriptions',
    icon: VideoCameraIcon,
    endpoint: '/api/ai/text-to-video'
  }
];

const prompt = ref('');
const loading = ref(false);
const result = ref(null);
const error = ref(null);

const generateImage = async () => {
  if (!prompt.value) {
    error.value = 'Please enter a prompt';
    return;
  }

  loading.value = true;
  error.value = null;

  try {
    const response = await fetch('/api/ai/text-to-image', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ prompt: prompt.value }),
    });

    if (!response.ok) {
      throw new Error('Failed to generate image');
    }

    const data = await response.json();
    result.value = data.imageUrl;
  } catch (err) {
    error.value = err.message;
  } finally {
    loading.value = false;
  }
};
</script>

<template>
  <FrontLayout>
    <div class="min-h-screen bg-gradient-to-b from-dragon-dark-900 to-dragon-dark-800">
      <!-- Hero Section -->
      <section class="relative py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto text-center">
          <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold bg-gradient-to-r from-teal-400 via-blue-500 to-purple-600 bg-clip-text text-transparent">
            AI Tools
          </h1>
          <p class="mt-6 text-xl sm:text-2xl text-blue-400 max-w-3xl mx-auto">
            Create amazing content with our AI-powered tools
          </p>
        </div>
      </section>

      <!-- Text to Image Section -->
      <section class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
          <h2 class="text-3xl font-bold text-white text-center mb-12">Text to Image</h2>
          
          <div class="max-w-2xl mx-auto">
            <div class="bg-dragon-dark-700/50 p-6 rounded-xl border border-dragon-dark-600">
              <div class="space-y-4">
                <div>
                  <label for="prompt" class="block text-sm font-medium text-gray-400 mb-2">
                    Enter your prompt
                  </label>
                  <textarea
                    id="prompt"
                    v-model="prompt"
                    rows="4"
                    class="w-full bg-dragon-dark-800 border border-dragon-dark-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-teal-500"
                    placeholder="Describe the image you want to generate..."
                  ></textarea>
                </div>
                
                <div v-if="error" class="text-red-500 text-sm">
                  {{ error }}
                </div>
                
                <button
                  @click="generateImage"
                  :disabled="loading"
                  class="btn-dragon w-full"
                >
                  <span v-if="loading">Generating...</span>
                  <span v-else>Generate Image</span>
                </button>
              </div>
            </div>
            
            <div v-if="result" class="mt-8">
              <h3 class="text-xl font-semibold text-white mb-4">Generated Image</h3>
              <div class="bg-dragon-dark-700/50 p-6 rounded-xl border border-dragon-dark-600">
                <img :src="result" alt="Generated image" class="w-full rounded-lg">
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Other AI Tools -->
      <section class="py-20 px-4 sm:px-6 lg:px-8 bg-dragon-dark-800/50">
        <div class="max-w-7xl mx-auto">
          <h2 class="text-3xl font-bold text-white text-center mb-12">Other AI Tools</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div v-for="tool in tools" :key="tool.title" 
                 class="feature-card p-6 rounded-xl">
              <component :is="tool.icon" class="w-12 h-12 text-teal-400 mb-4" />
              <h3 class="text-xl font-semibold text-white mb-2">{{ tool.title }}</h3>
              <p class="text-gray-400 mb-4">{{ tool.description }}</p>
              <Link
                :href="tool.endpoint"
                class="btn-dragon-outline w-full"
              >
                Try Now
              </Link>
            </div>
          </div>
        </div>
      </section>

      <!-- CTA Section -->
      <section class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
          <h2 class="text-3xl sm:text-4xl font-bold text-white mb-6">
            Ready to Create Amazing Content?
          </h2>
          <p class="text-xl text-gray-400 mb-10 max-w-2xl mx-auto">
            Start using our AI tools to bring your ideas to life
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <Link
              :href="route('register')"
              class="btn-dragon"
            >
              Get Started
            </Link>
            <Link
              :href="route('pricing')"
              class="btn-dragon-outline"
            >
              View Pricing
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
         rounded-lg shadow-xl hover:shadow-teal-500/25 transition-all duration-300
         hover:from-teal-600 hover:to-blue-700 transform hover:-translate-y-0.5;
}

.btn-dragon-outline {
  @apply px-6 py-3 border-2 border-teal-500 text-teal-500 font-semibold 
         rounded-lg hover:bg-teal-500/10 transition-all duration-300
         hover:border-teal-400 hover:text-teal-400 transform hover:-translate-y-0.5;
}

.feature-card {
  @apply bg-dragon-dark-700/50 border border-dragon-dark-600 backdrop-blur-sm
         hover:border-teal-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-teal-500/10
         transform hover:-translate-y-1;
}
</style> 