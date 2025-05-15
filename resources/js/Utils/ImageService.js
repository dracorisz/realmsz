import axios from "axios";
import { useToast } from "vue-toastification";

class ImageService {
  constructor() {
    this.toast = useToast();
    this.maxRetries = 3;
    this.retryDelay = 1000; // 1 second
  }

  async sleep(ms) {
    return new Promise((resolve) => setTimeout(resolve, ms));
  }

  async generateImageWithRetry(prompt, options, attempt = 1) {
    try {
      console.log(`Attempt ${attempt} of ${this.maxRetries}`);
      const response = await axios.post(
        route("images.generate"),
        {
          prompt,
          width: options.width || 1024,
          height: options.height || 1024,
          style: options.style || "realistic",
          negative_prompt: options.negative_prompt || "blurry, low quality, distorted, ugly, deformed",
          model: options.model || "flux",
          guidance_scale: options.guidance_scale || 7.5,
          steps: options.steps || 30,
          seed: options.seed || null,
        },
        {
          withCredentials: true, // Enable credentials
          timeout: 120000, // 120 seconds timeout
          headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
          },
        },
      );

      return response.data;
    } catch (error) {
      console.error(`Attempt ${attempt} failed:`, error);

      // If we haven't exceeded max retries and it's a network error or timeout
      if (attempt < this.maxRetries && 
        (error.code === "ECONNABORTED" || 
         error.code === "ECONNRESET" ||
         error.message.includes("timeout") ||
         error.message.includes("Network Error"))) {
        
        // Exponential backoff
        const delay = this.retryDelay * Math.pow(2, attempt - 1);
        console.log(`Retrying after ${delay}ms...`);
        await this.sleep(delay);
        return this.generateImageWithRetry(prompt, options, attempt + 1);
      }

      throw error;
    }
  }

  async generateImage(prompt, options = {}) {
    try {
      console.log('Sending request with:', { prompt, options });
      const response = await axios.post(route('images.generate'), {
        prompt,
        width: options.width || 1024,
        height: options.height || 1024,
        style: options.style || 'realistic',
        negative_prompt: options.negative_prompt || 'blurry, low quality, distorted, ugly, deformed',
        model: options.model || 'flux',
        guidance_scale: options.guidance_scale || 7.5,
        steps: options.steps || 30,
        seed: options.seed || null
      }, {
        withCredentials: false,
        timeout: 60000 // 60 seconds timeout
      });

      console.log('Raw API response:', response);

      if (!response.data.success) {
        throw new Error(response.data.message || 'Failed to generate image');
      }

      // Check both possible image URL locations
      const imageUrl = response.data.image_url || response.data.image;
      if (!imageUrl) {
        console.error('No image URL in response:', response.data);
        throw new Error('No image URL received from server');
      }

      return {
        success: true,
        image: imageUrl,
        message: 'Image generated successfully'
      };
    } catch (error) {
      console.error('Error generating image:', {
        message: error.message,
        response: error.response?.data,
        status: error.response?.status,
        config: error.config
      });
      
      if (error.response?.data?.errors) {
        const errorMessages = Object.values(error.response.data.errors).flat();
        throw new Error(errorMessages.join(', '));
      } else if (error.response?.data?.message) {
        throw new Error(error.response.data.message);
      } else if (error.request) {
        throw new Error('No response received from the server. Please check your internet connection.');
      } else {
        throw new Error('Error setting up the request: ' + error.message);
      }
    }
  }

  async uploadImage(file, title) {
    try {
      // Validate file type
      const validTypes = ["image/jpeg", "image/png", "image/gif", "image/webp"];
      if (!validTypes.includes(file.type)) {
        throw new Error("Please upload a valid image file (JPEG, PNG, GIF, or WebP)");
      }

      // Validate file size (10MB)
      if (file.size > 10 * 1024 * 1024) {
        throw new Error("File size must be less than 10MB");
      }

      const formData = new FormData();
      formData.append("image", file);
      formData.append("title", title);

      const response = await axios.post(route("images.upload"), formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
        withCredentials: false,
      });

      if (response.data.success) {
        // The backend will handle saving to S3 and return the S3 URL
        return response.data.image_url;
      } else {
        throw new Error(response.data.message || "Failed to upload image");
      }
    } catch (error) {
      console.error("Error uploading image:", error);
      this.toast.error(error.response?.data?.message || "Error uploading image");
      throw error;
    }
  }

  async getOrganizationImages() {
    try {
      const response = await axios.get(route("images.organization"), {
        withCredentials: false,
      });

      if (response.data.success) {
        // Images are already stored in S3, return the S3 URLs
        return response.data.images;
      } else {
        throw new Error(response.data.message || "Failed to fetch images");
      }
    } catch (error) {
      console.error("Error fetching images:", error);
      this.toast.error(error.response?.data?.message || "Error fetching images");
      throw error;
    }
  }

  async deleteImage(imageId) {
    try {
      const response = await axios.delete(route("images.delete", { id: imageId }), {
        withCredentials: false,
      });

      if (response.data.success) {
        // The backend will handle deleting from S3
        return true;
      } else {
        throw new Error(response.data.message || "Failed to delete image");
      }
    } catch (error) {
      console.error("Error deleting image:", error);
      this.toast.error(error.response?.data?.message || "Error deleting image");
      throw error;
    }
  }
}

export default new ImageService();
