<?php
// Include database connection
// Adjust path if needed. If this is in root: 'admin/includes/db.php'
require_once 'admin/includes/db.php'; 

// --- SEARCH LOGIC ---
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$whereClause = "WHERE Status = 'active'";

if (!empty($search)) {
    $s = $conn->real_escape_string($search);
    $whereClause .= " AND Title LIKE '%$s%'";
}

// --- FETCH SERVICES ---
$assessmentServices = [];
$specializedServices = [];
$therapeuticServices = [];

$sql = "SELECT * FROM clinical_services $whereClause ORDER BY DisplayOrder ASC";
$result = $conn->query($sql);

if ($result) {
    while($row = $result->fetch_assoc()) {
        if ($row['Category'] == 'Assessment') {
            $assessmentServices[] = $row;
        } elseif ($row['Category'] == 'Specialized') {
            $specializedServices[] = $row;
        } elseif ($row['Category'] == 'Therapeutic') {
            $therapeuticServices[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSS Links -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/boxicons.min.css">
    <link rel="stylesheet" href="assets/css/magnific-popup.min.css">
    <link rel="stylesheet" href="assets/css/flaticon.css">
    <link rel="stylesheet" href="assets/css/meanmenu.min.css">
    <link rel="stylesheet" href="assets/css/odometer.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dark.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="icon" type="image/png" href="assets/img/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <title>IMHRC - Clinical & Diagnostic Services</title>
   
<style>
.services-page{
  max-width:1300px;
  margin:auto;
  padding:40px 20px;
}

/* ---------- FILTER DESIGN ---------- */
.stylish-filter{
  display:grid;
  grid-template-columns:300px 300px;
  gap:24px;
  justify-content:center;
  margin-bottom:40px;
}

@media(max-width:768px){
  .stylish-filter{
    grid-template-columns:1fr;
  }
}

.field{
  position:relative;
}

.field label{
  font-weight:600;
  color:#666;
  margin-bottom:6px;
  display:block;
}

.field select,
.field input{
  width:100%;
  padding:15px 16px;
  border-radius:16px;
  border:none;
  background:#ffffff;
  box-shadow:0 10px 30px rgba(0,0,0,.08);
  font-size:14px;
  outline:none;
  transition:.3s;
}

.field select:focus,
.field input:focus{
  box-shadow:0 12px 35px rgba(76,110,255,.35);
}

.search-field img{
  position:absolute;
  right:18px;
  bottom:16px;
  width:18px;
  opacity:.6;
}

/* ---------- UNIQUE CARDS ---------- */
.unique-cards{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(190px,1fr));
  gap:26px;
}

.service-card-uniq{
  text-decoration:none;
  background:linear-gradient(180deg,#ffffff,#f5f8ff);
  border-radius:22px;
  padding:34px 24px 30px;
  position:relative;
  text-align:center;
  box-shadow:0 12px 35px rgba(0,0,0,.06);
  transition:.4s;
  color:#222;
  overflow:hidden;
  display: block; /* Ensure anchor works as block */
}

/* gradient ribbon */
.service-card-uniq:before{
  content:"";
  position:absolute;
  top:0;
  left:0;
  width:100%;
  height:6px;
  background:linear-gradient(90deg,#7aa2ff,#b388ff);
}

/* floating icon */
.icon-box{
  width:70px;
  height:70px;
  margin:auto;
  margin-bottom:16px;
  border-radius:22px;
  background:#eef2ff;
  display:flex;
  align-items:center;
  justify-content:center;
  box-shadow:0 10px 25px rgba(0,0,0,.08);
}

.icon-box img{
  width:32px;
  height: 32px; /* Ensure icon size */
  object-fit: contain;
}

.service-card-uniq h4{
  font-size:15px;
  font-weight:600;
  line-height:1.4;
  color: #222; /* Ensure text color */
}

/* hover effect */
.service-card-uniq:hover{
  transform:translateY(-12px);
  box-shadow:0 22px 55px rgba(0,0,0,.12);
  background:linear-gradient(180deg,#ffffff,#eff3ff);
}

.service-card-uniq:hover .icon-box{
  background:#ffffff;
}
h2.service-heading {
    font-size: 22px;
    padding-bottom: 20px;
    padding-top: 40px;
    color: #0b2c57;
    font-weight: 700;
}

/* Slider & FAQ Styles */
.bd-carousel .carousel-inner { max-height: 350px; }
.bd-carousel .carousel-item img { object-fit: cover; height: 350px; }
.carousel-caption { text-align: left !important; bottom: 20px; }
.accordion-button { font-size: 1.05rem; padding: 15px; }
.accordion-body { font-size: 0.95rem; }
</style>
</head>

<body>

    <?php include 'includes/header.php'; ?>
    
    <!-- Start Page Title Area -->
    <div class="page-title-wave">
        <div class="container">
            <h2>Clinical & Diagnostic Services</h2>
            <p class="inde">Home › Clinical & Diagnostic Services</p>
        </div>
        <svg class="wave" viewBox="0 0 1440 320" preserveAspectRatio="none">
            <path fill="#ffffff" fill-opacity="1" 
              d="M0,64L48,90.7C96,117,192,171,288,170.7C384,171,480,117,576,85.3C672,53,768,43,864,74.7C960,107,1056,181,1152,192C1248,203,1344,149,1392,122.7L1440,96L1440,320L1392,320C1344,320,1248,320,
              1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
            </path>
        </svg>
    </div>

    <!-- HERO / Filter Section -->
    <section class="services-page">
        <form method="GET" class="filter-row stylish-filter">
            <div class="field">
                <select name="location">
                    <option>All Locations</option>
                    <option>Delhi</option>
                    <option>Mumbai</option>
                    <option>Bangalore</option>
                    <option>Jaipur</option>
                </select>
            </div>
            <div class="field search-field">
                <input type="text" name="search" placeholder="Search services..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" style="border:none; background:none; position:absolute; right:15px; top:15px; cursor:pointer;">
                    <img src="assets/icons/search.svg" alt="Search">
                </button>
            </div>
        </form>

        <!-- CARDS SECTION -->
        <section class="services-section">

            <!-- 1. Assessment Services -->
            <?php if (!empty($assessmentServices)): ?>
            <h2 class="service-heading">Assessment Services</h2>
            <div class="services-grid unique-cards">
                <?php foreach ($assessmentServices as $service): ?>
                <a href="<?php echo htmlspecialchars($service['Link']); ?>" class="service-card-uniq">
                    <div class="icon-box">
                        <img src="<?php echo htmlspecialchars($service['Icon']); ?>" alt="icon" onerror="this.src='assets/img/default-icon.png'">
                    </div>
                    <h4><?php echo htmlspecialchars($service['Title']); ?></h4>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- 2. Specialized Services -->
            <?php if (!empty($specializedServices)): ?>
            <h2 class="service-heading">Specialized Services</h2>
            <div class="services-grid unique-cards">
                <?php foreach ($specializedServices as $service): ?>
                <a href="<?php echo htmlspecialchars($service['Link']); ?>" class="service-card-uniq">
                    <div class="icon-box">
                        <img src="<?php echo htmlspecialchars($service['Icon']); ?>" alt="icon" onerror="this.src='assets/img/default-icon.png'">
                    </div>
                    <h4><?php echo htmlspecialchars($service['Title']); ?></h4>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- 3. Therapeutic Services -->
            <?php if (!empty($therapeuticServices)): ?>
            <h2 class="service-heading">Therapeutic Services</h2>
            <div class="services-grid unique-cards">
                <?php foreach ($therapeuticServices as $service): ?>
                <a href="<?php echo htmlspecialchars($service['Link']); ?>" class="service-card-uniq">
                    <div class="icon-box">
                        <img src="<?php echo htmlspecialchars($service['Icon']); ?>" alt="icon" onerror="this.src='assets/img/default-icon.png'">
                    </div>
                    <h4><?php echo htmlspecialchars($service['Title']); ?></h4>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

        </section>
    </section>

    <!-- Highlights & Facilities -->
    <main class="mt-5 mb-5">
        <div class="container">
            <section class="mb-5">
                <h4 class="mb-4 text-center">Highlights & Facilities</h4>
                <div id="facCarousel" class="carousel slide bd-carousel" data-bs-ride="carousel">
                    <!-- Indicators -->
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#facCarousel" data-bs-slide-to="0" class="active" aria-current="true"></button>
                        <button type="button" data-bs-target="#facCarousel" data-bs-slide-to="1"></button>
                        <button type="button" data-bs-target="#facCarousel" data-bs-slide-to="2"></button>
                    </div>

                    <!-- Slides -->
                    <div class="carousel-inner rounded-3 shadow-sm overflow-hidden">
                        <div class="carousel-item active">
                            <img src="assets/img/imhrc-img.jpeg" class="d-block w-100" alt="Advanced Imaging">
                            <div class="carousel-caption text-start bg-dark bg-opacity-50 rounded p-2">
                                <h5>Advanced Imaging Centre</h5>
                                <p class="small">MRI, CT & Ultrasound with expert radiologists.</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="assets/img/imhrc-img.jpeg" class="d-block w-100" alt="Diagnostic Lab">
                            <div class="carousel-caption text-start bg-dark bg-opacity-50 rounded p-2">
                                <h5>24/7 Diagnostic Lab</h5>
                                <p class="small">Comprehensive pathology tests with quick turnaround.</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="assets/img/imhrc-img.jpeg" class="d-block w-100" alt="Therapy">
                            <div class="carousel-caption text-start bg-dark bg-opacity-50 rounded p-2">
                                <h5>Therapy Rooms</h5>
                                <p class="small">Private therapy rooms for various treatments.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Controls -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#facCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#facCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </section>

            <!-- FAQ Section -->
            <section class="mb-5">
                <h4 class="mb-4 text-center">Frequently Asked Questions</h4>
                <div class="accordion" id="faqAcc">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="q1">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#qa1">What services are included?</button>
                        </h2>
                        <div id="qa1" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
                            <div class="accordion-body">Imaging, laboratory diagnostics, specialist consultations, pre-surgical evaluations, and follow-up care.</div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="q2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#qa2">How to book an appointment?</button>
                        </h2>
                        <div id="qa2" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
                            <div class="accordion-body">Use the Book Appointment button, call our helpline, or visit the desired clinic location.</div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="q3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#qa3">Are consultations available online?</button>
                        </h2>
                        <div id="qa3" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
                            <div class="accordion-body">Yes, online consultations via video call are available for select services.</div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <!-- JS Files -->
    <script data-cfasync="false" src="../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/meanmenu.min.js"></script>
    <script src="assets/js/wow.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/magnific-popup.min.js"></script>
    <script src="assets/js/jarallax.min.js"></script>
    <script src="assets/js/appear.min.js"></script>
    <script src="assets/js/odometer.min.js"></script>
    <script src="assets/js/smoothscroll.min.js"></script>
    <script src="assets/js/form-validator.min.js"></script>
    <script src="assets/js/contact-form-script.js"></script>
    <script src="assets/js/ajaxchimp.min.js"></script>
    <script src="assets/js/custom.js"></script>
  
</body>
</html>