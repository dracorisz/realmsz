<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

defineProps({
  canResetPassword: Boolean,
  status: String,
});

const form = useForm({
  email: "",
  password: "",
  remember: false,
});

const submit = () => {
  form.transform((data) => ({ ...data, remember: form.remember ? "on" : "" })).post(route("login"), { onFinish: () => form.reset("password") });
};
</script>

<template>
  <Head title="Login" />

  <AuthenticationCard>
    <template #logo>
      <ApplicationLogo />
    </template>

    <div v-if="status" class="mb-5 text-sm">{{ status }}</div>

    <form @submit.prevent="submit" class="flex flex-col gap-5">
      <div class="flex flex-col gap-1">
        <InputLabel for="email" value="Email" />
        <TextInput id="email" v-model="form.email" type="email" required autocomplete="email" />
        <InputError class="mt-2" :message="form.errors.email" />
      </div>

      <div class="flex flex-col gap-1">
        <InputLabel for="password" value="Password" />
        <TextInput id="password" v-model="form.password" type="password" required autocomplete="password" />
        <InputError class="mt-2" :message="form.errors.password" />
      </div>

      <div class="flex flex-col gap-1">
        <label for="remember" class="flex items-center">
          <Checkbox id="remember" v-model="form.remember" />
          <span class="link ml-2">Remember me</span>
        </label>
      </div>

      <div class="flex items-center justify-end">
        <Link v-if="canResetPassword" :href="route('password.request')" class="link">Forgot your password?</Link>
        <PrimaryButton class="ml-3" :disabled="form.processing">Login</PrimaryButton>
      </div>
    </form>
  </AuthenticationCard>
</template>

