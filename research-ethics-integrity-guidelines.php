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
.ethics-committee {
  padding: 80px 20px;
  color: #333;
}
button.btn {
    background: #ffb904;
    font-size: 18px;
}
.section-header h2 { font-size: 2.5rem; margin-bottom: 10px; }
.section-header p { font-size: 1.1rem; color: #555;  }

/* Overview */
.committee-overview { max-width: 900px; margin: 0 auto 50px; font-size: 1rem; line-height: 1.7; color: #555; text-align: center; }

/* Committee Members */
.committee-members h3 { font-size: 1.8rem; margin-bottom: 25px; text-align: center; }
.members-grid { display: flex; flex-wrap: wrap; gap: 25px; justify-content: center; }
.member-card {
    background: #fff;
    border-radius: 15px;
    padding: 20px;
    max-width: 23%;
    width: 100%;
    text-align: center;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s, box-shadow 0.3s;
}
.member-card:hover { transform: translateY(-8px); box-shadow: 0 12px 25px rgba(0,0,0,0.15); }
.member-card img { width: 100%;  margin-bottom: 15px; }
.member-card h4 { font-size: 1.2rem; margin-bottom: 5px; }
.member-card p { font-size: 0.9rem; color: #777; }

/* Responsibilities */
.committee-responsibilities { max-width: 900px; margin: 50px auto; }
.committee-responsibilities h3 { font-size: 1.8rem; margin-bottom: 20px; text-align: center; }
.committee-responsibilities ul { list-style: disc; padding-left: 20px; line-height: 1.6; color: #555; }

/* CTA Button */
.cta .btn { display: inline-block; padding: 12px 30px; background: #ffb800; color: #fff; border-radius: 50px; font-weight: 600; text-decoration: none; transition: background 0.3s, transform 0.3s; margin-top: 40px; }
.cta .btn:hover { background: #3b46d6; transform: translateY(-3px); }
.btn:hover {
    color: #ffffff;
}
/* Responsive */
@media(max-width: 992px){ .members-grid { gap: 20px; } }
@media(max-width: 768px){ .members-grid { flex-direction: column; align-items: center; } }
</style>
<style>
/* Responsibilities */
.committee-responsibilities.premium { max-width: 1100px; margin: 50px auto; }
.committee-responsibilities h3 { text-align: center; font-size: 2rem; margin-bottom: 30px; }
.responsibilities-grid { display: flex; flex-wrap: wrap; gap: 25px; justify-content: center; }
.resp-card { background: #fff; border-radius: 15px; padding: 20px; max-width: 220px; text-align: center; box-shadow: 0 6px 18px rgba(0,0,0,0.08); transition: transform 0.3s, box-shadow 0.3s; }
.resp-card:hover { transform: translateY(-6px); box-shadow: 0 12px 25px rgba(0,0,0,0.15); }
.resp-card img { width: 60px; margin-bottom: 15px; }
.resp-card p { font-size: 0.95rem; color: #555; line-height: 1.5; }


/* Modal Styles */
.modal { display: none; position: fixed; z-index: 999; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background: rgba(0,0,0,0.6); }
.modal-content { background: #fff; margin: 5% auto; padding: 30px; border-radius: 15px; max-width: 500px; position: relative; }
.close-btn { position: absolute; top: 15px; right: 20px; font-size: 1.5rem; cursor: pointer; }
form input, form textarea { width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 8px; border: 1px solid #ccc; font-size: 1rem; }
form textarea { resize: vertical; }
form button { width: 100%; padding: 12px; margin-top: 10px; background: #4e5bff; color: #fff; border: none; border-radius: 50px; cursor: pointer; font-weight: 600; transition: background 0.3s; }
form button:hover { background: #3b46d6; }

/* Responsive */
@media(max-width: 768px){ .responsibilities-grid { flex-direction: column; align-items: center; } }
</style>
</head>

<body>


   <?php include 'includes/header.php'; ?>
   
   	<!-- Start Page Title Area -->
<div class="page-title-wave">
  <div class="container">
    <h2>Research Ethics & Integrity Guidelines</h2>
  </div>

  <svg class="wave" viewBox="0 0 1440 320" preserveAspectRatio="none">
    <path fill="#ffffff" fill-opacity="1" 
      d="M0,64L48,90.7C96,117,192,171,288,170.7C384,171,480,117,576,85.3C672,53,768,43,864,74.7C960,107,1056,181,1152,192C1248,203,1344,149,1392,122.7L1440,96L1440,320L1392,320C1344,320,1248,320,
      1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
    </path>
  </svg>
</div>
<section class="ethics-committee">
  <div class="container">

    <!-- Section Header -->
    <div class="section-header text-center">
      <h2>Ethics Committee</h2>
      <p>Ensuring the highest standards of research integrity and ethical compliance.</p>
    </div>

    <!-- Committee Overview -->
    <div class="committee-overview">
      <p>The Ethics Committee at our institute ensures all research activities comply with the highest standards of integrity and ethical conduct. The committee oversees research proposals, monitors adherence to ethical guidelines, and provides guidance to faculty, staff, and students to promote responsible research practices.</p>
    </div>

    <!-- Committee Members -->
    <div class="committee-members">
      <h3>Committee Members</h3>
      <div class="members-grid">
        <div class="member-card">
          <img src="assets/img/team/team-1.jpg" alt="Member 1">
          <h4>Dr. Anjali Sharma</h4>
          <p>Chairperson</p>
        </div>
        <div class="member-card">
          <img src="assets/img/team/team-2.jpg" alt="Member 2">
          <h4>Prof. Rajiv Verma</h4>
          <p>Member</p>
        </div>
        <div class="member-card">
          <img src="assets/img/team/team-3.jpg" alt="Member 3">
          <h4>Dr. Meera Gupta</h4>
          <p>Member</p>
        </div>
        <div class="member-card">
          <img src="assets/img/team/team-4.jpg" alt="Member 4">
          <h4>Prof. Amit Singh</h4>
          <p>Member</p>
        </div>
      </div>
    </div>

  <!-- Responsibilities -->
<div class="committee-responsibilities premium">
  <h3>Key Responsibilities</h3>
  <div class="responsibilities-grid">
    <div class="resp-card">
      <img src="assets/img/icons/review.png" alt="Review Proposals">
      <p>Review all research proposals for ethical compliance</p>
    </div>
    <div class="resp-card">
      <img src="assets/img/icons/standards.png" alt="Ethical Standards">
      <p>Ensure adherence to national and international ethical standards</p>
    </div>
    <div class="resp-card">
      <img src="assets/img/icons/guide.png" alt="Guide Researchers">
      <p>Guide researchers on responsible conduct and integrity</p>
    </div>
    <div class="resp-card">
      <img src="assets/img/icons/approval.png" alt="Approve Studies">
      <p>Approve studies involving human participants, animals, or sensitive data</p>
    </div>
    <div class="resp-card">
      <img src="assets/img/icons/awareness.png" alt="Training">
      <p>Promote awareness and training on research ethics across the institute</p>
    </div>
  </div>
</div>

<!-- CTA -->
<div class="cta text-center">
  <button class="btn" id="contactCommitteeBtn">Contact Committee</button>
</div>

<!-- Modal Form -->
<div id="contactCommitteeModal" class="modal">
  <div class="modal-content">
    <span class="close-btn">&times;</span>
    <h2>Contact Ethics Committee</h2>
    <form id="contactCommitteeForm">
      <input type="text" name="name" placeholder="Full Name" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="text" name="subject" placeholder="Subject" required>
      <textarea name="message" placeholder="Your Message / Query" required></textarea>
      <button type="submit" class="btn">Send Message</button>
    </form>
  </div>
</div>



<script>
// Modal functionality
const contactBtn = document.getElementById('contactCommitteeBtn');
const modal = document.getElementById('contactCommitteeModal');
const closeBtn = document.querySelector('.close-btn');

contactBtn.onclick = () => modal.style.display = 'block';
closeBtn.onclick = () => modal.style.display = 'none';
window.onclick = e => { if(e.target == modal) modal.style.display = 'none'; };

// Form submit handler
document.getElementById('contactCommitteeForm').addEventListener('submit', function(e){
  e.preventDefault();
  alert('Your message has been sent to the Ethics Committee!');
  modal.style.display = 'none';
  this.reset();
});
</script>


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