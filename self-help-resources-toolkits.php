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
        /* Full-width section */
.toolkits-section {
  width: 100%;
  padding: 60px 20px;
  box-sizing: border-box;
}

/* Section header */
.toolkits-section .section-header {
  text-align: center;
  margin-bottom: 50px;
}

.toolkits-section .section-header h2 {
  font-size: 2.5rem;
  margin-bottom: 15px;
  color: #333;
}

.toolkits-section .section-header p {
  font-size: 1.1rem;
  color: #555;
}

/* Toolkit grid */
.toolkits-section .toolkit-grid {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 30px;
}

/* Individual card */
.toolkits-section .toolkit-card {
  position: relative;
  width: 300px;
  background: linear-gradient(135deg, #4e5bff, #7f40ff);
  color: #fff;
  border-radius: 15px;
  overflow: hidden;
  cursor: pointer;
  transition: transform 0.3s, box-shadow 0.3s;
  box-shadow: 0 6px 15px rgba(0,0,0,0.15);
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  padding: 20px;
}

.toolkits-section .toolkit-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 25px rgba(0,0,0,0.25);
}

.toolkits-section .toolkit-card .card-content h4 {
  margin-bottom: 10px;
  font-size: 1.3rem;
      color: #fff;
}

.toolkits-section .toolkit-card .card-content p {
  font-size: 0.95rem;
  color: #e0e0e0;
}

/* Download button */
.toolkits-section .download-btn {
  display: inline-block;
  background: #fff;
  color: #4e5bff;
  padding: 8px 18px;
  border-radius: 5px;
  text-decoration: none;
  font-weight: bold;
  margin-top: 15px;
  transition: 0.3s;
}

.toolkits-section .download-btn:hover {
  background: #e0e0e0;
  color: #333;
}

/* Responsive */
@media(max-width: 768px) {
  .toolkits-section .toolkit-grid {
    flex-direction: column;
    align-items: center;
  }
}

    </style>
</head>


<body>


   <?php include 'includes/header.php'; ?>
   	<!-- Start Page Title Area -->
<div class="page-title-wave">
  <div class="container">
    <h2> Self-Help Resources & Toolkits</h2>
  </div>

  <svg class="wave" viewBox="0 0 1440 320" preserveAspectRatio="none">
    <path fill="#ffffff" fill-opacity="1" 
      d="M0,64L48,90.7C96,117,192,171,288,170.7C384,171,480,117,576,85.3C672,53,768,43,864,74.7C960,107,1056,181,1152,192C1248,203,1344,149,1392,122.7L1440,96L1440,320L1392,320C1344,320,1248,320,
      1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
    </path>
  </svg>
</div>

  <!-- SELF-HELP RESOURCES & TOOLKITS -->
  <section class="toolkits-section">


    <div class="toolkit-grid">

      <div class="toolkit-card" onclick="downloadToolkit('toolkit1.pdf')">
        <div class="card-content">
          <h4>Mindfulness Workbook</h4>
          <p>Step-by-step exercises to enhance mindfulness in daily life.</p>
        </div>
        <a href="#" class="download-btn">Download →</a>
      </div>

      <div class="toolkit-card" onclick="downloadToolkit('toolkit2.pdf')">
        <div class="card-content">
          <h4>Stress Management Guide</h4>
          <p>Practical strategies to reduce stress and boost resilience.</p>
        </div>
        <a href="#" class="download-btn">Download →</a>
      </div>

      <div class="toolkit-card" onclick="downloadToolkit('toolkit3.pdf')">
        <div class="card-content">
          <h4>Emotional Wellness Toolkit</h4>
          <p>Resources and exercises for managing emotions effectively.</p>
        </div>
        <a href="#" class="download-btn">Download →</a>
      </div>

    </div>

  </section>

  <script>
    function downloadToolkit(file){
      alert("Downloading: " + file);
      // For real download:
      // window.location.href = file;
    }
  </script>



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