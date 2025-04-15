<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import FrontLayout from "@/Layouts/FrontLayout.vue";

const canRegister = ref(true);
</script>

<template>
  <FrontLayout title="Welcome" :hasHero="true">
    <template #content>
      <div class="z-10 h-full w-full px-10 py-20 text-white">
        <div class="relative z-40 m-auto flex max-w-7xl flex-col items-start justify-start gap-5 p-10 md:items-center md:text-center">
          <div class="flex flex-col gap-0">
            <h2 class="heading-0 text-gradient">Enter the Realmsz of</h2>
            <h2 class="heading-0 text-accent saturate-[1.1]">Infinite Possibilities</h2>
          </div>
          <div class="flex gap-3">
            <Link v-if="canRegister" :href="route('register')">
              <PrimaryButton>Early Access</PrimaryButton>
            </Link>
            <PrimaryButton :onlyIcon="true">
              <template #icon>
                <svg class="icons" width="24" height="24" stroke-width="1.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor">
                  <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                  <path d="M7.5 11C7.22386 11 7 10.7761 7 10.5C7 10.2239 7.22386 10 7.5 10C7.77614 10 8 10.2239 8 10.5C8 10.7761 7.77614 11 7.5 11Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                  <path d="M13 18C12.4477 18 12 17.5523 12 17C12 16.4477 12.4477 16 13 16C13.5523 16 14 16.4477 14 17C14 17.5523 13.5523 18 13 18Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                  <path d="M11 7.01L11.01 6.99889" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                  <path d="M8 16.01L8.01 15.9989" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                  <path d="M16 9.01L16.01 8.99889" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                  <path d="M17 14.01L17.01 13.9989" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                  <path d="M13 12.01L13.01 11.9989" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
              </template>
            </PrimaryButton>
          </div>
        </div>
        <div class="mx-auto max-w-7xl items-center justify-center px-10 py-44 text-center">
          <span class="mx-auto block max-w-xl text-sm text-white">Realmsz combines 10 essential modules in one place: Focus, Plans, Profile, Network, Milai, Studio, Finance, Crypto, Websites, and Storage. Streamline your workflow, create content, connect with your community, and manage your digital assets. Get started by creating an account and exploring each module!</span>
        </div>
      </div>
    </template>
  </FrontLayout>
</template>

