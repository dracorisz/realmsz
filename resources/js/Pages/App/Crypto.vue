<!-- <script setup>
import AppLayout from "@/Layouts/AppLayout.vue";

const chains = [
  { id: "eth", name: "Ethereum" },
  { id: "sol", name: "Solana" },
  { id: "matic", name: "Polygon" },
  // { id: "avax", name: "Avalanche" },
  // { id: "ftm", name: "Fantom" },
  { id: "arb", name: "Arbitrum" },
  // { id: "op", name: "Optimism" },
  // { id: "celo", name: "Celo" },
  // { id: "near", name: "Near Protocol" },
];
</script>

<template>
  <AppLayout title="Crypto">
    <div class="grid w-full h-full grid-cols-2 gap-5">
      <div v-for="c in chains" :key="c.id" class="crypto-gradient group">
        <img :src="`${process.env.VITE_ASSET_URL}/images/chains/${c.name.replaceAll(' ', '-').toLowerCase()}-${c.id}-logo.svg`" class="as-[55px] group-hover:animate-spin-fast" />
        <span class="text-sm text-white/70 group-hover:text-white">{{ c.name }}</span>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped lang="pcss">
.crypto-gradient {
  @apply relative z-[1] flex w-full cursor-pointer flex-col items-center justify-center gap-5 rounded-2xl border-white/5 p-10;
  background-image: linear-gradient(34deg, hsla(0, 0%, 55%, 0.13), hsla(0, 0%, 21%, 0.21));
}

.crypto-gradient::before {
  @apply absolute inset-0 z-[-1] rounded-2xl opacity-0 transition-opacity duration-300 ease-linear content-[""];
  background-image: linear-gradient(to right, hsl(211, 100%, 10%), hsl(179, 100%, 10%));
}

.crypto-gradient:hover::before {
  opacity: 1;
}
</style>
 -->

 <script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
// const assetUrl = import.meta.env.VITE_ASSET_URL;
</script>

<template>
  <AppLayout title="Module">
    <div class="h-full flex items-center justify-center w-full gap-2 text-white/70">
      <span>Module under development</span>
      <span class="animate-pulse">_</span>
    </div>
  </AppLayout>
</template>