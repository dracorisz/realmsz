<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import ActionSection from "@/Components/ActionSection.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DialogModal from "@/Components/DialogModal.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
  password: "",
});

const confirmUserDeletion = () => {
  confirmingUserDeletion.value = true;

  setTimeout(() => passwordInput.value.focus(), 250);
};

const deleteUser = () => {
  form.delete(route("current-user.destroy"), {
    preserveScroll: true,
    onSuccess: () => closeModal(),
    onError: () => passwordInput.value.focus(),
    onFinish: () => form.reset(),
  });
};

const closeModal = () => {
  confirmingUserDeletion.value = false;

  form.reset();
};
</script>

<template>
  <ActionSection name="Delete Account">
    <template #title>Delete Account</template>
    <template #description>Permanently delete your account.</template>
    <template #content>
      <div class="max-w-xl text-sm">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</div>
      <div class="mt-5">
        <PrimaryButton color="#ff0033" @click="confirmUserDeletion">Delete Account</PrimaryButton>
      </div>
      <DialogModal :show="confirmingUserDeletion" @close="closeModal">
        <template #title>Delete Account</template>
        <template #content>
          <span class="ml-px">Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.</span>
          <div class="mt-4">
            <TextInput ref="passwordInput" v-model="form.password" type="password" class="mt-1 block w-3/4" placeholder="Password" autocomplete="current-password" @keyup.enter="deleteUser" />
            <InputError :message="form.errors.password" class="mt-2" />
          </div>
        </template>
        <template #footer>
          <PrimaryButton @click="closeModal">Cancel</PrimaryButton>
          <PrimaryButton color="#ff0033" class="ml-3" :disabled="form.processing" @click="deleteUser">Delete Account</PrimaryButton>
        </template>
      </DialogModal>
    </template>
  </ActionSection>
</template>

