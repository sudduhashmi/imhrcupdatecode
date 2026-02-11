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
/* Section Base */
.proceedings-archive {
  padding: 90px 20px;
}



/* Header */
.archive-header {
  text-align: center;
  margin-bottom: 50px;
}
.archive-header h2 {
  font-size: 2.6rem;
  margin-bottom: 10px;
}
.archive-header p {
  color: #555;
  font-size: 1.05rem;
}

/* Tabs */
.archive-tabs {
  display: flex;
  justify-content: center;
  gap: 15px;
  flex-wrap: wrap;
  margin-bottom: 40px;
}
.archive-tab {
  padding: 10px 26px;
  border: none;
  border-radius: 30px;
  background: #e8ebff;
  color: #333;
  font-weight: 600;
  cursor: pointer;
  transition: .3s;
}
.archive-tab.active,
.archive-tab:hover {
  background: #4e5bff;
  color: #fff;
}

/* Grid */
.archive-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 30px;
}

/* Card */
.archive-card {
  background: #fff;
  padding: 30px;
  border-radius: 22px;
  box-shadow: 0 15px 35px rgba(0,0,0,.08);
  position: relative;
  overflow: hidden;
  transition: .4s;
}
.archive-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 25px 55px rgba(0,0,0,.15);
}

/* Badge */
.badge {
  position: absolute;
  top: 20px;
  right: 20px;
  padding: 6px 14px;
  border-radius: 50px;
  font-size: .75rem;
  font-weight: 600;
  color: #fff;
}
.news-badge { background: #4e5bff; }
.event-badge { background: #00b894; }
.announce-badge { background: #e17055; }

.date {
  display: block;
  font-size: .85rem;
  color: #999;
  margin-bottom: 10px;
}

/* Card Content */
.archive-card h4 {
  font-size: 1.2rem;
  margin-bottom: 12px;
}
.archive-card p {
  font-size: .95rem;
  color: #666;
  line-height: 1.6;
}

/* Button */
.archive-btn {
  margin-top: 18px;
  display: inline-block;
  color: #4e5bff;
  font-weight: 600;
  text-decoration: none;
  transition: .3s;
}
.archive-btn:hover {
  letter-spacing: .5px;
}

/* Responsive */
@media(max-width:768px){
  .archive-header h2 { font-size: 2.1rem; }
}
.archive-img {
  width: 100%;
  height: 190px;
  object-fit: cover;
  border-radius: 18px;
  margin-bottom: 15px;
}

</style>

</head>

<body>


   <?php include 'includes/header.php'; ?>
   	<!-- Start Page Title Area -->
<div class="page-title-wave">
  <div class="container">
    <h2>Past Event Reports & Media Gallery</h2>
  </div>

  <svg class="wave" viewBox="0 0 1440 320" preserveAspectRatio="none">
    <path fill="#ffffff" fill-opacity="1" 
      d="M0,64L48,90.7C96,117,192,171,288,170.7C384,171,480,117,576,85.3C672,53,768,43,864,74.7C960,107,1056,181,1152,192C1248,203,1344,149,1392,122.7L1440,96L1440,320L1392,320C1344,320,1248,320,
      1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
    </path>
  </svg>
</div>

<section class="proceedings-archive">
  <div class="container">


    <!-- Filter Tabs -->
    <div class="archive-tabs">
      <button class="archive-tab active" data-filter="all">All</button>
      <button class="archive-tab" data-filter="news">Latest News</button>
      <button class="archive-tab" data-filter="events">Events</button>
      <button class="archive-tab" data-filter="announcements">Announcements</button>
    </div>

    <!-- Grid -->
    <div class="archive-grid">

      <!-- ===== NEWS (3) ===== -->
      <div class="archive-card news">
        <img src="assets/img/past-news1.jpg" class="archive-img">
        <span class="badge news-badge">News</span>
        <span class="date">12 Apr 2025</span>
        <h4>Comprehensive Report Released for Mental Health Conference 2025</h4>
        <p>Detailed post-event report highlighting key findings, outcomes, and expert insights has been published.</p>
        <a href="#" class="archive-btn">Read Report →</a>
      </div>

      <div class="archive-card news">
        <img src="assets/img/past-news2.jpg" class="archive-img">
        <span class="badge news-badge">News</span>
        <span class="date">30 Mar 2025</span>
        <h4>Past Conference Proceedings Now Available Online</h4>
        <p>Proceedings, presentations, and session recordings from past events are now accessible.</p>
        <a href="#" class="archive-btn">View Archive →</a>
      </div>

      <div class="archive-card news">
        <img src="assets/img/past-news3.jpg" class="archive-img">
        <span class="badge news-badge">News</span>
        <span class="date">18 Feb 2025</span>
        <h4>Event Media Gallery Updated with New Content</h4>
        <p>Photo and video galleries from past academic events have been updated.</p>
        <a href="#" class="archive-btn">View Gallery →</a>
      </div>

      <!-- ===== EVENTS (3) ===== -->
      <div class="archive-card events">
        <img src="assets/img/past-event1.jpg" class="archive-img">
        <span class="badge event-badge">Event</span>
        <span class="date">10 Jan 2025</span>
        <h4>International Mental Health Research Conference 2025</h4>
        <p>Highlights and recorded sessions from the international conference held earlier this year.</p>
        <a href="#" class="archive-btn">View Highlights →</a>
      </div>

      <div class="archive-card events">
        <img src="assets/img/past-event2.jpg" class="archive-img">
        <span class="badge event-badge">Event</span>
        <span class="date">28 Nov 2024</span>
        <h4>Annual Mental Health Research Symposium</h4>
        <p>Summarized outcomes, keynote highlights, and panel discussions from the symposium.</p>
        <a href="#" class="archive-btn">View Report →</a>
      </div>

      <div class="archive-card events">
        <img src="assets/img/past-event3.jpg" class="archive-img">
        <span class="badge event-badge">Event</span>
        <span class="date">15 Sep 2024</span>
        <h4>National Workshop on Research Ethics</h4>
        <p>Workshop report covering ethical practices, training sessions, and expert recommendations.</p>
        <a href="#" class="archive-btn">Read Summary →</a>
      </div>

      <!-- ===== ANNOUNCEMENTS (3) ===== -->
      <div class="archive-card announcements">
        <img src="assets/img/past-announce1.jpg" class="archive-img">
        <span class="badge announce-badge">Announcement</span>
        <span class="date">05 May 2025</span>
        <h4>Event Reports & Media Access Guidelines Released</h4>
        <p>Guidelines for accessing official event reports, images, and recorded sessions are now available.</p>
        <a href="#" class="archive-btn">Read Guidelines →</a>
      </div>

      <div class="archive-card announcements">
        <img src="assets/img/past-announce2.jpg" class="archive-img">
        <span class="badge announce-badge">Announcement</span>
        <span class="date">22 Apr 2025</span>
        <h4>Certificates for Past Events Now Downloadable</h4>
        <p>Participants can download certificates for previously attended academic events.</p>
        <a href="#" class="archive-btn">Download →</a>
      </div>

      <div class="archive-card announcements">
        <img src="assets/img/past-announce3.jpg" class="archive-img">
        <span class="badge announce-badge">Announcement</span>
        <span class="date">10 Mar 2025</span>
        <h4>Archived Event Videos Uploaded</h4>
        <p>Recorded sessions and keynote speeches from past events are now available in the media gallery.</p>
        <a href="#" class="archive-btn">Watch Videos →</a>
      </div>

    </div>
  </div>
</section>


<script>
const tabs = document.querySelectorAll('.archive-tab');
const cards = document.querySelectorAll('.archive-card');

tabs.forEach(tab => {
  tab.addEventListener('click', () => {
    tabs.forEach(t => t.classList.remove('active'));
    tab.classList.add('active');

    const filter = tab.dataset.filter;

    cards.forEach(card => {
      if (filter === 'all' || card.classList.contains(filter)) {
        card.style.display = 'block';
        card.style.animation = 'fadeIn .5s';
      } else {
        card.style.display = 'none';
      }
    });
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