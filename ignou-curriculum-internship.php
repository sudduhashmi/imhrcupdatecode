
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
    <title>IGNOU Curriculum Internship Registration Form</title>
<style>


.ignou-form-card {
  background: #fff;
  border-radius: 14px;
  box-shadow: 0 15px 40px rgba(0,0,0,0.08);
  overflow: hidden;
}

.ignou-form-header {
    background: linear-gradient(135deg, #1d274b, #1d274b);
  color: #fff;
  padding: 30px;
  text-align: center;
}

.ignou-form-header h2 {
    font-weight: 700;
    color: #ffb800;
}

.ignou-form-header p {
  margin: 0;
  opacity: 0.9;
}

.ignou-internship-form {
  padding: 30px;
}

.ignou-section-title {
  font-weight: 600;
  color: #1a4ed8;
  margin: 25px 0 15px;
  border-left: 4px solid #1a4ed8;
  padding-left: 10px;
}

.ignou-internship-form label {
    font-weight: 600;
    margin-bottom: 6px;
}
form.ignou-internship-form input,
form.ignou-internship-form select,
form.ignou-internship-form textarea {
    border: 1px solid #b6acac !important;
    outline: none;
    box-shadow: none;
    border-radius: 5px;
}

.ignou-submit-btn {
  background: #1a4ed8;
  color: #fff;
  font-weight: 600;
  padding: 14px;
  border-radius: 8px;
}

.ignou-submit-btn:hover {
  background: #0b3fc2;
  color: #fff;
}

</style>
</head>

<body>


    <?php include 'includes/header.php'; ?>
 

<section class="ignou-internship-wrap py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-9">

        <div class="ignou-form-card">
          <div class="ignou-form-header">
            <h2>IGNOU Curriculum Internship Registration Form</h2>
            <p>This form is strictly for IGNOU students applying for curriculum-based internship.</p>
          </div>
    <?php if (isset($_GET['success'])) { ?>
  <div class="alert alert-success text-center">
    ✅ Your registration has been successfully submitted!
  </div>
  <script>
    window.history.replaceState({}, document.title, window.location.pathname);
  </script>
<?php } ?>

<?php if (isset($_GET['error'])) { ?>
  <div class="alert alert-danger text-center">
    ❌ Something went wrong. Please try again.
  </div>
<?php } ?>

<form class="ignou-internship-form"
      method="post"
      enctype="multipart/form-data"
      action="ignou-submit.php">
  <div class="row">

    <!-- Email -->
    <div class="col-md-6 mb-3">
      <label>Email Address*</label>
      <input type="email" name="email" class="form-control" placeholder="example@email.com" required>
    </div>

    <!-- Prefix -->
    <div class="col-md-6 mb-3">
      <label>Prefix*</label>
      <select name="prefix" class="form-select" required>
        <option value="">Select Prefix</option>
        <option>Dr.</option>
        <option>Ms.</option>
        <option>Mr.</option>
      </select>
    </div>

    <!-- Name -->
    <div class="col-md-6 mb-3">
      <label>Name (as per academic documents)*</label>
      <input type="text" name="name" class="form-control" placeholder="Enter your full name" required>
    </div>

    <!-- Gender -->
    <div class="col-md-6 mb-3">
      <label>Gender*</label>
      <select name="gender" class="form-select" required>
        <option value="">Select Gender</option>
        <option>Male</option>
        <option>Female</option>
        <option>Prefer not to say</option>
      </select>
    </div>

    <!-- Age -->
    <div class="col-md-6 mb-3">
      <label>Age (years)*</label>
      <input type="number" name="age" min="16" max="80"
             class="form-control" placeholder="Enter your age" required>
    </div>

    <!-- Contact -->
    <div class="col-md-6 mb-3">
      <label>WhatsApp/Contact Number*</label>
      <input type="tel" name="contact" class="form-control"
             pattern="[0-9]{10}" maxlength="10"
             placeholder="10-digit mobile number" required>
    </div>

    <!-- Address -->
    <div class="col-md-12 mb-3">
      <label>Address*</label>
      <textarea name="address" class="form-control" rows="3"
        placeholder="Enter your full address" required></textarea>
    </div>

    <!-- Enrolment -->
    <div class="col-md-6 mb-3">
      <label>IGNOU Enrolment Number*</label>
      <input type="text" name="enrolment" class="form-control"
             placeholder="Enter your IGNOU Enrolment Number" required>
    </div>

    <!-- Regional Centre -->
    <div class="col-md-6 mb-3">
      <label>Name of Regional Centre (IGNOU RC)*</label>
      <input type="text" name="regional_centre" class="form-control"
             placeholder="Name of Regional Centre" required>
    </div>

  </div>

  <div class="row">

    <!-- Course Pursuing -->
    <div class="col-md-6 mb-3">
      <label>Course Pursuing*</label>
      <select name="course" class="form-select" required>
        <option value="">Select Course Pursuing</option>
        <option>B.A. Psychology</option>
        <option>M.A. Psychology</option>
      </select>
    </div>

    <!-- Course Code -->
    <div class="col-md-6 mb-3">
      <label>Course Code (Specialisation)*</label>
      <select name="course_code" class="form-select" required>
        <option value="">Select Course Code</option>
        <option>MPCE 015 (Clinical Psychology) – 240 Hours</option>
        <option>MPCE 025 (Counselling Psychology) – 240 Hours</option>
        <option>BPCE 023 (BDP – BA Psychology) – 120 Hours</option>
      </select>
    </div>

    <!-- Reference Letter -->
    <div class="col-md-12 mb-3">
      <label>
        Upload Reference Letter issued from RC for undertaking Internship at IMHRC*
      </label>
      <input type="file" name="reference_letter" class="form-control"
             accept=".pdf,.jpg,.jpeg,.png" required>
    </div>

    <!-- Internship Letter -->
    <div class="col-12 mb-3">
      <label>Upload Internship Letter issued from IGNOU RC*</label>
      <input type="file" name="internship_letter" class="form-control"
             accept=".pdf,.jpg,.jpeg,.png" required>
    </div>

  

    <!-- ID Proof -->
    <div class="col-12 mb-3">
      <label>
        Upload ID Proof (PAN Card, AADHAR Card, Voter ID,
        Driving Licence, School/College ID Card)*
      </label>
      <input type="file" name="id_proof" class="form-control"
             accept=".pdf,.jpg,.jpeg,.png" required>
    </div>

    <!-- Photo -->
    <div class="col-12 mb-3">
      <label>Upload latest Passport size Photograph*</label>
      <input type="file" name="photo" class="form-control"
             accept=".jpg,.jpeg,.png" required>
    </div>

  </div>

  <!-- Declaration -->
  <div class="form-check mb-4">
    <input class="form-check-input" type="checkbox" name="declaration" required>
    <label class="form-check-label">
      I hereby declare that the information furnished above is true
      and correct to the best of my knowledge. I understand that this
      internship is part of my IGNOU curriculum and any false
      information may lead to rejection.
    </label>
  </div>

  <!-- Submit -->
  <button type="submit" class="btn ignou-submit-btn w-100">
    Submit Registration
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