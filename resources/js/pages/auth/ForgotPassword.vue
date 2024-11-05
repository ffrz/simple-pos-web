<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import { useQuasar } from 'quasar';
import { validateEmail } from "@/helpers/validations";

defineProps({
  status: {
    type: String,
  },
});

const $q = useQuasar();
const emailInput = ref();
const form = useForm({
  email: '',
});

const submit = () => {
  form.clearErrors();
  form.post(route('password.email'), {
    preserveScroll: true,
    onError: () => {
      emailInput.value.focus();
    }
  });
};

// watch(
//   () => usePage().props.status,
//   (newValue, oldValue) => {
//     if (newValue) {
//       // $q.notify(newValue);
//     }
//   }
// );
</script>

<template>
  <guest-layout>
    <i-head title="Forgot Password" />
    <q-page class="row justify-center items-center">
      <div class="column">
        <q-form @submit.prevent="submit">
          <q-card square bordered class="q-pa-md shadow-1">
            <q-card-section>
              <h5 class="q-my-sm text-center">Forgot Password</h5>
            </q-card-section>
            <q-card-section class="text-grey-8">
              Forgot your password? No problem. Just let us know your email
              address and we will email you a password reset link that will allow
              you to choose a new one.
            </q-card-section>
            <q-card-section v-if="status" class="text-green-9 text-weight-bold border">
              {{ status }}
            </q-card-section>
            <q-card-section>
              <q-input ref="emailInput" autofocus square v-model.trim="form.email" label="Email" lazy-rules
                :error="!!form.errors.email" :error-message="form.errors.email" :disable="form.processing"
                :rules="[(val) => validateEmail(val) || 'Must be a valid email.']">
                <template v-slot:append>
                  <q-icon name="email" />
                </template>
              </q-input>
            </q-card-section>
            <q-card-actions>
              <q-btn icon="email" type="submit" color="primary" class="full-width" label="Email Password Reset Link"
                :disable="form.processing" />
            </q-card-actions>
            <q-card-section class="text-center q-pa-none q-mt-md">
              <p class="q-my-xs text-grey-7">
                <i-link :href="route('login')">Back to login page</i-link>
              </p>
            </q-card-section>
          </q-card>
        </q-form>
      </div>
    </q-page>
  </guest-layout>
</template>

<style>
.q-card {
  width: 360px;
}
</style>
