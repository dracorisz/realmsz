<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import FrontLayout from '@/Layouts/FrontLayout.vue';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const shareholderBenefits = [
  'Voting rights in company decisions',
  'Priority access to new features and products',
  'Exclusive shareholder events and meetings',
  'Regular dividend payments',
  'Access to detailed financial reports'
];

const growthPotential = [
  'Expanding global market presence',
  'Innovative product pipeline',
  'Strategic partnerships and acquisitions',
  'Strong revenue growth projections',
  'Industry-leading technology platform'
];

const offeringDetails = {
  companyName: 'Realmsz Technologies',
  tickerSymbol: 'RLMZ',
  offeringType: 'Initial Public Offering',
  sharesOffered: '10,000,000',
  priceRange: '$10 - $12',
  totalValue: '$100,000,000 - $120,000,000',
  exchange: 'NASDAQ',
  expectedDate: 'Q4 2026'
};

const useOfProceeds = [
  {
    category: 'Platform Development',
    percentage: '40%',
    amount: '$40,000,000 - $48,000,000',
    description: 'Core platform features and infrastructure expansion'
  },
  {
    category: 'Market Expansion',
    percentage: '25%',
    amount: '$25,000,000 - $30,000,000',
    description: 'Global market penetration and user acquisition'
  },
  {
    category: 'Research & Development',
    percentage: '20%',
    amount: '$20,000,000 - $24,000,000',
    description: 'Innovation and new technology development'
  },
  {
    category: 'Working Capital',
    percentage: '15%',
    amount: '$15,000,000 - $18,000,000',
    description: 'General corporate purposes and operations'
  }
];

const keyMetrics = [
  {
    title: 'Market Opportunity',
    value: '$1T+',
    description: 'Total addressable market in digital assets and blockchain'
  },
  {
    title: 'User Growth',
    value: '300%',
    description: 'Year-over-year user base growth'
  },
  {
    title: 'Revenue Growth',
    value: '250%',
    description: 'Year-over-year revenue growth'
  },
  {
    title: 'Platform Users',
    value: '5M+',
    description: 'Active users on the platform'
  }
];

const teamMembers = [
  {
    name: 'John Smith',
    role: 'CEO & Founder',
    image: 'https://randomuser.me/api/portraits/men/32.jpg'
  },
  {
    name: 'Sarah Johnson',
    role: 'CTO',
    image: 'https://randomuser.me/api/portraits/women/44.jpg'
  },
  {
    name: 'Michael Chen',
    role: 'Head of Product',
    image: 'https://randomuser.me/api/portraits/men/67.jpg'
  },
  {
    name: 'Emily Davis',
    role: 'Head of Marketing',
    image: 'https://randomuser.me/api/portraits/women/28.jpg'
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

  // Animate cards
  gsap.from('.card-dragon', {
    y: 50,
    opacity: 0,
    duration: 0.8,
    stagger: 0.2,
    ease: 'power3.out',
    scrollTrigger: {
      trigger: '.card-dragon',
      start: 'top bottom-=100',
      toggleActions: 'play none none reverse'
    }
  });

  // Animate allocation circles
  gsap.from('.allocation-circle', {
    scale: 0,
    opacity: 0,
    duration: 0.5,
    stagger: 0.2,
    ease: 'back.out(1.7)',
    scrollTrigger: {
      trigger: '.allocation-circle',
      start: 'top bottom-=100',
      toggleActions: 'play none none reverse'
    }
  });
});
</script>

<template>
  <FrontLayout>
    <div class="min-h-screen bg-gradient-to-b from-dragon-dark-900 to-dragon-dark-800">
      <!-- Hero Section -->
      <section class="relative py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto text-center">
          <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold bg-gradient-to-r from-teal-400 via-blue-500 to-purple-600 bg-clip-text text-transparent">
            Initial Public Offering
          </h1>
          <p class="mt-6 text-xl sm:text-2xl text-blue-400 max-w-3xl mx-auto">
            Join us in our journey to revolutionize the digital asset space
          </p>
        </div>
      </section>

      <!-- Offering Details -->
      <section class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
          <h2 class="text-3xl font-bold text-white text-center mb-12">Offering Details</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-dragon-dark-700/50 p-6 rounded-xl border border-dragon-dark-600">
              <h3 class="text-xl font-semibold text-white mb-4">Company Information</h3>
              <div class="space-y-4">
                <div class="flex justify-between">
                  <span class="text-gray-400">Company Name</span>
                  <span class="text-teal-400">{{ offeringDetails.companyName }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-400">Ticker Symbol</span>
                  <span class="text-teal-400">{{ offeringDetails.tickerSymbol }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-400">Offering Type</span>
                  <span class="text-teal-400">{{ offeringDetails.offeringType }}</span>
                </div>
              </div>
            </div>
            
            <div class="bg-dragon-dark-700/50 p-6 rounded-xl border border-dragon-dark-600">
              <h3 class="text-xl font-semibold text-white mb-4">Offering Information</h3>
              <div class="space-y-4">
                <div class="flex justify-between">
                  <span class="text-gray-400">Shares Offered</span>
                  <span class="text-teal-400">{{ offeringDetails.sharesOffered }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-400">Price Range</span>
                  <span class="text-teal-400">{{ offeringDetails.priceRange }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-400">Total Value</span>
                  <span class="text-teal-400">{{ offeringDetails.totalValue }}</span>
                </div>
              </div>
            </div>
            
            <div class="bg-dragon-dark-700/50 p-6 rounded-xl border border-dragon-dark-600">
              <h3 class="text-xl font-semibold text-white mb-4">Listing Details</h3>
              <div class="space-y-4">
                <div class="flex justify-between">
                  <span class="text-gray-400">Exchange</span>
                  <span class="text-teal-400">{{ offeringDetails.exchange }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-400">Expected Date</span>
                  <span class="text-teal-400">{{ offeringDetails.expectedDate }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Use of Proceeds -->
      <section class="py-20 px-4 sm:px-6 lg:px-8 bg-dragon-dark-800/50">
        <div class="max-w-7xl mx-auto">
          <h2 class="text-3xl font-bold text-white text-center mb-12">Use of Proceeds</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div v-for="item in useOfProceeds" :key="item.category" 
                 class="bg-dragon-dark-700/50 p-6 rounded-xl border border-dragon-dark-600">
              <h3 class="text-xl font-semibold text-white mb-2">{{ item.category }}</h3>
              <div class="flex justify-between items-center mb-4">
                <span class="text-3xl font-bold text-teal-400">{{ item.percentage }}</span>
                <span class="text-gray-400">{{ item.amount }}</span>
              </div>
              <p class="text-gray-400">{{ item.description }}</p>
            </div>
          </div>
        </div>
      </section>

      <!-- Key Metrics -->
      <section class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
          <h2 class="text-3xl font-bold text-white text-center mb-12">Key Metrics</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div v-for="metric in keyMetrics" :key="metric.title" 
                 class="bg-dragon-dark-700/50 p-6 rounded-xl border border-dragon-dark-600">
              <h3 class="text-xl font-semibold text-white mb-2">{{ metric.title }}</h3>
              <div class="text-3xl font-bold text-teal-400 mb-4">{{ metric.value }}</div>
              <p class="text-gray-400">{{ metric.description }}</p>
            </div>
          </div>
        </div>
      </section>

      <!-- Team Section -->
      <section class="py-20 px-4 sm:px-6 lg:px-8 bg-dragon-dark-800/50">
        <div class="max-w-7xl mx-auto">
          <h2 class="text-3xl font-bold text-white text-center mb-12">Management Team</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div v-for="member in teamMembers" :key="member.name" 
                 class="bg-dragon-dark-700/50 p-6 rounded-xl border border-dragon-dark-600">
              <div class="aspect-w-1 aspect-h-1 mb-6">
                <img :src="member.image" :alt="member.name" 
                     class="w-full h-full object-cover rounded-lg">
              </div>
              
              <h3 class="text-xl font-semibold text-white mb-2">{{ member.name }}</h3>
              <p class="text-teal-400">{{ member.role }}</p>
            </div>
          </div>
        </div>
      </section>

      <!-- CTA Section -->
      <section class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
          <h2 class="text-3xl sm:text-4xl font-bold text-white mb-6">
            Interested in Our IPO?
          </h2>
          <p class="text-xl text-gray-400 mb-10 max-w-2xl mx-auto">
            Contact our investor relations team for more information
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <Link
              :href="route('contact')"
              class="btn-dragon"
            >
              Contact Investor Relations
            </Link>
            <a
              href="mailto:scopia@realmsz.com"
              class="btn-dragon-outline"
            >
              Email Investor Relations
            </a>
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

.card-dragon {
  @apply bg-dragon-dark-700/50 border border-dragon-dark-600 backdrop-blur-sm
         hover:border-teal-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-teal-500/10;
}
</style>

