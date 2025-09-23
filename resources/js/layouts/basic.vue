<template>
  <div class="dimaso-kit-79">
    <div id="page-wrapper" class="page-wrapper">
    <child />
    </div>
  </div>
</template>

<script>
export default {
  name: 'BasicLayout',
  data() {
    return {
      isMenuOpen: false,
    };
  },
  methods: {
    toggleMobileMenu() {
      this.isMenuOpen = !this.isMenuOpen;
    },
  },
};
</script>
