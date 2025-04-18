<script setup>
import { Link, Head } from '@inertiajs/vue3';
import {
  ShieldCheckIcon,
  DocumentTextIcon,
  UserIcon,
  LockClosedIcon,
  ShareIcon,
  TrashIcon,
  CheckIcon
} from '@heroicons/vue/24/outline';
import FrontLayout from '@/Layouts/FrontLayout.vue';
</script>

<template>
  <Head title="Privacy Policy | Realmsz" />
  <FrontLayout>
    <div class="min-h-screen bg-gradient-to-b from-dragon-dark-900 to-dragon-dark-800">
      <!-- Hero Section -->
      <section class="relative py-20 px-4 sm:px-6 lg:px-8">
        <div class="absolute inset-0 overflow-hidden">
          <div class="absolute inset-0 z-0">
            <img src="https://dracoscopia.com/images/backgrounds/privacy.jpg" alt="Privacy Background" class="w-full h-full object-cover opacity-20" />
          </div>
          <div class="max-w-7xl mx-auto">
            <div class="text-center">
              <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold bg-gradient-to-r from-teal-400 via-blue-500 to-purple-600 bg-clip-text text-transparent">
                Privacy Policy
              </h1>
              <p class="mt-6 text-xl sm:text-2xl text-blue-400 max-w-3xl mx-auto">
                How we protect and handle your personal information
              </p>
            </div>
          </div>
        </div>
      </section>

      <!-- Privacy Content Section -->
      <section class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
          <div class="space-y-12">
            <div v-for="(section, index) in privacyPolicy" :key="index" class="card-dragon p-8 rounded-xl">
              <div class="flex items-center mb-6">
                <component :is="section.icon" class="w-8 h-8 text-teal-400 mr-4" />
                <h2 class="text-2xl font-bold text-white">{{ section.title }}</h2>
              </div>
              <div class="space-y-4">
                <p v-for="(paragraph, pIndex) in section.content" :key="pIndex" class="text-gray-400">
                  {{ paragraph }}
                </p>
                <ul v-if="section.list" class="mt-4 space-y-2">
                  <li v-for="(item, iIndex) in section.list" :key="iIndex" class="flex items-start">
                    <CheckIcon class="w-5 h-5 text-teal-400 mt-1 mr-2 flex-shrink-0" />
                    <span class="text-gray-400">{{ item }}</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Last Updated -->
          <div class="mt-12 text-center text-gray-400">
            Last updated: {{ lastUpdated }}
          </div>
        </div>
      </section>

      <!-- Contact Section -->
      <section class="py-20 px-4 sm:px-6 lg:px-8 bg-dragon-dark-800/50">
        <div class="max-w-4xl mx-auto text-center">
          <h2 class="text-3xl sm:text-4xl font-bold text-white mb-6">
            Privacy Concerns?
          </h2>
          <p class="text-xl text-gray-400 mb-10 max-w-2xl mx-auto">
            Contact our data protection team for any questions about your privacy
          </p>
          <Link href="/contact" class="btn-dragon-large">
            Contact Us
          </Link>
        </div>
      </section>
    </div>
  </FrontLayout>
</template>

<style scoped>
.btn-dragon-large {
  @apply px-8 py-4 text-lg bg-gradient-to-r from-teal-500 to-blue-600 text-white font-semibold 
         rounded-lg shadow-xl hover:shadow-teal-500/25 transition-all duration-300 hover:-translate-y-1;
}

.card-dragon {
  @apply bg-dragon-dark-700/50 border border-dragon-dark-600 backdrop-blur-sm
         hover:border-teal-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-teal-500/10;
}
</style> 