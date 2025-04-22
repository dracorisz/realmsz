<template>
  <FrontLayout title="Content Details | Realmsz Digital Asset Management">
    <div class="relative">
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
              <div class="mb-6">
                <h3 class="text-2xl font-bold text-gray-900 content-title">{{ content.title }}</h3>
                <p class="mt-2 text-gray-600 content-description">{{ content.description }}</p>
              </div>

              <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Content Details</h4>
                <div class="bg-gray-50 p-4 rounded-lg content-details">
                  <div class="prose max-w-none">
                    <div v-html="content.content"></div>
                  </div>
                </div>
              </div>

              <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Features</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div
                    v-for="feature in content.features"
                    :key="feature.name"
                    class="bg-gray-50 p-4 rounded-lg feature-card"
                  >
                    <div class="flex items-center">
                      <svg
                        v-if="feature.enabled"
                        class="h-5 w-5 text-green-500 mr-2"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M5 13l4 4L19 7"
                        />
                      </svg>
                      <div>
                        <p class="font-medium text-gray-900">{{ feature.name }}</p>
                        <p class="text-sm text-gray-500">{{ feature.description }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Metadata</h4>
                <div class="bg-gray-50 p-4 rounded-lg metadata-card">
                  <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                    <div>
                      <dt class="text-sm font-medium text-gray-500">Package</dt>
                      <dd class="mt-1 text-sm text-gray-900">{{ content.metadata.package_name }}</dd>
                    </div>
                    <div>
                      <dt class="text-sm font-medium text-gray-500">Generated At</dt>
                      <dd class="mt-1 text-sm text-gray-900">
                        {{ formatDate(content.metadata.generated_at) }}
                      </dd>
                    </div>
                    <div>
                      <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                      <dd class="mt-1 text-sm text-gray-900">
                        {{ formatDate(content.metadata.updated_at) }}
                      </dd>
                    </div>
                  </dl>
                </div>
              </div>

              <div class="flex justify-end space-x-4">
                <Link
                  :href="route('content.preview', package.slug)"
                  class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 preview-button"
                >
                  Preview Content
                </Link>
                <Link
                  :href="route('content.edit', package.slug)"
                  class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 edit-button"
                >
                  Edit Content
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </FrontLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import FrontLayout from '@/Layouts/FrontLayout.vue';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

const props = defineProps({
  content: {
    type: Object,
    required: true,
  },
  package: {
    type: Object,
    required: true,
  },
});

const formatDate = (date) => {
  return new Date(date).toLocaleString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
};

onMounted(() => {
  gsap.registerPlugin(ScrollTrigger);

  // Title and description animations
  const titleTimeline = gsap.timeline();
  titleTimeline
    .from(".content-title", {
      duration: 0.8,
      y: 30,
      opacity: 0,
      ease: "power2.out"
    })
    .from(".content-description", {
      duration: 0.8,
      y: 20,
      opacity: 0,
      ease: "power2.out"
    }, "-=0.4");

  // Content details animations
  const contentTimeline = gsap.timeline({
    scrollTrigger: {
      trigger: ".content-details",
      start: "top center+=100",
      toggleActions: "play none none reverse"
    }
  });

  contentTimeline.from(".content-details", {
    duration: 0.8,
    y: 30,
    opacity: 0,
    ease: "power2.out"
  });

  // Features section animations
  const featuresTimeline = gsap.timeline({
    scrollTrigger: {
      trigger: ".feature-card",
      start: "top center+=100",
      toggleActions: "play none none reverse"
    }
  });

  featuresTimeline.from(".feature-card", {
    duration: 0.8,
    y: 50,
    opacity: 0,
    stagger: 0.1,
    ease: "back.out(1.7)"
  });

  // Metadata section animations
  const metadataTimeline = gsap.timeline({
    scrollTrigger: {
      trigger: ".metadata-card",
      start: "top center+=100",
      toggleActions: "play none none reverse"
    }
  });

  metadataTimeline.from(".metadata-card", {
    duration: 0.8,
    y: 30,
    opacity: 0,
    ease: "power2.out"
  });

  // Button animations
  const buttonTimeline = gsap.timeline({
    scrollTrigger: {
      trigger: ".preview-button",
      start: "top center+=100",
      toggleActions: "play none none reverse"
    }
  });

  buttonTimeline
    .from(".preview-button", {
      duration: 0.8,
      y: 20,
      opacity: 0,
      ease: "power2.out"
    })
    .from(".edit-button", {
      duration: 0.8,
      y: 20,
      opacity: 0,
      ease: "power2.out"
    }, "-=0.4");

  // Hover animations for cards
  gsap.utils.toArray(".feature-card, .metadata-card, .content-details").forEach(card => {
    card.addEventListener("mouseenter", () => {
      gsap.to(card, {
        duration: 0.3,
        y: -5,
        scale: 1.02,
        boxShadow: "0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)",
        ease: "power2.out"
      });
    });

    card.addEventListener("mouseleave", () => {
      gsap.to(card, {
        duration: 0.3,
        y: 0,
        scale: 1,
        boxShadow: "none",
        ease: "power2.out"
      });
    });
  });
});
</script>

<style scoped>
.feature-card, .metadata-card, .content-details {
  transition: all 0.3s ease;
  border-radius: 0.5rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.feature-card:hover, .metadata-card:hover, .content-details:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

@media (max-width: 768px) {
  .feature-card, .metadata-card, .content-details {
    text-align: center;
  }
}
</style> 