<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { validateEmail } from "@/helpers/validations";

let emailInput = ref(null);
let form = useForm({
  email: "",
  password: "",
  remember: false,
});

const submit = () => {
  form.clearErrors();
  form.post(route('login'), {
    preserveScroll: true,
    onError: () => {
      emailInput.value.focus();
    }
  });
};
</script>

<template>
  <guest-layout>
    <i-head title="Login" />
    <q-page class="row justify-center items-center">
      <div class="column">
        <div class="row">
          <q-form class="q-gutter-md" @submit.prevent="submit">
            <q-card square bordered class="q-pa-md shadow-1">
              <q-card-section>
                <h5 class="q-my-sm text-center">Login</h5>
              </q-card-section>
              <q-card-section>
                <q-input ref="emailInput" autofocus square v-model.trim="form.email" label="Email" lazy-rules
                  :error="!!form.errors.email" :error-message="form.errors.email" :disable="form.processing"
                  :rules="[(val) => validateEmail(val) || 'Must be a valid email.']" />
                <q-input square v-model="form.password" type="password" label="Password" :error="!!form.errors.password"
                  :error-message="form.errors.password" lazy-rules :disable="form.processing"
                  :rules="[(val) => (val && val.length > 0) || 'Please enter password',]" />
                <q-checkbox class="q-mt-sm q-pl-none" style="margin-left: -10px;" v-model="form.remember"
                  :disable="form.processing" label="Remember me" />
              </q-card-section>
              <q-card-actions>
                <q-btn type="submit" color="primary" class="full-width" label="Login" :disable="form.processing" />
              </q-card-actions>
              <q-card-section class="text-center q-pa-none q-mt-md">
                <p class="q-my-xs text-grey-7">
                  Not reigistered?
                  <i-link :href="route('register')">Create an Account</i-link>
                </p>
                <p class="q-my-xs text-grey-7">
                  Forgot password?
                  <i-link :href="route('password.request')">Reset Password</i-link>
                </p>
                <!-- <p class="q-my-xs text-grey-7">
                  Forgot password? Please contact our system administrator.
                </p> -->
              </q-card-section>
            </q-card>
          </q-form>
        </div>
      </div>
    </q-page>
  </guest-layout>
</template>

<style>
.q-card {
  width: 360px;
}
</style>
