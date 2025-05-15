<script setup>
import { ref, onMounted } from "vue";
import { Link, Head } from "@inertiajs/vue3";
import FrontLayout from "@/Layouts/FrontLayout.vue";
import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Autoplay, EffectFade, Navigation, Pagination } from "swiper/modules";
import "swiper/css";
import "swiper/css/effect-fade";
import "swiper/css/navigation";
import "swiper/css/pagination";

// Import icons
import { ComputerDesktopIcon, SparklesIcon, CurrencyDollarIcon, ShieldCheckIcon, GlobeAltIcon, CubeIcon, ChartBarIcon, UserGroupIcon, ArrowRightIcon } from "@heroicons/vue/24/outline";

const modules = [Autoplay, EffectFade, Navigation, Pagination];

const features = [
  {
    title: "AI-Powered Development",
    description: "Leverage cutting-edge AI for website creation, content generation, and smart contract development.",
    icon: SparklesIcon,
    image: "https://dracoscopia.com/images/ai/3.png",
  },
  {
    title: "Blockchain Integration",
    description: "Comprehensive blockchain integration with support for multiple networks and smart contract deployment.",
    icon: CubeIcon,
    image: "https://dracoscopia.com/com.png",
  },
  {
    title: "Security & Compliance",
    description: "Enterprise-grade security with KYC/AML integration and regulatory compliance for all blockchain operations.",
    icon: ShieldCheckIcon,
    image: "https://dracoscopia.com/new.png",
  },
  {
    title: "NFT Marketplace",
    description: "Create, buy, and sell NFTs with our integrated marketplace. Support for multiple blockchains.",
    icon: CurrencyDollarIcon,
    image: "https://dracoscopia.com/ee.png",
  },
  {
    title: "Web3 Solutions",
    description: "Build and deploy decentralized applications with our comprehensive Web3 development tools.",
    icon: GlobeAltIcon,
    image: "https://dracoscopia.com/tt.png",
  },
  {
    title: "Analytics & Insights",
    description: "Advanced analytics and reporting tools for tracking performance and making data-driven decisions.",
    icon: ChartBarIcon,
    image: "https://dracoscopia.com/images/ai/4.png",
  },
];

const testimonials = [
  {
    name: "Alex Johnson",
    role: "Blockchain Developer",
    content: "Realmsz has revolutionized how we develop and deploy blockchain applications. The AI integration is game-changing.",
    image: "https://dracoscopia.com/tt.png",
  },
  {
    name: "Sarah Chen",
    role: "NFT Artist",
    content: "The NFT marketplace and creation tools are exceptional. It's made my workflow so much more efficient.",
    image: "https://dracoscopia.com/ee.png",
  },
  {
    name: "Michael Rodriguez",
    role: "Web3 Entrepreneur",
    content: "The comprehensive suite of tools and the supportive community have been invaluable for my startup.",
    image: "https://dracoscopia.com/com.png",
  },
];

const teamMembers = [
  {
    name: "Scopia Rewainde",
    role: "CEO",
    image: "https://dracoscopia.com/scopia.png",
    email: "scopia@realmsz.com",
  },
  {
    name: "Dragol Rewainde",
    role: "CTO",
    image: "https://dracoscopia.com/dragol.png",
    email: "dragol@realmsz.com",
  },
  {
    name: "Mila Milai",
    role: "Admin & AI",
    image: "https://dracoscopia.com/milai.png",
    email: "mila@realmsz.com",
  },
];

const plans = [
  {
    name: "Starter",
    price: "$9",
    period: "/month",
    description: "Perfect for individuals and small teams",
    features: ["5 Projects", "10GB Storage", "Basic Analytics", "Email Support", "Community Access"],
    popular: false,
    savings: null,
  },
  {
    name: "Professional",
    price: "$29",
    period: "/month",
    description: "Ideal for growing businesses",
    features: ["Unlimited Projects", "50GB Storage", "Advanced Analytics", "Priority Support", "API Access", "Custom Domain"],
    popular: true,
    savings: "Save 20%",
  },
  {
    name: "Enterprise",
    price: "$99",
    period: "/month",
    description: "For large organizations",
    features: ["Unlimited Projects", "500GB Storage", "Custom Analytics", "24/7 Support", "Advanced API", "Custom Domain", "Dedicated Account Manager", "SLA Guarantee"],
    popular: false,
    savings: "Save 30%",
  },
];

onMounted(() => {
  gsap.registerPlugin(ScrollTrigger);

  // Animate features on scroll
  gsap.utils.toArray(".feature-card").forEach((card, i) => {
    gsap.from(card, {
      scrollTrigger: {
        trigger: card,
        start: "top center+=100",
        toggleActions: "play none none reverse",
      },
      duration: 0.8,
      opacity: 0,
      y: 50,
      delay: i * 0.1,
      ease: "power2.out",
    });
  });
});
</script>

<template>
  <FrontLayout title="Welcome to Realmsz | Digital Asset Management Platform">
    <div class="min-h-screen bg-gradient-to-b from-dragon-dark-900 to-dragon-dark-800">
      <!-- Hero Section with Slider -->
      <section class="relative h-screen">
        <Swiper :modules="modules" :slides-per-view="1" :effect="'fade'" :autoplay="{ delay: 5000, disableOnInteraction: false }" :pagination="{ clickable: true }" :navigation="true" class="h-full">
          <SwiperSlide v-for="(feature, index) in features" :key="index">
            <div class="absolute inset-0">
              <img :src="feature.image" alt="Hero Background" class="h-full w-full object-cover opacity-20" />
              <div class="absolute inset-0 bg-gradient-to-b from-dragon-dark-900/80 to-dragon-dark-800/50"></div>
            </div>
            <div class="relative flex h-full items-center justify-center px-4 text-center sm:px-6 lg:px-8">
              <div class="mx-auto max-w-7xl">
                <h1 class="bg-gradient-to-r from-teal-400 via-blue-500 to-purple-600 bg-clip-text text-4xl font-bold text-transparent sm:text-5xl md:text-6xl">
                  {{ feature.title }}
                </h1>
                <p class="mx-auto mt-6 max-w-3xl text-xl text-blue-400 sm:text-2xl">
                  {{ feature.description }}
                </p>
                <div class="mt-8 flex justify-center gap-4">
                  <Link :href="route('register')" class="btn-dragon"> Get Started </Link>
                  <Link :href="route('contact')" class="btn-dragon-outline"> Learn More </Link>
                </div>
              </div>
            </div>
          </SwiperSlide>
        </Swiper>
      </section>

      <!-- Features Section -->
      <section class="relative px-4 py-20 sm:px-6 lg:px-8">
        <div class="absolute inset-0 overflow-hidden">
          <div class="absolute inset-0 z-0">
            <img :src="'https://dracoscopia.com/images/ai/9.png'" alt="Features Background" class="h-full w-full object-cover opacity-10" />
          </div>
        </div>
        <div class="relative z-10 mx-auto max-w-7xl">
          <h2 class="mb-12 text-center text-3xl font-bold text-white">Our Features</h2>
          <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
            <div v-for="feature in features" :key="feature.title" class="feature-card">
              <div class="aspect-h-9 aspect-w-16 mb-6">
                <img :src="feature.image" :alt="feature.title" class="h-full w-full rounded-lg object-cover" />
              </div>
              <div class="p-6">
                <component :is="feature.icon" class="mb-4 h-8 w-8 text-teal-400" />
                <h3 class="mb-2 text-xl font-semibold text-white">{{ feature.title }}</h3>
                <p class="text-gray-400">{{ feature.description }}</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Testimonials Section -->
      <section class="relative bg-dragon-dark-800/50 px-4 py-20 sm:px-6 lg:px-8">
        <div class="absolute inset-0 overflow-hidden">
          <div class="absolute inset-0 z-0">
            <img src="https://dracoscopia.com/images/backgrounds/background03.jpg" alt="Testimonials Background" class="h-full w-full object-cover opacity-10" />
          </div>
        </div>
        <div class="relative z-10 mx-auto max-w-7xl">
          <h2 class="mb-12 text-center text-3xl font-bold text-white">What Our Users Say</h2>
          <Swiper
            :modules="modules"
            :slides-per-view="1"
            :space-between="30"
            :autoplay="{ delay: 5000, disableOnInteraction: false }"
            :pagination="{ clickable: true }"
            :breakpoints="{
              640: { slidesPerView: 2 },
              1024: { slidesPerView: 3 },
            }"
            class="pb-12"
          >
            <SwiperSlide v-for="testimonial in testimonials" :key="testimonial.name">
              <div class="rounded-xl border border-dragon-dark-600 bg-dragon-dark-700/50 p-6">
                <div class="mb-4 flex items-center gap-4">
                  <img :src="testimonial.image" :alt="testimonial.name" class="h-12 w-12 rounded-full" />
                  <div>
                    <h4 class="font-semibold text-white">{{ testimonial.name }}</h4>
                    <p class="text-sm text-teal-400">{{ testimonial.role }}</p>
                  </div>
                </div>
                <p class="text-gray-400">{{ testimonial.content }}</p>
              </div>
            </SwiperSlide>
          </Swiper>
        </div>
      </section>

      <!-- Add this section before the CTA section -->
      <section class="bg-dragon-dark-800/50 py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div class="text-center">
            <h2 class="text-3xl font-bold text-white sm:text-4xl">Simple, Transparent Pricing</h2>
            <p class="mt-4 text-xl text-gray-400">Choose the perfect plan for your needs</p>
          </div>
          <div class="mt-16 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
            <div v-for="plan in plans" :key="plan.name" class="transform rounded-xl border border-dragon-dark-600 bg-dragon-dark-700/50 p-8 backdrop-blur-sm transition-all duration-300 hover:-translate-y-1" :class="{ 'ring-2 ring-teal-500': plan.popular }">
              <div class="text-center">
                <h3 class="text-2xl font-bold text-white">{{ plan.name }}</h3>
                <div class="mt-4 flex items-center justify-center">
                  <span class="text-4xl font-bold text-white">{{ plan.price }}</span>
                  <span class="ml-2 text-gray-400">{{ plan.period }}</span>
                </div>
                <p class="mt-2 text-gray-400">{{ plan.description }}</p>
                <div v-if="plan.savings" class="mt-2">
                  <span class="inline-flex items-center rounded-full bg-teal-500/10 px-3 py-1 text-sm font-medium text-teal-400">
                    {{ plan.savings }}
                  </span>
                </div>
              </div>
              <div class="mt-8 space-y-4">
                <div v-for="feature in plan.features" :key="feature" class="flex items-center text-gray-400">
                  <svg class="mr-2 h-5 w-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                  {{ feature }}
                </div>
              </div>
              <div class="mt-8 text-center">
                <Link :href="route('register')" class="inline-flex w-full items-center justify-center rounded-lg border border-transparent bg-teal-500 px-6 py-3 text-base font-medium text-white transition-all duration-300 hover:bg-teal-600 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                  Get Started
                  <ArrowRightIcon class="ml-2 h-5 w-5" />
                </Link>
              </div>
            </div>
          </div>
          <div class="mt-12 text-center">
            <p class="text-gray-400">Need a custom plan? <Link :href="route('contact')" class="text-teal-400 hover:text-teal-300">Contact us</Link></p>
          </div>
        </div>
      </section>

      <!-- CTA Section -->
      <section class="relative px-4 py-20 sm:px-6 lg:px-8">
        <div class="absolute inset-0 overflow-hidden">
          <div class="absolute inset-0 z-0">
            <img src="https://dracoscopia.com/images/backgrounds/background04.jpg" alt="CTA Background" class="h-full w-full object-cover opacity-10" />
          </div>
        </div>
        <div class="relative z-10 mx-auto max-w-4xl text-center">
          <h2 class="mb-6 text-3xl font-bold text-white sm:text-4xl">Ready to Get Started?</h2>
          <p class="mx-auto mb-10 max-w-2xl text-xl text-gray-400">Join our community of innovators and start building the future of blockchain technology</p>
          <div class="flex flex-col justify-center gap-4 sm:flex-row">
            <Link :href="route('register')" class="btn-dragon"> Start Free Trial </Link>
            <Link :href="route('contact')" class="btn-dragon-outline"> Contact Us </Link>
          </div>
        </div>
      </section>
    </div>
  </FrontLayout>
</template>

<style scoped>
.btn-dragon {
  @apply transform rounded-lg bg-gradient-to-r from-teal-500 to-blue-600 px-6 py-3 font-semibold text-white shadow-xl transition-all duration-300 hover:-translate-y-0.5 hover:from-teal-600 hover:to-blue-700 hover:shadow-teal-500/25;
}

.btn-dragon-outline {
  @apply transform rounded-lg border-2 border-teal-500 px-6 py-3 font-semibold text-teal-500 transition-all duration-300 hover:-translate-y-0.5 hover:border-teal-400 hover:bg-teal-500/10 hover:text-teal-400;
}

.feature-card {
  @apply transform border border-dragon-dark-600 bg-dragon-dark-700/50 backdrop-blur-sm transition-all duration-300 hover:-translate-y-1 hover:border-teal-500/50 hover:shadow-lg hover:shadow-teal-500/10;
}

:deep(.swiper-button-next),
:deep(.swiper-button-prev) {
  @apply text-teal-400 hover:text-teal-300;
}

:deep(.swiper-pagination-bullet) {
  @apply bg-gray-400;
}

:deep(.swiper-pagination-bullet-active) {
  @apply bg-teal-400;
}
</style>

