<template>
<h1>App main</h1>  
</template>

<script>
import { mapGetters } from 'vuex'
import ContactForm from '~/components/ContactForm';

export default {
  layout: 'basic',
  components: {
    ContactForm
  },
  metaInfo() {
    return { title: this.$t('home') }
  },

  data: () => ({
    title: window.config.appName,
    heroBanner: '/assets/images/hero_banner.png',
    iconDimaso: '/assets/images/icon-dimaso.png',
    logoUrl: '/assets/images/dimaso_main_logo.webp',
    iconPerson: '/assets/images/icPersonAdvertising.svg',
    iconClick: '/assets/images/icParkSolidClick.svg',
    iconLocation: '/assets/images/icLocationAdvetising.svg',
  }),

  computed: mapGetters({
    authenticated: 'auth/check'
  })
}
</script>