<template>
  <div class="main-layout d-flex flex-column min-vh-100">
    <main-header />
    <div class="container-lg mt-4 flex-grow-1">
      <child />
    </div>
  </div>
  </template>

<script>
import MainHeader from '~/components/MainHeader'

export default {
  name: 'MainLayout',
  components: { MainHeader }
}
</script>
