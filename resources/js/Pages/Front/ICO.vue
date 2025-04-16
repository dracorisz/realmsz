<script setup>
import FrontLayout from "@/Layouts/FrontLayout.vue";
import Slider from "@/Components/Slider.vue";
import { onMounted, ref } from "vue";
import { gsap } from "gsap";

const assetUrl = import.meta.env.VITE_ASSET_URL;

const sliderImages = [
  {
    src: `${assetUrl}/images/ai/1.webp`,
    alt: 'Dracoscopia Hero',
    title: 'Dracoscopia Token (DRC) ICO'
  },
  {
    src: `${assetUrl}/images/ai/2.png`,
    alt: 'Early Bird Phase',
    title: 'Early Bird Phase - 50% Discount'
  },
  {
    src: `${assetUrl}/images/ai/3.png`,
    alt: 'Main Sale Phase',
    title: 'Main Sale Phase - 25% Discount'
  },
  {
    src: `${assetUrl}/images/ai/4.png`,
    alt: 'Public Sale Phase',
    title: 'Public Sale Phase'
  },
  {
    src: `${assetUrl}/images/ai/5.png`,
    alt: 'Blockchain Technology',
    title: 'Multi-Chain Integration'
  }
];

const chainLogos = [
  {
    src: `${assetUrl}/images/chains/polygon-matic-logo.svg`,
    alt: 'Polygon',
    name: 'Polygon'
  },
  {
    src: `${assetUrl}/images/chains/ethereum-eth-logo.svg`,
    alt: 'Ethereum',
    name: 'Ethereum'
  },
  {
    src: `${assetUrl}/images/chains/solana-sol-logo.svg`,
    alt: 'Solana',
    name: 'Solana'
  },
  {
    src: `${assetUrl}/images/chains/avalanche-avax-logo.svg`,
    alt: 'Avalanche',
    name: 'Avalanche'
  },
  {
    src: `${assetUrl}/images/chains/bnb-bnb-logo.svg`,
    alt: 'BNB Chain',
    name: 'BNB Chain'
  },
  {
    src: `${assetUrl}/images/chains/arbitrum-arb-logo.svg`,
    alt: 'Arbitrum',
    name: 'Arbitrum'
  }
];

const elements = ref(null);

onMounted(() => {
  // Wait for next tick to ensure DOM is ready
  setTimeout(() => {
    const heading = document.querySelector(".heading-1");
    const description = document.querySelectorAll("p");
    const listItems = document.querySelectorAll("li");
    const cards = document.querySelectorAll(".card");
    const links = document.querySelectorAll("a");
    const images = document.querySelectorAll("img");

    if (heading || description.length || listItems.length || cards.length || links.length || images.length) {
      // Initial setup
      gsap.set([heading, ...description, ...listItems, ...cards, ...links, ...images].filter(Boolean), { 
        opacity: 0, 
        y: 20 
      });

      // Animate elements in sequence
      const timeline = gsap.timeline();
      timeline
        .to(heading, { duration: 1, opacity: 1, y: 0, ease: "power2.out" })
        .to(description, { duration: 0.8, opacity: 1, y: 0, stagger: 0.2, ease: "power2.out" }, "-=0.5")
        .to(listItems, { duration: 0.6, opacity: 1, y: 0, stagger: 0.1, ease: "power2.out" }, "-=0.3")
        .to(cards, { duration: 0.8, opacity: 1, y: 0, stagger: 0.2, ease: "power2.out" }, "-=0.3")
        .to(links, { duration: 0.5, opacity: 1, y: 0, stagger: 0.1, ease: "power2.out" }, "-=0.3")
        .to(images, { duration: 0.8, opacity: 1, y: 0, stagger: 0.2, ease: "power2.out" }, "-=0.3");
    }
  }, 100);
});
</script>

<template>
  <FrontLayout title="ICO">
    <template #content>
      <div class="m-auto flex h-full w-full max-w-7xl flex-col bg-black px-4 md:px-10 py-10 md:py-20 text-white">
        <!-- Hero Section with Slider -->
        <div class="mb-8 md:mb-16">
          <Slider :images="sliderImages" height="h-64 md:h-96" />
        </div>

        <!-- Token Info Section -->
        <div class="flex flex-col md:flex-row gap-8 mb-8 md:mb-16">
          <div class="flex-1">
            <div class="flex flex-col gap-3">
              <p class="text-sm md:text-base">Join our ICO and be part of the future of Dracoscopia!</p>
              <p class="text-sm md:text-base">Our token sale will help fund the development and growth of the platform.</p>
              <div class="flex flex-col gap-2 mt-4">
                <p class="text-sm md:text-base">Token Contract Address:</p>
                <a href="https://polygonscan.com/token/0x497A50c4784dc278BC5d78A273596E0EB2Fd149b" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   class="text-blue-400 hover:text-blue-300 transition-colors break-all text-xs md:text-sm">
                   0x497A50c4784dc278BC5d78A273596E0EB2Fd149b
                </a>
                <a href="https://dracoscopia.com" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   class="text-blue-400 hover:text-blue-300 transition-colors text-sm md:text-base">
                   Visit Dracoscopia Website
                </a>
              </div>
            </div>
          </div>
          <div class="flex-1 mt-4 md:mt-0">
            <img :src="`${assetUrl}/images/ai/token.png`" alt="DRC Token" class="w-full h-auto rounded-2xl">
          </div>
        </div>

        <!-- Sale Phases -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-8 my-8 md:my-10">
          <div class="card bg-white/5 p-4 md:p-6 rounded-2xl hover:bg-white/10 transition-colors">
            <img :src="`${assetUrl}/images/ai/2.png`" alt="Early Bird" class="w-full h-32 md:h-48 object-cover rounded-xl mb-4">
            <h3 class="text-lg md:text-xl font-bold mb-4">Early Bird</h3>
            <ul class="space-y-2 md:space-y-3">
              <li class="text-base md:text-lg">• 50% discount on DRC tokens</li>
              <li class="text-base md:text-lg">• Limited to first 1000 participants</li>
              <li class="text-base md:text-lg">• Minimum purchase: 1000 DRC</li>
              <li class="text-base md:text-lg">• Exclusive early access to features</li>
            </ul>
          </div>
          <div class="card bg-white/5 p-4 md:p-6 rounded-2xl hover:bg-white/10 transition-colors">
            <img :src="`${assetUrl}/images/ai/3.png`" alt="Main Sale" class="w-full h-32 md:h-48 object-cover rounded-xl mb-4">
            <h3 class="text-lg md:text-xl font-bold mb-4">Main Sale</h3>
            <ul class="space-y-2 md:space-y-3">
              <li class="text-base md:text-lg">• 25% discount on DRC tokens</li>
              <li class="text-base md:text-lg">• Open to all participants</li>
              <li class="text-base md:text-lg">• Minimum purchase: 500 DRC</li>
              <li class="text-base md:text-lg">• Priority access to platform features</li>
            </ul>
          </div>
          <div class="card bg-white/5 p-4 md:p-6 rounded-2xl hover:bg-white/10 transition-colors">
            <img :src="`${assetUrl}/images/ai/4.png`" alt="Public Sale" class="w-full h-32 md:h-48 object-cover rounded-xl mb-4">
            <h3 class="text-lg md:text-xl font-bold mb-4">Public Sale</h3>
            <ul class="space-y-2 md:space-y-3">
              <li class="text-base md:text-lg">• Standard DRC token price</li>
              <li class="text-base md:text-lg">• Open to all participants</li>
              <li class="text-base md:text-lg">• No minimum purchase</li>
              <li class="text-base md:text-lg">• Full platform access</li>
            </ul>
          </div>
        </div>

        <!-- Blockchain Integration -->
        <div class="bg-white/5 p-4 md:p-8 rounded-2xl mb-8 md:mb-16">
          <h3 class="text-xl md:text-2xl font-bold mb-6">Blockchain Integration</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
              <h4 class="text-lg md:text-xl font-semibold mb-4">Multi-Chain Support</h4>
              <p class="text-sm md:text-base mb-4">DRC token is built on multiple leading blockchain networks, offering:</p>
              <ul class="space-y-2">
                <li class="text-sm md:text-base">• Low transaction fees</li>
                <li class="text-sm md:text-base">• Fast transaction speeds</li>
                <li class="text-sm md:text-base">• Cross-chain compatibility</li>
                <li class="text-sm md:text-base">• Secure and scalable infrastructure</li>
              </ul>
              
              <div class="mt-6 md:mt-8">
                <h4 class="text-lg md:text-xl font-semibold mb-4">Supported Networks</h4>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                  <div v-for="chain in chainLogos" :key="chain.name" class="flex items-center gap-2">
                    <img :src="chain.src" :alt="chain.alt" class="w-6 h-6 md:w-8 md:h-8">
                    <span class="text-xs md:text-sm">{{ chain.name }}</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="mt-4 md:mt-0">
              <img :src="`${assetUrl}/images/ai/5.png`" alt="Blockchain Technology" class="w-full h-auto rounded-xl">
            </div>
          </div>
        </div>

        <!-- Token Benefits -->
        <div class="flex flex-col gap-3">
          <h3 class="text-xl md:text-2xl font-bold mb-6">DRC Token Benefits</h3>
          <ul class="space-y-2">
            <li class="text-sm md:text-base">• Voting rights on platform decisions</li>
            <li class="text-sm md:text-base">• Access to premium features</li>
            <li class="text-sm md:text-base">• Revenue sharing from platform fees</li>
            <li class="text-sm md:text-base">• Early access to new features</li>
            <li class="text-sm md:text-base">• Staking rewards and benefits</li>
            <li class="text-sm md:text-base">• Exclusive community events</li>
          </ul>
        </div>

        <!-- Version Info -->
        <div class="mt-8 text-center text-xs text-gray-400">
          Version 1.0.0-beta
        </div>
      </div>
    </template>
  </FrontLayout>
</template>

