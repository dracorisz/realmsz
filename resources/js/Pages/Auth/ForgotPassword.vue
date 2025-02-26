<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

defineProps({
  status: String,
});

const form = useForm({
  email: "",
});

const submit = () => {
  form.post(route("password.email"));
};
</script>

<template>
  <Head title="Forgot Password" />

  <AuthenticationCard>
    <template #logo>
      <ApplicationLogo />
    </template>

    <div class="mb-5 ml-1 text-sm text-white/70">
      <p>Have you lost your password? We can email you a password reset link and you can choose a new one.</p>
    </div>

    <form @submit.prevent="submit" class="flex flex-col gap-5">
      <div class="flex w-full flex-col gap-1">
        <InputLabel for="email" value="Email" />
        <TextInput id="email" v-model="form.email" type="email" required autocomplete="username" />
        <InputError class="mt-2" :message="form.errors.email" />
      </div>

      <div v-if="status" class="w-full text-sm text-white/70">
        <p>{{ status }}</p>
      </div>

      <div class="flex w-full items-start gap-2 pl-px">
        <Link :href="route('register')" class="link">Not registered?</Link>
        <PrimaryButton class="ml-auto" color="#000" opacity="30" hoverOpacity="50" :disabled="form.processing">Email Me</PrimaryButton>
      </div>
    </form>
  </AuthenticationCard>
</template>

