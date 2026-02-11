<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>International Conference</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
:root{
 --primary:#6a11cb;
 --secondary:#2575fc;
 --accent:#ff9800;
}

/* HERO */
.hero{
  background:linear-gradient(135deg,rgba(106,17,203,.9),rgba(37,117,252,.9)),
  url('https://images.unsplash.com/photo-1521737604893-d14cc237f11d') center/cover;
}
.hero h1{font-size:2.6rem}

/* SECTION */
.section-title{
 font-weight:800;
 text-transform:uppercase;
 letter-spacing:1px;
 position:relative;
}
.section-title::after{
 content:'';
 width:60px;height:4px;
 background:var(--accent);
 display:block;margin-top:8px;
}

/* CARDS */
.card{
 border-radius:18px;
 border:none;
 transition:.4s;
}
.card:hover{
 transform:translateY(-8px);
 box-shadow:0 20px 40px rgba(0,0,0,.15);
}

/* ICON BOX */
.icon-box{
 background:linear-gradient(135deg,#fff,#f7f7f7);
 border-radius:16px;
 padding:25px;
 text-align:center;
 font-weight:600;
 box-shadow:0 10px 30px rgba(0,0,0,.1);
 transition:.3s;
}
.icon-box:hover{background:linear-gradient(135deg,#ffe259,#ffa751);color:#000}

/* TRACK BADGES */
.badge-track{
 background:linear-gradient(135deg,#00c6ff,#0072ff);
 padding:10px 16px;
 font-size:14px;
 border-radius:30px;
 margin:6px;
}

/* FEES */
.table thead{background:linear-gradient(135deg,#6a11cb,#2575fc);color:#fff}
.table tbody tr:hover{background:#fff4e0}

/* COMMITTEE */
.committee-card img{
 height:240px;
 object-fit:cover;
 border-radius:18px 18px 0 0;
}
.committee-card .card-body{
 background:linear-gradient(135deg,#fdfbfb,#ebedee);
}

/* CTA */
.cta{
 background:linear-gradient(135deg,#ff512f,#dd2476);
}

/* MODAL */
.modal-content{
 border-radius:18px;
}
</style>
</head>

<body>

<!-- HERO -->
<section class="hero text-white py-5">
<div class="container text-center">
<span class="badge bg-warning text-dark px-3 py-2">International Conference</span>
<h1 class="fw-bold mt-4">International Conference on Science, Technology & Innovation</h1>
<p class="lead">15 – 17 March 2025 · New Delhi · Hybrid Mode</p>
<a class="btn btn-light btn-lg me-2" data-bs-toggle="modal" data-bs-target="#registerModal">Register Now</a>
<a class="btn btn-outline-light btn-lg">Download Brochure</a>
</div>
</section>

<!-- UPCOMING / PAST -->
<section class="py-5 bg-light">
<div class="container">

<ul class="nav nav-pills justify-content-center mb-5">
<li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#upcoming">Upcoming</button></li>
<li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#past">Past</button></li>
</ul>

<div class="tab-content">

<div class="tab-pane fade show active" id="upcoming">
<div class="row g-4 justify-content-center">
<div class="col-md-4">
<div class="card">
<img src="https://images.unsplash.com/photo-1551836022-d5d88e9218df" class="card-img-top">
<div class="card-body">
<span class="badge bg-danger">Upcoming</span>
<h5 class="mt-2">ICSTI 2025</h5>
<p>📅 15–17 March 2025<br>📍 New Delhi</p>
<button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detailsModal">View Details</button>
<button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#registerModal">Register</button>
</div>
</div>
</div>
</div>
</div>

<div class="tab-pane fade" id="past">
<div class="row g-4 justify-content-center">
<div class="col-md-4">
<div class="card">
<div class="card-body">
<span class="badge bg-success">Completed</span>
<h5 class="mt-2">ICSTI 2023</h5>
<p>📍 Lucknow · 👥 300+ Delegates</p>
<button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#galleryModal">Gallery</button>
<button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#proceedingsModal">Proceedings</button>
</div>
</div>
</div>
</div>
</div>

</div>
</div>
</section>

<!-- IMPORTANT DATES -->
<section class="py-5">
<div class="container">
<h2 class="section-title mb-4">Important Dates</h2>
<div class="row g-4">
<div class="col-md-3"><div class="icon-box">📄 Abstract<br><strong>30 July 2025</strong></div></div>
<div class="col-md-3"><div class="icon-box">✅ Acceptance<br><strong>05 Aug 2025</strong></div></div>
<div class="col-md-3"><div class="icon-box">💰 Earlybird<br><strong>15 Aug 2025</strong></div></div>
<div class="col-md-3"><div class="icon-box">🎤 Conference<br><strong>15–17 Mar 2025</strong></div></div>
</div>
</div>
</section>

<!-- FEATURES -->
<section class="py-5 bg-light">
<div class="container">
<h2 class="section-title mb-4">Conference Features</h2>
<div class="row g-4">
<div class="col-md-3"><div class="icon-box">🌍 Global Speakers</div></div>
<div class="col-md-3"><div class="icon-box">📢 Oral & Poster</div></div>
<div class="col-md-3"><div class="icon-box">📚 Indexed Papers</div></div>
<div class="col-md-3"><div class="icon-box">🏆 Awards</div></div>
</div>
</div>
</section>

<!-- TRACKS -->
<section class="py-5">
<div class="container">
<h2 class="section-title mb-4">Conference Tracks</h2>
<span class="badge badge-track">AI & ML</span>
<span class="badge badge-track">Renewable Energy</span>
<span class="badge badge-track">Healthcare</span>
<span class="badge badge-track">Management</span>
<span class="badge badge-track">Education</span>
</div>
</section>

<!-- FEES -->
<section class="py-5 bg-light">
<div class="container">
<h2 class="section-title mb-4">Registration Fees</h2>
<table class="table text-center">
<thead><tr><th>Category</th><th>Earlybird</th><th>Regular</th></tr></thead>
<tbody>
<tr><td>Professionals</td><td>₹2,500</td><td>₹3,200</td></tr>
<tr><td>Scholars</td><td>₹2,000</td><td>₹2,500</td></tr>
<tr><td>Students</td><td>₹1,500</td><td>₹2,000</td></tr>
<tr><td>International</td><td>$50</td><td>$80</td></tr>
</tbody>
</table>
</div>
</section>

<!-- COMMITTEE -->
<section class="py-5">
<div class="container">
<h2 class="section-title mb-4">Organizing Committee</h2>
<div class="row g-4">
<div class="col-md-3">
<div class="card committee-card">
<img src="https://images.unsplash.com/photo-1603415526960-f7e0328c63b1">
<div class="card-body text-center">
<h6>Prof. A. K. Sharma</h6>
<p class="text-muted">Conference Chair</p>
</div>
</div>
</div>
</div>
</div>
</section>

<!-- CTA -->
<section class="cta text-white py-5 text-center">
<div class="container">
<h2 class="fw-bold">Submit Your Research Paper</h2>
<p>Be part of a global academic revolution</p>
<a class="btn btn-light btn-lg" data-bs-toggle="modal" data-bs-target="#registerModal">Submit Now</a>
</div>
</section>

<!-- MODALS (same as before, working) -->
<div class="modal fade" id="registerModal">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<div class="modal-header"><h5>Registration</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body">
<input class="form-control mb-2" placeholder="Name">
<input class="form-control mb-2" placeholder="Email">
<button class="btn btn-primary w-100">Submit</button>
</div>
</div>
</div>
</div>

<div class="modal fade" id="detailsModal">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content">
<div class="modal-header"><h5>Conference Details</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body">
<p>International conference with indexed proceedings & awards.</p>
</div>
</div>
</div>
</div>

<div class="modal fade" id="galleryModal">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content">
<div class="modal-header"><h5>Gallery</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body">
<img src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655" class="img-fluid rounded">
</div>
</div>
</div>
</div>

<div class="modal fade" id="proceedingsModal">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<div class="modal-header"><h5>Proceedings</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body">
<ul><li>AI Research</li><li>Energy Systems</li></ul>
</div>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
