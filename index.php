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
    <title>INDIAN MENTAL HEALTH AND RESEARCH CENTRE (IMHRC)</title>
    <style>
    .feature-box img {
    width: 100%;
    height: 100px;
    object-fit: cover;
    /* margin-bottom: 12px; */
}
      .expert-panel {
  position: fixed;
  right: -360px;
  top: 0;
  width: 360px;
  height: 100vh;
  background: #fff;
  box-shadow: -10px 0 30px rgba(0,0,0,.15);
  transition: 0.4s;
  z-index: 10000;
  display: flex;
  flex-direction: column;   /* ✅ important */
}

/* Header fixed height */
.expert-header {
  flex-shrink: 0;
}

/* Body scrollable */
.expert-body {
  padding: 20px;
  overflow-y: auto;         /* ✅ scrolling */
 
}

      /* Sticky Tab */
.expert-tab {
  position: fixed;
  right: -52px;
  top: 50%;
  transform: rotate(-90deg);
  background: #2563eb;
  color: #fff;
  padding: 10px 18px;
  font-weight: bold;
  border-radius: 8px 8px 0 0;
  cursor: pointer;
  z-index: 9999;
}

/* Panel */
.expert-panel {
  position: fixed;
  right: -360px;
  top: 0;
  width: 360px;
  height: 100vh;
  background: #fff;
  box-shadow: -10px 0 30px rgba(0,0,0,.15);
  transition: 0.4s;
  z-index: 10000;
}

.expert-panel.open {
  right: 0;
}

/* Header */
.expert-header {
  background: #ffb800;
  color: white;
  padding: 15px;
  display: flex;
  justify-content: space-between;
}

/* Body */
.expert-body {
  padding: 20px;
}

.expert-body input,
.expert-body select,
.expert-body textarea {
  width: 100%;
  margin-bottom: 10px;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 6px;
}

.expert-body button {
    width: 100%;
    background: #ffb800;
    color: #000000;
    border: none;
    padding: 12px;
    border-radius: 30px;
    font-weight: bold;
}

    </style>
    <style>
  
    .blog-section { padding: 60px 15px;       background: #fff4d999; }
    .blog-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
    .blog-header h2 { font-weight: 800; font-size: 2rem; color: #1a1a1a; }

    .slider-btns button {
      border: none; border-radius: 50%; width: 45px; height: 45px; color: #fff;
      font-size: 1.2rem; cursor: pointer; transition: transform 0.3s, opacity 0.3s;
    }
    .slider-btns button:hover { transform: scale(1.1); opacity: 0.8; }

    .blog-slider { display: flex; overflow-x: auto; scroll-behavior: smooth; gap: 20px; padding-bottom: 20px; }
    .blog-slider::-webkit-scrollbar { display: none; }

    .blog-card {
      flex: 0 0 320px; background: #fff; border-radius: 20px;
      box-shadow: 0 8px 25px rgb(199 190 190 / 10%);
      transition: transform 0.3s, box-shadow 0.3s;
    }
    .blog-card:hover { transform: translateY(-8px); box-shadow: 0 12px 30px rgba(0,0,0,0.15); }

    .blog-card img { width: 100%; height: 180px; object-fit: cover; border-top-left-radius: 20px; border-top-right-radius: 20px; }
    .blog-card-body { padding: 20px; }
    .blog-card-body h5 { font-size: 1.2rem; margin-bottom: 10px; color: #333; }
    .blog-card-body p { font-size: 0.95rem; color: #555; line-height: 1.5; }

    .read-more { display: inline-block; margin-top: 12px; text-decoration: none; color: #fff; padding: 8px 18px; border-radius: 50px; font-weight: 500; transition: background 0.3s; }
    .all-blogs { text-align: center; margin-top: 30px; }
    .all-blogs a { text-decoration: none; color: #fff; padding: 12px 30px; border-radius: 50px; font-weight: 600; transition: background 0.3s; }

    /* Specific gradients */
  .imhrc .slider-btns button {
    background: linear-gradient(45deg, #ffb800, #ffb800);
}
   .imhrc .read-more {
    background: linear-gradient(45deg, #ffb800, #ffb800);
    color: #1d274b;
    font-weight: 600;
}
  .imhrc .all-blogs a {
    background: linear-gradient(45deg, #ffb800, #ffb800);
    color: #1d274b;
}

.psychology .slider-btns button {
    background: linear-gradient(45deg, #ffb800, #ffb800);
}
 .psychology .read-more {
    background: linear-gradient(45deg, #ffb800, #ffb800);
    color: #1d274b;
    font-weight: 600;
}
  .psychology .all-blogs a {
    background: linear-gradient(45deg, #ffb800, #ffb800);
    color: #1d274b;
}

    @media (max-width: 768px) { .blog-card { flex: 0 0 80%; } }
    @media (max-width: 480px) { .blog-header { flex-direction: column; gap: 15px; text-align: center; } .slider-btns { justify-content: center; } }
 .appointment-area {
  padding: 100px 0;
  background: linear-gradient(135deg, #f3f7ff, #fdfefe);
}

.appointment-form {
  background: #ffffff;
  max-width: 850px;
  margin: auto;
  padding: 50px 40px;
  border-radius: 24px;
  box-shadow: 0 20px 60px rgba(0,0,0,0.08);
}

.appointment-title span {
  color: #6a11cb;
  font-weight: 600;
  font-size: 14px;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.appointment-title h2 {
  font-weight: 800;
  margin: 8px 0;
  font-size: 32px;
}

.appointment-title p {
  color: #666;
  margin-bottom: 40px;
}

.appointment-form input,
.appointment-form textarea {
  width: 100%;
  border: 1.5px solid #e5e7eb;
  border-radius: 14px;
  padding: 14px 18px;
  font-size: 15px;
  background: #fafafa;
  transition: all 0.3s ease;
  outline: none;
}

/* REMOVE BLUE FOCUS */
.appointment-form input:focus,
.appointment-form textarea:focus {
  border-color: #6a11cb;
  background: #ffffff;
  box-shadow: 0 0 0 4px rgba(106,17,203,0.12);
}

.appointment-form textarea {
  resize: none;
}

/* Button */
.appointment-btn {
  margin-top: 15px;
  padding: 14px 40px;
  border: none;
  color: #fff;
  font-weight: 600;
  border-radius: 50px;
  background: linear-gradient(45deg, #6a11cb, #2575fc);
  transition: all 0.4s ease;
}

.appointment-btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 25px rgba(37,117,252,0.35);
}

/* Mobile */
@media (max-width: 576px) {
  .appointment-form {
    padding: 35px 20px;
  }
  .appointment-title h2 {
    font-size: 26px;
  }
}

 /* SECTION BACKGROUND */
.about-area {
    /* background: linear-gradient(135deg, #f9fbff, #e4efff); */
        background: #fff4d999;
}

/* LIGHT DECORATIVE SHAPES */




.about-area .container {
    position: relative;
    z-index: 2;
}

/* IMAGE STYLING */
.about-img img {
    width: 100%;
    border-radius: 15px;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
    transition: all 0.4s ease;
}

.about-img img:hover {
    transform: scale(1.03);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.18);
}

/* CONTENT */
.about-content h2 {
    font-size: 34px;
    font-weight: 800;
background: linear-gradient(90deg, #ffb800, #ffb800);
    -webkit-background-clip: text;
    color: transparent;
    margin-bottom: 20px;
}

.about-content p {
    font-size: 17px;
    color: #444;
    line-height: 1.7;
    margin-bottom: 25px;
}

/* BUTTON */
.about-content .default-btn {
    background: linear-gradient(90deg, #005bea, #00c6fb);
    border-radius: 30px;
    padding: 12px 28px;
    color: #fff;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 10px 25px rgba(0, 148, 255, 0.3);
    transition: all 0.3s ease;
}

.about-content .default-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(0, 148, 255, 0.45);
}


/* RESET BASIC */


/* SECTION */
.booking-section {
  padding: 80px 20px;
  background: #ffffff;
}



/* MAIN GRID */
.booking-grid {
  display: grid;
  grid-template-columns: 2fr 1.5fr;
  gap: 40px;
}

/* ================= LEFT CARDS ================= */
.left-cards {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 22px;
}

/* INFO CARD */
.info-card {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 26px;
  border-radius: 16px;
  /* box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06); */
  transition: all 0.3s ease;
  text-decoration: none;
  color: inherit;
}

.info-card:hover {
  transform: translateY(-3px);
}

/* TEXT */
.info-card h4 {
  font-size: 25px;
  font-weight: 600;
  margin-bottom: 6px;
}

.info-card p {
  font-size: 14px;
  color: #555;
  line-height: 1.4;
}

/* ICON CIRCLE */
.info-card .icon {
    width: 75px;
    height: 75px;
  background: #ffffff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.info-card .icon img {
  width: 50px;
  height: auto;
}

/* COLOR VARIANTS */
.green {
  /* background: #f4fae8; */
      background: #FDFFF4;
    border: 2px solid #F1F5DE;
}

.blue {
  /* background: #eef6ff; */
      background: #EDFAFF;
    border: 2px solid #D1F1FF;
}

.purple {
  /* background: #f3f1ff; */
      background: #F8F7FF;
    border: 2px solid #E9E6FF;
}

.peach {
  /* background: #fff1ea; */
  background: #FFF6F4;
    border: 2px solid #F7E9E5;
}
.grey {
    /* background: #f4fae8; */
    background: #fafaf8;
    border: 2px solid #eff1e5;
}
.yellow {
    /* background: #eef6ff; */
    background: #fff8e4;
    border: 2px solid #efe4c2;
}
/* ================= RIGHT SIDE ================= */
.right-cards {
  display: flex;
  flex-direction: column;
}

.right-cards h3 {
  font-size: 26px;
  font-weight: 600;
  margin-bottom: 24px;
}

/* BIG CARD */
.big-card {
  border: 2px solid #ccefe3;
  border-radius: 18px;
  padding: 36px 20px;
  background: #f6fffb;
  text-align: center;
  margin-bottom: 22px;
  transition: all 0.3s ease;
  text-decoration: none;
  color: inherit;
}

.big-card:hover {
  transform: scale(1.04);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
}

.big-card img {
  width: 44px;
  margin-bottom: 14px;
}

.big-card h4 {
  font-size: 18px;
  font-weight: 600;
}

/* ================= ACCESSIBILITY ================= */
.info-card:focus-visible,
.big-card:focus-visible {
  outline: 2px solid #2575fc;
  outline-offset: 4px;
}

/* ================= RESPONSIVE ================= */
@media (max-width: 1024px) {
  .booking-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 600px) {
  .left-cards {
    grid-template-columns: 1fr;
  }

  .right-cards h3 {
    text-align: center;
  }
}


 </style>

</head>

<body>


   <?php include 'includes/header.php'; ?>

    <!-- Start Hero Slider Area -->
 <section class="hero-slider-area">
    <div class="hero-slider owl-carousel owl-theme">

        <!-- SLIDE 1 -->
        <div class="slider-item slider-item-bg-1">
            <div class="d-table">
                <div class="d-table-cell">
                    <div class="container">
                        <div class="slider-text">
                            <h1>Leading Centre for Mental Health & Psychological Well-Being</h1>
                            <p>Providing compassionate, ethical, and research-driven mental health services for all age groups.</p>

                            <div class="slider-btn">
                                <a class="default-btn" href="book-appointment.php"><span>Book Appointment</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SLIDE 2 -->
        <div class="slider-item slider-item-bg-2">
            <div class="d-table">
                <div class="d-table-cell">
                    <div class="container">
                        <div class="slider-text">
                            <h1>Comprehensive Psychological Assessments</h1>
                            <p>Scientific and standardized psychological testing for children, adolescents, and adults.</p>

                            <div class="slider-btn">
                                <a class="default-btn" href="book-appointment.php"><span>Book Appointment</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SLIDE 3 -->
        <div class="slider-item slider-item-bg-3">
            <div class="d-table">
                <div class="d-table-cell">
                    <div class="container">
                        <div class="slider-text">
                            <h1>Empowering India Through Mental Wellness</h1>
                            <p>We envision a society where mental health is accessible, stigma-free, and supported by science.</p>

                            <div class="slider-btn">
                                <a class="default-btn" href="book-appointment.php"><span>Book Appointment</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SLIDE 4 -->
        <div class="slider-item slider-item-bg-4">
            <div class="d-table">
                <div class="d-table-cell">
                    <div class="container">
                        <div class="slider-text">
                            <h1>Delivering Ethical & Evidence-Based Therapy</h1>
                            <p>Our mission is to enhance emotional well-being through high-quality clinical services and community outreach.</p>

                            <div class="slider-btn">
                                <a class="default-btn" href="book-appointment.php"><span>Book Appointment</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SLIDE 5 -->
        <div class="slider-item slider-item-bg-5">
            <div class="d-table">
                <div class="d-table-cell">
                    <div class="container">
                        <div class="slider-text">
                            <h1>Psychotherapy & Counselling Sessions</h1>
                            <p>Providing CBT, DBT, family therapy, child counselling, and adult psychotherapy.</p>

                            <div class="slider-btn">
                                <a class="default-btn" href="book-appointment.php"><span>Book Appointment</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SLIDE 6 -->
        <div class="slider-item slider-item-bg-6">
            <div class="d-table">
                <div class="d-table-cell">
                    <div class="container">
                        <div class="slider-text">
                            <h1>Internships & Psychology Certifications</h1>
                            <p>Hands-on training for students, counsellors, and emerging mental health professionals.</p>

                            <div class="slider-btn">
                                <a class="default-btn" href="book-appointment.php"><span>Book Appointment</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SLIDE 7 -->
        <div class="slider-item slider-item-bg-7">
            <div class="d-table">
                <div class="d-table-cell">
                    <div class="container">
                        <div class="slider-text">
                            <h1>Advancing Psychological Science</h1>
                            <p>Our team contributes to national and international mental health research initiatives.</p>

                            <div class="slider-btn">
                                <a class="default-btn" href="book-appointment.php"><span>Book Appointment</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SLIDE 8 -->
        <div class="slider-item slider-item-bg-8">
            <div class="d-table">
                <div class="d-table-cell">
                    <div class="container">
                        <div class="slider-text">
                            <h1>Mental Health Awareness & Support</h1>
                            <p>Workshops, school programs, counselling camps, and community mental health training.</p>

                            <div class="slider-btn">
                                <a class="default-btn" href="book-appointment.php"><span>Book Appointment</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<section class="latest-news-section py-3">
    <div class="container">

        <div class="news-wrapper">
            <strong class="news-title">LATEST NEWS</strong>

            <div class="news-marquee" id="newsBox">
                <div class="news-track" id="newsTrack">

                    <!-- NEWS 1 -->
                    <div class="single-news">
                        <span class="new-badge blink">NEW</span>
                        7th International Conference on Mental Healthcare & Rehabilitation | National & Global Imperatives  
                        <span class="date">Posted: 2025-03-05</span>
                    </div>

                    <!-- NEWS 2 -->
                    <div class="single-news">
                        <span class="new-badge blink">NEW</span>
                        National Mental Health Workshop Announced
                        <span class="date">Posted: 2025-04-12</span>
                    </div>

                    <!-- NEWS 3 -->
                    <div class="single-news">
                        IMHRC Launches Community Health Training Program
                        <span class="date">Posted: 2024-12-10</span>
                    </div>

                </div>
            </div>

        </div>

    </div>
</section>

    <!-- End Hero Slider Area -->
<section class="booking-section">
  <div class="container">

    <div class="row g-4">

      <!-- CARD 1 -->
      <div class="col-lg-4 col-md-6">
        <a href="expert.php" class="info-card green">
          <div class="text">
            <h4>Book an Appointment</h4>
            <p>With country's leading experts</p>
          </div>
          <div class="icon">
            <img src="assets/img/appointment.png" alt="Book Appointment">
          </div>
        </a>
      </div>

      <!-- CARD 2 -->
      <div class="col-lg-4 col-md-6">
        <a href="clinical-&-diagnostic-services.php" class="info-card blue">
          <div class="text">
            <h4>Services</h4>
            <p>Health needs under one roof</p>
          </div>
          <div class="icon">
            <img src="assets/img/services.png" alt="Services">
          </div>
        </a>
      </div>

      <!-- CARD 3 -->
      <div class="col-lg-4 col-md-6">
        <a href="internships-&-training.php" class="info-card purple">
          <div class="text">
            <h4>Internships & Training</h4>
            <p>Our expertise in Healthcare</p>
          </div>
          <div class="icon">
            <img src="assets/img/leader.png" alt="Specialities">
          </div>
        </a>
      </div>

      <!-- CARD 4 -->
      <div class="col-lg-4 col-md-6">
        <a href="research-&-publications.php" class="info-card peach">
          <div class="text">
            <h4>Research & Publications</h4>
            <p>Top experts for your health</p>
          </div>
          <div class="icon">
            <img src="assets/img/medical-team.png" alt="Doctors">
          </div>
        </a>
      </div>

      <!-- CARD 5 -->
      <div class="col-lg-4 col-md-6">
        <a href="academic-programs.php" class="info-card grey">
          <div class="text">
            <h4>Programs</h4>
            <p>Academic & professional programs</p>
          </div>
          <div class="icon">
            <img src="assets/img/software.png" alt="Programs">
          </div>
        </a>
      </div>

      <!-- CARD 6 -->
      <div class="col-lg-4 col-md-6">
        <a href="articles-blogs.php" class="info-card yellow">
          <div class="text">
            <h4>Departments</h4>
            <p>Specialized healthcare departments</p>
          </div>
          <div class="icon">
            <img src="assets/img/networking.png" alt="Departments">
          </div>
        </a>
      </div>

    </div>

  </div>
</section>


    <!-- Start About Area -->
 <section class="about-area pt-100 pb-70">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-6">
                <div class="about-img">
                    <img src="assets/img/unnamed5.jpg" alt="IMHRC Institutional Image">

                </div>
            </div>

            <div class="col-lg-6">
                <div class="about-content">
                    <h2>About Us</h2>
                    <p>
                        IMHRC is a premier mental health and rehabilitation institute dedicated to psychological wellbeing,
                        evidence-based therapy, clinical services, and community mental health awareness across India. Our
                        focus spans from children and adolescents to adults and geriatrics — offering comprehensive support
                        for emotional, cognitive, behavioural, and rehabilitation needs.
                    </p>

                    <a href="about-overview.php" class="default-btn">
                        <span>Learn More </span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

    <!-- End About Area -->

<!-- <section class="search-section py-5">
    <div class="container">

        <div class="text-center mb-5">
            <h2 class="fw-bold display-6">Search by Service </h2>
            <p class="text-muted fs-5">Find what you need quickly and easily</p>
        </div>

        <div class="row justify-content-center mb-4">
            <div class="col-lg-8">
                <div class="search-bar d-flex">
                    <input type="text" class="form-control search-input" placeholder="Search any service, program, department or expert...">
                    <button class="btn btn-primary search-btn">
                        <i class="bx bx-search"></i>
                    </button>
                </div>
            </div>
        </div>

    
        <div class="row g-4">

         
            <div class="col-lg-3 col-md-6">
                <div class="search-box p-4 text-center">
                    <div class="icon mb-3">
                        <i class="bx bx-cog"></i>
                    </div>
                    <h5 class="fw-bold">Services</h5>
                    <p class="text-muted small">Explore all public services</p>
                </div>
            </div>

          
            <div class="col-lg-3 col-md-6">
                <div class="search-box p-4 text-center">
                    <div class="icon mb-3">
                        <i class="bx bx-layer"></i>
                    </div>
                    <h5 class="fw-bold">Programs</h5>
                    <p class="text-muted small">Government schemes & initiatives</p>
                </div>
            </div>

         
            <div class="col-lg-3 col-md-6">
                <div class="search-box p-4 text-center">
                    <div class="icon mb-3">
                        <i class="bx bxs-bank"></i>
                    </div>
                    <h5 class="fw-bold">Departments</h5>
                    <p class="text-muted small">All government departments</p>
                </div>
            </div>

          
            <div class="col-lg-3 col-md-6">
                <div class="search-box p-4 text-center">
                    <div class="icon mb-3">
                        <i class="bx bxs-user-voice"></i>
                    </div>
                    <h5 class="fw-bold">Experts</h5>
                    <p class="text-muted small">Find verified professionals</p>
                </div>
            </div>

        </div>
    </div>
</section> -->

    <section class="impact-section py-5">
        <div class="container">

            <!-- Title -->
            <div class="text-center mb-5">
                <h2 class="impact-title">Success Stories & Impact Statistics</h2>
                <p class="impact-sub">Real Outcomes. Real Transformations.</p>
            </div>

            <!-- Row 1 -->
            <div class="impact-grid mb-5">

                <div class="impact-box">
                    <h3>1K+</h3>
                    <h4>Lives Transformed</h4>
                </div>

                <div class="impact-box">
                    <h3>15+</h3>
                    <h4>Specialized Clinical Services</h4>
                </div>

                <div class="impact-box">
                    <h3>500+</h3>
                    <h4>Students Trained Annually</h4>
                </div>

                <div class="impact-box">
                    <h3>50+</h3>
                    <h4>Collaborations</h4>
                </div>

            </div>



        </div>
    </section>


 

<!-- IMHRC Section -->
<section class="blog-section  imhrc">
    <div class="container">
          <div class="blog-header">
    <h2>Articles & Blogs
</h2>
    <div class="slider-btns">
      <button id="prevIMHRC">&#8592;</button>
      <button id="nextIMHRC">&#8594;</button>
    </div>
  </div>

  <div class="blog-slider" id="sliderIMHRC">
    <div class="blog-card">
      <img src="assets/img/artical1.jpeg" alt="Child" class="img-blog">
      <div class="blog-card-body">
        <h5>Depression Amid COVID-19: Understanding Mental Health in Crisis</h5>
        <p>This webinar focused on how the COVID-19 pandemic affected emotional well-being, increasing feelings of isolation, fear, and uncertainty.</p>
        <a href="#" class="read-more">Read More</a>
      </div>
    </div>
    <div class="blog-card">
      <img src="assets/img/artical2.jpeg" alt="Adolescent" class="img-blog">
      <div class="blog-card-body">
        <h5>Anxiety Amid COVID-19: Coping with Uncertainty and Fear</h5>
        <p>This session addressed the rising levels of anxiety during the pandemic, especially related to health concerns, job insecurity.</p>
        <a href="#" class="read-more">Read More</a>
      </div>
    </div>
    <div class="blog-card">
      <img src="assets/img/blog1.jpeg" alt="Adult" class="img-blog">
      <div class="blog-card-body">
        <h5>International Conference on Bio-Psychosocial Perspectives of Trauma</h5>
        <p>This international conference brought together mental health professionals, researchers, and academicians to discuss trauma from biologica.</p>
        <a href="#" class="read-more">Read More</a>
      </div>
    </div>
    <div class="blog-card">
      <img src="assets/img/blog2.jpeg" alt="Geriatric" class="img-blog">
      <div class="blog-card-body">
        <h5>Community Mental Health Awareness and Outreach Program</h5>
        <p>This image represents a community-based mental health awareness and screening camp aimed at reaching people at the grassroots level.</p>
        <a href="#" class="read-more">Read More</a>
      </div>
    </div>
  </div>

  <div class="all-blogs"><a href="#">View All Blogs</a></div>
    </div>

</section>



 <section class="partner-section py-5">
        <div class="container">
            <h2 class="section-title">
               Affiliation and Associations
            </h2>
            <div class="feature-marquee">
                <div class="feature-track">

                    <div class="feature-box">
                        <img src="assets/img/our-affiliations/1.jpg" alt="">
                    </div>
                    <div class="feature-box">
                        <img src="assets/img/our-affiliations/2.jpg" alt="">
                    </div>
                    <div class="feature-box">
                        <img src="assets/img/our-affiliations/3.jpg" alt="">
                    </div>
                     <div class="feature-box">
                        <img src="assets/img/our-affiliations/19.jpg" alt="">
                    </div>
                     <div class="feature-box">
                        <img src="assets/img/our-affiliations/20.jpg" alt="">
                    </div>
                     <div class="feature-box">
                        <img src="assets/img/our-affiliations/21.jpg" alt="">
                    </div>
                    <div class="feature-box">
                        <img src="assets/img/our-affiliations/4.jpg" alt="">
                    </div>
                    <div class="feature-box">
                        <img src="assets/img/our-affiliations/5.jpg" alt="">
                    </div>
                    <div class="feature-box">
                        <img src="assets/img/our-affiliations/6.jpg" alt="">
                    </div>
                    <div class="feature-box">
                        <img src="assets/img/our-affiliations/7.jpg" alt="">
                    </div>
                    <div class="feature-box">
                        <img src="assets/img/our-affiliations/8.jpg" alt="">
                    </div>
                    <div class="feature-box">
                        <img src="assets/img/our-affiliations/9.jpg" alt="">
                    </div>
                    <div class="feature-box">
                        <img src="assets/img/our-affiliations/10.jpg" alt="">
                    </div>
                    <div class="feature-box">
                        <img src="assets/img/our-affiliations/11.jpg" alt="">
                    </div>
                    <div class="feature-box">
                        <img src="assets/img/our-affiliations/12.jpg" alt="">
                    </div> 
                     <div class="feature-box">
                        <img src="assets/img/our-affiliations/13.jpg" alt="">
                    </div> 
                     <div class="feature-box">
                        <img src="assets/img/our-affiliations/14.jpg" alt="">
                    </div> 
                     <div class="feature-box">
                        <img src="assets/img/our-affiliations/15.jpg" alt="">
                    </div> 
                     <div class="feature-box">
                        <img src="assets/img/our-affiliations/16.jpg" alt="">
                    </div> 
                     <div class="feature-box">
                        <img src="assets/img/our-affiliations/17.jpg" alt="">
                    </div> 
                     <div class="feature-box">
                        <img src="assets/img/our-affiliations/18.jpg" alt="">
                    </div> 
                </div>
            </div>

        </div>
    </section> 

<!-- Psychology Section -->
<section class="blog-section  psychology">
    <div class="container">
          <div class="blog-header">
    <h2>Latest News, Events & Announcements</h2>
    <div class="slider-btns">
      <button id="prevPsych">&#8592;</button>
      <button id="nextPsych">&#8594;</button>
    </div>
  </div>

  <div class="blog-slider" id="sliderPsych">
    <div class="blog-card">
      <img src="assets/img/events1.jpeg" alt="Article 1">
      <div class="blog-card-body">
        <h5>Trauma Focused Acceptance and Commitment Therapy</h5>
        <p>Trauma Focused Acceptance and Commitment Therapy is a modern therapeutic approach that helps individuals.</p>
        <a href="#" class="read-more">Read More</a>
      </div>
    </div>
    <div class="blog-card">
      <img src="assets/img/events2.jpeg" alt="Article 2">
      <div class="blog-card-body">
        <h5>Psycho-Education for Patients with Mood Disorders</h5>
        <p>Psycho-education provides patients with mood disorders such as depression and bipolar disorder with a clear understanding.</p>
        <a href="#" class="read-more">Read More</a>
      </div>
    </div>
    <div class="blog-card">
      <img src="assets/img/events3.jpeg" alt="Article 3">
      <div class="blog-card-body">
        <h5>REBT Techniques for Management of Anxiety</h5>
        <p>Rational Emotive Behaviour Therapy (REBT) is a practical cognitive-behavioural approach for managing anxiety by identifying</p>
        <a href="#" class="read-more">Read More</a>
      </div>
    </div>
    <div class="blog-card">
      <img src="assets/img/events1.jpeg" alt="Article 4">
      <div class="blog-card-body">
        <h5>Trauma Focused Acceptance and Commitment Therapy</h5>
        <p>Trauma Focused Acceptance and Commitment Therapy is a modern therapeutic approach that helps individuals.</p>
        <a href="#" class="read-more">Read More</a>
      </div>
    </div>
  </div>

  <div class="all-blogs"><a href="#">View All Articles</a></div>
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
    $(".hero-slider").owlCarousel({
        items: 1,
        loop: true,
        autoplay: true,
        autoplayTimeout: 7000, // 7 seconds (slow)
        autoplayHoverPause: false,
        nav: true,
        dots: true,
        animateOut: "fadeOut",
        smartSpeed: 2000, // smoother slow transition
    });
</script>

<script>
  // IMHRC Slider
  const sliderIMHRC = document.getElementById('sliderIMHRC');
  document.getElementById('nextIMHRC').addEventListener('click', () => {
    sliderIMHRC.scrollBy({ left: 340, behavior: 'smooth' });
  });
  document.getElementById('prevIMHRC').addEventListener('click', () => {
    sliderIMHRC.scrollBy({ left: -340, behavior: 'smooth' });
  });
  setInterval(() => {
    if ((sliderIMHRC.scrollLeft + sliderIMHRC.clientWidth) >= sliderIMHRC.scrollWidth) sliderIMHRC.scrollTo({ left: 0, behavior: 'smooth' });
    else sliderIMHRC.scrollBy({ left: 340, behavior: 'smooth' });
  }, 4500);

  // Psychology Slider
  const sliderPsych = document.getElementById('sliderPsych');
  document.getElementById('nextPsych').addEventListener('click', () => {
    sliderPsych.scrollBy({ left: 340, behavior: 'smooth' });
  });
  document.getElementById('prevPsych').addEventListener('click', () => {
    sliderPsych.scrollBy({ left: -340, behavior: 'smooth' });
  });
  setInterval(() => {
    if ((sliderPsych.scrollLeft + sliderPsych.clientWidth) >= sliderPsych.scrollWidth) sliderPsych.scrollTo({ left: 0, behavior: 'smooth' });
    else sliderPsych.scrollBy({ left: 340, behavior: 'smooth' });
  }, 4500);
</script>


</body>


</html>