<template>
  <div class="coreui-wrapper">
    <router-view />
  </div>
</template>

<script>
export default {
  name: 'CoreUIWrapper',
  layout: 'admin',
  middleware: 'auth'
}
</script>
