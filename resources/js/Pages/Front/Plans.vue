<script setup>
import { Link } from "@inertiajs/vue3";
import FrontLayout from "@/Layouts/FrontLayout.vue";
import { ref, onMounted, computed } from "vue";
import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

const BASE_URL = "https://dracoscopia.com";
const billingCycle = ref("monthly");
const plans = ref([]);
const loading = ref(true);
const error = ref(null);
const selectedPlan = ref(null);
const showModal = ref(false);

// Enhanced plan features with images
const planFeatures = {
  "NFT Marketplace": {
    icon: `${BASE_URL}/images/ai/2.png`,
    description: "Create, buy, sell and trade unique digital assets",
  },
  "Gaming Platform": {
    icon: `${BASE_URL}/images/ai/4.png`,
    description: "Access our immersive gaming ecosystem",
  },
  "AI Studio": {
    icon: `${BASE_URL}/images/ai/5.png`,
    description: "Generate unique AI-powered content and NFTs",
  },
  "Social Hub": {
    icon: `${BASE_URL}/images/ai/7.png`,
    description: "Connect with creators and collectors",
  },
  "DeFi Integration": {
    icon: `${BASE_URL}/images/ai/8.png`,
    description: "Access decentralized finance features",
  },
};

const fetchPlans = async () => {
  try {
    const response = await fetch("/api/plans");
    const data = await response.json();
    plans.value = data.map((plan) => ({
      ...plan,
      features: typeof plan.features === "string" ? JSON.parse(plan.features) : plan.features,
    }));
    loading.value = false;
  } catch (e) {
    error.value = "Failed to load plans. Please try again later.";
    loading.value = false;
  }
};

const handleSubscribe = async (plan) => {
  selectedPlan.value = plan;
  showModal.value = true;
};

const processSubscription = async () => {
  try {
    const response = await fetch("/api/subscriptions", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        plan_id: selectedPlan.value.id,
        billing_cycle: billingCycle.value,
      }),
    });
    const data = await response.json();
    if (data.checkout_url) {
      window.location.href = data.checkout_url;
    }
  } catch (e) {
    error.value = "Failed to process subscription. Please try again.";
  }
};

onMounted(() => {
  fetchPlans();

  // Enhanced animations
  gsap.from(".plan-card", {
    scrollTrigger: {
      trigger: ".plan-card",
      start: "top bottom",
      end: "bottom top",
      toggleActions: "play none none reverse",
    },
    y: 50,
    opacity: 0,
    duration: 1,
    stagger: 0.2,
  });

  gsap.from(".feature-icon", {
    scrollTrigger: {
      trigger: ".feature-icon",
      start: "top bottom",
    },
    scale: 0,
    duration: 0.5,
    stagger: 0.1,
  });
});

const getAnnualPrice = (monthlyPrice) => {
  return (monthlyPrice * 12 * 0.8).toFixed(2);
};

const getCurrentPrice = (plan) => {
  return billingCycle.value === "monthly" ? plan.price : getAnnualPrice(plan.price);
};

const getSavings = (plan) => {
  const monthlyTotal = plan.price * 12;
  const annualPrice = getAnnualPrice(plan.price);
  return (monthlyTotal - annualPrice).toFixed(2);
};

const getFeatureDetails = (feature) => {
  return (
    planFeatures[feature] || {
      icon: `${BASE_URL}/images/icons/check.svg`,
      description: feature,
    }
  );
};

const faqs = [
  {
    question: "Can I change my plan later?",
    answer: "Yes, you can upgrade or downgrade your plan at any time. Changes will be reflected in your next billing cycle.",
  },
  {
    question: "What payment methods do you accept?",
    answer: "We accept all major credit cards, PayPal, and various cryptocurrencies including ETH, USDT, and BNB.",
  },
  {
    question: "Is there a free trial?",
    answer: "Yes, all paid plans come with a 14-day free trial. You can explore all features without any commitment.",
  },
  {
    question: "What happens if I exceed my plan limits?",
    answer: "We will notify you when you are approaching your limits. You can upgrade your plan or purchase additional credits as needed.",
  },
  {
    question: "How does the AI image generation work?",
    answer: "Our AI Studio uses advanced algorithms to generate unique images based on your prompts. The number of generations varies by plan.",
  },
  {
    question: "Can I cancel my subscription?",
    answer: "Yes, you can cancel your subscription at any time. You will continue to have access until the end of your billing period.",
  },
  {
    question: "Do you offer custom enterprise solutions?",
    answer: "Yes, we offer tailored enterprise solutions with custom features, dedicated support, and flexible pricing.",
  },
  {
    question: "How do I get started with NFT creation?",
    answer: "Once subscribed, you can access our NFT creation tools through the dashboard. We provide templates and guides to help you get started.",
  },
];
</script>

<template>
  <FrontLayout title="Plans & Pricing | Realmsz Digital Asset Management Platform">
    <div class="min-h-screen bg-gradient-to-b from-dragon-dark-900 to-dragon-dark-800">
      <!-- Hero Section with Enhanced Animation -->
      <section class="relative px-4 py-20 sm:px-6 lg:px-8">
        <div class="absolute inset-0 overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-b from-dragon-dark-900/80 to-dragon-dark-800/50"></div>
        </div>
        <div class="relative mx-auto max-w-7xl">
          <div class="text-center">
            <h1 class="animate-gradient bg-gradient-to-r from-teal-400 via-blue-500 to-purple-600 bg-clip-text text-4xl font-bold text-transparent sm:text-5xl md:text-6xl">Choose Your Plan</h1>
            <p class="mx-auto mt-6 max-w-3xl text-xl text-blue-400 sm:text-2xl">Unlock the full potential of Realmsz with our flexible pricing plans</p>
          </div>
        </div>
      </section>

      <!-- Enhanced Billing Toggle -->
      <div class="mb-12 flex flex-col items-center">
        <div class="mb-4 inline-flex rounded-lg bg-dragon-dark-700 p-1">
          <button @click="billingCycle = 'monthly'" :class="['rounded-md px-6 py-3 text-sm font-medium transition-all duration-300', billingCycle === 'monthly' ? 'bg-dragon-primary text-white shadow-lg shadow-dragon-primary/30' : 'text-gray-400 hover:text-white']">Monthly</button>
          <button @click="billingCycle = 'annual'" :class="['rounded-md px-6 py-3 text-sm font-medium transition-all duration-300', billingCycle === 'annual' ? 'bg-dragon-primary text-white shadow-lg shadow-dragon-primary/30' : 'text-gray-400 hover:text-white']">
            Annual
            <span class="ml-1 text-xs text-teal-400">Save 20%</span>
          </button>
        </div>
        <p class="text-sm text-gray-400">
          {{ billingCycle === "annual" ? "Save more with annual billing" : "Flexible monthly billing" }}
        </p>
      </div>

      <!-- Loading and Error States -->
      <div v-if="loading" class="flex items-center justify-center py-20">
        <div class="h-12 w-12 animate-spin rounded-full border-b-2 border-t-2 border-dragon-primary"></div>
      </div>

      <div v-else-if="error" class="py-20 text-center">
        <p class="text-red-500">{{ error }}</p>
        <button @click="fetchPlans" class="btn-dragon mt-4">Try Again</button>
      </div>

      <!-- Enhanced Plans Grid -->
      <section v-else class="px-4 py-12 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
          <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
            <div v-for="plan in plans" :key="plan.id" class="plan-card relative transform rounded-xl border border-dragon-dark-600 bg-dragon-dark-700/50 p-8 transition-all duration-300 hover:scale-105" :class="{ 'border-dragon-primary shadow-xl shadow-dragon-primary/20': plan.name === 'Pro' }">
              <!-- Popular Badge -->
              <div v-if="plan.name === 'Pro'" class="absolute -right-4 -top-4 rounded-full bg-dragon-primary px-4 py-2 text-sm font-bold text-white shadow-lg">Most Popular</div>

              <h3 class="mb-4 text-2xl font-bold text-white">{{ plan.name }}</h3>

              <!-- Enhanced Price Display -->
              <div class="mb-6">
                <div class="flex items-end">
                  <span class="text-5xl font-bold text-white">${{ getCurrentPrice(plan) }}</span>
                  <span class="mb-1 ml-2 text-gray-400">/{{ billingCycle === "monthly" ? "mo" : "yr" }}</span>
                </div>

                <!-- Free Plan Label -->
                <div v-if="plan.price === 0" class="mt-2">
                  <span class="rounded-full bg-teal-500/20 px-3 py-1 text-sm text-teal-400">Free Forever</span>
                </div>

                <!-- Plan Description -->
                <p class="mt-3 text-gray-400">{{ plan.description }}</p>
              </div>

              <!-- Annual Savings -->
              <div v-if="billingCycle === 'annual' && plan.price > 0" class="mb-6">
                <span class="flex items-center text-sm text-teal-400">
                  <svg class="mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12zm-1-5h2v2H9v-2zm0-6h2v4H9V5z" />
                  </svg>
                  Save ${{ getSavings(plan) }} per year
                </span>
              </div>

              <!-- Enhanced Features -->
              <ul class="mb-8 space-y-4">
                <li v-for="feature in plan.features" :key="feature" class="group flex items-start">
                  <div class="feature-icon mr-3 h-8 w-8 flex-shrink-0 rounded-lg bg-dragon-dark-600 p-1.5">
                    <img :src="getFeatureDetails(feature).icon" :alt="feature" class="h-full w-full rounded object-cover" />
                  </div>
                  <div>
                    <span class="text-gray-300">{{ feature }}</span>
                    <p class="mt-1 text-sm text-gray-500 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                      {{ getFeatureDetails(feature).description }}
                    </p>
                  </div>
                </li>
              </ul>

              <!-- Enhanced CTA Button -->
              <button @click="handleSubscribe(plan)" class="btn-dragon group relative w-full overflow-hidden">
                <span class="relative z-10">{{ plan.price === 0 ? "Get Started" : "Subscribe Now" }}</span>
                <div class="absolute inset-0 translate-y-full transform bg-gradient-to-r from-teal-500 to-blue-500 transition-transform duration-300 group-hover:translate-y-0"></div>
              </button>
            </div>
          </div>
        </div>
      </section>

      <!-- Enhanced FAQ Section -->
      <section class="bg-dragon-dark-800/50 px-4 py-16 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-4xl">
          <h2 class="mb-12 text-center text-3xl font-bold text-white">Frequently Asked Questions</h2>

          <div class="grid gap-6 md:grid-cols-2">
            <div v-for="(faq, index) in faqs" :key="index" class="hover:scale-102 transform rounded-lg bg-dragon-dark-700/50 p-6 transition-all duration-300">
              <h3 class="mb-2 text-xl font-semibold text-white">{{ faq.question }}</h3>
              <p class="text-gray-300">{{ faq.answer }}</p>
            </div>
          </div>
        </div>
      </section>

      <!-- Subscription Modal -->
      <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/75">
        <div class="mx-4 w-full max-w-md rounded-xl bg-dragon-dark-800 p-8">
          <h3 class="mb-4 text-2xl font-bold text-white">Subscribe to {{ selectedPlan?.name }}</h3>
          <p class="mb-6 text-gray-300">You're about to subscribe to the {{ selectedPlan?.name }} plan at ${{ getCurrentPrice(selectedPlan) }}/{{ billingCycle === "monthly" ? "month" : "year" }}.</p>

          <div class="flex justify-end space-x-4">
            <button @click="showModal = false" class="px-4 py-2 text-gray-400 transition-colors hover:text-white">Cancel</button>
            <button @click="processSubscription" class="btn-dragon">Confirm Subscription</button>
          </div>
        </div>
      </div>
    </div>
  </FrontLayout>
</template>

<style scoped lang="pcss">
.btn-dragon {
  @apply inline-flex items-center justify-center rounded-md border border-transparent bg-dragon-primary px-6 py-3 text-base font-medium text-white shadow-lg transition-all duration-300 hover:bg-dragon-primary hover:shadow-dragon-primary/30 focus:outline-none focus:ring-2 focus:ring-dragon-primary focus:ring-offset-2;
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
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}
</style>
