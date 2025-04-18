<template>
  <FrontLayout>
    <div class="relative">
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
              <h3 class="text-lg font-medium nft-title">Your NFTs</h3>
              <Link
                :href="route('nft.create')"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded create-button"
              >
                Create NFT
              </Link>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <!-- NFT Card -->
              <div
                v-for="nft in nfts"
                :key="nft.id"
                class="border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow nft-card"
              >
                <img
                  :src="nft.image"
                  :alt="nft.name"
                  class="w-full h-48 object-cover nft-image"
                />
                <div class="p-4">
                  <h4 class="font-semibold text-lg nft-name">{{ nft.name }}</h4>
                  <p class="text-gray-600 nft-description">{{ nft.description }}</p>
                  <div class="mt-4 flex justify-between items-center">
                    <span class="text-sm text-gray-500">#{{ nft.id }}</span>
                    <Link
                      :href="route('nft.show', nft.id)"
                      class="text-blue-500 hover:text-blue-700 view-button"
                    >
                      View Details
                    </Link>
                  </div>
                </div>
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
import { Link } from '@inertiajs/vue3';
import FrontLayout from '@/Layouts/FrontLayout.vue';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

defineProps({
  nfts: {
    type: Array,
    default: () => [],
  },
});

onMounted(() => {
  gsap.registerPlugin(ScrollTrigger);

  // Title and button animations
  const titleTimeline = gsap.timeline();
  const title = document.querySelector(".nft-title");
  const createButton = document.querySelector(".create-button");

  if (title) {
    titleTimeline.from(title, {
      duration: 0.8,
      y: 30,
      opacity: 0,
      ease: "power2.out"
    });
  }

  if (createButton) {
    titleTimeline.from(createButton, {
      duration: 0.8,
      y: 20,
      opacity: 0,
      ease: "power2.out"
    }, "-=0.4");
  }

  // NFT cards animations
  const nftCards = document.querySelectorAll(".nft-card");
  if (nftCards.length > 0) {
    const cardsTimeline = gsap.timeline({
      scrollTrigger: {
        trigger: ".nft-card",
        start: "top center+=100",
        toggleActions: "play none none reverse"
      }
    });

    cardsTimeline.from(nftCards, {
      duration: 0.8,
      y: 50,
      opacity: 0,
      stagger: 0.1,
      ease: "back.out(1.7)"
    });

    // Hover animations for cards
    nftCards.forEach(card => {
      card.addEventListener("mouseenter", () => {
        gsap.to(card, {
          duration: 0.3,
          y: -5,
          scale: 1.02,
          boxShadow: "0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)",
          ease: "power2.out"
        });
      });

      card.addEventListener("mouseleave", () => {
        gsap.to(card, {
          duration: 0.3,
          y: 0,
          scale: 1,
          boxShadow: "0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)",
          ease: "power2.out"
        });
      });
    });
  }
});
</script>

<style scoped>
.nft-card {
  transition: all 0.3s ease;
  border-radius: 0.5rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.nft-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.nft-image {
  transition: transform 0.3s ease;
}

.nft-card:hover .nft-image {
  transform: scale(1.05);
}

@media (max-width: 768px) {
  .nft-card {
    text-align: center;
  }
}
</style> 