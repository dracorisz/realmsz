<template>
  <FrontLayout>
    <div class="min-h-screen bg-gradient-to-b from-dragon-dark-900 to-dragon-dark-800 py-12 px-4 sm:px-6 lg:px-8">
      <div class="max-w-3xl mx-auto">
        <div class="text-center">
          <h1 class="text-4xl font-bold text-white mb-4">Waiting for Payment Confirmation</h1>
          <p class="text-xl text-gray-300 mb-8">
            Your subscription is being processed. We'll notify you once your payment is confirmed.
          </p>
        </div>

        <div class="bg-dragon-dark-700 rounded-xl p-8 shadow-lg">
          <div class="flex items-center justify-center mb-8">
            <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-dragon-primary"></div>
          </div>

          <div class="space-y-6">
            <div class="flex items-start">
              <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-dragon-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <div class="ml-3">
                <h3 class="text-lg font-medium text-white">What's happening?</h3>
                <p class="mt-2 text-gray-300">
                  We're processing your payment and setting up your subscription. This usually takes just a few minutes.
                </p>
              </div>
            </div>

            <div class="flex items-start">
              <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-dragon-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div class="ml-3">
                <h3 class="text-lg font-medium text-white">What to expect</h3>
                <p class="mt-2 text-gray-300">
                  Once your payment is confirmed, you'll have full access to all features. We'll send you an email when everything is ready.
                </p>
              </div>
            </div>

            <div class="flex items-start">
              <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-dragon-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
              </div>
              <div class="ml-3">
                <h3 class="text-lg font-medium text-white">Need help?</h3>
                <p class="mt-2 text-gray-300">
                  If you have any questions or need assistance, please contact our support team at 
                  <a href="mailto:support@realmsz.com" class="text-dragon-primary hover:text-dragon-primary-light">
                    scopia@realmsz.com
                  </a>
                </p>
              </div>
            </div>
          </div>

          <div class="mt-8 text-center">
            <Link 
              :href="route('packages.index')" 
              class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-dragon-primary hover:bg-dragon-primary-light focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-dragon-primary"
            >
              View Subscription Plans
            </Link>
          </div>
        </div>
      </div>
    </div>
  </FrontLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import FrontLayout from '@/Layouts/FrontLayout.vue';
</script> 