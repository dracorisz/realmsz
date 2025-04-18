<template>
  <AppLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ package.name }}
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
              <div>
                <h3 class="text-2xl font-bold text-gray-900">
                  {{ package.name }}
                </h3>
                <p class="mt-4 text-gray-600">{{ package.description }}</p>
                <div class="mt-6">
                  <span class="text-4xl font-bold text-gray-900"
                    >${{ package.price }}</span
                  >
                  <span class="text-gray-500">/{{ package.billing_period }}</span>
                </div>

                <div class="mt-8">
                  <ul class="space-y-4">
                    <li
                      v-for="feature in package.features"
                      :key="feature"
                      class="flex items-center"
                    >
                      <svg
                        class="h-5 w-5 text-green-500"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M5 13l4 4L19 7"
                        />
                      </svg>
                      <span class="ml-3 text-gray-600">{{ feature }}</span>
                    </li>
                  </ul>
                </div>
              </div>

              <div class="bg-gray-50 p-6 rounded-lg">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">
                  Subscribe to {{ package.name }}
                </h4>
                <form @submit.prevent="subscribe" class="space-y-4">
                  <div>
                    <InputLabel for="card_number" value="Card Number" />
                    <TextInput
                      id="card_number"
                      v-model="form.card_number"
                      type="text"
                      class="mt-1 block w-full"
                      required
                    />
                  </div>

                  <div class="grid grid-cols-2 gap-4">
                    <div>
                      <InputLabel for="expiry" value="Expiry Date" />
                      <TextInput
                        id="expiry"
                        v-model="form.expiry"
                        type="text"
                        class="mt-1 block w-full"
                        placeholder="MM/YY"
                        required
                      />
                    </div>
                    <div>
                      <InputLabel for="cvc" value="CVC" />
                      <TextInput
                        id="cvc"
                        v-model="form.cvc"
                        type="text"
                        class="mt-1 block w-full"
                        required
                      />
                    </div>
                  </div>

                  <div>
                    <InputLabel for="name" value="Name on Card" />
                    <TextInput
                      id="name"
                      v-model="form.name"
                      type="text"
                      class="mt-1 block w-full"
                      required
                    />
                  </div>

                  <div class="mt-6">
                    <PrimaryButton
                      class="w-full"
                      :class="{ 'opacity-25': form.processing }"
                      :disabled="form.processing"
                    >
                      Subscribe Now
                    </PrimaryButton>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
  package: {
    type: Object,
    required: true,
  },
});

const form = useForm({
  card_number: '',
  expiry: '',
  cvc: '',
  name: '',
});

const subscribe = () => {
  form.post(route('packages.subscribe', props.package.slug), {
    onSuccess: () => {
      form.reset();
    },
  });
};
</script> 