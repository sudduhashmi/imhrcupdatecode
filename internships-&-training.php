<?php
// --- DEBUGGING: Enable Error Reporting (Fix White Page) ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'admin/includes/db.php';

// --- 1. FETCH SETTINGS (For Razorpay) ---
$settings = [];
try {
  $res = $conn->query("SELECT SettingKey, SettingValue FROM site_settings");
  if ($res) {
    while ($row = $res->fetch_assoc()) {
      $settings[$row['SettingKey']] = $row['SettingValue'];
    }
  }
} catch (Exception $e) {
}

$keyId = ($settings['razorpay_mode'] ?? 'test') == 'live' ? ($settings['razorpay_live_key_id'] ?? '') : ($settings['razorpay_test_key_id'] ?? '');
$currency = $settings['currency'] ?? 'INR';

// Email Settings
$adminEmail = $settings['contact_email'] ?? 'info@imhrc.org';
$siteTitle = $settings['site_title'] ?? 'IMHRC';

// Payment Status Check (Default to '1' aka Enabled if not set)
$paymentEnabled = $settings['internship_payment_status'] ?? '1';

// --- 2. FETCH USER DATA IF LOGGED IN ---
$user = [
  'Name' => '',
  'Email' => '',
  'Phone' => '',
  'Address' => ''
];
if (isset($_SESSION['user_id'])) {
  $uid = $_SESSION['user_id'];
  $uQ = $conn->query("SELECT Name, Email, Phone, Address FROM userlogin WHERE UserId = $uid");
  if ($uQ && $uRow = $uQ->fetch_assoc()) {
    $user = array_merge($user, $uRow);
  }
}

// --- 3. FETCH DYNAMIC DATA (Internships & Courses) ---
$internships = [];
$academicCourses = [];
$specializations = [];

try {
  // A. Fetch Internships (Programs)
  $resIntern = $conn->query("SELECT * FROM internships WHERE Status = 'active' ORDER BY DisplayOrder ASC");
  if ($resIntern) {
    $internships = $resIntern->fetch_all(MYSQLI_ASSOC);
  }

  // B. Fetch Academic Courses (for Course Pursuing Dropdown)
  $resAcad = $conn->query("SELECT Title FROM academic_programs WHERE Status = 'active' ORDER BY Title ASC");
  if ($resAcad) {
    while ($r = $resAcad->fetch_assoc()) {
      $academicCourses[] = $r['Title'];
    }
  }

  // C. Fetch Unique Specializations (Dynamic from Internships table + Admin Settings if any)
  if (isset($settings['internship_specializations'])) {
    $specializations = array_map('trim', explode(',', $settings['internship_specializations']));
  } else {
    $resSpec = $conn->query("SELECT DISTINCT Specialization FROM internships WHERE Status='active'");
    if ($resSpec) {
      while ($r = $resSpec->fetch_assoc()) {
        if (!empty($r['Specialization'])) {
          $parts = explode(',', $r['Specialization']);
          foreach ($parts as $part) {
            $clean = trim($part);
            if (!in_array($clean, $specializations) && $clean != 'NA') {
              $specializations[] = $clean;
            }
          }
        }
      }
    }
    if (empty($specializations)) {
      $specializations = ['Clinical Psychology', 'Counselling Psychology', 'Child Psychology', 'Industrial Psychology', 'Educational Psychology', 'Other'];
    }
  }
  sort($specializations);

} catch (Exception $e) {
}

// --- 4. HANDLE FORM SUBMISSION ---
$msg = "";
$msgType = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_application'])) {
  // Collect Data
  $prefix = $_POST['prefix'] ?? '';
  $fullName = trim($_POST['full_name']);
  $email = trim($_POST['email']);
  $age = isset($_POST['age']) ? (int) $_POST['age'] : 0; // Cast to Int
  $phone = trim($_POST['phone']);
  $gender = $_POST['gender'] ?? '';
  $address = trim($_POST['address']);
  $coursePursuing = $_POST['course_pursuing'] ?? '';
  $institute = trim($_POST['institute_name']);
  $specialization = $_POST['specialization'] ?? '';
  $programId = $_POST['program_id'] ?? '';
  $programName = $_POST['program_name'] ?? '';

  // Fallback if program name empty
  if (empty($programName) && !empty($programId)) {
    foreach ($internships as $int) {
      if ($int['InternshipId'] == $programId) {
        $programName = $int['Title'];
        break;
      }
    }
  }
  // Fallback 2: Use the visible input if hidden failed
  if (empty($programName)) {
    $programName = $_POST['program'] ?? '';
  }

  $startDate = $_POST['start_date'] ?? '';
  $amount = $_POST['amount'] ?? 0;
  $paymentMethod = $_POST['payment_method'] ?? 'razorpay';
  $payId = $_POST['razorpay_payment_id'] ?? '';

  // File Upload Helper
  function uploadDoc($fileKey)
  {
    if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === 0) {
      $dir = 'assets/uploads/internships/';
      if (!file_exists($dir))
        mkdir($dir, 0777, true);
      $ext = pathinfo($_FILES[$fileKey]['name'], PATHINFO_EXTENSION);
      $name = time() . '_' . uniqid() . '.' . $ext;
      if (move_uploaded_file($_FILES[$fileKey]['tmp_name'], $dir . $name)) {
        return $dir . $name;
      }
    }
    return '';
  }

  $recLetter = uploadDoc('rec_letter');
  $idProof = uploadDoc('id_proof');
  $photo = uploadDoc('photo');
  $feeReceipt = ($paymentMethod == 'manual') ? uploadDoc('fee_receipt') : '';

  // Insert into DB
  $sql = "INSERT INTO internship_applications (
        UserId, Prefix, FullName, Age, ContactNumber, Email, Gender, Address,
        CoursePursuing, InstituteName, Specialization, ProgramSelected, ProposedStartDate,
        AmountPaid, PaymentMethod, RazorpayPaymentId, RecommendationLetter, IDProof, Photograph, FeeReceipt, PaymentStatus
    ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

  $uid = $_SESSION['user_id'] ?? NULL;
  $payStatus = ($paymentMethod == 'razorpay' && !empty($payId)) ? 'success' : 'pending';

  $stmt = $conn->prepare($sql);

  // Corrected Types String: ississs ssssssd ssssssss (Total 21)
  $stmt->bind_param(
    "ississssssssdssssssss",
    $uid,
    $prefix,
    $fullName,
    $age,
    $phone,
    $email,
    $gender,
    $address,
    $coursePursuing,
    $institute,
    $specialization,
    $programName,
    $startDate,
    $amount,
    $paymentMethod,
    $payId,
    $recLetter,
    $idProof,
    $photo,
    $feeReceipt,
    $payStatus
  );

  if ($stmt->execute()) {
    $appID = $conn->insert_id;
    $appCode = "APP-" . date('Y') . "-" . str_pad($appID, 4, '0', STR_PAD_LEFT);

    // --- EMAIL LOGIC ---
    $mailSentAdmin = false;
    $mailSentUser = false;

    // Ensure mail function exists before calling to prevent 500 error
    if (function_exists('mail')) {
      $headers = "MIME-Version: 1.0" . "\r\n";
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

      // Fix: Use a safe From address
      $serverDomain = $_SERVER['SERVER_NAME'];
      $fromEmail = "no-reply@" . str_replace('www.', '', $serverDomain);
      $headers .= "From: $siteTitle <$fromEmail>" . "\r\n";
      $headers .= "Reply-To: $adminEmail" . "\r\n";
      $headers .= "X-Mailer: PHP/" . phpversion();

      // 1. Email to Admin
      $adminSubject = "New Application: $programName ($fullName)";
      $adminBody = "
            <div style='font-family: Arial, sans-serif; color: #333;'>
                <h2 style='color: #0b2c57;'>New Internship Application</h2>
                <p><strong>Application ID:</strong> #$appCode</p>
                <table cellpadding='10' border='1' style='border-collapse: collapse; width: 100%; border-color: #ddd;'>
                    <tr><td bgcolor='#f9f9f9'><strong>Applicant Name</strong></td><td>$prefix $fullName</td></tr>
                    <tr><td bgcolor='#f9f9f9'><strong>Email</strong></td><td>$email</td></tr>
                    <tr><td bgcolor='#f9f9f9'><strong>Phone</strong></td><td>$phone</td></tr>
                    <tr><td bgcolor='#f9f9f9'><strong>Program</strong></td><td>$programName</td></tr>
                    <tr><td bgcolor='#f9f9f9'><strong>Institute</strong></td><td>$institute</td></tr>
                    <tr><td bgcolor='#f9f9f9'><strong>Payment Status</strong></td><td><strong style='color:" . ($payStatus == 'success' ? 'green' : 'orange') . "'>" . strtoupper($payStatus) . "</strong></td></tr>
                    <tr><td bgcolor='#f9f9f9'><strong>Amount</strong></td><td>₹$amount</td></tr>
                    <tr><td bgcolor='#f9f9f9'><strong>Payment ID</strong></td><td>$payId</td></tr>
                </table>
            </div>";

      $mailSentAdmin = @mail($adminEmail, $adminSubject, $adminBody, $headers);

      // 2. Email to Student
      $studentSubject = "";
      $studentBody = "";

      if ($payStatus == 'success') {
        $studentSubject = "Payment Receipt & Confirmation - $siteTitle";
        $studentBody = "
                <div style='font-family: Arial, sans-serif; color: #333; max-width:600px; margin:auto; border:1px solid #ddd; padding:20px;'>
                    <div style='text-align:center; border-bottom:2px solid #0b2c57; padding-bottom:15px; margin-bottom:20px;'>
                        <h2 style='color: #0b2c57; margin:0;'>Payment Receipt</h2>
                        <p style='margin:5px 0; color:#777;'>$siteTitle</p>
                    </div>
                    <p>Dear <strong>$fullName</strong>,</p>
                    <p>Thank you for enrolling in <strong>$programName</strong>. We have successfully received your payment.</p>
                    <div style='background:#f0fff4; border:1px solid #c6f6d5; padding:15px; border-radius:8px; margin:20px 0;'>
                        <h3 style='color:green; margin-top:0;'>PAID: ₹$amount</h3>
                        <p style='margin:5px 0;'><strong>Transaction ID:</strong> $payId</p>
                        <p style='margin:5px 0;'><strong>Date:</strong> " . date('d M Y') . "</p>
                    </div>
                    <p>Your Application Reference ID is: <strong>$appCode</strong></p>
                </div>";
      } else {
        $studentSubject = "Application Received - $siteTitle";
        $studentBody = "
                <div style='font-family: Arial, sans-serif; color: #333; max-width:600px; margin:auto; border:1px solid #ddd; padding:20px;'>
                    <h2 style='color: #0b2c57;'>Application Received</h2>
                    <p>Dear <strong>$fullName</strong>,</p>
                    <p>We have received your application for <strong>$programName</strong>.</p>
                    <div style='background:#fffaf0; border:1px solid #feeebc; padding:15px; border-radius:8px; margin:20px 0;'>
                        <h4 style='color:#c05621; margin-top:0;'>Status: Pending Verification</h4>
                        <p>We are verifying your details. You will receive a confirmation once approved.</p>
                    </div>
                    <p>Reference ID: <strong>$appCode</strong></p>
                </div>";
      }
      $mailSentUser = @mail($email, $studentSubject, $studentBody, $headers);
    } else {
      // Fallback if mail() not available
      $mailSentAdmin = false;
      $mailSentUser = false;
    }

    // Message to User
    $mailMsg = "";
    if (!$mailSentUser) {
      $mailMsg = "<br><small class='text-muted'>(Note: Confirmation email could not be sent due to server configuration, but your application is saved.)</small>";
    } else {
      $mailMsg = "<br><small class='text-success'>Confirmation email sent.</small>";
    }

    $msg = "<h4 class='text-success'>Application Submitted!</h4>
                <p>You have successfully applied for: <strong>$programName</strong></p>
                <div class='bg-light p-2 rounded text-dark border d-inline-block mt-1'>
                    <strong>Ref No:</strong> <span class='text-primary'>#$appCode</span>
                </div>
                $mailMsg";
    $msgType = "success";

    $_POST = []; // Clear form
  } else {
    $msg = "Error: " . $conn->error;
    $msgType = "danger";
  }
}
?>
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
    :root {
      --primary: #004aad;
      --secondary: #00b4d8;
      --light: #f4f8ff;
      --accent: #f6b400;
    }

    .section {
      padding: 80px 0;
    }

    .bg-soft {
      background: var(--light);
    }

    .hero {
      color: #fff;
      position: relative;
    }

    .hero img {
      border-radius: 18px;
    }

    .badge-soft {
      background: rgb(29 39 75);
      color: #f6b400;
      font-weight: 600;
      padding: 8px 18px;
    }

    .hero h1 {
      font-size: 42px;
      line-height: 1.2;
    }

    .icon-box {
      width: 70px;
      height: 70px;
      background: linear-gradient(135deg, #ffb800, #ffb800);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      font-size: 2rem;
      margin: 0 auto;
      transition: all 0.3s ease;
    }

    .internship-card:hover .icon-box {
      transform: scale(1.2);
    }

    .internship-card {
      transition: all 0.3s ease;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
      padding: 25px;
      height: 100%;
      border: 1px solid #eee;
    }

    .internship-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
    }

    .section-title {
      font-size: 34px;
      font-weight: 700;
      position: relative;
      padding-left: 18px;
    }

    .section-title::before {
      content: '';
      position: absolute;
      left: 0;
      top: 6px;
      width: 4px;
      height: 30px;
      background: var(--accent);
      border-radius: 10px;
    }

    .intern-card {
      background: #fff;
      border-radius: 20px;
      padding: 28px;
      height: 100%;
      position: relative;
      border: 1px solid #edf1f6;
      transition: .35s;
      display: flex;
      flex-direction: column;
    }

    .intern-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 18px 40px rgba(11, 44, 77, .15);
    }

    .intern-card h5 {
      font-weight: 700;
      color: var(--primary);
      margin-bottom: 15px;
      min-height: 50px;
    }

    .intern-meta p {
      font-size: 14px;
      margin-bottom: 6px;
    }

    .intern-card ul {
      margin-top: 8px;
      font-size: 14px;
      padding-left: 20px;
      flex-grow: 1;
      margin-bottom: 20px;
    }

    .intern-card .btn {
      margin-top: auto;
    }

    .info-panel {
      background: linear-gradient(135deg, #0b2545, #133b76);
      color: #fff;
      padding: 40px;
      padding: 30px;
      border-top-left-radius: 18px;
      border-bottom-left-radius: 18px;
    }

    .info-panel h3 {
      font-weight: 700;
    }

    .info-panel h4 {
      font-weight: 700;
      margin-bottom: 15px;
      color: #ffb800 !important;
    }

    .info-panel h5 {
      color: #ffb800;
      margin-top: 15px;
      font-weight: 700;
      font-size: 1.1rem;
    }

    .info-panel h6 {
      color: #e0e7ff;
      margin-top: 10px;
      font-weight: 600;
      font-size: 1rem;
    }

    .info-panel ul {
      padding-left: 20px;
    }

    .info-panel li {
      margin-bottom: 5px;
      color: rgba(255, 255, 255, 0.9);
    }

    .info-panel p {
      color: rgba(255, 255, 255, 0.9);
    }

    /* Modal Styles */
    .internship-modal {
      border-radius: 18px;
      overflow: hidden;
      font-family: 'Inter', sans-serif;
      border: none;
    }

    .form-panel {
      background: #f8fafc;
    }

    .form-header {
      background: #fff;
      padding: 20px 30px;
      border-bottom: 1px solid #e5e7eb;
      position: relative;
    }

    .form-header .btn-close {
      position: absolute;
      right: 20px;
      top: 20px;
    }

    .form-control,
    .form-select {
      padding: 12px;
      border-radius: 10px;
      border: 1px solid #d1d5db;
      height: auto;
    }

    label {
      font-size: 14px;
      font-weight: 600;
      margin-bottom: 6px;
      display: block;
    }

    .terms-box {
      background: #fffbe6;
      padding: 12px;
      border-radius: 10px;
      font-size: 12px;
      border: 1px solid #ffe58f;
    }

    .carousel-caption {
      background: rgba(0, 0, 0, .65);
      padding: 18px;
      bottom: 20px;
    }

    .testimonial-card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      text-align: center;
    }

    .testimonial-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
    }

    @media(max-width:991px) {
      .info-panel {
        display: block !important;
        border-radius: 0 0 16px 16px;
      }

      .form-panel {
        border-radius: 16px 16px 0 0;
      }
    }

    /* Carousel wrapper */
    .imhrc-carousel {
      position: relative;
    }

    /* Slide image */
    .imhrc-slide-img {
      width: 100%;
      height: 100vh;
      object-fit: cover;
      user-select: none;
      pointer-events: none;
    }

    /* Full overlay */
   .imhrc-overlay {
    position: absolute;
    inset: 0;
    z-index: 2;
       background: linear-gradient(to bottom, rgba(0, 0, 0, 0) 0%, rgb(0 0 0 / 0%) 40%, rgb(0 0 0 / 57%) 70%, rgb(0 0 0 / 71%) 100%);
}


    /* Caption */
    .imhrc-caption {
      position: absolute;
      inset: 0;
      z-index: 2;
      display: flex !important;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
    }

    .imhrc-caption h1 {
      font-size: 2.8rem;
      font-weight: 700;
      color: #ffb800;
    }

    .imhrc-caption p {
      max-width: 700px;

      font-size: 1.1rem;
    }

    /* Slide image */
    .imhrc-slide {
      position: relative;
    }

    .imhrc-slide-img {
      width: 100%;
      height: 100vh;
      object-fit: cover;
      user-select: none;
      pointer-events: none;
      z-index: 1;
    }

    /* Overlay (image ke upar, text ke niche) */
  
    /* Caption (overlay ke upar) */
    .imhrc-caption {
      position: absolute;
      inset: 0;
      z-index: 3 !important;
      display: flex !important;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      color: #fff;
    }

    /* Caption text styling */
    .imhrc-caption h1 {
      font-size: 2.8rem;
      font-weight: 700;
    }

    .imhrc-caption p {
      max-width: 720px;
      font-size: 1.1rem;
    }

    /* Carousel arrows sabse upar */
    .imhrc-control-prev,
    .imhrc-control-next {
      z-index: 4;
    }

    /* Indicators bhi upar */
    .imhrc-indicators {
      z-index: 4;
    }

    #processing-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.9);
      z-index: 10000;
      align-items: center;
      justify-content: center;
      flex-direction: column;
    }
  </style>
</head>

<body>

  <?php include 'includes/header.php'; ?>

  <!-- Loader -->
  <div id="processing-overlay">
    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>
    <h5 class="mt-3 text-dark">Processing...</h5>
  </div>

  <div id="imhrcHeroCarousel" class="carousel slide imhrc-carousel" data-bs-ride="carousel">

    <!-- Indicators -->
    <div class="carousel-indicators imhrc-indicators">
      <button type="button" data-bs-target="#imhrcHeroCarousel" data-bs-slide-to="0" class="active"></button>
      <button type="button" data-bs-target="#imhrcHeroCarousel" data-bs-slide-to="1"></button>
      <button type="button" data-bs-target="#imhrcHeroCarousel" data-bs-slide-to="2"></button>
      <button type="button" data-bs-target="#imhrcHeroCarousel" data-bs-slide-to="3"></button>
      <button type="button" data-bs-target="#imhrcHeroCarousel" data-bs-slide-to="4"></button>
      <button type="button" data-bs-target="#imhrcHeroCarousel" data-bs-slide-to="5"></button>
    </div>

    <!-- Slides -->
    <div class="carousel-inner imhrc-carousel-inner">

      <!-- Slide 1 -->
      <div class="carousel-item active imhrc-slide">
        <img src="assets/img/internship1.jpeg" class="imhrc-slide-img" alt="">
        <div class="imhrc-overlay"></div>

        <div class="carousel-caption imhrc-caption">
          <h1>IMHRC Internship & Professional Training Program</h1>
          <p>Enhancing skills through practical exposure, expert mentorship, and real-world projects.</p>

        </div>
      </div>

      <!-- Slide 2 -->
      <div class="carousel-item imhrc-slide">
        <img src="assets/img/internship2.jpeg" class="imhrc-slide-img" alt="">
        <div class="imhrc-overlay"></div>

        <div class="carousel-caption imhrc-caption">
          <h1>Career-Focused Internship Programs</h1>
          <p>Designed to prepare students and professionals for industry-ready roles.</p>

        </div>
      </div>

      <!-- Slide 3 -->
      <div class="carousel-item imhrc-slide">
        <img src="assets/img/internship3.jpeg" class="imhrc-slide-img" alt="">
        <div class="imhrc-overlay"></div>

        <div class="carousel-caption imhrc-caption">
          <h1>Hands-On Professional Training</h1>
          <p>Learn from experts with structured training modules and certifications.</p>

        </div>
      </div>

      <!-- Slide 4 -->
      <div class="carousel-item imhrc-slide">
        <img src="assets/img/internship4.jpeg" class="imhrc-slide-img" alt="">
        <div class="imhrc-overlay"></div>

        <div class="carousel-caption imhrc-caption">
          <h1>Skill Development & Mentorship</h1>
          <p>Build confidence, leadership, and technical expertise.</p>

        </div>
      </div>

      <!-- Slide 5 -->
      <div class="carousel-item imhrc-slide">
        <img src="assets/img/internship5.jpeg" class="imhrc-slide-img" alt="">
        <div class="imhrc-overlay"></div>

        <div class="carousel-caption imhrc-caption">
          <h1>Industry-Oriented Learning</h1>
          <p>Bridge the gap between academics and professional careers.</p>

        </div>
      </div>

      <!-- Slide 6 -->
      <div class="carousel-item imhrc-slide">
        <img src="assets/img/internship6.jpeg" class="imhrc-slide-img" alt="">
        <div class="imhrc-overlay"></div>

        <div class="carousel-caption imhrc-caption">
          <h1>Certified Training Programs</h1>
          <p>Boost your resume with recognized certifications.</p>

        </div>
      </div>

    </div>

    <!-- Controls -->
    <button class="carousel-control-prev imhrc-control-prev" type="button" data-bs-target="#imhrcHeroCarousel"
      data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>

    <button class="carousel-control-next imhrc-control-next" type="button" data-bs-target="#imhrcHeroCarousel"
      data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>

  </div>




  <!-- HERO -->
  <section class="hero section pt-5">
    <div class="container">
      <div class="row align-items-center g-5">
        <div class="col-lg-7">
          <span class="badge-soft rounded-pill mb-3 d-inline-block">Internships & Training</span>
          <h1 class="fw-bold mt-3" style="color:#000;">IMHRC Internship & Professional Training Program</h1>
          <p class="mt-3 text-black">
            IMHRC offers structured internship and training programs focused on practical exposure, skill development,
            and career readiness in healthcare and clinical domains.
          </p>
          <button class="btn btn-primary btn-lg mt-3" onclick="openApplyModal()">
            Apply for Internship
          </button>
     <button class="btn btn-primary btn-lg mt-3" data-bs-toggle="modal" data-bs-target="#imhrcInternshipModal">
 Apply for IGNOU Internship
</button>
</div>
        <div class="col-lg-5">
          <img src="assets/img/internship-about.png" class="img-fluid shadow">
        </div>
      </div>
    </div>
  </section>

  <!-- ABOUT -->
  <section class="section py-5 bg-light">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="fw-bold display-6">About IMHRC Internships</h2>
        <p class="text-muted fs-5">
          IMHRC internships are designed for students and aspiring professionals who wish to gain experiential learning
          in healthcare, clinical research, diagnostics, administration, and allied medical fields.
        </p>
      </div>

      <div class="row g-4 justify-content-center">
        <!-- Card 1 -->
        <div class="col-md-4">
          <div class="internship-card text-center h-100">
            <div class="icon-box mb-3">
              <i class="bi bi-hospital"></i>
            </div>
            <h5 class="fw-bold mb-2">Real-World Exposure</h5>
            <p class="text-muted">Hands-on experience aligned with industry and institutional standards.</p>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="col-md-4">
          <div class="internship-card text-center h-100">
            <div class="icon-box mb-3">
              <i class="bi bi-person-video3"></i>
            </div>
            <h5 class="fw-bold mb-2">Mentored Learning</h5>
            <p class="text-muted">Guidance by experts, clinicians and subject matter specialists.</p>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="col-md-4">
          <div class="internship-card text-center h-100">
            <div class="icon-box mb-3">
              <i class="bi bi-award"></i>
            </div>
            <h5 class="fw-bold mb-2">Career Readiness</h5>
            <p class="text-muted">Skill-focused training preparing interns for future opportunities.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

 <!-- INTERNSHIPS OFFERED -->
<section class="py-5" id="internships-offered">
  <div class="container">

    <h2 class="section-title mb-4">Internship Offered</h2>

    <div class="row g-4">
      
      <?php if (!empty($internships)): foreach($internships as $row): ?>
      <div class="col-lg-4 col-md-6">
        <div class="intern-card">
          <h5><?php echo htmlspecialchars($row['Title']); ?></h5>
          <hr>
          <div class="intern-meta">
            <p><strong>Duration:</strong> <?php echo htmlspecialchars($row['Duration']); ?></p>
            <p><strong>Specialization:</strong> <?php echo htmlspecialchars($row['Specialization']); ?></p>
            <p><strong>Fee:</strong> INR <?php echo number_format($row['Fee']); ?></p>
            <p><strong>Suitable for:</strong> <?php echo htmlspecialchars($row['SuitableFor']); ?></p>
          </div>
          <strong>Curriculum Includes:</strong>
          <ul>
            <?php 
              $curriculum = explode(',', $row['Curriculum']); 
              foreach($curriculum as $item) echo "<li>".htmlspecialchars(trim($item))."</li>";
            ?>
          </ul>
          
          <?php 
            // 1. Simple and Robust JSON Encoding
            // json_encode handles escaping automatically. base64 ensures HTML attribute safety.
            $jsonData = json_encode($row);
            $safeData = base64_encode($jsonData);
          ?>

          <!-- 2. Button with Simplified Data Attribute -->
          <button class="btn btn-primary btn-lg w-100" 
                  type="button"
                  data-internship="<?php echo $safeData; ?>"
                  onclick="handleApplyClick(this)">
            Apply for Internship
          </button>
        </div>
      </div>
      <?php endforeach; else: ?>
        <div class="col-12 text-center text-muted">No internship programs available at the moment.</div>
      <?php endif; ?>

    </div>
  </div>
</section>

<!-- Required JS for this section -->
<script>
function handleApplyClick(btn) {
    try {
        // 1. Get Base64 data
        const b64Data = btn.getAttribute('data-internship');
        
        if (!b64Data) {
            console.error("No data-internship attribute found on button");
            alert("Error: Program data is missing.");
            return;
        }

        // 2. Decode Base64
        // Since PHP json_encode produces ASCII-safe output (escaping unicode),
        // window.atob() is sufficient and safe here.
        const jsonStr = window.atob(b64Data);
        
        // 3. Parse JSON
        const data = JSON.parse(jsonStr);
        
        // 4. Call the main modal function
        if(typeof openApplyModal === 'function') {
            openApplyModal(data);
        } else {
            console.error('openApplyModal function is missing');
            alert('Error: Application form not loaded. Please refresh the page.');
        }
    } catch (e) {
        console.error('Error handling internship click:', e);
        alert('Could not open the form. Please try refreshing the page.');
    }
}
</script>
  <!-- OUTCOMES -->
  <section class="section py-5 bg-light">
    <div class="container">
      <h2 class="fw-bold text-center mb-5">Outcomes & Certification</h2>
      <div class="row g-4">

        <div class="col-md-6">
          <div class="card card-soft h-100 p-4 shadow-sm border-0 rounded-4 bg-white">
            <h5 class="fw-bold mb-4 text-primary">Learning Outcomes</h5>
            <ul class="list-unstyled mb-0">
              <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Practical work with skilled
                Psychologists</li>
              <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Core Mental Health Professional
                Skills</li>
              <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Understanding of Mental
                Disorders</li>
              <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Psychotherapeutic Interventions
              </li>
              <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Case Conference & Certification
              </li>
              <li><i class="bi bi-check-circle-fill text-success me-2"></i> Study Material Provided</li>
            </ul>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card card-soft h-100 p-4 shadow-sm border-0 rounded-4 bg-gradient p-4 text-white"
            style="background: linear-gradient(135deg, #ff416c, #ff4b2b);">
            <h5 class="fw-bold mb-3 text-white">Certification</h5>
            <p style="color: #000 !important; font-weight: 500;">
              Upon successful completion, participants receive an <strong>official IMHRC Internship Certificate</strong>
              recognizing their training, duration and performance.
              <span class="d-block mt-2"><i class="bi bi-award-fill"></i> Valuable for career growth</span>
            </p>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- TESTIMONIALS (Static) -->
  <section class="section bg-soft py-5">
    <div class="container">
      <h2 class="fw-bold text-center mb-5">Student Testimonials</h2>
      <div id="testimonials" class="carousel slide" data-bs-ride="carousel">
        <!-- Keeping static for now -->
        <div class="carousel-inner">
          <div class="carousel-item active">
            <div class="testimonial-card mx-auto p-4 rounded-4 shadow-sm bg-white" style="max-width:700px;">
              <i class="bi bi-chat-quote-fill text-primary fs-1 mb-3"></i>
              <p class="mb-3">"IMHRC internship helped me understand real clinical workflows and boosted my confidence."
              </p>
              <h6 class="fw-bold mb-0">Anjali Sharma</h6>
              <small class="text-muted">Clinical Research Intern</small>
            </div>
          </div>
          <div class="carousel-item">
            <div class="testimonial-card mx-auto p-4 rounded-4 shadow-sm bg-white" style="max-width:700px;">
              <i class="bi bi-chat-quote-fill text-primary fs-1 mb-3"></i>
              <p class="mb-3">"Well-structured program with hands-on exposure and supportive mentors."</p>
              <h6 class="fw-bold mb-0">Aman Verma</h6>
              <small class="text-muted">Healthcare Administration Trainee</small>
            </div>
          </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#testimonials" data-bs-slide="prev">
          <span class="carousel-control-prev-icon bg-primary rounded-circle p-3" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#testimonials" data-bs-slide="next">
          <span class="carousel-control-next-icon bg-primary rounded-circle p-3" aria-hidden="true"></span>
        </button>
      </div>
    </div>
  </section>




  <!-- MODAL FORM -->
  <div class="modal fade" id="internshipApply" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content internship-modal">

        <div class="modal-body p-0">
          <div class="row g-0">

            <!-- LEFT INFO PANEL (Static Terms) -->
            <div class="col-lg-5 col-12 info-panel order-2 order-lg-1">
              <div style="overflow-y:auto; max-height:80vh; padding-right:10px;">
                <h4 class="mb-3 text-warning">Terms, Conditions & Policies</h4>

                <h5>1. Terms & Conditions</h5>
                <h6>1.1 Scope of Services</h6>
                <p>Hausla Wellness Pvt Ltd ("Company") provides:
                <ul>
                  <li>Counselling & mental healthcare services (online/offline)</li>
                  <li>Educational programmes, internships, and training (online/offline)</li>
                </ul>
                </p>
                <h6>1.2 Payment</h6>
                <ul>
                  <li>All fees are quoted in Indian Rupees (INR) and are exclusive of applicable taxes.</li>
                  <li>Payments are due in full at the time of booking or registration.</li>
                  <li>The Company reserves the right to suspend or cancel access if payment is not received.</li>
                </ul>
                <h6>1.3 Bookings & Confirmation</h6>
                <ul>
                  <li>Bookings are confirmed only upon successful payment via Razorpay.</li>
                  <li>You will receive an email/SMS confirmation including service details and dates.</li>
                </ul>
                <h6>1.4 User Obligations</h6>
                <ul>
                  <li>You agree to provide accurate personal and billing information.</li>
                  <li>You are responsible for maintaining confidentiality of any account credentials.</li>
                </ul>
                <h5 class="mt-4">2. Refund & Cancellation Policy</h5>
                <h6>2.1 Free Cancellation Window</h6>
                <ul>
                  <li>Training, academic Fee paid is non-refundable</li>
                  <li>Counselling Sessions, if cancelled within the 24 hours of scheduling, 50% fee refund will be done
                    only.</li>
                </ul>
                <h6>2.2 Late Cancellation / No-Show</h6>
                <ul>
                  <li>Cancellations made within 24 hours of the service or failure to attend ("No-Show") are
                    non-refundable.</li>
                </ul>
                <h6>2.3 Refund Timeline</h6>
                <ul>
                  <li>All approved refunds will be processed within 7–10 business days of cancellation confirmation.
                  </li>
                  <li>Refunds will be credited to the original payment method used.</li>
                </ul>
                <h5 class="mt-4">3. Shipping & Delivery Policy</h5>
                <p>(Applies only to any physical materials, certificates, or course kits.)</p>
                <h6>3.1 Mode of Delivery</h6>
                <ul>
                  <li>Digital services (e-certificates, e-materials) are delivered via email immediately upon
                    completion.</li>
                  <li>Physical materials are dispatched via courier to the address provided at registration.</li>
                </ul>
                <h6>3.2 Delivery Timeline</h6>
                <ul>
                  <li>Minimum: 7 business days</li>
                  <li>Maximum: 15 business days</li>
                  <li>You will receive tracking details via email/SMS once the order is dispatched.</li>
                </ul>
                <h6>3.3 Delays</h6>
                <p>While we strive to meet timelines, occasional delays due to logistics or force majeure may occur. We
                  will notify you of any expected delays promptly.</p>
                <h5 class="mt-4">4. Privacy Policy</h5>
                <h6>4.1 Information We Collect</h6>
                <ul>
                  <li>Personal Data: Name, email, phone number, billing address.</li>
                  <li>Usage Data: Session logs, device information, IP address.</li>
                </ul>
                <h6>4.2 How We Use Your Data</h6>
                <ul>
                  <li>To process payments, confirm bookings, and deliver services.</li>
                  <li>To send service-related notifications, updates, and marketing communications (you may opt out at
                    any time).</li>
                </ul>
                <h6>4.3 Data Sharing</h6>
                <ul>
                  <li>We do not sell or rent your personal data.</li>
                  <li>We may share data with:<ul>
                      <li>Razorpay (for payment processing)</li>
                      <li>Authorized service providers (e.g., courier companies)</li>
                      <li>Regulatory authorities if required by law.</li>
                    </ul>
                  </li>
                </ul>
              
              </div>
            </div>

            <!-- FORM PANEL -->
            <div class="col-lg-7 col-12 form-panel order-1 order-lg-2">
              <div class="form-header">
                <h4 class="text-black mb-0">Internship Application Form</h4>
                <p class="text-muted small mb-0">Apply online for professional psychology internship programs</p>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>

              <?php if ($msg): ?>
                <div class="alert alert-<?php echo $msgType; ?> m-3"><?php echo $msg; ?></div>
              <?php endif; ?>

            <form class="p-4 ignou-internship-form" method="POST" enctype="multipart/form-data" id="appForm">
  <input type="hidden" name="submit_application" value="1">

  <!-- Hidden Payment Fields -->
  <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
  <input type="hidden" name="payment_method" id="payment_method"
    value="<?php echo ($paymentEnabled == '1') ? 'razorpay' : 'manual'; ?>">
  <input type="hidden" name="program_id" id="programId">
  <input type="hidden" name="program_name" id="programName">

  <div class="row g-3">

    <div class="col-md-6">
      <label>Email*</label>
      <input type="email" class="form-control" name="email"
        placeholder="Enter your email address"
        value="<?php echo htmlspecialchars($user['Email']); ?>" required>
    </div>

      <div class="col-md-6">
      <label>WhatsApp / Contact Number*</label>
      <input type="tel" class="form-control" name="phone"
        placeholder="Enter mobile number"
        value="<?php echo htmlspecialchars($user['Phone']); ?>" required>
    </div>

    <div class="col-md-6">
      <label>Prefix*</label>
      <select class="form-select" name="prefix" required>
        <option value="">Select</option>
        <option value="Mr">Mr.</option>
        <option value="Ms">Ms.</option>
        <option value="Dr">Dr.</option>
      </select>
    </div>

    <div class="col-md-6">
      <label>Name (as per academic documents)*</label>
      <input type="text" class="form-control" name="full_name"
        placeholder="Enter full name as per records"
        value="<?php echo htmlspecialchars($user['Name']); ?>" required>
    </div>

      <div class="col-md-6">
      <label>Age (Years)*</label>
      <input type="number" class="form-control" name="age" placeholder="Enter your age" required>
    </div>

    <div class="col-md-6">
      <label>Gender*</label>
      <select class="form-select" name="gender" required>
        <option value="">Select Gender</option>
        <option>Female</option>
        <option>Male</option>
        <option>Prefer not to say</option>
      </select>
    </div>

      <div class="col-12">
      <label>Address*</label>
      <textarea class="form-control" name="address" rows="2"
        placeholder="Enter complete address"
        required><?php echo htmlspecialchars($user['Address']); ?></textarea>
    </div>
    <div class="col-md-6">
      <label>Course Pursuing*</label>
      <select class="form-select" name="course_pursuing" required>
        <option value="">Select Course</option>
        <?php foreach ($academicCourses as $ac): ?>
          <option value="<?php echo htmlspecialchars($ac); ?>">
            <?php echo htmlspecialchars($ac); ?>
          </option>
        <?php endforeach; ?>
        <option value="Others">Others</option>
      </select>
    </div>


    <div class="col-md-6">
      <label>Name of Institute / College / University*</label>
      <input type="text" class="form-control" name="institute_name"
        placeholder="Enter institute / university name" required>
    </div>

         <div class="col-md-6">
                    <label>Internship Specialisation*</label>
                    <select class="form-select" name="specialization">
                      <option value="">Select Specialization</option>
                      <?php foreach ($specializations as $sp): ?>
                        <option value="<?php echo htmlspecialchars($sp); ?>">
                            <?php echo htmlspecialchars($sp); ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>

    <div class="col-md-6">
      <label>Internship Program*</label>
      <select class="form-select bg-light fw-semibold" id="formProgramSelect"
        onchange="updateFee()" required>
        <option value="">-- Select Internship Program --</option>
      </select>
    </div>

  
    <div class="col-md-12">
      <label>Proposed Date of Joining Internship*</label>
      <input type="date" class="form-control" name="start_date" required>
    </div>

    <div class="col-12">
     
      <div class="row g-3">

        <div class="col-md-12">
          <label>Upload Recommendation Letter (Institute / University)</label>
          <input type="file" class="form-control" name="rec_letter">
        </div>

     

        <div class="col-md-12">
          <label>Upload ID Proof (Aadhaar / PAN / Voter ID / College ID)</label>
          <input type="file" class="form-control" name="id_proof">
        </div>

        <div class="col-md-12">
          <label>Upload Passport Size Photograph</label>
          <input type="file" class="form-control" name="photo">
        </div>

      </div>
    </div>
  <div class="col-md-12">
      <label>Total Fee (INR)*</label>
      <input type="number" class="form-control bg-light"
        id="totalFeeDisplay" placeholder="Auto calculated" readonly>
    </div>
    <div class="col-12 mt-3 p-3 border rounded bg-white">
      <label class="fw-bold mb-2 d-block">Payment Mode:</label>

      <div class="d-flex gap-3">
        <?php if ($paymentEnabled == '1'): ?>
          <label>
            <input type="radio" name="payment_method_sel" value="razorpay"
              checked onclick="togglePay('razorpay')">
            Online (Razorpay)
          </label>
        <?php endif; ?>

        <!-- <label>
          <input type="radio" name="payment_method_sel" value="manual"
            <?php echo ($paymentEnabled != '1') ? 'checked' : ''; ?>
            onclick="togglePay('manual')">
          Manual Upload
        </label> -->
      </div>

      <!-- <div id="manual-pay-div" class="mt-3"
        style="display: <?php echo ($paymentEnabled != '1') ? 'block' : 'none'; ?>;">
        <label>Upload Payment Receipt</label>
        <input type="file" class="form-control" name="fee_receipt">
        <small class="text-muted">Bank Details: IMHRC, Account: XXXXX, IFSC: XXXX</small>
      </div> -->
    </div>

    <div class="col-12">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" required>
        <label class="form-check-label">I agree to the terms & conditions</label>
      </div>
    </div>

    <div class="col-12">
      <button type="button" onclick="handleFormSubmit()"
        class="btn btn-success w-100 fw-bold">
        <?php echo ($paymentEnabled == '1') ? 'Pay & Submit Application' : 'Submit Application'; ?>
      </button>

      <button type="submit" id="realSubmit" style="display:none;"></button>
    </div>

  </div>
</form>

            </div>

          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="modal fade" id="imhrcInternshipModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content internship-modal">

        <div class="modal-body p-0">
          <div class="row g-0">

            <!-- LEFT INFO PANEL (Static Terms) -->
            <div class="col-lg-5 col-12 info-panel order-2 order-lg-1">
              <div style="overflow-y:auto; max-height:80vh; padding-right:10px;">
                <h4 class="mb-3 text-warning">Terms, Conditions & Policies</h4>

                <h5>1. Terms & Conditions</h5>
                <h6>1.1 Scope of Services</h6>
                <p>Hausla Wellness Pvt Ltd ("Company") provides:
                <ul>
                  <li>Counselling & mental healthcare services (online/offline)</li>
                  <li>Educational programmes, internships, and training (online/offline)</li>
                </ul>
                </p>
                <h6>1.2 Payment</h6>
                <ul>
                  <li>All fees are quoted in Indian Rupees (INR) and are exclusive of applicable taxes.</li>
                  <li>Payments are due in full at the time of booking or registration.</li>
                  <li>The Company reserves the right to suspend or cancel access if payment is not received.</li>
                </ul>
                <h6>1.3 Bookings & Confirmation</h6>
                <ul>
                  <li>Bookings are confirmed only upon successful payment via Razorpay.</li>
                  <li>You will receive an email/SMS confirmation including service details and dates.</li>
                </ul>
                <h6>1.4 User Obligations</h6>
                <ul>
                  <li>You agree to provide accurate personal and billing information.</li>
                  <li>You are responsible for maintaining confidentiality of any account credentials.</li>
                </ul>
                <h5 class="mt-4">2. Refund & Cancellation Policy</h5>
                <h6>2.1 Free Cancellation Window</h6>
                <ul>
                  <li>Training, academic Fee paid is non-refundable</li>
                  <li>Counselling Sessions, if cancelled within the 24 hours of scheduling, 50% fee refund will be done
                    only.</li>
                </ul>
                <h6>2.2 Late Cancellation / No-Show</h6>
                <ul>
                  <li>Cancellations made within 24 hours of the service or failure to attend ("No-Show") are
                    non-refundable.</li>
                </ul>
                <h6>2.3 Refund Timeline</h6>
                <ul>
                  <li>All approved refunds will be processed within 7–10 business days of cancellation confirmation.
                  </li>
                  <li>Refunds will be credited to the original payment method used.</li>
                </ul>
                <h5 class="mt-4">3. Shipping & Delivery Policy</h5>
                <p>(Applies only to any physical materials, certificates, or course kits.)</p>
                <h6>3.1 Mode of Delivery</h6>
                <ul>
                  <li>Digital services (e-certificates, e-materials) are delivered via email immediately upon
                    completion.</li>
                  <li>Physical materials are dispatched via courier to the address provided at registration.</li>
                </ul>
                <h6>3.2 Delivery Timeline</h6>
                <ul>
                  <li>Minimum: 7 business days</li>
                  <li>Maximum: 15 business days</li>
                  <li>You will receive tracking details via email/SMS once the order is dispatched.</li>
                </ul>
                <h6>3.3 Delays</h6>
                <p>While we strive to meet timelines, occasional delays due to logistics or force majeure may occur. We
                  will notify you of any expected delays promptly.</p>
                <h5 class="mt-4">4. Privacy Policy</h5>
                <h6>4.1 Information We Collect</h6>
                <ul>
                  <li>Personal Data: Name, email, phone number, billing address.</li>
                  <li>Usage Data: Session logs, device information, IP address.</li>
                </ul>
                <h6>4.2 How We Use Your Data</h6>
                <ul>
                  <li>To process payments, confirm bookings, and deliver services.</li>
                  <li>To send service-related notifications, updates, and marketing communications (you may opt out at
                    any time).</li>
                </ul>
                <h6>4.3 Data Sharing</h6>
                <ul>
                  <li>We do not sell or rent your personal data.</li>
                  <li>We may share data with:<ul>
                      <li>Razorpay (for payment processing)</li>
                      <li>Authorized service providers (e.g., courier companies)</li>
                      <li>Regulatory authorities if required by law.</li>
                    </ul>
                  </li>
                </ul>
              
              </div>
            </div>

            <!-- FORM PANEL -->
            <div class="col-lg-7 col-12 form-panel order-1 order-lg-2">
              <div class="form-header">
                <h4 class="text-black mb-0">IGNOU Curriculum Internship Registration Form</h4>
                <p class="text-muted small mb-0">This form is strictly for IGNOU students applying for curriculum-based internship.</p>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>

              <?php if ($msg): ?>
                <div class="alert alert-<?php echo $msgType; ?> m-3"><?php echo $msg; ?></div>
              <?php endif; ?>

            <form class="p-4 ignou-internship-form" method="POST" enctype="multipart/form-data" id="appForm">
  <input type="hidden" name="submit_application" value="1">

  <!-- Hidden Payment Fields -->
  <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
  <input type="hidden" name="payment_method" id="payment_method"
    value="<?php echo ($paymentEnabled == '1') ? 'razorpay' : 'manual'; ?>">
  <input type="hidden" name="program_id" id="programId">
  <input type="hidden" name="program_name" id="programName">

  <div class="row g-3">

    <div class="col-md-6">
      <label>Email*</label>
      <input type="email" class="form-control" name="email"
        placeholder="Enter your email address"
        value="<?php echo htmlspecialchars($user['Email']); ?>" required>
    </div>

      <div class="col-md-6">
      <label>WhatsApp / Contact Number*</label>
      <input type="tel" class="form-control" name="phone"
        placeholder="Enter mobile number"
        value="<?php echo htmlspecialchars($user['Phone']); ?>" required>
    </div>

    <div class="col-md-6">
      <label>Prefix*</label>
      <select class="form-select" name="prefix" required>
        <option value="">Select</option>
        <option value="Mr">Mr.</option>
        <option value="Ms">Ms.</option>
        <option value="Dr">Dr.</option>
      </select>
    </div>

    <div class="col-md-6">
      <label>Name (as per academic documents)*</label>
      <input type="text" class="form-control" name="full_name"
        placeholder="Enter full name as per records"
        value="<?php echo htmlspecialchars($user['Name']); ?>" required>
    </div>

      <div class="col-md-6">
      <label>Age (Years)*</label>
      <input type="number" class="form-control" name="age" placeholder="Enter your age" required>
    </div>

    <div class="col-md-6">
      <label>Gender*</label>
      <select class="form-select" name="gender" required>
        <option value="">Select Gender</option>
        <option>Female</option>
        <option>Male</option>
        <option>Prefer not to say</option>
      </select>
    </div>

      <div class="col-12">
      <label>Address*</label>
      <textarea class="form-control" name="address" rows="2"
        placeholder="Enter complete address"
        required><?php echo htmlspecialchars($user['Address']); ?></textarea>
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


  <div class="col-md-12">
      <label>Total Fee (INR)*</label>
      <input type="number" class="form-control bg-light"
        id="totalFeeDisplay" placeholder="Auto calculated" readonly>
    </div>
    <div class="col-12 mt-3 p-3 border rounded bg-white">
      <label class="fw-bold mb-2 d-block">Payment Mode:</label>

      <div class="d-flex gap-3">
        <?php if ($paymentEnabled == '1'): ?>
          <label>
            <input type="radio" name="payment_method_sel" value="razorpay"
              checked onclick="togglePay('razorpay')">
            Online (Razorpay)
          </label>
        <?php endif; ?>

        <!-- <label>
          <input type="radio" name="payment_method_sel" value="manual"
            <?php echo ($paymentEnabled != '1') ? 'checked' : ''; ?>
            onclick="togglePay('manual')">
          Manual Upload
        </label> -->
      </div>

      <!-- <div id="manual-pay-div" class="mt-3"
        style="display: <?php echo ($paymentEnabled != '1') ? 'block' : 'none'; ?>;">
        <label>Upload Payment Receipt</label>
        <input type="file" class="form-control" name="fee_receipt">
        <small class="text-muted">Bank Details: IMHRC, Account: XXXXX, IFSC: XXXX</small>
      </div> -->
    </div>

    <div class="col-12">
      <div class="form-check py-3">
        <input class="form-check-input" type="checkbox" required>
        <label class="form-check-label">  I hereby declare that the information furnished above is true
      and correct to the best of my knowledge. I understand that this
      internship is part of my IGNOU curriculum and any false
      information may lead to rejection.</label>
      </div>
    </div>

    <div class="col-12">
      <button type="button" onclick="handleFormSubmit()"
        class="btn btn-success w-100 fw-bold">
        <?php echo ($paymentEnabled == '1') ? 'Pay & Submit Application' : 'Submit Application'; ?>
      </button>

      <button type="submit" id="realSubmit" style="display:none;"></button>
    </div>

  </div>
</form>

            </div>

          </div>
        </div>
      </div>
    </div>
  </div>


  <?php include 'includes/footer.php'; ?>

  <!-- JS Files -->
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
  <script src="assets/js/custom.js"></script>

  <script>
    // Pass DB data to JS
    const internshipsData = <?php echo json_encode($internships); ?>;

    const modalEl = document.getElementById('internshipApply');
    const modal = new bootstrap.Modal(modalEl);

    // Populate Program Dropdown
    const progSelect = document.getElementById('formProgramSelect');
    internshipsData.forEach(p => {
      let opt = document.createElement('option');
      opt.value = p.InternshipId;
      opt.text = `${p.Title} - ₹${p.Fee}`;
      opt.dataset.fee = p.Fee;
      opt.dataset.name = p.Title;
      progSelect.appendChild(opt);
    });

    // Update Fee when dropdown changes
    function updateFee() {
      const sel = progSelect.options[progSelect.selectedIndex];
      if (sel.value) {
        const totalFee = parseFloat(sel.dataset.fee);
        // Default 50%
        const minFee = totalFee / 2;
        document.getElementById('totalFeeDisplay').value = totalFee;
        document.getElementById('formAmount').value = minFee;
        document.getElementById('programId').value = sel.value;
        document.getElementById('programName').value = sel.dataset.name;
        calculateBalance();
      } else {
        document.getElementById('totalFeeDisplay').value = '';
        document.getElementById('formAmount').value = '';
        document.getElementById('balanceDisplay').innerText = '₹0';
      }
    }

    // Calculate Balance dynamically
    function calculateBalance() {
      const total = parseFloat(document.getElementById('totalFeeDisplay').value) || 0;
      const paid = parseFloat(document.getElementById('formAmount').value) || 0;
      const balance = total - paid;

      const balanceEl = document.getElementById('balanceDisplay');
      if (balance > 0) {
        balanceEl.innerHTML = `<span class="text-danger">₹${balance} (Pending)</span>`;
      } else {
        balanceEl.innerHTML = `<span class="text-success">₹0 (Paid Full)</span>`;
      }
    }

    // Bind dropdown change
    progSelect.addEventListener('change', updateFee);

    function openApplyModal(progData = null) {
      document.getElementById('appForm').reset();

      // Auto-select if button clicked from specific card
      if (progData) {
        // Find option by value
        for (let i = 0; i < progSelect.options.length; i++) {
          if (progSelect.options[i].value == progData.InternshipId) {
            progSelect.selectedIndex = i;
            break;
          }
        }
        updateFee(); // Trigger fee update
      }

      modal.show();
    }

    <?php if (!empty($msg)): ?>
      modal.show();
    <?php endif; ?>

    function togglePay(method) {
      document.getElementById('payment_method').value = method;
      if (method === 'manual') {
        document.getElementById('manual-pay-div').style.display = 'block';
      } else {
        document.getElementById('manual-pay-div').style.display = 'none';
      }
    }

    function handleFormSubmit() {
      const method = document.getElementById('payment_method').value;
      const amount = parseFloat(document.getElementById('formAmount').value);
      const totalFee = parseFloat(document.getElementById('totalFeeDisplay').value);
      const name = document.querySelector('input[name="full_name"]').value;
      const email = document.querySelector('input[name="email"]').value;
      const phone = document.querySelector('input[name="phone"]').value;

      // Basic Validation
      if (!name || !email || !phone || !amount) {
        alert("Please fill all required fields first.");
        return;
      }

      // 50% Validation Rule
      if (totalFee > 0 && amount < (totalFee / 2)) {
        alert("Minimum payment of 50% (₹" + (totalFee / 2) + ") is required to proceed.");
        return;
      }

      if (method === 'razorpay' && amount > 0) {
        // Init Razorpay
        var options = {
          "key": "<?php echo $keyId; ?>",
          "amount": amount * 100,
          "currency": "<?php echo $currency; ?>",
          "name": "IMHRC Internship",
          "description": "Fee Payment",
          "image": "assets/img/logo.png",
          "handler": function (response) {
            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
            document.getElementById('realSubmit').click(); // Submit form after payment
          },
          "prefill": { "name": name, "email": email, "contact": phone },
          "theme": { "color": "#0b2c57" }
        };
        var rzp1 = new Razorpay(options);
        rzp1.open();

        rzp1.on('payment.failed', function (response) {
          alert("Payment Failed: " + response.error.description);
        });
      } else {
        // Manual or Free
        document.getElementById('realSubmit').click();
      }
    }
  </script>

    <script>
    // Pass DB data to JS
    const internshipsData = <?php echo json_encode($internships); ?>;

    const modalEl = document.getElementById('imhrcInternshipModal');
    const modal = new bootstrap.Modal(modalEl);

    // Populate Program Dropdown
    const progSelect = document.getElementById('formProgramSelect');
    internshipsData.forEach(p => {
      let opt = document.createElement('option');
      opt.value = p.InternshipId;
      opt.text = `${p.Title} - ₹${p.Fee}`;
      opt.dataset.fee = p.Fee;
      opt.dataset.name = p.Title;
      progSelect.appendChild(opt);
    });

    // Update Fee when dropdown changes
    function updateFee() {
      const sel = progSelect.options[progSelect.selectedIndex];
      if (sel.value) {
        const totalFee = parseFloat(sel.dataset.fee);
        // Default 50%
        const minFee = totalFee / 2;
        document.getElementById('totalFeeDisplay').value = totalFee;
        document.getElementById('formAmount').value = minFee;
        document.getElementById('programId').value = sel.value;
        document.getElementById('programName').value = sel.dataset.name;
        calculateBalance();
      } else {
        document.getElementById('totalFeeDisplay').value = '';
        document.getElementById('formAmount').value = '';
        document.getElementById('balanceDisplay').innerText = '₹0';
      }
    }

    // Calculate Balance dynamically
    function calculateBalance() {
      const total = parseFloat(document.getElementById('totalFeeDisplay').value) || 0;
      const paid = parseFloat(document.getElementById('formAmount').value) || 0;
      const balance = total - paid;

      const balanceEl = document.getElementById('balanceDisplay');
      if (balance > 0) {
        balanceEl.innerHTML = `<span class="text-danger">₹${balance} (Pending)</span>`;
      } else {
        balanceEl.innerHTML = `<span class="text-success">₹0 (Paid Full)</span>`;
      }
    }

    // Bind dropdown change
    progSelect.addEventListener('change', updateFee);

    function openApplyModal(progData = null) {
      document.getElementById('appForm').reset();

      // Auto-select if button clicked from specific card
      if (progData) {
        // Find option by value
        for (let i = 0; i < progSelect.options.length; i++) {
          if (progSelect.options[i].value == progData.InternshipId) {
            progSelect.selectedIndex = i;
            break;
          }
        }
        updateFee(); // Trigger fee update
      }

      modal.show();
    }

    <?php if (!empty($msg)): ?>
      modal.show();
    <?php endif; ?>

    function togglePay(method) {
      document.getElementById('payment_method').value = method;
      if (method === 'manual') {
        document.getElementById('manual-pay-div').style.display = 'block';
      } else {
        document.getElementById('manual-pay-div').style.display = 'none';
      }
    }

    function handleFormSubmit() {
      const method = document.getElementById('payment_method').value;
      const amount = parseFloat(document.getElementById('formAmount').value);
      const totalFee = parseFloat(document.getElementById('totalFeeDisplay').value);
      const name = document.querySelector('input[name="full_name"]').value;
      const email = document.querySelector('input[name="email"]').value;
      const phone = document.querySelector('input[name="phone"]').value;

      // Basic Validation
      if (!name || !email || !phone || !amount) {
        alert("Please fill all required fields first.");
        return;
      }

      // 50% Validation Rule
      if (totalFee > 0 && amount < (totalFee / 2)) {
        alert("Minimum payment of 50% (₹" + (totalFee / 2) + ") is required to proceed.");
        return;
      }

      if (method === 'razorpay' && amount > 0) {
        // Init Razorpay
        var options = {
          "key": "<?php echo $keyId; ?>",
          "amount": amount * 100,
          "currency": "<?php echo $currency; ?>",
          "name": "IMHRC Internship",
          "description": "Fee Payment",
          "image": "assets/img/logo.png",
          "handler": function (response) {
            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
            document.getElementById('realSubmit').click(); // Submit form after payment
          },
          "prefill": { "name": name, "email": email, "contact": phone },
          "theme": { "color": "#0b2c57" }
        };
        var rzp1 = new Razorpay(options);
        rzp1.open();

        rzp1.on('payment.failed', function (response) {
          alert("Payment Failed: " + response.error.description);
        });
      } else {
        // Manual or Free
        document.getElementById('realSubmit').click();
      }
    }
  </script>


</body>

</html>