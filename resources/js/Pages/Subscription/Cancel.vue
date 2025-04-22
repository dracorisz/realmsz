<script setup>
import { Link } from '@inertiajs/vue3';
import FrontLayout from '@/Layouts/FrontLayout.vue';
</script>

<template>
  <FrontLayout title="Subscription Cancelled">
    <div class="min-h-screen bg-gradient-to-b from-dragon-dark-900 to-dragon-dark-800 py-20">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="bg-dragon-dark-700/50 rounded-2xl p-8 shadow-xl">
          <div class="w-20 h-20 mx-auto mb-8 bg-red-500/20 rounded-full flex items-center justify-center">
            <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </div>

          <h1 class="text-3xl font-bold text-white mb-4">
            Subscription Cancelled
          </h1>

          <p class="text-xl text-gray-300 mb-8">
            Your subscription process has been cancelled. No charges have been made to your account.
          </p>

          <div class="space-y-4">
            <div class="bg-dragon-dark-800/50 rounded-lg p-4">
              <h3 class="text-lg font-semibold text-white mb-2">Want to Try Again?</h3>
              <p class="text-gray-400">
                You can return to the plans page and select a different plan or try again.
              </p>
            </div>

            <div class="bg-dragon-dark-800/50 rounded-lg p-4">
              <h3 class="text-lg font-semibold text-white mb-2">Have Questions?</h3>
              <p class="text-gray-400">
                Our support team is here to help you choose the right plan for your needs.
              </p>
            </div>
          </div>

          <div class="mt-8 space-x-4">
            <Link
              href="/plans"
              class="btn-dragon"
            >
              View Plans
            </Link>
            
            <Link
              href="/contact"
              class="text-gray-400 hover:text-white transition-colors"
            >
              Contact Us
            </Link>
          </div>
        </div>
      </div>
    </div>
  </FrontLayout>
</template>

<style scoped>
.btn-dragon {
  @apply inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-dragon-primary hover:bg-dragon-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-dragon-primary transition-all duration-300 shadow-lg hover:shadow-dragon-primary/30;
}
</style> 