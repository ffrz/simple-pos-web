<script setup>

import { router, useForm, usePage } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

defineProps({
  mustVerifyEmail: {
    type: Boolean,
  },
  status: {
    type: String,
  },
});

const nameInputRef = ref();
const page = usePage();
const user = page.props.auth.user;
const form = useForm({
  name: user.name,
  email: user.email,
});

const submit = () => {
  form.clearErrors();
  form.patch(route('profile.update'), {
    preserveScroll: true,
    onError: () => {
      nameInputRef.value.focus();
    }
  });
};

</script>

<template>
  <q-form class="row" @submit.prevent="submit">
    <q-card square flat bordered class="col q-pa-sm">
      <q-card-section>
        <h2 class="text-h6 q-my-xs">Profile Information</h2>
        <p>Update your account's profile information and email address.</p>
        <q-input readonly square v-model.trim="form.email" label="Email" :disable="form.processing" />
        <div v-if="mustVerifyEmail && user.email_verified_at === null">
          <p class="text-grey-7">
            Your email address is unverified.
          </p>
          <p>
            <q-btn color="secondary" @click.prevent="router.post(route('verification.send'))">
              Click here to re-send the verification email.
            </q-btn>
          </p>
          <p v-show="status === 'verification-link-sent'">
            A new verification link has been sent to your email address.
          </p>
        </div>
        <q-input ref="nameInputRef" square v-model.trim="form.name" label="Name" :disable="form.processing" lazy-rules
          :error="!!form.errors.name" :error-message="form.errors.name"
          :rules="[(val) => (val && val.length > 0) || 'Name is required.']" />
      </q-card-section>
      <q-card-section>
        <q-btn type="submit" color="grey-8" label="Save" :disable="form.processing" />
      </q-card-section>
    </q-card>
  </q-form>
</template>
