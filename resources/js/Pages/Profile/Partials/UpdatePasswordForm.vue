<script setup>

import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
});

const updatePassword = () => {
  form.clearErrors();
  form.put(route('password.update'), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
    onError: () => {
      if (form.errors.password) {
        form.reset('password', 'password_confirmation');
        passwordInput.value.focus();
      }
      if (form.errors.current_password) {
        form.reset('current_password');
        currentPasswordInput.value.focus();
      }
    },
  });
};
</script>

<template>
  <q-form class="row" @submit.prevent="updatePassword">
    <q-card square flat bordered class="col q-pa-sm">
      <q-card-section>
        <h2 class="text-h6 q-my-xs">Update Password</h2>
        <p>Ensure your account is using a long, random password to stay
          secure.</p>
        <q-input square v-model="form.current_password" label="Current Password" type="password" lazy-rules
          :disable="form.processing" :error="!!form.errors.current_password"
          :error-message="form.errors.current_password"
          :rules="[(val) => (val && val.length > 0) || 'Current password is required.']" />
        <q-input square v-model="form.password" label="New Password" type="password" lazy-rules
          :disable="form.processing" :error="!!form.errors.password" :error-message="form.errors.password"
          :rules="[(val) => (val && val.length > 0) || 'Current password is required.']" />
        <q-input square v-model="form.password_confirmation" label="Confirm your Password" type="password" lazy-rules
          :disable="form.processing" :error="!!form.errors.password_confirmation"
          :error-message="form.errors.password_confirmation"
          :rules="[(val) => (val && val.length > 0) || 'Current password is required.']" />
      </q-card-section>
      <q-card-section>
        <q-btn type="submit" color="grey-8" label="Save" :disable="form.processing" />
      </q-card-section>
    </q-card>
  </q-form>
</template>
