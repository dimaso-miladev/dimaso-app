<template>
  <div class="welcome-page">
    <section class="hero-section text-white text-center py-5 d-flex align-items-center justify-content-center">
      <div class="container animated-hero">
        <h1 class="display-3 mb-4 animate__animated animate__fadeInDown"><i class="fas fa-bullhorn mr-3"></i>Chào mừng đến với Cộng đồng MMO & Digital Marketing!</h1>
        <p class="lead mb-4 animate__animated animate__fadeInUp animate__delay-1s">Nơi chia sẻ kiến thức, kinh nghiệm và kết nối những người đam mê.</p>
        <p class="mb-5 animate__animated animate__fadeInUp animate__delay-2s">Khám phá các bài viết hữu ích, đặt câu hỏi, và tương tác với cộng đồng chuyên gia hàng đầu.</p>
        <router-link :to="{ name: authenticated ? 'home' : 'login' }" class="btn btn-light btn-lg custom-btn-hero animate__animated animate__zoomIn animate__delay-3s">
          <i class="fas fa-plus-circle mr-2"></i>Bắt đầu ngay!
        </router-link>
      </div>
    </section>

    <section class="features-section container my-5 py-4">
      <h2 class="text-center mb-5 animate__animated animate__fadeIn">Tại sao nên tham gia?</h2>
      <div class="row text-center">
        <div class="col-md-4 mb-4">
          <div class="card h-100 shadow-lg border-0 feature-card animate__animated animate__fadeInUp animate__delay-1s">
            <div class="card-body d-flex flex-column justify-content-center align-items-center">
              <i class="fas fa-lightbulb fa-4x text-info mb-3 feature-icon"></i>
              <h5 class="card-title">Học hỏi kiến thức</h5>
              <p class="card-text">Tiếp cận kho tàng kiến thức sâu rộng về MMO, SEO, quảng cáo, content và nhiều hơn nữa, được cập nhật liên tục.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card h-100 shadow-lg border-0 feature-card animate__animated animate__fadeInUp animate__delay-2s">
            <div class="card-body d-flex flex-column justify-content-center align-items-center">
              <i class="fas fa-comments fa-4x text-success mb-3 feature-icon"></i>
              <h5 class="card-title">Chia sẻ & Thảo luận</h5>
              <p class="card-text">Tạo bài viết, bình luận, và tham gia vào các cuộc thảo luận sôi nổi cùng cộng đồng, nâng cao kỹ năng của bạn.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card h-100 shadow-lg border-0 feature-card animate__animated animate__fadeInUp animate__delay-3s">
            <div class="card-body d-flex flex-column justify-content-center align-items-center">
              <i class="fas fa-users fa-4x text-warning mb-3 feature-icon"></i>
              <h5 class="card-title">Kết nối chuyên gia</h5>
              <p class="card-text">Mở rộng mối quan hệ với các chuyên gia hàng đầu và những người có cùng đam mê, tìm kiếm cơ hội hợp tác.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="cta-section py-5 text-white text-center">
      <div class="container">
        <h2 class="mb-4 animate__animated animate__fadeInDown">Bạn đã sẵn sàng tham gia cộng đồng?</h2>
        <p class="lead mb-5 animate__animated animate__fadeInUp">Đừng bỏ lỡ cơ hội học hỏi, chia sẻ và phát triển không ngừng cùng chúng tôi!</p>
        <router-link :to="{ name: 'register' }" class="btn btn-light btn-lg custom-btn-cta mr-3 animate__animated animate__bounceInLeft">
          <i class="fas fa-user-plus mr-2"></i>Đăng ký ngay
        </router-link>
        <router-link :to="{ name: 'login' }" class="btn btn-outline-light btn-lg custom-btn-cta animate__animated animate__bounceInRight">
          <i class="fas fa-sign-in-alt mr-2"></i>Đăng nhập
        </router-link>
      </div>
    </section>

    <ContactForm />
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import ContactForm from '~/components/ContactForm'

export default {
  layout: 'basic',
  components: {
    ContactForm
  },
  metaInfo () {
    return { title: this.$t('home') }
  },

  data: () => ({
    title: window.config.appName,
    // Original data properties are kept but not actively used in this new layout
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

<style scoped>
/* Basic styling for modern look */
.welcome-page {
  font-family: 'Montserrat', sans-serif; /* Using a modern font, ensure it's imported in your main CSS/HTML */
  background-color: #f8f9fa;
}

/* Hero Section */
.hero-section {
  min-height: 70vh;
  background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%); /* Vibrant gradient */
  position: relative;
  overflow: hidden;
}

.hero-section::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0; bottom: 0;
  background-image: url('data:image/svg+xml;utf8,<svg width="100%" height="100%" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 L 0 10" fill="none" stroke="%23ffffff" stroke-width="0.5" opacity="0.1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grid)" /></svg>');
  opacity: 0.3;
  z-index: 1;
}

.hero-section .container {
  position: relative;
  z-index: 2;
}

.hero-section h1 {
  font-weight: 700;
  font-size: 3.5rem;
  animation-duration: 1.5s;
}

.hero-section p.lead {
  font-weight: 400;
  animation-duration: 1.5s;
}

.custom-btn-hero {
  padding: 15px 35px;
  border-radius: 50px;
  font-weight: 600;
  transition: all 0.3s ease-in-out;
  background-color: #fff; /* White background */
  color: #6a11cb; /* Primary color text */
  border: none;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
  animation-duration: 1.5s;
}

.custom-btn-hero:hover {
  transform: translateY(-3px) scale(1.02);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
  background-color: #f0f0f0;
  color: #4a0f8c;
}

/* Features Section */
.features-section h2 {
  font-weight: 700;
  color: #343a40;
  animation-duration: 1s;
}

.feature-card {
  border-radius: 15px;
  transition: all 0.3s ease-in-out;
  background-color: #ffffff;
  animation-duration: 1s;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  align-items: center;
  padding: 30px;
}

.feature-card:hover {
  transform: translateY(-8px) scale(1.03);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

.feature-icon {
  transition: transform 0.4s ease-in-out, color 0.3s ease-in-out;
  animation-duration: 1.5s;
}

.feature-card:hover .feature-icon {
  transform: rotateY(360deg) scale(1.1);
  color: #2575fc !important; /* Change color on hover, !important to override Bootstrap utilities */
}

.feature-card .card-title {
  font-weight: 600;
  color: #007bff;
  margin-top: 15px;
}

/* Call to Action Section */
.cta-section {
  background: linear-gradient(45deg, #1f4037, #99f2c8); /* Fresh gradient */
  padding: 80px 0;
  position: relative;
  overflow: hidden;
}

.cta-section::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0; bottom: 0;
  background-image: url('data:image/svg+xml;utf8,<svg width="100%" height="100%" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="dots" width="10" height="10" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="1" fill="%23ffffff" opacity="0.2"/></pattern></defs><rect width="100%" height="100%" fill="url(%23dots)" /></svg>');
  opacity: 0.4;
  z-index: 1;
}

.cta-section .container {
  position: relative;
  z-index: 2;
}

.cta-section h2 {
  font-weight: 700;
  font-size: 2.8rem;
  animation-duration: 1.2s;
}

.cta-section p.lead {
  font-weight: 300;
  animation-duration: 1.2s;
}

.custom-btn-cta {
  padding: 14px 30px;
  border-radius: 30px;
  font-weight: 600;
  transition: all 0.3s ease-in-out;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  animation-duration: 1.2s;
}

.custom-btn-cta:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
  background-color: rgba(255, 255, 255, 0.9); /* Slight white tint */
  border-color: rgba(255, 255, 255, 0.9); /* Slight white tint */
  color: #1f4037; /* Darker text on hover */
}

/* Animate.css integration - assuming Animate.css is included in the project */
/* If Animate.css is not included, you would need to define these keyframes manually or import it */
/* For example, for fadeInDown */
@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translate3d(0, -100%, 0);
  }

  to {
    opacity: 1;
    transform: translate3d(0, 0, 0);
  }
}

/* Example of other animations if Animate.css is not present, you'd define them here */
/* @keyframes fadeInUp, @keyframes zoomIn, @keyframes bounceInLeft, @keyframes bounceInRight */

/* You should include Animate.css in your project for full effect. */
/* You can add it via npm: npm install animate.css --save */
/* Then in your main app.js or component: import 'animate.css'; */
</style>