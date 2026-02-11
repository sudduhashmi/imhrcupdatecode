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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/img/logo.png">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Title -->
    <title>IMHRC</title>
    
<style>
#otherExpertsSlider {
  padding: 20px 0;
}

#otherExpertsSlider .swiper-slide {
  height: auto;   /* force fix */
  display: flex;
  gap: 20;
}

.expert-card {
  background: #fff;
  border-radius: 12px;
  overflow: hidden;
  text-align: center;
  box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.expert-card img {
  width: 100%;
  height: 200px;
  object-fit: cover;
}

.form-control {
    /* height: 50px; */
    color: #324cc5;
    border: 1px solid #d6cdcd;
    background-color: transparent;
    border-radius: 0;
    font-size: 16px;
    /* padding: 10px 20px; */
    width: 100%;
}
/* EXPERT CARD */
.expert-card{
  background:#fff;
  border-radius:18px;
  box-shadow:0 12px 30px rgba(0,0,0,.08);
}

.expert-img{
  width:160px;
  height:160px;
  border-radius:16px;
  object-fit:cover;
}

.tag{
  display:inline-block;
  padding:6px 14px;
  background:#eef3ff;
  color:#0d3cff;
  border-radius:30px;
  font-size:13px;
  margin:4px;
}

/* BUTTON */
.btn-primary{
  border-radius:14px;
  padding:12px 22px;
  font-weight:600;
}

/* MODAL */
.modal-content{
  border-radius:18px;
}
.modal-body{
  max-height:70vh;
  overflow-y:auto;
}
.policy h6{
  margin-top:18px;
  font-weight:700;
}
</style>
<style>
body{
  background:#f4f7fb;
  font-family:system-ui;
}
.section2 {
    background: #fff;
    padding: 30px;
}
.avatar{
  width:130px;height:130px;
  border-radius:50%;
  object-fit:cover;
  border:5px solid #e9efff;
}
.tag{
  display:inline-block;
  background:#e9efff;
  color:#0d6efd;
  padding:6px 16px;
  border-radius:50px;
  font-size:13px;
  margin:4px;
}

/* SLOT */
.slot-btn{
  width:100%;
  padding:12px;
  border-radius:12px;
  border:1px solid #ddd;
  background:#fff;
  font-weight:600;
  transition:.2s;
}
.slot-btn:hover:not(.booked){
  background:#0d6efd;
  color:#fff;
}
.slot-btn.active{
  background:#0d6efd;
  color:#fff;
}
.slot-btn.booked{
  background:#eee;
  color:#999;
  cursor:not-allowed;
}

/* SLIDER */
.expert-slider{
  display:flex;
  gap:20px;

  padding-bottom:10px;
 
}
.expert-slider::-webkit-scrollbar{
  height:6px;
}
.expert-slider::-webkit-scrollbar-thumb{
  background:#d0d7e2;
  border-radius:10px;
}
.expert-card{
  min-width:220px;
  background:#fff;
  border-radius:18px;
  padding:18px;
  text-align:center;
  box-shadow:0 8px 22px rgba(0,0,0,.08);
  transition:.3s;
  scroll-snap-align:start;
}
.expert-card:hover{
  transform:translateY(-6px);
  box-shadow:0 14px 32px rgba(0,0,0,.15);
}
.expert-card img{
    width: 100%;
    height: 100%;
    border-radius: 0;
    object-fit: cover;
    margin-bottom: 10px;
    border: 4px solid #e9efff;
}
.expert-slider {
  padding: 10px 0;
}

.expert-card {
  background: #fff;
  border-radius: 12px;
  padding: 15px;
  text-align: center;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.expert-card img {
  width: 100%;
  height: 160px;
  object-fit: cover;
  border-radius: 10px;
  margin-bottom: 10px;
}

.swiper-button-next,
.swiper-button-prev {
  color: #000;
}

</style>
</head>

<body>


   <?php include 'includes/header.php'; ?>
   	<!-- Start Page Title Area -->


<?php
$expert = [
  'name'=>'Dr. Garima Singh',
  'role'=>'Clinical Psychologist',
  'fee'=>800,
  'image'=>'https://images.unsplash.com/photo-1550831107-1553da8c8464',
  'about'=>'Dr. Garima Singh is an experienced clinical psychologist specialising in anxiety, depression, stress management, relationship counselling, CBT therapy, trauma recovery and sleep disorders.'
];
?>

<div class="container py-4">

<!-- ================= TOP EXPERT ================= -->

<div class="container py-5">

  <!-- ===== EXPERT SECTION ===== -->
  <div class="expert-card">
    <div class="row align-items-center">
      
      <div class="col-md-4 text-center">
        <img src="https://i.imgur.com/VL0u7wN.jpg" class="expert-img">
      </div>

      <div class="col-md-8 " style="    text-align: left">
        <h2 class="mb-1">Dr. Garima Singh</h2>
        <div class="text-muted mb-2">
          Clinical Psychologist | M.Phil | Ph.D
        </div>

        <p>
          Dr. Garima Singh is an experienced Clinical Psychologist specializing in
          evidence-based therapies such as CBT. She helps individuals dealing with
          anxiety, depression, stress, childhood behavioural issues, and adolescent
          mental health concerns.
        </p>

        <div class="mb-2">
          <strong>Expertise:</strong><br>
          Mental Illness, Childhood & Adolescent Mental Health, CBT
        </div>

        <div class="mt-2">
          <span class="tag">Anxiety</span>
          <span class="tag">Depression</span>
          <span class="tag">Stress</span>
          <span class="tag">CBT Therapy</span>
          <span class="tag">Relationship Issues</span>
        </div>

        <h4 class="text-primary mt-3">
          Consultation Fee: ₹800
        </h4>

        <div class="badge bg-dark px-3 py-2 mt-2">
          Slots: Mon – Fri | 11:00 AM – 7:00 PM
        </div>

        <div class="mt-4">
          <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#termsModal">
            View Terms & Conditions
          </button>
        </div>
      </div>

    </div>
  </div>

</div>

<!-- ===== TERMS MODAL ===== -->
<div class="modal fade" id="termsModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Terms, Conditions & Policies</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body policy">

        <h6>1. Terms & Conditions</h6>
        <p><strong>Scope of Services</strong></p>
        <ul>
          <li>Counselling & mental healthcare services (online/offline)</li>
          <li>Educational programmes, internships & training</li>
        </ul>

        <p><strong>Payment</strong></p>
        <ul>
          <li>All fees are in INR and payable at the time of booking</li>
          <li>Access may be cancelled if payment fails</li>
        </ul>

        <p><strong>Bookings & Confirmation</strong></p>
        <ul>
          <li>Bookings confirmed only after successful Razorpay payment</li>
          <li>Email/SMS confirmation will be sent</li>
        </ul>

        <p><strong>User Obligations</strong></p>
        <ul>
          <li>Accurate personal & billing details required</li>
          <li>Users must maintain confidentiality</li>
        </ul>

        <h6>2. Refund & Cancellation Policy</h6>
        <ul>
          <li>Training & academic fees are non-refundable</li>
          <li>Counselling cancelled within 24 hours → 50% refund</li>
          <li>No-show or late cancellation → No refund</li>
          <li>Refunds processed in 7–10 business days</li>
        </ul>

        <h6>3. Shipping & Delivery Policy</h6>
        <ul>
          <li>Digital services delivered via email</li>
          <li>Physical materials dispatched via courier</li>
          <li>Delivery timeline: 7–15 business days</li>
        </ul>

        <h6>4. Privacy Policy</h6>
        <ul>
          <li>We collect personal & usage data</li>
          <li>Used for payments, bookings & notifications</li>
          <li>No selling of personal data</li>
          <li>Shared only with Razorpay & legal authorities if required</li>
        </ul>

        <p class="text-muted mt-4">
          © Sanseeb Health & Edutech Pvt Ltd
        </p>

      </div>

      <div class="modal-footer">
        <button class="btn btn-primary" data-bs-dismiss="modal">I Understand</button>
      </div>

    </div>
  </div>
</div>


<!-- ================= RELATED EXPERTS ================= -->
<div class="py-5">
  <h5 class="mb-3">Related Experts</h5>

  <div class="swiper expert-slider">
    <div class="swiper-wrapper">

      <?php for($i=1;$i<=6;$i++): ?>
      <div class="swiper-slide">
        <div class="expert-card">
          <img src="https://images.unsplash.com/photo-1612349317150-e413f6a5b16d" />
          <h6 class="mb-1">Dr. Expert <?=$i?></h6>
          <small class="text-muted">Clinical Psychologist</small>
        </div>
      </div>
      <?php endfor; ?>

    </div>

    <!-- arrows -->
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
  </div>
</div>


<!-- ================= BOOKING FORM ================= -->
<div class="section2">
<h4 class="mb-3">Book Appointment</h4>

<form>
<input type="hidden" id="slotInput">

<div class="row g-3">

  <div class="col-md-6">
    <label>Patient Name</label>
    <input type="text" class="form-control" placeholder="Enter patient full name" required>
  </div>

  <div class="col-md-6">
    <label>Phone / Email</label>
    <input type="text" class="form-control" placeholder="Enter phone number or email" required>
  </div>

  <div class="col-md-3">
    <label>Age</label>
    <input type="number" class="form-control" placeholder="Age">
  </div>

  <div class="col-md-9">
    <label>Problem / Notes</label>
    <input type="text" class="form-control" placeholder="Briefly describe the issue or notes">
  </div>

  <div class="col-md-4">
    <label>Select Date</label>
    <input type="date" id="dateInput" class="form-control" placeholder="Select appointment date" required>
  </div>

  <div class="col-md-4">
    <label>Language</label>
    <select class="form-select">
      <option value="" selected disabled>Select preferred language</option>
      <option>English</option>
      <option>Hindi</option>
      <option>Both</option>
    </select>
  </div>

  <div class="col-md-4">
    <label>Appointment Type</label>
    <select class="form-select" id="appointmentType">
      <option value="Online">Online</option>
      <option value="Offline">Offline</option>
    </select>
  </div>

  <div class="col-12">
    <div id="typeMessage" class="alert alert-info d-flex gap-2 align-items-center">
      <i class="bi bi-camera-video"></i>
      Online consultation via Zoom / Google Meet.
    </div>
  </div>

</div>

<hr>

<!-- SLOTS -->
<div>
  <div class="d-flex justify-content-between mb-2">
    <strong>Select Time Slot</strong>
    <span>Selected: <b id="selectedSlot">—</b></span>
  </div>

  <div class="row g-2" id="slotsGrid"></div>

  <div class="mt-3 fw-bold text-success">
    Payable Amount: ₹<?=$expert['fee']?>
  </div>
</div>

<button class="btn btn-primary w-100 mt-4">
  Proceed to Payment
</button>
</form>

</div>

<!-- ================= OTHER EXPERTS ================= -->
<div class="py-5">
  <h5 class="mb-3">Other Experts</h5>

  <div class="swiper" id="otherExpertsSlider">
    <div class="swiper-wrapper">

      <?php for($i=1;$i<=6;$i++): ?>
      <div class="swiper-slide">
        <div class="expert-card">
          <img src="https://images.unsplash.com/photo-1607746882042-944635dfe10e" />
          <h6 class="mb-1">Dr. Expert <?=$i?></h6>
          <small class="text-muted">Therapist</small>
        </div>
      </div>
      <?php endfor; ?>

    </div>

    <!-- arrows -->
    <div class="swiper-button-next" id="other-next"></div>
    <div class="swiper-button-prev" id="other-prev"></div>
  </div>
</div>


</div>
<script>
const otherExpertsSwiper = new Swiper("#otherExpertsSlider", {
  slidesPerView: 3,
  spaceBetween: 20,
  loop: true,
  speed: 800,

  autoplay: {
    delay: 3000,
    disableOnInteraction: false,
  },

  navigation: {
    nextEl: "#other-next",
    prevEl: "#other-prev",
  },

  breakpoints: {
    0: {
      slidesPerView: 1,
    },
    768: {
      slidesPerView: 2,
    },
    992: {
      slidesPerView: 3,
    }
  }
});
</script>


<script>
// DATE FIX (NO PAST DATE, START FROM TODAY)
const dateInput = document.getElementById('dateInput');

// local date fix (timezone safe)
const today = new Date();
const yyyy = today.getFullYear();
const mm = String(today.getMonth() + 1).padStart(2, '0');
const dd = String(today.getDate()).padStart(2, '0');
const todayStr = `${yyyy}-${mm}-${dd}`;

// set min date
dateInput.min = todayStr;

// auto select today's date on load
dateInput.value = todayStr;


// ONLINE / OFFLINE MESSAGE
const appointmentType = document.getElementById('appointmentType');
const typeMessage = document.getElementById('typeMessage');

appointmentType.onchange = () => {
  if (appointmentType.value === 'Online') {
    typeMessage.className = 'alert alert-info d-flex gap-2 align-items-center';
    typeMessage.innerHTML = '<i class="bi bi-camera-video"></i> Online consultation via Zoom / Google Meet.';
  } else {
    typeMessage.className = 'alert alert-warning d-flex gap-2 align-items-center';
    typeMessage.innerHTML = '<i class="bi bi-hospital"></i> Offline consultation requires clinic visit.';
  }
};


// SLOT LOGIC
const slots = ['09:00 AM','09:30 AM','10:00 AM','10:30 AM','01:00 PM','01:30 PM','02:00 PM'];
const booked = ['10:00 AM'];

const slotsGrid = document.getElementById('slotsGrid');
const selectedSlot = document.getElementById('selectedSlot');
const slotInput = document.getElementById('slotInput');

function loadSlots(){
  slotsGrid.innerHTML='';
  selectedSlot.innerText='—';
  slotInput.value='';

  slots.forEach(t=>{
    const col=document.createElement('div');
    col.className='col-6 col-md-3';

    const btn=document.createElement('button');
    btn.type='button';
    btn.className='slot-btn';

    if(booked.includes(t)){
      btn.classList.add('booked');
      btn.disabled=true;
      btn.innerHTML = t + '<br><small>Booked</small>';
    }else{
      btn.innerText=t;
      btn.onclick=()=>{
        document.querySelectorAll('.slot-btn').forEach(b=>b.classList.remove('active'));
        btn.classList.add('active');
        selectedSlot.innerText=t;
        slotInput.value=t;
      };
    }

    col.appendChild(btn);
    slotsGrid.appendChild(col);
  });
}

// load slots on page load
loadSlots();

// reload slots on date change
dateInput.onchange = loadSlots;
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
  <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

<script>
new Swiper(".expert-slider", {
  slidesPerView: 4,
  spaceBetween: 20,
  loop: true,
  autoplay: {
    delay: 2500,
    disableOnInteraction: false,
  },
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
  breakpoints: {
    0: {
      slidesPerView: 1,
    },
    768: {
      slidesPerView: 2,
    },
    992: {
      slidesPerView: 3,
    }
  }
});
</script>

</body>


</html>