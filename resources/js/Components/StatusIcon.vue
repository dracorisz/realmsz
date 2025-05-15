<script setup>
import { computed } from 'vue';
import {
  ClockIcon,
  PlayIcon, 
  CheckIcon,
  ExclamationTriangleIcon,
  FireIcon,
  ArrowTrendingUpIcon,
  SignalIcon,
  FlagIcon,
  CalendarIcon,
  ArchiveBoxIcon,
  PencilSquareIcon,
  DocumentDuplicateIcon,
  TrashIcon,
  ArrowPathIcon,
  TagIcon,
  UserGroupIcon,
  PaperClipIcon,
  ListBulletIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
  type: {
    type: String,
    default: 'status',
    validator: value => ['status', 'priority', 'action', 'item'].includes(value)
  },
  name: {
    type: String,
    required: true
  },
  size: {
    type: String,
    default: 'md', // xs, sm, md, lg
    validator: value => ['xs', 'sm', 'md', 'lg'].includes(value)
  },
  color: {
    type: String,
    default: ''
  }
});

// Map status names to icons
const statusIcons = {
  todo: ClockIcon,
  pending: ClockIcon,
  doing: PlayIcon,
  in_progress: PlayIcon,
  done: CheckIcon,
  completed: CheckIcon,
  blocked: ExclamationTriangleIcon
};

// Map priority names to icons
const priorityIcons = {
  urgent: FireIcon,
  high: ArrowTrendingUpIcon,
  medium: SignalIcon,
  normal: SignalIcon,
  low: FlagIcon
};

// Map action names to icons
const actionIcons = {
  edit: PencilSquareIcon,
  archive: ArchiveBoxIcon,
  delete: TrashIcon,
  duplicate: DocumentDuplicateIcon,
  calendar: CalendarIcon,
  refresh: ArrowPathIcon,
  status: TagIcon,
  priority: SignalIcon,
  users: UserGroupIcon,
  attachments: PaperClipIcon,
  checklist: ListBulletIcon
};

// Map to all possible icons
const allIcons = {
  ...statusIcons,
  ...priorityIcons,
  ...actionIcons
};

// Determine which icon to display
const iconComponent = computed(() => {
  if (props.type === 'status') {
    return statusIcons[props.name.toLowerCase()] || ClockIcon;
  } else if (props.type === 'priority') {
    return priorityIcons[props.name.toLowerCase()] || SignalIcon;
  } else if (props.type === 'action') {
    return actionIcons[props.name.toLowerCase()] || PencilSquareIcon;
  } else {
    return allIcons[props.name.toLowerCase()] || TagIcon;
  }
});

// Size classes based on prop
const sizeClasses = computed(() => {
  switch(props.size) {
    case 'xs': return 'h-3 w-3';
    case 'sm': return 'h-4 w-4';
    case 'lg': return 'h-6 w-6';
    default: return 'h-5 w-5'; // md
  }
});

// Color classes based on type and name
const colorClasses = computed(() => {
  if (props.color) {
    return `text-${props.color}-500`;
  }
  
  if (props.type === 'status') {
    switch(props.name.toLowerCase()) {
      case 'todo': return 'text-gray-500 dark:text-gray-400';
      case 'pending': return 'text-amber-500 dark:text-amber-400';
      case 'doing': return 'text-blue-500 dark:text-blue-400';
      case 'in_progress': return 'text-blue-500 dark:text-blue-400';
      case 'done': return 'text-green-500 dark:text-green-400';
      case 'completed': return 'text-green-500 dark:text-green-400';
      case 'blocked': return 'text-red-500 dark:text-red-400';
      default: return 'text-gray-500 dark:text-gray-400';
    }
  } else if (props.type === 'priority') {
    switch(props.name.toLowerCase()) {
      case 'urgent': return 'text-red-500 dark:text-red-400';
      case 'high': return 'text-orange-500 dark:text-orange-400';
      case 'medium': return 'text-amber-500 dark:text-amber-400';
      case 'normal': return 'text-amber-500 dark:text-amber-400';
      case 'low': return 'text-blue-500 dark:text-blue-400';
      default: return 'text-gray-500 dark:text-gray-400';
    }
  } else {
    return 'text-gray-500 dark:text-gray-400';
  }
});
</script>

<template>
  <component 
    :is="iconComponent" 
    :class="[sizeClasses, colorClasses]" 
    aria-hidden="true"
  />
</template> 