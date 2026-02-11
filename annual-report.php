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
    :root {
  --accent: #ff7b2f;
  --accent2: #ffb84d;
  --muted: #6b7280;
  --card: #ffffff;
  --shadow: rgba(0, 0, 0, 0.08);
}

.resources-wrapper { padding: 20px; max-width: 1200px; margin: auto; }

.header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
.kicker {

  color: #ff7b2f;  border-radius: 50px; font-weight: 700; font-size: 13px;
}
.title { margin: 6px 0 0; font-size: 32px; color: #0d2540; }
.subtitle { margin: 4px 0 0; color: var(--muted); }

.hero {
  display: flex; gap: 28px; padding: 28px;
  background: #1d274b; border-radius: 16px;
  box-shadow: 0 10px 30px var(--shadow);
  margin-bottom: 25px;
}
.hero-left { flex: 1; }
.hero-right img { width: 100%; border-radius: 10px; }

.hero-title { font-size: 28px; margin-bottom: 6px; color: #ffffff; }
.hero-desc { margin: 0; color: #ffffff; }

.controls { display: flex; gap: 12px; margin-top: 15px; flex-wrap: wrap; }
.select, .search {
  padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px;
  width: 49%;
}

.grid {
  display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 20px;
}

.card {
  background: var(--card); padding: 20px;
  border-radius: 14px;
  box-shadow: 0 6px 20px var(--shadow);
  display: flex; flex-direction: column; gap: 12px;
  transition: transform .2s;
      border: 1px solid #1d274b;
}
.card:hover { transform: translateY(-4px); }

.meta { display: flex; justify-content: space-between; color: var(--muted); font-size: 13px; }
.card h3 { margin: 0; }
.card p { margin: 0; color: #374151; }

.actions { margin-top: auto; display: flex; gap: 10px; }
.btn {
  padding: 10px 14px; border-radius: 8px; border: 0; cursor: pointer; font-weight: 600;
}
.btn-download {
    background: linear-gradient(90deg, #1d274b, #1d274b);
    color: #fff;
    border: 1px solid #1d274b;
}
a.btn.btn-download:hover {
    color: #fff;
}
.btn-preview {
    background: #ffb800;
    color: #000000;
}

/* MODAL */
.modal {
  display: none; position: fixed; inset: 0;
  background: rgba(0,0,0,0.7);
  justify-content: center; align-items: center; z-index: 9999;
}
.modal-box {
  width: 90%; max-width: 1100px; height: 85%;
  background: #fff; border-radius: 12px; position: relative; overflow: hidden;
}
.close-btn {
  position: absolute; right: 15px; top: 15px;
  background: #111; color: #fff; padding: 8px 12px;
  border: none; border-radius: 6px; cursor: pointer;
}
iframe { width: 100%; height: 100%; border: none; }

@media(max-width:760px){
  .hero { flex-direction: column; }
  .hero-right img { width: 100%; }
}

</style>
</head>

<body>


   <?php include 'includes/header.php'; ?>
   	<!-- Start Page Title Area -->
<div class="page-title-wave">
  <div class="container">
    <h2>Annual Report</h2>
  </div>

  <svg class="wave" viewBox="0 0 1440 320" preserveAspectRatio="none">
    <path fill="#ffffff" fill-opacity="1" 
      d="M0,64L48,90.7C96,117,192,171,288,170.7C384,171,480,117,576,85.3C672,53,768,43,864,74.7C960,107,1056,181,1152,192C1248,203,1344,149,1392,122.7L1440,96L1440,320L1392,320C1344,320,1248,320,
      1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
    </path>
  </svg>
</div>

<div class="container pb-5">
  <!-- HERO -->
  <div class="hero">
    <div class="hero-left">
      <h1 class="hero-title">Annual Reports Archive</h1>
      <p class="hero-desc">
        Explore IMHRC’s year-wise program achievements, financial summaries, 
        community mental health initiatives, rehabilitation outcomes, global collaborations,
        and research highlights. Download the complete audited reports or preview online.
      </p>

      <div class="controls">
        <select id="filterYear" class="select" aria-label="Filter Year">
          <option value="all">All years</option>
          <option value="2024">2024</option>
          <option value="2023">2023</option>
          <option value="2022">2022</option>
          <option value="2021">2021</option>
        </select>

        <input id="searchReports" class="search" placeholder="Search report title or keyword">
      </div>
    </div>

   
  </div>

  <!-- GRID -->
  <div id="reportsGrid" class="grid">

    <!-- 2024 -->
    <div class="card" data-year="2024">
      <div class="meta"><span>IMHRC Annual Report</span><span>Dec 2024</span></div>
      <h3>IMHRC Annual Report 2024</h3>
      <p>
        Comprehensive insights into mental health rehabilitation programs, 
        community outreach growth, national training initiatives, and audited financial statements.
      </p>

      <div class="actions">
        <a class="btn btn-download" href="/docs/annual-report-2024.pdf" target="_blank">Download PDF</a>
        <button class="btn btn-preview" onclick="previewPDF('/docs/annual-report-2024.pdf')">Preview</button>
      </div>
    </div>

    <!-- 2023 -->
    <div class="card" data-year="2023">
      <div class="meta"><span>IMHRC Annual Report</span><span>Dec 2023</span></div>
      <h3>IMHRC Annual Report 2023</h3>
      <p>
        Highlights covering national mental health awareness campaigns, 
        early intervention services, research publications, and multi-sector program expansion.
      </p>

      <div class="actions">
        <a class="btn btn-download" href="/docs/annual-report-2023.pdf" target="_blank">Download PDF</a>
        <button class="btn btn-preview" onclick="previewPDF('/docs/annual-report-2023.pdf')">Preview</button>
      </div>
    </div>

    <!-- 2022 -->
    <div class="card" data-year="2022">
      <div class="meta"><span>IMHRC Annual Report</span><span>Dec 2022</span></div>
      <h3>IMHRC Annual Report 2022</h3>
      <p>
        Detailed review of rehabilitation case studies, digital learning modules,
        national partnerships, and long-term mental wellness interventions.
      </p>

      <div class="actions">
        <a class="btn btn-download" href="/docs/annual-report-2022.pdf" target="_blank">Download PDF</a>
        <button class="btn btn-preview" onclick="previewPDF('/docs/annual-report-2022.pdf')">Preview</button>
      </div>
    </div>

    <!-- 2021 -->
    <div class="card" data-year="2021">
      <div class="meta"><span>IMHRC Annual Report</span><span>Dec 2021</span></div>
      <h3>IMHRC Annual Report 2021</h3>
      <p>
        Overview of IMHRC’s pandemic-time support services, tele-counseling operations, 
        capacity-building initiatives, and health systems strengthening.
      </p>

      <div class="actions">
        <a class="btn btn-download" href="/docs/annual-report-2021.pdf" target="_blank">Download PDF</a>
        <button class="btn btn-preview" onclick="previewPDF('/docs/annual-report-2021.pdf')">Preview</button>
      </div>
    </div>

  </div>

  <!-- MODAL -->
  <div id="pdfModal" class="modal">
    <div class="modal-box">
      <button class="close-btn" onclick="closePDF()">Close</button>
      <iframe id="pdfFrame" src=""></iframe>
    </div>
  </div>

</div>


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