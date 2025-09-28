<template>
  <div class="admin-layout d-flex">
    <core-sidebar :brand="appName" :brand-short="appShort" />

    <div class="wrapper d-flex flex-column min-vh-100 flex-grow-1">
      <core-header :user="user" :breadcrumbs="breadcrumbs" @toggle="toggleSidebar" @logout="logout" />

      <div class="body flex-grow-1">
        <div class="container-lg px-4 py-3">
          <child />
        </div>
      </div>

      <div class="footer px-4 d-flex align-items-center justify-content-between">
        <div>
          <a href="https://coreui.io" target="_blank">Dimaso</a>
          <span class="ms-1">© 2025 creativeLabs.</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import CoreHeader from '~/components/CoreHeader'
import CoreSidebar from '~/components/CoreSidebar'

export default {
  name: 'AdminLayout',
  components: { CoreHeader, CoreSidebar },
  computed: {
    ...mapGetters({
      user: 'auth/user'
    }),
    breadcrumbs () {
      return [
        { text: 'Home', to: { name: 'home' }, active: false },
        { text: 'Dashboard', active: true }
      ]
    }
  },
  data: () => ({
    appName: window.config.appName,
    appShort: (window.config.appName || 'A').slice(0, 1),
    defaultAvatar: '/assets/images/icon-dimaso.png'
  }),
  methods: {
    toggleSidebar () {
      document.body.classList.toggle('coreui-sidebar-show')
    },
    async logout () {
      try { await this.$store.dispatch('auth/logout') } catch (e) {}
      this.$router.push({ name: 'login' })
    }
  }
}
</script>
