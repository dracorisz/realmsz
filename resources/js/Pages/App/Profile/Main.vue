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
import Subscription from "./Partials/Subscription.vue";

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
        <button 
          @click="tab = 1" 
          class="text-left rounded-t-xl h-[36px] px-3 text-white/70 hover:bg-white/10 hover:text-white"
          :class="{ 'bg-white/10 text-white': tab === 1 }"
        >
          Profile
        </button>
        <button 
          @click="tab = 2" 
          class="text-left border-y border-white/5 h-[36px] px-3 text-white/70 hover:bg-white/10 hover:text-white"
          :class="{ 'bg-white/10 text-white': tab === 2 }"
        >
          Security
        </button>
        <button 
          @click="tab = 3" 
          class="text-left border-b border-white/5 h-[36px] px-3 text-white/70 hover:bg-white/10 hover:text-white"
          :class="{ 'bg-white/10 text-white': tab === 3 }"
        >
          Sessions
        </button>
        <button 
          @click="tab = 4" 
          class="text-left border-b border-white/5 h-[36px] px-3 text-white/70 hover:bg-white/10 hover:text-white"
          :class="{ 'bg-white/10 text-white': tab === 4 }"
        >
          Membership
        </button>
        <button 
          @click="tab = 5" 
          class="text-left border-b border-white/5 h-[36px] px-3 text-white/70 hover:bg-white/10 hover:text-white"
          :class="{ 'bg-white/10 text-white': tab === 5 }"
        >
          Subscription
        </button>
        <button 
          @click="tab = 6" 
          class="text-left rounded-b-xl border-b border-white/5 h-[36px] px-3 text-white/70 hover:bg-white/10 hover:text-white"
          :class="{ 'bg-white/10 text-white': tab === 6 }"
        >
          Delete
        </button>
      </div>
      <div class="w-5/6">
        <div v-show="tab === 1 && $page.props.jetstream.canUpdateProfileInformation">
          <UpdateProfileInformationForm :user="$page.props.auth.user" />
        </div>
        <div v-show="tab === 2">
          <UpdatePasswordForm v-if="$page.props.jetstream.canUpdatePassword" />
          <TwoFactorAuthenticationForm 
            v-if="$page.props.jetstream.canManageTwoFactorAuthentication" 
            :requires-confirmation="confirmsTwoFactorAuthentication" 
          />
        </div>
        <div v-show="tab === 3">
          <LogoutOtherBrowserSessionsForm :sessions="sessions" />
          <Activity />
        </div>
        <div v-show="tab === 4">
          <Membership :user="$page.props.auth.user" />
        </div>
        <div v-show="tab === 5">
          <Subscription :user="$page.props.auth.user" />
        </div>
        <div v-show="tab === 6 && $page.props.jetstream.hasAccountDeletionFeatures">
          <DeleteUserForm />
        </div>
      </div>
    </div>
  </AppLayout>
</template>

