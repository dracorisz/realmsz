<script setup>
import { onBeforeUnmount, ref } from "vue";
import { Link } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const resources = [
  { id: 1, title: "Business" },
  { id: 2, title: "Finances" },
  { id: 3, title: "Plans" },
  { id: 4, title: "Profile" },
  { id: 5, title: "Progress" },
  { id: 6, title: "Storage" },
];

const titles = [
  { id: 1, text: "Unified" },
  { id: 2, text: "Integrated" },
  { id: 3, text: "Simplified" },
];

const currentTitle = ref(1);
const updateCurrentTitle = () => {
  if (currentTitle.value === titles.length) currentTitle.value = 1;
  else currentTitle.value = ++currentTitle.value;
  return currentTitle;
};

const currentResource = ref(1);
const updateCurrentResource = () => {
  if (currentResource.value === resources.length) currentResource.value = 1;
  else currentResource.value = ++currentResource.value;
  return currentResource;
};

const updateResourceInterval = setInterval(updateCurrentResource, 5000);
const updateTitleInterval = setInterval(updateCurrentTitle, 30000);

onBeforeUnmount(() => {
  clearInterval(updateResourceInterval);
  clearInterval(updateTitleInterval);
});

const year = new Date().getFullYear();
</script>

<template>
  <div class="relative flex w-full flex-col bg-gradient-to-b from-black to-accent/10 bg-black">
    <div class="mx-auto flex w-full max-w-7xl flex-col justify-between px-10 py-5">
      <div class="relative flex w-full justify-between py-10">
        <div class="flex flex-col items-start justify-center text-white">
          <template v-for="resource in resources" :key="resource.id">
            <Transition name="resource">
              <h2 v-if="resource.id == currentResource" class="absolute font-black top-[21px]">{{ resource.title }}</h2>
            </Transition>
          </template>
          <template v-for="title in titles" :key="title.id">
            <Transition name="title">
              <h4 v-if="title.id == currentTitle" class="text-accent font-black absolute top-[43px]">{{ title.text }}</h4>
            </Transition>
          </template>
        </div>
        <PrimaryButton type="link" href="register">Start Now</PrimaryButton>
      </div>
      <div class="flex w-full flex-1 flex-col items-center justify-between gap-2 md:flex-row">
        <p class="text-xs text-white/70 hover:text-white/70">&copy; Realmsz {{ year }}. All rights reserved.</p>
        <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-2 flex items-center gap-3 md:mt-0">
          <Link :href="route('terms')" class="text-xs text-white/70 hover:text-white">Terms Of Service</Link>
          <Link :href="route('policy')" class="text-xs text-white/70 hover:text-white">Privacy Policy</Link>
        </div>
      </div>
      <div class="flex w-full items-center justify-center text-zx text-white/50">v0.0.3</div>
    </div>
  </div>
</template>

<style scoped lang="pcss">
.title-leave-active,
.title-enter-active,
.resource-leave-active,
.resource-enter-active {
  transition: 0.5s;
  transform: translateY(0px);
  transform: translateX(0px);
  opacity: 1;
}

.resource-enter-from {
  transform: translateY(30px);
  opacity: 0;
}

.resource-leave-to {
  transform: translateY(-30px);
  opacity: 0;
}

.title-enter-from {
  transform: translateX(-300px);
  opacity: 0;
}

.title-leave-to {
  transform: translateX(300px);
  opacity: 0;
}
</style>

