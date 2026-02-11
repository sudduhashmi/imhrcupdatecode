<?php
// Include database connection
require_once 'admin/includes/db.php'; 

// --- FETCH PROGRAMS FROM DB ---
$dbPrograms = [];
try {
    // Check if table exists to avoid errors
    $checkTable = $conn->query("SHOW TABLES LIKE 'academic_programs'");
    if($checkTable && $checkTable->num_rows > 0) {
        $sql = "SELECT * FROM academic_programs WHERE Status = 'active' ORDER BY DisplayOrder ASC";
        $result = $conn->query($sql);
        
        if ($result) {
            while($row = $result->fetch_assoc()) {
                // Fix Icon path
                $icon = !empty($row['Icon']) ? str_replace('../', '', $row['Icon']) : 'assets/img/logo.png';
                
                // Map ColorClass to Category/Level so filters work
                $category = 'certificate'; 
                $level = 'Certificate';
                
                switch($row['ColorClass']) {
                    case 'pink': $category = 'certificate'; $level = 'Executive'; break;
                    case 'orange': 
                    case 'blue': $category = 'certificate'; $level = 'Certificate'; break;
                    case 'teal': $category = 'ug'; $level = 'Undergraduate'; break;
                    case 'red': $category = 'pgd'; $level = 'PG Diploma'; break;
                    case 'dark': 
                    case 'green-1': $category = 'diploma'; $level = 'Diploma/Degree'; break;
                    case 'green': $category = 'diploma'; $level = 'Diploma'; break;
                    default: $category = 'certificate'; $level = 'Program'; break;
                }

                // Prepare Fee
                $feeDisplay = ($row['Fee'] > 0) ? number_format($row['Fee']) : 'Contact Us';

                // Generate URL-friendly Slug (Title Name for URL)
                // e.g. "Clinical Psychology" -> "Clinical-Psychology"
                $slug = urlencode(str_replace(' ', '-', $row['Title']));

                // Prepare Array for JS
                $dbPrograms[] = [
                    'id' => $row['ProgramId'],
                    'slug' => $slug, // Added Slug
                    'title' => $row['Title'],
                    'desc' => $row['Description'],
                    'icon' => $icon,
                    'link' => $row['Link'],
                    'category' => $category,
                    'level' => $level,
                    'color' => $row['ColorClass'],
                    'mode' => 'Offline', 
                    'duration' => 'View Details', 
                    'fee' => $feeDisplay 
                ];
            }
        }
    }
} catch (Exception $e) { }
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
    <title>Academic Programs - IMHRC</title>
    <style>
    :root{
      --brand:#0b2c4d;
      --accent:#f6b400;
      --soft:#f4f7fb;
      --muted:#6b7280;
      --card-radius:14px;
    }

    /* HERO */
    .hero{
      color:#fff;
      padding:56px 0;
      border-bottom-left-radius:30px;
      border-bottom-right-radius:30px;
    }
    .hero .badge-accent{
      background: rgba(246,180,0,0.12);
      color: var(--accent);
      font-weight:700;
      padding:8px 14px;
      border-radius:999px;
    }
    .hero h1{ font-size:34px; line-height:1.08; margin-top:8px; font-weight:700; color: #0b2c4d; }
    .hero p{ color: #555; margin-top:12px; }

    /* SECTION TITLE */
    .section-title{
      font-size:28px; font-weight:700; position:relative; padding-left:18px; margin-bottom:20px;
    }
    .section-title::before{
      content:""; position:absolute; left:0; top:6px; width:6px; height:34px; background:var(--accent); border-radius:4px;
    }

    /* Tab & filters */
    .category-tabs .nav-link{ color:var(--brand); font-weight:600; border-radius:12px; cursor: pointer; }
    .category-tabs .nav-link.active{ background:var(--brand); color:#fff; }

    /* Program card */
    .prog-card{
      background: linear-gradient(180deg, #ffffff, #ffffff);
      border-radius:var(--card-radius);
      padding:20px;
      border: 1px solid rgb(246 180 0 / 77%);
      transition: transform .22s, box-shadow .22s;
      height:100%;
      display:flex; flex-direction:column; justify-content:space-between;
    }
    .prog-card:hover{
      transform: translateY(-8px);
      box-shadow: 0 20px 45px rgba(11,44,77,0.08);
    }
    .prog-badge{ display:inline-block; padding:6px 10px; background:var(--soft); color:var(--brand); border-radius:999px; font-weight:700; font-size:13px; }
    .prog-meta{ color:var(--muted); font-size:14px; margin-top:8px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    .prog-footer{ display:flex; flex-direction: column; gap:10px; margin-top:14px; }
    
    .btn-brochure {
        width: 100%;
        font-size: 0.85rem;
        font-weight: 600;
        border: 1px dashed var(--brand);
        color: var(--brand);
        border-radius: 8px;
        padding: 8px;
        background: transparent;
        transition: 0.3s;
    }
    .btn-brochure:hover {
        background: #f0f4ff;
        color: #000;
    }

    .btn-enroll{ background:var(--accent); color:#000; font-weight:700; border-radius:50px; padding:8px 18px; border:none; text-decoration: none; display: inline-block; text-align: center; }
    .btn-enroll:hover{ background:#e0a800; color: #000; }

    /* info column */
    .info-panel{
      background: linear-gradient(135deg,#0b2545,#123b6f);
      color:#fff; border-radius:14px; padding:26px;
    }
    .info-panel li { margin-bottom: 8px; color: #f6b400; }
    .info-panel h4 { color: #f6b400; }

    /* outcomes/outlines */
    .outline-box{ background:#fff; border-radius:14px; padding:20px; border:1px solid rgba(11,44,77,0.05); }

    /* FAQ */
    .accordion-button{ font-weight:700; color:var(--brand); background: #e9e9e9; }
    .accordion-item{ border-radius:12px; overflow:hidden; margin-bottom:10px; border:none; }

    /* responsive tweaks */
    @media (max-width:991px){ .hero h1{ font-size:28px; } }
    @media (max-width:767px){ .hero{ padding:36px 0; border-radius:0 0 18px 18px; } .info-panel{ display:none; } }
    
    .form-control { color: #324cc5; border: 1px solid #d0c7c7; background-color: transparent; border-radius: 0; font-size: 16px; width: 100%; }
    
    /* Color Themes */
    .purple { --accent-color: #6a11cb; }
    .blue { --accent-color: #1cb5e0; }
    .green { --accent-color: #11998e; }
    .orange { --accent-color: #f7971e; }
    .pink { --accent-color: #ff758c; }
    .teal { --accent-color: #43cea2; }
    .red { --accent-color: #ff416c; }
    .dark { --accent-color: #232526; }
    .green-1 { --accent-color: #4dd50b; }
    </style>
</head>

<body>

   <?php include 'includes/header.php'; ?>
   
   <!-- Start Page Title Area -->
   <div class="page-title-wave">
     <div class="container">
       <h2>Academic Programs</h2>
     </div>

     <svg class="wave" viewBox="0 0 1440 320" preserveAspectRatio="none">
       <path fill="#ffffff" fill-opacity="1" 
         d="M0,64L48,90.7C96,117,192,171,288,170.7C384,171,480,117,576,85.3C672,53,768,43,864,74.7C960,107,1056,181,1152,192C1248,203,1344,149,1392,122.7L1440,96L1440,320L1392,320C1344,320,1248,320,
         1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
       </path>
     </svg>
   </div>

  <!-- HERO -->
  <header class="hero">
    <div class="container">
      <div class="row gx-5 align-items-center">
        <div class="col-lg-8">
          <span class="badge-accent">Explore Our Offerings</span>
          <h1>Academic & Professional Programs at IMHRC</h1>
          <p>Explore IMHRC’s certificate, diploma, undergraduate and postgraduate programs. Industry-aligned curriculum, expert faculty, clinical exposure and recognized certifications to launch your career in mental health.</p>
          <div class="mt-3">
            <a href="#programs" class="btn btn-outline-dark btn-lg me-2">Browse Programs</a>
          </div>
        </div>

        <div class="col-lg-4 text-center">
          <img src="assets/img/images.jpeg" alt="academic" class="img-fluid rounded-3 shadow" style="max-height:220px; object-fit:cover;">
        </div>
      </div>
    </div>
  </header>

  <!-- MAIN -->
  <main class="py-5">
    <div class="container">
      <div class="row gy-4">

        <!-- LEFT INFO -->
        <aside class="col-lg-4">
          <div class="info-panel">
            <h4 class="fw-bold">Why IMHRC Programs?</h4>
            <p>Practical, accredited programs crafted by clinical experts. Gain hands-on experience, case-based learning and a recognized certificate.</p>

            <ul class="list-unstyled mt-3">
              <li><i class="bi bi-check2-circle me-2"></i> Clinical & Counselling focus</li>
              <li><i class="bi bi-check2-circle me-2"></i> Online & Offline delivery</li>
              <li><i class="bi bi-check2-circle me-2"></i> Internship support</li>
              <li><i class="bi bi-check2-circle me-2"></i> Industry recognized</li>
            </ul>

            <div class="mt-4">
              <a href="contact-us.php" class="btn btn-enroll w-100 text-center">Enquire Now</a>
            </div>

            <!-- Program Types Section -->
            <div class="mt-5">
                <h5 class="fw-bold text-white mb-3 border-bottom pb-2">Program Types</h5>
                <ul class="list-unstyled">
                    <li><i class="bi bi-caret-right-fill me-2"></i> Certificate & Online Programs</li>
                    <li><i class="bi bi-caret-right-fill me-2"></i> Diploma Programs</li>
                    <li><i class="bi bi-caret-right-fill me-2"></i> Undergraduate Programs</li>
                    <li><i class="bi bi-caret-right-fill me-2"></i> Postgraduate Diploma</li>
                    <li><i class="bi bi-caret-right-fill me-2"></i> Postgraduate Programs</li>
                    <li><i class="bi bi-caret-right-fill me-2"></i> Doctoral Research (Ph.D.)</li>
                </ul>
            </div>
          </div>
        </aside>

        <!-- RIGHT CONTENT -->
        <section class="col-lg-8">

          <!-- Tabs / Filters -->
          <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
            <ul class="nav category-tabs" role="tablist">
              <li class="nav-item"><a class="nav-link active" data-cat="all" onclick="filterCategory(event,'all')">All</a></li>
              <li class="nav-item"><a class="nav-link" data-cat="online" onclick="filterCategory(event,'online')">Academic & Online</a></li>
              <li class="nav-item"><a class="nav-link" data-cat="diploma" onclick="filterCategory(event,'diploma')">Diploma</a></li>
              <li class="nav-item"><a class="nav-link" data-cat="ug" onclick="filterCategory(event,'ug')">Undergraduate</a></li>
              <li class="nav-item"><a class="nav-link" data-cat="pgd" onclick="filterCategory(event,'pgd')">Postgraduate Diploma</a></li>
            </ul>

            <div class="d-flex gap-2">
              <input id="searchBox" oninput="applyFilters()" class="form-control form-control-sm" style="max-width:220px" placeholder="Search programs">
            </div>
          </div>

          <!-- Programs Grid -->
          <div id="programs" class="row g-3">
            <!-- Cards rendered by JS -->
          </div>

        </section>
      </div>

      <!-- FAQs -->
      <div class="row mt-5">
        <div class="col-lg-12">
          <h3 class="section-title">Frequently Asked Questions</h3>
          <div class="accordion" id="faqAcc">
            <div class="accordion-item">
              <h2 class="accordion-header" id="faqOne">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">Who can apply?</button>
              </h2>
              <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
                <div class="accordion-body">Students, graduates, and working professionals from psychology backgrounds can apply.</div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="faqTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">Are programs available online?</button>
              </h2>
              <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
                <div class="accordion-body">Yes, we offer Online, Offline, and Hybrid modes depending on the course.</div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </main>

  <?php include 'includes/footer.php'; ?>

    <!-- JS Files -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/meanmenu.min.js"></script>
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

    <script>
    // DYNAMIC DATA FROM DATABASE
    const programs = <?php echo json_encode($dbPrograms); ?>;

    const programsContainer = document.getElementById('programs');
    const searchBox = document.getElementById('searchBox');

    function renderPrograms(list){
      programsContainer.innerHTML = '';
      if(!list.length){
        programsContainer.innerHTML = '<div class="col-12"><div class="outline-box text-center p-5 text-muted">No programs found matching your criteria.</div></div>';
        return;
      }
      list.forEach(p=>{
        const col = document.createElement('div');
        col.className = 'col-md-6 col-lg-6';
        
        // Fee formatting
        let feeHtml = p.fee;
        if(p.fee.includes('Contact')) {
             feeHtml = p.fee; 
        } else {
             feeHtml = `<strong>Fees:</strong> ₹${p.fee.replace('₹ ', '')}`;
        }
        
        col.innerHTML = `
          <article class="prog-card h-100 ${p.color}">
            <div>
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <span class="prog-badge">${p.level}</span>
                  <h5 class="mt-3">${p.title}</h5>
                </div>
                <div class="text-end">
                   <img src="${p.icon}" style="width:40px; height:40px; object-fit:contain;">
                </div>
              </div>

              <p class="prog-meta mt-2">${p.desc}</p>

              <div class="mt-3 small text-muted">
                <i class="bi bi-clock"></i> ${p.duration} &nbsp;|&nbsp; 
                ${feeHtml}
              </div>
            </div>

            <div class="prog-footer pt-3 border-top mt-3">
              <button class="btn btn-brochure" onclick="downloadBrochure('${p.title}')"><i class="bi bi-file-earmark-arrow-down"></i> Download Brochure</button>
              
              <div class="d-flex justify-content-between align-items-center w-100 mt-2">
                  <!-- Updated Link to Details Page using Slug -->
                  <a href="academic-program-details.php?course=${p.slug}" class="btn btn-link p-0 text-decoration-none fw-bold" style="color:var(--brand)">View Details</a>
                  
                  <!-- ENROLL BUTTON WITH COURSE NAME IN URL -->
                  <a href="admission-form.php?course=${p.slug}" class="btn btn-enroll">Enroll</a>
              </div>
            </div>
          </article>
        `;
        programsContainer.appendChild(col);
      });
    }

    // initial render
    renderPrograms(programs);

    // filters
    function applyFilters(){
      const text = (searchBox.value || '').toLowerCase().trim();
      const activeTab = document.querySelector('.category-tabs .nav-link.active');
      const category = activeTab ? activeTab.dataset.cat : 'all';

      const filtered = programs.filter(p=>{
        const matchText = (p.title + ' ' + p.desc).toLowerCase().includes(text);
        const matchCat = (category === 'all' || p.category === category);
        return matchText && matchCat;
      });
      renderPrograms(filtered);
    }

    function filterCategory(ev, cat){
      ev.preventDefault();
      document.querySelectorAll('.category-tabs .nav-link').forEach(n=>n.classList.remove('active'));
      ev.currentTarget.classList.add('active');
      applyFilters();
    }
    
    // Placeholder function for brochure download
    function downloadBrochure(title) {
        alert("Downloading brochure for: " + title + "\n(This feature will be connected to actual files soon)");
    }
  </script>
  
</body>
</html>