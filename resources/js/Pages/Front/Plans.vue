<script setup>
import { Link } from '@inertiajs/vue3';
import FrontLayout from '@/Layouts/FrontLayout.vue';
import { ref, onMounted, computed } from 'vue';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const BASE_URL = 'https://dracoscopia.com';
const billingCycle = ref('monthly');
const plans = ref([]);
const loading = ref(true);
const error = ref(null);
const selectedPlan = ref(null);
const showModal = ref(false);

// Enhanced plan features with images
const planFeatures = {
  'NFT Marketplace': {
    icon: `${BASE_URL}/images/ai/2.png`,
    description: 'Create, buy, sell and trade unique digital assets'
  },
  'Gaming Platform': {
    icon: `${BASE_URL}/images/ai/4.png`,
    description: 'Access our immersive gaming ecosystem'
  },
  'AI Studio': {
    icon: `${BASE_URL}/images/ai/5.png`,
    description: 'Generate unique AI-powered content and NFTs'
  },
  'Social Hub': {
    icon: `${BASE_URL}/images/ai/7.png`,
    description: 'Connect with creators and collectors'
  },
  'DeFi Integration': {
    icon: `${BASE_URL}/images/ai/8.png`,
    description: 'Access decentralized finance features'
  }
};

const fetchPlans = async () => {
  try {
    const response = await fetch('/api/plans');
    const data = await response.json();
    plans.value = data.map(plan => ({
      ...plan,
      features: typeof plan.features === 'string' ? JSON.parse(plan.features) : plan.features
    }));
    loading.value = false;
  } catch (e) {
    error.value = 'Failed to load plans. Please try again later.';
    loading.value = false;
  }
};

const handleSubscribe = async (plan) => {
  selectedPlan.value = plan;
  showModal.value = true;
};

const processSubscription = async () => {
  try {
    const response = await fetch('/api/subscriptions', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        plan_id: selectedPlan.value.id,
        billing_cycle: billingCycle.value
      })
    });
    const data = await response.json();
    if (data.checkout_url) {
      window.location.href = data.checkout_url;
    }
  } catch (e) {
    error.value = 'Failed to process subscription. Please try again.';
  }
};

onMounted(() => {
  fetchPlans();
  
  // Enhanced animations
  gsap.from('.plan-card', {
    scrollTrigger: {
      trigger: '.plan-card',
      start: 'top bottom',
      end: 'bottom top',
      toggleActions: 'play none none reverse'
    },
    y: 50,
    opacity: 0,
    duration: 1,
    stagger: 0.2
  });

  gsap.from('.feature-icon', {
    scrollTrigger: {
      trigger: '.feature-icon',
      start: 'top bottom',
    },
    scale: 0,
    duration: 0.5,
    stagger: 0.1
  });
});

const getAnnualPrice = (monthlyPrice) => {
  return (monthlyPrice * 12 * 0.8).toFixed(2);
};

const getCurrentPrice = (plan) => {
  return billingCycle.value === 'monthly' ? plan.price : getAnnualPrice(plan.price);
};

const getSavings = (plan) => {
  const monthlyTotal = plan.price * 12;
  const annualPrice = getAnnualPrice(plan.price);
  return (monthlyTotal - annualPrice).toFixed(2);
};

const getFeatureDetails = (feature) => {
  return planFeatures[feature] || { 
    icon: `${BASE_URL}/images/icons/check.svg`,
    description: feature 
  };
};

const faqs = [
  {
    question: 'Can I change my plan later?',
    answer: 'Yes, you can upgrade or downgrade your plan at any time. Changes will be reflected in your next billing cycle.'
  },
  {
    question: 'What payment methods do you accept?',
    answer: 'We accept all major credit cards, PayPal, and various cryptocurrencies including ETH, USDT, and BNB.'
  },
  {
    question: 'Is there a free trial?',
    answer: 'Yes, all paid plans come with a 14-day free trial. You can explore all features without any commitment.'
  },
  {
    question: 'What happens if I exceed my plan limits?',
    answer: 'We'll notify you when you're approaching your limits. You can upgrade your plan or purchase additional credits as needed.'
  },
  {
    question: 'How does the AI image generation work?',
    answer: 'Our AI Studio uses advanced algorithms to generate unique images based on your prompts. The number of generations varies by plan.'
  },
  {
    question: 'Can I cancel my subscription?',
    answer: 'Yes, you can cancel your subscription at any time. You'll continue to have access until the end of your billing period.'
  },
  {
    question: 'Do you offer custom enterprise solutions?',
    answer: 'Yes, we offer tailored enterprise solutions with custom features, dedicated support, and flexible pricing.'
  },
  {
    question: 'How do I get started with NFT creation?',
    answer: 'Once subscribed, you can access our NFT creation tools through the dashboard. We provide templates and guides to help you get started.'
  }
];
</script>

<template>
  <FrontLayout>
    <div class="min-h-screen bg-gradient-to-b from-dragon-dark-900 to-dragon-dark-800">
      <!-- Hero Section with Enhanced Animation -->
      <section class="relative py-20 px-4 sm:px-6 lg:px-8">
        <div class="absolute inset-0 overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-b from-dragon-dark-900/80 to-dragon-dark-800/50"></div>
        </div>
        <div class="relative max-w-7xl mx-auto">
          <div class="text-center">
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold bg-gradient-to-r from-teal-400 via-blue-500 to-purple-600 bg-clip-text text-transparent animate-gradient">
              Choose Your Plan
            </h1>
            <p class="mt-6 text-xl sm:text-2xl text-blue-400 max-w-3xl mx-auto">
              Unlock the full potential of Realmsz with our flexible pricing plans
            </p>
          </div>
        </div>
      </section>

      <!-- Enhanced Billing Toggle -->
      <div class="flex flex-col items-center mb-12">
        <div class="inline-flex rounded-lg bg-dragon-dark-700 p-1 mb-4">
          <button
            @click="billingCycle = 'monthly'"
            :class="[
              'px-6 py-3 rounded-md text-sm font-medium transition-all duration-300',
              billingCycle === 'monthly'
                ? 'bg-dragon-primary text-white shadow-lg shadow-dragon-primary/30'
                : 'text-gray-400 hover:text-white'
            ]"
          >
            Monthly
          </button>
          <button
            @click="billingCycle = 'annual'"
            :class="[
              'px-6 py-3 rounded-md text-sm font-medium transition-all duration-300',
              billingCycle === 'annual'
                ? 'bg-dragon-primary text-white shadow-lg shadow-dragon-primary/30'
                : 'text-gray-400 hover:text-white'
            ]"
          >
            Annual
            <span class="ml-1 text-xs text-teal-400">Save 20%</span>
          </button>
        </div>
        <p class="text-gray-400 text-sm">
          {{ billingCycle === 'annual' ? 'Save more with annual billing' : 'Flexible monthly billing' }}
        </p>
      </div>

      <!-- Loading and Error States -->
      <div v-if="loading" class="flex justify-center items-center py-20">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-dragon-primary"></div>
      </div>

      <div v-else-if="error" class="text-center py-20">
        <p class="text-red-500">{{ error }}</p>
        <button @click="fetchPlans" class="mt-4 btn-dragon">
          Try Again
        </button>
      </div>

      <!-- Enhanced Plans Grid -->
      <section v-else class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div
              v-for="plan in plans"
              :key="plan.id"
              class="plan-card relative bg-dragon-dark-700/50 rounded-xl p-8 border border-dragon-dark-600 transform hover:scale-105 transition-all duration-300"
              :class="{ 'border-dragon-primary shadow-xl shadow-dragon-primary/20': plan.name === 'Pro' }"
            >
              <!-- Popular Badge -->
              <div
                v-if="plan.name === 'Pro'"
                class="absolute -top-4 -right-4 bg-dragon-primary text-white text-sm font-bold px-4 py-2 rounded-full shadow-lg"
              >
                Most Popular
              </div>

              <h3 class="text-2xl font-bold text-white mb-4">{{ plan.name }}</h3>
              
              <!-- Enhanced Price Display -->
              <div class="mb-6">
                <div class="flex items-end">
                  <span class="text-5xl font-bold text-white">${{ getCurrentPrice(plan) }}</span>
                  <span class="text-gray-400 ml-2 mb-1">/{{ billingCycle === 'monthly' ? 'mo' : 'yr' }}</span>
                </div>
                
                <!-- Free Plan Label -->
                <div v-if="plan.price === 0" class="mt-2">
                  <span class="bg-teal-500/20 text-teal-400 px-3 py-1 rounded-full text-sm">Free Forever</span>
                </div>

                <!-- Plan Description -->
                <p class="text-gray-400 mt-3">{{ plan.description }}</p>
              </div>

              <!-- Annual Savings -->
              <div v-if="billingCycle === 'annual' && plan.price > 0" class="mb-6">
                <span class="text-teal-400 text-sm flex items-center">
                  <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12zm-1-5h2v2H9v-2zm0-6h2v4H9V5z"/>
                  </svg>
                  Save ${{ getSavings(plan) }} per year
                </span>
              </div>

              <!-- Enhanced Features -->
              <ul class="space-y-4 mb-8">
                <li
                  v-for="feature in plan.features"
                  :key="feature"
                  class="flex items-start group"
                >
                  <div class="feature-icon w-8 h-8 rounded-lg bg-dragon-dark-600 p-1.5 mr-3 flex-shrink-0">
                    <img 
                      :src="getFeatureDetails(feature).icon"
                      :alt="feature"
                      class="w-full h-full object-cover rounded"
                    />
                  </div>
                  <div>
                    <span class="text-gray-300">{{ feature }}</span>
                    <p class="text-sm text-gray-500 mt-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                      {{ getFeatureDetails(feature).description }}
                    </p>
                  </div>
                </li>
              </ul>

              <!-- Enhanced CTA Button -->
              <button
                @click="handleSubscribe(plan)"
                class="w-full btn-dragon group relative overflow-hidden"
              >
                <span class="relative z-10">{{ plan.price === 0 ? 'Get Started' : 'Subscribe Now' }}</span>
                <div class="absolute inset-0 bg-gradient-to-r from-teal-500 to-blue-500 transform translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
              </button>
            </div>
          </div>
        </div>
      </section>

      <!-- Enhanced FAQ Section -->
      <section class="py-16 px-4 sm:px-6 lg:px-8 bg-dragon-dark-800/50">
        <div class="max-w-4xl mx-auto">
          <h2 class="text-3xl font-bold text-white mb-12 text-center">Frequently Asked Questions</h2>
          
          <div class="grid gap-6 md:grid-cols-2">
            <div
              v-for="(faq, index) in faqs"
              :key="index"
              class="bg-dragon-dark-700/50 rounded-lg p-6 transform hover:scale-102 transition-all duration-300"
            >
              <h3 class="text-xl font-semibold text-white mb-2">{{ faq.question }}</h3>
              <p class="text-gray-300">{{ faq.answer }}</p>
            </div>
          </div>
        </div>
      </section>

      <!-- Subscription Modal -->
      <div v-if="showModal" class="fixed inset-0 bg-black/75 flex items-center justify-center z-50">
        <div class="bg-dragon-dark-800 rounded-xl p-8 max-w-md w-full mx-4">
          <h3 class="text-2xl font-bold text-white mb-4">Subscribe to {{ selectedPlan?.name }}</h3>
          <p class="text-gray-300 mb-6">
            You're about to subscribe to the {{ selectedPlan?.name }} plan at 
            ${{ getCurrentPrice(selectedPlan) }}/{{ billingCycle === 'monthly' ? 'month' : 'year' }}.
          </p>
          
          <div class="flex justify-end space-x-4">
            <button
              @click="showModal = false"
              class="px-4 py-2 text-gray-400 hover:text-white transition-colors"
            >
              Cancel
            </button>
            <button
              @click="processSubscription"
              class="btn-dragon"
            >
              Confirm Subscription
            </button>
          </div>
        </div>
      </div>
    </div>
  </FrontLayout>
</template>

<style scoped>
.btn-dragon {
  @apply inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-dragon-primary hover:bg-dragon-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-dragon-primary transition-all duration-300 shadow-lg hover:shadow-dragon-primary/30;
}

.scale-102 {
  --tw-scale-x: 1.02;
  --tw-scale-y: 1.02;
}

.animate-gradient {
  background-size: 200% 200%;
  animation: gradient 8s ease infinite;
}

@keyframes gradient {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}
</style>