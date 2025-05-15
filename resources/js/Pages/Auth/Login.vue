<script setup>
import { ref } from "vue";
import { Link, useForm } from "@inertiajs/vue3";
import FrontLayout from "@/Layouts/FrontLayout.vue";
import { EyeIcon, EyeSlashIcon } from "@heroicons/vue/24/outline";

const form = useForm({
  email: "",
  password: "",
  remember: false,
});

const showPassword = ref(false);

const submit = () => {
  form.post(route("login"), {
    onFinish: () => form.reset("password"),
  });
};
</script>

<template>
  <FrontLayout title="Sign in to your account">
    <div class="mx-auto my-20 w-full max-w-md rounded-xl border border-dragon-dark-600 bg-dragon-dark-700/50 p-8 backdrop-blur-sm">
      <div class="text-center">
        <h2 class="text-2xl font-bold text-white">Welcome back</h2>
        <p class="mt-2 text-gray-400">Sign in to your account</p>
      </div>

      <form @submit.prevent="submit" class="mt-8 space-y-6">
        <div>
          <label for="email" class="block text-sm font-medium text-gray-400">Email address</label>
          <div class="mt-1">
            <input id="email" v-model="form.email" type="email" required class="block w-full rounded-lg border border-dragon-dark-600 bg-dragon-dark-800/50 px-4 py-3 text-white placeholder-gray-500 transition-all duration-200 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500" placeholder="you@example.com" />
          </div>
          <p v-if="form.errors.email" class="mt-2 text-sm text-red-500">{{ form.errors.email }}</p>
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-400">Password</label>
          <div class="relative mt-1">
            <input :type="showPassword ? 'text' : 'password'" id="password" v-model="form.password" required class="block w-full rounded-lg border border-dragon-dark-600 bg-dragon-dark-800/50 px-4 py-3 pr-12 text-white placeholder-gray-500 transition-all duration-200 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500" placeholder="••••••••" />
            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 transition-colors hover:text-white">
              <component :is="showPassword ? EyeSlashIcon : EyeIcon" class="h-5 w-5" />
            </button>
          </div>
          <p v-if="form.errors.password" class="mt-2 text-sm text-red-500">{{ form.errors.password }}</p>
        </div>

        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <input id="remember" v-model="form.remember" type="checkbox" class="h-4 w-4 rounded border-dragon-dark-600 bg-dragon-dark-800/50 text-teal-500 transition-all duration-200 focus:outline-none focus:ring-1 focus:ring-teal-500" />
            <label for="remember" class="ml-2 block text-sm text-gray-400">Remember me</label>
          </div>

          <div class="text-sm">
            <Link :href="route('password.request')" class="text-teal-400 transition-colors hover:text-teal-300"> Forgot your password? </Link>
          </div>
        </div>

        <div>
          <button type="submit" :disabled="form.processing" class="flex w-full justify-center rounded-lg border border-transparent bg-teal-500 px-4 py-3 text-sm font-medium text-white shadow-sm transition-all duration-300 hover:bg-teal-600 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
            <span v-if="form.processing" class="flex items-center">
              <svg class="-ml-1 mr-3 h-5 w-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Signing in...
            </span>
            <span v-else>Sign in</span>
          </button>
        </div>
      </form>

      <div class="mt-6 text-center">
        <p class="text-sm text-gray-400">
          Don't have an account?
          <Link :href="route('register')" class="text-teal-400 transition-colors hover:text-teal-300"> Sign up </Link>
        </p>
      </div>
    </div>
  </FrontLayout>
</template>

