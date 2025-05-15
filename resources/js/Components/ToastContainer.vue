<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useToast, POSITION } from 'vue-toastification';

// We don't need to manage the toasts here, as vue-toastification handles that
</script>

<template>
  <!-- Vue Toastification renders its own container -->
  <div class="toast-container-wrapper">
    <!-- This component doesn't need to render anything as vue-toastification manages the DOM -->
  </div>
</template>

<style>
/* Global styles for toast containers */
.Vue-Toastification__container {
  z-index: 9999 !important;
}

/* Dark theme styles for toasts */
.Vue-Toastification__toast {
  background-color: #1a1a1a !important;
  border: 1px solid rgba(255, 255, 255, 0.1) !important;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
}

.Vue-Toastification__toast--success {
  border-left: 4px solid #10b981 !important;
}

.Vue-Toastification__toast--error {
  border-left: 4px solid #ef4444 !important;
}

.Vue-Toastification__toast--warning {
  border-left: 4px solid #f59e0b !important;
}

.Vue-Toastification__toast--info {
  border-left: 4px solid #3b82f6 !important;
}

.Vue-Toastification__toast-body {
  font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif !important;
  color: rgba(255, 255, 255, 0.9) !important;
}

/* Add nice animations */
.Vue-Toastification__bounce-enter-active {
  animation: toast-in-right 0.3s ease-out;
}

.Vue-Toastification__bounce-leave-active {
  animation: toast-out-right 0.3s ease-out forwards;
}

@keyframes toast-in-right {
  from {
    transform: translateX(100%);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

@keyframes toast-out-right {
  from {
    transform: translateX(0);
    opacity: 1;
  }
  to {
    transform: translateX(100%);
    opacity: 0;
  }
}

/* Override toast theme colors */
.Vue-Toastification__toast-icon {
  color: inherit !important;
}

/* Improve close button */
.Vue-Toastification__close-button {
  color: rgba(255, 255, 255, 0.5) !important;
}

.Vue-Toastification__close-button:hover {
  color: rgba(255, 255, 255, 0.8) !important;
}

/* Add responsive styles */
@media (max-width: 640px) {
  .Vue-Toastification__toast {
    margin: 0.5rem !important;
    width: calc(100% - 1rem) !important;
  }
}
</style> 