<script setup>
import { Link } from '@inertiajs/vue3';
import FrontLayout from '@/Layouts/FrontLayout.vue';
</script>

<template>
  <FrontLayout>
    <div class="min-h-screen bg-gradient-to-b from-dragon-dark-900 to-dragon-dark-800 py-20">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="bg-dragon-dark-700/50 rounded-2xl p-8 shadow-xl">
          <div class="w-20 h-20 mx-auto mb-8 bg-teal-500/20 rounded-full flex items-center justify-center">
            <svg class="w-12 h-12 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
          </div>

          <h1 class="text-3xl font-bold text-white mb-4">
            Subscription Successful!
          </h1>

          <p class="text-xl text-gray-300 mb-8">
            Thank you for subscribing to Realmsz. Your account has been activated with a 14-day free trial.
          </p>

          <div class="space-y-4">
            <div class="bg-dragon-dark-800/50 rounded-lg p-4">
              <h3 class="text-lg font-semibold text-white mb-2">What's Next?</h3>
              <p class="text-gray-400">
                Explore your new features and start creating amazing content with Realmsz.
              </p>
            </div>

            <div class="bg-dragon-dark-800/50 rounded-lg p-4">
              <h3 class="text-lg font-semibold text-white mb-2">Need Help?</h3>
              <p class="text-gray-400">
                Check out our documentation or contact our support team for assistance.
              </p>
            </div>
          </div>

          <div class="mt-8 space-x-4">
            <Link
              href="/dashboard"
              class="btn-dragon"
            >
              Go to Dashboard
            </Link>
            
            <Link
              href="/support"
              class="text-gray-400 hover:text-white transition-colors"
            >
              Contact Support
            </Link>
          </div>
        </div>
      </div>
    </div>
  </FrontLayout>
</template>

<style scoped>
.btn-dragon {
  @apply inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-dragon-primary hover:bg-dragon-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-dragon-primary transition-all duration-300 shadow-lg hover:shadow-dragon-primary/30;
}
</style> 