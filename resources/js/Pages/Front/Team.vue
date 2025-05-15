<script setup>
import { Link, Head } from '@inertiajs/vue3';
import FrontLayout from '@/Layouts/FrontLayout.vue';

const teamMembers = [
  {
    name: 'Scopia Rewainde',
    role: 'Chief Executive Officer',
    image: 'https://dracoscopia.com/scopia.png',
    email: 'scopia@realmsz.com',
    website: 'https://dracoscopia.com'
  },
  {
    name: 'Phillip Nnadi',
    role: 'Marketing Expert',
    image: 'https://dracoscopia.com/seypro.png',
    email: 'filip@realmsz.com',
    website: 'https://sey-pro.com'
  },
  {
    name: 'Mila Milai',
    role: 'Administrator & AI',
    image: 'https://dracoscopia.com/milai.png',
    email: 'mila@realmsz.com',
    website: 'https://websitedesignsnyc.com/'
  },
  {
    name: 'Alex Nnadi',
    role: 'Art Director',
    image: 'https://dracoscopia.com/logo.png',
    email: 'suzy@realmsz.com'
  },
  {
    name: 'Dragol Rewainde',
    role: 'Enthusiast',
    image: 'https://dracoscopia.com/dragol.png',
    email: 'scopia@realmsz.com'
  },
  {
    name: 'Join Our Team',
    role: 'We are looking for talented individuals to help shape the future of Realmsz',
    image: 'https://dracoscopia.com/images/ai/8.png',
    email: 'scopia@realmsz.com'
  } 
];

const advisors = [
  {
    name: 'Robert Nnadi',
    role: 'Blockchain Advisor',
    image: 'https://dracoscopia.com/tt.png',
    email: 'scopia@realmsz.com'
  },
  {
    name: 'Lisa Nnadi',
    role: 'Business Strategy Advisor',
    image: 'https://dracoscopia.com/aes.png',
    email: 'scopia@realmsz.com'
  }
];
</script>

<template>
  <FrontLayout title="Team | Realmsz Digital Asset Management">
    <div class="min-h-screen bg-gradient-to-b from-dragon-dark-900 to-dragon-dark-800">
      <!-- Hero Section -->
      <section class="relative py-20 px-4 sm:px-6 lg:px-8">
        <div class="absolute inset-0 overflow-hidden">
          <div class="absolute inset-0 z-0">
            <img src="https://dracoscopia.com/images/backgrounds/background01.jpg" alt="Hero Background" class="w-full h-full object-cover opacity-20" />
          </div>
          <div class="absolute inset-0 bg-gradient-to-b from-dragon-dark-900/80 to-dragon-dark-800/50"></div>
        </div>
        <div class="relative max-w-7xl mx-auto text-center">
          <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold bg-gradient-to-r from-teal-400 via-blue-500 to-purple-600 bg-clip-text text-transparent">
            Our Team
          </h1>
          <p class="mt-6 text-xl sm:text-2xl text-blue-400 max-w-3xl mx-auto">
            Meet the talented individuals behind Realmsz
          </p>
        </div>
      </section>

      <!-- Team Section -->
      <section class="relative py-20 px-4 sm:px-6 lg:px-8 bg-dragon-dark-800/50">
        <div class="absolute inset-0 overflow-hidden">
          <div class="absolute inset-0 z-0">
            <img src="https://dracoscopia.com/rt.png" alt="Team Background" class="w-full h-full object-cover opacity-30" />
          </div>
        </div>
        <div class="max-w-7xl mx-auto relative z-10">
          <h2 class="text-3xl font-bold text-white text-center mb-12">Core Team</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div v-for="member in teamMembers" :key="member.name" 
                 class="bg-dragon-dark-700/50 p-6 rounded-xl border border-dragon-dark-600 col-span-1 self-center items-center justify-center flex flex-col gap-0" :class="member.name === 'Join Our Team' ? 'py-16' : ''">
              <div v-if="member.name !== 'Join Our Team'" class="as-[90px] mb-6">
                <img :src="member.image" :alt="member.name" 
                     class="w-full h-full object-cover rounded-lg">
              </div>
              
              <h3 class="text-xl font-semibold text-white mb-2" :class="member.name !== 'Join Our Team' ? '' : 'm-auto text-center'">{{ member.name }}</h3>
              <p class="text-teal-400" :class="member.name !== 'Join Our Team' ? '' : 'm-auto text-center'">{{ member.role }}</p>
              <a :href="`mailto:${member.email}`" class="text-gray-400 hover:text-teal-400 transition-colors" :class="member.name !== 'Join Our Team' ? '' : ' w-full mx-auto text-center mt-3'">
                {{ member.email }}
              </a>
              <a v-if="member.website" :href="member.website" class="text-gray-400 hover:text-teal-400 transition-colors" :class="member.name !== 'Join Our Team' ? '' : 'm-auto text-center'">
                {{ member.website }}
              </a>
            </div>
          </div>
        </div>
      </section>

      <!-- Advisors Section -->
      <section class="relative py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
          <h2 class="text-3xl font-bold text-white text-center mb-12">Advisors</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div v-for="advisor in advisors" :key="advisor.name" 
                 class="bg-dragon-dark-700/50 p-6 rounded-xl border border-dragon-dark-600">
              <div class="flex items-center gap-4 mb-4">
                <img :src="advisor.image" :alt="advisor.name" class="w-16 h-16 rounded-full object-cover" />
                <div>
                  <h3 class="text-xl font-semibold text-white">{{ advisor.name }}</h3>
                  <p class="text-teal-400">{{ advisor.role }}</p>
                </div>
              </div>
              <a :href="`mailto:${advisor.email}`" class="text-gray-400 hover:text-teal-400 transition-colors">
                {{ advisor.email }}
              </a>
            </div>
          </div>
        </div>
      </section>

      <!-- CTA Section -->
      <section class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
          <h2 class="text-3xl sm:text-4xl font-bold text-white mb-6">
            Want to Join Our Team?
          </h2>
          <p class="text-xl text-gray-400 mb-10 max-w-2xl mx-auto">
            We're always looking for talented individuals to help shape the future of Realmsz
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <Link
              :href="route('contact')"
              class="btn-dragon"
            >
              Contact Us
            </Link>
            <a
              href="mailto:scopia@realmsz.com"
              class="btn-dragon-outline"
            >
              Email Careers
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