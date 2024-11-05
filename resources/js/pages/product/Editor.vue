<script setup>
import { router, useForm, usePage } from "@inertiajs/vue3";
import { useQuasar } from "quasar";
import { onMounted } from "vue";

const page = usePage();
const $q = useQuasar();
const productTypes = page.props.types;

const form = useForm({
  ...page.props.data,
  id: page.props.data.id,
  id_formatted: page.props.data.id ? page.props.data.id_formatted : '-auto generated-',
  active: !!page.props.data.active,
});

onMounted(() => {
  console.log(page.props);
});

const submit = () => {
  form.clearErrors();
  form.post("/inventory/product/save",
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
      <i-head title="Product" />
      <div class="col col-lg-6 q-pa-md">
        <q-form class="row" @submit.prevent="submit">
          <q-card square flat bordered class="col q-pa-sm">
            <q-card-section>
              <div class="text-h6">
                <template v-if="!!form.id">Edit Product </template>
                <template v-else> Add Product </template>
              </div>
            </q-card-section>
            <q-card-section class="q-pt-none">
              <input autofocus type="hidden" name="id" v-model="form.id" />
              <q-input dense v-model="form.id_formatted" label="Code" readonly placeholder="Generated" class="q-mb-md"/>
              <q-select dense autofocus emit-value map-options v-model="form.type" label="Type" :options="productTypes"
                option-value="id" option-label="label" behavior="menu" :error-message="form.errors.type"
                :disable="form.processing" :error="!!form.errors.type" />
              <q-input dense v-model.trim="form.code" label="Name" lazy-rules :error="!!form.errors.code"
                :disable="form.processing" :error-message="form.errors.code" :rules="[
                  (val) => (val && val.length > 0) || 'Please enter name.',
                ]" />
              <q-input dense v-model.trim="form.description" label="Description" lazy-rules :disable="form.processing"
                :error="!!form.errors.description" :error-message="form.errors.description" />
              <q-checkbox class="full-width q-pl-none" v-model="form.active" :disable="form.processing"
                label="Active" />
            </q-card-section>
            <q-card-actions>
              <q-btn type="submit" label="Save" color="primary" icon="check" :disable="form.processing" />
              <q-btn label="Cancel" v-close-popup color="grey-7" icon="close" :disable="form.processing"
                @click="router.get('/inventory/product')" />
            </q-card-actions>
          </q-card>
        </q-form>
      </div>
    </q-page>
  </authenticated-layout>
</template>
