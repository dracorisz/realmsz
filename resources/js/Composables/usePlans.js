import { ref, computed } from 'vue';
import ToastManager from '../Services/ToastManager';
import axios from 'axios';
import { router } from "@inertiajs/vue3";

export function usePlans() {
  // State
  const showSettings = ref(false);
  const gridView = ref(false);
  const loading = ref(false);
  const search = ref('');
  const popupStates = ref({
    status: {},
    priority: {},
    date: {},
    actions: {}
  });
  const selectedItems = ref([]);
  const loadingStates = ref({
    status: false,
    priority: false,
    date: false,
    edit: false,
    archive: false,
    delete: false
  });

  // Computed properties for popups
  const statusPopups = computed({
    get: () => popupStates.value.status,
    set: (val) => { popupStates.value.status = val }
  });

  const priorityPopups = computed({
    get: () => popupStates.value.priority,
    set: (val) => { popupStates.value.priority = val }
  });

  const datePopups = computed({
    get: () => popupStates.value.date,
    set: (val) => { popupStates.value.date = val }
  });

  const moreActions = computed({
    get: () => popupStates.value.actions,
    set: (val) => { popupStates.value.actions = val }
  });

  // Methods to handle popups
  const closeAllPopups = () => {
    popupStates.value = {
      status: {},
      priority: {},
      date: {},
      actions: {}
    };
  };

  const togglePopup = (type, id) => {
    closeAllPopups();
    const key = type === 'status' ? 'status' :
                type === 'priority' ? 'priority' :
                type === 'date' ? 'date' : 'actions';
                
    popupStates.value[key] = { [id]: !popupStates.value[key][id] };
  };

  // Add function to check if a specific action is loading
  const isLoading = (actionType) => {
    return loadingStates.value[actionType] || false;
  };

  // API Methods
  const handleStatusChange = async (itemId, statusId) => {
    closeAllPopups();
    loadingStates.value.status = true;
    loading.value = true;
    try {
      const response = await axios.post(route('items.status'), {
        id: itemId,
        status_id: statusId
      });

      if (!response.data.success) {
        throw new Error(response.data.message || 'Failed to update status');
      }

      ToastManager.success('Status updated successfully');
      return true;
    } catch (error) {
      console.error('Error updating status:', error);
      ToastManager.error(error.response?.data?.message || error.message || 'Failed to update status');
      throw error;
    } finally {
      loadingStates.value.status = false;
      loading.value = false;
    }
  };

  const handlePriorityChange = async (itemId, priorityId) => {
    closeAllPopups();
    loadingStates.value.priority = true;
    loading.value = true;
    try {
      const response = await axios.post(route('items.priority'), {
        id: itemId,
        priority_id: priorityId
      });

      if (!response.data.success) {
        throw new Error(response.data.message || 'Failed to update priority');
      }

      ToastManager.success('Priority updated successfully');
      return true;
    } catch (error) {
      console.error('Error updating priority:', error);
      ToastManager.error(error.response?.data?.message || error.message || 'Failed to update priority');
      throw error;
    } finally {
      loadingStates.value.priority = false;
      loading.value = false;
    }
  };

  const handleDateChange = async (itemId, date, recurring = false, recurringInterval = null) => {
    closeAllPopups();
    loadingStates.value.date = true;
    try {
      const response = await axios.post(route('items.date'), {
        id: itemId,
        date: date,
        recurring: recurring ? 1 : 0,
        recurring_interval: recurringInterval || null
      });

      if (!response.data.success) {
        throw new Error(response.data.message || 'Failed to update date');
      }

      ToastManager.success('Date updated successfully');
      return true;
    } catch (error) {
      console.error('Error updating date:', error);
      ToastManager.error(error.response?.data?.message || error.message || 'Failed to update date');
      throw error; // Propagate error to component
    } finally {
      loadingStates.value.date = false;
    }
  };

  // Add handlers for delete and archive
  const handleDelete = async (itemId) => {
    closeAllPopups();
    loadingStates.value.delete = true;
    try {
      await axios.post(route('items.destroy', itemId));
      ToastManager.success('Item deleted successfully');
      await handleRefresh();
    } catch (error) {
      ToastManager.error(error.response?.data?.message || 'Failed to delete item');
    } finally {
      loadingStates.value.delete = false;
    }
  };

  const handleArchive = async (item) => {
    closeAllPopups();
    loadingStates.value.archive = true;
    try {
      await axios.post(route('items.archive', item));
      ToastManager.success('Item archived successfully');
      await handleRefresh();
    } catch (error) {
      ToastManager.error(error.response?.data?.message || 'Failed to archive item');
    } finally {
      loadingStates.value.archive = false;
    }
  };

  // Add modal-related handlers
  const openEditModal = (item) => {
    // This should be implemented in the parent component where the modal is defined
    // We'll emit an event that the parent component can handle
    router.visit(route('plans.edit', item.id), {
      preserveScroll: true,
      only: ['modal']
    });
  };

  const deleteItem = async (itemId) => {
    if (confirm('Are you sure you want to delete this item?')) {
      await handleDelete(itemId);
    }
  };

  const archiveItem = async (itemId) => {
    await handleArchive(itemId);
  };

  // Selection Methods
  const handleSelect = (item) => {
    const index = selectedItems.value.findIndex(i => i.id === item.id);
    if (index === -1) {
      selectedItems.value.push(item);
    } else {
      selectedItems.value.splice(index, 1);
    }
  };

  const handleSelectAll = (items) => {
    if (selectedItems.value.length === items.length) {
      selectedItems.value = [];
    } else {
      selectedItems.value = [...items];
    }
  };

  // Bulk Actions
  const handleBulkDelete = async () => {
    if (!selectedItems.value.length) return;
    
    loading.value = true;
    try {
      await axios.post(route('items.destroy'), {
        ids: selectedItems.value.map(item => item.id)
      });
      selectedItems.value = [];
      ToastManager.success('Selected items deleted successfully');
      await handleRefresh();
    } catch (error) {
      ToastManager.error(error.response?.data?.message || 'Failed to delete selected items');
    } finally {
      loading.value = false;
    }
  };

  const handleBulkArchive = async () => {
    if (!selectedItems.value.length) return;
    
    loading.value = true;
    try {
      await axios.post(route('items.archive'), {
        ids: selectedItems.value.map(item => item.id)
      });
      selectedItems.value = [];
      ToastManager.success('Selected items archived successfully');
      await handleRefresh();
    } catch (error) {
      ToastManager.error(error.response?.data?.message || 'Failed to archive selected items');
    } finally {
      loading.value = false;
    }
  };

  const toggleGridView = () => {
    gridView.value = !gridView.value;
    localStorage.setItem('plansGridLayout', gridView.value.toString());
  };

  const toggleSettings = () => {
    showSettings.value = !showSettings.value;
  };

  // Utility Methods
  const handleRefresh = async () => {
    loading.value = true;
    try {
      await router.reload({ 
        preserveScroll: true,
        onSuccess: () => {
          // Close any open popups after successful refresh
          closeAllPopups();
        }
      });
    } catch (error) {
      handleError(error, 'Failed to refresh data');
    } finally {
      loading.value = false;
    }
  };

  const formatDate = (date) => {
    if (!date) return 'No due date';
    return new Date(date).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric'
    });
  };

  const formatRecurringDate = (date, type) => {
    if (!date) return 'No due date';
    const d = new Date(date);
    const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    const dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    
    const dayNumber = d.getDate();
    const monthName = monthNames[d.getMonth()];
    const dayName = dayNames[d.getDay()];
    
    const getOrdinal = (n) => {
      if (n % 100 >= 11 && n % 100 <= 13) return "th";
      switch (n % 10) {
        case 1: return "st";
        case 2: return "nd";
        case 3: return "rd";
        default: return "th";
      }
    };

    switch (type) {
      case 'yearly':
        return `Yearly on ${dayNumber}${getOrdinal(dayNumber)} of ${monthName}`;
      case 'monthly':
        return `Monthly on ${dayNumber}${getOrdinal(dayNumber)}`;
      case 'weekly':
        return `Weekly on ${dayName}`;
      case 'daily':
        return 'Daily';
      default:
        return formatDate(date);
    }
  };

  const handleKeyDown = (e, type, item, callback) => {
    if ((e.key === 'Enter' || e.key === ' ') && e.target.dataset) {
      e.preventDefault();
      if (typeof callback === 'function') {
        callback(e, type, item);
      } else if (type) {
        togglePopup(type, item?.id);
      }
    } else if (e.key === 'Escape') {
      e.preventDefault();
      closeAllPopups();
    }
  };

  // Add a helper for error handling
  const handleError = (error, defaultMessage = 'An error occurred') => {
    console.error('Error:', error);
    const message = error.response?.data?.message || error.message || defaultMessage;
    ToastManager.error(message);
    return message; // Return the message for component usage
  };

  // Add a helper for handling popup toggle
  const handleTogglePopup = (type, itemId) => {
    // Close all other popups first
    closeAllPopups();
    
    // Then toggle the requested popup
    if (type === 'close-all') {
      return;
    }
    
    // Use the togglePopup function
    togglePopup(type, itemId);
  };

  return {
    // State
    showSettings,
    gridView,
    loading,
    search,
    statusPopups,
    priorityPopups,
    datePopups,
    moreActions,
    selectedItems,
    loadingStates,

    // Methods
    closeAllPopups,
    togglePopup,
    handleStatusChange,
    handlePriorityChange,
    handleDateChange,
    handleDelete,
    handleArchive,
    openEditModal,
    deleteItem,
    archiveItem,
    handleKeyDown,
    toggleGridView,
    toggleSettings,
    handleRefresh,
    formatDate,
    formatRecurringDate,
    handleError,
    handleTogglePopup,
    isLoading
  };
} 