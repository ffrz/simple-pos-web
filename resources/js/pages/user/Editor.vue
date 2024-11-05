<script setup>
import { validateEmail } from "@/helpers/validations";
import { router, useForm, usePage } from "@inertiajs/vue3";
import { useQuasar } from "quasar";
import { onMounted } from "vue";

const page = usePage();
const $q = useQuasar();

const form = useForm({
  id: page.props.data.id,
  name: page.props.data.name,
  email: page.props.data.email,
  password: "",
  admin: !!page.props.data.admin,
  active: !!page.props.data.active,
});

onMounted(() => {
  console.log(page.props.data);
});

const submit = () => {
  form.clearErrors();
  form.post("/user-v2/save",
    {
      preserveScroll: true,
      onError: (response) => {
        $q.notify({
          message: response.message,
          icon: "info",
          color: "negative",
          actions: [
            { icon: "close", color: "white", round: true, dense: true },
          ],
        });
      },
    }
  );
};

</script>

<template>
  <authenticated-layout>
    <q-page class="row">
      <i-head title="Add User" />
      <div class="col col-lg-6 q-pa-md">
        <q-form class="row" @submit.prevent="submit">
          <q-card square flat bordered class="col q-pa-sm">
            <q-card-section>
              <div class="text-h6">
                <template v-if="!!form.id">Edit User </template>
                <template v-else> Add User </template>
              </div>
            </q-card-section>
            <q-card-section class="q-pt-none">
              <input type="hidden" name="id" v-model="form.id" />
              <q-input autofocus v-model.trim="form.name" label="Name" lazy-rules :error="!!form.errors.name"
                :disable="form.processing" :error-message="form.errors.name" :rules="[
                  (val) => (val && val.length > 0) || 'Please enter name.',
                ]" />
              <q-input v-model.trim="form.email" type="email" label="Email Address" lazy-rules
                :disable="form.processing" :error="!!form.errors.email" :error-message="form.errors.email"
                :rules="[(val) => validateEmail(val) || 'Must be a valid email.']" />
              <q-input v-model="form.password" type="password" label="Password" lazy-rules :disable="form.processing"
                :error="!!form.errors.password" :error-message="form.errors.password" />
              <q-checkbox class="full-width q-pl-none" v-model="form.admin" :disable="form.processing"
                label="Administrator" />
              <q-checkbox class="full-width q-pl-none" v-model="form.active" :disable="form.processing"
                label="Active" />
            </q-card-section>
            <q-card-actions>
              <q-btn type="submit" label="Save" color="primary" icon="check" :disable="form.processing" />
              <q-btn label="Cancel" v-close-popup color="grey-7" icon="close" :disable="form.processing" @click="router.get('/user-v2')"/>
            </q-card-actions>
          </q-card>
        </q-form>
      </div>
    </q-page>
  </authenticated-layout>
</template>
