<script setup>
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInputRef = ref(null);

const form = useForm({
  password: '',
});

const confirmUserDeletion = () => {
  confirmingUserDeletion.value = true;
  nextTick(() => passwordInputRef.value.focus());
};

const deleteUser = () => {
  if (form.password.length == 0) {
    passwordInputRef.value.focus();
    return;
  }

  form.clearErrors();
  form.delete(route('profile.destroy'), {
    preserveScroll: true,
    onSuccess: () => closeModal(),
    onError: () => passwordInputRef.value.focus(),
    onFinish: () => form.reset(),
  });
};

const closeModal = () => {
  confirmingUserDeletion.value = false;
  form.clearErrors();
  form.reset();
};
</script>

<template>
  <q-form class="row" @submit.prevent="deleteUser">
    <q-card square flat bordered class="col q-pa-sm">
      <q-card-section>
        <h2 class="text-h6 q-my-xs">Delete Account</h2>
        <p>Once your account is deleted, all of its resources and data will
          be permanently deleted. Before deleting your account, please
          download any data or information that you wish to retain.</p>
        <q-btn color="negative" @click="confirmUserDeletion">Delete Account</q-btn>
      </q-card-section>
    </q-card>
    <q-dialog v-model="confirmingUserDeletion" persistent>
      <q-card style="min-width: 350px" class="q-pa-md">
        <q-card-section>
          <div class="text-h6 q-mb-md">Are you sure you want to delete your account?</div>
          <p class="text-grey-8">
            Once your account is deleted, all of its resources and data
            will be permanently deleted. Please enter your password to
            confirm you would like to permanently delete your account.
          </p>
          <q-input ref="passwordInputRef" autofocus v-model="form.password" square label="Current Password"
            type="password" lazy-rules :error="!!form.errors.password" :error-message="form.errors.password"
            :disable="form.processing" :rules="[(val) => (val && val.length > 0) || 'Password is required.']"
            @keyup.enter="deleteUser" />
        </q-card-section>
        <q-card-actions align="right" class="q-mt-lg">
          <q-btn color="grey" label="Cancel" @click="closeModal" :disable="form.processing" />
          <q-btn color="negative" label="Delete Account" :disable="form.processing" @click="deleteUser" />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </q-form>
</template>
