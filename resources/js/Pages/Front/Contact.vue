<script setup>
import { Link, Head } from '@inertiajs/vue3';
import FrontLayout from '@/Layouts/FrontLayout.vue';
import { ref, onMounted, computed } from 'vue';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import axios from 'axios';

const form = ref({
  name: '',
  email: '',
  subject: '',
  message: '',
  type: 'general',
  priority: 'normal'
});

const errors = ref({});
const success = ref(false);
const loading = ref(false);
const showEmailForm = ref(false);

const contactTypes = [
  { value: 'general', label: 'General Inquiry', icon: '📧', email: 'scopia@realmsz.com' },
  { value: 'support', label: 'Technical Support', icon: '🛠️', email: 'scopia@realmsz.com' },
  { value: 'partnership', label: 'Partnership', icon: '🤝', email: 'scopia@realmsz.com' },
  { value: 'investor', label: 'Investor Relations', icon: '💰', email: 'suzy@realmsz.com' }
];

const priorities = [
  { value: 'low', label: 'Low Priority', icon: '🐢' },
  { value: 'normal', label: 'Normal Priority', icon: '⚡' },
  { value: 'high', label: 'High Priority', icon: '🚨' }
];

const socialLinks = [
  { name: 'Bluesky', url: 'https://bsky.app/profile/dracoscopia.com', icon: '🦋' },
  { name: 'Discord', url: 'https://discord.gg/vPyfYzYw', icon: '🎮' },
  { name: 'Instagram', url: 'https://www.instagram.com/dracorisz', icon: '🐉' },
  { name: 'GitHub', url: 'https://github.com/dracoscopia', icon: '💻' }
];

const selectedContactType = computed(() => {
  return contactTypes.find(type => type.value === form.value.type);
});

const validateForm = () => {
  errors.value = {};
  if (!form.value.name) errors.value.name = 'Name is required';
  if (!form.value.email) {
    errors.value.email = 'Email is required';
  } else if (!/^\S+@\S+\.\S+$/.test(form.value.email)) {
    errors.value.email = 'Please enter a valid email';
  }
  if (!form.value.subject) errors.value.subject = 'Subject is required';
  if (!form.value.message) errors.value.message = 'Message is required';
  return Object.keys(errors.value).length === 0;
};

const submitForm = async () => {
  if (!validateForm()) return;
  
  loading.value = true;
  try {
    const response = await axios.post('/api/contact', {
      ...form.value,
      contactEmail: selectedContactType.value.email
    });
    
    if (response.status === 200) {
      success.value = true;
      form.value = { 
        name: '', 
        email: '', 
        subject: '', 
        message: '', 
        type: 'general',
        priority: 'normal'
      };
    }
  } catch (error) {
    console.error('Error submitting form:', error);
    errors.value.submit = 'Failed to send message. Please try again later.';
  } finally {
    loading.value = false;
  }
};

const sendEmail = () => {
  const type = selectedContactType.value;
  const subject = encodeURIComponent(form.value.subject);
  const body = encodeURIComponent(
    `Name: ${form.value.name}\nEmail: ${form.value.email}\n\n${form.value.message}`
  );
  window.location.href = `mailto:${type.email}?subject=${subject}&body=${body}`;
};

const toggleEmailForm = () => {
  showEmailForm.value = !showEmailForm.value;
};

gsap.registerPlugin(ScrollTrigger);

onMounted(() => {
  // Animate form elements
  gsap.from('.contact-form', {
    opacity: 0,
    y: 50,
    duration: 1,
    scrollTrigger: {
      trigger: '.contact-form',
      start: 'top bottom-=100',
      toggleActions: 'play none none reverse'
    }
  });

  // Animate contact cards
  gsap.from('.contact-card', {
    opacity: 0,
    x: -50,
    duration: 0.5,
    stagger: 0.2,
    scrollTrigger: {
      trigger: '.contact-cards',
      start: 'top bottom-=100',
      toggleActions: 'play none none reverse'
    }
  });
});
</script>

<template>
  <Head title="Contact Us | Realmsz" />
  <FrontLayout>
    <div class="min-h-screen bg-gradient-to-b from-dragon-dark-900 to-dragon-dark-800">
      <!-- Hero Section -->
      <section class="relative py-20 px-4 sm:px-6 lg:px-8">
        <div class="absolute inset-0 overflow-hidden">
          <div class="absolute inset-0 z-0">
            <img src="https://dracoscopia.com/images/backgrounds/contact.jpg" alt="Contact Background" class="w-full h-full object-cover opacity-20" />
          </div>
          <div class="absolute inset-0 bg-gradient-to-b from-dragon-dark-900/80 to-dragon-dark-800/50"></div>
        </div>
        <div class="relative max-w-7xl mx-auto text-center">
          <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold bg-gradient-to-r from-teal-400 via-blue-500 to-purple-600 bg-clip-text text-transparent">
            Contact Us
          </h1>
          <p class="mt-6 text-xl sm:text-2xl text-blue-400 max-w-3xl mx-auto">
            Get in touch with our team
          </p>
        </div>
      </section>

      <!-- Contact Form Section -->
      <section class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div class="contact-form">
              <div class="bg-dragon-dark-700/50 p-8 rounded-xl border border-dragon-dark-600">
                <h2 class="text-2xl font-bold text-white mb-6">Send us a Message</h2>
                <form @submit.prevent="submitForm" class="space-y-6">
                  <div>
                    <label class="block text-gray-300 mb-2">Name</label>
                    <input
                      v-model="form.name"
                      type="text"
                      class="w-full px-4 py-3 bg-dragon-dark-800 border border-dragon-dark-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                      :class="{ 'border-red-500': errors.name }"
                      placeholder="Your name"
                    />
                    <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</p>
                  </div>

                  <div>
                    <label class="block text-gray-300 mb-2">Email</label>
                    <input
                      v-model="form.email"
                      type="email"
                      class="w-full px-4 py-3 bg-dragon-dark-800 border border-dragon-dark-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                      :class="{ 'border-red-500': errors.email }"
                      placeholder="your@email.com"
                    />
                    <p v-if="errors.email" class="text-red-500 text-sm mt-1">{{ errors.email }}</p>
                  </div>

                  <div class="grid grid-cols-2 gap-4">
                    <div>
                      <label class="block text-gray-300 mb-2">Contact Type</label>
                      <select
                        v-model="form.type"
                        class="w-full px-4 py-3 bg-dragon-dark-800 border border-dragon-dark-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                      >
                        <option v-for="type in contactTypes" :key="type.value" :value="type.value">
                          {{ type.icon }} {{ type.label }}
                        </option>
                      </select>
                    </div>
                    <div>
                      <label class="block text-gray-300 mb-2">Priority</label>
                      <select
                        v-model="form.priority"
                        class="w-full px-4 py-3 bg-dragon-dark-800 border border-dragon-dark-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                      >
                        <option v-for="priority in priorities" :key="priority.value" :value="priority.value">
                          {{ priority.icon }} {{ priority.label }}
                        </option>
                      </select>
                    </div>
                  </div>

                  <div>
                    <label class="block text-gray-300 mb-2">Subject</label>
                    <input
                      v-model="form.subject"
                      type="text"
                      class="w-full px-4 py-3 bg-dragon-dark-800 border border-dragon-dark-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                      :class="{ 'border-red-500': errors.subject }"
                      placeholder="What's this about?"
                    />
                    <p v-if="errors.subject" class="text-red-500 text-sm mt-1">{{ errors.subject }}</p>
                  </div>

                  <div>
                    <label class="block text-gray-300 mb-2">Message</label>
                    <textarea
                      v-model="form.message"
                      rows="4"
                      class="w-full px-4 py-3 bg-dragon-dark-800 border border-dragon-dark-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                      :class="{ 'border-red-500': errors.message }"
                      placeholder="Your message..."
                    ></textarea>
                    <p v-if="errors.message" class="text-red-500 text-sm mt-1">{{ errors.message }}</p>
                  </div>

                  <div class="flex gap-4">
                    <button
                      type="submit"
                      class="flex-1 btn-dragon"
                      :disabled="loading"
                    >
                      <span v-if="loading">Sending...</span>
                      <span v-else>Send Message</span>
                    </button>
                    <button
                      type="button"
                      class="flex-1 btn-dragon-outline"
                      @click="sendEmail"
                    >
                      Send via Email
                    </button>
                  </div>

                  <div v-if="success" class="p-4 bg-green-500/20 text-green-400 rounded-lg">
                    Thank you for your message! We'll get back to you soon.
                  </div>
                  <div v-if="errors.submit" class="p-4 bg-red-500/20 text-red-400 rounded-lg">
                    {{ errors.submit }}
                  </div>
                </form>
              </div>
            </div>

            <!-- Contact Information -->
            <div class="contact-cards space-y-6">
              <div class="contact-card bg-dragon-dark-700/50 p-6 rounded-xl border border-dragon-dark-600">
                <h3 class="text-xl font-semibold text-white mb-4">Contact Information</h3>
                <div class="space-y-4">
                  <!-- <div class="flex items-center">
                    <span class="text-2xl mr-4">📍</span>
                    <div>
                      <p class="text-gray-300">123 Blockchain Street</p>
                      <p class="text-gray-400">Crypto City, CC 12345</p>
                    </div>
                  </div>
                  <div class="flex items-center">
                    <span class="text-2xl mr-4">📞</span>
                    <div>
                      <p class="text-gray-300">+1 (555) 123-4567</p>
                      <p class="text-gray-400">Mon-Fri, 9AM-5PM EST</p>
                    </div>
                  </div> -->
                  <div class="flex items-center">
                    <span class="text-2xl mr-4">✉️</span>
                    <div>
                      <p class="text-gray-300">{{ selectedContactType.email }}</p>
                      <p class="text-gray-400">{{ selectedContactType.label }}</p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="contact-card bg-dragon-dark-700/50 p-6 rounded-xl border border-dragon-dark-600">
                <h3 class="text-xl font-semibold text-white mb-4">Follow Us</h3>
                <div class="grid grid-cols-2 gap-4">
                  <a
                    v-for="link in socialLinks"
                    :key="link.name"
                    :href="link.url"
                    target="_blank"
                    class="flex items-center p-3 rounded-lg bg-dragon-dark-800 hover:bg-dragon-dark-600 transition-colors duration-300"
                  >
                    <span class="text-2xl mr-3">{{ link.icon }}</span>
                    <span class="text-gray-300">{{ link.name }}</span>
                  </a>
                </div>
              </div>

              <div class="contact-card bg-dragon-dark-700/50 p-6 rounded-xl border border-dragon-dark-600">
                <h3 class="text-xl font-semibold text-white mb-4">Quick Links</h3>
                <div class="space-y-3">
                  <Link
                    :href="route('faq')"
                    class="flex items-center text-gray-300 hover:text-teal-400 transition-colors duration-300"
                  >
                    <span class="mr-2">❓</span>
                    FAQ
                  </Link>
                  <a
                    href="mailto:support@realmsz.com"
                    class="flex items-center text-gray-300 hover:text-teal-400 transition-colors duration-300"
                  >
                    <span class="mr-2">🛠️</span>
                    Support
                  </a>
                  <a
                    href="mailto:contact@realmsz.com"
                    class="flex items-center text-gray-300 hover:text-teal-400 transition-colors duration-300"
                  >
                    <span class="mr-2">📚</span>
                    Contact
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </FrontLayout>
</template>

<style scoped>
.btn-dragon {
  @apply px-6 py-3 bg-gradient-to-r from-teal-500 to-blue-600 text-white font-semibold 
         rounded-lg shadow-xl hover:shadow-teal-500/25 transition-all duration-300;
}

.btn-dragon-outline {
  @apply px-6 py-3 border-2 border-teal-500 text-teal-500 font-semibold 
         rounded-lg hover:bg-teal-500/10 transition-all duration-300;
}

.contact-card {
  @apply hover:transform hover:-translate-y-1 hover:shadow-lg hover:shadow-teal-500/10 transition-all duration-300;
}

input::placeholder,
textarea::placeholder {
  @apply text-gray-500;
}
</style> 