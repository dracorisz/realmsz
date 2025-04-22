<script setup>
import { ref, onMounted } from "vue";
import { Link } from "@inertiajs/vue3";
import FrontLayout from "@/Layouts/FrontLayout.vue";
import { SparklesIcon, PhotoIcon, DocumentTextIcon, CodeBracketIcon, MusicalNoteIcon, VideoCameraIcon } from "@heroicons/vue/24/outline";

const tools = [
  {
    title: "Text to Image",
    description: "Generate stunning images from text descriptions",
    icon: PhotoIcon,
    endpoint: "/api/ai/text-to-image",
  },
  {
    title: "Text to Code",
    description: "Generate code from natural language descriptions",
    icon: CodeBracketIcon,
    endpoint: "/api/ai/text-to-code",
  },
  {
    title: "Text to Music",
    description: "Create unique music tracks from text prompts",
    icon: MusicalNoteIcon,
    endpoint: "/api/ai/text-to-music",
  },
  {
    title: "Text to Video",
    description: "Generate video content from text descriptions",
    icon: VideoCameraIcon,
    endpoint: "/api/ai/text-to-video",
  },
];

const prompt = ref("");
const loading = ref(false);
const result = ref(null);
const error = ref(null);

const generateImage = async () => {
  if (!prompt.value) {
    error.value = "Please enter a prompt";
    return;
  }

  loading.value = true;
  error.value = null;

  try {
    const response = await fetch("/api/ai/text-to-image", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ prompt: prompt.value }),
    });

    if (!response.ok) {
      throw new Error("Failed to generate image");
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
  <FrontLayout title="AI Tools | Realmsz Digital Asset Management">
    <div class="min-h-screen bg-gradient-to-b from-dragon-dark-900 to-dragon-dark-800">
      <!-- Hero Section -->
      <section class="relative px-4 py-20 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl text-center">
          <h1 class="bg-gradient-to-r from-teal-400 via-blue-500 to-purple-600 bg-clip-text text-4xl font-bold text-transparent sm:text-5xl md:text-6xl">AI Tools</h1>
          <p class="mx-auto mt-6 max-w-3xl text-xl text-blue-400 sm:text-2xl">Create amazing content with our AI-powered tools</p>
        </div>
      </section>

      <!-- Text to Image Section -->
      <section class="px-4 py-20 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
          <h2 class="mb-12 text-center text-3xl font-bold text-white">Text to Image</h2>

          <div class="mx-auto max-w-2xl">
            <div class="rounded-xl border border-dragon-dark-600 bg-dragon-dark-700/50 p-6">
              <div class="space-y-4">
                <div>
                  <label for="prompt" class="mb-2 block text-sm font-medium text-gray-400"> Enter your prompt </label>
                  <textarea id="prompt" v-model="prompt" rows="4" class="w-full rounded-lg border border-dragon-dark-600 bg-dragon-dark-800 px-4 py-2 text-white focus:border-teal-500 focus:outline-none" placeholder="Describe the image you want to generate..."></textarea>
                </div>

                <div v-if="error" class="text-sm text-red-500">
                  {{ error }}
                </div>

                <button @click="generateImage" :disabled="loading" class="btn-dragon w-full">
                  <span v-if="loading">Generating...</span>
                  <span v-else>Generate Image</span>
                </button>
              </div>
            </div>

            <div v-if="result" class="mt-8">
              <h3 class="mb-4 text-xl font-semibold text-white">Generated Image</h3>
              <div class="rounded-xl border border-dragon-dark-600 bg-dragon-dark-700/50 p-6">
                <img :src="result" alt="Generated image" class="w-full rounded-lg" />
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Other AI Tools -->
      <section class="bg-dragon-dark-800/50 px-4 py-20 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
          <h2 class="mb-12 text-center text-3xl font-bold text-white">Other AI Tools</h2>

          <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
            <div v-for="tool in tools" :key="tool.title" class="feature-card rounded-xl p-6">
              <component :is="tool.icon" class="mb-4 h-12 w-12 text-teal-400" />
              <h3 class="mb-2 text-xl font-semibold text-white">{{ tool.title }}</h3>
              <p class="mb-4 text-gray-400">{{ tool.description }}</p>
              <Link :href="tool.endpoint" class="btn-dragon-outline w-full"> Try Now </Link>
            </div>
          </div>
        </div>
      </section>

      <!-- CTA Section -->
      <section class="px-4 py-20 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-4xl text-center">
          <h2 class="mb-6 text-3xl font-bold text-white sm:text-4xl">Ready to Create Amazing Content?</h2>
          <p class="mx-auto mb-10 max-w-2xl text-xl text-gray-400">Start using our AI tools to bring your ideas to life</p>
          <div class="flex flex-col justify-center gap-4 sm:flex-row">
            <Link :href="route('register')" class="btn-dragon"> Get Started </Link>
            <Link :href="route('pricing')" class="btn-dragon-outline"> View Pricing </Link>
          </div>
        </div>
      </section>
    </div>
  </FrontLayout>
</template>

<style scoped>
.btn-dragon {
  @apply transform rounded-lg bg-gradient-to-r from-teal-500 to-blue-600 px-6 py-3 font-semibold text-white shadow-xl transition-all duration-300 hover:-translate-y-0.5 hover:from-teal-600 hover:to-blue-700 hover:shadow-teal-500/25;
}

.btn-dragon-outline {
  @apply transform rounded-lg border-2 border-teal-500 px-6 py-3 font-semibold text-teal-500 transition-all duration-300 hover:-translate-y-0.5 hover:border-teal-400 hover:bg-teal-500/10 hover:text-teal-400;
}

.feature-card {
  @apply transform border border-dragon-dark-600 bg-dragon-dark-700/50 backdrop-blur-sm transition-all duration-300 hover:-translate-y-1 hover:border-teal-500/50 hover:shadow-lg hover:shadow-teal-500/10;
}
</style>
