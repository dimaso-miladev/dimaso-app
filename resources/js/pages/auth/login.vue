<template>
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">
      <div class="card shadow-sm border-0 coreui-auth-card">
        <div class="card-body p-4 p-md-5">
          <h1 class="h4 mb-1">{{ $t('login') }}</h1>
          <p class="text-muted mb-4">Sign in to your account</p>

          <form @submit.prevent="login" @keydown="form.onKeydown($event)">
            <div v-if="serverMessage" class="alert alert-danger" role="alert">
              {{ serverMessage }}
            </div>
            <!-- Email -->
            <div class="mb-3">
              <label class="form-label">{{ $t('email') }}</label>
              <input v-model="form.login"  class="form-control" type="text" name="login" autocomplete="username">
            </div>

            <!-- Password -->
            <div class="mb-3">
              <label class="form-label mb-1">{{ $t('password') }}</label>
              <div class="input-group">
                <input :type="showPassword ? 'text' : 'password'"
                       v-model="form.password"
                       :class="{ 'is-invalid': form.errors.has('password') }"
                       class="form-control"
                       name="password"
                       autocomplete="current-password">
                <button type="button" class="input-group-text password-toggle-btn" @click="togglePassword" :aria-label="showPassword ? 'Hide password' : 'Show password'">
                  <fa :icon="showPassword ? 'eye-slash' : 'eye'" />
                </button>
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

  layout: 'default',

  metaInfo () {
    return { title: this.$t('login') }
  },

  data: () => ({
    form: new Form({
      login: '',
      password: ''
    }),
    remember: false,
    showPassword: false,
    serverMessage: ''
  }),

  methods: {
    togglePassword () {
      this.showPassword = !this.showPassword
    },
    async login () {
      this.serverMessage = ''
      // Submit the form with { login, password }
      try {
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
      } catch (e) {
        const res = e && e.response ? e.response : {}
        const body = res.data || {}

        // Map known error shapes to UI
        // 1) Laravel validation: { message, errors: { field: [msg] } }
        if (body.errors) {
          this.serverMessage = body.message || this.$t ? this.$t('error_alert_text') : 'Validation error.'
          this.form.errors.set(body.errors)
        }
        // 2) Our ApiResponse error: { success: false, error: { message, details } }
        else if (body.success === false && body.error) {
          this.serverMessage = body.error.message || 'Login failed.'
          if (body.error.details) {
            this.form.errors.set(body.error.details)
          }
        } else {
          this.serverMessage = 'Login failed.'
        }
      }
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
