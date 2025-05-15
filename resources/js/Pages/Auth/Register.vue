<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import FrontLayout from '@/Layouts/FrontLayout.vue';
import { EyeIcon, EyeSlashIcon } from '@heroicons/vue/24/outline';

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  terms: false,
});

const showPassword = ref(false);
const showConfirmPassword = ref(false);

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>

<template>
  <FrontLayout title="Create your account">
    <div class="bg-dragon-dark-700/50 backdrop-blur-sm p-8 rounded-xl border border-dragon-dark-600 w-full max-w-md mx-auto my-20">
      <div class="text-center">
        <h2 class="text-2xl font-bold text-white">Create your account</h2>
        <p class="mt-2 text-gray-400">Join Realmsz and start your journey</p>
      </div>

      <form @submit.prevent="submit" class="mt-8 space-y-6">
        <div>
          <label for="name" class="block text-sm font-medium text-gray-400">Full name</label>
          <div class="mt-1">
            <input
              id="name"
              v-model="form.name"
              type="text"
              required
              autofocus
              class="block w-full rounded-lg border border-dragon-dark-600 bg-dragon-dark-800/50 px-4 py-3 text-white 
                     placeholder-gray-500 focus:border-teal-500 focus:ring-teal-500 focus:ring-1 focus:outline-none 
                     transition-all duration-200"
              placeholder="John Doe"
            />
          </div>
          <p v-if="form.errors.name" class="mt-2 text-sm text-red-500">{{ form.errors.name }}</p>
        </div>

        <div>
          <label for="email" class="block text-sm font-medium text-gray-400">Email address</label>
          <div class="mt-1">
            <input
              id="email"
              v-model="form.email"
              type="email"
              required
              class="block w-full rounded-lg border border-dragon-dark-600 bg-dragon-dark-800/50 px-4 py-3 text-white 
                     placeholder-gray-500 focus:border-teal-500 focus:ring-teal-500 focus:ring-1 focus:outline-none 
                     transition-all duration-200"
              placeholder="you@example.com"
            />
          </div>
          <p v-if="form.errors.email" class="mt-2 text-sm text-red-500">{{ form.errors.email }}</p>
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-400">Password</label>
          <div class="mt-1 relative">
            <input
              :type="showPassword ? 'text' : 'password'"
              id="password"
              v-model="form.password"
              required
              class="block w-full rounded-lg border border-dragon-dark-600 bg-dragon-dark-800/50 px-4 py-3 text-white 
                     placeholder-gray-500 focus:border-teal-500 focus:ring-teal-500 focus:ring-1 focus:outline-none 
                     transition-all duration-200 pr-12"
              placeholder="••••••••"
            />
            <button
              type="button"
              @click="showPassword = !showPassword"
              class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-white transition-colors"
            >
              <component :is="showPassword ? EyeSlashIcon : EyeIcon" class="h-5 w-5" />
            </button>
          </div>
          <p v-if="form.errors.password" class="mt-2 text-sm text-red-500">{{ form.errors.password }}</p>
        </div>

        <div>
          <label for="password_confirmation" class="block text-sm font-medium text-gray-400">Confirm password</label>
          <div class="mt-1 relative">
            <input
              :type="showConfirmPassword ? 'text' : 'password'"
              id="password_confirmation"
              v-model="form.password_confirmation"
              required
              class="block w-full rounded-lg border border-dragon-dark-600 bg-dragon-dark-800/50 px-4 py-3 text-white 
                     placeholder-gray-500 focus:border-teal-500 focus:ring-teal-500 focus:ring-1 focus:outline-none 
                     transition-all duration-200 pr-12"
              placeholder="••••••••"
            />
            <button
              type="button"
              @click="showConfirmPassword = !showConfirmPassword"
              class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-white transition-colors"
            >
              <component :is="showConfirmPassword ? EyeSlashIcon : EyeIcon" class="h-5 w-5" />
            </button>
          </div>
        </div>

        <div class="flex items-center">
          <input
            id="terms"
            v-model="form.terms"
            type="checkbox"
            required
            class="h-4 w-4 rounded border-dragon-dark-600 bg-dragon-dark-800/50 text-teal-500 
                   focus:ring-teal-500 focus:ring-1 focus:outline-none transition-all duration-200"
          />
          <label for="terms" class="ml-2 block text-sm text-gray-400">
            I agree to the
            <Link :href="route('terms-of-service')" class="text-teal-400 hover:text-teal-300 transition-colors">Terms of Service</Link>
            and
            <Link :href="route('privacy-policy')" class="text-teal-400 hover:text-teal-300 transition-colors">Privacy Policy</Link>
          </label>
        </div>
        <p v-if="form.errors.terms" class="text-sm text-red-500">{{ form.errors.terms }}</p>

        <div>
          <button
            type="submit"
            :disabled="form.processing"
            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium 
                   text-white bg-teal-500 hover:bg-teal-600 focus:outline-none focus:ring-2 focus:ring-offset-2 
                   focus:ring-teal-500 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="form.processing" class="flex items-center">
              <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Creating account...
            </span>
            <span v-else>Create account</span>
          </button>
        </div>
      </form>

      <div class="mt-6 text-center">
        <p class="text-sm text-gray-400">
          Already have an account?
          <Link :href="route('login')" class="text-teal-400 hover:text-teal-300 transition-colors">
            Sign in
          </Link>
        </p>
      </div>
    </div>
  </FrontLayout>
</template>

