<script setup>
import { Link, Head } from '@inertiajs/vue3';
import FrontLayout from '@/Layouts/FrontLayout.vue';

const teamMembers = [
  {
    name: 'Scopia Rewainde',
    role: 'Chief Executive Officer',
    bio: 'Visionary leader and founder of Realmsz, driving innovation and strategic growth in the blockchain space.',
    image: 'https://dracoscopia.com/scopia.png',
    // social: {
    //   twitter: 'https://twitter.com/scopia',
    //   linkedin: 'https://linkedin.com/in/scopia'
    // },
    email: 'scopia@realmsz.com'
  },
  {
    name: 'Dragol Rewainde',
    role: 'Chief Technology Officer',
    bio: 'Technical architect and blockchain expert, leading the development of the Realmsz platform and its innovative features.',
    image: 'https://dracoscopia.com/dragol.png',
    // social: {
    //   twitter: 'https://twitter.com/dragol',
    //   linkedin: 'https://linkedin.com/in/dragol'
    // },
    email: 'dragol@realmsz.com'
  },
  {
    name: 'Mila Milai',
    role: 'Administrator & AI',
    bio: 'Operations specialist and community manager, ensuring smooth platform operations and user satisfaction.',
    image: 'https://dracoscopia.com/milai.png',
    // social: {
    //   twitter: 'https://twitter.com/milamilai',
    //   linkedin: 'https://linkedin.com/in/milamilai'
    // },
    email: 'mila@realmsz.com'
  }
];

const advisors = [
  {
    name: 'Dr. Robert Wilson',
    role: 'Blockchain Advisor',
    bio: 'Expert in blockchain technology and decentralized systems, guiding technical strategy and innovation.',
    image: 'https://dracoscopia.com/tt.png',
    // social: {
    //   linkedin: 'https://linkedin.com/in/robertwilson'
    // },
    email: 'robert@realmsz.com'
  },
  {
    name: 'Lisa Thompson',
    role: 'Business Strategy Advisor',
    bio: 'Seasoned business strategist with extensive experience in tech startups and blockchain ventures.',
    image: 'https://dracoscopia.com/56.png',
    // social: {
    //   linkedin: 'https://linkedin.com/in/lisathompson'
    // },
    email: 'lisa@realmsz.com'
  }
];
</script>

<template>
  <Head title="Team | Realmsz" />
  <FrontLayout>
    <div class="min-h-screen bg-gradient-to-b from-dragon-dark-900 to-dragon-dark-800">
      <!-- Hero Section -->
      <section class="relative py-20 px-4 sm:px-6 lg:px-8">
        <div class="absolute inset-0 overflow-hidden">
          <div class="absolute inset-0 z-0">
            <img src="https://dracoscopia.com/images/backgrounds/background04.jpg" alt="Team Background" class="w-full h-full object-cover opacity-20" />
          </div>
          <div class="max-w-7xl mx-auto text-center">
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold bg-gradient-to-r from-teal-400 via-blue-500 to-purple-600 bg-clip-text text-transparent">
              Our Team
            </h1>
            <p class="mt-6 text-xl sm:text-2xl text-blue-400 max-w-3xl mx-auto">
              Meet the visionaries and experts behind Realmsz
            </p>
          </div>
        </div>
      </section>

      <!-- Core Team -->
      <section class="relative py-20 px-4 sm:px-6 lg:px-8">
        <div class="absolute inset-0 overflow-hidden">
          <div class="absolute inset-0 z-0">
            <img src="https://dracoscopia.com/images/backgrounds/background04.jpg" alt="Leadership Background" class="w-full h-full object-cover opacity-10" />
          </div>
        </div>
        <div class="max-w-7xl mx-auto relative z-10">
          <h2 class="text-3xl font-bold text-white text-center mb-12">Leadership Team</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div v-for="member in teamMembers" :key="member.name" 
                 class="bg-dragon-dark-700/50 p-6 rounded-xl border border-dragon-dark-600">
              <div class="aspect-w-1 aspect-h-1 mb-6">
                <img :src="member.image" :alt="member.name" 
                     class="w-full h-full object-cover rounded-lg">
              </div>
              
              <h3 class="text-xl font-semibold text-white mb-2">{{ member.name }}</h3>
              <p class="text-teal-400 mb-4">{{ member.role }}</p>
              <p class="text-gray-400 mb-6">{{ member.bio }}</p>
              
              <div class="flex flex-col gap-4">
                <!-- <div class="flex gap-4">
                  <a v-if="member.social.linkedin" :href="member.social.linkedin" 
                     class="text-gray-400 hover:text-teal-400 transition-colors">
                    <span class="sr-only">LinkedIn</span>
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                    </svg>
                  </a>
                  <a v-if="member.social.twitter" :href="member.social.twitter" 
                     class="text-gray-400 hover:text-teal-400 transition-colors">
                    <span class="sr-only">Twitter</span>
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                    </svg>
                  </a>
                </div> -->
                <a :href="`mailto:${member.email}`" 
                   class="text-gray-400 hover:text-teal-400 transition-colors flex items-center gap-2">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                  </svg>
                  {{ member.email }}
                </a>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Advisors -->
      <section class="relative py-20 px-4 sm:px-6 lg:px-8 bg-dragon-dark-800/50">
        <div class="absolute inset-0 overflow-hidden">
          <div class="absolute inset-0 z-0">
            <img src="https://dracoscopia.com/images/backgrounds/background04.jpg" alt="Advisors Background" class="w-full h-full object-cover opacity-10" />
          </div>
        </div>
        <div class="max-w-7xl mx-auto relative z-10">
          <h2 class="text-3xl font-bold text-white text-center mb-12">Advisors</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div v-for="advisor in advisors" :key="advisor.name" 
                 class="bg-dragon-dark-700/50 p-6 rounded-xl border border-dragon-dark-600">
              <div class="flex items-start gap-6">
                <div class="w-24 h-24 flex-shrink-0">
                  <img :src="advisor.image" :alt="advisor.name" 
                       class="w-full h-full object-cover rounded-lg">
                </div>
                
                <div>
                  <h3 class="text-xl font-semibold text-white mb-2">{{ advisor.name }}</h3>
                  <p class="text-teal-400 mb-4">{{ advisor.role }}</p>
                  <p class="text-gray-400 mb-6">{{ advisor.bio }}</p>
                  
                  <!-- <div class="flex flex-col gap-4">
                    <a v-if="advisor.social.linkedin" :href="advisor.social.linkedin" 
                       class="text-gray-400 hover:text-teal-400 transition-colors">
                      <span class="sr-only">LinkedIn</span>
                      <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                      </svg>
                    </a>
                    <a :href="`mailto:${advisor.email}`" 
                       class="text-gray-400 hover:text-teal-400 transition-colors flex items-center gap-2">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                      </svg>
                      {{ advisor.email }}
                    </a>
                  </div> -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- CTA Section -->
      <section class="relative py-20 px-4 sm:px-6 lg:px-8">
        <div class="absolute inset-0 overflow-hidden">
          <div class="absolute inset-0 z-0">
            <img src="https://dracoscopia.com/images/backgrounds/background03.jpg" alt="Join Background" class="w-full h-full object-cover opacity-10" />
          </div>
        </div>
        <div class="max-w-4xl mx-auto text-center relative z-10">
          <h2 class="text-3xl sm:text-4xl font-bold text-white mb-6">
            Join Our Team
          </h2>
          <p class="text-xl text-gray-400 mb-10 max-w-2xl mx-auto">
            We're always looking for talented individuals to join our mission
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