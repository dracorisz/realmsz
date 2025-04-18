<script setup>
import { Link, Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import FrontLayout from '@/Layouts/FrontLayout.vue';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import {
  DocumentTextIcon,
  ShieldCheckIcon,
  UserGroupIcon,
  CurrencyDollarIcon,
  ScaleIcon,
  ExclamationTriangleIcon,
  LockClosedIcon,
  ServerIcon,
  ChevronDownIcon,
  ChevronUpIcon
} from '@heroicons/vue/24/outline';

gsap.registerPlugin(ScrollTrigger);

const lastUpdated = ref('December 1, 2023');
const activeSection = ref(null);

const toggleSection = (index) => {
  activeSection.value = activeSection.value === index ? null : index;
};

const terms = [
  {
    title: 'User Agreement',
    icon: UserGroupIcon,
    content: [
      'By accessing our platform, you agree to these terms of service',
      'You must be at least 18 years old to use our services',
      'You are responsible for maintaining the security of your account',
      'Multiple accounts per user are not permitted'
    ],
    details: 'This section outlines the basic rules and requirements for using our platform. It includes age restrictions, account security responsibilities, and usage limitations.'
  },
  {
    title: 'Privacy & Security',
    icon: ShieldCheckIcon,
    content: [
      'We protect your personal information using industry-standard encryption',
      'Your data will never be sold to third parties',
      'You can request deletion of your account and data at any time',
      'We use cookies to enhance your browsing experience'
    ],
    details: 'Our commitment to protecting your privacy and securing your data. Learn about our encryption standards, data handling practices, and your rights regarding personal information.'
  },
  {
    title: 'Financial Terms',
    icon: CurrencyDollarIcon,
    content: [
      'All transactions are processed in USD',
      'Refunds are processed within 14 business days',
      'We charge a 2.5% processing fee for all transactions',
      'Subscription cancellations must be made 7 days before billing'
    ],
    details: 'Important information about financial transactions, including currency, fees, refund policies, and subscription management.'
  },
  {
    title: 'Content Policy',
    icon: DocumentTextIcon,
    content: [
      'Users retain ownership of their created content',
      'We reserve the right to remove inappropriate content',
      'Content must not infringe on intellectual property rights',
      'Sharing of explicit or harmful content is prohibited'
    ],
    details: 'Guidelines for content creation and sharing on our platform, including intellectual property rights and content moderation policies.'
  },
  {
    title: 'Data Protection',
    icon: LockClosedIcon,
    content: [
      'Your data is stored in secure, encrypted servers',
      'Regular backups are performed to prevent data loss',
      'We comply with GDPR and similar regulations',
      'Third-party access is strictly controlled'
    ],
    details: 'Information about how we protect your data, including storage practices, backup procedures, and compliance with data protection regulations.'
  },
  {
    title: 'Platform Usage',
    icon: ServerIcon,
    content: [
      'The platform may experience occasional maintenance downtime',
      'We reserve the right to modify or discontinue features',
      'API usage is subject to rate limiting',
      'Automated scraping is not permitted without approval'
    ],
    details: 'Guidelines for platform usage, including maintenance schedules, feature updates, API usage policies, and automation restrictions.'
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
  const sections = ["#terms-content", "#contact"];
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
  const cards = gsap.utils.toArray(".term-card");
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
  <Head title="Terms of Service | Realmsz" />
  <FrontLayout>
    <div class="min-h-screen bg-gradient-to-b from-dragon-dark-900 to-dragon-dark-800">
      <!-- Hero Section -->
      <section class="relative py-20 px-4 sm:px-6 lg:px-8">
        <div class="absolute inset-0 overflow-hidden">
          <div class="absolute inset-0 z-0">
            <img src="https://dracoscopia.com/images/backgrounds/terms.jpg" alt="Terms Background" class="w-full h-full object-cover opacity-20" />
          </div>
          <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
              <h1 class="hero-title text-4xl font-bold tracking-tight text-teal-400 sm:text-6xl">Terms of Service</h1>
              <p class="hero-subtitle mt-6 text-lg leading-8 text-blue-400">
                Please read these terms carefully before using our platform.
              </p>
              <p class="mt-2 text-sm text-gray-400">Last updated: {{ lastUpdated }}</p>
            </div>
          </div>
        </div>
      </section>

      <!-- Terms Content -->
      <div class="mx-auto max-w-7xl px-6 pb-24 lg:px-8">
        <div class="grid gap-8 md:grid-cols-2">
          <template v-for="(section, index) in terms" :key="index">
            <div 
              class="card-dragon p-6 rounded-lg cursor-pointer transition-all duration-300"
              @click="toggleSection(index)"
            >
              <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-4">
                  <component :is="section.icon" class="section-icon h-8 w-8 text-teal-400" />
                  <h2 class="text-2xl font-semibold text-white">{{ section.title }}</h2>
                </div>
                <component 
                  :is="activeSection === index ? ChevronUpIcon : ChevronDownIcon"
                  class="h-6 w-6 text-teal-400 transition-transform duration-300"
                  :class="{ 'rotate-180': activeSection === index }"
                />
              </div>
              
              <div 
                class="overflow-hidden transition-all duration-300"
                :class="{ 'max-h-0': activeSection !== index, 'max-h-[500px]': activeSection === index }"
              >
                <p class="text-gray-400 mb-4">{{ section.details }}</p>
                <ul class="space-y-3">
                  <li 
                    v-for="(item, itemIndex) in section.content" 
                    :key="itemIndex" 
                    class="content-item text-gray-300 flex items-start"
                  >
                    <span class="mr-2 mt-1 block h-2 w-2 rounded-full bg-teal-400"></span>
                    <span>{{ item }}</span>
                  </li>
                </ul>
              </div>
            </div>
          </template>
        </div>

        <!-- Contact Section -->
        <div class="mt-16 text-center">
          <p class="text-gray-300">
            If you have any questions about these terms, please
            <Link :href="route('contact')" class="text-teal-400 hover:text-teal-300 underline">contact us</Link>.
          </p>
        </div>
      </div>
    </div>
  </FrontLayout>
</template>

<style scoped>
.card-dragon {
  @apply bg-dragon-dark-700/50 border border-dragon-dark-600 backdrop-blur-sm
         hover:border-teal-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-teal-500/10;
}

.card-dragon:hover {
  @apply transform -translate-y-1;
}

.content-item {
  @apply transition-all duration-300;
}

.content-item:hover {
  @apply transform translate-x-2 text-teal-400;
}
</style>

