import { useToast } from 'vue-toastification';

/**
 * ToastManager - A wrapper around vue-toastification to provide a consistent API
 * for showing toast notifications throughout the application
 */
class ToastManager {
  constructor() {
    this.toastInstance = null;
  }

  /**
   * Initialize the toast instance
   * Should be called once during app initialization
   */
  init() {
    this.toastInstance = useToast();
    return this;
  }

  /**
   * Get the toast instance, initializing if needed
   * @returns {Object} The toast instance
   */
  getToast() {
    if (!this.toastInstance) {
      this.init();
    }
    return this.toastInstance;
  }

  // Helper to sanitize timeout value
  sanitizeTimeout(options, defaultTimeout) {
    if (typeof options.timeout !== 'number' && typeof options.timeout !== 'boolean') {
      options.timeout = defaultTimeout;
    }
    return options;
  }

  /**
   * Show a success toast notification
   * @param {string} message - The message to display
   * @param {Object} options - Optional toast configuration
   */
  success(message, options = {}) {
    this.getToast().success(message, this.sanitizeTimeout(options, 3000));
  }

  /**
   * Show an error toast notification
   * @param {string} message - The message to display
   * @param {Object} options - Optional toast configuration
   */
  error(message, options = {}) {
    this.getToast().error(message, this.sanitizeTimeout(options, 5000));
  }

  /**
   * Show an info toast notification
   * @param {string} message - The message to display
   * @param {Object} options - Optional toast configuration
   */
  info(message, options = {}) {
    this.getToast().info(message, this.sanitizeTimeout(options, 3000));
  }

  /**
   * Show a warning toast notification
   * @param {string} message - The message to display
   * @param {Object} options - Optional toast configuration
   */
  warning(message, options = {}) {
    this.getToast().warning(message, this.sanitizeTimeout(options, 4000));
  }

  /**
   * Show a loading toast notification
   * Can be updated or dismissed using the returned ID
   * @param {string} message - The message to display
   * @param {Object} options - Optional toast configuration
   * @returns {number} Toast ID for updating or dismissing
   */
  loading(message = 'Loading...', options = {}) {
    return this.getToast().info(
      `<div class="flex items-center">
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        ${message}
      </div>`,
      false
    );
  }

  /**
   * Update an existing toast notification
   * @param {number} id - The toast ID to update
   * @param {string} message - The new message
   * @param {string} type - The toast type (success, error, etc.)
   * @param {Object} options - Optional toast configuration
   */
  update(id, message, type = 'info', options = {}) {
    const toast = this.getToast();
    const updateOptions = {
      type,
      ...options
    };
    toast.update(id, { content: message, options: updateOptions });
  }

  /**
   * Dismiss a specific toast notification
   * @param {number} id - The toast ID to dismiss
   */
  dismiss(id) {
    this.getToast().dismiss(id);
  }

  /**
   * Dismiss all toast notifications
   */
  dismissAll() {
    this.getToast().clear();
  }

  // Helper method to format error messages from Laravel validation errors
  formatValidationErrors(errors) {
    if (!errors) return 'An error occurred';
    
    if (typeof errors === 'string') return errors;
    
    // Handle Laravel validation errors object
    if (typeof errors === 'object') {
      const firstError = Object.values(errors)[0];
      if (Array.isArray(firstError)) {
        return firstError[0];
      }
      return firstError || 'Validation failed';
    }
    
    return 'An error occurred';
  }
}

export default new ToastManager(); 