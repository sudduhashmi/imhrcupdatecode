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
    :root{
      --brand:#0b2c4d;
      --accent:#f6b400;
      --soft:#f4f7fb;
      --muted:#6b7280;
      --card-radius:14px;
    }

 

    /* HERO */
    .hero{
      /* background:linear-gradient(120deg,var(--brand),#123f76); */
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
    .hero h1{ font-size:34px; line-height:1.08; margin-top:8px; font-weight:700; }
.hero p {
    color: rgb(0 0 0 / 88%);
    margin-top: 12px;
}

    /* SECTION TITLE */
    .section-title{
      font-size:28px; font-weight:700; position:relative; padding-left:18px; margin-bottom:20px;
    }
    .section-title::before{
      content:""; position:absolute; left:0; top:6px; width:6px; height:34px; background:var(--accent); border-radius:4px;
    }

    /* Tab & filters */
    .category-tabs .nav-link{ color:var(--brand); font-weight:600; border-radius:12px; }
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
    .prog-meta{ color:var(--muted); font-size:14px; margin-top:8px; }
    .prog-footer{ display:flex; gap:12px; align-items:center; margin-top:14px; }

    .btn-enroll{ background:var(--accent); color:#000; font-weight:700; border-radius:50px; padding:8px 18px; border:none; }
    .btn-enroll:hover{ background:#e0a800; }

    /* info column */
    .info-panel{
      background: linear-gradient(135deg,#0b2545,#123b6f);
      color:#fff; border-radius:14px; padding:26px;
    }
.info-panel li {
    margin-bottom: 8px;
    color: #f6b400;
}

    /* outcomes/outlines */
    .outline-box{ background:#fff; border-radius:14px; padding:20px; border:1px solid rgba(11,44,77,0.05); }

    /* FAQ */
    .accordion-button{ font-weight:700; color:var(--brand); }
    .accordion-item{ border-radius:12px; overflow:hidden; margin-bottom:10px; border:none; }

    /* responsive tweaks */
    @media (max-width:991px){
      .hero h1{ font-size:28px; }
    }
    @media (max-width:767px){
      .hero{ padding:36px 0; border-radius:0 0 18px 18px; }
      .info-panel{ display:none; }
    }
    button.btn.btn-light.btn-lg {
    background: #0e2f58;
    color: #fff;
}
.btn-outline-light {
    color: #f6b400;
    border-color: #f6b400;
}
.btn-outline-light:hover {
    color: #ffffff;
    background-color: #0e2f58;
    border-color: #0e2f58;
}
.info-panel h4 {
    color: #f6b400;
}
.accordion-button {
    font-weight: 700;
    color: var(--brand);
    background: #e9e9e9;
}
.form-control {
    /* height: 50px; */
    color: #324cc5;
    border: 1px solid #d0c7c7;
    background-color: transparent;
    border-radius: 0;
    font-size: 16px;
    /* padding: 10px 20px; */
    width: 100%;
}
  </style>

</style>

</head>

<body>


   <?php include 'includes/header.php'; ?>
   	<!-- Start Page Title Area -->
<div class="page-title-wave">
  <div class="container">
    <h2>Offerings Postgraduate Programs</h2>
  </div>

  <svg class="wave" viewBox="0 0 1440 320" preserveAspectRatio="none">
    <path fill="#ffffff" fill-opacity="1" 
      d="M0,64L48,90.7C96,117,192,171,288,170.7C384,171,480,117,576,85.3C672,53,768,43,864,74.7C960,107,1056,181,1152,192C1248,203,1344,149,1392,122.7L1440,96L1440,320L1392,320C1344,320,1248,320,
      1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
    </path>
  </svg>
</div>
<!-- HERO -->

<!-- HERO -->
<header class="hero">
  <div class="container">
    <div class="row align-items-center">
      
      <div class="col-lg-8">
        <span class="badge-accent">Other Academic Offerings / Postgraduate Programs </span>
        <h1 class="mt-3">
          Postgraduate Programs in Psychology & Mental Health
        </h1>
        <p>
          IMHRC Postgraduate Programs are designed for graduates and
          working professionals seeking advanced specialization in clinical,
          counselling and applied mental health. Programs focus on intensive
          training, supervised clinical practice and industry-recognized
          postgraduate certification.
        </p>

        <a href="admission-form.php" class="btn btn-light btn-lg me-2">
          Enroll Now
       </a>
      </div>

      <div class="col-lg-4 text-center">
        <img src="assets/img/images.jpeg"
             alt="Postgraduate Diploma Psychology"
             class="img-fluid rounded-3 shadow"
             style="max-height:220px;object-fit:cover;">
      </div>

    </div>
  </div>
</header>

<!-- MAIN -->
<main class="py-5">
<div class="container">
<div class="row gy-4">

<!-- LEFT -->
<aside class="col-lg-4">
<div class="info-panel">
<h4 class="fw-bold">Why Choose IMHRC Postgraduate Diploma Programs</h4>
<p>
IMHRC Postgraduate Diploma Programs are designed for graduates and professionals
seeking advanced specialization, clinical exposure and industry-relevant
expertise in psychology and mental health sciences.
</p>

<ul class="list-unstyled">
<li><i class="bi bi-check2-circle me-2"></i> Advanced clinical & applied specialization</li>
<li><i class="bi bi-check2-circle me-2"></i> Designed for graduates & professionals</li>
<li><i class="bi bi-check2-circle me-2"></i> Supervised internships & case work</li>
<li><i class="bi bi-check2-circle me-2"></i> Career advancement & skill enhancement</li>
</ul>

<a href="admission-form.php" class="btn btn-enroll w-100 mt-3">
Apply for PG Diploma
</a>

<div class="outline-box mt-4">
<strong class="text-black"> Postgraduate Programs Offered</strong>
<p class="small text-muted mt-1">
PG Diploma in Clinical Psychology, Counselling Psychology,
Mental Health & Applied Psychology Specializations.
</p>
</div>
</div>
</aside>

<!-- RIGHT -->
<section class="col-lg-8">

<!-- FILTER BAR -->
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
<ul class="nav category-tabs">
<li class="nav-item">
<a class="nav-link active" href="#" onclick="filterCategory(event,'all')">All PG Diplomas</a>
</li>
<li class="nav-item">
<a class="nav-link" href="#" onclick="filterCategory(event,'clinical')">Clinical PG Diploma</a>
</li>
<li class="nav-item">
<a class="nav-link" href="#" onclick="filterCategory(event,'counselling')">Counselling PG Diploma</a>
</li>
</ul>

<div class="d-flex gap-2">
<select id="modeFilter" class="form-select form-select-sm" style="max-width:160px" onchange="applyFilters()">
<option value="all">Mode: All</option>
<option value="online">Online</option>
<option value="offline">Offline</option>
<option value="hybrid">Hybrid</option>
</select>

<input id="searchBox" oninput="applyFilters()" class="form-control form-control-sm"
style="max-width:160px" placeholder="Search programs">
</div>
</div>

<div id="programs" class="row g-3"></div>

</section>
</div>

<!-- FAQ -->
<div class="row mt-5">
<div class="col-12">
<h3 class="section-title">Postgraduate Diploma Programs – FAQs</h3>

<div class="accordion" id="faqAcc">

<div class="accordion-item">
<button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#pgf1">
Who can apply for PG Diploma programs?
</button>
<div id="pgf1" class="accordion-collapse collapse">
<div class="accordion-body">
Graduates in psychology, social work, education, nursing,
medicine and mental health-related disciplines can apply.
</div>
</div>
</div>

<div class="accordion-item">
<button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#pgf2">
Are PG Diploma programs clinically oriented?
</button>
<div id="pgf2" class="accordion-collapse collapse">
<div class="accordion-body">
Yes. All PG diploma programs include advanced case studies,
supervised clinical exposure and applied interventions.
</div>
</div>
</div>

<div class="accordion-item">
<button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#pgf3">
Is certification provided after completion?
</button>
<div id="pgf3" class="accordion-collapse collapse">
<div class="accordion-body">
Yes. Candidates receive an IMHRC Postgraduate Diploma certificate
with detailed training hours and specialization mentioned.
</div>
</div>
</div>

</div>
</div>
</div>

</div>
</main>



<script>
const programs=[
{
id:1,
title:"PG Diploma in Clinical Psychology",
category:"clinical",
mode:"offline",
duration:"12 Months",
fee:"₹45,000",
desc:"Advanced clinical psychology training focusing on assessment, diagnosis, psychotherapy and supervised clinical practice."
},
{
id:2,
title:"PG Diploma in Counselling Psychology",
category:"counselling",
mode:"hybrid",
duration:"9 Months",
fee:"₹40,000",
desc:"Advanced counselling approaches covering therapeutic techniques, ethics, supervision and applied interventions."
},
{
id:3,
title:"PG Diploma in Mental Health & Wellness",
category:"clinical",
mode:"online",
duration:"6 Months",
fee:"₹30,000",
desc:"Specialized mental health program focusing on wellness promotion, interventions and community mental health."
},
{
id:4,
title:"PG Diploma in Applied Psychology",
category:"counselling",
mode:"offline",
duration:"12 Months",
fee:"₹35,000",
desc:"Application-oriented psychology program integrating assessment tools, behavioural science and professional practice."
}
];

const box=document.getElementById('programs');
const search=document.getElementById('searchBox');

function renderPrograms(list){
box.innerHTML='';
list.forEach(p=>{
box.innerHTML+=`
<div class="col-md-6">
<div class="prog-card">
<span class="prog-badge">${p.duration} | ${p.mode}</span>
<h5 class="mt-3">${p.title}</h5>
<p class="small text-muted">${p.desc}</p>
<strong>Fees: ${p.fee}</strong>
<div class="prog-footer mt-3 d-flex">
<button class="btn btn-sm btn-outline-secondary" onclick="openDetails(${p.id})">Overview</button>
<a href="admission-form.php" class="btn btn-enroll ms-auto" >Apply</a>
</div>
</div>
</div>`;
});
}

function filterCategory(e,cat){
e.preventDefault();
document.querySelectorAll('.category-tabs .nav-link').forEach(n=>n.classList.remove('active'));
e.currentTarget.classList.add('active');
applyFilters(cat);
}

function applyFilters(catOverride){
let list=[...programs];
const mode=document.getElementById('modeFilter').value;
const term=search.value.toLowerCase();
if(catOverride && catOverride!=='all') list=list.filter(p=>p.category===catOverride);
if(mode!=='all') list=list.filter(p=>p.mode===mode);
if(term) list=list.filter(p=>p.title.toLowerCase().includes(term));
renderPrograms(list);
}

function populateForm(){
document.getElementById('formProgram').innerHTML=
'<option value="">Select Program</option>'+
programs.map(p=>`<option>${p.title}</option>`).join('');
}

function quickEnroll(id){
document.getElementById('formProgram').value=programs.find(p=>p.id===id).title;
new bootstrap.Modal(document.getElementById('enrollModal')).show();
}

function openDetails(id){
const p=programs.find(x=>x.id===id);
alert(p.title+"\n\n"+p.desc+"\n\nFees: "+p.fee);
}

renderPrograms(programs);
populateForm();
</script>



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