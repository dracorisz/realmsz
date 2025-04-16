<script setup>
import FrontLayout from "@/Layouts/FrontLayout.vue";
</script>

<template>
  <FrontLayout title="Privacy Policy">
    <template #content>
      <div class="relative flex h-full w-full flex-col items-center justify-start gap-10 px-5 py-20 text-white">
      <div class="flex w-full max-w-4xl flex-col items-center justify-center gap-5">
        <h1 class="text-4xl font-bold text-white">Privacy Policy</h1>
        <p class="text-center text-white/70">Last updated: {{ new Date().toLocaleDateString() }}</p>
      </div>

      <div class="flex w-full max-w-4xl flex-col gap-10">
        <div class="privacy-section">
          <h2 class="text-2xl font-bold text-accent">1. Information We Collect</h2>
          <p class="mt-5 text-white/80">
            We collect information that you provide directly to us, including but not limited to your name, email address, and any other information you choose to provide. We also automatically collect certain information about your device and how you interact with our Service.
          </p>
        </div>

        <div class="privacy-section">
          <h2 class="text-2xl font-bold text-accent">2. How We Use Your Information</h2>
          <p class="mt-5 text-white/80">
            We use the information we collect to provide, maintain, and improve our Service, to communicate with you, to monitor and analyze trends, and to personalize your experience. We may also use your information to detect, prevent, and address technical issues.
          </p>
        </div>

        <div class="privacy-section">
          <h2 class="text-2xl font-bold text-accent">3. Information Sharing</h2>
          <p class="mt-5 text-white/80">
            We do not share your personal information with third parties except as described in this policy. We may share your information with service providers who perform services on our behalf, with law enforcement when required by law, or in connection with a business transfer.
          </p>
        </div>

        <div class="privacy-section">
          <h2 class="text-2xl font-bold text-accent">4. Data Security</h2>
          <p class="mt-5 text-white/80">
            We implement appropriate technical and organizational measures to protect your personal information against unauthorized or unlawful processing, accidental loss, destruction, or damage.
          </p>
        </div>

        <div class="privacy-section">
          <h2 class="text-2xl font-bold text-accent">5. Your Rights</h2>
          <p class="mt-5 text-white/80">
            You have the right to access, correct, or delete your personal information. You may also have the right to object to or restrict certain processing of your information, and to receive your information in a portable format.
          </p>
        </div>

        <div class="privacy-section">
          <h2 class="text-2xl font-bold text-accent">6. Cookies and Tracking</h2>
          <p class="mt-5 text-white/80">
            We use cookies and similar tracking technologies to track activity on our Service and hold certain information. You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent.
          </p>
        </div>

        <div class="privacy-section">
          <h2 class="text-2xl font-bold text-accent">7. Children's Privacy</h2>
          <p class="mt-5 text-white/80">
            Our Service is not intended for use by children under the age of 13. We do not knowingly collect personal information from children under 13. If you are a parent or guardian and you are aware that your child has provided us with personal information, please contact us.
          </p>
        </div>

        <div class="privacy-section">
          <h2 class="text-2xl font-bold text-accent">8. Changes to This Policy</h2>
          <p class="mt-5 text-white/80">
            We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last updated" date.
          </p>
        </div>

        <div class="privacy-section">
          <h2 class="text-2xl font-bold text-accent">9. Contact Us</h2>
          <p class="mt-5 text-white/80">
            If you have any questions about this Privacy Policy, please contact us at privacy@realmsz.com.
          </p>
        </div>
      </div>
    </div>
    </template>
  </FrontLayout>
</template>


<style scoped lang="pcss">
.privacy-section {
  @apply relative rounded-2xl border border-white/5 bg-black/30 p-5 backdrop-blur-sm;
  background-image: linear-gradient(34deg, hsla(0, 0%, 55%, 0.13), hsla(0, 0%, 21%, 0.21));
}

.privacy-section::before {
  @apply absolute inset-0 z-[-1] rounded-2xl opacity-0 transition-opacity duration-300 ease-linear content-[""];
  background-image: linear-gradient(to right, hsl(211, 100%, 10%), hsl(179, 100%, 10%));
}

.privacy-section:hover::before {
  opacity: 1;
}
</style> 