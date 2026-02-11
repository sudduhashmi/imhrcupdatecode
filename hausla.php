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
/* ===============================
   SECTION TITLE
================================ */
.section-title{
  text-align:center;
  margin-bottom:40px;
}
.section-title h2{
  font-weight:700;
}
.section-title p{
  color:#666;
}

/* ===============================
   SERVICES
================================ */
.service-card{
  border:1px solid #eee;
  padding:25px;
  border-radius:10px;
  height:100%;
  transition:.3s;
}
.service-card:hover{
  box-shadow:0 10px 25px rgba(0,0,0,.1);
}

/* ===============================
   TEAM
================================ */
.team-card img{
  border-radius:10px;
}

/* ===============================
   CTA
================================ */
.hausla-cta{
  background:#0f1e3d;
  color:#fff;
  padding:60px 15px;
  text-align:center;
}
section#appointment h2 {
    color: #ffb800;
}
.btn-light {
    color: #000;
    background-color: #ffb800;
    border-color: #ffb800;
    max-width: 100%;
    width: 13%;
    font-weight: 600;
}
/* ===============================
   FOOTER
================================ */
.hausla-footer{
  background:#0f1e3d;
  color:#ccc;
  padding:30px 0;
}

/* ===============================
   RESPONSIVE
================================ */
@media(max-width:768px){
  .hausla-hero h1{font-size:26px;}
  .hausla-hero p{font-size:14px;}
}
</style>
<style>
  .service-card {
    transition: all 0.3s ease;
    border-radius: 20px;
    background: #fff;
    padding: 30px;
    border: 1px solid #e8e8e8;
    height: 100%;
    position: relative;
  }
  .service-card:hover {
    transform: translateY(-8px);
    box-shadow: 0px 15px 35px rgba(0,0,0,0.1);
    border-color: #1e63ff;
  }
  .service-icon {
    font-size: 55px;
    margin-bottom: 15px;
    color: #ffb800;
  }
  .read-more-btn {
    border: none;
    background: none;
    color: #1e63ff;
    font-weight: 600;
    cursor: pointer;
  }
  .read-more-content {
    display: none;
    margin-top: 10px;
  }
  .btn-primary {
    color: #ffffff;
    background-color: #1d274b;
    border-color: #1d274b;
}
</style>
<style>
.team-card {
  transition: 0.3s;
}
.team-card:hover {
  transform: translateY(-10px);
  box-shadow: 0px 15px 35px rgba(0,0,0,0.15);
}
.team-card img {
  transition: 0.3s;
}
.team-card:hover img {
  transform: scale(1.07);
}
</style>
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
.navbar-dark .navbar-nav .nav-link {
      color: rgb(10 10 10 / 98%);
}
.navbar-nav .nav-item .nav-link:hover {
    color: #ffb800 !important;
}

.btn-warning {
    color: #000;
    background-color: #ffc107;
    border-color: #ffc107;
    font-weight: 600;
}
/* ===============================
   STICKY HAUSLA NAVBAR
================================ */
.navbar {
  position: sticky;
  top: 0;
  z-index: 9999;
}

/* thoda premium feel */

/* ===== HAUSLA STICKY MENU ===== */
.hausla-sticky-wrap{
  position: sticky;
  top: 0;              /* viewport ke top par chipkega */
  z-index: 998;        /* main header se niche */
  background: #fff;    /* overlap ke time transparent na ho */
}

/* navbar look */
.hausla-navbar{
  padding:12px 0;
  box-shadow:0 4px 12px rgba(0,0,0,0.08);
}

/* pills style */
.hausla-navbar .nav-link{
  background:#1f2b4f;
  color:#fff !important;
  padding:8px 18px;
  border-radius:30px;
  margin:4px;
  font-size:14px;
}

.hausla-navbar .nav-link:hover{
  background:#f6b300;
  color:#000 !important;
}

</style>


</head>

<body>


   <?php include 'includes/header.php'; ?>
   	<!-- Start Page Title Area -->
<div class="page-title-wave" style="    padding: 110px 0 110px;">
  <div class="container">
    <h2> Hausla</h2>
  </div>

  
</div>

<!-- ===============================
 HAUSLA HEADER / MENU
================================ -->

<nav class="navbar navbar-expand-lg navbar-dark  " >
  <div class="container">

    <!-- Logo -->
    <a class="" href="hausla.php">
     <img src="assets/img/hausla-logo.png" alt="Hausla Logo" style="width:120px ;" class="img-fluid">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#hauslaMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="hauslaMenu">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

        <li class="nav-item">
          <a class="nav-link" href="#overview">Overview</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="#services">Our Services</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="#team">Our Team</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="#appointment">Book Appointment</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="#eventsreport">Event Reports </a>
        </li>
         <li class="nav-item">
          <a class="nav-link" href="#gallery">Gallery</a>
        </li>

        <!-- Back to Main Site -->
        <li class="nav-item ms-lg-3">
          <a class="btn btn-warning btn-sm" href="book-appointment.php">
           Book Appointment
          </a>
        </li>

      </ul>
    </div>
  </div>
</nav>




<!-- ===============================
 HERO
================================ -->
<section class="hausla-hero">
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
</section>

<!-- ===============================
 OVERVIEW
================================ -->

<div class="py-5" id="overview">
<section class="hausla-overview py-5 ">
  <div class="container">

    <div class=" mb-5">
      <h2 class="fw-bold text-primary">HAUSLA – Early Intervention & Neuro-Psychological Rehabilitation Center, Lucknow</h2>
      <p class="  mx-auto ">
        HAUSLA is an initiative of IPYF Trust, registered with the Department of Empowerment of Persons with Disabilities, Government of Uttar Pradesh.
        The center provides evidence-based Early Intervention, Neuro-Psychological Assessment, Behaviour Therapy, Speech Therapy, Occupational Therapy, 
        Counselling, Day Care Services and Rehabilitation programs for children and adults with developmental, behavioural and psychological challenges.
      </p>
    </div>

    <div class="row align-items-center">
      <div class="col-lg-4">
        <img src="assets/img/hausla-img.jpeg" class="img-fluid rounded-4 ">
      </div>

 <div class="col-lg-8">

  <!-- Mission Section -->
  <section class="mb-5">
    <h3 class="fw-bold text-primary">Our Mission</h3>
    <p class=" ">
      At HAUSLA, our mission is to provide early identification and intervention for children with developmental challenges. 
      We focus on holistic treatment through evidence-based therapies, personalized care plans, and continuous rehabilitation support. 
      By addressing cognitive, emotional, social, and behavioral development, we strive to enhance the overall quality of life for every individual, 
      empowering them to reach their full potential in a safe, nurturing, and structured environment.
    </p>
  </section>

  <!-- Vision Section -->
   <section class="mb-5">
    <h3 class="fw-bold text-primary">Our Vision</h3>
    <p class=" ">
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
</div>

<!-- ===============================
 SERVICES
================================ -->
<div class="py-5 bg-light" id="services">


<section class="py-5 ">
  <div class="container">
     <div class="section-head text-center mb-5">
      <h2>Our <span>Services</span></h2>
    </div>
    <div class="row g-4">
      <!-- Early Intervention -->
      <div class="col-md-4">
        <div class="service-card text-center">
          <i class="bx bxs-sun service-icon"></i>
          <h4 class="fw-bold">Early Intervention</h4>
          <p>
            Structured programs designed for children with developmental delays, Autism (ASD), ADHD and speech delay.
          </p>

          <div id="read1" class="read-more-content">
            <p>
              The focus is on improving social interaction, attention, communication, cognitive growth and school readiness skills.
            </p>
          </div>
<a href="art-therapy.php"
   class="btn btn-primary mt-3">
  Read More
</a>
        </div>
      </div>

      <!-- Speech Therapy -->
      <div class="col-md-4">
        <div class="service-card text-center">
          <i class="bx bxs-microphone service-icon"></i>
          <h4 class="fw-bold">Speech Therapy</h4>
          <p>
            Speech clarity, articulation training, language development and communication skill enhancement.
          </p>

          <div id="read2" class="read-more-content">
            <p>
              Therapy supports both children and adults facing stammering, delayed speech, unclear speech and language disorders.
            </p>
          </div>
<a href="art-therapy.php"
   class="btn btn-primary mt-3">
  Read More
</a>
        </div>
      </div>

      <!-- Occupational Therapy -->
      <div class="col-md-4">
        <div class="service-card text-center">
          <i class="bx bxs-hand service-icon"></i>
          <h4 class="fw-bold">Occupational Therapy</h4>
          <p>
            Sensory integration, fine motor skills, handwriting improvement and daily living skills training.
          </p>

          <div id="read3" class="read-more-content">
            <p>
              Helps children improve coordination, balance, attention and independence in routine activities.
            </p>
          </div>
<a href="art-therapy.php"
   class="btn btn-primary mt-3">
  Read More
</a>
        </div>
      </div>

      <!-- Behaviour Therapy -->
      <div class="col-md-4">
        <div class="service-card text-center">
          <i class="bx bxs-happy service-icon"></i>
          <h4 class="fw-bold">Behaviour Therapy</h4>
          <p>
            Behaviour modification plans for aggression, hyperactivity, tantrums and social skill difficulties.
          </p>

          <div id="read4" class="read-more-content">
            <p>
              Includes reinforcement-based strategies and emotional regulation techniques for long-term improvement.
            </p>
          </div>
<a href="art-therapy.php"
   class="btn btn-primary mt-3">
  Read More
</a>
        </div>
      </div>

      <!-- Psychological Assessment -->
      <div class="col-md-4">
        <div class="service-card text-center">
          <i class="bx bxs-brain service-icon"></i>
          <h4 class="fw-bold">Psychological Assessment</h4>
          <p>
            IQ testing, developmental assessment, behaviour rating scales and diagnostic evaluation.
          </p>

          <div id="read5" class="read-more-content">
            <p>
              Useful for identifying learning disabilities, emotional issues and developmental challenges.
            </p>
          </div>
<a href="art-therapy.php"
   class="btn btn-primary mt-3">
  Read More
</a>
        </div>
      </div>

      <!-- Neuro Psychological Assessment -->
      <div class="col-md-4">
        <div class="service-card text-center">
          <i class="bx bxs-analyse service-icon"></i>
          <h4 class="fw-bold">Neuro-Psychological Assessment</h4>
          <p>
            Evaluation of memory, attention, cognitive functions and executive functioning skills.
          </p>

          <div id="read6" class="read-more-content">
            <p>
              Helpful for identifying neurodevelopmental disorders, attention deficits and cognitive imbalance.
            </p>
          </div>

        <a href="art-therapy.php"
   class="btn btn-primary mt-3">
  Read More
</a>
        </div>
      </div>

      <!-- Counselling & Psychotherapy -->
      <div class="col-md-4">
        <div class="service-card text-center">
          <i class="bx bxs-comment-dots service-icon"></i>
          <h4 class="fw-bold">Counselling & Psychotherapy</h4>
          <p>
            Emotional support, family counselling, child therapy and stress/anxiety management.
          </p>

          <div id="read7" class="read-more-content">
            <p>
              Helps children and parents cope with emotional challenges, behavioural issues and relationship difficulties.
            </p>
          </div>
<a href="art-therapy.php"
   class="btn btn-primary mt-3">
  Read More
</a>
        </div>
      </div>

      <!-- Day Care -->
      <div class="col-md-4">
        <div class="service-card text-center">
          <i class="bx bxs-home-heart service-icon"></i>
          <h4 class="fw-bold">Day Care Program</h4>
          <p>
            Structured day-care with supervised learning, therapy support and behavioural guidance.
          </p>

          <div id="read8" class="read-more-content">
            <p>
              Ideal for children needing continuous developmental engagement and a safe, friendly environment.
            </p>
          </div>
<a href="art-therapy.php"
   class="btn btn-primary mt-3">
  Read More
</a>
        </div>
      </div>

    </div>

  </div>
</section>
</div>

<!-- ===============================
 TEAM
================================ -->
<div class="py-5" id="team">
<section class="hausla-team py-5 ">
  <div class="container">
      <div class="section-head text-center mb-5">
      <h2>Our <span>Team</span></h2>
    </div>
    <div class="row g-4">

      <!-- Team Member 1 -->
      <div class="col-md-4">
        <div class="team-card bg-white shadow-lg p-4 rounded-4 text-center h-100">
          <img src="assets/img/Dr-Garima Singh.jpeg" class="rounded-circle mb-3 border border-3 border-primary" width="150">

          <h4 class="fw-bold mb-1">Dr. Riya Sharma</h4>
          <h6 class="text-primary mb-2">Clinical Psychologist</h6>


        </div>
      </div>

      <!-- Team Member 2 -->
      <div class="col-md-4">
        <div class="team-card bg-white shadow-lg p-4 rounded-4 text-center h-100">
          <img src="assets/img/Dr-Garima Singh.jpeg" class="rounded-circle mb-3 border border-3 border-success" width="150">

          <h4 class="fw-bold mb-1">Amit Verma</h4>
          <h6 class="text-success mb-2">Occupational Therapist</h6>

        </div>
      </div>

      <!-- Team Member 3 -->
      <div class="col-md-4">
        <div class="team-card bg-white shadow-lg p-4 rounded-4 text-center h-100">
          <img src="assets/img/Dr-Garima Singh.jpeg" class="rounded-circle mb-3 border border-3 border-danger" width="150">

          <h4 class="fw-bold mb-1">Neha Singh</h4>
          <h6 class="text-danger mb-2">Speech Therapist</h6>

        </div>
      </div>

    </div>

  </div>
</section>

</div>

<!-- ===============================
 CTA
================================ -->
<section class="hausla-cta" id="appointment">
  <h2>Book an Appointment</h2>
  <p>Start your journey towards care & recovery today</p>
  <a href="book-appointment.php" class="btn btn-light mt-3">Book Now</a>
</section>

<div  id="eventsreport">
 <!-- ================= EVENT REPORTS SECTION ================= -->
<section class="event-reports py-5">
  <div class="container">
     <div class="section-head text-center mb-5">
      <h2>Events <span>Report</span></h2>
      <p>Moments captured from our journeys and events</p>
    </div>
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
</div>

<div id="gallery">
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