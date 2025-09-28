<template>
  <header id="global-nav" aria-label="Điều hướng toàn cầu" class="global-nav global-alert-offset-top">
    <div class="global-nav__content container px-4 d-flex align-items-center">
      <router-link class="brand d-flex align-items-center text-decoration-none me-3" :to="{ name: user ? 'home' : 'welcome' }">
        <img src="/assets/images/icon-dimaso.png" width="28" height="28" alt="Logo" class="me-2">
        <span class="brand-name d-none d-sm-inline fw-semibold text-dark">{{ appName }}</span>
      </router-link>

      <div id="global-nav-search" class="global-nav__search flex-grow-1 me-3 d-none d-md-block">
        <div class="position-relative">
          <input class="form-control ps-5 search-main-header" type="text" v-model="q" placeholder="Tìm kiếm" @keyup.enter="onSearch">
          <fa icon="search" class="search-icon position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" />
        </div>
      </div>

      <ul class="global-nav__primary-items nav align-items-center ms-auto">
        <li class="nav-item">
          <router-link class="nav-link text-dark" :to="{ name: 'home' }">
            <fa icon="home" class="icon-lg" />
            <span class="d-none d-lg-block small">Trang chủ</span>
          </router-link>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="#">
            <fa icon="users" class="icon-lg" />
            <span class="d-none d-lg-block small">Mạng lưới</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="#">
            <fa icon="briefcase" class="icon-lg" />
            <span class="d-none d-lg-block small">Việc làm</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="#">
            <fa icon="envelope" class="icon-lg" />
            <span class="d-none d-lg-block small">Nhắn tin</span>
          </a>
        </li>
        <li class="nav-item position-relative">
          <a class="nav-link text-dark" href="#">
            <fa icon="bell" class="icon-lg" />
            <span class="badge rounded-pill bg-danger position-absolute top-10 bage-mess  translate-middle">1</span>
            <span class="d-none d-lg-block small">Thông báo</span>
          </a>
        </li>
        <li class="nav-item dropdown">
          <a href="#" class="nav-link py-0 pe-0 d-flex align-items-center dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <img :src="user && user.photo_url ? user.photo_url : defaultAvatar" class="rounded-circle" width="28" height="28" alt="Avatar">
            <span class="d-none d-lg-inline small ms-1">Tôi</span>
          </a>
          <div class="dropdown-menu dropdown-menu-end pt-0">
            <h6 class="dropdown-header bg-body-secondary text-body-secondary fw-semibold mb-2 rounded-top">Account</h6>
            <router-link class="dropdown-item" :to="{ name: 'settings.profile' }">
              <fa icon="cog" class="me-2"/> Profile
            </router-link>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#" @click.prevent="logout">
              <fa icon="sign-out-alt" class="me-2"/> Logout
            </a>
          </div>
        </li>
      </ul>
    </div>
  </header>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'MainHeader',
  computed: {
    ...mapGetters({ user: 'auth/user' })
  },
  data: () => ({
    q: '',
    appName: window.config.appName || 'App',
    defaultAvatar: '/assets/images/icon-dimaso.png'
  }),
  methods: {
    onSearch () {
      // Placeholder: keep query in URL if you want
      if (this.q) {
        this.$router.push({ name: 'home', query: { q: this.q } })
      }
    },
    async logout () {
      try { await this.$store.dispatch('auth/logout') } catch (e) {}
      this.$router.push({ name: 'login' })
    }
  }
}
</script>

