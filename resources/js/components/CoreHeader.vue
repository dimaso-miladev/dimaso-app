<template>
  <div class="header header-sticky mb-4 p-0 w-100">
    <div class="container-fluid border-bottom px-4 d-flex align-items-center">
      <button class="header-toggler btn btn-link px-2 text-body" type="button" aria-label="Toggle navigation" @click="onToggle">
        <fa icon="bars" class="icon icon-lg" />
      </button>

      <ul class="header-nav d-none d-md-flex" role="navigation">
        <slot name="left">
          <li class="nav-item">
            <router-link class="nav-link" :to="{ name: 'coreui.dashboard' }">Dashboard</router-link>
          </li>
          <li class="nav-item"><a class="nav-link" href="#">Users</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Settings</a></li>
        </slot>
      </ul>

      <ul class="header-nav ms-auto" role="navigation">
        <slot name="right-icons">
          <li class="nav-item"><a class="nav-link" href="#"><fa icon="bell" class="icon icon-lg" /></a></li>
          <li class="nav-item"><a class="nav-link" href="#"><fa icon="list" class="icon icon-lg" /></a></li>
          <li class="nav-item"><a class="nav-link" href="#"><fa icon="envelope" class="icon icon-lg" /></a></li>
        </slot>
      </ul>

      <ul class="header-nav ms-2" role="navigation">
        <li class="nav-item py-1"><div class="vr h-100 mx-2 text-body text-opacity-75"></div></li>
        <li class="nav-item dropdown">
          <a href="#" class="nav-link py-0 pe-0" data-bs-toggle="dropdown" aria-expanded="false" role="button">
            <div class="avatar avatar-md">
              <img :src="avatarSrc" class="avatar-img" alt="Avatar" />
            </div>
          </a>
          <div class="dropdown-menu dropdown-menu-end pt-0">
            <h6 class="dropdown-header bg-body-secondary text-body-secondary fw-semibold mb-2 rounded-top">Account</h6>
            <router-link class="dropdown-item" :to="{ name: 'settings.profile' }">
              <fa icon="cog" class="me-2"/> Profile
            </router-link>
            <slot name="avatar-menu" />
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#" @click.prevent="onLogout">
              <fa icon="sign-out-alt" class="me-2"/> Logout
            </a>
          </div>
        </li>
      </ul>
    </div>

    <div v-if="showBreadcrumbs" class="container-fluid px-4">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb my-0">
          <li v-for="(bc, i) in breadcrumbs" :key="i" class="breadcrumb-item" :class="{ active: bc.active }" :aria-current="bc.active ? 'page' : null">
            <template v-if="bc.to && !bc.active">
              <router-link :to="bc.to">{{ bc.text }}</router-link>
            </template>
            <template v-else>
              {{ bc.text }}
            </template>
          </li>
        </ol>
      </nav>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CoreHeader',
  props: {
    user: { type: Object, default: null },
    defaultAvatar: { type: String, default: '/assets/images/icon-dimaso.png' },
    breadcrumbs: {
      type: Array,
      default: () => ([
        { text: 'Home', to: { name: 'home' }, active: false },
        { text: 'Dashboard', active: true }
      ])
    },
    showBreadcrumbs: { type: Boolean, default: true }
  },
  computed: {
    avatarSrc () {
      return this.user && this.user.photo_url ? this.user.photo_url : this.defaultAvatar
    }
  },
  methods: {
    onToggle () { this.$emit('toggle') },
    onLogout () { this.$emit('logout') }
  }
}
</script>

