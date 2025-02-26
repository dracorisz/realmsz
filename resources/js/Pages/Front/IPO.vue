<script setup>
import FrontLayout from "@/Layouts/FrontLayout.vue";
</script>

<template>
  <FrontLayout title="IPO">
    <template #content>
      <div class="m-auto flex h-full w-full max-w-7xl flex-col bg-black px-10 py-20 text-white">
        <div class="mx-auto flex w-full max-w-7xl ah-[144px] justify-start items-center text-white relative z-40">
          <h2 class="heading-1">Initial Public Offering (IPO)</h2>
        </div>
        <div class="flex flex-col gap-3">
          <p class="text-sm">35% ownership available for sale!</p>
          <p class="text-sm">Complete sales transparency will be maintained among stakeholders.</p>
        </div>
        <ul class="space-y-5 my-10">
          <!-- <li class="text-lg text-accent font-bold capitalize">Pre-Launch 🖥️</li> -->
          <li class="text-lg">• 1-5%: $1,000 per 1% ownership. <span class="ml-1 text-sm text-accent">(10% off for 5% bundle)</span></li>
          <li class="text-lg">• 6-15%: $2,000 per 1% ownership. <span class="ml-1 text-sm text-warning">(Sales income is non-transferable or non-divisible.)</span></li>
          <li class="text-lg">• 16-25%: $5,000 per 1% ownership. <span class="ml-1 text-sm text-success">(Sales income will be distributed among stakeholders.)</span></li>
          <!-- <li class="text-lg text-success font-bold capitalize">Launch 🚀</li> -->
          <li class="text-lg">• 26-35%: Price and sales will be determined by stakeholders (if any).</li>
        </ul>
        <div class="flex flex-col gap-3">

          <p class="text-sm">15%+ ownership: Voting rights on key decisions and influence on roadmap.</p>
          <p class="text-sm">25%+ ownership: Full access to the codebase.</p>
        </div>
      </div>
    </template>
  </FrontLayout>
</template>

