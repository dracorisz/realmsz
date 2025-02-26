<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

defineProps({
  items: Array,
});
</script>

<template>
  <AppLayout title="Focus">
    <div class="bg-radial relative flex h-full w-full flex-col items-center justify-center text-white">
      <!-- <span class="heading-0 relative z-30 mt-auto">Tempor anim elit</span> -->
      <div v-if="items && items[0]" class="mt-auto rounded-2xl border border-white/5 bg-black/10 p-10 text-xs backdrop-blur-3xl">
        {{ items[0].title }}
      </div>
      <div class="relative z-30 mt-auto flex w-full justify-between p-5">
        <div class="grid grid-cols-4 gap-3">
          <PrimaryButton  color="#00c2c5" opacity="30" hoverOpacity="50" :onlyIcon="true" tooltip="Notes">
            <template #icon>
              <svg width="24" height="24" class="icons" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor">
                <path d="M8 14L16 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M8 10L10 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M8 18L12 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M10 3H6C4.89543 3 4 3.89543 4 5V20C4 21.1046 4.89543 22 6 22H18C19.1046 22 20 21.1046 20 20V5C20 3.89543 19.1046 3 18 3H14.5M10 3V1M10 3V5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
              </svg>
            </template>
          </PrimaryButton>
          <PrimaryButton  color="#00c2c5" opacity="30" hoverOpacity="50" :onlyIcon="true" tooltip="Bckground">
            <template #icon>
              <svg width="24" height="24" class="icons" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor">
                <path d="M21 3.6V20.4C21 20.7314 20.7314 21 20.4 21H3.6C3.26863 21 3 20.7314 3 20.4V3.6C3 3.26863 3.26863 3 3.6 3H20.4C20.7314 3 21 3.26863 21 3.6Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M3 16L10 13L21 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M16 10C14.8954 10 14 9.10457 14 8C14 6.89543 14.8954 6 16 6C17.1046 6 18 6.89543 18 8C18 9.10457 17.1046 10 16 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
              </svg>
            </template>
          </PrimaryButton>
          <PrimaryButton  color="#00c2c5" opacity="30" hoverOpacity="50" :onlyIcon="true" tooltip="Messages">
            <template #icon>
              <svg width="24" height="24" class="icons" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor">
                <path d="M7 12L17 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M7 8L13 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M3 20.2895V5C3 3.89543 3.89543 3 5 3H19C20.1046 3 21 3.89543 21 5V15C21 16.1046 20.1046 17 19 17H7.96125C7.35368 17 6.77906 17.2762 6.39951 17.7506L4.06852 20.6643C3.71421 21.1072 3 20.8567 3 20.2895Z" stroke="currentColor" stroke-width="2"></path>
              </svg>
            </template>
          </PrimaryButton>
          <PrimaryButton  color="#00c2c5" opacity="30" hoverOpacity="50" :onlyIcon="true" tooltip="Weather">
            <template #icon>
              <svg width="24" height="24" class="icons" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor">
                <path d="M6 13C4.33333 13 1 14 1 18C1 22 4.33333 23 6 23H18C19.6667 23 23 22 23 18C23 14 19.6667 13 18 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M12 12C13.6569 12 15 10.6569 15 9C15 7.34315 13.6569 6 12 6C10.3431 6 9 7.34315 9 9C9 10.6569 10.3431 12 12 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M19 9L20 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M12 2V1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M18.5 3.5L17.5 4.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M5.5 3.5L6.5 4.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M4 9L5 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
              </svg>
            </template>
          </PrimaryButton>
        </div>
        <div v-if="items && items[1]" class="relative z-30 flex flex-col items-end justify-end gap-3 text-xs">
          <span>Upcoming on {{ items[1].date }}</span>
          <span>{{ items[1].title }}</span>
        </div>
      </div>
      <img :src="'images/backgrounds/avatar.jpg'" alt="Sidebar Background Image" class="absolute inset-0 z-[-1] h-full w-full object-cover" />
    </div>
  </AppLayout>
</template>

<style scoped lang="pcss">
.bg-radial {
  background-image: radial-gradient(closest-side at 50% 50%, #00000000, #00000088);
}
</style>

