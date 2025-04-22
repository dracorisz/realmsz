<script setup>
import { Link } from "@inertiajs/vue3";
import FrontLayout from "@/Layouts/FrontLayout.vue";
</script>

<template>
  <FrontLayout title="Subscription Successful">
    <div class="min-h-screen bg-gradient-to-b from-dragon-dark-900 to-dragon-dark-800 py-20">
      <div class="mx-auto max-w-3xl px-4 text-center sm:px-6 lg:px-8">
        <div class="rounded-2xl bg-dragon-dark-700/50 p-8 shadow-xl">
          <div class="mx-auto mb-8 flex h-20 w-20 items-center justify-center rounded-full bg-teal-500/20">
            <svg class="h-12 w-12 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
          </div>

          <h1 class="mb-4 text-3xl font-bold text-white">Subscription Successful!</h1>

          <p class="mb-8 text-xl text-gray-300">Thank you for subscribing to Realmsz. Your account has been activated.</p>

          <div class="space-y-4">
            <div class="rounded-lg bg-dragon-dark-800/50 p-4">
              <h3 class="mb-2 text-lg font-semibold text-white">What's Next?</h3>
              <p class="text-gray-400">Explore your new features and start creating amazing content with Realmsz.</p>
            </div>

            <div class="rounded-lg bg-dragon-dark-800/50 p-4">
              <h3 class="mb-2 text-lg font-semibold text-white">Need Help?</h3>
              <p class="text-gray-400">Check out our documentation or contact our support team for assistance.</p>
            </div>
          </div>

          <div class="mt-8 space-x-4">
            <Link :href="route('focus')" class="btn-dragon"> Go to Dashboard </Link>

            <Link :href="route('contact')" class="text-gray-400 transition-colors hover:text-white"> Contact Support </Link>
          </div>
        </div>
      </div>
    </div>
  </FrontLayout>
</template>

<style scoped>
.btn-dragon {
  @apply inline-flex items-center justify-center rounded-md border border-transparent bg-dragon-primary px-6 py-3 text-base font-medium text-white shadow-lg transition-all duration-300 hover:bg-dragon-primary hover:shadow-dragon-primary/30 focus:outline-none focus:ring-2 focus:ring-dragon-primary focus:ring-offset-2;
}
</style>
