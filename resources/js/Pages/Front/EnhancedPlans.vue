<script setup>
import { Link } from '@inertiajs/vue3';
import FrontLayout from '@/Layouts/FrontLayout.vue';

const plans = [
  {
    name: 'Starter',
    price: '$29',
    period: 'per month',
    description: 'Perfect for individuals and small projects',
    features: [
      'Basic NFT Creation',
      'Up to 100 NFTs',
      'Standard Support',
      'Basic Analytics',
      'Community Access'
    ],
    cta: 'Get Started',
    popular: false
  },
  {
    name: 'Professional',
    price: '$99',
    period: 'per month',
    description: 'Ideal for growing businesses and creators',
    features: [
      'Advanced NFT Creation',
      'Up to 1,000 NFTs',
      'Priority Support',
      'Advanced Analytics',
      'API Access',
      'Custom Branding',
      'Bulk Operations'
    ],
    cta: 'Start Free Trial',
    popular: true
  },
  {
    name: 'Enterprise',
    price: 'Custom',
    period: 'per month',
    description: 'Tailored solutions for large organizations',
    features: [
      'Unlimited NFTs',
      '24/7 Premium Support',
      'Custom Development',
      'Dedicated Account Manager',
      'White Label Solution',
      'Advanced Security',
      'SLA Guarantee'
    ],
    cta: 'Contact Sales',
    popular: false
  }
];

const features = [
  {
    name: 'NFT Creation',
    description: 'Create and manage NFTs with our intuitive tools',
    icon: '🎨'
  },
  {
    name: 'Analytics',
    description: 'Track performance and engagement metrics',
    icon: '📊'
  },
  {
    name: 'Security',
    description: 'Enterprise-grade security and encryption',
    icon: '🔒'
  },
  {
    name: 'Support',
    description: 'Dedicated support team available 24/7',
    icon: '💬'
  },
  {
    name: 'API Access',
    description: 'Integrate with your existing systems',
    icon: '🔌'
  },
  {
    name: 'Customization',
    description: 'Tailor the platform to your needs',
    icon: '⚙️'
  }
];
</script>

<template>
  <FrontLayout title="Enhanced Plans">
    <div class="min-h-screen bg-gradient-to-b from-dragon-dark-900 to-dragon-dark-800">
      <!-- Hero Section -->
      <section class="relative py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto text-center">
          <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold bg-gradient-to-r from-teal-400 via-blue-500 to-purple-600 bg-clip-text text-transparent">
            Enhanced Plans
          </h1>
          <p class="mt-6 text-xl sm:text-2xl text-blue-400 max-w-3xl mx-auto">
            Choose the perfect plan for your needs
          </p>
        </div>
      </section>

      <!-- Pricing Plans -->
      <section class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div v-for="plan in plans" :key="plan.name" 
                 class="relative bg-dragon-dark-700/50 p-8 rounded-xl border border-dragon-dark-600"
                 :class="{ 'border-teal-500': plan.popular }">
              <div v-if="plan.popular" 
                   class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                <span class="bg-teal-500 text-white text-sm font-semibold px-4 py-1 rounded-full">
                  Most Popular
                </span>
              </div>
              
              <h3 class="text-2xl font-bold text-white mb-2">{{ plan.name }}</h3>
              <div class="flex items-baseline mb-4">
                <span class="text-4xl font-bold text-teal-400">{{ plan.price }}</span>
                <span class="text-gray-400 ml-2">{{ plan.period }}</span>
              </div>
              <p class="text-gray-400 mb-6">{{ plan.description }}</p>
              
              <ul class="space-y-4 mb-8">
                <li v-for="feature in plan.features" :key="feature" 
                    class="flex items-center text-gray-300">
                  <span class="text-teal-400 mr-2">✓</span>
                  {{ feature }}
                </li>
              </ul>
              
              <Link
                :href="route('contact')"
                class="btn-dragon w-full text-center"
              >
                {{ plan.cta }}
              </Link>
            </div>
          </div>
        </div>
      </section>

      <!-- Features -->
      <section class="py-20 px-4 sm:px-6 lg:px-8 bg-dragon-dark-800/50">
        <div class="max-w-7xl mx-auto">
          <h2 class="text-3xl font-bold text-white text-center mb-12">Key Features</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div v-for="feature in features" :key="feature.name" 
                 class="bg-dragon-dark-700/50 p-6 rounded-xl border border-dragon-dark-600">
              <div class="flex items-center gap-4 mb-4">
                <span class="text-4xl">{{ feature.icon }}</span>
                <h3 class="text-xl font-semibold text-white">{{ feature.name }}</h3>
              </div>
              
              <p class="text-gray-400">{{ feature.description }}</p>
            </div>
          </div>
        </div>
      </section>

      <!-- FAQ Section -->
      <section class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
          <h2 class="text-3xl font-bold text-white text-center mb-12">Frequently Asked Questions</h2>
          
          <div class="space-y-6">
            <div class="bg-dragon-dark-700/50 p-6 rounded-xl border border-dragon-dark-600">
              <h3 class="text-xl font-semibold text-white mb-2">Can I change my plan later?</h3>
              <p class="text-gray-400">Yes, you can upgrade or downgrade your plan at any time. Changes will be reflected in your next billing cycle.</p>
            </div>
            
            <div class="bg-dragon-dark-700/50 p-6 rounded-xl border border-dragon-dark-600">
              <h3 class="text-xl font-semibold text-white mb-2">Is there a free trial available?</h3>
              <p class="text-gray-400">Yes, we offer a 14-day free trial for our Professional plan. No credit card required.</p>
            </div>
            
            <div class="bg-dragon-dark-700/50 p-6 rounded-xl border border-dragon-dark-600">
              <h3 class="text-xl font-semibold text-white mb-2">What payment methods do you accept?</h3>
              <p class="text-gray-400">We accept all major credit cards, PayPal, and cryptocurrency payments.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- CTA Section -->
      <section class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
          <h2 class="text-3xl sm:text-4xl font-bold text-white mb-6">
            Ready to Get Started?
          </h2>
          <p class="text-xl text-gray-400 mb-10 max-w-2xl mx-auto">
            Choose a plan that fits your needs and start creating today
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <Link
              :href="route('contact')"
              class="btn-dragon"
            >
              Contact Sales
            </Link>
            <a
              href="mailto:sales@realmsz.com"
              class="btn-dragon-outline"
            >
              Email Sales
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
</style> 