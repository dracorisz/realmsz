import { inject } from 'vue'

export default function useToast() {
  const toast = inject('toast')

  if (!toast) {
    console.error('Toast provider not found. Make sure ToastManager is mounted in your app.')
    
    // Return dummy functions to prevent errors
    return {
      success: (message) => console.log('Toast success:', message),
      error: (message) => console.error('Toast error:', message),
      warning: (message) => console.warn('Toast warning:', message),
      info: (message) => console.info('Toast info:', message),
      remove: () => {}
    }
  }

  return toast
} 