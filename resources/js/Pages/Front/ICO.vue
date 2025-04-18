<script setup>
import { ref } from 'vue';
import { Link, Head } from '@inertiajs/vue3';
import FrontLayout from '@/Layouts/FrontLayout.vue';
import { onMounted } from 'vue';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { 
  CurrencyDollarIcon,
  ClockIcon,
  ChartBarIcon,
  ShieldCheckIcon,
  GlobeAltIcon,
  UserGroupIcon,
  ArrowRightIcon
} from '@heroicons/vue/24/outline';

// Token Metrics
const tokenMetrics = {
  totalSupply: '10,000 ONLY',
  initialPrice: '$0.10',
  hardCap: '$1,000,000',
  softCap: '$500,000',
  startDate: 'May 1, 2024',
  endDate: 'June 30, 2024'
};

// Token Distribution
const tokenDistribution = [
  { name: 'Community Rewards', percentage: 40, color: 'bg-teal-500' },
  { name: 'Team & Advisors', percentage: 25, color: 'bg-purple-500' },
  { name: 'Liquidity Pool', percentage: 20, color: 'bg-blue-500' },
  { name: 'Marketing', percentage: 10, color: 'bg-pink-500' },
  { name: 'Treasury', percentage: 5, color: 'bg-yellow-500' }
];

// Features
const features = [
  {
    name: 'Token Utility',
    description: 'Access to platform features, governance rights, and exclusive benefits',
    icon: CurrencyDollarIcon
  },
  {
    name: 'Vesting Schedule',
    description: 'Team tokens locked for 12 months with 24-month linear vesting',
    icon: ClockIcon
  },
  {
    name: 'Market Analysis',
    description: 'Strong market potential with growing demand for blockchain solutions',
    icon: ChartBarIcon
  },
  {
    name: 'Security',
    description: 'Smart contract audited by leading security firms',
    icon: ShieldCheckIcon
  },
  {
    name: 'Global Reach',
    description: 'Targeting international markets with multi-language support',
    icon: GlobeAltIcon
  },
  {
    name: 'Community Focus',
    description: 'Active community engagement and regular updates',
    icon: UserGroupIcon
  }
];

// Sale Phases
const currentPhase = ref(1);
const phases = [
  {
    id: 1,
    name: 'Private Sale',
    startDate: 'May 1, 2024',
    endDate: 'May 15, 2024',
    bonus: '30%',
    price: '$0.07',
    minContribution: '$1,000',
    maxContribution: '$50,000'
  },
  {
    id: 2,
    name: 'Pre-Sale',
    startDate: 'May 16, 2024',
    endDate: 'May 31, 2024',
    bonus: '20%',
    price: '$0.08',
    minContribution: '$500',
    maxContribution: '$25,000'
  },
  {
    id: 3,
    name: 'Public Sale',
    startDate: 'June 1, 2024',
    endDate: 'June 30, 2024',
    bonus: '10%',
    price: '$0.09',
    minContribution: '$100',
    maxContribution: '$10,000'
  }
];

const teamMembers = [
  {
    name: 'Scopia Rewainde',
    role: 'CEO',
    image: 'https://dracoscopia.com/sa5.png',
    email: 'scopia@realmsz.com'
  },
  {
    name: 'Dragol Rewainde',
    role: 'CTO',
    image: 'https://dracoscopia.com/ee.png',
    email: 'dragol@realmsz.com'
  },
  {
    name: 'Mila Milai',
    role: 'Admin & AU',
    image: 'https://dracoscopia.com/tt.png',
    email: 'mila@realmsz.com'
  }
];

onMounted(() => {
  gsap.registerPlugin(ScrollTrigger);

  // Simple fade-in animation for hero section
  gsap.from(".hero-section", {
    duration: 1,
    opacity: 0,
    y: 20,
    ease: "power2.out"
  });

  // Simple fade-in animation for sections
  const sections = ["#token-metrics", "#token-features", "#timeline"];
  sections.forEach(section => {
    gsap.from(section, {
      scrollTrigger: {
        trigger: section,
        start: "top center+=100",
        toggleActions: "play none none reverse"
      },
      duration: 0.8,
      opacity: 0,
      y: 30,
      ease: "power2.out"
    });
  });

  // Simple hover effects for cards
  const cards = gsap.utils.toArray(".metric-card, .feature-card");
  cards.forEach(card => {
    card.addEventListener("mouseenter", () => {
      gsap.to(card, {
        duration: 0.3,
        y: -5,
        scale: 1.02,
        ease: "power2.out"
      });
    });

    card.addEventListener("mouseleave", () => {
      gsap.to(card, {
        duration: 0.3,
        y: 0,
        scale: 1,
        ease: "power2.out"
      });
    });
  });
});
</script>

<template>
  <Head title="ICO" />
  <FrontLayout>
    <div class="min-h-screen bg-gradient-to-b from-dragon-dark-900 to-dragon-dark-800">
      <!-- Hero Section -->
      <div class="relative overflow-hidden">
        <div class="absolute inset-0">
          <img class="h-full w-full object-cover opacity-20" src="https://dracoscopia.com/images/backgrounds/background03.jpg" alt="Background">
          <div class="absolute inset-0 bg-gradient-to-b from-dragon-dark-900/80 to-dragon-dark-800/80"></div>
        </div>
        <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8">
          <div class="text-center">
            <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">
              Realmsz Token Sale
            </h1>
            <p class="mt-6 text-xl text-gray-400 max-w-3xl mx-auto">
              Join us in revolutionizing the future of digital assets and blockchain technology
            </p>
            <div class="mt-10 flex justify-center space-x-4">
              <Link :href="route('register')" 
                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg 
                           text-white bg-teal-500 hover:bg-teal-600 focus:outline-none focus:ring-2 focus:ring-offset-2 
                           focus:ring-teal-500 transition-all duration-300 transform hover:-translate-y-0.5">
                Participate Now
                <ArrowRightIcon class="ml-2 h-5 w-5" />
              </Link>
              <Link :href="route('whitepaper')" 
                    class="inline-flex items-center px-6 py-3 border border-gray-700 text-base font-medium rounded-lg 
                           text-gray-400 bg-dragon-dark-700 hover:bg-dragon-dark-600 focus:outline-none focus:ring-2 
                           focus:ring-offset-2 focus:ring-teal-500 transition-all duration-300">
                Read Whitepaper
              </Link>
            </div>
          </div>
        </div>
      </div>

      <!-- Token Metrics -->
      <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
          <div v-for="(value, key) in tokenMetrics" :key="key"
               class="bg-dragon-dark-700/50 p-6 rounded-xl border border-dragon-dark-600 backdrop-blur-sm">
            <h3 class="text-lg font-medium text-gray-400 capitalize">{{ key.replace(/([A-Z])/g, ' $1').trim() }}</h3>
            <p class="mt-2 text-3xl font-bold text-white">{{ value }}</p>
          </div>
        </div>
      </div>

      <!-- Token Distribution -->
      <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
        <div class="text-center">
          <h2 class="text-3xl font-bold text-white">Token Distribution</h2>
          <p class="mt-4 text-xl text-gray-400">Fair and transparent allocation of tokens</p>
        </div>
        <div class="mt-12">
          <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-5">
            <div v-for="item in tokenDistribution" :key="item.name"
                 class="bg-dragon-dark-700/50 p-6 rounded-xl border border-dragon-dark-600 backdrop-blur-sm">
              <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-white">{{ item.name }}</h3>
                <span class="text-2xl font-bold" :class="item.color">{{ item.percentage }}%</span>
              </div>
              <div class="mt-4 h-2 bg-dragon-dark-600 rounded-full overflow-hidden">
                <div class="h-full rounded-full" :class="item.color" :style="{ width: `${item.percentage}%` }"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Sale Phases -->
      <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
        <div class="text-center">
          <h2 class="text-3xl font-bold text-white">Sale Phases</h2>
          <p class="mt-4 text-xl text-gray-400">Multiple phases with different benefits</p>
        </div>
        <div class="mt-12">
          <div class="grid grid-cols-1 gap-8 sm:grid-cols-3">
            <div v-for="phase in phases" :key="phase.id"
                 class="bg-dragon-dark-700/50 p-8 rounded-xl border border-dragon-dark-600 backdrop-blur-sm
                        transition-all duration-300 transform hover:-translate-y-1"
                 :class="{ 'ring-2 ring-teal-500': currentPhase === phase.id }">
              <div class="text-center">
                <h3 class="text-2xl font-bold text-white">{{ phase.name }}</h3>
                <div class="mt-4 text-3xl font-bold text-teal-400">
                  {{ phase.price }}<span class="text-lg text-gray-400">/token</span>
                </div>
                <div class="mt-2 text-xl font-bold text-purple-400">
                  {{ phase.bonus }} Bonus
                </div>
              </div>
              <div class="mt-6 space-y-4">
                <div class="flex justify-between text-gray-400">
                  <span>Start Date</span>
                  <span class="text-white">{{ phase.startDate }}</span>
                </div>
                <div class="flex justify-between text-gray-400">
                  <span>End Date</span>
                  <span class="text-white">{{ phase.endDate }}</span>
                </div>
                <div class="flex justify-between text-gray-400">
                  <span>Min Contribution</span>
                  <span class="text-white">{{ phase.minContribution }}</span>
                </div>
                <div class="flex justify-between text-gray-400">
                  <span>Max Contribution</span>
                  <span class="text-white">{{ phase.maxContribution }}</span>
                </div>
              </div>
              <div class="mt-8 text-center">
                <button class="w-full py-3 px-4 rounded-lg text-white bg-teal-500 hover:bg-teal-600 
                              focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500
                              transition-all duration-300"
                        :class="{ 'opacity-50 cursor-not-allowed': currentPhase !== phase.id }"
                        :disabled="currentPhase !== phase.id">
                  {{ currentPhase === phase.id ? 'Participate Now' : 'Coming Soon' }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Features -->
      <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
        <div class="text-center">
          <h2 class="text-3xl font-bold text-white">Why Invest in Realmsz?</h2>
          <p class="mt-4 text-xl text-gray-400">Key features and benefits of our token</p>
        </div>
        <div class="mt-12">
          <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
            <div v-for="feature in features" :key="feature.name"
                 class="bg-dragon-dark-700/50 p-8 rounded-xl border border-dragon-dark-600 backdrop-blur-sm
                        transition-all duration-300 transform hover:-translate-y-1">
              <component :is="feature.icon" class="h-12 w-12 text-teal-400" />
              <h3 class="mt-4 text-xl font-bold text-white">{{ feature.name }}</h3>
              <p class="mt-2 text-gray-400">{{ feature.description }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- CTA Section -->
      <div class="relative overflow-hidden">
        <div class="absolute inset-0">
          <img class="h-full w-full object-cover opacity-20" src="https://dracoscopia.com/images/backgrounds/background03.jpg" alt="Background">
          <div class="absolute inset-0 bg-gradient-to-b from-dragon-dark-900/80 to-dragon-dark-800/80"></div>
        </div>
        <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8">
          <div class="text-center">
            <h2 class="text-3xl font-bold text-white">Ready to Join the Revolution?</h2>
            <p class="mt-4 text-xl text-gray-400 max-w-3xl mx-auto">
              Don't miss out on this opportunity to be part of the future of blockchain technology
            </p>
            <div class="mt-10">
              <Link :href="route('register')" 
                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg 
                           text-white bg-teal-500 hover:bg-teal-600 focus:outline-none focus:ring-2 focus:ring-offset-2 
                           focus:ring-teal-500 transition-all duration-300 transform hover:-translate-y-0.5">
                Get Started Now
                <ArrowRightIcon class="ml-2 h-5 w-5" />
              </Link>
            </div>
          </div>
        </div>
      </div>
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

.metric-card {
  @apply bg-dragon-dark-700/50 border border-dragon-dark-600 backdrop-blur-sm
         hover:border-teal-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-teal-500/10;
}

.feature-card {
  @apply bg-dragon-dark-700/50 border border-dragon-dark-600 backdrop-blur-sm
         hover:border-teal-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-teal-500/10;
}

.allocation-card {
  @apply bg-dragon-dark-700/50 border border-dragon-dark-600 backdrop-blur-sm;
}

.metric-card:hover,
.feature-card:hover {
  @apply transform -translate-y-1;
}
</style>

