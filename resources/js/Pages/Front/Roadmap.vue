<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import FrontLayout from '@/Layouts/FrontLayout.vue';

gsap.registerPlugin(ScrollTrigger);

const roadmap = [
  {
    phase: 'Phase 1: Foundation',
    date: 'Q1 2025',
    milestones: [
      'Platform architecture design',
      'Core blockchain integration',
      'Basic NFT marketplace features'
    ]
  },
  {
    phase: 'Phase 2: Development',
    date: 'Q2 2025',
    milestones: [
      'AI integration with Dezgo API',
      'Gaming platform development',
      'Social hub implementation'
    ]
  },
  {
    phase: 'Phase 3: Expansion',
    date: 'Q3 2025',
    milestones: [
      'DeFi integration',
      'Content hub launch',
      'Partnership program'
    ]
  },
  {
    phase: 'Phase 4: Growth',
    date: 'Q4 2025',
    milestones: [
      'Mobile app development',
      'Advanced AI features',
      'Global expansion'
    ]
  }
];

onMounted(() => {
  // Animate hero section
  gsap.from('.hero-title', {
    y: 50,
    opacity: 0,
    duration: 1,
    ease: 'power3.out'
  });

  gsap.from('.hero-subtitle', {
    y: 30,
    opacity: 0,
    duration: 1,
    delay: 0.3,
    ease: 'power3.out'
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
      <section class="relative py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
          <div class="text-center">
            <h1 class="hero-title text-4xl sm:text-5xl md:text-6xl font-bold bg-gradient-to-r from-teal-400 via-blue-500 to-purple-600 bg-clip-text text-transparent">
              Development Roadmap
            </h1>
            <p class="hero-subtitle mt-6 text-xl sm:text-2xl text-blue-400 max-w-3xl mx-auto">
              Our vision for the future of Realmsz
            </p>
          </div>
        </div>
      </section>

      <!-- Roadmap Section -->
      <section class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
          <div class="space-y-12">
            <div
              v-for="(phase, index) in roadmap"
              :key="index"
              class="roadmap-phase card-dragon p-8 rounded-xl"
            >
              <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-white">{{ phase.phase }}</h2>
                <span
                  class="px-4 py-1 rounded-full text-sm font-semibold"
                  :class="{
                    'bg-green-500/20 text-green-400': phase.status === 'completed',
                    'bg-blue-500/20 text-blue-400': phase.status === 'in-progress',
                    'bg-purple-500/20 text-purple-400': phase.status === 'planned'
                  }"
                >
                  {{ phase.status === 'completed' ? 'Completed' : phase.status === 'in-progress' ? 'In Progress' : 'Planned' }}
                </span>
              </div>
              <ul class="space-y-4">
                <li
                  v-for="(item, itemIndex) in phase.milestones"
                  :key="itemIndex"
                  class="flex items-start"
                >
                  <svg
                    class="h-6 w-6 text-teal-400 mr-2 mt-1"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                  </svg>
                  <span class="text-gray-300">{{ item }}</span>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- CTA Section -->
      <section class="py-20 px-4 sm:px-6 lg:px-8 bg-dragon-dark-800/50">
        <div class="max-w-4xl mx-auto text-center">
          <h2 class="text-3xl sm:text-4xl font-bold text-white mb-6">
            Join Us on This Journey
          </h2>
          <p class="text-xl text-gray-400 mb-10 max-w-2xl mx-auto">
            Be part of the future of digital art and gaming
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <Link
              :href="route('register')"
              class="btn-dragon"
            >
              Get Started
            </Link>
            <Link
              :href="route('ecosystem')"
              class="btn-dragon-outline"
            >
              Learn More
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
  @apply px-6 py-3 border border-teal-500 text-teal-400 font-semibold 
         rounded-lg hover:bg-teal-500/10 transition-all duration-300;
}

.card-dragon {
  @apply bg-dragon-dark-700/50 border border-dragon-dark-600 backdrop-blur-sm
         hover:border-teal-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-teal-500/10;
}
</style> 