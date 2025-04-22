<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ActionSection from "@/Components/ActionSection.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import axios from 'axios';

const props = defineProps({
  user: {
    type: Object,
    required: true
  }
});

const selectedPlan = ref('monthly');

const plans = {
  monthly: {
    name: 'Monthly Premium',
    price: '$9.99',
    period: 'month',
    features: [
      'Unlimited access to all features',
      'Priority support',
      'Advanced analytics',
      'Custom branding',
      'API access'
    ]
  },
  yearly: {
    name: 'Yearly Premium',
    price: '$99.99',
    period: 'year',
    features: [
      'All Monthly Premium features',
      'Save 16% compared to monthly',
      'Priority feature requests',
      'Dedicated account manager',
      'Early access to new features'
    ]
  }
};

const handleSubscription = async (plan) => {
    try {
        const response = await axios.post(route('subscription.checkout'), { plan_id: plan.id });
        if (response.data.url) {
            window.location.href = response.data.url;
        }
    } catch (error) {
        console.error('Subscription error:', error);
    }
};
</script>

<template>
  <ActionSection>
    <template #title>Subscription Plans</template>
    <template #description>Choose the plan that best fits your needs.</template>
    <template #content>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Monthly Plan -->
        <div class="rounded-lg border border-gray-200 p-6">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900">{{ plans.monthly.name }}</h3>
            <span class="text-2xl font-bold text-gray-900">{{ plans.monthly.price }}</span>
          </div>
          <p class="mt-2 text-sm text-gray-500">per {{ plans.monthly.period }}</p>
          
          <ul class="mt-6 space-y-4">
            <li v-for="feature in plans.monthly.features" :key="feature" class="flex items-start">
              <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <span class="ml-3 text-sm text-gray-700">{{ feature }}</span>
            </li>
          </ul>

          <div class="mt-6">
            <PrimaryButton 
              @click="handleSubscription(plans.monthly)"
              class="w-full justify-center"
              :disabled="user.membership_status === 'active'"
            >
              {{ user.membership_status === 'active' ? 'Current Plan' : 'Subscribe Monthly' }}
            </PrimaryButton>
          </div>
        </div>

        <!-- Yearly Plan -->
        <div class="rounded-lg border-2 border-indigo-500 p-6 bg-indigo-50">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900">{{ plans.yearly.name }}</h3>
            <span class="text-2xl font-bold text-gray-900">{{ plans.yearly.price }}</span>
          </div>
          <p class="mt-2 text-sm text-gray-500">per {{ plans.yearly.period }}</p>
          
          <ul class="mt-6 space-y-4">
            <li v-for="feature in plans.yearly.features" :key="feature" class="flex items-start">
              <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <span class="ml-3 text-sm text-gray-700">{{ feature }}</span>
            </li>
          </ul>

          <div class="mt-6">
            <PrimaryButton 
              @click="handleSubscription(plans.yearly)"
              class="w-full justify-center"
              :disabled="user.membership_status === 'active'"
            >
              {{ user.membership_status === 'active' ? 'Current Plan' : 'Subscribe Yearly' }}
            </PrimaryButton>
          </div>
        </div>
      </div>

      <!-- Current Subscription Status -->
      <div v-if="user.membership_status === 'active'" class="mt-8 p-4 bg-green-50 rounded-lg">
        <div class="flex items-center">
          <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          <span class="ml-2 text-sm text-green-700">You are currently on a Premium subscription.</span>
        </div>
      </div>
    </template>
  </ActionSection>
</template> 