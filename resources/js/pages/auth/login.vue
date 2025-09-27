<template>
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">
      <div class="card shadow-sm border-0 coreui-auth-card">
        <div class="card-body p-4 p-md-5">
          <h1 class="h4 mb-1">{{ $t('login') }}</h1>
          <p class="text-muted mb-4">Sign in to your account</p>

          <form @submit.prevent="login" @keydown="form.onKeydown($event)">
            <!-- Email -->
            <div class="mb-3">
              <label class="form-label">{{ $t('email') }}</label>
              <input v-model="form.login" :class="{ 'is-invalid': form.errors.has('login') }" class="form-control" type="text" name="login" autocomplete="username">
              <has-error :form="form" field="login" />
            </div>

            <!-- Password -->
            <div class="mb-3">
              <div class="d-flex justify-content-between align-items-center">
                <label class="form-label mb-0">{{ $t('password') }}</label>
                <button type="button" class="btn btn-link btn-sm text-decoration-none" @click="togglePassword">
                  <fa :icon="showPassword ? 'eye-slash' : 'eye'" fixed-width />
                  <span class="ms-1">{{ showPassword ? 'Hide' : 'Show' }}</span>
                </button>
              </div>
              <div class="input-group">
                <input :type="showPassword ? 'text' : 'password'" v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }" class="form-control" name="password" autocomplete="current-password">
              </div>
              <has-error :form="form" field="password" />
            </div>

            <!-- Remember / Forgot -->
            <div class="mb-3 d-flex align-items-center">
              <checkbox v-model="remember" name="remember">
                {{ $t('remember_me') }}
              </checkbox>
              <router-link :to="{ name: 'password.request' }" class="small ms-auto">
                {{ $t('forgot_password') }}
              </router-link>
            </div>

            <!-- Actions -->
            <div class="d-flex align-items-center gap-2">
              <v-button :loading="form.busy" class="btn btn-primary px-4">
                {{ $t('login') }}
              </v-button>
              <login-with-github />
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Form from 'vform'
import Cookies from 'js-cookie'
import LoginWithGithub from '~/components/LoginWithGithub'

export default {
  components: {
    LoginWithGithub
  },

  middleware: 'guest',

  layout: 'coreui-auth',

  metaInfo () {
    return { title: this.$t('login') }
  },

  data: () => ({
    form: new Form({
      login: '',
      password: ''
    }),
    remember: false,
    showPassword: false
  }),

  methods: {
    togglePassword () {
      this.showPassword = !this.showPassword
    },
    async login () {
      // Submit the form with { login, password }
      const { data } = await this.form.post('/api/login')

      // API response shape: { success: true, data: { access_token, token_type, expires_in, user } }
      const payload = data && data.data ? data.data : {}

      // Save the token.
      this.$store.dispatch('auth/saveToken', {
        token: payload.access_token,
        remember: this.remember
      })

      // Save user directly from response (no /api/user endpoint needed)
      if (payload.user) {
        this.$store.dispatch('auth/updateUser', { user: payload.user })
      }

      // Redirect home.
      this.redirect()
    },

    redirect () {
      const intendedUrl = Cookies.get('intended_url')

      if (intendedUrl) {
        Cookies.remove('intended_url')
        this.$router.push({ path: intendedUrl })
      } else {
        this.$router.push({ name: 'home' })
      }
    }
  }
}
</script>
