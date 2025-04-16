<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import FrontLayout from "@/Layouts/FrontLayout.vue";
import { onMounted } from "vue";
import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

const assetUrl = import.meta.env.VITE_ASSET_URL;

const modules = [
  {
    name: "Focus",
    description: "Streamline your workflow and boost productivity with our task management system.",
    icon: "M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
  },
  {
    name: "Plans",
    description: "Create and manage your projects with advanced planning tools.",
    icon: "M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
  },
  {
    name: "Profile",
    description: "Build your professional identity with customizable profiles.",
    icon: "M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
  },
  {
    name: "Network",
    description: "Connect and collaborate with professionals in your field.",
    icon: "M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
  },
  {
    name: "Milai",
    description: "Leverage AI-powered tools for content creation and analysis.",
    icon: "M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
  },
  {
    name: "Studio",
    description: "Create and edit multimedia content with professional tools.",
    icon: "M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"
  },
  {
    name: "Finance",
    description: "Manage your financial assets and track investments.",
    icon: "M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
  },
  {
    name: "Crypto",
    description: "Trade and manage your cryptocurrency portfolio.",
    icon: "M13 10V3L4 14h7v7l9-11h-7z"
  },
  {
    name: "Websites",
    description: "Build and host your professional websites.",
    icon: "M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"
  },
  {
    name: "Storage",
    description: "Secure cloud storage for all your digital assets.",
    icon: "M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"
  }
];

onMounted(() => {
  gsap.registerPlugin(ScrollTrigger);

  // Hero section animation
  gsap.from(".hero-title", {
    duration: 1,
    y: 50,
    opacity: 0,
    ease: "power2.out"
  });

  gsap.from(".hero-description", {
    duration: 1,
    y: 30,
    opacity: 0,
    delay: 0.3,
    ease: "power2.out"
  });

  // Module animations
  gsap.utils.toArray(".module-card").forEach((card, i) => {
    gsap.from(card, {
      scrollTrigger: {
        trigger: card,
        start: "top bottom-=100",
        toggleActions: "play none none reverse"
      },
      duration: 0.8,
      y: 50,
      opacity: 0,
      delay: i * 0.1,
      ease: "power2.out"
    });
  });
});
</script>

<template>
  <FrontLayout title="Ecosystem" :hasHero="true">
    <template #content>
      <div class="min-h-screen bg-black text-white">
        <!-- Hero Section -->
        <div class="relative py-20 md:py-32">
          <div class="absolute inset-0 overflow-hidden">
            <img 
              :src="`${assetUrl}/images/backgrounds/background04.jpg`" 
              alt="Ecosystem Background" 
              class="w-full h-full object-cover opacity-30"
            >
          </div>
          <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
              <h1 class="hero-title text-4xl md:text-6xl font-bold mb-6">
                Realmsz Ecosystem
              </h1>
              <p class="hero-description text-xl md:text-2xl text-gray-300 max-w-3xl mx-auto">
                A comprehensive suite of tools and services designed to empower your digital journey.
              </p>
            </div>
          </div>
        </div>

        <!-- Modules Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-24">
          <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Integrated Modules</h2>
            <p class="text-xl text-gray-300 max-w-3xl mx-auto">
              Discover how our 10 essential modules work together to create a seamless experience.
            </p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div v-for="(module, index) in modules" :key="index" class="module-card bg-white/5 p-6 rounded-2xl hover:bg-white/10 transition-colors">
              <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="module.icon" />
                  </svg>
                </div>
                <h3 class="text-xl font-bold ml-4">{{ module.name }}</h3>
              </div>
              <p class="text-gray-300">
                {{ module.description }}
              </p>
            </div>
          </div>

          <!-- Partner Section -->
          <div class="mt-16 text-center">
            <div class="inline-flex items-center space-x-4 mb-8">
              <div class="h-px w-16 bg-gray-600"></div>
              <p class="text-gray-400 text-sm uppercase tracking-wider">Strategic Partnership</p>
              <div class="h-px w-16 bg-gray-600"></div>
            </div>
            <a 
              href="https://dracoscopia.com" 
              target="_blank" 
              rel="noopener noreferrer"
              class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-purple-600/20 to-blue-600/20 hover:from-purple-600/30 hover:to-blue-600/30 text-white font-semibold rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg hover:shadow-purple-500/20"
            >
              <span class="mr-3 text-lg">Dracoscopia</span>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
              </svg>
            </a>
            <p class="mt-4 text-gray-400 text-sm max-w-md mx-auto">
              Our strategic partnership with Dracoscopia brings together cutting-edge AI technology and blockchain solutions.
            </p>
          </div>

          <!-- Call to Action -->
          <div class="mt-16 text-center">
            <div class="inline-flex items-center space-x-4 mb-8">
              <div class="h-px w-16 bg-gray-600"></div>
              <p class="text-gray-400 text-sm uppercase tracking-wider">Ready to Get Started?</p>
              <div class="h-px w-16 bg-gray-600"></div>
            </div>
            <a 
              href="https://realmsz.com" 
              target="_blank" 
              rel="noopener noreferrer"
              class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg hover:shadow-blue-500/20"
            >
              <span class="mr-3 text-lg">Explore Realmsz</span>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
              </svg>
            </a>
            <p class="mt-4 text-gray-400 text-sm max-w-md mx-auto">
              Join thousands of users who are already experiencing the power of our integrated platform.
            </p>
          </div>
        </div>
      </div>
    </template>
  </FrontLayout>
</template>

<style scoped>
.hero-title {
  background: linear-gradient(to right, #fff, #94a3b8);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.module-card {
  transition: transform 0.3s ease;
}

.module-card:hover {
  transform: translateY(-5px);
}

@media (max-width: 768px) {
  .module-card {
    text-align: center;
  }
}
</style>
