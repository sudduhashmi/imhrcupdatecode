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
    .hero p{ color: rgba(255,255,255,0.88); margin-top:12px; }

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

</head>

<body>


   <?php include 'includes/header.php'; ?>
   	<!-- Start Page Title Area -->
<div class="page-title-wave">
  <div class="container">
    <h2>Certificate Programs</h2>
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
          <span class="badge-accent">Certificate Programs</span>
          <h1>Certificate Programs at IMHRC — Psychology & Mental Health Education</h1>
          <p class="text-black">Explore IMHRC’s certificate, diploma, undergraduate and postgraduate programs. Industry-aligned curriculum, expert faculty, clinical exposure and recognized certifications to launch your career in mental health.</p>
          <div class="mt-3">
            <a href="admission-form.php" class="btn btn-light btn-lg me-2">Enroll Now</a>
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
            <h4 class="fw-bold">Why IMHRC Certificate Programs</h4>
            <p>Practical, accredited programs crafted by clinical experts. Gain hands-on experience, case-based learning and a recognized certificate to strengthen your professional profile.</p>

            <ul class="list-unstyled mt-3">
              <li><i class="bi bi-check2-circle me-2"></i> Clinical & Counselling focus</li>
              <li><i class="bi bi-check2-circle me-2"></i> Online & Offline delivery</li>
              <li><i class="bi bi-check2-circle me-2"></i> Internship & placement support</li>
              <li><i class="bi bi-check2-circle me-2"></i> Short-term & long-term tracks</li>
            </ul>

            <div class="mt-4">
              <a href="admission-form.php" class="btn btn-enroll w-100" >Apply / Enroll</a>
            </div>

            <div class="mt-4 outline-box">
              <h6 class="mb-2">Program Types</h6>
              <div class="small text-muted">
                Certificate & Online, Diploma, Undergraduate, Postgraduate Diploma and Doctoral research (PhD) pathways — suitable for U.G., P.G. students & professionals.
              </div>
            </div>
          </div>
        </aside>

        <!-- RIGHT CONTENT -->
        <section class="col-lg-8">

          <!-- Tabs / Filters -->
          <div class="d-flex align-items-center justify-content-between mb-3">
            <ul class="nav category-tabs" role="tablist">
              <li class="nav-item"><a class="nav-link active" data-cat="all" href="#" onclick="filterCategory(event,'all')">All Programs</a></li>
              <li class="nav-item"><a class="nav-link" data-cat="certificate" href="#" onclick="filterCategory(event,'certificate')">Certificate & Online</a></li>
              <li class="nav-item"><a class="nav-link" data-cat="diploma" href="#" onclick="filterCategory(event,'diploma')">Diploma</a></li>
              <li class="nav-item"><a class="nav-link" data-cat="ug" href="#" onclick="filterCategory(event,'ug')">Undergraduate</a></li>
              <li class="nav-item"><a class="nav-link" data-cat="pgd" href="#" onclick="filterCategory(event,'pgd')">Postgraduate Diploma</a></li>
            </ul>

            <div class="d-flex gap-2">
              <select id="modeFilter" class="form-select form-select-sm" onchange="applyFilters()">
                <option value="all">Mode: All</option>
                <option value="online">Online</option>
                <option value="offline">Offline</option>
                <option value="hybrid">Hybrid</option>
              </select>

              <input id="searchBox" oninput="applyFilters()" class="form-control form-control-sm" style="max-width:220px" placeholder="Search programs">
            </div>
          </div>

          <!-- Programs Grid -->
          <div id="programs" class="row g-3">
            <!-- Cards rendered by JS -->
          </div>

          <!-- Enroll CTA -->
          <div class="mt-4">
            <div class="outline-box d-flex align-items-center justify-content-between">
              <div>
                <strong>Ready to enroll?</strong>
                <div class="small text-muted">Choose a program and secure your seat with IMHRC’s structured academic pathways.</div>
              </div>
              <div>
                <a href="admission-form.php" class="btn btn-enroll" >Enroll Now</a>
              </div>
            </div>
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
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">Who can apply for IMHRC Certificate Programs?</button>
              </h2>
              <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
                <div class="accordion-body">Students, graduates, and working professionals from psychology, social sciences and allied healthcare backgrounds can apply. Eligibility specifics are listed on each program card.</div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header" id="faqTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">Are programs available online?</button>
              </h2>
              <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
                <div class="accordion-body">Yes — IMHRC offers online, offline and hybrid delivery depending on the program. You can filter programs by delivery mode above.</div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header" id="faqThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">What certification is provided?</button>
              </h2>
              <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
                <div class="accordion-body">On successful completion, participants receive an IMHRC certificate indicating duration, hours completed, and competencies attained. Some programs have university/IGNOU tie-ups — check the program details.</div>
              </div>
            </div>

          </div>
        </div>
      </div>

    </div>
  </main>





  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Sample program data (add/modify as required)
    const programs = [
      { id:1, title:"Certificate in Counselling Skills", category:"certificate", mode:"online", level:"Certificate", duration:"6 weeks", fee:"INR 3,000", desc:"Short-term skill-based course focused on counselling techniques." },
      { id:2, title:"Diploma in Clinical Psychology", category:"diploma", mode:"offline", level:"Diploma", duration:"12 months", fee:"INR 25,000", desc:"Comprehensive diploma with supervised clinical training." },
      { id:3, title:"B.Sc. Psychology (Undergraduate)", category:"ug", mode:"offline", level:"Undergraduate", duration:"3 years", fee:"Varies", desc:"Undergraduate program covering core psychology subjects." },
      { id:4, title:"Postgraduate Diploma in Mental Health", category:"pgd", mode:"hybrid", level:"PG Diploma", duration:"1 year", fee:"INR 30,000", desc:"Advanced diploma focusing on applied clinical skills." },
      { id:5, title:"Certificate: Psychotherapy Essentials", category:"certificate", mode:"online", level:"Certificate", duration:"8 weeks", fee:"INR 4,000", desc:"Foundational psychotherapy intervention techniques." },
      { id:6, title:"IGNOU MAPC Internship Program", category:"diploma", mode:"offline", level:"Diploma", duration:"240 hours", fee:"INR 8,000", desc:"Internship aligned with IGNOU MAPC requirements." }
    ];

    const programsContainer = document.getElementById('programs');
    const searchBox = document.getElementById('searchBox');
    const modeFilter = document.getElementById('modeFilter');

    // render program options for enroll form
    function populateFormPrograms(){
      const sel = document.getElementById('formProgram');
      sel.innerHTML = programs.map(p=>`<option value="${p.id}">${p.title} — ${p.level} (${p.mode})</option>`).join('');
    }

    function renderPrograms(list){
      programsContainer.innerHTML = '';
      if(!list.length){
        programsContainer.innerHTML = '<div class="col-12"><div class="outline-box text-center">No programs found — try different filters.</div></div>';
        return;
      }
      list.forEach(p=>{
        const col = document.createElement('div');
        col.className = 'col-md-6 col-lg-6';
        col.innerHTML = `
          <article class="prog-card h-100">
            <div>
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <span class="prog-badge">${p.level}</span>
                  <h5 class="mt-3">${p.title}</h5>
                </div>
                <div class="text-end">
                  <div class="small text-muted">${p.duration}</div>
                  <div class="small text-muted mt-1">${p.mode.charAt(0).toUpperCase()+p.mode.slice(1)}</div>
                </div>
              </div>

              <p class="prog-meta">${p.desc}</p>

              <div class="mt-3">
                <strong>Fees: </strong> <span class="text-muted">${p.fee}</span>
              </div>
            </div>

            <div class="prog-footer">
              <button class="btn btn-link p-0" onclick="openDetails(${p.id})">Program Overview</button>
              <div class="ms-auto">
                <button class="btn btn-sm btn-outline-secondary me-2" onclick="downloadBrochure(${p.id})"><i class="bi bi-download"></i> Brochure</button>
                <a href="admission-form.php" class="btn btn-enroll">Enroll</a>
              </div>
            </div>
          </article>
        `;
        programsContainer.appendChild(col);
      });
    }

    // initial render
    renderPrograms(programs);
    populateFormPrograms();

    // filters
    function applyFilters(){
      const text = (searchBox.value || '').toLowerCase().trim();
      const mode = modeFilter.value;
      const filtered = programs.filter(p=>{
        const matchText = (p.title + ' ' + p.desc + ' ' + p.level).toLowerCase().includes(text);
        const matchMode = (mode === 'all' || p.mode === mode);
        return matchText && matchMode;
      });
      renderPrograms(filtered);
    }

    function filterCategory(ev, cat){
      ev.preventDefault();
      // activate tab styles
      document.querySelectorAll('.category-tabs .nav-link').forEach(n=>n.classList.remove('active'));
      ev.currentTarget.classList.add('active');

      if(cat === 'all'){
        renderPrograms(programs);
      } else {
        renderPrograms(programs.filter(p => p.category === cat));
      }
      // reset search & mode filters on category change
      searchBox.value = '';
      modeFilter.value = 'all';
    }

    // quick actions
    function quickEnroll(id){
      const modal = new bootstrap.Modal(document.getElementById('enrollModal'));
      document.getElementById('formProgram').value = id;
      document.getElementById('formMode').value = programs.find(p=>p.id===id)?.mode || '';
      modal.show();
    }

    function openDetails(id){
      const p = programs.find(x=>x.id===id);
      alert(p.title + "\\n\\n" + p.desc + "\\n\\nDuration: " + p.duration + "\\nFees: " + p.fee);
    }

    function downloadBrochure(id){
      alert('Brochure download (demo) for program id ' + id);
    }

    // form submission (demo)
    document.getElementById('enrollForm').addEventListener('submit', function(e){
      e.preventDefault();
      // simple validation demo
      const program = document.getElementById('formProgram').value;
      const name = this.querySelector('input[type="text"]').value;
      const email = this.querySelector('input[type="email"]').value;
      if(!program || !name || !email){
        alert('Please complete required fields.');
        return;
      }
      // success demo
      bootstrap.Modal.getInstance(document.getElementById('enrollModal')).hide();
      alert('Application submitted. Our academic team will contact you soon.');
      this.reset();
    });

    // accessibility: keyboard support for category links
    document.querySelectorAll('.category-tabs .nav-link').forEach(a=>{
      a.addEventListener('keydown', (e)=>{
        if(e.key === 'Enter' || e.key === ' '){ e.preventDefault(); a.click(); }
      });
    });

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