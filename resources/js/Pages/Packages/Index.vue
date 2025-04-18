<template>
  <div class="min-h-screen bg-gradient-to-b from-dragon-dark-900 to-dragon-dark-800">
    <!-- Hero Section -->
    <section class="relative py-20 px-4 sm:px-6 lg:px-8">
      <div class="max-w-7xl mx-auto">
        <div class="text-center">
          <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold bg-gradient-to-r from-teal-400 via-blue-500 to-purple-600 bg-clip-text text-transparent">
            Subscription Packages
          </h1>
          <p class="mt-6 text-xl sm:text-2xl text-blue-400 max-w-3xl mx-auto">
            Choose the perfect plan to unlock the full potential of Realmsz
          </p>
        </div>
      </div>
    </section>

    <!-- Packages Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8">
      <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div
            v-for="pkg in packages"
            :key="pkg.id"
            class="card-dragon p-8 rounded-xl transform hover:-translate-y-2 transition-all duration-300"
          >
            <div class="text-center">
              <h3 class="text-2xl font-bold text-white">{{ pkg.name }}</h3>
              <p class="mt-4 text-gray-400">{{ pkg.description }}</p>
              <div class="mt-6">
                <span class="text-4xl font-bold bg-gradient-to-r from-teal-400 to-blue-500 bg-clip-text text-transparent">
                  ${{ pkg.price }}
                </span>
                <span class="text-gray-400">/{{ pkg.billing_period }}</span>
              </div>
            </div>

            <div class="mt-8">
              <ul class="space-y-4">
                <li
                  v-for="feature in pkg.features"
                  :key="feature"
                  class="flex items-center"
                >
                  <svg
                    class="h-5 w-5 text-teal-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 13l4 4L19 7"
                    />
                  </svg>
                  <span class="ml-3 text-gray-400">{{ feature }}</span>
                </li>
              </ul>
            </div>

            <div class="mt-8">
              <Link
                :href="$route('packages.show', pkg.slug)"
                class="btn-dragon block w-full text-center"
              >
                Choose Plan
              </Link>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-dragon-dark-800/50">
      <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-3xl sm:text-4xl font-bold text-white mb-6">
          Need Help Choosing?
        </h2>
        <p class="text-xl text-gray-400 mb-10 max-w-2xl mx-auto">
          Our team is here to help you find the perfect plan for your needs
        </p>
        <Link :href="$route('contact')" class="btn-dragon-large">
          Contact Support
        </Link>
      </div>
    </section>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  packages: {
    type: Array,
    required: true,
  },
});
</script>

<style scoped>
.btn-dragon {
  @apply px-6 py-3 bg-gradient-to-r from-teal-500 to-blue-600 text-white font-semibold 
         rounded-lg shadow-xl hover:shadow-teal-500/25 transition-all duration-300;
}

.btn-dragon-large {
  @apply px-8 py-4 text-lg bg-gradient-to-r from-teal-500 to-blue-600 text-white font-semibold 
         rounded-lg shadow-xl hover:shadow-teal-500/25 transition-all duration-300 hover:-translate-y-1;
}

.card-dragon {
  @apply bg-dragon-dark-700/50 border border-dragon-dark-600 backdrop-blur-sm
         hover:border-teal-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-teal-500/10;
}
</style> 