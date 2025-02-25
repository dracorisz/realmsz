<script setup>
import { ref } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import LogoutOtherBrowserSessionsForm from "./Partials/LogoutOtherBrowserSessionsForm.vue";
import TwoFactorAuthenticationForm from "./Partials/TwoFactorAuthenticationForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
import Activity from "./Partials/Activity.vue";
import Membership from "./Partials/Membership.vue";

defineProps({
  confirmsTwoFactorAuthentication: Boolean,
  sessions: Array,
});

const tab = ref(1);
</script>

<template>
  <AppLayout title="Profile">
    <div class="flex w-full items-start justify-start gap-5">
      <div class="flex flex-col rounded-2xl w-1/6 bg-white/5 text-left border border-white/5 text-xs">
        <!-- active class -->
        <button @click="tab = 1" class="text-left rounded-t-xl ah-[36px] px-3 text-white/70 hover:bg-white/10 hover:text-white">Profile</button>
        <button @click="tab = 2" class="text-left border-y border-white/5 ah-[36px] px-3 text-white/70 hover:bg-white/10 hover:text-white">Security</button>
        <button @click="tab = 3" class="text-left border-b border-white/5 ah-[36px] px-3 text-white/70 hover:bg-white/10 hover:text-white">Sessions</button>
        <button @click="tab = 4" class="text-left border-b border-white/5 ah-[36px] px-3 text-white/70 hover:bg-white/10 hover:text-white">Membership</button>
        <button @click="tab = 5" class="text-left rounded-b-xl border-b border-white/5 ah-[36px] px-3 text-white/70 hover:bg-white/10 hover:text-white">Delete</button>
      </div>
      <div class="grid w-5/6 grid-cols-1 items-start justify-start gap-5">
        <UpdateProfileInformationForm v-if="$page.props.jetstream.canUpdateProfileInformation && tab == 1" :user="$page.props.auth.user" />
        <UpdatePasswordForm v-if="$page.props.jetstream.canUpdatePassword && tab == 2" />
        <TwoFactorAuthenticationForm v-if="$page.props.jetstream.canManageTwoFactorAuthentication && tab == 2" :requires-confirmation="confirmsTwoFactorAuthentication" />
        <LogoutOtherBrowserSessionsForm v-if="tab == 3" :sessions="sessions" />
        <Activity v-if="tab == 3" />
        <Membership v-if="tab == 4" />
        <DeleteUserForm v-if="$page.props.jetstream.hasAccountDeletionFeatures && tab == 5" />
      </div>
    </div>
  </AppLayout>
</template>

