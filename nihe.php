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
        .academic-section {
  padding: 70px 0;

}

.section-title {
  font-size: 32px;
  font-weight: 800;
  margin-bottom: 40px;
  position: relative;
  padding-left: 20px;
  color: #0b1c39;
}

.section-title span {
  position: absolute;
  left: 0;
  top: 5px;
  width: 6px;
  height: 35px;
  background: linear-gradient(180deg,#ffb703,#fb8500);
  border-radius: 4px;
}

.program-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit,minmax(260px,1fr));
  gap: 25px;
}

.program-card {
  padding: 30px 25px;
  border-radius: 16px;
  background: #fff;
  box-shadow: 0 10px 30px rgba(0,0,0,0.07);
  text-align: center;
  position: relative;
  overflow: hidden;
  cursor: pointer;
  transition: all 0.35s ease;
}

.program-card::before {
  content: "";
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg,transparent,rgba(255,255,255,0.25));
  opacity: 0;
  transition: 0.3s;
}

.program-card:hover {
  transform: translateY(-12px);
  box-shadow: 0 18px 40px rgba(0,0,0,0.15);
      color: #ffffff;
}

.program-card:hover::before {
  opacity: 1;
}

.program-card .icon {
  font-size: 40px;
  margin-bottom: 15px;
}

.program-card h4 {
  font-size: 20px;
  font-weight: 700;
  margin-bottom: 8px;
      color: #ffffff;
}

.program-card p {
  font-size: 14px;
  opacity: 0.9;
      color: #fff;
}

/* Color Themes */
.purple {background: linear-gradient(135deg,#6a11cb,#2575fc); color:#fff;}
.blue {background: linear-gradient(135deg,#1cb5e0,#000851); color:#fff;}
.green {background: linear-gradient(135deg,#11998e,#38ef7d); color:#fff;}
.orange {background: linear-gradient(135deg,#f7971e,#ffd200); color:#000;}
.pink {background: linear-gradient(135deg,#ff758c,#ff7eb3); color:#fff;}
.teal {background: linear-gradient(135deg,#43cea2,#185a9d); color:#fff;}
.red {background: linear-gradient(135deg,#ff416c,#ff4b2b); color:#fff;}
.dark {background: linear-gradient(135deg,#232526,#414345); color:#fff;}
.green-1 {
    background: linear-gradient(135deg, #232526, #4dd50b);
    color: #fff;
}
.program-icon {
  width: 55px;
  height: 55px;
  object-fit: contain;
  margin-bottom: 15px;
  /* filter: brightness(0) invert(1); */
}

/* orange card ke liye dark icon */
.orange .program-icon {
  filter: none;
}
.nihe-logo {
    height: 120px;
    width: auto;
    border-radius: 5%;
}
    </style>
</head>

<body>


   <?php include 'includes/header.php'; ?>
   	<!-- Start Page Title Area -->
<div class="page-title-wave">
<div class="container">
  <div class="align-items-center gap-3">
    <img src="assets/img/our-affiliations/13.jpg" alt="NIHE Logo" class="nihe-logo mb-2">
    <h2 class="mb-0">National Institute of Higher Education</h2>
  </div>
</div>


  <svg class="wave" viewBox="0 0 1440 320" preserveAspectRatio="none">
    <path fill="#ffffff" fill-opacity="1" 
      d="M0,64L48,90.7C96,117,192,171,288,170.7C384,171,480,117,576,85.3C672,53,768,43,864,74.7C960,107,1056,181,1152,192C1248,203,1344,149,1392,122.7L1440,96L1440,320L1392,320C1344,320,1248,320,
      1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
    </path>
  </svg>
</div>

<section class="academic-section">
  <div class="container">


    <div class="program-grid">

      <!-- Executive -->
      <a href="offerings.diploma-programs.php" class="program-card pink">
        <img src="assets/icons/ceo.png" class="program-icon" alt="">
        <h4>Executive Education Programs</h4>
        <p>
          Executive-level programs designed for working professionals to upgrade
          leadership skills, management knowledge, and strategic capabilities.
        </p>
      </a>

      <!-- UG -->
      <a href="offerings-undergraduate-programs.php" class="program-card teal">
        <img src="assets/icons/undergraduate1.png" class="program-icon" alt="">
        <h4>Undergraduate Programs</h4>
        <p>
          Undergraduate courses structured to combine academic excellence,
          experiential learning, and holistic student development.
        </p>
      </a>

      <!-- PG Diploma -->
      <a href="offerings-postgraduate-diploma.php" class="program-card red">
        <img src="assets/icons/undergraduate1.png" class="program-icon" alt="">
        <h4>Postgraduate Diploma</h4>
        <p>
          Professional postgraduate diplomas focusing on advanced skills,
          domain expertise, and industry-oriented education.
        </p>
      </a>

      <!-- PG -->
      <a href="offerings-postgraduate-programs.php" class="program-card dark">
        <img src="assets/icons/postgraduate.png" class="program-icon" alt="">
        <h4>Postgraduate Programs</h4>
        <p>
          Postgraduate degree programs that emphasize research, advanced learning,
          specialization, and professional growth.
        </p>
      </a>

      <!-- PhD -->
      <a href="doctoral-research-phd.php" class="program-card green-1">
        <img src="assets/icons/doctor.png" class="program-icon" alt="">
        <h4>Doctoral Research (Ph.D.)</h4>
        <p>
          Doctoral research programs designed for scholars pursuing advanced research,
          innovation, and academic leadership across disciplines.
        </p>
      </a>

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