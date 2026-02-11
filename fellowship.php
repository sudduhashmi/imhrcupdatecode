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
    /* ================= GLOBAL ================= */

.fellowship-page section {
  background: #ffffff;
  margin-bottom: 40px;
  padding: 40px;
  border-radius: 16px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.05);
}

/* ================= HEADINGS ================= */
.fellowship-page h2 {
  font-size: 34px;
  font-weight: 700;
  color: #0b3c64;
  margin-bottom: 20px;
  position: relative;
}

.fellowship-page h3 {
  font-size: 26px;
  font-weight: 600;
  color: #124e89;
  margin-bottom: 20px;
}

.fellowship-page h4 {
  font-size: 20px;
  font-weight: 600;
  color: #0b3c64;
  margin-top: 20px;
}

.fellowship-page h5 {
  margin-top: 15px;
  font-size: 17px;
  color: #0b3c64;
  font-weight: 600;
}

/* ================= TEXT ================= */
.fellowship-page p {
  font-size: 16px;
  line-height: 1.8;
  color: #3e4a59;
}

.fellowship-page ul,
.fellowship-page ol {
    padding-left: 0px;
    margin-top: 10px;
    list-style: none;
}

.fellowship-page li {
  margin-bottom: 12px;
  line-height: 1.7;
}

/* ================= OFFER LIST ================= */
.fellowship-offers ul li {
  position: relative;
  padding-left: 24px;
}

.fellowship-offers ul li::before {
  content: "✔";
  position: absolute;
  left: 0;
  top: 0;
  color: #28a745;
  font-weight: bold;
}

/* ================= CATEGORIES ================= */
.fellowship-categories .fellowship-list {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 20px;
  margin-top: 20px;
}

.fellowship-item {
  background: linear-gradient(135deg, #e3f2ff, #f6fcff);
  padding: 25px;
  border-radius: 14px;
  border: 1px solid #dde8ff;
  transition: all 0.3s ease;
}

.fellowship-item:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 25px rgba(0,0,0,0.08);
}

.fellowship-item .price {
  font-size: 24px;
  font-weight: 700;
  color: #1d72f1;
  margin: 10px 0;
}

/* ================= SELECTION ================= */
.selection-process ol li {
  margin-bottom: 15px;
  font-weight: 500;
}

/* ================= DISTINGUISHED ================= */
.distinguished-fellows h4 {
  margin-top: 25px;
}

.distinguished-fellows ul li {
  background: #f2f6ff;
  padding: 10px 15px;
  border-radius: 8px;
}

/* ================= IMPACT AREAS ================= */
.impact-areas ul {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 12px;
}

.impact-areas ul li {
  background: #eef7ff;
  padding: 12px 15px;
  border-radius: 10px;
  font-weight: 500;
}

/* ================= WHY CHOOSE ================= */
.why-choose ul li {
  position: relative;
  padding-left: 22px;
}

.why-choose ul li::before {
  content: "★";
  position: absolute;
  left: 0;
  color: #ffb703;
}

/* ================= APPLY CTA ================= */
.apply-now {
  text-align: center;
  background: linear-gradient(135deg, #0b3c64, #124e89);
  color: #ffffff;
}

.apply-now h3 {
  color: #000000;
}

.apply-now p {
  color: #000000;
}

.apply-now .note {
  background: rgba(255,255,255,0.1);
  padding: 12px;
  border-radius: 10px;
  margin: 20px 0;
  font-size: 14px;
}

.btn-apply {
  display: inline-block;
  margin-top: 15px;
  padding: 14px 32px;
  background: #ffb703;
  color: #0b3c64;
  font-size: 16px;
  font-weight: 600;
  border-radius: 30px;
  text-decoration: none;
  transition: all 0.3s ease;
}

.btn-apply:hover {
  background: #ffcc33;
  transform: translateY(-3px);
}

/* ================= RESPONSIVE ================= */
@media (max-width: 768px) {
  .fellowship-page section {
    padding: 25px;
  }

  .fellowship-page h2 {
    font-size: 28px;
  }

  .fellowship-page h3 {
    font-size: 22px;
  }
}

/* ================= POPUP ================= */
.fellowship-popup {
  display: none;
  position: fixed;
  inset: 0;
  z-index: 9999;
}

/* Overlay */
.popup-overlay {
  position: absolute;
  inset: 0;
  background: rgba(0,0,0,0.65);
}

/* Popup Box */
.popup-content {
  position: relative;
  max-width: 600px;
  width: 90%;
  margin: 60px auto;
  background: #ffffff;
  padding: 30px;
  border-radius: 16px;
  box-shadow: 0 20px 50px rgba(0,0,0,0.2);
  animation: popupFade 0.4s ease;
}

/* Close Button */
.popup-close {
  position: absolute;
  top: 12px;
  right: 15px;
  background: none;
  border: none;
  font-size: 28px;
  cursor: pointer;
  color: #999;
}

/* Head */
.popup-content h3 {
  margin-bottom: 5px;
  color: #0b3c64;
}

.popup-content p {
  font-size: 14px;
  margin-bottom: 20px;
  color: #555;
}

/* Form */
.fellowship-form .form-group {
  margin-bottom: 15px;
}

.fellowship-form label {
  font-size: 14px;
  font-weight: 600;
  margin-bottom: 6px;
  display: block;
}

.fellowship-form input,
.fellowship-form textarea,
.fellowship-form select {
  width: 100%;
  padding: 12px;
  border-radius: 8px;
  border: 1px solid #ccc;
  font-size: 14px;
}

.fellowship-form textarea {
  resize: vertical;
  min-height: 90px;
}

.submit-btn {
  width: 100%;
  padding: 14px;
  background: linear-gradient(135deg, #0b3c64, #124e89);
  color: #fff;
  font-size: 16px;
  font-weight: 600;
  border-radius: 30px;
  border: none;
  cursor: pointer;
  transition: 0.3s;
}

.submit-btn:hover {
  background: linear-gradient(135deg, #124e89, #0b3c64);
}

.form-note {
  font-size: 12px;
  color: #666;
  margin-top: 12px;
  text-align: center;
}

/* Animation */
@keyframes popupFade {
  from {
    transform: scale(0.9);
    opacity: 0;
  }
  to {
    transform: scale(1);
    opacity: 1;
  }
}

.popup-content {
  position: relative;
  max-width: 600px;
  width: 90%;
  margin: 60px auto;

  /* NEW */
  max-height: 85vh;          /* screen ke andar rahe */
  overflow-y: auto;          /* vertical scrolling */

  background: #ffffff;
  padding: 30px;
  border-radius: 16px;
  box-shadow: 0 20px 50px rgba(0,0,0,0.2);
  animation: popupFade 0.4s ease;
}

/* Smooth scrollbar */
.popup-content::-webkit-scrollbar {
  width: 6px;
}

.popup-content::-webkit-scrollbar-thumb {
  background: #0b3c64;
  border-radius: 10px;
}

.popup-content::-webkit-scrollbar-track {
  background: #edf3ff;
}

   </style>
</head>

<body>


   <?php include 'includes/header.php'; ?>
   	<!-- Start Page Title Area -->
<div class="page-title-wave">
  <div class="container">
    <h2>Fellowship</h2>
  </div>

  <svg class="wave" viewBox="0 0 1440 320" preserveAspectRatio="none">
    <path fill="#ffffff" fill-opacity="1" 
      d="M0,64L48,90.7C96,117,192,171,288,170.7C384,171,480,117,576,85.3C672,53,768,43,864,74.7C960,107,1056,181,1152,192C1248,203,1344,149,1392,122.7L1440,96L1440,320L1392,320C1344,320,1248,320,
      1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
    </path>
  </svg>
</div>

<section class="container fellowship-page">

  <!-- ================= ABOUT FELLOWSHIP ================= -->
  <section class="fellowship-overview">
    <h2>About the IMHRC Fellowship Program</h2>
    <p>
      The <strong>IMHRC Fellowship Program in Mental Health</strong> is a prestigious academic and professional initiative 
      created to recognize and support exceptional <strong>students, researchers, clinicians, and mental health professionals</strong>.
      By integrating <strong>academic excellence, research exposure, and practical community engagement</strong>, the fellowship
      offers unparalleled opportunities for professional growth, scholarly contribution, and meaningful impact in 
      mental health services.
    </p>
  </section>

  <!-- ================= WHAT WE OFFER ================= -->
  <section class="fellowship-offers">
    <h3>What We Offer</h3>
    <ul>
      <li>
        <strong>Professional Certification:</strong> Enhance your academic and career prospects with a 
        globally recognized <strong>IMHRC Fellowship Certificate in Mental Health</strong>.
      </li>
      <li>
        <strong>Community Engagement:</strong> Gain hands-on experience through 
        <strong>3–6 months of volunteer service</strong> with flexible online and offline options in community mental health initiatives.
      </li>
      <li>
        <strong>Research & Publication Opportunities:</strong> Publish your work in our peer-reviewed 
        journal <strong>JARSSC</strong> with discounted publication fees and dedicated editorial support from 
        <strong>Progressive Publications</strong>.
      </li>
      <li>
        <strong>Exclusive Benefits:</strong> Avail special discounts on international mental health conferences,
        workshops, training programs, and academic events.
      </li>
    </ul>
  </section>

  <!-- ================= FELLOWSHIP CATEGORIES ================= -->
  <section class="fellowship-categories">
    <h3>Fellowship Categories & Investment</h3>

    <div class="fellowship-list">
      <div class="fellowship-item">
        <h4>6-Month Fellowship</h4>
        <p class="price">₹1,000</p>
        <p>
          Ideal for students and early-career professionals seeking introductory exposure to
          mental health research, community service, and academic development.
        </p>
      </div>

      <div class="fellowship-item">
        <h4>1-Year Fellowship</h4>
        <p class="price">₹1,500</p>
        <p>
          Designed for postgraduate students and professionals aiming for deeper involvement in
          psychological research, publication activities, and professional skill development.
        </p>
      </div>

      <div class="fellowship-item">
        <h4>5-Year Fellowship</h4>
        <p class="price">₹2,500</p>
        <p>
          Best suited for academicians, senior clinicians, and researchers committed to long-term
          engagement in mental health research, leadership, and advocacy programs.
        </p>
      </div>

      <div class="fellowship-item">
        <h4>Lifetime Fellowship</h4>
        <p class="price">₹5,000</p>
        <p>
          A prestigious lifetime recognition for individuals who have made or aspire to make
          significant contributions to psychology, psychiatry, counselling, social work, and
          community mental health.
        </p>
      </div>
    </div>
  </section>

  <!-- ================= SELECTION PROCESS ================= -->
  <section class="selection-process">
    <h3>Selection Process</h3>
    <ol>
      <li>
        <strong>Step 1:</strong> Submit your CV or professional profile for evaluation by the IMHRC expert committee.
      </li>
      <li>
        <strong>Step 2:</strong> Receive confirmation or rejection based on eligibility and committee review.
      </li>
      <li>
        <strong>Step 3:</strong> Upon acceptance, obtain your official fellowship certification and begin your journey with IMHRC.
      </li>
    </ol>
  </section>

  <!-- ================= DISTINGUISHED FELLOWS ================= -->
  <section class="distinguished-fellows">
    <h3>Our Distinguished Fellows</h3>

    <h4>Professional Fellows</h4>
    <ul>
      <li>Licensed Clinical Psychologists working at premier institutions</li>
      <li>University Professors and Department Heads specializing in psychology and mental health</li>
      <li>Published Authors and Researchers contributing to impactful studies</li>
      <li>Mental Health Advocates and Community Leaders driving social initiatives</li>
    </ul>

    <h4>Student Fellows</h4>
    <p>
      Our diverse student fellowship community represents the future of mental health services,
      with participants from leading institutions across India and internationally.
    </p>

    <h5>International Representation</h5>
    <ul>
      <li>Fathima Ayesha Badurdeen – Coventry University, United Kingdom</li>
      <li>Nawaf Ahmed Saleh Matar – Kingdom of Bahrain</li>
    </ul>

    <h5>Leading Indian Institutions</h5>
    <ul>
      <li>Christ (Deemed to be) University – Clinical psychology, behavioural studies, mental health advocacy</li>
      <li>National Post Graduate College, Lucknow – Psychology, counselling, and mental health research</li>
      <li>Indira Gandhi National Open University – Accessible mental health education and distance learning</li>
      <li>Other Notable Institutions – Graphic Era University, Vivekananda Institute of Medical Science, Mount Carmel College, and Sikkim University</li>
    </ul>
  </section>

  <!-- ================= IMPACT AREAS ================= -->
  <section class="impact-areas">
    <h3>Impact Areas</h3>
    <ul>
      <li>Clinical Psychology and Psychotherapy</li>
      <li>Community Mental Health Initiatives</li>
      <li>Educational Psychology</li>
      <li>Research and Academic Publications</li>
      <li>Mental Health Awareness Programs</li>
      <li>Special Population Mental Health Services</li>
    </ul>
  </section>

  <!-- ================= WHY CHOOSE ================= -->
  <section class="why-choose">
    <h3>Why Choose IMHRC Fellowship?</h3>
    <ul>
      <li>Join a global network of mental health professionals and students</li>
      <li>Enhance professional credibility with a prestigious fellowship certification</li>
      <li>Access exclusive research and academic publication opportunities</li>
      <li>Contribute directly to community mental health initiatives</li>
      <li>Stay updated with the latest trends and developments in mental health</li>
      <li>Benefit from mentorship and collaborative learning opportunities</li>
      <li>Connect with peers from diverse international backgrounds</li>
    </ul>
  </section>

  <!-- ================= CTA ================= -->
  <section class="apply-now">
    <h3>Ready to Apply?</h3>
    <p>
      Take the first step toward joining our distinguished community of mental health professionals 
      and researchers. Apply through our online application process and embark on a journey of 
      growth, impact, and excellence in mental health.
    </p>
    <p class="note">
      <strong>Note:</strong> All fellowships include a mandatory community service commitment, 
      ensuring fellows contribute meaningfully to mental health awareness and support initiatives.
    </p>
  <a href="javascript:void(0)" class="btn-apply" onclick="openFellowshipPopup()">Apply Now</a>

  </section>

</section>

<div class="fellowship-popup" id="fellowshipPopup">

  <div class="popup-overlay" onclick="closeFellowshipPopup()"></div>

  <div class="popup-content">
    <button class="popup-close" onclick="closeFellowshipPopup()">×</button>

    <h3>IMHRC Fellowship Application Form</h3>
    <p>Apply for the Mental Health Fellowship Program</p>

    <form class="fellowship-form">

      <!-- Personal Info -->
      <div class="form-group">
        <label>Full Name *</label>
        <input type="text" placeholder="Enter your full name" required>
      </div>

      <div class="form-group">
        <label>Email Address *</label>
        <input type="email" placeholder="Enter your email" required>
      </div>

      <div class="form-group">
        <label>Mobile Number *</label>
        <input type="tel" placeholder="Enter your mobile number" required>
      </div>

      <!-- Academic / Professional -->
      <div class="form-group">
        <label>Category *</label>
        <select required>
          <option value="">Select Category</option>
          <option>Student</option>
          <option>Professional</option>
          <option>Researcher</option>
          <option>Clinician</option>
          <option>Academic</option>
        </select>
      </div>

      <div class="form-group">
        <label>Current Institution / Organization *</label>
        <input type="text" placeholder="Institution / Organization name" required>
      </div>

      <!-- Fellowship Type -->
      <div class="form-group">
        <label>Fellowship Type *</label>
        <select required>
          <option value="">Select Fellowship</option>
          <option>6-Month Fellowship – ₹1,000</option>
          <option>1-Year Fellowship – ₹1,500</option>
          <option>5-Year Fellowship – ₹2,500</option>
          <option>Lifetime Fellowship – ₹5,000</option>
        </select>
      </div>

      <!-- Purpose -->
      <div class="form-group">
        <label>Purpose of Applying *</label>
        <textarea placeholder="Briefly explain why you want to apply for this fellowship" required></textarea>
      </div>

      <!-- CV Upload -->
      <div class="form-group">
        <label>Upload CV / Resume *</label>
        <input type="file" required>
      </div>

      <!-- Submit -->
      <button type="submit" class="submit-btn">Submit Application</button>

      <p class="form-note">
        * All fields are mandatory. Applications are reviewed by the IMHRC Fellowship Committee.
      </p>

    </form>
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
function openFellowshipPopup() {
  document.getElementById('fellowshipPopup').style.display = 'block';
  document.body.style.overflow = 'hidden';
}

function closeFellowshipPopup() {
  document.getElementById('fellowshipPopup').style.display = 'none';
  document.body.style.overflow = 'auto';
}
</script>

  
</body>


</html>