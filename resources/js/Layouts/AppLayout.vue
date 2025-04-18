<script setup>
import { ref } from "vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import ApplicationMark from "@/Components/ApplicationMark.vue";
import AppBanner from "@/Components/AppBanner.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
const page = usePage();
const assetUrl = import.meta.env.VITE_ASSET_URL;

defineProps({
  title: String,
  hasHero: {
    type: Boolean,
    default: false,
  },
});

const expandNav = ref(true);
const userNav = ref(true);
const sidebarPosition = ref(true);

const logout = () => {
  router.post(route("logout"));
};

const canAccess = (allowedRoles) => {
  return page.props.auth?.user?.role ? allowedRoles.includes(page.props.auth.user.role) : false;
};
</script>

<template>
  <Head :title="title" />
  <div id="dragref" class="relative flex h-screen w-full overflow-hidden bg-black" :class="sidebarPosition ? 'flex-row' : 'flex-row-reverse'">
    
   
    <AppBanner />
    <nav class="relative z-50 flex h-full flex-col bg-black bg-gradient-to-br from-primary/10 to-accent/5 transition-all duration-300 ease-in-out" :class="expandNav ? 'aw-[233px]' : 'aw-[55px]'">
      <div class="flex h-full w-full flex-col items-start justify-start">
        <div class="gradient flex w-full items-center gap-3 bg-black py-2" :class="expandNav ? 'justify-start px-5' : 'flex-col px-px'">
          <Link :href="route('focus')">
            <ApplicationLogo v-if="expandNav" class="h-8" :link="false" />
            <ApplicationMark v-else class="h-8" />
          </Link>
          <PrimaryButton :onlyIcon="true" :class="expandNav && 'ml-auto'" color="#1a1a1a" opacity="100" hoverOpacity="100" @click="expandNav = !expandNav">
            <template #icon>
              <svg :class="!expandNav && '-scale-x-100'" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icons">
                <polyline points="11 17 6 12 11 7"></polyline>
                <polyline points="18 17 13 12 18 7"></polyline>
              </svg>
            </template>
          </PrimaryButton>
        </div>
        <div class="relative z-40 flex h-full w-full flex-col items-center py-5" :class="expandNav ? 'justify-between px-5' : 'justify-center px-px'">
          <div class="relative z-40 flex h-full w-full flex-col gap-5">
            <div class="flex w-full items-center">
              <div class="flex w-full items-center gap-3 text-sm text-white" :class="!expandNav ? 'flex-col' : 'justify-start'">
                <!-- <img class="rounded-full object-cover ah-[36px]" :src="page.props.auth.user.profile_photo_url" :alt="page.props.auth.user.name" /> -->
                <div :class="expandNav ? 'mr-auto flex flex-col text-xs' : 'hidden'">
                  <span>{{ page.props.auth?.user?.name?.split(" ")[0] || 'User' }}</span>
                  <span>{{ page.props.auth?.user?.name?.split(" ")[1] || '' }}</span>
                </div>
                <PrimaryButton v-if="expandNav" :onlyIcon="true" class="w-full" color="#1a1a1a" opacity="100" hoverOpacity="100" @click="userNav = !userNav">
                  <template #icon>
                    <svg :class="userNav && '-scale-y-100'" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icons"><polyline points="6 9 12 15 18 9"></polyline></svg>
                  </template>
                </PrimaryButton>
                <div v-else="expandNav" class="flex items-center justify-center rounded-2xl border border-accent text-zx text-accent aw-[36px]">Pro</div>
              </div>
            </div>
            <div v-if="userNav && expandNav" :class="['flex w-full flex-col items-center gap-3', expandNav && 'justify-between']">
              <div class="bg-gradient-to-br from-black to-accent/40 flex flex-col gap-3 rounded-2xl border border-accent/90 p-5 text-xs text-white/80">
                <span class="text-accent">Pro Membership</span>
                <span>Quis laboris consequat id sunt elit quis. Nostrud magna cupidatat.</span>
              </div>
            </div>
            <div :class="['flex h-full w-full flex-col items-center gap-3', expandNav && 'justify-between']">
              <PrimaryButton :opaque="true" :onlyIcon="!expandNav" class="w-full" color="#00c2c5" opacity="30" hoverOpacity="50" type="link" href="focus" :toggled="route().current('focus')">
                <template #icon>
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="icons" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                  </svg>
                </template>
                <div :class="expandNav ? 'inline-block' : 'hidden'">Focus</div>
              </PrimaryButton>
              <PrimaryButton :opaque="true" :onlyIcon="!expandNav" class="w-full" color="#00c2c5" opacity="30" hoverOpacity="50" type="link" href="plans" :toggled="route().current('plans')">
                <template #icon>
                  <svg width="24" height="24" class="icons" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor">
                    <path d="M9 6L20 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M3.80002 5.79999L4.60002 6.59998L6.60001 4.59999" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M3.80002 11.8L4.60002 12.6L6.60001 10.6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M3.80002 17.8L4.60002 18.6L6.60001 16.6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M9 12L20 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M9 18L20 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                  </svg>
                </template>
                <div :class="expandNav ? 'inline-block' : 'hidden'">Plans</div>
              </PrimaryButton>
              <PrimaryButton :opaque="true" :onlyIcon="!expandNav" class="w-full" color="#00c2c5" opacity="30" hoverOpacity="50" type="link" href="profile" :toggled="route().current('profile')">
                <template #icon>
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="icons" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                  </svg>
                </template>
                <div :class="expandNav ? 'inline-block' : 'hidden'">Profile</div>
              </PrimaryButton>
              <PrimaryButton v-if="canAccess(['manager'])" :opaque="true" :onlyIcon="!expandNav" class="w-full" color="#00c2c5" opacity="30" hoverOpacity="50" type="link" href="network" :toggled="route().current('network')">
                <template #icon>
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="icons" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                  </svg>
                </template>
                <div :class="expandNav ? 'inline-block' : 'hidden'">Network</div>
              </PrimaryButton>
              <PrimaryButton :opaque="true" :onlyIcon="!expandNav" class="w-full" color="#00c2c5" opacity="30" hoverOpacity="50" type="link" href="studio" :toggled="route().current('studio')">
                <template #icon>
                  <svg width="24" height="24" class="icons" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21 7.35304L21 16.647C21 16.8649 20.8819 17.0656 20.6914 17.1715L12.2914 21.8381C12.1102 21.9388 11.8898 21.9388 11.7086 21.8381L3.30861 17.1715C3.11814 17.0656 3 16.8649 3 16.647L2.99998 7.35304C2.99998 7.13514 3.11812 6.93437 3.3086 6.82855L11.7086 2.16188C11.8898 2.06121 12.1102 2.06121 12.2914 2.16188L20.6914 6.82855C20.8818 6.93437 21 7.13514 21 7.35304Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M3.52844 7.29357L11.7086 11.8381C11.8898 11.9388 12.1102 11.9388 12.2914 11.8381L20.5 7.27777" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M12 21L12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M11.6914 11.8285L3.89139 7.49521C3.49147 7.27304 3 7.56222 3 8.01971V16.647C3 16.8649 3.11813 17.0656 3.30861 17.1715L11.1086 21.5048C11.5085 21.727 12 21.4378 12 20.9803V12.353C12 12.1351 11.8819 11.9344 11.6914 11.8285Z" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linejoin="round"></path>
                  </svg>
                </template>
                <div :class="expandNav ? 'inline-block' : 'hidden'">Studio</div>
              </PrimaryButton>
              <PrimaryButton :opaque="true" :onlyIcon="!expandNav" class="w-full" color="#00c2c5" opacity="30" hoverOpacity="50" type="link" href="milai" :toggled="route().current('milai')">
                <template #icon>
                  <svg width="24" height="24" class="icons" viewBox="0 0 24 24" stroke-width="2" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor">
                    <path d="M10.5 9C10.5 9 10.5 7 9.5 5C13.5 5 16 7.49997 16 7.49997C16 7.49997 19.5 7 22 12C21 17.5 16 18 16 18L12 20.5C12 20.5 12 19.5 12 17.5C9.5 16.5 6.99998 14 7 12.5C7.00001 11 10.5 9 10.5 9ZM10.5 9C10.5 9 11.5 8.5 12.5 8.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M2 9.5L3 12.5L2 15.5C2 15.5 7 15.5 7 12.5C7 9.5 2 9.5 2 9.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M17 12.01L17.01 11.9989" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                  </svg>
                </template>
                <div :class="expandNav ? 'inline-block' : 'hidden'">Milai</div>
              </PrimaryButton>
              
              <PrimaryButton :opaque="true" :onlyIcon="!expandNav" class="w-full" color="#00c2c5" opacity="30" hoverOpacity="50" type="link" href="finance" :toggled="route().current('finance')">
                <template #icon>
                  <svg width="24" height="24" class="icons" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21 7.35304L21 16.647C21 16.8649 20.8819 17.0656 20.6914 17.1715L12.2914 21.8381C12.1102 21.9388 11.8898 21.9388 11.7086 21.8381L3.30861 17.1715C3.11814 17.0656 3 16.8649 3 16.647L2.99998 7.35304C2.99998 7.13514 3.11812 6.93437 3.3086 6.82855L11.7086 2.16188C11.8898 2.06121 12.1102 2.06121 12.2914 2.16188L20.6914 6.82855C20.8818 6.93437 21 7.13514 21 7.35304Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M3.52844 7.29357L11.7086 11.8381C11.8898 11.9388 12.1102 11.9388 12.2914 11.8381L20.5 7.27777" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M12 21L12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M11.6914 11.8285L3.89139 7.49521C3.49147 7.27304 3 7.56222 3 8.01971V16.647C3 16.8649 3.11813 17.0656 3.30861 17.1715L11.1086 21.5048C11.5085 21.727 12 21.4378 12 20.9803V12.353C12 12.1351 11.8819 11.9344 11.6914 11.8285Z" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linejoin="round"></path>
                  </svg>
                </template>
                <div :class="expandNav ? 'inline-block' : 'hidden'">Finance</div>
              </PrimaryButton>
              <PrimaryButton :opaque="true" :onlyIcon="!expandNav" class="w-full" color="#00c2c5" opacity="30" hoverOpacity="50" type="link" href="crypto" :toggled="route().current('crypto')">
                <template #icon>
                  <svg width="24" height="24" class="icons" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21 7.35304L21 16.647C21 16.8649 20.8819 17.0656 20.6914 17.1715L12.2914 21.8381C12.1102 21.9388 11.8898 21.9388 11.7086 21.8381L3.30861 17.1715C3.11814 17.0656 3 16.8649 3 16.647L2.99998 7.35304C2.99998 7.13514 3.11812 6.93437 3.3086 6.82855L11.7086 2.16188C11.8898 2.06121 12.1102 2.06121 12.2914 2.16188L20.6914 6.82855C20.8818 6.93437 21 7.13514 21 7.35304Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M3.52844 7.29357L11.7086 11.8381C11.8898 11.9388 12.1102 11.9388 12.2914 11.8381L20.5 7.27777" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M12 21L12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M11.6914 11.8285L3.89139 7.49521C3.49147 7.27304 3 7.56222 3 8.01971V16.647C3 16.8649 3.11813 17.0656 3.30861 17.1715L11.1086 21.5048C11.5085 21.727 12 21.4378 12 20.9803V12.353C12 12.1351 11.8819 11.9344 11.6914 11.8285Z" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linejoin="round"></path>
                  </svg>
                </template>
                <div :class="expandNav ? 'inline-block' : 'hidden'">Crypto</div>
              </PrimaryButton>
              <PrimaryButton :opaque="true" :onlyIcon="!expandNav" class="w-full" color="#00c2c5" opacity="30" hoverOpacity="50" type="link" href="websites" :toggled="route().current('websites')">
                <template #icon>
                  <svg width="24" height="24" class="icons" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21 7.35304L21 16.647C21 16.8649 20.8819 17.0656 20.6914 17.1715L12.2914 21.8381C12.1102 21.9388 11.8898 21.9388 11.7086 21.8381L3.30861 17.1715C3.11814 17.0656 3 16.8649 3 16.647L2.99998 7.35304C2.99998 7.13514 3.11812 6.93437 3.3086 6.82855L11.7086 2.16188C11.8898 2.06121 12.1102 2.06121 12.2914 2.16188L20.6914 6.82855C20.8818 6.93437 21 7.13514 21 7.35304Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M3.52844 7.29357L11.7086 11.8381C11.8898 11.9388 12.1102 11.9388 12.2914 11.8381L20.5 7.27777" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M12 21L12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M11.6914 11.8285L3.89139 7.49521C3.49147 7.27304 3 7.56222 3 8.01971V16.647C3 16.8649 3.11813 17.0656 3.30861 17.1715L11.1086 21.5048C11.5085 21.727 12 21.4378 12 20.9803V12.353C12 12.1351 11.8819 11.9344 11.6914 11.8285Z" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linejoin="round"></path>
                  </svg>
                </template>
                <div :class="expandNav ? 'inline-block' : 'hidden'">Websites</div>
              </PrimaryButton>
              <PrimaryButton :opaque="true" :onlyIcon="!expandNav" class="w-full" color="#00c2c5" opacity="30" hoverOpacity="50" type="link" href="storage" :toggled="route().current('storage')">
                <template #icon>
                  <svg width="24" height="24" class="icons" stroke-width="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21 7.35304L21 16.647C21 16.8649 20.8819 17.0656 20.6914 17.1715L12.2914 21.8381C12.1102 21.9388 11.8898 21.9388 11.7086 21.8381L3.30861 17.1715C3.11814 17.0656 3 16.8649 3 16.647L2.99998 7.35304C2.99998 7.13514 3.11812 6.93437 3.3086 6.82855L11.7086 2.16188C11.8898 2.06121 12.1102 2.06121 12.2914 2.16188L20.6914 6.82855C20.8818 6.93437 21 7.13514 21 7.35304Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M3.52844 7.29357L11.7086 11.8381C11.8898 11.9388 12.1102 11.9388 12.2914 11.8381L20.5 7.27777" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M12 21L12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M11.6914 11.8285L3.89139 7.49521C3.49147 7.27304 3 7.56222 3 8.01971V16.647C3 16.8649 3.11813 17.0656 3.30861 17.1715L11.1086 21.5048C11.5085 21.727 12 21.4378 12 20.9803V12.353C12 12.1351 11.8819 11.9344 11.6914 11.8285Z" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linejoin="round"></path>
                  </svg>
                </template>
                <div :class="expandNav ? 'inline-block' : 'hidden'">Storage</div>
              </PrimaryButton>
              <form @submit.prevent="logout" class="mt-auto flex items-center self-center" :class="expandNav && 'w-full'">
                <PrimaryButton :opaque="true" :onlyIcon="!expandNav" class="w-full" color="#00c2c5" opacity="30" hoverOpacity="50" type="submit" :toggled="false">
                  <template #icon>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="icons" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                      <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                      <polyline points="16 17 21 12 16 7" />
                      <line x1="21" x2="9" y1="12" y2="12" />
                    </svg>
                  </template>
                  <div :class="expandNav ? 'inline-block' : 'hidden'">Logout</div>
                </PrimaryButton>
              </form>
            </div>
          </div>
          <!-- <img :src="`${process.env.VITE_ASSET_URL}/images/backgrounds/background04.jpg`" alt="Sidebar Background Image" class="absolute inset-0 z-0 h-full w-full object-cover opacity-20" /> -->
          <img v-if="hasHero" :src="`${assetUrl}/images/milai.jpg`" class="opacity-50 absolute w-full h-full object-cover z-0 inset-0" />
        </div>
      </div>
    </nav>
    <!-- <img v-if="hasHero" :src="`${process.env.VITE_ASSET_URL}/images/milai.jpg`" class="opacity-5 absolute w-full h-full object-cover z-0 inset-0" /> -->
    <div class="relative z-40 flex h-full w-full flex-col overflow-hidden" :class="route().current('studio') || route().current('focus') ? 'px-0' : 'px-5'">
      <header v-if="$slots.header" class="mx-auto flex w-full flex-col items-center justify-center gap-5 py-5 text-white">
        <slot name="header" />
      </header>
      <main :class="['flex flex-1 flex-col overflow-hidden', $slots.header ? 'pb-5' : route().current('studio') || route().current('focus') ? 'py-0' : 'py-5']">
        <slot />
      </main>
    </div>
  </div>
</template>
