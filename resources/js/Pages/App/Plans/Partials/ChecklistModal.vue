<script setup>
import { ref, computed, watch, nextTick } from 'vue'
import ToastManager from '@/Services/ToastManager'
import { XMarkIcon, PlusIcon, TrashIcon, ChevronUpIcon, ChevronDownIcon } from '@heroicons/vue/24/outline'
import { useToast } from 'vue-toastification'
import axios from 'axios'

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  item: {
    type: Object,
    default: null
  },
  checklists: {
    type: Array,
    default: () => []
  }
})

const emit = defineEmits(['close', 'update'])

const toast = useToast()
const isLoading = ref(false)
const checklistItems = ref([])
const newItem = ref('')

// Initialize checklist items
watch(() => props.checklists, (value) => {
  if (value && value.length) {
    checklistItems.value = [...value]
  } else {
    checklistItems.value = []
  }
}, { immediate: true })

// Add a new checklist item
const addItem = () => {
  if (!newItem.value.trim()) {
    toast.error('Please enter a task description')
    return
  }
  
  checklistItems.value.push({
    id: Date.now(), // Temporary ID for new items
    content: newItem.value,
    is_completed: false,
    order: checklistItems.value.length
  })
  
  newItem.value = ''
  nextTick(() => {
    document.getElementById('new-item-input')?.focus()
  })
}

// Remove a checklist item
const removeItem = (index) => {
  checklistItems.value.splice(index, 1)
  // Update order after removal
  checklistItems.value.forEach((item, idx) => {
    item.order = idx
  })
}

// Toggle completion status
const toggleComplete = (index) => {
  const item = checklistItems.value[index]
  item.is_completed = !item.is_completed
}

// Move item up in order
const moveUp = (index) => {
  if (index > 0) {
    const temp = checklistItems.value[index]
    checklistItems.value[index] = checklistItems.value[index - 1]
    checklistItems.value[index - 1] = temp
    
    // Update order properties
    checklistItems.value.forEach((item, idx) => {
      item.order = idx
    })
  }
}

// Move item down in order
const moveDown = (index) => {
  if (index < checklistItems.value.length - 1) {
    const temp = checklistItems.value[index]
    checklistItems.value[index] = checklistItems.value[index + 1]
    checklistItems.value[index + 1] = temp
    
    // Update order properties
    checklistItems.value.forEach((item, idx) => {
      item.order = idx
    })
  }
}

// Save checklist changes
const saveChecklist = async () => {
  if (!props.item?.id) {
    emit('update', checklistItems.value)
    emit('close')
    return
  }
  
  try {
    isLoading.value = true
    const response = await axios.post(route('items.checklists.store', { item: props.item.id }), {
      items: checklistItems.value
    })
    
    if (response.data.success) {
      emit('update', checklistItems.value)
      toast.success('Checklist updated successfully')
      emit('close')
    } else {
      throw new Error(response.data.message || 'Failed to update checklist')
    }
  } catch (error) {
    console.error('Error updating checklist:', error)
    toast.error(error.response?.data?.message || 'Failed to update checklist')
  } finally {
    isLoading.value = false
  }
}

// Handle keydown events
const handleKeyDown = (e) => {
  if (e.key === 'Escape') {
    emit('close')
  } else if (e.key === 'Enter' && e.target.id === 'new-item-input') {
    addItem()
  }
}

// Computed properties
const hasItems = computed(() => checklistItems.value.length > 0)
const completedCount = computed(() => checklistItems.value.filter(item => item.is_completed).length)
const completionPercentage = computed(() => {
  if (checklistItems.value.length === 0) return 0
  return Math.round((completedCount.value / checklistItems.value.length) * 100)
})
</script>

<template>
  <Teleport to="body">
    <Transition name="fade">
      <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto" @keydown="handleKeyDown">
        <div class="flex min-h-screen items-center justify-center p-4">
          <div 
            class="fixed inset-0 bg-black/50 dark:bg-black/70 backdrop-blur-sm"
            @click="emit('close')"
            aria-hidden="true"
          ></div>

          <div 
            class="checklist-modal relative w-full max-w-md bg-white dark:bg-gray-800
              rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden
              transform transition-all duration-300"
            @click.stop
            role="dialog"
            aria-modal="true"
            aria-labelledby="checklist-modal-title"
          >
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
              <h3 id="checklist-modal-title" class="text-lg font-medium text-gray-900 dark:text-white">
                Manage Checklist
              </h3>
              <button 
                type="button" 
                class="text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-opacity-50 rounded-md"
                @click="emit('close')"
                aria-label="Close modal"
              >
                <XMarkIcon class="h-5 w-5" />
              </button>
            </div>

            <!-- Progress bar -->
            <div v-if="hasItems" class="px-6 pt-4">
              <div class="flex items-center justify-between mb-1">
                <span class="text-sm text-gray-700 dark:text-gray-300">{{ completionPercentage }}% complete</span>
                <span class="text-sm text-gray-700 dark:text-gray-300">{{ completedCount }} / {{ checklistItems.length }}</span>
              </div>
              <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                <div 
                  class="bg-indigo-600 dark:bg-indigo-500 h-2.5 rounded-full transition-all duration-300"
                  :style="{ width: `${completionPercentage}%` }"
                ></div>
              </div>
            </div>

            <!-- Checklist items -->
            <div class="px-6 py-4 max-h-[60vh] overflow-y-auto">
              <div v-if="!hasItems" class="text-center py-6">
                <p class="text-gray-500 dark:text-gray-400">No items in checklist</p>
                <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Add your first task below</p>
              </div>
              
              <ul v-else class="space-y-2">
                <li 
                  v-for="(item, index) in checklistItems" 
                  :key="item.id || index"
                  class="flex items-start space-x-2 p-2 rounded-md hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors group"
                >
                  <div class="flex-shrink-0 pt-0.5">
                    <input 
                      type="checkbox" 
                      :checked="item.is_completed" 
                      @change="toggleComplete(index)"
                      class="h-4 w-4 text-indigo-600 dark:text-indigo-500 border-gray-300 dark:border-gray-600 rounded focus:ring-indigo-500 dark:focus:ring-indigo-400"
                    />
                  </div>
                  <div class="flex-grow min-w-0">
                    <p 
                      class="text-gray-900 dark:text-gray-100"
                      :class="{ 'line-through text-gray-500 dark:text-gray-500': item.is_completed }"
                    >
                      {{ item.content }}
                    </p>
                  </div>
                  <div class="flex-shrink-0 flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button 
                      v-if="index > 0"
                      type="button" 
                      class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 p-1 rounded-md"
                      @click="moveUp(index)"
                      aria-label="Move up"
                    >
                      <ChevronUpIcon class="h-4 w-4" />
                    </button>
                    <button 
                      v-if="index < checklistItems.length - 1"
                      type="button" 
                      class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 p-1 rounded-md"
                      @click="moveDown(index)"
                      aria-label="Move down"
                    >
                      <ChevronDownIcon class="h-4 w-4" />
                    </button>
                    <button 
                      type="button" 
                      class="text-gray-400 hover:text-red-600 dark:text-gray-500 dark:hover:text-red-500 p-1 rounded-md"
                      @click="removeItem(index)"
                      aria-label="Remove item"
                    >
                      <TrashIcon class="h-4 w-4" />
                    </button>
                  </div>
                </li>
              </ul>
            </div>

            <!-- Add new item -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
              <div class="flex space-x-2">
                <input 
                  id="new-item-input"
                  v-model="newItem" 
                  type="text" 
                  class="flex-grow rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400"
                  placeholder="Add a new task..." 
                  @keydown.enter="addItem"
                />
                <button 
                  type="button" 
                  class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-indigo-400"
                  @click="addItem"
                >
                  <PlusIcon class="h-4 w-4 mr-1" />
                  Add
                </button>
              </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-750 border-t border-gray-200 dark:border-gray-700 flex justify-end">
              <button 
                type="button" 
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-indigo-400"
                @click="saveChecklist"
                :disabled="isLoading"
              >
                <svg v-if="isLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Save Checklist
              </button>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.checklist-modal {
  @apply transition-all duration-300;
  animation: modal-in 0.3s ease-out forwards;
}

@keyframes modal-in {
  0% {
    opacity: 0;
    transform: scale(0.95) translateY(10px);
  }
  100% {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

/* Scrollbar styling */
.max-h-\[60vh\]::-webkit-scrollbar {
  width: 6px;
}

.max-h-\[60vh\]::-webkit-scrollbar-track {
  @apply bg-gray-100 dark:bg-gray-700 rounded-full;
}

.max-h-\[60vh\]::-webkit-scrollbar-thumb {
  @apply bg-gray-400 dark:bg-gray-600 rounded-full;
}

.max-h-\[60vh\]::-webkit-scrollbar-thumb:hover {
  @apply bg-gray-500 dark:bg-gray-500;
}

/* Ensure checkbox is properly styled in dark mode */
input[type='checkbox'] {
  @apply border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700;
}

input[type='checkbox']:checked {
  @apply border-indigo-500 dark:border-indigo-400 bg-indigo-500 dark:bg-indigo-400;
}

.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}
</style> 