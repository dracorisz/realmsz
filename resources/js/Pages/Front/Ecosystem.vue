<script setup>
import FrontLayout from "@/Layouts/FrontLayout.vue";
import { Link } from "@inertiajs/vue3";
import { onMounted } from "vue";

const categories = [
  { badge: false, title: "Focus", text: "Non non ea enim ea est laborum dolor.", icon: '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" /><polyline points="22 4 12 14.01 9 11.01" />' },
  { badge: false, title: "Plans", text: "Non non ea enim ea est laborum dolor.", icon: '<path d="M9 6L20 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M3.80002 5.79999L4.60002 6.59998L6.60001 4.59999" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M3.80002 11.8L4.60002 12.6L6.60001 10.6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M3.80002 17.8L4.60002 18.6L6.60001 16.6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M9 12L20 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M9 18L20 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>' },
  { badge: false, title: "Profile", text: "Elit proident laborum ut esse ex quis.", icon: '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" /><circle cx="12" cy="7" r="4" />' },
  { badge: false, title: "Network", text: "Aiqua volupt cupidatat veniam esse.", icon: '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M23 21v-2a4 4 0 0 0-3-3.87" /><path d="M16 3.13a4 4 0 0 1 0 7.75" />' },
  { badge: false, title: "Milai", text: "Elit proident laborum ut esse ex quis.", icon: '<path d="M10.5 9C10.5 9 10.5 7 9.5 5C13.5 5 16 7.49997 16 7.49997C16 7.49997 19.5 7 22 12C21 17.5 16 18 16 18L12 20.5C12 20.5 12 19.5 12 17.5C9.5 16.5 6.99998 14 7 12.5C7.00001 11 10.5 9 10.5 9ZM10.5 9C10.5 9 11.5 8.5 12.5 8.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M2 9.5L3 12.5L2 15.5C2 15.5 7 15.5 7 12.5C7 9.5 2 9.5 2 9.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M17 12.01L17.01 11.9989" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>' },
  { badge: false, title: "Studio", text: "Excepteur lorem consequat labore.", icon: '<path d="M21 7.35304L21 16.647C21 16.8649 20.8819 17.0656 20.6914 17.1715L12.2914 21.8381C12.1102 21.9388 11.8898 21.9388 11.7086 21.8381L3.30861 17.1715C3.11814 17.0656 3 16.8649 3 16.647L2.99998 7.35304C2.99998 7.13514 3.11812 6.93437 3.3086 6.82855L11.7086 2.16188C11.8898 2.06121 12.1102 2.06121 12.2914 2.16188L20.6914 6.82855C20.8818 6.93437 21 7.13514 21 7.35304Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M3.52844 7.29357L11.7086 11.8381C11.8898 11.9388 12.1102 11.9388 12.2914 11.8381L20.5 7.27777" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M12 21L12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M11.6914 11.8285L3.89139 7.49521C3.49147 7.27304 3 7.56222 3 8.01971V16.647C3 16.8649 3.11813 17.0656 3.30861 17.1715L11.1086 21.5048C11.5085 21.727 12 21.4378 12 20.9803V12.353C12 12.1351 11.8819 11.9344 11.6914 11.8285Z" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linejoin="round"></path>' },
  { badge: false, title: "Finance", text: "Commodo anim eiusmod minim aliquip.", icon: '<path d="M21 7.35304L21 16.647C21 16.8649 20.8819 17.0656 20.6914 17.1715L12.2914 21.8381C12.1102 21.9388 11.8898 21.9388 11.7086 21.8381L3.30861 17.1715C3.11814 17.0656 3 16.8649 3 16.647L2.99998 7.35304C2.99998 7.13514 3.11812 6.93437 3.3086 6.82855L11.7086 2.16188C11.8898 2.06121 12.1102 2.06121 12.2914 2.16188L20.6914 6.82855C20.8818 6.93437 21 7.13514 21 7.35304Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M3.52844 7.29357L11.7086 11.8381C11.8898 11.9388 12.1102 11.9388 12.2914 11.8381L20.5 7.27777" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M12 21L12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M11.6914 11.8285L3.89139 7.49521C3.49147 7.27304 3 7.56222 3 8.01971V16.647C3 16.8649 3.11813 17.0656 3.30861 17.1715L11.1086 21.5048C11.5085 21.727 12 21.4378 12 20.9803V12.353C12 12.1351 11.8819 11.9344 11.6914 11.8285Z" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linejoin="round"></path>' },
  { badge: false, title: "Crypto", text: "Velit elit minim proid exercitation cillum qui.", icon: '<path d="M21 7.35304L21 16.647C21 16.8649 20.8819 17.0656 20.6914 17.1715L12.2914 21.8381C12.1102 21.9388 11.8898 21.9388 11.7086 21.8381L3.30861 17.1715C3.11814 17.0656 3 16.8649 3 16.647L2.99998 7.35304C2.99998 7.13514 3.11812 6.93437 3.3086 6.82855L11.7086 2.16188C11.8898 2.06121 12.1102 2.06121 12.2914 2.16188L20.6914 6.82855C20.8818 6.93437 21 7.13514 21 7.35304Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M3.52844 7.29357L11.7086 11.8381C11.8898 11.9388 12.1102 11.9388 12.2914 11.8381L20.5 7.27777" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M12 21L12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M11.6914 11.8285L3.89139 7.49521C3.49147 7.27304 3 7.56222 3 8.01971V16.647C3 16.8649 3.11813 17.0656 3.30861 17.1715L11.1086 21.5048C11.5085 21.727 12 21.4378 12 20.9803V12.353C12 12.1351 11.8819 11.9344 11.6914 11.8285Z" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linejoin="round"></path>' },
  { badge: false, title: "Websites", text: "Eu quis mollit anim fugiat incididunt nulla.", icon: '<path d="M21 7.35304L21 16.647C21 16.8649 20.8819 17.0656 20.6914 17.1715L12.2914 21.8381C12.1102 21.9388 11.8898 21.9388 11.7086 21.8381L3.30861 17.1715C3.11814 17.0656 3 16.8649 3 16.647L2.99998 7.35304C2.99998 7.13514 3.11812 6.93437 3.3086 6.82855L11.7086 2.16188C11.8898 2.06121 12.1102 2.06121 12.2914 2.16188L20.6914 6.82855C20.8818 6.93437 21 7.13514 21 7.35304Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M3.52844 7.29357L11.7086 11.8381C11.8898 11.9388 12.1102 11.9388 12.2914 11.8381L20.5 7.27777" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M12 21L12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M11.6914 11.8285L3.89139 7.49521C3.49147 7.27304 3 7.56222 3 8.01971V16.647C3 16.8649 3.11813 17.0656 3.30861 17.1715L11.1086 21.5048C11.5085 21.727 12 21.4378 12 20.9803V12.353C12 12.1351 11.8819 11.9344 11.6914 11.8285Z" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linejoin="round"></path>' },
  { badge: false, title: "Storage", text: "Aiqua volupt cupidatat veniam esse.", icon: '<path d="M21 7.35304L21 16.647C21 16.8649 20.8819 17.0656 20.6914 17.1715L12.2914 21.8381C12.1102 21.9388 11.8898 21.9388 11.7086 21.8381L3.30861 17.1715C3.11814 17.0656 3 16.8649 3 16.647L2.99998 7.35304C2.99998 7.13514 3.11812 6.93437 3.3086 6.82855L11.7086 2.16188C11.8898 2.06121 12.1102 2.06121 12.2914 2.16188L20.6914 6.82855C20.8818 6.93437 21 7.13514 21 7.35304Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M3.52844 7.29357L11.7086 11.8381C11.8898 11.9388 12.1102 11.9388 12.2914 11.8381L20.5 7.27777" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M12 21L12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M11.6914 11.8285L3.89139 7.49521C3.49147 7.27304 3 7.56222 3 8.01971V16.647C3 16.8649 3.11813 17.0656 3.30861 17.1715L11.1086 21.5048C11.5085 21.727 12 21.4378 12 20.9803V12.353C12 12.1351 11.8819 11.9344 11.6914 11.8285Z" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linejoin="round"></path>' },
];

onMounted(() => {
  const masks = document.getElementsByClassName("mask");

  for (let i = 0; i < masks.length; i++) {
    let maskIteration = masks[i];
    let originalBackground = maskIteration.style.backgroundImage;
    maskIteration.addEventListener("mousemove", (e) => {
      const rect = maskIteration.getBoundingClientRect();
      let x = event.clientX - rect.left;
      let y = event.clientY - rect.top;
      let xy = x + " " + y;
      let lightColor = "#222";
      let gradientSize = 200;
      let webkitBackground = "-webkit-gradient(radial, " + xy + ", 0, " + xy + ", 200, from(rgba(255,255,255,0.2)), to(rgba(255,255,255,0.0))), " + originalBackground;
      let mozBackground = "-moz-radial-gradient(" + x + "px " + y + "px, circle, " + lightColor + " " + 0 + "px, " + originalBackground + " " + gradientSize + "px)";
      let userAgent = navigator.userAgent;
      if (userAgent.indexOf("Edg") > -1) {
        maskIteration.style.background = webkitBackground;
      } else if (userAgent.indexOf("Chrome") > -1) {
        maskIteration.style.background = webkitBackground;
      } else if (userAgent.indexOf("Firefox") > -1) {
        maskIteration.style.background = mozBackground;
      } else if (userAgent.indexOf("Safari") > -1) {
        maskIteration.style.background = webkitBackground;
      } else if (userAgent.indexOf("Opera") > -1) {
        maskIteration.style.background = webkitBackground;
      } else if (userAgent.indexOf("Trident") > -1 || userAgent.indexOf("MSIE") > -1) {
        maskIteration.style.background = webkitBackground;
      }
    });
    maskIteration.addEventListener("mouseleave", (e) => {
      maskIteration.style.setProperty("background", originalBackground);
    });
  }
});
</script>

<template>
  <FrontLayout title="Ecosystem">
    <template #content>
      <div class="flex flex-col max-w-7xl m-auto bg-black text-white px-10 w-full h-full py-20">
        <div class="mx-auto flex w-full max-w-7xl ah-[144px] justify-center items-center text-white">
          <h2 class="heading-1">Discover Realmsz Ecosystem</h2>
          <svg class="w-40 fill-white ml-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M395.137 132.332c66.047 13.629 99.84 54.91 101.523 57l-33.234 26.773c-1.54-1.855-38.188-45.437-111.446-45.437-38.957 0-94.464 38.336-96 42.664 0-22.312 7.465-50.453 24.403-72.234C206.188 120.02 173.012 65.578 170.645 64c.406.277 55.66 11.2 110.316 17.3-17.559-25.214-24.98-56.234-24.98-81.3 0 4.523 31.359 33.43 67.84 50.793-2.626-14.27-3.84-31.102-3.84-50.793 0 3.328 17.234 25.516 39.187 42.668h14.144c17.45 0 33.407 8.383 43.458 21.332h95.21v42.668h-85.355c-.898 0-27.648.445-31.512 25.664Zm-36.653 96.531c-5.949-4.414-9.683-9.277-15.593-14.25C326.805 218.453 224 277.355 224 277.355c-5.566-17.855-10.668-42.668-10.668-64 0-16.683 2.883-33.855 8.191-50.175a244.5 244.5 0 0 1-45.543-31.106c-14.87 19.031-25.75 42.39-26.582 67.52l-64.066 45.761 74.563 9.32c3.347 7.641 7.378 14.766 11.968 21.462L127.98 320.02h87.168a345 345 0 0 0 14.313 10.37l-26.816 53.63 81.878-18.196c20.075 13.442 35.457 26.473 35.457 39.531 0 25.32-48.855 32-74.668 32-84.757 0-202.667-85.996-202.667-170.668 0-53.332 10.667-106.667 35.906-148.906l28.094 52.906C117.289 123.649 131.137 68.845 127.168.02 86.828 16.258 40.383 57.898 0 106.668h44.29C9.046 165.441 0 222.848 0 266.668 0 385.898 137.066 512 266.668 512h42.664c64.32 0 160-31.254 160-117.332 0-83.605-64.062-131.117-110.848-165.805m0 0" /></svg>
        </div>
        <p class="heading-3 w-2/3 mb-10 mr-10 text-white">Realmsz is a powerful, all-in-one platform that empowers you to organize, create, connect, and manage your digital life. Access 10 integrated modules - Focus, Plans, Profile, Network, AI Assistance (Milai), Studio, Finance, Crypto, Websites, and Storage - via web or app. Generate content, build websites, manage finances, and connect with like-minded individuals, all in one place.</p>
        <div class="grid w-full grid-cols-1 gap-5 text-white md:grid-cols-2 h-full">
          <Link v-for="resource in categories" :key="resource.title" :href="'#route(resource.title)'" :class="['mask relative flex flex-col rounded-2xl border border-white/5 backdrop-blur-3xl transition-colors', resource.badge ? 'crypto-gradient pointer-events-none' : 'crypto-gradient cursor-pointer']" :style="resource.badge ? 'background-image: linear-gradient(34deg, hsla(0, 0%, 55%, 0.13), hsla(0, 0%, 21%, 0.21))' : 'background-image: linear-gradient(55deg, hsl(211, 100%, 13%), hsl(179, 100%, 13%))'">
            <div v-if="resource.badge" class="badge absolute right-2 top-2 z-30">Coming Soon</div>
            <div class="flex flex-col gap-5 p-10">
              <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="icons" v-html="resource.icon"></svg>
                <h3 class="heading-3 text-white">{{ resource.title }}</h3>
              </div>
              <p class="text-xs">{{ resource.text }}</p>
            </div>
          </Link>
        </div>
      </div>
    </template>
  </FrontLayout>
</template>

<style scoped lang="pcss">
.crypto-gradient {
  background-image: linear-gradient(to right, hsl(211, 100%, 10%), hsl(179, 100%, 10%));
}
</style>
