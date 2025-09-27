<template>
  <div class="row register-container">
    <div class="register-box">
      <form @submit.prevent="register"> 
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="first_name" class="form-label">Tên</label>
 
            <input type="text" class="form-control" id="first_name" placeholder="Nhập tên của bạn" required
              v-model="form.first_name">
          </div>
          <div class="col-md-6">
            <label for="last_name" class="form-label">Họ</label>

            <input type="text" class="form-control" id="last_name" placeholder="Nhập họ của bạn" required
              v-model="form.last_name">
          </div>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>

          <input type="email" class="form-control" id="email" placeholder="Nhập địa chỉ email" required
            v-model="form.email">
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Mật khẩu</label>
          <div class="input-group">
            <input :type="showPassword ? 'text' : 'password'" class="form-control" id="password"
              placeholder="Nhập mật khẩu của bạn" required v-model="form.password">
            <span class="input-group-text password-toggle-btn" @click="togglePasswordVisibility">
              <fa :icon="showPassword ? 'eye-slash' : 'eye'" />
            </span>
          </div>
        </div>
        <div class="mb-4">
          <label for="confirmPassword" class="form-label">Xác nhận mật khẩu</label>
          <div class="input-group">
            <input :type="showConfirmPassword ? 'text' : 'password'" class="form-control" id="confirmPassword"
              placeholder="Xác nhận lại mật khẩu" required v-model="form.password_confirmation">
            <span class="input-group-text password-toggle-btn" id="toggleConfirmPassword"
              @click="toggleConfirmPasswordVisibility">
              <fa :icon="showConfirmPassword ? 'eye-slash' : 'eye'" />
            </span>
          </div>
        </div>
        <div class="d-grid mb-3">
          <button type="submit" class="btn btn-primary">Đăng Ký</button>
        </div>
        <div class="text-muted-separator">
          Hoặc
        </div>
        <div class="d-grid">
          <!-- Google Register Button -->
          <login-with-google />
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Form from 'vform'
import LoginWithGoogle from '~/components/LoginWithGoogle.vue'

export default {
  components: {
    LoginWithGoogle
  },

  middleware: 'guest',

  layout: 'auth',

  metaInfo () {
    return { title: this.$t('register') }
  },

  data: () => ({
    form: new Form({
      first_name: '', 
      last_name: '', 
      email: '',
      password: '',
      password_confirmation: ''
    }),

    mustVerifyEmail: false,
    showPassword: false, 
    showConfirmPassword: false 
  }),

  methods: {
    async register () {
      try {
        console.log(this.form);
        const { data } = await this.form.post('/api/register')

        if (data.status) {
          this.mustVerifyEmail = true
        } else {
          const { data: loginData } = await this.form.post('/api/login')
          this.$store.dispatch('auth/saveToken', { token: loginData.token })
          await this.$store.dispatch('auth/updateUser', { user: loginData.user })
          this.$router.push({ name: 'home' })
        }
      } catch (error) {
        console.error('Lỗi đăng ký:', error);
      }
    },

    togglePasswordVisibility () {
      this.showPassword = !this.showPassword;
    },

    toggleConfirmPasswordVisibility () {
      this.showConfirmPassword = !this.showConfirmPassword;
    }
  }
}
</script>
<style scoped>
.register-container {
  display: flex;
  justify-content: center;
}

.register-box {
  max-width: 650px;
  width: 90%;
  padding: 40px;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  background-color: #ffffff;
}

.register-box h2 {
  color: #333;
  margin-bottom: 30px;
  font-weight: 600;
}

.form-label {
  font-weight: 500;
  color: #555;
  margin-bottom: 8px;
}

.form-control {
  border-radius: 8px;
  padding: 12px 15px;
  border: 1px solid #ddd;
}

.form-control:focus {
  border-color: #86b7fe;
  box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.input-group-text.password-toggle-btn {
  background-color: #f8f9fa;
  border: 1px solid #ddd;
  border-left: none;
  border-radius: 0 8px 8px 0;
  cursor: pointer;
  padding: 12px 15px;
  display: flex;
  align-items: center;
  color: #6c757d;
  transition: color 0.2s ease-in-out;
}

.input-group-text.password-toggle-btn:hover {
  color: #343a40;
}

.btn-primary {
  background-color: #007bff;
  border-color: #007bff;
  border-radius: 8px;
  padding: 12px 0;
  font-size: 1.1rem;
  font-weight: 600;
  transition: background-color 0.2s ease-in-out, border-color 0.2s ease-in-out;
}

.btn-primary:hover {
  background-color: #0056b3;
  border-color: #0056b3;
}

.text-muted-separator {
  display: flex;
  align-items: center;
  text-align: center;
  color: #999;
  margin: 25px 0;
}

.text-muted-separator::before,
.text-muted-separator::after {
  content: '';
  flex: 1;
  border-bottom: 1px solid #eee;
}

.text-muted-separator:not(:empty)::before {
  margin-right: .5em;
}

.text-muted-separator:not(:empty)::after {
  margin-left: .5em;
}
</style>
