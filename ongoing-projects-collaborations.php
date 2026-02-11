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
        /* =============================
       Overview Section
    ============================= */
    .overview-section {
      background: linear-gradient(135deg, #4755c4, #1d274b);
      color: #fff;
    }
    section.overview-section h2 {
    color: #ffb800;
}
    .overview-section h2 {
      font-weight: 700;
    }
    .overview-section p {
      font-size: 1.1rem;
      margin-bottom: 2rem;
    }
    .overview-section .stats-card {
      background: rgba(255,255,255,0.1);
      color: #fff;
      transition: transform 0.3s;
    }
    .overview-section .stats-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    }
    .overview-section .stats-card h3 {
      font-size: 2rem;
      font-weight: 700;
          color: #ffb800;
    }
    .stats-card {
    padding: 40px !important;
}
    .overview-section .stats-card p {
      margin: 0;
      font-weight: 500;
    }

    /* =============================
       Collaboration Details
    ============================= */
    .collaboration-details h2 {
      font-weight: 700;
      margin-bottom: 2rem;
    }
    .collaboration-card {
      transition: transform 0.3s;
      border: none;
    }
    .collaboration-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    .collaboration-card .card-body h5 {
      font-weight: 600;
    }
    .status-badge {
      font-size: 0.8rem;
      padding: 0.25rem 0.5rem;
      border-radius: 5px;
      color: #fff;
    }
    .status-ongoing { background-color: #28a745; }
    .status-completed { background-color: #6c757d; }
    .status-upcoming { background-color: #ffc107; color: #212529; }
  
    </style>
</head>

<body>


   <?php include 'includes/header.php'; ?>
     <!-- ============================
       Overview Section
  ============================ -->
  <section class="overview-section overview-stats py-5">
    <div class="container text-center">
      <h2 class="mb-3">Ongoing Projects & International Collaborations</h2>
      <p>Explore our diverse projects and partnerships across the globe, contributing to innovative solutions and research excellence.</p>

   <div class="row g-4 text-center">
      <div class="col-md-3 col-6">
        <div class="stats-card p-3 rounded shadow-sm">
          <h3 class="mb-1" data-target="25">0</h3>
          <p>Projects</p>
        </div>
      </div>
      <div class="col-md-3 col-6">
        <div class="stats-card p-3 rounded shadow-sm">
          <h3 class="mb-1" data-target="15">0</h3>
          <p>Countries</p>
        </div>
      </div>
      <div class="col-md-3 col-6">
        <div class="stats-card p-3 rounded shadow-sm">
          <h3 class="mb-1" data-target="10">0</h3>
          <p>Research Areas</p>
        </div>
      </div>
      <div class="col-md-3 col-6">
        <div class="stats-card p-3 rounded shadow-sm">
          <h3 class="mb-1" data-target="30">0</h3>
          <p>Partners</p>
        </div>
      </div>
    </div>

    </div>
  </section>

  <!-- ============================
       Collaboration Details
  ============================ -->
<section class="collaboration-details py-5">
  <div class="container">
    <div class="row g-4 d-flex align-items-stretch">
      <!-- Project 1 -->
      <div class="col-md-6 d-flex">
        <div class="card collaboration-card shadow-sm w-100">
          <div class="card-body">
            <h5 class="card-title">
              Project Alpha 
              <span class="status-badge status-ongoing">Ongoing</span>
            </h5>
            <p class="mb-1"><strong>Partner Institutions:</strong> University A, Institute B</p>
            <p class="mb-1"><strong>Duration:</strong> Jan 2025 - Dec 2026</p>
            <p><strong>Objectives:</strong> Research on renewable energy solutions and international knowledge exchange.</p>
          </div>
        </div>
      </div>

      <!-- Project 2 -->
      <div class="col-md-6 d-flex">
        <div class="card collaboration-card shadow-sm w-100">
          <div class="card-body">
            <h5 class="card-title">
              Project Beta 
              <span class="status-badge status-completed">Completed</span>
            </h5>
            <p class="mb-1"><strong>Partner Institutions:</strong> University C, Company D</p>
            <p class="mb-1"><strong>Duration:</strong> Mar 2024 - Feb 2025</p>
            <p><strong>Objectives:</strong> Development of AI-based healthcare tools with global collaboration.</p>
          </div>
        </div>
      </div>

      <!-- Project 3 -->
      <div class="col-md-6 d-flex">
        <div class="card collaboration-card shadow-sm w-100">
          <div class="card-body">
            <h5 class="card-title">
              Project Gamma 
              <span class="status-badge status-upcoming">Upcoming</span>
            </h5>
            <p class="mb-1"><strong>Partner Institutions:</strong> Institute E, Company F</p>
            <p class="mb-1"><strong>Duration:</strong> Jan 2026 - Dec 2027</p>
            <p><strong>Objectives:</strong> International collaboration in smart city research and urban sustainability.</p>
          </div>
        </div>
      </div>

      <!-- Project 4 -->
      <div class="col-md-6 d-flex">
        <div class="card collaboration-card shadow-sm w-100">
          <div class="card-body">
            <h5 class="card-title">
              Project Delta 
              <span class="status-badge status-ongoing">Ongoing</span>
            </h5>
            <p class="mb-1"><strong>Partner Institutions:</strong> University G, NGO H</p>
            <p class="mb-1"><strong>Duration:</strong> May 2025 - Apr 2027</p>
            <p><strong>Objectives:</strong> Global education initiatives and skill development programs.</p>
          </div>
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
  <script>
  // Counter animation
  const counters = document.querySelectorAll('.stats-card h3');
  const speed = 200; // lower = faster

  const animateCounters = () => {
    counters.forEach(counter => {
      const updateCount = () => {
        const target = +counter.getAttribute('data-target');
        const count = +counter.innerText;
        const increment = target / speed;

        if(count < target){
          counter.innerText = Math.ceil(count + increment);
          setTimeout(updateCount, 20);
        } else {
          counter.innerText = target + (counter.getAttribute('data-target').includes('+') ? '+' : '');
        }
      }
      updateCount();
    });
  }

  // Optional: Animate only when section comes into view
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if(entry.isIntersecting){
        animateCounters();
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.5 });

  observer.observe(document.querySelector('.overview-stats'));
</script>
</body>


</html>