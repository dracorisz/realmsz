<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import FrontLayout from "@/Layouts/FrontLayout.vue";
import { onMounted, ref } from "vue";
import { gsap } from "gsap";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";

const assetUrl = import.meta.env.VITE_ASSET_URL;

const form = useForm({
  name: "",
  email: "",
  message: "",
});

const submit = () => {
  form.post(route('partnership.store'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
      // Show success message
      alert('Your partnership inquiry has been sent successfully!');
    },
    onError: () => {
      // Show error message
      alert('There was an error sending your inquiry. Please try again.');
    }
  });
};

const elements = ref(null);

onMounted(() => {
  // Wait for next tick to ensure DOM is ready
  setTimeout(() => {
    const container = document.querySelector(".form-container");
    const form = document.querySelector(".form");
    const stage = document.querySelector(".form-inner-container");
    const toggle = document.querySelector(".form-toggle");
    const heading = document.querySelector(".heading-1");
    const description = document.querySelector(".heading-3");
    const links = document.querySelectorAll(".heading-3 a");
    const cards = document.querySelectorAll(".card");

    // Only proceed if elements exist
    if (!heading && !description && !links.length && !cards.length) return;

    // Initial setup
    gsap.set([heading, description, links, cards].filter(Boolean), { opacity: 0, y: 20 });
    gsap.set(form, { perspective: 500, rotateX: "50deg", rotateY: "40deg", rotateZ: "-20deg", z: 0, scale: 1 });
    gsap.set(stage, { opacity: 0 });

    // Animate elements in sequence
    const timeline = gsap.timeline();
    timeline
      .to(heading, { duration: 1, opacity: 1, y: 0, ease: "power2.out" })
      .to(description, { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "-=0.5")
      .to(links, { duration: 0.5, opacity: 1, y: 0, stagger: 0.1, ease: "power2.out" }, "-=0.3")
      .to(cards, { duration: 0.8, opacity: 1, y: 0, stagger: 0.2, ease: "power2.out" }, "-=0.3")
      .to(stage, { duration: 1, opacity: 1, ease: "power2.out" }, "-=0.2");

    let hover = gsap
      .timeline({
        repeat: -1,
        yoyo: true,
      })
      .to(form, { duration: 1, y: -20, z: 0 });

    const toCenter = gsap.timeline({ paused: true, duration: 1 })
      .to(form, { duration: 1, rotateX: 0, rotateZ: 0, y: 0, scale: 1, ease: "back.out(3)" }, 0)
      .to(stage, { duration: 1, y: 20, ease: "back.out(3)" }, 0)
      .to(stage, { duration: 1, y: 10, ease: "back.out(1)" }, 0);

    let centered = false;

    if (toggle) {
      toggle.addEventListener("click", (e) => {
        e.stopPropagation();
        container.classList.toggle("open");

        if (centered) {
          toCenter.reverse();
          hover.play();
          centered = false;
        } else {
          hover.pause();
          toCenter.play();
          centered = true;
        }
      });
    }

    if (form) {
      form.addEventListener("focusin", () => {
        container.classList.add("open");
        hover.pause();
        toCenter.play();
        centered = true;
      });

      form.addEventListener("focusout", () => {
        container.classList.remove("open");
        toCenter.reverse();
        hover.play();
        centered = false;
      });
    }
  }, 100);
});
</script>

<template>
  <FrontLayout title="Partnership" :hasHero="true">
    <template #content>
      <div class="m-auto flex h-full w-full max-w-7xl flex-col bg-black px-4 md:px-10 py-10 md:py-20 text-white">
        <!-- Hero Section -->
        <div class="relative mb-8 md:mb-16">
          <img :src="`${assetUrl}/images/backgrounds/background03.jpg`" alt="Partnership Hero" class="w-full h-64 md:h-96 object-cover rounded-2xl opacity-80">
          <div class="absolute inset-0 flex items-center justify-center bg-black/50 rounded-2xl">
            <h2 class="heading-1 text-3xl md:text-6xl text-center px-4">Partnership Opportunities</h2>
          </div>
        </div>

        <!-- Navigation Links -->
        <div class="heading-3 mb-8 md:mb-16 text-center md:text-left">
          <Link :href="route('roadmap')" class="text-white/70 transition-colors hover:text-blue-400" :class="route().current('roadmap')">Roadmap</Link>
          <span class="mx-2">,</span>
          <Link :href="route('ipo')" class="text-white/70 transition-colors hover:text-blue-400" :class="route().current('ipo')">IPO</Link>
          <span class="mx-2">, and</span>
          <Link :href="route('ico')" class="text-white/70 transition-colors hover:text-blue-400" :class="route().current('ico')">ICO</Link>
          <span class="ml-2">for investors and shareholders.</span>
        </div>

        <!-- Partnership Types -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-8 mb-8 md:mb-16">
          <div class="card bg-white/5 p-4 md:p-6 rounded-2xl hover:bg-white/10 transition-colors">
            <h3 class="text-lg md:text-xl font-bold mb-4">Strategic Partnerships</h3>
            <ul class="space-y-2">
              <li class="text-sm md:text-base">• Long-term collaboration</li>
              <li class="text-sm md:text-base">• Joint development projects</li>
              <li class="text-sm md:text-base">• Resource sharing</li>
              <li class="text-sm md:text-base">• Market expansion</li>
            </ul>
          </div>
          <div class="card bg-white/5 p-4 md:p-6 rounded-2xl hover:bg-white/10 transition-colors">
            <h3 class="text-lg md:text-xl font-bold mb-4">Technology Integration</h3>
            <ul class="space-y-2">
              <li class="text-sm md:text-base">• API integration</li>
              <li class="text-sm md:text-base">• Cross-platform solutions</li>
              <li class="text-sm md:text-base">• Technical collaboration</li>
              <li class="text-sm md:text-base">• Innovation partnerships</li>
            </ul>
          </div>
          <div class="card bg-white/5 p-4 md:p-6 rounded-2xl hover:bg-white/10 transition-colors">
            <h3 class="text-lg md:text-xl font-bold mb-4">Community Partnerships</h3>
            <ul class="space-y-2">
              <li class="text-sm md:text-base">• Community engagement</li>
              <li class="text-sm md:text-base">• Educational initiatives</li>
              <li class="text-sm md:text-base">• Social impact projects</li>
              <li class="text-sm md:text-base">• Local partnerships</li>
            </ul>
          </div>
        </div>

        <!-- Contact Form -->
        <div class="form-container relative mb-8 md:mb-16">
          <div class="form-inner-container">
            <form @submit.prevent="submit" class="form bg-white/5 p-6 md:p-8 rounded-2xl">
              <h3 class="text-xl md:text-2xl font-bold mb-6">Contact Us</h3>
              
              <div class="mb-4">
                <InputLabel for="name" value="Name" />
                <TextInput
                  id="name"
                  type="text"
                  class="mt-1 block w-full bg-white/10 border-white/20 text-white"
                  v-model="form.name"
                  required
                  autofocus
                />
                <InputError class="mt-2" :message="form.errors.name" />
              </div>

              <div class="mb-4">
                <InputLabel for="email" value="Email" />
                <TextInput
                  id="email"
                  type="email"
                  class="mt-1 block w-full bg-white/10 border-white/20 text-white"
                  v-model="form.email"
                  required
                />
                <InputError class="mt-2" :message="form.errors.email" />
              </div>

              <div class="mb-6">
                <InputLabel for="message" value="Message" />
                <textarea
                  id="message"
                  class="mt-1 block w-full bg-white/10 border-white/20 text-white rounded-md shadow-sm"
                  v-model="form.message"
                  required
                  rows="4"
                ></textarea>
                <InputError class="mt-2" :message="form.errors.message" />
              </div>

              <div class="flex items-center justify-end">
                <PrimaryButton
                  class="ml-4"
                  :class="{ 'opacity-25': form.processing }"
                  :disabled="form.processing"
                >
                  Submit
                </PrimaryButton>
              </div>
            </form>
          </div>
        </div>

        <!-- Website Link -->
        <div class="flex justify-center mb-8">
          <a 
            href="https://dracoscopia.com" 
            target="_blank" 
            rel="noopener noreferrer"
            class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-300 transform hover:scale-105"
          >
            <span class="mr-2">Visit Dracoscopia Website</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>
          </a>
        </div>

        <!-- Version Info -->
        <div class="mt-8 text-center text-xs text-gray-400">
          Version 1.0.0-beta
        </div>
      </div>
    </template>
  </FrontLayout>
</template>

<style scoped lang="pcss">
.form-container {
  transform-style: preserve-3d;
}

.form-inner-container {
  @apply relative opacity-0 transition-all duration-[1.5s] ease-in-out will-change-transform;
  transform-style: preserve-3d;
}

.open .form-inner-container {
  @apply opacity-100;
}

.form-toggle {
  @apply invisible absolute -right-3 -top-3 rounded-2xl;
}

.open .form-toggle {
  @apply visible -right-3 -top-3 cursor-pointer rounded-2xl;
}

.form {
  @apply absolute left-0 z-30 w-full rounded-2xl bg-[#1e1e1e] bg-gradient-to-br from-black/50 to-[#1e1e1e] p-6 md:p-10;
}

.open .form-inner-container {
  @apply !ml-0 opacity-100;
}

.open .form {
  @apply !scale-100;
}

@media (max-width: 768px) {
  .form {
    @apply p-4;
  }
  
  .form-inner-container {
    @apply transform-none;
  }
}
</style>

