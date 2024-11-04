<script setup>
import { onMounted, ref, watch } from "vue";
import axios from "axios";
import { exportFile, useQuasar } from "quasar";
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
  { name: "name", label: "Name", field: "name", align: "left", sortable: true },
  {
    name: "email",
    label: "Email",
    field: "email",
    align: "left",
    sortable: true,
  },
  {
    name: "admin",
    label: "Admin",
    field: "admin",
    align: "center",
    sortable: true,
  },
  { name: "action", label: "Action", align: "center" },
];

onMounted(() => {
  filter.value = localStorage.getItem("my-app.user-list-page.filter");
  fetchUsers();
});

watch(filter, (newValue) => {
  if (!newValue && newValue != "") newValue = "";
  localStorage.setItem("my-app.user-list-page.filter", newValue);
});

const deleteUser = (row) => {
  $q.dialog({
    title: "Confirm",
    icon: "question",
    message: "Are you sure you want to delete " + row.email + "?",
    focus: "cancel",
    cancel: true,
    persistent: true,
  }).onOk(() => {
    loading.value = true;
    axios
      .post("user-v2/delete/" + row.id)
      .then((response) => {
        $q.notify(response.data.message);
        fetchUsers();
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

const fetchUsers = (props = null) => {
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
    .get("user-v2/data", { params: params })
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

function wrapCsvValue(val, formatFn, row) {
  let formatted = formatFn !== void 0 ? formatFn(val, row) : val;

  formatted =
    formatted === void 0 || formatted === null ? "" : String(formatted);

  formatted = formatted.split('"').join('""');
  /**
   * Excel accepts \n and \r in strings, but some other CSV parsers do not
   * Uncomment the next two lines to escape new lines
   */
  // .split('\n').join('\\n')
  // .split('\r').join('\\r')

  return `"${formatted}"`;
}

const exportToCsv = () => {
  const content = [columns.map((col) => wrapCsvValue(col.label))]
    .concat(
      rows.value.map((row) =>
        columns
          .map((col) =>
            wrapCsvValue(
              typeof col.field === "function"
                ? col.field(row)
                : row[col.field === void 0 ? col.name : col.field],
              col.format,
              row
            )
          )
          .join(",")
      )
    )
    .join("\r\n");

  const status = exportFile("table-export.csv", content, "text/csv");

  if (status !== true) {
    $q.notify({
      message: "Browser denied file download...",
      color: "negative",
      icon: "warning",
    });
  }
};
</script>

<template>
  <authenticated-layout>
    <q-page>
      <div class="q-pa-md">
        <q-table ref="tableRef" flat bordered square :dense="true || $q.screen.lt.md" color="primary" row-key="id"
          virtual-scroll title="Users" v-model:pagination="pagination" :filter="filter" :loading="loading"
          :columns="columns" :rows="rows" :rows-per-page-options="[10, 25, 50]" @request="fetchUsers" binary-state-sort>
          <template v-slot:loading>
            <q-inner-loading showing color="red" />
          </template>

          <template v-slot:top-left>
            <div class="q-gutter-sm">
              <q-btn color="primary" icon="add" @click="router.get('user-v2/add')" label="Add User"
                class="desktop-only" />
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
            <q-tr :props="props" :class="(!props.row.active) ? 'bg-red-1' : ''">
              <q-td key="name" :props="props">
                {{ props.row.name }}
              </q-td>
              <q-td key="email" :props="props">
                {{ props.row.email }}
              </q-td>
              <q-td key="admin" :props="props" align="center">
                <span v-if="props.row.admin">
                  Yes
                </span>
                <span v-else>
                  No
                </span>
              </q-td>
              <q-td key="action" class="q-gutter-x-sm" :props="props" align="center">
                <q-btn :disable="props.row.id == currentUser.id || props.row.email == 'admin@example.com'" rounded dense
                  color="grey" icon="edit" @click="router.get('user-v2/edit/' + props.row.id)">
                  <q-tooltip>Edit User</q-tooltip>
                </q-btn>
                <q-btn :disable="props.row.id == currentUser.id || props.row.email == 'admin@example.com'" rounded dense
                  color="red" icon="delete" @click="deleteUser(props.row)">
                  <q-tooltip>Delete User</q-tooltip>
                </q-btn>
              </q-td>
            </q-tr>
          </template>
        </q-table>
      </div>
    </q-page>
  </authenticated-layout>
</template>
