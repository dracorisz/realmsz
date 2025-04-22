<template>
  <FrontLayout title="Subscription Packages">
  <div class="min-h-screen bg-gradient-to-b from-dragon-dark-900 to-dragon-dark-800">
    <!-- Hero Section -->
    <section class="relative px-4 py-20 sm:px-6 lg:px-8">
      <div class="mx-auto max-w-7xl">
        <div class="text-center">
          <h1 class="bg-gradient-to-r from-teal-400 via-blue-500 to-purple-600 bg-clip-text text-4xl font-bold text-transparent sm:text-5xl md:text-6xl">Subscription Packages</h1>
          <p class="mx-auto mt-6 max-w-3xl text-xl text-blue-400 sm:text-2xl">Choose the perfect plan to unlock the full potential of Realmsz</p>
        </div>
      </div>
    </section>

    <!-- Packages Section -->
    <section class="px-4 py-20 sm:px-6 lg:px-8">
      <div class="mx-auto max-w-7xl">
        <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
          <div v-for="pkg in packages" :key="pkg.id" class="card-dragon transform rounded-xl p-8 transition-all duration-300 hover:-translate-y-2">
            <div class="text-center">
              <h3 class="text-2xl font-bold text-white">{{ pkg.name }}</h3>
              <p class="mt-4 text-gray-400">{{ pkg.description }}</p>
              <div class="mt-6">
                <span class="bg-gradient-to-r from-teal-400 to-blue-500 bg-clip-text text-4xl font-bold text-transparent"> ${{ pkg.price }} </span>
                <span class="text-gray-400">/{{ pkg.billing_period }}</span>
              </div>
            </div>

            <div class="mt-8">
              <ul class="space-y-4">
                <li v-for="feature in pkg.features" :key="feature" class="flex items-center">
                  <svg class="h-5 w-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                  <span class="ml-3 text-gray-400">{{ feature }}</span>
                </li>
              </ul>
            </div>

            <div class="mt-8">
              <Link :href="$route('packages.show', pkg.slug)" class="btn-dragon block w-full text-center"> Choose Plan </Link>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-dragon-dark-800/50 px-4 py-20 sm:px-6 lg:px-8">
      <div class="mx-auto max-w-4xl text-center">
        <h2 class="mb-6 text-3xl font-bold text-white sm:text-4xl">Need Help Choosing?</h2>
        <p class="mx-auto mb-10 max-w-2xl text-xl text-gray-400">Our team is here to help you find the perfect plan for your needs</p>
        <Link :href="$route('contact')" class="btn-dragon-large"> Contact Support </Link>
      </div>
    </section>
  </div>
</FrontLayout>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
  packages: {
    type: Array,
    required: true,
  },
});
</script>

<style scoped>
.btn-dragon {
  @apply rounded-lg bg-gradient-to-r from-teal-500 to-blue-600 px-6 py-3 font-semibold text-white shadow-xl transition-all duration-300 hover:shadow-teal-500/25;
}

.btn-dragon-large {
  @apply rounded-lg bg-gradient-to-r from-teal-500 to-blue-600 px-8 py-4 text-lg font-semibold text-white shadow-xl transition-all duration-300 hover:-translate-y-1 hover:shadow-teal-500/25;
}

.card-dragon {
  @apply border border-dragon-dark-600 bg-dragon-dark-700/50 backdrop-blur-sm transition-all duration-300 hover:border-teal-500/50 hover:shadow-lg hover:shadow-teal-500/10;
}
</style>
