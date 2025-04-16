<script setup>
import FrontLayout from "@/Layouts/FrontLayout.vue";
import { onMounted } from "vue";
import { gsap } from "gsap";

onMounted(() => {
  const heading = document.querySelector(".heading-1");
  const description = document.querySelectorAll("p");
  const listItems = document.querySelectorAll("li");

  // Initial setup
  gsap.set([heading, ...description, ...listItems], { opacity: 0, y: 20 });

  // Animate elements in sequence
  const timeline = gsap.timeline();
  timeline
    .to(heading, { duration: 1, opacity: 1, y: 0, ease: "power2.out" })
    .to(description, { duration: 0.8, opacity: 1, y: 0, stagger: 0.2, ease: "power2.out" }, "-=0.5")
    .to(listItems, { duration: 0.6, opacity: 1, y: 0, stagger: 0.1, ease: "power2.out" }, "-=0.3");
});
</script>

<template>
  <FrontLayout title="IPO">
    <template #content>
      <div class="m-auto flex h-full w-full max-w-7xl flex-col bg-black px-10 py-20 text-white">
        <div class="mx-auto flex w-full max-w-7xl ah-[144px] justify-start items-center text-white relative z-40">
          <h2 class="heading-1">Initial Public Offering (IPO)</h2>
        </div>
        <div class="flex flex-col gap-3">
          <p class="text-sm">30% ownership available for sale!</p>
          <p class="text-sm">Complete sales transparency will be maintained among stakeholders.</p>
        </div>
        <ul class="space-y-5 my-10">
          <!-- <li class="text-lg text-accent font-bold capitalize">Pre-Launch 🖥️</li> -->
          <li class="text-lg">• 1-5%: $1,000 per 1% ownership. <span class="ml-1 text-sm text-accent">(10% off for 5% bundle.)</span></li>
          <li class="text-lg">• 6-15%: $3,000 per 1% ownership. <span class="ml-1 text-sm text-warning">(Sales income is non-transferable or non-divisible.)</span></li>
          <li class="text-lg">• 16-25%: $8,000 per 1% ownership. <span class="ml-1 text-sm text-success">(Sales income will be distributed among stakeholders.)</span></li>
          <!-- <li class="text-lg text-success font-bold capitalize">Launch 🚀</li> -->
          <li class="text-lg">• 26-30%: Price and sales will be determined by stakeholders.</li>
        </ul>
        <div class="flex flex-col gap-3">

          <p class="text-sm">10%+ ownership: Voting rights on key decisions and influence on roadmap.</p>
          <p class="text-sm">20%+ ownership: Full access to the codebase.</p>
        </div>
      </div>
    </template>
  </FrontLayout>
</template>

