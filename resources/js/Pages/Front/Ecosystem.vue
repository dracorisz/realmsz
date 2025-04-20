<script setup>
import { Link } from "@inertiajs/vue3";
import FrontLayout from "@/Layouts/FrontLayout.vue";
import { onMounted, ref, computed } from "vue";
import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import axios from "axios";
import AIImageGenerator from '@/Components/AIImageGenerator.vue';
// import DezgoImageGenerator from '@/Components/DezgoImageGenerator.vue';

const scrollToSection = (sectionId) => {
  const element = document.getElementById(sectionId);
  if (element) {
    element.scrollIntoView({ behavior: "smooth" });
  }
};

// Static data for modules
const modules = [
  {
    name: "NFT Marketplace",
    description: "Create, buy, and sell NFTs with our advanced marketplace",
    icon: "🎨",
    features: ["Batch Minting", "Royalty Management", "Cross-chain Support", "Auction System", "Dezgo AI Integration"],
    image: "https://dracoscopia.com/images/ai/8.png",
  },
  {
    name: "Gaming Platform",
    description: "Play and create games in our immersive gaming environment",
    icon: "🎮",
    features: ["Play-to-Earn", "Game Development", "Asset Trading", "Tournaments"],
    image: "https://dracoscopia.com/images/ai/2.png",
  },
  {
    name: "Social Hub",
    description: "Connect with creators and gamers in our social network",
    icon: "🤝",
    features: ["Community Forums", "Creator Profiles", "Collaboration Tools", "Event Calendar"],
    image: "https://dracoscopia.com/images/ai/3.png",
  },
  {
    name: "AI Studio",
    description: "Generate art and content with our AI-powered tools",
    icon: "🤖",
    features: ["Stable Diffusion XL", "Flux AI Models", "Lightning Fast Generation", "Custom LoRA Support", "Batch Generation", "Image-to-Image", "Inpainting", "Background Removal"],
    image: "https://dracoscopia.com/images/ai/4.png",
  },
  {
    name: "DeFi Integration",
    description: "Access decentralized finance features and services",
    icon: "💰",
    features: ["Liquidity Pools", "Staking", "Yield Farming", "Cross-chain Swaps"],
    image: "https://dracoscopia.com/images/ai/5.png",
  },
  {
    name: "Content Hub",
    description: "Share and monetize your creative content",
    icon: "🎬",
    features: ["Content Management", "Monetization Tools", "Analytics", "Distribution"],
    image: "https://dracoscopia.com/images/ai/7.png",
  },
];

// Static data for integrations
const integrations = [
  {
    name: "Ethereum",
    logo: "https://dracoscopia.com/images/chains/ethereum-eth-logo.svg",
    description: "Leading smart contract platform",
  },
  {
    name: "Polygon",
    logo: "https://dracoscopia.com/images/chains/polygon-matic-logo.svg",
    description: "Scalable Ethereum scaling solution",
  },
  {
    name: "Optimism",
    logo: "https://dracoscopia.com/images/chains/optimism-op-logo.svg",
    description: "Ethereum Layer 2 scaling",
  },
  {
    name: "Solana",
    logo: "https://dracoscopia.com/images/chains/solana-sol-logo.svg",
    description: "High-performance blockchain",
  },
];

// Add Dezgo API configuration
const dezgoConfig = {
  apiUrl: "https://api.dezgo.com",
  apiKey: "DEZGO-C435BFF100D9194A967B1C199F2F5BACE688F4A861EBE2C8A9892536C1835AEDD5107C3B",
};

gsap.registerPlugin(ScrollTrigger);

onMounted(() => {
  // Simple fade-in animation for hero section
  const hero = document.querySelector(".hero-section");
  if (hero) {
    gsap.from(hero, {
      opacity: 0,
      y: 50,
      duration: 1,
      ease: "power2.out",
    });
  }
});
</script>

<template>
  <FrontLayout>
    <div class="min-h-screen bg-gradient-to-b from-gray-900 to-black text-white">
      <!-- Hero Section -->
      <section class="hero-section container mx-auto px-4 py-16">
        <div class="text-center">
          <h1 class="mb-6 text-6xl font-bold">RealmSZ Ecosystem</h1>
          <p class="mb-8 text-xl text-gray-300">
            A comprehensive platform for creators, gamers, and investors
          </p>
          <div class="flex justify-center gap-4">
            <button
              @click="scrollToSection('modules')"
              class="rounded-lg bg-blue-600 px-6 py-3 font-semibold hover:bg-blue-700"
            >
              Explore Modules
            </button>
            <button
              @click="scrollToSection('ai-studio')"
              class="rounded-lg bg-purple-600 px-6 py-3 font-semibold hover:bg-purple-700"
            >
              Try AI Studio
            </button>
          </div>
        </div>
      </section>

      <!-- Modules Section -->
      <section id="modules" class="container mx-auto px-4 py-16">
        <h2 class="mb-8 text-4xl font-bold">Core Modules</h2>
        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
          <div
            v-for="module in modules"
            :key="module.name"
            class="rounded-lg bg-gray-800 p-6 shadow-lg transition-transform hover:scale-105"
          >
            <div class="mb-4 text-4xl">{{ module.icon }}</div>
            <h3 class="mb-2 text-2xl font-semibold">{{ module.name }}</h3>
            <p class="mb-4 text-gray-300">{{ module.description }}</p>
            <ul class="space-y-2">
              <li
                v-for="feature in module.features"
                :key="feature"
                class="flex items-center text-gray-300"
              >
                <span class="mr-2">✓</span>
                {{ feature }}
              </li>
            </ul>
          </div>
        </div>
      </section>

      <!-- AI Studio Section -->
      <section id="ai-studio" class="container mx-auto px-4 py-16">
        <h2 class="mb-8 text-4xl font-bold">AI Image Generation</h2>
        <AIImageGenerator
          :api-key="dezgoConfig.apiKey"
          :api-url="dezgoConfig.apiUrl"
        />
      </section>

      <!-- Integrations Section -->
      <section class="container mx-auto px-4 py-16">
        <h2 class="mb-8 text-4xl font-bold">Blockchain Integrations</h2>
        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
          <div
            v-for="integration in integrations"
            :key="integration.name"
            class="rounded-lg bg-gray-800 p-6 text-center"
          >
            <img
              :src="integration.logo"
              :alt="integration.name"
              class="mx-auto mb-4 h-16 w-16"
            />
            <h3 class="mb-2 text-xl font-semibold">{{ integration.name }}</h3>
            <p class="text-gray-300">{{ integration.description }}</p>
          </div>
        </div>
      </section>
    </div>
  </FrontLayout>
</template>

<style scoped>
/* Add any custom styles here */
</style>

