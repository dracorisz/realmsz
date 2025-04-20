<script setup>
import { ref, onMounted } from "vue";
import { Link, Head } from "@inertiajs/vue3";
import FrontLayout from "@/Layouts/FrontLayout.vue";
import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay, EffectFade, Navigation, Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/effect-fade';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

// Import icons
import { 
  ComputerDesktopIcon,
  SparklesIcon,
  CurrencyDollarIcon,
  ShieldCheckIcon,
  GlobeAltIcon,
  CubeIcon,
  ChartBarIcon,
  UserGroupIcon,
  ArrowRightIcon
} from '@heroicons/vue/24/outline';

const modules = [Autoplay, EffectFade, Navigation, Pagination];

const features = [
  {
    title: 'AI-Powered Development',
    description: 'Leverage cutting-edge AI for website creation, content generation, and smart contract development.',
    icon: SparklesIcon,
    image: 'https://dracoscopia.com/images/ai/3.png'
  },
  {
    title: 'Blockchain Integration',
    description: 'Comprehensive blockchain integration with support for multiple networks and smart contract deployment.',
    icon: CubeIcon,
    image: 'https://dracoscopia.com/com.png'
  },
  {
    title: 'Security & Compliance',
    description: 'Enterprise-grade security with KYC/AML integration and regulatory compliance for all blockchain operations.',
    icon: ShieldCheckIcon,
    image: 'https://dracoscopia.com/new.png'
  },
  {
    title: 'NFT Marketplace',
    description: 'Create, buy, and sell NFTs with our integrated marketplace. Support for multiple blockchains.',
    icon: CurrencyDollarIcon,
    image: 'https://dracoscopia.com/ee.png'
  },
  {
    title: 'Web3 Solutions',
    description: 'Build and deploy decentralized applications with our comprehensive Web3 development tools.',
    icon: GlobeAltIcon,
    image: 'https://dracoscopia.com/tt.png'
  },
  {
    title: 'Analytics & Insights',
    description: 'Advanced analytics and reporting tools for tracking performance and making data-driven decisions.',
    icon: ChartBarIcon,
    image: 'https://dracoscopia.com/images/ai/4.png'
  }
];

const testimonials = [
  {
    name: 'Alex Johnson',
    role: 'Blockchain Developer',
    content: 'Realmsz has revolutionized how we develop and deploy blockchain applications. The AI integration is game-changing.',
    image: 'https://dracoscopia.com/tt.png'
  },
  {
    name: 'Sarah Chen',
    role: 'NFT Artist',
    content: 'The NFT marketplace and creation tools are exceptional. It\'s made my workflow so much more efficient.',
    image: 'https://dracoscopia.com/ee.png'
  },
  {
    name: 'Michael Rodriguez',
    role: 'Web3 Entrepreneur',
    content: 'The comprehensive suite of tools and the supportive community have been invaluable for my startup.',
    image: 'https://dracoscopia.com/com.png'
  }
];

const teamMembers = [
  {
    name: 'Scopia Rewainde',
    role: 'CEO',
    image: 'https://dracoscopia.com/scopia.png',
    email: 'scopia@realmsz.com'
  },
  {
    name: 'Dragol Rewainde',
    role: 'CTO',
    image: 'https://dracoscopia.com/dragol.png',
    email: 'dragol@realmsz.com'
  },
  {
    name: 'Mila Milai',
    role: 'Admin & AI',
    image: 'https://dracoscopia.com/milai.png',
    email: 'mila@realmsz.com'
  }
];

const plans = [
  {
    name: 'Starter',
    price: '$9',
    period: '/month',
    description: 'Perfect for individuals and small teams',
    features: [
      '5 Projects',
      '10GB Storage',
      'Basic Analytics',
      'Email Support',
      'Community Access'
    ],
    popular: false,
    savings: null
  },
  {
    name: 'Professional',
    price: '$29',
    period: '/month',
    description: 'Ideal for growing businesses',
    features: [
      'Unlimited Projects',
      '50GB Storage',
      'Advanced Analytics',
      'Priority Support',
      'API Access',
      'Custom Domain'
    ],
    popular: true,
    savings: 'Save 20%'
  },
  {
    name: 'Enterprise',
    price: '$99',
    period: '/month',
    description: 'For large organizations',
    features: [
      'Unlimited Projects',
      '500GB Storage',
      'Custom Analytics',
      '24/7 Support',
      'Advanced API',
      'Custom Domain',
      'Dedicated Account Manager',
      'SLA Guarantee'
    ],
    popular: false,
    savings: 'Save 30%'
  }
];

onMounted(() => {
  gsap.registerPlugin(ScrollTrigger);

  // Animate features on scroll
  gsap.utils.toArray('.feature-card').forEach((card, i) => {
    gsap.from(card, {
      scrollTrigger: {
        trigger: card,
        start: 'top center+=100',
        toggleActions: 'play none none reverse'
      },
      duration: 0.8,
      opacity: 0,
      y: 50,
      delay: i * 0.1,
      ease: 'power2.out'
    });
  });
});
</script>

<template>
  <FrontLayout title="Welcome to Realmsz | Digital Asset Management Platform">
    <div class="min-h-screen bg-gradient-to-b from-dragon-dark-900 to-dragon-dark-800">
      <!-- Hero Section with Slider -->
      <section class="relative h-screen">
        <Swiper
          :modules="modules"
          :slides-per-view="1"
          :effect="'fade'"
          :autoplay="{ delay: 5000, disableOnInteraction: false }"
          :pagination="{ clickable: true }"
          :navigation="true"
          class="h-full"
        >
          <SwiperSlide v-for="(feature, index) in features" :key="index">
            <div class="absolute inset-0">
              <img :src="feature.image" alt="Hero Background" class="w-full h-full object-cover opacity-20" />
              <div class="absolute inset-0 bg-gradient-to-b from-dragon-dark-900/80 to-dragon-dark-800/50"></div>
            </div>
            <div class="relative h-full flex items-center justify-center text-center px-4 sm:px-6 lg:px-8">
              <div class="max-w-7xl mx-auto">
                <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold bg-gradient-to-r from-teal-400 via-blue-500 to-purple-600 bg-clip-text text-transparent">
                  {{ feature.title }}
                </h1>
                <p class="mt-6 text-xl sm:text-2xl text-blue-400 max-w-3xl mx-auto">
                  {{ feature.description }}
                </p>
                <div class="mt-8 flex justify-center gap-4">
                  <Link :href="route('register')" class="btn-dragon">
                    Get Started
                  </Link>
                  <Link :href="route('contact')" class="btn-dragon-outline">
                    Learn More
                  </Link>
                </div>
              </div>
            </div>
          </SwiperSlide>
        </Swiper>
      </section>

      <!-- Features Section -->
      <section class="relative py-20 px-4 sm:px-6 lg:px-8">
          <div class="absolute inset-0 overflow-hidden">
          <div class="absolute inset-0 z-0">
            <img :src="'images/ai/9.png'" alt="Features Background" class="w-full h-full object-cover opacity-10" />
          </div>
        </div>
        <div class="max-w-7xl mx-auto relative z-10">
          <h2 class="text-3xl font-bold text-white text-center mb-12">Our Features</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div v-for="feature in features" :key="feature.title" class="feature-card">
              <div class="aspect-w-16 aspect-h-9 mb-6">
                <img :src="feature.image" :alt="feature.title" class="w-full h-full object-cover rounded-lg" />
              </div>
              <div class="p-6">
                <component :is="feature.icon" class="h-8 w-8 text-teal-400 mb-4" />
                <h3 class="text-xl font-semibold text-white mb-2">{{ feature.title }}</h3>
                <p class="text-gray-400">{{ feature.description }}</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Testimonials Section -->
      <section class="relative py-20 px-4 sm:px-6 lg:px-8 bg-dragon-dark-800/50">
        <div class="absolute inset-0 overflow-hidden">
          <div class="absolute inset-0 z-0">
            <img src="https://dracoscopia.com/images/backgrounds/background03.jpg" alt="Testimonials Background" class="w-full h-full object-cover opacity-10" />
          </div>
        </div>
        <div class="max-w-7xl mx-auto relative z-10">
          <h2 class="text-3xl font-bold text-white text-center mb-12">What Our Users Say</h2>
          <Swiper
            :modules="modules"
            :slides-per-view="1"
            :space-between="30"
            :autoplay="{ delay: 5000, disableOnInteraction: false }"
            :pagination="{ clickable: true }"
            :breakpoints="{
              640: { slidesPerView: 2 },
              1024: { slidesPerView: 3 }
            }"
            class="pb-12"
          >
            <SwiperSlide v-for="testimonial in testimonials" :key="testimonial.name">
              <div class="bg-dragon-dark-700/50 p-6 rounded-xl border border-dragon-dark-600">
                <div class="flex items-center gap-4 mb-4">
                  <img :src="testimonial.image" :alt="testimonial.name" class="w-12 h-12 rounded-full" />
                  <div>
                    <h4 class="text-white font-semibold">{{ testimonial.name }}</h4>
                    <p class="text-teal-400 text-sm">{{ testimonial.role }}</p>
                  </div>
                </div>
                <p class="text-gray-400">{{ testimonial.content }}</p>
              </div>
            </SwiperSlide>
          </Swiper>
        </div>
      </section>

      <!-- Add this section before the CTA section -->
      <section class="py-24 bg-dragon-dark-800/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="text-center">
            <h2 class="text-3xl font-bold text-white sm:text-4xl">Simple, Transparent Pricing</h2>
            <p class="mt-4 text-xl text-gray-400">Choose the perfect plan for your needs</p>
          </div>
          <div class="mt-16 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
            <div v-for="plan in plans" :key="plan.name"
                 class="bg-dragon-dark-700/50 p-8 rounded-xl border border-dragon-dark-600 backdrop-blur-sm
                        transition-all duration-300 transform hover:-translate-y-1"
                 :class="{ 'ring-2 ring-teal-500': plan.popular }">
              <div class="text-center">
                <h3 class="text-2xl font-bold text-white">{{ plan.name }}</h3>
                <div class="mt-4 flex items-center justify-center">
                  <span class="text-4xl font-bold text-white">{{ plan.price }}</span>
                  <span class="text-gray-400 ml-2">{{ plan.period }}</span>
                </div>
                <p class="mt-2 text-gray-400">{{ plan.description }}</p>
                <div v-if="plan.savings" class="mt-2">
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-teal-500/10 text-teal-400">
                    {{ plan.savings }}
                  </span>
                </div>
              </div>
              <div class="mt-8 space-y-4">
                <div v-for="feature in plan.features" :key="feature"
                     class="flex items-center text-gray-400">
                  <svg class="h-5 w-5 text-teal-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                  {{ feature }}
                </div>
              </div>
              <div class="mt-8 text-center">
                <Link :href="route('register')" 
                      class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg 
                             text-white bg-teal-500 hover:bg-teal-600 focus:outline-none focus:ring-2 focus:ring-offset-2 
                             focus:ring-teal-500 transition-all duration-300">
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
      <section class="relative py-20 px-4 sm:px-6 lg:px-8">
        <div class="absolute inset-0 overflow-hidden">
          <div class="absolute inset-0 z-0">
            <img src="https://dracoscopia.com/images/backgrounds/background04.jpg" alt="CTA Background" class="w-full h-full object-cover opacity-10" />
          </div>
            </div>
        <div class="max-w-4xl mx-auto text-center relative z-10">
          <h2 class="text-3xl sm:text-4xl font-bold text-white mb-6">
            Ready to Get Started?
          </h2>
          <p class="text-xl text-gray-400 mb-10 max-w-2xl mx-auto">
            Join our community of innovators and start building the future of blockchain technology
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <Link :href="route('register')" class="btn-dragon">
              Start Free Trial
            </Link>
            <Link :href="route('contact')" class="btn-dragon-outline">
              Contact Us
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
         rounded-lg shadow-xl hover:shadow-teal-500/25 transition-all duration-300
         hover:from-teal-600 hover:to-blue-700 transform hover:-translate-y-0.5;
}

.btn-dragon-outline {
  @apply px-6 py-3 border-2 border-teal-500 text-teal-500 font-semibold 
         rounded-lg hover:bg-teal-500/10 transition-all duration-300
         hover:border-teal-400 hover:text-teal-400 transform hover:-translate-y-0.5;
}

.feature-card {
  @apply bg-dragon-dark-700/50 border border-dragon-dark-600 backdrop-blur-sm
         hover:border-teal-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-teal-500/10
         transform hover:-translate-y-1;
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

