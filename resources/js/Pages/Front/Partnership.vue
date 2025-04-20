<script setup>
import { Link, Head } from '@inertiajs/vue3';
import FrontLayout from '@/Layouts/FrontLayout.vue';

const partnershipTypes = [
  {
    name: 'Technology Partners',
    icon: '💻',
    description: 'Integrate your technology with our platform',
    benefits: [
      'API Access',
      'Technical Support',
      'Co-marketing Opportunities',
      'Revenue Sharing'
    ]
  },
  {
    name: 'Content Partners',
    icon: '📚',
    description: 'Share your content with our community',
    benefits: [
      'Content Distribution',
      'Audience Growth',
      'Monetization Options',
      'Analytics Tools'
    ]
  },
  {
    name: 'Service Partners',
    icon: '🛠️',
    description: 'Offer your services to our users',
    benefits: [
      'Service Marketplace',
      'Lead Generation',
      'Client Management',
      'Payment Processing'
    ]
  },
  {
    name: 'Enterprise Partners',
    icon: '🏢',
    description: 'Custom solutions for large organizations',
    benefits: [
      'Custom Development',
      'Dedicated Support',
      'Enterprise Features',
      'Security Compliance'
    ]
  }
];

const partnershipProcess = [
  {
    step: 1,
    title: 'Initial Contact',
    description: 'Reach out to discuss partnership opportunities'
  },
  {
    step: 2,
    title: 'Evaluation',
    description: 'We review your proposal and requirements'
  },
  {
    step: 3,
    title: 'Agreement',
    description: 'Define terms and sign partnership agreement'
  },
  {
    step: 4,
    title: 'Integration',
    description: 'Technical integration and setup'
  },
  {
    step: 5,
    title: 'Launch',
    description: 'Public announcement and go-live'
  }
];
</script>

<template>
  <FrontLayout title="Partnerships | Realmsz Digital Asset Management">
    <div class="min-h-screen bg-gradient-to-b from-dragon-dark-900 to-dragon-dark-800">
      <!-- Hero Section -->
      <section class="relative py-20 px-4 sm:px-6 lg:px-8">
        <div class="absolute inset-0 overflow-hidden">
          <div class="absolute inset-0 z-0">
            <img src="https://dracoscopia.com/images/backgrounds/partnership.jpg" alt="Partnership Background" class="w-full h-full object-cover opacity-20" />
          </div>
          <div class="max-w-7xl mx-auto text-center">
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold bg-gradient-to-r from-teal-400 via-blue-500 to-purple-600 bg-clip-text text-transparent">
              Partnership Program
            </h1>
            <p class="mt-6 text-xl sm:text-2xl text-blue-400 max-w-3xl mx-auto">
              Join forces with Realmsz to create innovative solutions
            </p>
          </div>
        </div>
      </section>

      <!-- Partnership Types -->
      <section class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
          <h2 class="text-3xl font-bold text-white text-center mb-12">Partnership Types</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div v-for="type in partnershipTypes" :key="type.name" 
                 class="bg-dragon-dark-700/50 p-6 rounded-xl border border-dragon-dark-600">
              <div class="flex items-center gap-4 mb-4">
                <span class="text-4xl">{{ type.icon }}</span>
                <h3 class="text-xl font-semibold text-white">{{ type.name }}</h3>
              </div>
              
              <p class="text-gray-400 mb-6">{{ type.description }}</p>
              
              <ul class="space-y-3">
                <li v-for="benefit in type.benefits" :key="benefit" 
                    class="flex items-center text-gray-300">
                  <span class="text-teal-400 mr-2">✓</span>
                  {{ benefit }}
                </li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- Partnership Process -->
      <section class="py-20 px-4 sm:px-6 lg:px-8 bg-dragon-dark-800/50">
        <div class="max-w-7xl mx-auto">
          <h2 class="text-3xl font-bold text-white text-center mb-12">Partnership Process</h2>
          
          <div class="relative">
            <div class="absolute left-0 top-0 h-full w-0.5 bg-dragon-dark-600"></div>
            
            <div class="space-y-12">
              <div v-for="step in partnershipProcess" :key="step.step" class="relative pl-8">
                <div class="absolute left-[-2px] top-0 h-4 w-4 rounded-full bg-teal-500"></div>
                
                <div class="bg-dragon-dark-700/50 p-6 rounded-xl border border-dragon-dark-600">
                  <div class="flex items-center gap-4 mb-4">
                    <span class="text-2xl font-bold text-teal-400">Step {{ step.step }}</span>
                    <h3 class="text-xl font-semibold text-white">{{ step.title }}</h3>
                  </div>
                  
                  <p class="text-gray-400">{{ step.description }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- CTA Section -->
      <section class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
          <h2 class="text-3xl sm:text-4xl font-bold text-white mb-6">
            Ready to Partner with Us?
          </h2>
          <p class="text-xl text-gray-400 mb-10 max-w-2xl mx-auto">
            Contact our partnership team to discuss opportunities
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <Link
              :href="route('contact')"
              class="btn-dragon"
            >
              Contact Us
            </Link>
            <a
              href="mailto:partnerships@realmsz.com"
              class="btn-dragon-outline"
            >
              Email Partnerships
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

