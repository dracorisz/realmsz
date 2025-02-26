<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

const form = useForm({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
  terms: false,
});

const submit = () => {
  form.post(route("register"), { onFinish: () => form.reset("password", "password_confirmation") });
};
</script>

<template>
  <Head title="Register" />

  <AuthenticationCard>
    <template #logo>
      <ApplicationLogo />
    </template>
    <form @submit.prevent="submit" class="flex flex-col gap-5">
      <div class="flex flex-col gap-1">
        <InputLabel for="name" value="Full Name" />
        <TextInput id="name" v-model="form.name" type="text" required autofocus autocomplete="name" />
        <InputError class="mt-2" :message="form.errors.name" />
      </div>
      <div class="flex flex-col gap-1">
        <InputLabel for="email" value="Email" />
        <TextInput id="email" v-model="form.email" type="email" required autocomplete="username" />
        <InputError class="mt-2" :message="form.errors.email" />
      </div>
      <div class="flex flex-col gap-1">
        <InputLabel for="password" value="Password" />
        <TextInput id="password" v-model="form.password" type="password" required autocomplete="new-password" />
        <InputError class="mt-2" :message="form.errors.password" />
      </div>
      <div class="flex flex-col gap-1">
        <InputLabel for="password_confirmation" value="Confirm Password" />
        <TextInput id="password_confirmation" v-model="form.password_confirmation" type="password" required autocomplete="new-password" />
        <InputError class="mt-2" :message="form.errors.password_confirmation" />
      </div>
      <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="flex flex-col gap-1 -ml-1">
        <InputLabel for="terms">
          <div class="flex items-center gap-2">
            <Checkbox id="terms" v-model:checked="form.terms" name="terms" required />
            <div class="text-xs">
              <span>I accept </span>
              <a target="_blank" class="hover:text-white transition-colors" :href="route('terms')">Terms of Service</a>
              <span> and </span>
              <a target="_blank" class="hover:text-white transition-colors" :href="route('policy')">Privacy Policy</a>
            </div>
          </div>
          <InputError class="mt-2" :message="form.errors.terms" />
        </InputLabel>
      </div>
      <div class="flex items-center justify-end">
        <Link :href="route('login')" class="link">Already registered?</Link>
        <PrimaryButton class="ml-3" color="#000" opacity="30" hoverOpacity="50" :disabled="form.processing">Register</PrimaryButton>
      </div>
    </form>
  </AuthenticationCard>
</template>

