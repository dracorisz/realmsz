<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  images: {
    type: Array,
    required: true
  },
  height: {
    type: String,
    default: 'h-96'
  }
});

const currentIndex = ref(0);
let interval;

const nextSlide = () => {
  currentIndex.value = (currentIndex.value + 1) % props.images.length;
};

const prevSlide = () => {
  currentIndex.value = (currentIndex.value - 1 + props.images.length) % props.images.length;
};

onMounted(() => {
  interval = setInterval(nextSlide, 3000);
});

onUnmounted(() => {
  clearInterval(interval);
});
</script>

<template>
  <div class="relative w-full">
    <div :class="['relative overflow-hidden', height]">
      <div 
        v-for="(image, index) in images" 
        :key="index"
        class="absolute inset-0 transition-opacity duration-500"
        :class="{ 'opacity-100': currentIndex === index, 'opacity-0': currentIndex !== index }"
      >
        <img 
          :src="image.src" 
          :alt="image.alt || 'Slide image'"
          class="w-full h-full object-cover rounded-2xl"
        />
        <div v-if="image.title" class="absolute inset-0 flex items-center justify-center bg-black/50 rounded-2xl">
          <h2 class="text-3xl md:text-6xl text-center text-white px-4">{{ image.title }}</h2>
        </div>
      </div>
    </div>
    
    <!-- Navigation Buttons -->
    <button 
      @click="prevSlide"
      class="absolute left-4 top-1/2 -translate-y-1/2 bg-black/50 text-white p-2 rounded-full hover:bg-black/70 transition-colors"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
      </svg>
    </button>
    
    <button 
      @click="nextSlide"
      class="absolute right-4 top-1/2 -translate-y-1/2 bg-black/50 text-white p-2 rounded-full hover:bg-black/70 transition-colors"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
      </svg>
    </button>
    
    <!-- Pagination Dots -->
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2">
      <button 
        v-for="(_, index) in images" 
        :key="index"
        @click="currentIndex = index"
        class="w-2 h-2 rounded-full transition-colors"
        :class="currentIndex === index ? 'bg-white' : 'bg-white/50'"
      ></button>
    </div>
  </div>
</template> 