<!DOCTYPE html>
<html lang="zxx">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap Min CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Owl Theme Default Min CSS -->
    <link rel="stylesheet" href="assets/css/owl.theme.default.min.css">
    <!-- Owl Carousel Min CSS -->
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <!-- Animate Min CSS -->
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <!-- Boxicons Min CSS -->
    <link rel="stylesheet" href="assets/css/boxicons.min.css">
    <!-- Magnific Popup Min CSS -->
    <link rel="stylesheet" href="assets/css/magnific-popup.min.css">
    <!-- Flaticon CSS -->
    <link rel="stylesheet" href="assets/css/flaticon.css">
    <!-- Meanmenu Min CSS -->
    <link rel="stylesheet" href="assets/css/meanmenu.min.css">

    <!-- Odometer Min CSS-->
    <link rel="stylesheet" href="assets/css/odometer.min.css">
    <!-- Style CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Dark CSS -->
    <link rel="stylesheet" href="assets/css/dark.css">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="assets/css/responsive.css">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/img/logo.png">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Swiper -->
<link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet">

    <!-- Title -->
    <title>IMHRC</title>
   <style>


/* HERO */
/* Related Services Boxes */
.related-box-unique{
  display: block !important;
  background: #fff !important;
  border-radius: 18px !important;
  padding: 22px 20px !important;
  text-decoration: none !important;
  color: #000 !important;
  box-shadow: 0 10px 25px rgba(0,0,0,.08) !important;
  transition: transform 0.3s !important, box-shadow 0.3s !important;
}
.related-box-unique:hover{
  transform: translateY(-6px) !important;
  box-shadow: 0 15px 35px rgba(0,0,0,.15) !important;
}
.icon-circle-unique{
  width: 52px !important;
  height: 52px !important;
  border-radius: 50% !important;
  background: #0d6efd !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  margin-bottom: 12px !important;
  color: #fff !important;
  font-size: 22px !important;
}

/* Other Services Boxes */
.card-box-unique{
  background: #fff !important;
  border-radius: 18px !important;
  padding: 25px 20px !important;
  color: #000 !important;
  box-shadow: 0 10px 25px rgba(0,0,0,.08) !important;
  transition: transform 0.3s !important, box-shadow 0.3s !important;
  text-align: center !important;
}
.card-box-unique:hover{
  transform: translateY(-6px) !important;
  box-shadow: 0 15px 35px rgba(0,0,0,.15) !important;
}

/* Experts Boxes */
.card-box-expert{
  background: #fff !important;
  border-radius: 18px !important;
  padding: 20px !important;
  box-shadow: 0 10px 25px rgba(0,0,0,.08) !important;
  transition: transform 0.3s !important, box-shadow 0.3s !important;
  text-align: center !important;
}
.card-box-expert:hover{
  transform: translateY(-6px) !important;
  box-shadow: 0 15px 35px rgba(0,0,0,.15) !important;
}
.doc-img-wrapper{
  border: 3px solid #0d6efd !important;
  width: 120px !important;
  height: 120px !important;
  border-radius: 50% !important;
  margin: 0 auto 15px auto !important;
  overflow: hidden !important;
}
.doc-img-wrapper img{
  width: 100% !important;
  height: 100% !important;
  object-fit: cover !important;
}

/* Swiper navigation arrows */
.swiper-button-next, .swiper-button-prev{
  width: 40px !important;
  height: 40px !important;
  background-color: rgba(13, 110, 253, 0.8) !important;
  border-radius: 50% !important;
  color: #fff !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  top: 50% !important;
  transform: translateY(-50%) !important;
  opacity: 0 !important;
  transition: all 0.3s !important;
  z-index: 10 !important;
}
.swiper:hover .swiper-button-next,
.swiper:hover .swiper-button-prev{
  opacity: 1 !important;
}
.swiper-button-next:hover, .swiper-button-prev:hover{
  background-color: rgba(13, 110, 253, 1) !important;
}
.swiper-slide {
    flex-shrink: 0;
    width: 100%;
    height: 100%;
    position: relative;
    transition-property: transform;
    display: block;
    padding: 22px 9px;
}

/* COMMON CARD */
.card-box{
  background:#fff;border-radius:18px;padding:25px;height:100%;
  box-shadow:0 10px 25px rgba(0,0,0,.08)
}
.card-box img{border-radius:14px}

/* LIST */
.tick li{
  list-style:none;padding-left:26px;position:relative;margin-bottom:10px
}
.tick li::before{
  content:"✔";position:absolute;left:0;color:#0d6efd;font-weight:700
}

/* DOCTOR */
.doc-img{
  width:110px;height:110px;object-fit:cover;border-radius:50%
}

/* CTA */
.cta{
  background:linear-gradient(135deg,#0d6efd,#084298);
  color:#fff;border-radius:28px
}

.related-box{
  display:block;
  background:#fff;
  border-radius:18px;
  padding:22px 20px;
  height:100%;
  text-decoration:none;
  color:#000;
  box-shadow:0 10px 25px rgba(0,0,0,.08);
  transition:.3s ease;
}

.related-box:hover{
  transform:translateY(-6px);
  box-shadow:0 15px 35px rgba(13,110,253,.25);
}

.related-box h6{
  margin:0;
  font-weight:700;
  font-size:16px;
}

.icon-circle{
  width:52px;
  height:52px;
  border-radius:50%;
  background:#0d6efd;
  display:flex;
  align-items:center;
  justify-content:center;
  margin-bottom:12px;
}

.icon-circle i{
  color:#fff;
  font-size:22px;
}
<!-- Add this CSS -->
<style>
/* Custom Swiper Arrows */
.swiper-button-next, .swiper-button-prev {
  width: 40px;
  height: 40px;
  background-color: rgba(13, 110, 253, 0.8); /* background color */
  border-radius: 50%;
  top: 50%;
  transform: translateY(-50%);
  color: #fff;
  font-size: 18px;
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0; /* hidden by default */
  transition: all 0.3s ease;
  z-index: 10;
}

/* Show arrows on hover over the slider */
.swiper:hover .swiper-button-next,
.swiper:hover .swiper-button-prev {
  opacity: 1;
}

/* Adjust positions */
.swiper-button-next { right: 10px; }
.swiper-button-prev { left: 10px; }

/* Smaller arrow icons */
.swiper-button-next::after,
.swiper-button-prev::after {
  font-size: 16px;
  font-weight: bold;
}

/* Optional: Add hover effect */
.swiper-button-next:hover,
.swiper-button-prev:hover {
  background-color: rgba(13, 110, 253, 1);
}
</style>

</style>

</head>

<body>


   <?php include 'includes/header.php'; ?>
   	<!-- Start Page Title Area -->
<div class="page-title-wave">
  <div class="container">
    <h2> Counselling Psychology</h2>
  </div>

  <svg class="wave" viewBox="0 0 1440 320" preserveAspectRatio="none">
    <path fill="#ffffff" fill-opacity="1" 
      d="M0,64L48,90.7C96,117,192,171,288,170.7C384,171,480,117,576,85.3C672,53,768,43,864,74.7C960,107,1056,181,1152,192C1248,203,1344,149,1392,122.7L1440,96L1440,320L1392,320C1344,320,1248,320,
      1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
    </path>
  </svg>
</div>



<!-- SERVICE DETAILS -->
<section class="py-5">
<div class="container">
<div class="row g-5 align-items-center">

<div class="col-lg-6">
  <h2 class="fw-bold mb-3">About Counselling Psychology</h2>
  <p class="text-muted fs-5">
    Counselling Psychology focuses on helping individuals cope with emotional,
    behavioural and psychological challenges through structured therapeutic
    interventions and supportive counselling.
  </p>
  <ul class="tick mt-4">
    <li>Emotional wellbeing & stress management</li>
    <li>Anxiety, depression & mood disorder counselling</li>
    <li>Child, adolescent & family counselling</li>
    <li>Career guidance & life skills development</li>
  </ul>
</div>

<div class="col-lg-6">
  <img src="assets/img/bbrf-psycho.webp"
       class="img-fluid rounded-4 shadow">
</div>

</div>
</div>
</section>



<!-- RELATED SERVICES -->
<section class="py-5 bg-light">
  <div class="container">
    <h2 class="fw-bold text-start mb-4">Related Psychological Services</h2>

    <div class="swiper related-services">
      <div class="swiper-wrapper">

        <div class="swiper-slide">
          <a href="individual-counselling.php" class="related-box-unique">
            <div class="icon-circle-unique bg-primary">
              <i class="bi bi-person"></i>
            </div>
            <h6>Individual Counselling</h6>
          </a>
        </div>

        <div class="swiper-slide">
          <a href="child-counselling.php" class="related-box-unique">
            <div class="icon-circle-unique bg-success">
              <i class="bi bi-emoji-smile"></i>
            </div>
            <h6>Child & Adolescent Counselling</h6>
          </a>
        </div>

        <div class="swiper-slide">
          <a href="family-therapy.php" class="related-box-unique">
            <div class="icon-circle-unique bg-warning">
              <i class="bi bi-people"></i>
            </div>
            <h6>Family Therapy</h6>
          </a>
        </div>

        <div class="swiper-slide">
          <a href="behaviour-therapy.php" class="related-box-unique">
            <div class="icon-circle-unique bg-info">
              <i class="bi bi-activity"></i>
            </div>
            <h6>Behaviour Therapy</h6>
          </a>
        </div>

        <div class="swiper-slide">
          <a href="psychotherapy.php" class="related-box-unique">
            <div class="icon-circle-unique bg-danger">
              <i class="bi bi-chat-dots"></i>
            </div>
            <h6>Psychotherapy</h6>
          </a>
        </div>

      </div>

      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
    </div>
  </div>
</section>


<!-- OTHER SERVICES -->
<section class="py-5">
  <div class="container">
     <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold mb-0">Other Services</h2>
      <a href="clinical-&-diagnostic-services.php" class="btn btn-outline-primary btn-sm">
        All Services
      </a>
    </div>

    <div class="swiper other-services">
      <div class="swiper-wrapper">

        <div class="swiper-slide">
          <div class="card-box-unique text-center">
            <i class="bi bi-brain fs-1 text-primary"></i>
            <h6 class="mt-2">Clinical Psychology</h6>
          </div>
        </div>

        <div class="swiper-slide">
          <div class="card-box-unique text-center">
            <i class="bi bi-heart fs-1 text-danger"></i>
            <h6 class="mt-2">Mental Health Services</h6>
          </div>
        </div>

        <div class="swiper-slide">
          <div class="card-box-unique text-center">
            <i class="bi bi-person-check fs-1 text-success"></i>
            <h6 class="mt-2">Wellness Programs</h6>
          </div>
        </div>

        <div class="swiper-slide">
          <div class="card-box-unique text-center">
            <i class="bi bi-chat-left-dots fs-1 text-warning"></i>
            <h6 class="mt-2">Support Groups</h6>
          </div>
        </div>

        <div class="swiper-slide">
          <div class="card-box-unique text-center">
            <i class="bi bi-people fs-1 text-info"></i>
            <h6 class="mt-2">Community Outreach</h6>
          </div>
        </div>

      </div>

      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
    </div>
  </div>
</section>


<!-- RELATED EXPERTS -->
<section class="py-5 bg-light">
  <div class="container">
   <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold mb-0">Related Experts</h2>
      <a href="expert.php" class="btn btn-outline-primary btn-sm">All Experts</a>
    </div>

    <div class="swiper experts">
      <div class="swiper-wrapper">

        <div class="swiper-slide">
          <div class="card-box-expert text-center">
            <div class="doc-img-wrapper">
              <img src="https://images.unsplash.com/photo-1550831107-1553da8c8464" class="doc-img mb-3">
            </div>
            <h6>Ms. P. Sharma</h6>
            <small class="text-muted">Counselling Psychologist</small>
          </div>
        </div>

        <div class="swiper-slide">
          <div class="card-box-expert text-center">
            <div class="doc-img-wrapper">
              <img src="https://images.unsplash.com/photo-1607746882042-944635dfe10e" class="doc-img mb-3">
            </div>
            <h6>Mr. R. Verma</h6>
            <small class="text-muted">Psychotherapist</small>
          </div>
        </div>

        <div class="swiper-slide">
          <div class="card-box-expert text-center">
            <div class="doc-img-wrapper">
              <img src="https://images.unsplash.com/photo-1594824476967-48c8b964273f" class="doc-img mb-3">
            </div>
            <h6>Dr. A. Mehta</h6>
            <small class="text-muted">Clinical Psychologist</small>
          </div>
        </div>

      </div>

      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
    </div>
  </div>
</section>





<!-- UNIQUE STYLES -->
<style>
/* Related Services Unique */
.related-box-unique{
  display:block;
  background: linear-gradient(135deg, #ff6b6b, #ff8787);
  border-radius:20px;
  padding:25px 20px;
  text-decoration:none;
  color:#fff;
  box-shadow:0 10px 25px rgba(0,0,0,.15);
  transition:.3s ease;
}
.related-box-unique:hover{
  transform:translateY(-8px);
  box-shadow:0 18px 40px rgba(255,107,107,.35);
}
.icon-circle-unique{
  width:60px;height:60px;border-radius:50%;
  display:flex;align-items:center;justify-content:center;margin-bottom:15px;
  font-size:24px;
}

/* Other Services Unique */
.card-box-unique{
  background: linear-gradient(135deg, #845ef7, #5c7cfa);
  border-radius:25px;
  padding:30px 20px;
  transition:.3s ease;
  color:#fff;
}
.card-box-unique:hover{
  transform:scale(1.08);
  box-shadow:0 15px 35px rgba(132,94,247,.3);
}

/* Experts Unique */
.card-box-expert{
  background:#fff;
  border-radius:20px;
  padding:20px;
  transition:.3s ease;
  box-shadow:0 10px 25px rgba(0,0,0,.08);
}
.card-box-expert:hover{
  transform:translateY(-6px);
  box-shadow:0 15px 35px rgba(0,0,0,.18);
}
.doc-img-wrapper{
  border:3px solid #0d6efd;
  width:120px;height:120px;
  border-radius:50%;
  margin:0 auto 15px auto;
  overflow:hidden;
}
.doc-img-wrapper img{
  width:100%;height:100%;object-fit:cover;
}

/* Swiper navigation arrows color */
.swiper-button-next, .swiper-button-prev {
  color: #0d6efd;
}
</style>

<!-- UNIQUE SWIPER JS -->


<!-- CTA -->
<section class="py-5">
<div class="container text-center">
<div class="cta p-5">
<h2 class="fw-bold mb-3">Consult Our Adult CTVS Experts</h2>
<p class="fs-5 mb-4">Safe, advanced and trusted cardiac surgical care.</p>
<a href="book-appointment.php"
   class="btn btn-light btn-lg rounded-pill px-5">
  Book Appointment
</a>
</div>
</div>
</section>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>




  <?php include 'includes/footer.php'; ?>

    <!-- Jquery Min JS -->
    <script data-cfasync="false" src="../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="assets/js/jquery.min.js"></script>
    <!-- Bootstrap Bundle Min JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <!-- Meanmenu Min JS -->
    <script src="assets/js/meanmenu.min.js"></script>
    <!-- Wow Min JS -->
    <script src="assets/js/wow.min.js"></script>
    <!-- Owl Carousel Min JS -->
    <script src="assets/js/owl.carousel.min.js"></script>
    <!-- Magnific Popup Min JS -->
    <script src="assets/js/magnific-popup.min.js"></script>
    <!-- Jarallax Min JS -->
    <script src="assets/js/jarallax.min.js"></script>
    <!-- Appear Min JS -->
    <script src="assets/js/appear.min.js"></script>
    <!-- Odometer Min JS -->
    <script src="assets/js/odometer.min.js"></script>
    <!-- Smoothscroll Min JS -->
    <script src="assets/js/smoothscroll.min.js"></script>
    <!-- Form Validator Min JS -->
    <script src="assets/js/form-validator.min.js"></script>
    <!-- Contact JS -->
    <script src="assets/js/contact-form-script.js"></script>
    <!-- Ajaxchimp Min JS -->
    <script src="assets/js/ajaxchimp.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/custom.js"></script>
<script>
    new Swiper(".related-services", {
  slidesPerView: 4, // 4 boxes visible
  spaceBetween: 20,
  loop: true,
  navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
  autoplay: { delay: 2500, disableOnInteraction: false },
  breakpoints: {
    0: { slidesPerView: 1 },
    576: { slidesPerView: 2 },
    768: { slidesPerView: 3 },
    992: { slidesPerView: 4 }
  }
});

new Swiper(".other-services", {
  slidesPerView: 4,
  spaceBetween: 20,
  loop: true,
  navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
  autoplay: { delay: 2000, disableOnInteraction: false },
  breakpoints: {
    0: { slidesPerView: 1 },
    576: { slidesPerView: 2 },
    768: { slidesPerView: 3 },
    992: { slidesPerView: 4 }
  }
});

new Swiper(".experts", {
  slidesPerView: 4,
  spaceBetween: 20,
  loop: true,
  navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
  autoplay: { delay: 3000, disableOnInteraction: false },
  breakpoints: {
    0: { slidesPerView: 1 },
    576: { slidesPerView: 2 },
    768: { slidesPerView: 3 },
    992: { slidesPerView: 4 }
  }
});

</script>
</body>


</html>