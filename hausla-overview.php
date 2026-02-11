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
    <!-- Title -->
    <title>IMHRC</title>
    <style>
/* REQUIRED FOR OVERLAY */
.carousel-item {
  position: relative;
}

/* IMAGE */
.slider-img {
  width: 100%;
  /* height: 420px; */
  object-fit: cover;
}

/* OVERLAY */
.slider-overlay {
position: absolute;
    inset: 0;
    background: linear-gradient(to right, rgb(0 0 0 / 75%), rgb(0 0 0 / 75%));
    z-index: 1;
}

/* CENTER CONTENT – SAFE WAY */
.custom-caption {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 2;
  text-align: center;
  width: 100%;
  max-width: 900px;
  padding: 0 20px;
}

.custom-caption h2 {
  font-size: 38px;
  font-weight: 700;
  margin-bottom: 15px;
      color: #ffb800;
}
.btn-danger {
    color: #000000;
    background-color: #ffb800;
    border-color: #ffb800;
    font-weight: 600;
}
.btn-success {
    color: #000000;
    background-color: #ffb800;
    border-color: #ffb800;
    font-weight: 600;
}
.btn-primary:hover {
    color: #000000;
    background-color: #ffb800;
    border-color: #ffb800;
    font-weight: 600;
}
.custom-caption p {
  font-size: 18px;
  margin-bottom: 20px;
  line-height: 1.6;
}

/* ARROW FIX */
.carousel-control-prev,
.carousel-control-next {
  width: 55px;
}

.carousel-control-prev span,
.carousel-control-next span {
  background-color: #ffb800; /* HAUSLA red */
  border-radius: 50%;
  padding: 18px;
  background-size: 55%;
}

/* RESPONSIVE */
@media (max-width: 768px) {
  .slider-img {
    height: 260px;
  }

  .custom-caption h2 {
    font-size: 22px;
  }

  .custom-caption p {
    font-size: 14px;
  }
}

      .section-about {
  background: linear-gradient(180deg, #f8faff, #ffffff);
}

.about-img-wrap {
  position: relative;
  border-radius: 22px;
  overflow: hidden;
  box-shadow: 0 20px 45px rgba(0,0,0,0.12);
}

.about-img-wrap img {
  width: 100%;
  border-radius: 22px;
  transition: transform 0.5s ease;
}

.about-img-wrap:hover img {
  transform: scale(1.05);
}

.why-title {
  font-size: 34px;
}

.why-title span {
  color: #ffb800;
}

.why-list {
  list-style: none;
  padding: 0;
}

.why-list li {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 14px;
  font-weight: 600;

}

.why-list i {
  color: #ffb800;
  font-size: 18px;
  flex-shrink: 0;
}
.text-primary {
    --bs-text-opacity: 1;
    color: rgb(255 184 0) !important;
}
    </style>
</head>

<body>


   <?php include 'includes/header.php'; ?>
   	<!-- Start Page Title Area -->
<div id="autoSlider" class="carousel slide custom-slider" data-bs-ride="carousel" data-bs-interval="3000">

  <!-- Indicators -->
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#autoSlider" data-bs-slide-to="0" class="active"></button>
    <button type="button" data-bs-target="#autoSlider" data-bs-slide-to="1"></button>
    <button type="button" data-bs-target="#autoSlider" data-bs-slide-to="2"></button>
  </div>

  <div class="carousel-inner">

    <!-- Slide 1 -->
    <div class="carousel-item active">
      <img src="assets/img/hausla-img/5.jpeg" class="slider-img">
      <div class="slider-overlay"></div>
      <div class="carousel-caption custom-caption">
        <h2>HAUSLA – Early Intervention & Neuro-Psychological Rehabilitation Center</h2>
        <p>Supporting children and adults with developmental, behavioural and psychological challenges through evidence-based care.</p>
        <a href="#" class="btn btn-primary">Know More</a>
      </div>
    </div>

    <!-- Slide 2 -->
    <div class="carousel-item">
      <img src="assets/img/hausla-img/2.jpeg" class="slider-img">
      <div class="slider-overlay"></div>
      <div class="carousel-caption custom-caption">
        <h2>Comprehensive Therapy & Assessment</h2>
        <p>Early Intervention, Behaviour Therapy, Speech Therapy, Occupational Therapy, Counselling & Rehabilitation Programs.</p>
        <a href="#" class="btn btn-danger">Our Services</a>
      </div>
    </div>

    <!-- Slide 3 -->
    <div class="carousel-item">
      <img src="assets/img/hausla-img/5.jpeg" class="slider-img">
      <div class="slider-overlay"></div>
      <div class="carousel-caption custom-caption">
        <h2>Trusted Care Backed by Government Recognition</h2>
        <p>An initiative of IPYF Trust, registered with the Department of Empowerment of Persons with Disabilities, Govt. of Uttar Pradesh.</p>
        <a href="#" class="btn btn-success">Contact HAUSLA</a>
      </div>
    </div>

  </div>

  <!-- Controls -->
  <button class="carousel-control-prev hausla-arrow" type="button" data-bs-target="#autoSlider" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>

  <button class="carousel-control-next hausla-arrow" type="button" data-bs-target="#autoSlider" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>

</div>

<section class="hausla-overview py-5 ">
  <div class="container">

    <div class="text-center mb-5">
      <img src="assets/img/hausla-logo.png" alt="Hausla Logo" style="max-width:240px;" class="mb-3">
      <h2 class="fw-bold text-primary">HAUSLA – Early Intervention & Neuro-Psychological Rehabilitation Center, Lucknow</h2>
      <p class="  mx-auto fs-5">
        HAUSLA is an initiative of IPYF Trust, registered with the Department of Empowerment of Persons with Disabilities, Government of Uttar Pradesh.
        The center provides evidence-based Early Intervention, Neuro-Psychological Assessment, Behaviour Therapy, Speech Therapy, Occupational Therapy, 
        Counselling, Day Care Services and Rehabilitation programs for children and adults with developmental, behavioural and psychological challenges.
      </p>
    </div>

    <div class="row align-items-center">
      <div class="col-lg-4">
        <img src="assets/img/hausla-img.jpeg" class="img-fluid rounded-4 shadow">
      </div>

 <div class="col-lg-8">

  <!-- Mission Section -->
  <section class="mb-5">
    <h3 class="fw-bold text-primary">Our Mission</h3>
    <p class="fs-5 ">
      At HAUSLA, our mission is to provide early identification and intervention for children with developmental challenges. 
      We focus on holistic treatment through evidence-based therapies, personalized care plans, and continuous rehabilitation support. 
      By addressing cognitive, emotional, social, and behavioral development, we strive to enhance the overall quality of life for every individual, 
      empowering them to reach their full potential in a safe, nurturing, and structured environment.
    </p>
  </section>

  <!-- Vision Section -->
   <section class="mb-5">
    <h3 class="fw-bold text-primary">Our Vision</h3>
    <p class="fs-5 ">
      Our vision is to become a leading center for early intervention and special education in Lucknow, recognized for excellence, compassion, and 
      innovation in child development. We aim to create an inclusive society where every child, regardless of their abilities, has access to 
      the support, guidance, and resources necessary to thrive and lead a fulfilling life.
    </p>
  </section> 

  <!-- Why HAUSLA Section -->


</div>

    </div>

  </div>
</section>

<section class="section-about py-5">
  <div class="container">
    <div class="row align-items-center g-5">

      <!-- LEFT IMAGE -->
      <div class="col-lg-6">
        <div class="about-img-wrap">
          <img src="assets/img/hausla-about.jpg" alt="Why Hausla" class="img-fluid">
        </div>
      </div>

      <!-- RIGHT CONTENT -->
      <div class="col-lg-6">
        <h3 class="fw-bold why-title mb-3">
          Why <span>HAUSLA?</span>
        </h3>

        <p class=" mb-4">
          HAUSLA is a government-recognized center delivering evidence-based
          therapy and early intervention through a compassionate,
          multidisciplinary approach.
        </p>

        <ul class="why-list">
          <li>
            <i class="bi bi-patch-check-fill"></i>
            Government-registered Center in Lucknow
          </li>
          <li>
            <i class="bi bi-people-fill"></i>
            Multidisciplinary Team of Experts
          </li>
          <li>
            <i class="bi bi-clipboard-data-fill"></i>
            Evidence-based Early Intervention Programs
          </li>
          <li>
            <i class="bi bi-person-heart"></i>
            One-on-one Personalised Therapy Plans
          </li>
          <li>
            <i class="bi bi-house-heart-fill"></i>
            Child-friendly & Safe Environment
          </li>
          <li>
            <i class="bi bi-graph-up-arrow"></i>
            Focus on Holistic Development
          </li>
        </ul>

      </div>

    </div>
  </div>
</section>

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
  
</body>


</html>