<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import ActionSection from "@/Components/ActionSection.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
});

const form = useForm({
  display_name: props.user.display_name || "",
  phone: props.user.phone || "",
  birthday: props.user.birthday || "",
  gender: props.user.gender || "",
  bio: props.user.bio || "",
  instagram: props.user.instagram || "",
  twitter: props.user.twitter || "",
  linkedin: props.user.linkedin || "",
  github: props.user.github || "",
  website: props.user.website || "",
});

const updateProfile = () => {
  form.put(route("profile.update"), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
    },
  });
};

const handleSubscription = async (plan) => {
  try {
    const response = await axios.post(route("subscriptions.checkout"), { plan_id: plan.id });
    if (response.data.url) {
      window.location.href = response.data.url;
    }
  } catch (error) {
    console.error("Subscription error:", error);
  }
};
</script>

<template>
  <ActionSection>
    <template #title>Profile Details</template>
    <template #description>Update your profile information and social media connections.</template>
    <template #content>
      <!-- Membership Status -->
      <div class="mb-6 rounded-lg bg-gray-50/20 p-4">
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-lg font-medium text-gray-300">Membership Status</h3>
            <p class="mt-1 text-sm text-gray-400">
              {{ user.membership_status === "active" ? "Active Premium Member" : "Free Account" }}
            </p>
          </div>
          <div v-if="user.membership_status !== 'active'" class="flex-shrink-0">
            <SecondaryButton as="a" :href="handleSubscription">
              Upgrade to Premium
            </SecondaryButton>
          </div>
        </div>
      </div>

      <form @submit.prevent="updateProfile" class="space-y-6">
        <!-- Basic Information -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
          <div>
            <InputLabel for="display_name" value="Display Name" />
            <TextInput id="display_name" v-model="form.display_name" type="text" class="mt-1 block w-full" placeholder="Enter your display name" />
            <InputError :message="form.errors.display_name" class="mt-2" />
          </div>

          <div>
            <InputLabel for="phone" value="Phone Number" />
            <TextInput id="phone" v-model="form.phone" type="tel" class="mt-1 block w-full" placeholder="+1 (555) 000-0000" />
            <InputError :message="form.errors.phone" class="mt-2" />
          </div>

          <div>
            <InputLabel for="birthday" value="Birthday" />
            <TextInput id="birthday" v-model="form.birthday" type="date" class="mt-1 block w-full" />
            <InputError :message="form.errors.birthday" class="mt-2" />
          </div>

          <div>
            <InputLabel for="gender" value="Gender" />
            <select id="gender" v-model="form.gender" class="mt-1 block w-full">
              <option value="">Select Gender</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
              <option value="prefer_not_to_say">Prefer not to say</option>
            </select>
            <InputError :message="form.errors.gender" class="mt-2" />
          </div>
        </div>

        <!-- Bio -->
        <div>
          <InputLabel for="bio" value="Bio" />
          <textarea id="bio" v-model="form.bio" rows="3" class="mt-1 block w-full" placeholder="Tell us about yourself..."></textarea>
          <InputError :message="form.errors.bio" class="mt-2" />
        </div>

        <!-- Social Media -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
          <div>
            <InputLabel for="instagram" value="Instagram" />
            <TextInput id="instagram" v-model="form.instagram" type="text" class="mt-1 block w-full" placeholder="https://instagram.com/username" />
            <InputError :message="form.errors.instagram" class="mt-2" />
          </div>

          <div>
            <InputLabel for="twitter" value="Twitter" />
            <TextInput id="twitter" v-model="form.twitter" type="text" class="mt-1 block w-full" placeholder="https://twitter.com/username" />
            <InputError :message="form.errors.twitter" class="mt-2" />
          </div>

          <div>
            <InputLabel for="linkedin" value="LinkedIn" />
            <TextInput id="linkedin" v-model="form.linkedin" type="text" class="mt-1 block w-full" placeholder="https://linkedin.com/in/username" />
            <InputError :message="form.errors.linkedin" class="mt-2" />
          </div>

          <div>
            <InputLabel for="github" value="GitHub" />
            <TextInput id="github" v-model="form.github" type="text" class="mt-1 block w-full" placeholder="https://github.com/username" />
            <InputError :message="form.errors.github" class="mt-2" />
          </div>

          <div class="md:col-span-2">
            <InputLabel for="website" value="Personal Website" />
            <TextInput id="website" v-model="form.website" type="url" class="mt-1 block w-full" placeholder="https://yourwebsite.com" />
            <InputError :message="form.errors.website" class="mt-2" />
          </div>
        </div>

        <div class="flex items-center justify-end">
          <PrimaryButton :disabled="form.processing"> Save Changes </PrimaryButton>
        </div>
      </form>
    </template>
  </ActionSection>
</template>

