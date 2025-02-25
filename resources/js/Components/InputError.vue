<script setup>
defineProps({
  message: String,
});
</script>

<template>
  <div v-show="message">
    <p class="text-sm text-warning -mt-1 ml-px">
      {{ message }}
    </p>
  </div>
</template>
