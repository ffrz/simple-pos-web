<script setup>
import { onMounted, ref, watch } from "vue";
import axios from "axios";
import { exportFile, useQuasar } from "quasar";
import { usePage } from "@inertiajs/vue3";
import { useApiForm } from "@/helpers/useApiForm";

const page = usePage();
const currentUser = page.props.auth.user;
const $q = useQuasar();
const tableRef = ref(null);
const rows = ref([]);
const loading = ref(true);
const filter = ref("");
const showEditor = ref();
const editorMode = ref("add");
let currentRow = null;
const pagination = ref({
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 10,
  sortBy: "name",
  descending: false,
});

const columns = [
  {
    name: "name",
    label: "Name",
    field: "name",
    align: "left",
    sortable: true
  },
  {
    name: "description",
    label: "Description",
    field: "description",
    align: "left",
    sortable: true,
  },
  {
    name: "action",
    label: "Action",
    align: "center"
  },
];

let form = useApiForm({
  id: 0,
  name: "",
  description: "",
});

onMounted(() => {
  filter.value = localStorage.getItem("simple-pos.product-category-list-page.filter");
  fetchItems();
});

watch(filter, (newValue) => {
  if (!newValue && newValue != "") newValue = "";
  localStorage.setItem("simple-pos.product-category-list-page.filter", newValue);
});

const addItem = () => {
  form.id = null;
  form.reset();
  form.clearErrors();
  editorMode.value = "add";
  showEditor.value = true;
};

const editItem = (row) => {
  currentRow = row;
  form.clearErrors();
  editorMode.value = "edit";
  showEditor.value = true;
  form.id = row.id;
  form.name = row.name;
  form.description = row.description;
};

const submitForm = () => {
  form.clearErrors();
  form.submit(
    editorMode.value == "add" ? "post" : "put",
    "/inventory/product-category" + (editorMode.value == "add" ? "" : "/" + form.id),
    {
      preserveScroll: true,
      onSuccess: (response) => {
        showEditor.value = false;
        form.reset();
        $q.notify({
          message: response.message,
          icon: "info",
          color: "green",
          actions: [
            { icon: "close", color: "white", round: true, dense: true },
          ],
        });
        fetchItems();
      },
    }
  );
};

const deleteItem = (row) => {
  $q.dialog({
    title: "Confirm",
    icon: "question",
    message: "Are you sure you want to delete " + row.name + "?",
    focus: "cancel",
    cancel: true,
    persistent: true,
  }).onOk(() => {
    loading.value = true;
    axios
      .delete("/inventory/product-category/" + row.id)
      .then((response) => {
        $q.notify(response.data.message);
        fetchItems();
      })
      .finally(() => {
        loading.value = false;
      })
      .catch((error) => {
        let message = "";
        if (error.response.data && error.response.data.message) {
          message = error.response.data.message;
        } else if (error.message) {
          message = error.message;
        }
        $q.notify({ message: message, color: "red" });
        console.log(error);
      });
  });
};

const fetchItems = (props = null) => {
  let params = {
    page: pagination.value.page,
    per_page: pagination.value.rowsPerPage,
    order_by: pagination.value.sortBy,
    order_type: pagination.value.descending ? "desc" : "asc",
    filter: filter.value,
  };

  if (props != null) {
    params.page = props.pagination.page;
    params.per_page = props.pagination.rowsPerPage;
    params.order_by = props.pagination.sortBy;
    params.order_type = props.pagination.descending ? "desc" : "asc";
    params.filter = props.filter;
    filter.value = props.filter;
  }

  loading.value = true;

  axios
    .get("/inventory/product-category/data", { params: params })
    .then((response) => {
      rows.value = response.data.data;
      pagination.value.page = response.data.current_page;
      pagination.value.rowsPerPage = response.data.per_page;
      pagination.value.rowsNumber = response.data.total;
      if (props) {
        pagination.value.sortBy = props.pagination.sortBy;
        pagination.value.descending = props.pagination.descending;
      }
    })
    .finally(() => {
      loading.value = false;
    });
};

</script>


<template>
  <authenticated-layout>
    <q-page>
      <div class="q-pa-md">
        <q-table class="alt-row" ref="tableRef" flat bordered square :dense="true || $q.screen.lt.md" color="primary" row-key="id"
          virtual-scroll title="Users" v-model:pagination="pagination" :filter="filter" :loading="loading"
          :columns="columns" :rows="rows" :rows-per-page-options="[10, 25, 50]" @request="fetchItems" binary-state-sort>
          <template v-slot:loading>
            <q-inner-loading showing color="red" />
          </template>

          <template v-slot:top-left>
            <div class="q-gutter-sm">
              <q-btn color="primary" icon="add" @click="addItem" label="Add Category" class="desktop-only" />
              <!--<q-btn no-caps color="grey-8" icon="archive" @click="exportToCsv" />-->
            </div>
          </template>

          <template v-slot:top-right>
            <q-input dense debounce="300" v-model="filter" placeholder="Search" clearable>
              <template v-slot:append>
                <q-icon name="search" />
              </template>
            </q-input>
          </template>

          <template v-slot:no-data="{ icon, message, filter }">
            <div class="full-width row flex-center text-grey-8 q-gutter-sm">
              <q-icon size="2em" name="sentiment_dissatisfied" />
              <span>
                Well this is sad... {{ message }}
                {{ filter ? " with term " + filter : "" }}</span>
              <q-icon size="2em" :name="filter ? 'filter_b_and_w' : icon" />
            </div>
          </template>
          <template v-slot:body="props">
            <q-tr :props="props">
              <q-td key="name" :props="props">
                {{ props.row.name }}
              </q-td>
              <q-td key="description" :props="props">
                {{ props.row.description }}
              </q-td>
              <q-td key="action" class="q-gutter-x-sm" :props="props" align="center">
                <q-btn rounded dense color="grey" icon="edit" @click="editItem(props.row)">
                  <q-tooltip>Edit Item</q-tooltip>
                </q-btn>
                <q-btn rounded dense color="red" icon="delete" @click="deleteItem(props.row)">
                  <q-tooltip>Delete Item</q-tooltip>
                </q-btn>
              </q-td>
            </q-tr>
          </template>
        </q-table>
      </div>

      <q-dialog v-model="showEditor" persistent>
        <q-card square style="min-width: 350px" class="q-pa-md">
          <q-form class="q-gutter-md" @submit.prevent="submitForm">
            <q-card-section>
              <div class="text-h6">
                <template v-if="editorMode == 'add'"> Add Category </template>
                <template v-else> Edit Category </template>
              </div>
            </q-card-section>
            <q-card-section class="q-pt-none">
              <input type="hidden" name="id" v-model="form.id" />
              <q-input autofocus v-model.trim="form.name" label="Name" lazy-rules :error="!!form.errors.name"
                :disable="form.processing" :error-message="form.errors.name" :rules="[
                  (val) => (val && val.length > 0) || 'Please enter category name.',
                ]" />
              <q-input v-model.trim="form.description" label="Description" lazy-rules :disable="form.processing"
                :error="!!form.errors.description" :error-message="form.errors.description" />
            </q-card-section>
            <q-card-actions align="right">
              <q-btn type="submit" label="Save" color="primary" icon="check" :disable="form.processing" />
              <q-btn label="Cancel" v-close-popup color="grey-7" icon="close" :disable="form.processing" />
            </q-card-actions>
          </q-form>
        </q-card>
      </q-dialog>
    </q-page>
  </authenticated-layout>
</template>
