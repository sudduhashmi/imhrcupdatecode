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
.form-control {
    /* height: 50px; */
    color: #324cc5;
    border: 1px solid #c6c6c6;
    background-color: transparent;
    border-radius: 0;
    font-size: 16px;
    padding: 10px 20px;
    width: 100%;
    border-radius: 5px;
}

.career-title {
  font-size: 2.8rem;
  font-weight: 700;
  color: #1d274b;
}

.career-subtitle {
  font-size: 1.1rem;
  margin: 15px 0 20px;
  color: #444;
}

.career-points {
  padding-left: 0;
  list-style: none;
}

.career-points li {
  margin-bottom: 10px;
  font-size: 1rem;
}

.career-img {
  border-radius: 15px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

/* Form Box */
.career-form-box {
  background: #ffffff;
  padding: 35px;
  border-radius: 18px;
  box-shadow: 0 12px 40px rgba(0,0,0,0.12);
}

.career-form-box label {
  font-weight: 600;
  margin-bottom: 6px;
}

/* Button */
.btn-career {
  background: #1d274b;
  color: #fff;
  padding: 14px;
  font-size: 1.1rem;
  font-weight: 600;
  border-radius: 10px;
  transition: 0.3s;
}

.btn-career:hover {
  background: #f5ab17;
  color: #000;
}

/* Mobile */
@media(max-width: 768px) {
  .career-title {
    font-size: 2.2rem;
  }
}

  </style>
</head>

<body>


   <?php include 'includes/header.php'; ?>
   	<!-- Start Page Title Area -->
<div class="page-title-wave">
  <div class="container">
    <h2>Join Our Team</h2>
  </div>

  <svg class="wave" viewBox="0 0 1440 320" preserveAspectRatio="none">
    <path fill="#ffffff" fill-opacity="1" 
      d="M0,64L48,90.7C96,117,192,171,288,170.7C384,171,480,117,576,85.3C672,53,768,43,864,74.7C960,107,1056,181,1152,192C1248,203,1344,149,1392,122.7L1440,96L1440,320L1392,320C1344,320,1248,320,
      1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
    </path>
  </svg>
</div>

  <!-- ================= JOIN OUR TEAM ================= -->
<section class="career-apply-section py-5">
  <div class="container">
    <div class="row align-items-center">

      <!-- Left Content -->
      <div class="col-lg-12 mb-4">
        <div class="row align-items-center">
          <div class="col-lg-8">
     <h2 class="career-title">Join Our Team</h2>
        <p class="career-subtitle">
          We are looking for passionate individuals who want to make an impact.
          Fill out the application form and grow your career with us.
        </p>

        <ul class="career-points">
          <li>✔ All fields are mandatory</li>
          <li>✔ Transparent selection process</li>
          <li>✔ Growth & learning opportunities</li>
          <li>✔ Work with experienced professionals</li>
        </ul>

          </div>
            <div class="col-lg-4">
                <img src="assets/img/join-our-team.png" class="img-fluid mt-4 career-img" alt="Career">
          </div>
        </div>
   
    
      </div>

      <!-- Right Form -->
      <div class="col-lg-12">
        <div class="career-form-box">
          <h4 class="mb-4">Job Application Form</h4>

          <form method="POST" enctype="multipart/form-data">

            <!-- Prefix + Name -->
            <div class="row">
              <div class="col-md-3 mb-3">
                <label>Prefix *</label>
                <select class="form-control" required>
                  <option value="">Select</option>
                  <option>Mr.</option>
                  <option>Ms.</option>
                  <option>Mrs.</option>
                  <option>Dr.</option>
                </select>
              </div>

              <div class="col-md-9 mb-3">
                <label>Full Name *</label>
                <input type="text" class="form-control" placeholder="Enter your name" required>
              </div>
            </div>

            <!-- Email + Phone -->
            <div class="row">
              <div class="col-md-6 mb-3">
                <label>Email *</label>
                <input type="email" class="form-control" required>
              </div>

              <div class="col-md-6 mb-3">
                <label>Contact Number *</label>
                <input type="tel" class="form-control" required>
              </div>
            </div>

            <!-- Position -->
            <div class="mb-3">
              <label>Position Interested In *</label>
              <select class="form-control" required>
                <option value="">Select Position</option>
                <option>Research Assistant</option>
                <option>Clinical Intern</option>
                <option>Psychologist</option>
                <option>Content Writer</option>
                <option>Marketing Executive</option>
                <option>Volunteer</option>
              </select>
              <small class="text-muted">
                (Options can be easily added/removed later)
              </small>
            </div>

            <!-- Address -->
            <div class="mb-3">
              <label>Address of Candidate *</label>
              <textarea class="form-control" rows="3" required></textarea>
            </div>

            <!-- Uploads -->
            <div class="row">
              <div class="col-md-6 mb-3">
                <label>Upload Photo *</label>
                <input type="file" class="form-control" accept="image/*" required>
              </div>

              <div class="col-md-6 mb-3">
                <label>Upload Resume (PDF) *</label>
                <input type="file" class="form-control" accept=".pdf" required>
              </div>
            </div>

            <!-- Summary -->
            <div class="mb-4">
              <label>Short Summary About Yourself *</label>
              <textarea class="form-control" rows="4"
                placeholder="Brief introduction, skills & experience"
                required></textarea>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-career w-100">
              Submit Application
            </button>

          </form>
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