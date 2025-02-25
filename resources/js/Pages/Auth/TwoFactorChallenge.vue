<script setup>
import { nextTick, ref } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

const recovery = ref(false);

const form = useForm({
  code: "",
  recovery_code: "",
});

const recoveryCodeInput = ref(null);
const codeInput = ref(null);

const toggleRecovery = async () => {
  recovery.value ^= true;

  await nextTick();

  if (recovery.value) {
    recoveryCodeInput.value.focus();
    form.code = "";
  } else {
    codeInput.value.focus();
    form.recovery_code = "";
  }
};

const submit = () => {
  form.post(route("two-factor.login"));
};
</script>

<template>
  <Head title="Two-factor Confirmation" />

  <AuthenticationCard>
    <template #logo>
      <ApplicationLogo />
    </template>

    <div class="mb-5 text-sm">
      <template v-if="!recovery">
        <p class="text-white">Please confirm access to your account by entering the authentication code provided by your authenticator application.</p>
      </template>
      <template v-else>
        <p>Please confirm access to your account by entering one of your emergency recovery codes.</p>
      </template>
    </div>

    <form @submit.prevent="submit">
      <div v-if="!recovery" class="flex flex-col gap-1">
        <InputLabel for="code" value="Code" />
        <TextInput id="code" ref="codeInput" v-model="form.code" type="text" inputmode="numeric" autofocus autocomplete="one-time-code" />
        <InputError class="mt-2" :message="form.errors.code" />
      </div>

      <div v-else class="flex flex-col gap-1">
        <InputLabel for="recovery_code" value="Recovery Code" />
        <TextInput id="recovery_code" ref="recoveryCodeInput" v-model="form.recovery_code" type="text" autocomplete="one-time-code" />
        <InputError class="mt-2" :message="form.errors.recovery_code" />
      </div>

      <div class="mt-5 flex items-center justify-end">
        <button type="button" class="cursor-pointer text-sm" @click.prevent="toggleRecovery">
          <template v-if="!recovery">
            <p class="text-white">Use a recovery code</p>
          </template>
          <template v-else>
            <p>Use an authentication code</p>
          </template>
        </button>

        <PrimaryButton class="ml-3" :disabled="form.processing">Login</PrimaryButton>
      </div>
    </form>
  </AuthenticationCard>
</template>

