<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
});
</script>

<template>
  <div class="fixed top-0 inset-x-0 flex z-20 w-full items-center bg-[#00000099] backdrop-blur-3xl ah-[55px]">
    <div class="m-auto flex max-w-7xl w-full items-center justify-start px-10 ah-[55px]">
      <ApplicationLogo />
      <nav class="ml-10 flex w-full items-center justify-start gap-5 ah-[55px]">
        <Link :href="route('ecosystem')" class="link" :class="route().current('ecosystem')">Ecosystem</Link>
        <!-- <Link :href="route('roadmap')" class="link" :class="route().current('roadmap')">Roadmap</Link> -->
        <Link :href="route('partnership')" class="link" :class="route().current('partnership')">Partnership</Link>
        <div v-if="canLogin" class="ml-auto flex items-center ah-[55px]">
          <Link v-if="$page.props.auth.user" :href="route('focus')" class="link">Focus</Link>
          <div v-else class="flex items-center ah-[55px]">
            <Link :href="route('login')" class="link">Login</Link>
            <PrimaryButton v-if="canRegister" href="register" type="link" class="ml-4">Register</PrimaryButton>
          </div>
        </div>
      </nav>
    </div>
  </div>
</template>

