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
      /* HERO */
.aptitude-hero{
  background:
    linear-gradient(rgba(0,0,0,.55),rgba(0,0,0,.55)),
    url("https://images.unsplash.com/photo-1523240795612-9a054b0db644") center/cover;
  padding:120px 0;
  color:#fff;
}

/* CARDS */
.assess-card{
  background:#fff;
  padding:30px;
  border-radius:16px;
  text-align:center;
  box-shadow:0 10px 30px rgba(0,0,0,.08);
  height:100%;
}
.assess-card i{
  font-size:42px;
  color:#0d6efd;
  margin-bottom:15px;
}

/* CHECK LIST */
.list-check li{
  list-style:none;
  margin-bottom:10px;
  padding-left:30px;
  position:relative;
}
.list-check li::before{
  content:"✔";
  color:#28a745;
  position:absolute;
  left:0;
}

/* PRICE CARD */
.price-card{
  border-radius:20px;
  box-shadow:0 15px 40px rgba(0,0,0,.15);
}
    </style>
</head>

<body>


   <?php include 'includes/header.php'; ?>
   	<!-- Start Page Title Area -->
<div class="page-title-wave">
  <div class="container">
    <h2> Aptitude Tests</h2>
  </div>

  <svg class="wave" viewBox="0 0 1440 320" preserveAspectRatio="none">
    <path fill="#ffffff" fill-opacity="1" 
      d="M0,64L48,90.7C96,117,192,171,288,170.7C384,171,480,117,576,85.3C672,53,768,43,864,74.7C960,107,1056,181,1152,192C1248,203,1344,149,1392,122.7L1440,96L1440,320L1392,320C1344,320,1248,320,
      1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
    </path>
  </svg>
</div>




<!-- WHAT IS -->
<section class="py-5">
  <div class="container">
    <div class="row align-items-center g-5">

      <div class="col-lg-6">
        <img src="https://images.unsplash.com/photo-1551836022-d5d88e9218df"
             class="img-fluid rounded-4 shadow">
      </div>

      <div class="col-lg-6">
        <h2 class="fw-bold mb-3">What is an Aptitude Test?</h2>
        <p class="text-muted fs-5">
          Aptitude tests are psychological assessments designed to measure
          an individual’s natural abilities, interests and career suitability.
        </p>
        <p class="text-muted">
          These tests help students and professionals make confident academic
          and career decisions backed by scientific evaluation.
        </p>
      </div>

    </div>
  </div>
</section>

<!-- WHAT WE ASSESS -->
<section class="py-5 bg-light">
  <div class="container">

    <div class="text-center mb-5">
      <h2 class="fw-bold">What We Assess</h2>
      <p class="text-muted fs-5">Key dimensions of aptitude & ability</p>
    </div>

    <div class="row g-4">

      <div class="col-md-4">
        <div class="assess-card">
          <i class="bi bi-lightbulb"></i>
          <h5>Logical Reasoning</h5>
          <p>Problem-solving and logical thinking skills.</p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="assess-card">
          <i class="bi bi-calculator"></i>
          <h5>Numerical Ability</h5>
          <p>Understanding numbers, data and calculations.</p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="assess-card">
          <i class="bi bi-chat-dots"></i>
          <h5>Verbal Skills</h5>
          <p>Language comprehension and communication ability.</p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="assess-card">
          <i class="bi bi-graph-up-arrow"></i>
          <h5>Analytical Thinking</h5>
          <p>Ability to analyze and interpret situations.</p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="assess-card">
          <i class="bi bi-person-check"></i>
          <h5>Career Interests</h5>
          <p>Interest alignment with career paths.</p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="assess-card">
          <i class="bi bi-brain"></i>
          <h5>Cognitive Strength</h5>
          <p>Focus, memory and mental processing.</p>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- WHO SHOULD -->
<section class="py-5">
  <div class="container">
    <div class="row align-items-center g-5">

      <div class="col-lg-6 order-lg-2">
        <img src="https://images.unsplash.com/photo-1523580846011-d3a5bc25702b"
             class="img-fluid rounded-4 shadow">
      </div>

      <div class="col-lg-6">
        <h2 class="fw-bold mb-3">Who Should Take This Test?</h2>
        <ul class="list-check fs-5">
          <li>School students (Class 8 onwards)</li>
          <li>College & university students</li>
          <li>Working professionals seeking career change</li>
          <li>Parents seeking career guidance for children</li>
        </ul>
      </div>

    </div>
  </div>
</section>

<!-- PRICING / CTA -->
<section class="py-5 bg-light" id="book">
  <div class="container">
    <div class="row justify-content-center">

      <div class="col-lg-6">
        <div class="price-card bg-white p-5 text-center">

          <h3 class="fw-bold mb-3">Aptitude Test Package</h3>

          <ul class="list-unstyled mb-4">
            <li>✔ Detailed aptitude assessment</li>
            <li>✔ Comprehensive report</li>
            <li>✔ One-on-one counselling session</li>
            <li>✔ Online & Offline mode</li>
          </ul>

          <div class="fs-2 fw-bold text-primary mb-4">
            ₹1500
          </div>

          <a href="book-appointment.php" class="btn btn-success btn-lg rounded-pill px-5">
            Book Appointment
          </a>

        </div>
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