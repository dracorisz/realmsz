<script setup>
import { ref, onMounted } from 'vue';
import { gsap } from 'gsap';

const props = defineProps({
  module: {
    type: Object,
    required: true
  },
  isOpen: {
    type: Boolean,
    required: true
  }
});

const emit = defineEmits(['close']);

const closeModal = () => {
  emit('close');
};

onMounted(() => {
  if (props.isOpen) {
    gsap.from('.modal-content', {
      y: 50,
      opacity: 0,
      duration: 0.5,
      ease: 'power3.out'
    });
  }
});
</script>

<template>
  <Transition
    enter-active-class="transition duration-300 ease-out"
    enter-from-class="opacity-0"
    enter-to-class="opacity-100"
    leave-active-class="transition duration-200 ease-in"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
  >
    <div v-if="isOpen" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/70 backdrop-blur-sm" @click="closeModal"></div>
        
        <div class="modal-content relative w-full max-w-4xl transform overflow-hidden rounded-2xl bg-dragon-dark-800 p-6 shadow-2xl transition-all">
          <button
            @click="closeModal"
            class="absolute right-4 top-4 rounded-full p-2 text-gray-400 hover:bg-dragon-dark-700 hover:text-white"
          >
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>

          <div class="flex flex-col md:flex-row gap-8">
            <div class="flex-1">
              <div class="flex items-center gap-4 mb-6">
                <span class="text-4xl">{{ module.icon }}</span>
                <h2 class="text-2xl font-bold text-white">{{ module.name }}</h2>
              </div>
              
              <div class="space-y-4">
                <div v-for="(feature, index) in module.features" :key="index" 
                     class="flex items-start gap-3 p-4 rounded-lg bg-dragon-dark-700/50 hover:bg-dragon-dark-700 transition-colors">
                  <span class="text-teal-400">✓</span>
                  <p class="text-gray-300">{{ feature }}</p>
                </div>
              </div>

              <div class="mt-8">
                <h3 class="text-lg font-semibold text-white mb-4">Key Benefits</h3>
                <ul class="space-y-3">
                  <li class="flex items-center gap-3 text-gray-300">
                    <span class="text-teal-400">•</span>
                    <span>Seamless integration with other modules</span>
                  </li>
                  <li class="flex items-center gap-3 text-gray-300">
                    <span class="text-teal-400">•</span>
                    <span>Real-time collaboration features</span>
                  </li>
                  <li class="flex items-center gap-3 text-gray-300">
                    <span class="text-teal-400">•</span>
                    <span>Advanced analytics and reporting</span>
                  </li>
                </ul>
              </div>
            </div>

            <div class="flex-1">
              <div class="aspect-video rounded-lg overflow-hidden bg-dragon-dark-700/50">
                <img 
                  :src="`/images/modules/${module.name.toLowerCase()}.png`" 
                  :alt="`${module.name} Module Preview`"
                  class="w-full h-full object-cover"
                />
              </div>
              
              <div class="mt-6">
                <h3 class="text-lg font-semibold text-white mb-4">Pricing</h3>
                <div class="grid grid-cols-2 gap-4">
                  <div class="p-4 rounded-lg bg-dragon-dark-700/50">
                    <h4 class="text-white font-medium">Basic</h4>
                    <p class="text-2xl font-bold text-teal-400">Free</p>
                    <p class="text-sm text-gray-400">Core features included</p>
                  </div>
                  <div class="p-4 rounded-lg bg-dragon-dark-700/50">
                    <h4 class="text-white font-medium">Pro</h4>
                    <p class="text-2xl font-bold text-teal-400">$9.99/mo</p>
                    <p class="text-sm text-gray-400">All features unlocked</p>
                  </div>
                </div>
              </div>

              <div class="mt-6 flex gap-4">
                <button class="btn-dragon flex-1">Start Free Trial</button>
                <button class="btn-dragon-outline flex-1">Learn More</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Transition>
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