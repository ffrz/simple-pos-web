<script setup>
import { onMounted, ref, watch } from "vue";
import axios from "axios";
import { useQuasar } from "quasar";
import { router, usePage } from "@inertiajs/vue3";

const page = usePage();
const currentUser = page.props.auth.user;
const $q = useQuasar();
const tableRef = ref(null);
const rows = ref([]);
const loading = ref(true);
const filter = ref("");
const pagination = ref({
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 10,
  sortBy: "name",
  descending: false,
});

const columns = [
  {
    name: "code",
    label: "Code",
    field: "code",
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
    name: "stock",
    label: "QoH",
    field: "stock",
    align: "right",
    sortable: true,
  },
  {
    name: "cost",
    label: "Cost",
    field: "cost",
    align: "right",
    sortable: true,
  },
  {
    name: "price",
    label: "Price",
    field: "price",
    align: "right",
    sortable: true,
  },
  { name: "action", label: "Action", align: "center" },
];

onMounted(() => {
  filter.value = localStorage.getItem("simple-pos.product-list-page.filter");
  fetchItems();
});

watch(filter, (newValue) => {
  if (!newValue && newValue != "") newValue = "";
  localStorage.setItem("simple-pos.product-list-page.filter", newValue);
});

const deleteItem = (row) => {
  $q.dialog({
    title: "Confirm",
    icon: "question",
    message: "Are you sure you want to delete " + row.code + "?",
    focus: "cancel",
    cancel: true,
    persistent: true,
  }).onOk(() => {
    loading.value = true;
    axios
      .post("/inventory/product/delete/" + row.id)
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
    .get("/inventory/product/data", { params: params })
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
        <q-table ref="tableRef" flat bordered square :dense="true || $q.screen.lt.md" color="primary" row-key="id"
          virtual-scroll title="Users" v-model:pagination="pagination" :filter="filter" :loading="loading"
          :columns="columns" :rows="rows" :rows-per-page-options="[10, 25, 50]" @request="fetchItems" binary-state-sort>
          <template v-slot:loading>
            <q-inner-loading showing color="red" />
          </template>

          <template v-slot:top-left>
            <div class="q-gutter-sm">
              <q-btn color="primary" icon="add" @click="router.get('/inventory/product/add')" label="Add" />
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
            <q-tr :props="props" :class="(!props.row.active) ? 'bg-red-1' : ''">
              <q-td key="code" :props="props">
                {{ props.row.code }}
              </q-td>
              <q-td key="description" :props="props">
                {{ props.row.description }}
              </q-td>
              <q-td key="stock" :props="props" align="right">
                {{ props.row.stock }}
              </q-td>
              <q-td key="cost" :props="props" align="right">
                {{ props.row.cost }}
              </q-td>
              <q-td key="price" :props="props" align="right">
                {{ props.row.price }}
              </q-td>
              <q-td key="action" class="q-gutter-x-sm" :props="props" align="center">
                <q-btn rounded dense color="grey" icon="edit"
                  @click="router.get('/inventory/product/edit/' + props.row.id)">
                  <q-tooltip>Edit</q-tooltip>
                </q-btn>
                <q-btn rounded dense color="red" icon="delete" @click="deleteItem(props.row)">
                  <q-tooltip>Delete</q-tooltip>
                </q-btn>
              </q-td>
            </q-tr>
          </template>
        </q-table>
      </div>
    </q-page>
  </authenticated-layout>
</template>
