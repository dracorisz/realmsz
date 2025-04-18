<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { 
  CubeIcon,
  ShieldCheckIcon,
  ServerIcon,
  GlobeAltIcon
} from '@heroicons/vue/24/outline';

const features = [
  {
    name: 'Secure Platform',
    description: 'Enterprise-grade security with end-to-end encryption',
    icon: ShieldCheckIcon,
  },
  {
    name: 'Global Infrastructure',
    description: 'High-performance servers across multiple regions',
    icon: ServerIcon,
  },
  {
    name: 'Blockchain Integration',
    description: 'Seamless integration with major blockchain networks',
    icon: CubeIcon,
  },
  {
    name: 'Global Community',
    description: 'Join a thriving community of innovators',
    icon: GlobeAltIcon,
  },
];
</script>

<template>
  <div class="min-h-screen bg-gradient-to-b from-dragon-dark-900 to-dragon-dark-800">
    <!-- Background -->
    <div class="absolute inset-0 overflow-hidden">
      <img src="https://dracoscopia.com/images/backgrounds/background03.jpg" alt="Background" class="w-full h-full object-cover opacity-20" />
      <div class="absolute inset-0 bg-gradient-to-b from-dragon-dark-900/80 to-dragon-dark-800/80"></div>
    </div>

    <!-- Content -->
    <div class="relative min-h-screen flex flex-col">
      <!-- Header -->
      <header class="relative z-10">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
          <div class="flex justify-between items-center">
            <Link :href="route('welcome')" class="flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="ah-[24px] w-7 h-7 text-dragon-primary-400 fill-dragon-primary-400">
      <path d="m18.522,6.203c3.096.639,4.68,2.574,4.759,2.672l-1.558,1.255c-.072-.087-1.79-2.13-5.224-2.13-1.826,0-4.428,1.797-4.5,2,0-1.046.35-2.365,1.144-3.386-3.478-.988-5.033-3.54-5.144-3.614.019.013,2.609.525,5.171.811-.823-1.182-1.171-2.636-1.171-3.811,0,.212,1.47,1.567,3.18,2.381-.123-.669-.18-1.458-.18-2.381,0,.156.808,1.196,1.837,2h.663c.818,0,1.566.393,2.037,1h4.463v2h-4.001c-.042,0-1.296.021-1.477,1.203Zm-1.718,4.525c-.279-.207-.454-.435-.731-.668-.754.18-5.573,2.941-5.573,2.941-.261-.837-.5-2-.5-3,0-.782.135-1.587.384-2.352-.695-.367-1.422-.843-2.135-1.458-.697.892-1.207,1.987-1.246,3.165l-3.003,2.145,3.495.437c.157.358.346.692.561,1.006l-2.057,2.057h4.086c.221.169.445.331.671.486l-1.257,2.514,3.838-.853c.941.63,1.662,1.241,1.662,1.853,0,1.187-2.29,1.5-3.5,1.5-3.973,0-9.5-4.031-9.5-8,0-2.5.5-5,1.683-6.98l1.317,2.48c.499-2.205,1.148-4.774.962-8C4.07.762,1.893,2.714,0,5h2.076C.424,7.755,0,10.446,0,12.5c0,5.589,6.425,11.5,12.5,11.5h2c3.015,0,7.5-1.465,7.5-5.5,0-3.919-3.003-6.146-5.196-7.772Z" />
    </svg>
                <span class="text-dragon-primary-400 font-black text-sm uppercase">Realmsz</span>
              </Link>
            <div class="flex items-center space-x-4">
              <Link :href="route('login')" class="text-gray-400 hover:text-white transition-colors duration-200">
                Sign In
              </Link>
              <Link :href="route('register')" class="btn-dragon">
                Get Started
              </Link>
            </div>
          </div>
        </nav>
      </header>

      <!-- Main Content -->
      <main class="flex-1 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">
          <slot />
        </div>
      </main>

      <!-- Footer -->
      <footer class="relative z-10 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="flex items-center space-x-6">
              <Link :href="route('terms-of-service')" class="text-gray-400 hover:text-white transition-colors duration-200">
                Terms
              </Link>
              <Link :href="route('privacy-policy')" class="text-gray-400 hover:text-white transition-colors duration-200">
                Privacy
              </Link>
              <Link :href="route('contact')" class="text-gray-400 hover:text-white transition-colors duration-200">
                Contact
              </Link>
            </div>
            <div class="mt-4 md:mt-0 text-gray-400">
              © 2025 Realmsz. All rights reserved.
            </div>
          </div>
        </div>
      </footer>
    </div>
  </div>
</template>

<style scoped>
.btn-dragon {
  @apply px-4 py-2 bg-gradient-to-r from-teal-500 to-blue-600 text-white font-semibold 
         rounded-lg shadow-xl hover:shadow-teal-500/25 transition-all duration-300
         hover:from-teal-600 hover:to-blue-700 transform hover:-translate-y-0.5;
}
.bg-dragon-dark-900 {
  background-color: #0a0a0f;
}
.bg-dragon-dark-800 {
  background-color: #111118;
}
.bg-dragon-dark-700 {
  background-color: #1a1a24;
}
.bg-dragon-dark-600 {
  background-color: #222230;
}
.bg-dragon-dark-500 {
  background-color: #2a2a3c;
}
</style> 