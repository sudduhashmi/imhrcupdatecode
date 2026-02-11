


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
      .checkbox-item {
    display: flex;
    align-items: center;   /* vertical center */
    gap: 10px;             /* checkbox aur text ka gap */
    font-size: 14px;
    line-height: 1.5;      /* multi-line text clean rahe */
}

.checkbox-item input[type="checkbox"] {
    width: 16px;
    height: 16px;
    margin: 0;
    flex-shrink: 0;        /* checkbox chhota na ho */
}

      .radio-group {
    display: flex;
    gap: 30px;              /* dono options ke beech space */
    align-items: center;    /* vertical center */
    margin-top: 6px;
}

.radio-item {
    display: flex;
    align-items: center;
    gap: 8px;               /* radio aur text ka gap */
    font-size: 14px;
}

.radio-item input[type="radio"] {
    margin: 0;
    width: 16px;
    height: 16px;
}

      /* Full-width form section */
.grow-form-section {
  width: 100%;
  padding: 60px 20px;
  box-sizing: border-box;
}

/* Section Header */
.grow-form-section .section-header {
  text-align: center;
  margin-bottom: 40px;
}

.grow-form-section .section-header h2 {
  font-size: 2.5rem;
  margin-bottom: 10px;
  color: #4e5bff;
}

.grow-form-section .section-header p {
  font-size: 1.1rem;
  color: #555;
}

/* Form Container */
.form-container {
  max-width: 900px;
  margin: 0 auto;
  background: #fff;
  padding: 40px 30px;
  border-radius: 15px;
  box-shadow: 0 6px 25px rgba(0,0,0,0.1);
}

/* Form Group */
.form-group {
  display: flex;
  flex-direction: column;
  margin-bottom: 20px;
}

.form-group label {
  margin-bottom: 8px;
  font-weight: bold;
  color: #333;
}

.form-group input,
.form-group select,
.form-group textarea {
  padding: 12px 15px;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 1rem;
  width: 100%;
  box-sizing: border-box;
  transition: border-color 0.3s;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #4e5bff;
}

/* Submit Button */
.form-group button {
  background: #4e5bff;
  color: #fff;
  padding: 12px 25px;
  font-size: 1rem;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-weight: bold;
  transition: background 0.3s;
}

.form-group button:hover {
  background: #3b45d1;
}

/* Responsive */
@media(max-width:768px){
  .form-container {
    padding: 30px 20px;
  }
}

    </style>
</head>

<body>


   <?php include 'includes/header.php'; ?>
   	<!-- Start Page Title Area -->
<div class="page-title-wave">
  <div class="container">
    <h2>Grow with us</h2>
  </div>

  <svg class="wave" viewBox="0 0 1440 320" preserveAspectRatio="none">
    <path fill="#ffffff" fill-opacity="1" 
      d="M0,64L48,90.7C96,117,192,171,288,170.7C384,171,480,117,576,85.3C672,53,768,43,864,74.7C960,107,1056,181,1152,192C1248,203,1344,149,1392,122.7L1440,96L1440,320L1392,320C1344,320,1248,320,
      1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
    </path>
  </svg>
</div>

  <!-- ================= GROW WITH US FORM ================= -->
<section class="grow-form-section">
  <div class="form-container">

    <!-- Success / Error Messages -->
    <?php if(isset($_GET['success'])){ ?>
      <div style="background:#d1e7dd;color:#0f5132;padding:12px;margin-bottom:15px;border-radius:5px;">
        ✅ Thank you! Your enquiry has been submitted successfully.
      </div>
    <?php } ?>

    <?php if(isset($_GET['error'])){ ?>
      <div style="background:#f8d7da;color:#842029;padding:12px;margin-bottom:15px;border-radius:5px;">
        ❌ Something went wrong. Please try again.
      </div>
    <?php } ?>

    <h2>Grow With Us</h2>
    <p>IMHRC invites individuals and organizations interested in establishing an IMHRC Regional Centre or a Hausla Early Intervention Centre franchise. Please complete the form and our team will get in touch with you.</p>

    <form id="growForm" action="career-submit.php" method="post" enctype="multipart/form-data">

      <!-- Centre Selection -->
      <div class="form-group">
        <label for="centre_type">Type of Centre You Are Applying For <span style="color:red;">*</span></label>
        <select id="centre_type" name="centre_type" required>
          <option value="">Select Centre Type</option>
          <option value="imhrc_regional">IMHRC Regional Centre</option>
          <option value="hausla_franchise">Hausla Early Intervention Centre Franchise</option>
        </select>
      </div>

      <!-- Applicant Information -->
      <h3>Applicant Information</h3>
    <div class="form-group">
  <label>Applicant Type</label>

  <div class="radio-group">
    <label class="radio-item">
      <input type="radio" name="applicant_type" value="individual" required>
      Individual
    </label>

    <label class="radio-item">
      <input type="radio" name="applicant_type" value="organization" required>
      Organization / Institution
    </label>
  </div>
</div>


      <div class="form-group">
        <label for="full_name">Full Name / Authorized Representative <span style="color:red;">*</span></label>
        <input type="text" id="full_name" name="full_name" placeholder="Your full name" required>
      </div>

      <div class="form-group">
        <label for="organization_name">Organization Name (if applicable)</label>
        <input type="text" id="organization_name" name="organization_name" placeholder="Organization Name">
      </div>

      <div class="form-group">
        <label for="organization_type">Organization Type</label>
        <select id="organization_type" name="organization_type">
          <option value="">Select Type</option>
          <option value="society">Society</option>
          <option value="trust">Trust</option>
          <option value="pvt_ltd">Pvt Ltd Company</option>
          <option value="other">Other</option>
        </select>
      </div>

      <!-- Document Uploads -->
      <div class="form-group">
        <label for="org_pan">Organization PAN</label>
        <input type="file" id="org_pan" name="org_pan" accept=".pdf,.jpg,.png">
      </div>
      <div class="form-group">
        <label for="reg_cert">Registration Certificate</label>
        <input type="file" id="reg_cert" name="reg_cert" accept=".pdf,.jpg,.png">
      </div>
      <div class="form-group">
        <label for="auth_aadhar">Authorized Representative Aadhaar <span style="color:red;">*</span></label>
        <input type="file" id="auth_aadhar" name="auth_aadhar" accept=".pdf,.jpg,.png" required>
      </div>
      <div class="form-group">
        <label for="auth_pan">Authorized Representative PAN <span style="color:red;">*</span></label>
        <input type="file" id="auth_pan" name="auth_pan" accept=".pdf,.jpg,.png" required>
      </div>

      <!-- Contact Info -->
      <div class="form-group">
        <label for="email">Email Address <span style="color:red;">*</span></label>
        <input type="email" id="email" name="email" placeholder="Your email" required>
      </div>
      <div class="form-group">
        <label for="phone">Contact Number (including country code) <span style="color:red;">*</span></label>
        <input type="tel" id="phone" name="phone" placeholder="+91 XXXXX XXXXX" required>
      </div>

      <!-- Franchise Interest -->
      <div class="form-group">
        <label for="proposed_territory">Proposed Territory (Country / Region) <span style="color:red;">*</span></label>
        <input type="text" id="proposed_territory" name="proposed_territory" required>
      </div>

      <div class="form-group">
        <label for="profile">Brief Professional Background / Organizational Profile <span style="color:red;">*</span></label>
        <textarea id="profile" name="profile" rows="4" required></textarea>
      </div>

      <div class="form-group">
        <label for="experience">Relevant Experience (Training, Certification, HR, Education, Healthcare, or Related Fields) <span style="color:red;">*</span></label>
        <textarea id="experience" name="experience" rows="4" required></textarea>
      </div>

      <div class="form-group">
        <label for="reason">Reason for Interest in IMHRC Franchise <span style="color:red;">*</span></label>
        <textarea id="reason" name="reason" rows="4" required></textarea>
      </div>

      <!-- Consent -->
     <div class="form-group">
  <label class="checkbox-item">
    <input type="checkbox" id="consent" name="consent" required>
    I confirm that the information provided is accurate and I consent to being contacted by IMHRC regarding this franchise enquiry.
  </label>
</div>


      <!-- Submit -->
      <div class="form-group">
        <button type="submit" name="submit_franchise">Submit Enquiry</button>
      </div>

    </form>
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
  if (window.location.search.includes('success')) {
    window.history.replaceState({}, document.title, window.location.pathname);
  }
</script>

</body>


</html>