<template>
  <div class="sidebar sidebar-dark sidebar-fixed border-end" :class="{ 'show': isShown }">
    <div class="sidebar-header border-bottom d-flex align-items-center justify-content-between px-3 py-2">
      <router-link class="sidebar-brand text-decoration-none" :to="{ name: brandRoute }">
        <span class="sidebar-brand-full fw-semibold">{{ brand }}</span>
        <span class="sidebar-brand-narrow fw-bold">{{ brandShort }}</span>
      </router-link>
      <button type="button" class="btn btn-close d-lg-none" aria-label="Close" @click="toggle" />
    </div>

    <div class="sidebar-nav">
      <nav class="nav flex-column px-2 py-2">
        <div class="nav-item">
          <router-link class="nav-link" :to="{ name: 'coreui.dashboard' }" active-class="active">
            <fa icon="home" class="me-2" /> Dashboard
          </router-link>
        </div>

        <div class="nav-title mt-3 text-uppercase text-white-50 small px-2">Theme</div>
        <div class="nav-item">
          <a class="nav-link" href="#"><fa icon="palette" class="me-2" /> Colors</a>
        </div>
        <div class="nav-item">
          <a class="nav-link" href="#"><fa icon="font" class="me-2" /> Typography</a>
        </div>

        <div class="nav-title mt-3 text-uppercase text-white-50 small px-2">Components</div>
        <div class="nav-item">
          <a class="nav-link" href="#"><fa icon="cubes" class="me-2" /> Base</a>
        </div>
        <div class="nav-item">
          <a class="nav-link" href="#"><fa icon="table" class="me-2" /> Tables</a>
        </div>
        <div class="nav-item">
          <a class="nav-link" href="#"><fa icon="chart-bar" class="me-2" /> Charts</a>
        </div>
      </nav>
    </div>

    <div class="sidebar-footer border-top d-none d-lg-flex px-2 py-2">
      <button class="sidebar-toggler btn btn-sm btn-outline-secondary" @click="toggle">Toggle</button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CoreSidebar',
  props: {
    brand: { type: String, default: () => window.config?.appName || 'App' },
    brandShort: { type: String, default: 'A' },
    brandRoute: { type: String, default: 'coreui.dashboard' }
  },
  data: () => ({ isShown: false }),
  mounted () {
    // Sync initial state with body class if present
    this.isShown = document.body.classList.contains('coreui-sidebar-show')
  },
  methods: {
    toggle () {
      document.body.classList.toggle('coreui-sidebar-show')
      this.isShown = document.body.classList.contains('coreui-sidebar-show')
    }
  }
}
</script>

