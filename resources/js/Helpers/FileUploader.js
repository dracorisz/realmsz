import axios from 'axios';
import ToastManager from '@/Services/ToastManager';

/**
 * Upload a file to the server with progress tracking
 * @param {File} file - The file to upload
 * @param {Object} options - Upload options
 * @param {string} options.url - The upload URL
 * @param {Object} options.data - Additional data to send with the file
 * @param {Function} options.onProgress - Progress callback (percentage)
 * @param {Function} options.onSuccess - Success callback (response)
 * @param {Function} options.onError - Error callback (error)
 * @param {boolean} options.useS3 - Whether to use S3 direct upload
 * @returns {Promise<Object>} - Upload response
 */
export const uploadFile = async (file, options = {}) => {
  const {
    url,
    data = {},
    onProgress = () => {},
    onSuccess = () => {},
    onError = () => {},
    useS3 = false,
  } = options;

  try {
    if (!file) {
      throw new Error('No file provided');
    }

    if (!url) {
      throw new Error('No upload URL provided');
    }

    if (useS3) {
      // S3 direct upload flow
      // 1. Get presigned URL from the server
      const presignResponse = await axios.post(route('items.attachments.presign'), {
        filename: file.name,
        contentType: file.type,
      });

      if (!presignResponse.data.success) {
        throw new Error(presignResponse.data.message || 'Failed to get presigned URL');
      }

      const { url: presignedUrl, key } = presignResponse.data;

      // 2. Upload to S3
      const uploadResponse = await axios.put(presignedUrl, file, {
        headers: {
          'Content-Type': file.type,
        },
        onUploadProgress: (progressEvent) => {
          const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
          onProgress(percentCompleted);
        },
      });

      // 3. Save attachment record
      const response = await axios.post(url, {
        ...data,
        key,
        filename: file.name,
        size: file.size,
        type: file.type,
      });

      onSuccess(response.data);
      return response.data;
    } else {
      // Regular file upload to server
      const formData = new FormData();
      
      // Append file
      formData.append('file', file);

      // Append additional data
      Object.entries(data).forEach(([key, value]) => {
        if (value !== undefined && value !== null) {
          if (typeof value === 'object') {
            formData.append(key, JSON.stringify(value));
          } else {
            formData.append(key, value);
          }
        }
      });

      const response = await axios.post(url, formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
        onUploadProgress: (progressEvent) => {
          const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
          onProgress(percentCompleted);
        },
      });

      onSuccess(response.data);
      return response.data;
    }
  } catch (error) {
    const errorMessage = error.response?.data?.message || error.message || 'Failed to upload file';
    ToastManager.error(errorMessage);
    onError(error);
    throw error;
  }
};

/**
 * Get a human-readable file size string
 * @param {number} bytes - The file size in bytes
 * @returns {string} - Human-readable file size
 */
export const formatFileSize = (bytes) => {
  if (!bytes || isNaN(bytes)) return 'Unknown size';
  
  const units = ['B', 'KB', 'MB', 'GB'];
  let size = bytes;
  let unitIndex = 0;
  
  while (size >= 1024 && unitIndex < units.length - 1) {
    size /= 1024;
    unitIndex++;
  }
  
  return `${size.toFixed(1)} ${units[unitIndex]}`;
};

/**
 * Check if a file type is an image
 * @param {string} type - MIME type
 * @returns {boolean} - Whether the file is an image
 */
export const isImageType = (type) => {
  return type.startsWith('image/');
};

/**
 * Get a file type icon based on MIME type
 * @param {string} type - MIME type
 * @returns {string} - Icon name 
 */
export const getFileTypeIcon = (type) => {
  if (isImageType(type)) return 'image';
  
  if (type.includes('pdf')) return 'pdf';
  if (type.includes('word') || type.includes('msword') || type.includes('document')) return 'doc';
  if (type.includes('excel') || type.includes('spreadsheet') || type.includes('csv')) return 'sheet';
  if (type.includes('powerpoint') || type.includes('presentation')) return 'presentation';
  if (type.includes('zip') || type.includes('compressed') || type.includes('archive')) return 'archive';
  if (type.includes('audio')) return 'audio';
  if (type.includes('video')) return 'video';
  if (type.includes('text')) return 'text';
  
  return 'file';
};

export default {
  uploadFile,
  formatFileSize,
  isImageType,
  getFileTypeIcon,
}; 