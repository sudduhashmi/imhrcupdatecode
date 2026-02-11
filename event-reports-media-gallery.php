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
    <!-- ================= CUSTOM CSS ================= -->
<style>
/* Common Section Head */
.section-head h2{
  font-weight:700;
}
.section-head h2 span{
  color:#ffb800;
}
.section-head p{
  color:#666;
}

/* Event Reports */
.event-reports{
  background:#f8f9fa;
}
.event-card{
  background:#fff;
  padding:30px;
  border-radius:16px;
  position:relative;
  box-shadow:0 10px 30px rgba(0,0,0,0.08);
  transition:.3s;
  height: 100%;
}
.event-card:hover{
  transform:translateY(-8px);
}
.event-img{
  width:100%;
  height:180px;
  object-fit:cover;
  border-radius:12px;
  margin-bottom:15px;
}
.event-date{
  position:absolute;
  top:15px;
  right:20px;
  background:#ffb800;
  color:#fff;
  padding:10px 14px;
  text-align:center;
  border-radius:10px;
  font-weight:700;
}
.event-card{
  background:#fff;
  padding:30px;
  border-radius:16px;
  position:relative;
  box-shadow:0 10px 30px rgba(0,0,0,0.08);
  transition:.3s;
}
.event-card:hover{
  transform:translateY(-8px);
}
.event-date{
  position:absolute;
  top:-20px;
  right:20px;
  background:#ffb800;
  color:#fff;
  padding:10px 14px;
  text-align:center;
  border-radius:10px;
  font-weight:700;
}
.event-card h5{
  margin-top:20px;
  font-weight:600;
}
.event-card p{
  font-size:14px;
  color:#555;
}
.read-btn{
  color:#ffb800;
  font-weight:600;
  text-decoration:none;
}

/* Media Gallery */
.media-gallery{
  background:#fff;
}
.gallery-box{
  position:relative;
  overflow:hidden;
  border-radius:16px;
}
.gallery-box img{
  width:100%;
  height:280px;
  object-fit:cover;
  transition:.4s;
}
.gallery-box:hover img{
  transform:scale(1.1);
}
.gallery-overlay{
  position:absolute;
  inset:0;
  background:rgba(0,0,0,0.55);
  display:flex;
  align-items:center;
  justify-content:center;
  color:#fff;
  font-size:18px;
  font-weight:600;
  opacity:0;
  transition:.4s;
}
.gallery-box:hover .gallery-overlay{
  opacity:1;
}

@media(max-width:768px){
  .gallery-box img{height:220px;}
}
.gallery-box{
  position:relative;
  overflow:hidden;
  border-radius:16px;
  cursor:pointer;
}
.gallery-box img{
  width:100%;
  height:280px;
  object-fit:cover;
  transition:.4s;
}
.gallery-box:hover img{
  transform:scale(1.1);
}
.gallery-overlay{
  position:absolute;
  inset:0;
  background:rgba(0,0,0,0.55);
  display:flex;
  align-items:center;
  justify-content:center;
  color:#fff;
  font-size:18px;
  font-weight:600;
  opacity:0;
  transition:.3s;
}
.gallery-box:hover .gallery-overlay{
  opacity:1;
}
@media(max-width:768px){
  .gallery-box img{height:220px;}
}

</style>
</head>

<body>


   <?php include 'includes/header.php'; ?>
   	<!-- Start Page Title Area -->
<div class="page-title-wave">
  <div class="container">
    <h2>Event Reports & Media Gallery</h2>
     <p>Detailed insights and highlights from our recent events</p>
  </div>

  <svg class="wave" viewBox="0 0 1440 320" preserveAspectRatio="none">
    <path fill="#f8f9fa" fill-opacity="1" 
      d="M0,64L48,90.7C96,117,192,171,288,170.7C384,171,480,117,576,85.3C672,53,768,43,864,74.7C960,107,1056,181,1152,192C1248,203,1344,149,1392,122.7L1440,96L1440,320L1392,320C1344,320,1248,320,
      1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
    </path>
  </svg>
</div>

<!-- ================= EVENT REPORTS SECTION ================= -->
<section class="event-reports py-5">
  <div class="container">
    <div class="row g-4">
      <div class="col-md-4">
        <div class="event-card">
          <img src="assets\img\hausla-img/12.jpeg" class="event-img" alt="Event Image">
          <div class="event-date">12<br><small>Aug</small></div>
          <h5>Healthcare Awareness Camp</h5>
          <p>Community-focused medical awareness programme with doctors.</p>
          <a href="events-report.php" class="read-btn">Read Report →</a>
        </div>
      </div>

      <div class="col-md-4">
        <div class="event-card">
          <img src="assets\img\hausla-img/12.jpeg" class="event-img" alt="Event Image">
          <div class="event-date">28<br><small>Sep</small></div>
          <h5>Mental Health Workshop</h5>
          <p>Interactive session on stress management & counselling.</p>
          <a href="events-report.php" class="read-btn">Read Report →</a>
        </div>
      </div>

      <div class="col-md-4">
        <div class="event-card">
          <img src="assets\img\hausla-img/12.jpeg" class="event-img" alt="Event Image">
          <div class="event-date">05<br><small>Oct</small></div>
          <h5>Youth Leadership Meet</h5>
          <p>Empowering young leaders through training & mentorship.</p>
          <a href="events-report.php" class="read-btn">Read Report →</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ================= MEDIA GALLERY SECTION ================= -->
<section class="media-gallery py-5">
  <div class="container">
    <div class="section-head text-center mb-5">
      <h2>Media <span>Gallery</span></h2>
      <p>Moments captured from our journeys and events</p>
    </div>

    <div class="row g-4">
      <div class="col-md-4 col-sm-6">
        <div class="gallery-box" data-bs-toggle="modal" data-bs-target="#imageModal" 
             onclick="openImage('assets/img/hausla-img/12.jpeg')">
          <img src="assets/img/hausla-img/12.jpeg" alt="">
          <div class="gallery-overlay">View</div>
        </div>
      </div>

      <div class="col-md-4 col-sm-6">
        <div class="gallery-box" data-bs-toggle="modal" data-bs-target="#imageModal" 
             onclick="openImage('assets/img/hausla-img/12.jpeg')">
          <img src="assets/img/hausla-img/12.jpeg" alt="">
          <div class="gallery-overlay">View</div>
        </div>
      </div>

    </div>
  </div>
</section>

<div class="modal fade" id="imageModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-transparent border-0">
      <button type="button" class="btn-close btn-close-white ms-auto me-2 mt-2"
              data-bs-dismiss="modal"></button>
      <img id="popupImage" src="" class="img-fluid rounded">
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
  <script>
function openImage(src){
  document.getElementById("popupImage").src = src;
}
</script>

</body>


</html>