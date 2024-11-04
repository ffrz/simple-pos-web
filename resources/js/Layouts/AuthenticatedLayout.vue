<template>
  <q-layout view="lHh LpR lFf">
    <q-header>
      <q-toolbar>
        <q-btn flat dense round icon="menu" aria-label="Menu" @click="toggleLeftDrawer" />
        <q-toolbar-title class="absolute-center">{{ $config.APP_NAME }}</q-toolbar-title>
      </q-toolbar>
    </q-header>

    <q-drawer :breakpoint="768" v-model="leftDrawerOpen" show-if-above>
      <q-img class="absolute-top" src="https://cdn.quasar.dev/img/material.png" style="height: 150px">
        <div class="absolute-bottom bg-transparent">
          <q-avatar size="56px" class="q-mb-sm">
            <img src="https://cdn.quasar.dev/img/boy-avatar.png">
          </q-avatar>
          <div class="text-weight-bold">{{ page.props.auth.user.name }}</div>
          <div><my-link class="text-white" href="/profile" :label="page.props.auth.user.email" /></div>
        </div>
      </q-img>
      <q-scroll-area style="height: calc(100% - 150px); margin-top: 150px; border-right: 1px solid #ddd">
        <q-list id="main-nav" style="margin-bottom: 50px;">
          <q-item clickable v-ripple :active="$page.url == '/dashboard'" @click="router.get(route('dashboard'))">
            <q-item-section avatar>
              <q-icon name="dashboard" />
            </q-item-section>
            <q-item-section>
              <q-item-label>Dashboard</q-item-label>
            </q-item-section>
          </q-item>
          <q-expansion-item expand-separator icon="mail" label="Inbox">
            <q-expansion-item class="subnav" expand-separator icon="receipt" label="Receipts">
              <q-expansion-item class="subnav" expand-separator label="Today">
                <q-item class="subnav" clickable v-ripple>Example Link 1</q-item>
              </q-expansion-item>
              <q-expansion-item class="subnav" expand-separator label="Yesterday">
                <q-list>
                  <q-item class="subnav" clickable v-ripple>Example Link 1</q-item>
                  <q-item class="subnav" clickable v-ripple>Example Link 2</q-item>
                </q-list>
              </q-expansion-item>
            </q-expansion-item>
            <q-expansion-item expand-separator icon="schedule" label="Postponed">
              <q-card>
                <q-card-section>
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, eius reprehenderit eos corrupti
                  commodi magni quaerat ex numquam, dolorum officiis modi facere maiores architecto suscipit iste
                  eveniet doloribus ullam aliquid.
                </q-card-section>
              </q-card>
            </q-expansion-item>
          </q-expansion-item>
          <q-expansion-item expand-separator icon="people" label="User Manager" :default-opened="$page.url.startsWith('/user')">
            <q-item class="subnav" clickable v-ripple :active="$page.url == '/user'" @click="router.get('/user')">
              <q-item-section avatar>
                <q-icon name="people" />
              </q-item-section>
              <q-item-section>
                <q-item-label>Version 1 (Dialog)</q-item-label>
              </q-item-section>
            </q-item>
            <q-item class="subnav" clickable v-ripple :active="$page.url.startsWith('/user-v2')" @click="router.get('/user-v2')">
              <q-item-section avatar>
                <q-icon name="people" />
              </q-item-section>
              <q-item-section>
                <q-item-label>Version 2 (Page)</q-item-label>
              </q-item-section>
            </q-item>
          </q-expansion-item>
          <q-item clickable v-ripple :active="$page.url == '/about'" @click="router.get('/about')">
            <q-item-section avatar>
              <q-icon name="info" />
            </q-item-section>
            <q-item-section>
              <q-item-label>About</q-item-label>
            </q-item-section>
          </q-item>
          <q-item clickable v-ripple @click="router.post(route('logout'))">
            <q-item-section avatar>
              <q-icon name="logout" />
            </q-item-section>
            <q-item-section>
              <q-item-label>Logout</q-item-label>
            </q-item-section>
          </q-item>
          <div class="absolute-bottom text-grey-6 q-pa-md">&copy; 2024 - {{ $config.APP_NAME + ' v' + $config.APP_VERSION_STR }}</div>
        </q-list>
      </q-scroll-area>
    </q-drawer>

    <q-page-container style="background:#f8f8f8;">
      <slot></slot>
    </q-page-container>

    <!-- Footer hanya tampil jika di tampilan screen kecil, lihat di bagian style di bawah pada file ini -->
    <!-- <q-footer> -->
      <!-- <q-tabs v-model="tab" indicator-color="yellow" class="bg-primary text-white shadow-2">
        <q-tab name="mails" icon="mail" label="Mails" />
        <q-tab name="alarms" icon="alarm" label="Alarms" />
        <q-tab name="movies" icon="movie" label="Movies" />
      </q-tabs> -->
    <!-- </q-footer> -->
  </q-layout>
</template>

<script setup>
import { defineComponent, ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";

// const tab = ref();
const page = usePage();
// let user = page.props.auth.user;

defineComponent({
  name: "AuthenticatedLayout",
});

const leftDrawerOpen = ref(false);

function toggleLeftDrawer() {
  leftDrawerOpen.value = !leftDrawerOpen.value;
}

// watch(
//   () => page.props.auth.user,
//   (newValue, oldValue) => {
//     if (newValue) {
//       user = newValue;
//     }
//   }
// );

</script>

<!-- <style scoped>
@media screen and (min-width: 768px) {
  .q-footer {
    display: none;
  }
}
</style> -->
