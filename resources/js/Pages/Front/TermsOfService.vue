<script setup>
import FrontLayout from "@/Layouts/FrontLayout.vue";
</script>

<template>
  <FrontLayout title="Terms of Service">
    <template #content>
      <div class="relative flex h-full w-full flex-col items-center justify-start gap-10 px-5 py-20 text-white">
      <div class="flex w-full max-w-4xl flex-col items-center justify-center gap-5">
        <h1 class="text-4xl font-bold text-white">Terms of Service</h1>
        <p class="text-center text-white/70">Last updated: {{ new Date().toLocaleDateString() }}</p>
      </div>

      <div class="flex w-full max-w-4xl flex-col gap-10">
        <div class="terms-section">
          <h2 class="text-2xl font-bold text-accent">1. Acceptance of Terms</h2>
          <p class="mt-5 text-white/80">
            By accessing and using Realmsz ("the Service"), you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.
          </p>
        </div>

        <div class="terms-section">
          <h2 class="text-2xl font-bold text-accent">2. Description of Service</h2>
          <p class="mt-5 text-white/80">
            Realmsz provides a digital platform for managing tasks, projects, and personal development. The Service includes features such as task management, project planning, and various productivity tools.
          </p>
        </div>

        <div class="terms-section">
          <h2 class="text-2xl font-bold text-accent">3. User Accounts</h2>
          <p class="mt-5 text-white/80">
            To access certain features of the Service, you must register for an account. You are responsible for maintaining the confidentiality of your account information and for all activities that occur under your account.
          </p>
        </div>

        <div class="terms-section">
          <h2 class="text-2xl font-bold text-accent">4. Privacy Policy</h2>
          <p class="mt-5 text-white/80">
            Your use of the Service is also governed by our Privacy Policy. Please review our Privacy Policy, which also governs the Site and informs users of our data collection practices.
          </p>
        </div>

        <div class="terms-section">
          <h2 class="text-2xl font-bold text-accent">5. Intellectual Property</h2>
          <p class="mt-5 text-white/80">
            The Service and its original content, features, and functionality are owned by Realmsz and are protected by international copyright, trademark, patent, trade secret, and other intellectual property laws.
          </p>
        </div>

        <div class="terms-section">
          <h2 class="text-2xl font-bold text-accent">6. Limitation of Liability</h2>
          <p class="mt-5 text-white/80">
            In no event shall Realmsz, nor its directors, employees, partners, agents, suppliers, or affiliates, be liable for any indirect, incidental, special, consequential or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses.
          </p>
        </div>

        <div class="terms-section">
          <h2 class="text-2xl font-bold text-accent">7. Changes to Terms</h2>
          <p class="mt-5 text-white/80">
            We reserve the right to modify or replace these Terms at any time. If a revision is material, we will provide at least 30 days' notice prior to any new terms taking effect.
          </p>
        </div>

        <div class="terms-section">
          <h2 class="text-2xl font-bold text-accent">8. Contact Information</h2>
          <p class="mt-5 text-white/80">
            If you have any questions about these Terms, please contact us at support@realmsz.com.
          </p>
        </div>
      </div>
    </div>
    </template>
  </FrontLayout>
</template>

<style scoped lang="pcss">
.terms-section {
  @apply relative rounded-2xl border border-white/5 bg-black/30 p-5 backdrop-blur-sm;
  background-image: linear-gradient(34deg, hsla(0, 0%, 55%, 0.13), hsla(0, 0%, 21%, 0.21));
}

.terms-section::before {
  @apply absolute inset-0 z-[-1] rounded-2xl opacity-0 transition-opacity duration-300 ease-linear content-[""];
  background-image: linear-gradient(to right, hsl(211, 100%, 10%), hsl(179, 100%, 10%));
}

.terms-section:hover::before {
  opacity: 1;
}
</style> 

