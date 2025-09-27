<template>
  <div class="coreui-layout">
    <aside class="coreui-sidebar">
      <div class="coreui-brand">
        <router-link :to="{ name: 'coreui.dashboard' }" class="brand-link">
          {{ appName }}
        </router-link>
      </div>
      <nav class="coreui-nav">
        <ul class="nav flex-column">
          <li class="nav-item">
            <router-link :to="{ name: 'coreui.dashboard' }" class="nav-link" active-class="active">
              <fa icon="user" fixed-width />
              <span>Dashboard</span>
            </router-link>
          </li>
          <!-- Add more items here later -->
        </ul>
      </nav>
    </aside>

    <div class="coreui-main">
      <header class="coreui-header">
        <button class="btn btn-sm btn-outline-secondary d-lg-none" @click="toggleSidebar">
          ☰
        </button>
        <div class="ms-auto d-flex align-items-center gap-3">
          <span v-if="user" class="d-flex align-items-center">
            <img :src="user.photo_url" class="rounded-circle me-2" style="width:28px;height:28px;" />
            {{ user.name }}
          </span>
        </div>
      </header>

      <main class="coreui-content container-fluid py-3">
        <child />
      </main>
    </div>
  </div>
  
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'AdminLayout',
  computed: mapGetters({
    user: 'auth/user'
  }),
  data: () => ({
    appName: window.config.appName
  }),
  methods: {
    toggleSidebar () {
      document.body.classList.toggle('coreui-sidebar-show')
    }
  }
}
</script>
