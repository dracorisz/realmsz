<script setup>
import FrontLayout from "@/Layouts/FrontLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const assetUrl = import.meta.env.VITE_ASSET_URL;

const roadmap = [
  {
    version: "v0.0.5-init",
    items: [
      "Setup accounts, emailing",
      "Cofounding partnerships / initial $5000",
      "(Optimization) Integrate Slack for logging and notifications"
    ]
  },
  {
    version: "v0.0.6",
    items: [
      "Launch dracoscopia.com",
      "Register company or Product",
      "subscribe to Google Cloud for $300 credit",
      "Initial responsiveness improvements and complete front pages"
    ]
  },
  {
    version: "v0.0.7",
    items: [
      "Add pricing and payments system (membership status, renewal dates, payment methods, etc.)",
      "Enhance documentation",
      "Expand personal fields, verification, and subscriptions on profile and add profile images",
      "Main: Implement Cookies Compliance, Terms of Service, and Privacy Policy",
      "Complete plans with collaborative sharing"
    ]
  },
  {
    version: "v0.0.8 and Onward",
    items: [
      "Plans: Add/Delete statuses, priorities, categories, and ordering",
      "Crypto: Initial UI and functionalities (users can create and deploy tokens to their wallets, MetaMask integration, NFTs gallery, creation, listings, and studio assets)",
      "Recurring status and date sync",
      "Profile: Linking account and SSOs, activity and stats dashboards",
      "Main: Spin up MVP dracoscopia.com, add Uniswap liquidity, update cover image to WebP, fix tooltips, button hover effects, front pages SEO, update favicon",
      "Milai: Refine chat name generation and history modal, toggle options for web search/ideas/maps, attach/drop/paste files with URL parsing, text-to-speech integration, etc.",
      "Focus: Enhance task completion animations, active task cards, and button functionalities",
      "Plans: Implement filters, AI-assisted description, assign/unassign users, add image support, file/link/code parsing, state saving, checklist functionalities, multiple views (Mind Maps, Gantt, Kanban, Calendar), and more.",
      "Deployment: Create demo accounts with sample data, CI/CD on AWS (EBS), email functionalities, and Google Analytics integration.",
      "Optimization: Handle token mismatch errors, session management, maintenance mode, integrate Search Console, merge node modules with self-hosted copies, PWA service workers, track events via Slack, apply rel='nofollow' for SEO, refactor pagination, unify UI transitions, replace Laravel logging with Slack integration, update scrollbars, add aria-labels, test on Lighthouse/PageSpeed, implement try/catch on server requests, and update tech stacks (Vite, TailwindCSS), among other improvements.",
      "Studio & Network: Roll out initial UI functionalities for image generation, video, 3D avatars, user CRUD with permissions, social and network features.",
      "Wallet & Websites: Enable bank/wallet connections, budget tracking, and CMS-like functionalities.",
      "Post-launch: Deploy on app stores, set up newsletter and social media marketing, customer support, register trademark, and add multilingual and LTR support.",
      "Feature: Generate branding guidelines and resumes based on profiles and plans."
    ]
  }
];
</script>

<template>
  <FrontLayout title="Roadmap">
    <template #content>
    <div class="relative flex h-full w-full flex-col items-center justify-start gap-10 px-5 py-20 text-white">
      <div class="flex w-full max-w-4xl flex-col items-center justify-center gap-5">
        <h1 class="text-4xl font-bold text-white">Development Roadmap</h1>
        <p class="text-center text-white/70">Our journey to building the future of digital experiences</p>
      </div>

      <div class="flex w-full max-w-4xl flex-col gap-10">
        <div v-for="(phase, index) in roadmap" :key="phase.version" class="roadmap-phase">
          <div class="flex items-center gap-3">
            <div class="h-2 w-2 rounded-full bg-accent"></div>
            <h2 class="text-2xl font-bold text-accent">{{ phase.version }}</h2>
          </div>
          <div class="mt-5 grid gap-3 pl-5">
            <div v-for="(item, itemIndex) in phase.items" :key="itemIndex" class="flex items-start gap-3">
              <div class="mt-1.5 h-1.5 w-1.5 rounded-full bg-white/20"></div>
              <p class="text-white/80">{{ item }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  </FrontLayout>
</template>

<style scoped lang="pcss">
.roadmap-phase {
  @apply relative rounded-2xl border border-white/5 bg-black/30 p-5 backdrop-blur-sm;
  background-image: linear-gradient(34deg, hsla(0, 0%, 55%, 0.13), hsla(0, 0%, 21%, 0.21));
}

.roadmap-phase::before {
  @apply absolute inset-0 z-[-1] rounded-2xl opacity-0 transition-opacity duration-300 ease-linear content-[""];
  background-image: linear-gradient(to right, hsl(211, 100%, 10%), hsl(179, 100%, 10%));
}

.roadmap-phase:hover::before {
  opacity: 1;
}
</style> 