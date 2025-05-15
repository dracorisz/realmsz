<script setup>
import { computed } from 'vue';
import { 
  DocumentIcon, 
  PhotoIcon, 
  DocumentTextIcon,
  DocumentChartBarIcon,
  MusicalNoteIcon,
  FilmIcon,
  ArchiveBoxIcon,
  DocumentArrowDownIcon,
  DocumentPlusIcon
} from '@heroicons/vue/24/outline';
import { formatFileSize, isImageType, getFileTypeIcon } from '@/Helpers/FileUploader';

const props = defineProps({
  file: {
    type: Object,
    required: true,
  },
  showDownload: {
    type: Boolean,
    default: true,
  },
  showSize: {
    type: Boolean,
    default: true,
  },
  variant: {
    type: String,
    default: 'card', // card, compact, list
  },
  clickable: {
    type: Boolean,
    default: true,
  },
});

const emit = defineEmits(['click', 'download']);

// Get file properties from the file object
const fileName = computed(() => props.file.title || props.file.name || 'File');
const fileType = computed(() => props.file.file_type || props.file.type || 'application/octet-stream');
const fileSize = computed(() => formatFileSize(props.file.file_size || props.file.size || 0));
const fileUrl = computed(() => props.file.url || (props.file.id ? route('attachments.download', props.file.id) : null));
const isImage = computed(() => isImageType(fileType.value));

// Get the appropriate icon based on file type
const fileIcon = computed(() => {
  const iconType = getFileTypeIcon(fileType.value);
  
  switch (iconType) {
    case 'image':
      return PhotoIcon;
    case 'pdf':
      return DocumentIcon;
    case 'doc':
      return DocumentTextIcon;
    case 'sheet':
      return DocumentChartBarIcon;
    case 'audio':
      return MusicalNoteIcon;
    case 'video':
      return FilmIcon;
    case 'archive':
      return ArchiveBoxIcon;
    case 'text':
      return DocumentTextIcon;
    default:
      return DocumentIcon;
  }
});

// Handlers
const handleClick = () => {
  if (props.clickable) {
    emit('click', props.file);
  }
};

const handleDownload = (e) => {
  e.stopPropagation();
  emit('download', props.file);
  
  if (fileUrl.value) {
    window.open(fileUrl.value, '_blank');
  }
};
</script>

<template>
  <!-- Card variant -->
  <div
    v-if="variant === 'card'"
    class="group relative overflow-hidden rounded-lg border border-white/10 bg-gray-900 shadow-md transition-all hover:bg-gray-800"
    :class="{ 'cursor-pointer': clickable }"
    @click="handleClick"
  >
    <!-- Image preview -->
    <div v-if="isImage && fileUrl" class="aspect-video bg-gray-800">
      <img :src="fileUrl" :alt="fileName" class="h-full w-full object-cover" />
    </div>
    
    <!-- File icon -->
    <div v-else class="flex aspect-video items-center justify-center bg-gray-800 p-4">
      <component :is="fileIcon" class="h-16 w-16 text-gray-400" />
    </div>
    
    <!-- File info -->
    <div class="p-3">
      <h3 class="truncate text-sm font-medium text-white">{{ fileName }}</h3>
      <div class="mt-1 flex items-center justify-between">
        <span v-if="showSize" class="text-xs text-gray-400">{{ fileSize }}</span>
        <button
          v-if="showDownload && fileUrl"
          class="rounded-full p-1 text-gray-400 hover:bg-gray-700 hover:text-white"
          @click="handleDownload"
          title="Download"
        >
          <DocumentArrowDownIcon class="h-4 w-4" />
        </button>
      </div>
    </div>
  </div>

  <!-- Compact variant -->
  <div
    v-else-if="variant === 'compact'"
    class="group flex items-center gap-3 rounded-lg border border-white/10 bg-gray-900 p-2 shadow-sm transition-all hover:bg-gray-800"
    :class="{ 'cursor-pointer': clickable }"
    @click="handleClick"
  >
    <!-- File icon or thumbnail -->
    <div class="flex h-10 w-10 items-center justify-center rounded bg-gray-800">
      <img
        v-if="isImage && fileUrl"
        :src="fileUrl"
        :alt="fileName"
        class="h-full w-full rounded object-cover"
      />
      <component v-else :is="fileIcon" class="h-5 w-5 text-gray-400" />
    </div>
    
    <!-- File info -->
    <div class="flex-1 min-w-0">
      <h3 class="truncate text-sm font-medium text-white">{{ fileName }}</h3>
      <p v-if="showSize" class="text-xs text-gray-400">{{ fileSize }}</p>
    </div>
    
    <!-- Download button -->
    <button
      v-if="showDownload && fileUrl"
      class="rounded-full p-1 text-gray-400 hover:bg-gray-700 hover:text-white"
      @click="handleDownload"
      title="Download"
    >
      <DocumentArrowDownIcon class="h-4 w-4" />
    </button>
  </div>

  <!-- List variant -->
  <div
    v-else
    class="group flex items-center gap-4 rounded-lg border border-white/10 bg-gray-900 p-3 shadow-sm transition-all hover:bg-gray-800"
    :class="{ 'cursor-pointer': clickable }"
    @click="handleClick"
  >
    <!-- File icon or thumbnail -->
    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded bg-gray-800">
      <img
        v-if="isImage && fileUrl"
        :src="fileUrl"
        :alt="fileName"
        class="h-full w-full rounded object-cover"
      />
      <component v-else :is="fileIcon" class="h-6 w-6 text-gray-400" />
    </div>
    
    <!-- File info -->
    <div class="flex flex-1 min-w-0 flex-col">
      <h3 class="truncate text-sm font-medium text-white">{{ fileName }}</h3>
      <p class="text-xs text-gray-400">
        <span>{{ fileType.split('/')[1]?.toUpperCase() || 'FILE' }}</span>
        <span v-if="showSize" class="ml-2">• {{ fileSize }}</span>
      </p>
    </div>
    
    <!-- Download button -->
    <button
      v-if="showDownload && fileUrl"
      class="rounded-full p-1.5 text-gray-400 hover:bg-gray-700 hover:text-white"
      @click="handleDownload"
      title="Download"
    >
      <DocumentArrowDownIcon class="h-5 w-5" />
    </button>
  </div>
</template>

<style scoped>
/* Glass effect for cards */
.shadow-md {
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06), 0 0 0 1px rgba(255, 255, 255, 0.05);
}

/* Hover effects */
.hover\:bg-gray-800:hover {
  transform: translateY(-1px);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05), 0 0 0 1px rgba(255, 255, 255, 0.1);
}

/* Truncate text */
.truncate {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style> 