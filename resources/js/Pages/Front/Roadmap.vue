<script setup>
import { Link } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import FrontLayout from "@/Layouts/FrontLayout.vue";

gsap.registerPlugin(ScrollTrigger);

const roadmap = [
  {
    phase: "Phase 1: Foundation",
    date: "Q1 2025",
    milestones: ["Platform architecture design", "Core blockchain integration", "Basic NFT marketplace features"],
  },
  {
    phase: "Phase 2: Development",
    date: "Q2 2025",
    milestones: ["AI integration with Dezgo API", "Gaming platform development", "Social hub implementation"],
  },
  {
    phase: "Phase 3: Expansion",
    date: "Q3 2025",
    milestones: ["DeFi integration", "Content hub launch", "Partnership program"],
  },
  {
    phase: "Phase 4: Growth",
    date: "Q4 2025",
    milestones: ["Mobile app development", "Advanced AI features", "Global expansion"],
  },
];

onMounted(() => {
  // Animate hero section
  gsap.from(".hero-title", {
    y: 50,
    opacity: 0,
    duration: 1,
    ease: "power3.out",
  });

  gsap.from(".hero-subtitle", {
    y: 30,
    opacity: 0,
    duration: 1,
    delay: 0.3,
    ease: "power3.out",
  });

  // Animate roadmap items
  // gsap.from('.roadmap-phase', {
  //   y: 50,
  //   opacity: 0,
  //   duration: 0.8,
  //   stagger: 0.2,
  //   ease: 'power3.out',
  //   scrollTrigger: {
  //     trigger: '.roadmap-phase',
  //     start: 'top bottom-=100',
  //     toggleActions: 'play none none reverse'
  //   }
  // });
});
</script>

<template>
  <FrontLayout title="Roadmap | Realmsz Digital Asset Management">
    <div class="min-h-screen bg-gradient-to-b from-dragon-dark-900 to-dragon-dark-800">
      <!-- Hero Section -->
      <section class="relative px-4 py-20 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
          <div class="text-center">
            <h1 class="hero-title bg-gradient-to-r from-teal-400 via-blue-500 to-purple-600 bg-clip-text text-4xl font-bold text-transparent sm:text-5xl md:text-6xl">Development Roadmap</h1>
            <p class="hero-subtitle mx-auto mt-6 max-w-3xl text-xl text-blue-400 sm:text-2xl">Our vision for the future of Realmsz</p>
          </div>
        </div>
      </section>

      <!-- Roadmap Section -->
      <section class="px-4 py-20 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-4xl">
          <div class="space-y-12">
            <div v-for="(phase, index) in roadmap" :key="index" class="roadmap-phase card-dragon rounded-xl p-8">
              <div class="mb-6 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-white">{{ phase.phase }}</h2>
                <span
                  class="rounded-full px-4 py-1 text-sm font-semibold"
                  :class="{
                    'bg-green-500/20 text-green-400': phase.status === 'completed',
                    'bg-blue-500/20 text-blue-400': phase.status === 'in-progress',
                    'bg-purple-500/20 text-purple-400': phase.status === 'planned',
                  }"
                >
                  {{ phase.status === "completed" ? "Completed" : phase.status === "in-progress" ? "In Progress" : "Planned" }}
                </span>
              </div>
              <ul class="space-y-4">
                <li v-for="(item, itemIndex) in phase.milestones" :key="itemIndex" class="flex items-start">
                  <svg class="mr-2 mt-1 h-6 w-6 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span class="text-gray-300">{{ item }}</span>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- CTA Section -->
      <section class="bg-dragon-dark-800/50 px-4 py-20 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-4xl text-center">
          <h2 class="mb-6 text-3xl font-bold text-white sm:text-4xl">Join Us on This Journey</h2>
          <p class="mx-auto mb-10 max-w-2xl text-xl text-gray-400">Be part of the future of digital art and gaming</p>
          <div class="flex flex-col justify-center gap-4 sm:flex-row">
            <Link :href="route('register')" class="btn-dragon"> Get Started </Link>
            <Link :href="route('ecosystem')" class="btn-dragon-outline"> Learn More </Link>
          </div>
        </div>
      </section>
    </div>
  </FrontLayout>
</template>

<style scoped>
.btn-dragon {
  @apply rounded-lg bg-gradient-to-r from-teal-500 to-blue-600 px-6 py-3 font-semibold text-white shadow-xl transition-all duration-300 hover:shadow-teal-500/25;
}

.btn-dragon-outline {
  @apply rounded-lg border border-teal-500 px-6 py-3 font-semibold text-teal-400 transition-all duration-300 hover:bg-teal-500/10;
}

.card-dragon {
  @apply border border-dragon-dark-600 bg-dragon-dark-700/50 backdrop-blur-sm transition-all duration-300 hover:border-teal-500/50 hover:shadow-lg hover:shadow-teal-500/10;
}
</style>
