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
      <core-header :user="user"
                   :breadcrumbs="breadcrumbs"
                   @toggle="toggleSidebar"
                   @logout="logout" />

      <main class="coreui-content container-fluid py-3">
        <child />
      </main>
    </div>
  </div>
  
</template>

<script>
import { mapGetters } from 'vuex'
import CoreHeader from '~/components/CoreHeader'

export default {
  name: 'AdminLayout',
  components: { CoreHeader },
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
