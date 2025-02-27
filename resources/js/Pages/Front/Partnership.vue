<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import FrontLayout from "@/Layouts/FrontLayout.vue";
import { onMounted } from "vue";
import { gsap } from "gsap";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";

const form = useForm({
  name: "",
  email: "",
});

onMounted(() => {
  const container = document.querySelector(".form-container");
  const form = document.querySelector(".form");
  const stage = document.querySelector(".form-inner-container");
  const toggle = document.querySelector(".form-toggle");

  gsap.set(form, { perspective: 500, rotateX: "50deg", rotateY: "40deg", rotateZ: "-20deg", z: 0, scale: 1 });

  let hover = gsap
    .timeline({
      repeat: -1,
      yoyo: true,
    })
    .to(form, { duration: 1, y: -20, z: 0 });

  const toCenter = gsap.timeline({ paused: true, duration: 1 }).to(form, { duration: 1, rotateX: 0, rotateZ: 0, y: 0, scale: 1, ease: "back.out(3)" }, 0).to(stage, { duration: 1, y: 20, ease: "back.out(3)" }, 0).to(stage, { duration: 1, y: 10, ease: "back.out(1)" }, 0);
  let centered = false;

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

  gsap.set(stage, {
    opacity: 1,
  });
});
</script>

<template>
  <FrontLayout title="Partnership" :hasHero="true">
    <template #content>
      <div class="z-10 flex w-full flex-col bg-gradient-to-bl from-primary/10 to-accent/5 px-10 py-20 text-white">
        <div class="mx-auto flex w-full max-w-7xl items-center justify-start px-10 text-white ah-[144px]">
          <h2 class="heading-1">Partner up with Realmsz</h2>
        </div>
        <div class="heading-3 mx-auto flex w-full max-w-7xl items-center justify-start px-10 text-left">
          <!-- <Link :href="route('roadmap')" class="text-white/70 transition-colors hover:text-[rgb(34,144,233)]" :class="route().current('roadmap')">Roadmap</Link>
          <span>,</span> -->
          <Link :href="route('ipo')" class="mr-2 text-white/70 transition-colors hover:text-[rgb(34,144,233)]" :class="route().current('ipo')">IPO</Link>
          <!-- <span>, and</span>
          <Link :href="route('ico')" class="mx-2 text-white/70 transition-colors hover:text-[rgb(34,144,233)]" :class="route().current('ico')">ICO</Link> -->
          <span>for investors and shateholders.</span>
        </div>
        <div class="heading-3 mx-auto my-10 flex w-full max-w-7xl flex-col justify-start px-10 text-left">
          <p class="mr-auto w-2/3">Dolore aliquip sint pariatur tempor enim exercitation magna commodo. Sit aliqua ut cillum sunt voluptate id elit consequat Lorem. Est culpa ex non ad sint reprehenderit excepteur nisi ea. Tempor labore excepteur laborum cillum adipisicing aute laborum exercitation duis amet.</p>
        </div>
        <div class="form-container preserve-3d mx-auto flex h-full w-full max-w-7xl items-start justify-start px-10">
          <div class="form-inner-container ml-20 mr-auto self-start">
            <form class="form relative z-30 w-full" action="#">
              <div class="form-toggle text-white">
                <PrimaryButton :onlyIcon="true" color="#000" opacity="30" hoverOpacity="50">
                  <template #icon>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icons">
                      <line x1="18" y1="6" x2="6" y2="18"></line>
                      <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                  </template>
                </PrimaryButton>
              </div>
              <div class="gap-2">
                <InputLabel for="name" value="Name" />
                <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
                <InputError class="mt-2" :message="form.errors.name" />
              </div>
              <div class="mt-4 gap-2">
                <InputLabel for="email" value="Email" />
                <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" required autocomplete="username" />
                <InputError class="mt-2" :message="form.errors.email" />
              </div>
              <div class="mt-4 gap-2">
                <InputLabel for="message" value="Message" />
                <textarea name="message" id="message" class="textarea w-full"></textarea>
              </div>
              <div class="mt-4 flex w-full items-end justify-end">
                <PrimaryButton @click="console.log('send email partner')" color="#000" opacity="30" hoverOpacity="50">
                  <template #icon>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="icons" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                      <path d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                    </svg>
                  </template>
                  <span>Send</span>
                </PrimaryButton>
              </div>
            </form>
          </div>
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
  @apply relative opacity-0 transition-all duration-[1.5s] ease-in-out will-change-transform ah-[310px] aw-[377px];
  transform-style: preserve-3d;
}

.open .form-inner-container {
  @apply as-[417px];
}

.form-toggle {
  @apply invisible absolute -right-3 -top-3 rounded-2xl;
}

.open .form-toggle {
  @apply visible -right-3 -top-3 cursor-pointer rounded-2xl as-[36px];
}

.form {
  @apply absolute left-0 z-30 w-full rounded-2xl bg-[#1e1e1e] bg-gradient-to-br from-black/50 to-[#1e1e1e] p-10;
}

.open .form-inner-container {
  @apply !ml-0 opacity-100;
}

.open .form {
  @apply !scale-100 !aw-[377px];
}
</style>

