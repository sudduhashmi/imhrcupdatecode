<footer class="footer-area pt-80 pb-50">
    <div class="container">

        <!-- TOP ROW: Logo Left + Social Right -->
        <div class="footer-top d-flex justify-content-between align-items-center flex-wrap pt-2">
            
            <!-- Logo -->
            <div class="footer-logo ">
                <img src="assets/img/footer-logo.png" alt="IMHRC Logo" style="max-width:75px;">
            </div>

            <!-- Social Links -->
         <div class="footer-social ">
    <a href="#"><i class="bx bxl-facebook"></i></a>
    <a href="#"><i class="bx bxl-instagram"></i></a>
    <a href="#"><i class="bx bxl-twitter"></i></a>
    <a href="#"><i class="bx bxl-linkedin"></i></a>
    <a href="#"><i class="bx bxl-youtube"></i></a>
</div>

        </div>

        <!-- Divider -->
        <hr class="footer-divider">

        <!-- BOTTOM 4 COLUMN SECTION -->
        <div class="row pt-4">

            <!-- GET IN TOUCH -->
            <div class="col-lg-3 col-sm-6 mb-4">
                <h4 class="footer-title">Get in Touch</h4>
                <ul class="footer-list">
                    <li><i class="bx bxs-phone"></i> <a href="tel:+915223190284">+915223190284</a>,<a href="tel:+91-9044507650"> +91-9044507650</a></li>
                    <li><i class="bx bxs-envelope"></i> <a href="mailto:imhrcindia@gmail.com">imhrcindia@gmail.com</a></li>
                    <li><p><span><i class="bx bxs-map"></i></span>1040 B, Sector C, Sachivalay Colony, Mahanagar, Lucknow, Uttar Pradesh-226006 India</p></li>
                  
                </ul>
            </div>

            <!-- QUICK LINKS -->
            <div class="col-lg-3 col-sm-6 mb-4">
                <h4 class="footer-title">Quick Links</h4>
                <ul class="footer-list">
                    <li><a href="join-our-team.php">Join Our Team</a></li>
                    <li><a href="grow-with-us.php">Grow With Us</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Cancel & Refund Policy</a></li>
                    <li><a href="event-reports-media-gallery.php">Media Gallery</a></li>
                </ul>
            </div>

            <!-- POPULAR LINKS -->
            <div class="col-lg-3 col-sm-6 mb-4">
                <h4 class="footer-title">Popular Links</h4>
                <ul class="footer-list">
                    <li><a href="hausla.php">Hausla</a></li>
                    <li><a href="international-conference.php">International Conference</a></li>
                    <li><a href="book-appointment.php">Book Consultation</a></li>
                    <li><a href="admission-form.php">Enroll Now</a></li>
                    <li><a href="research-&-publications.php">Research & Publications</a></li>
                     <li><a href="admin/login.php">Admin Login</a></li>
                </ul>
            </div>

            <!-- MAP SECTION -->
            <div class="col-lg-3 col-sm-6 mb-4">
                <h4 class="footer-title">Locate Us</h4>
                <div class="footer-map">
<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d113886.72997738598!2d80.81773906946184!3d26.87300201152144!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x399bfd0775c3d577%3A0xf51448744a4618ea!2sHAUSLA%20Early%20Intervention%20and%20Neuropsychological%20Rehabilitation%20Centre!5e0!3m2!1sen!2sin!4v1765047841328!5m2!1sen!2sin" width="100%" height="180" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>

        </div>

    </div>
</footer>
<!-- Copyright -->
<div class="copyright-area py-3">
    <div class="container text-center">
        <p class="m-0">
            © <span>IMHRC</span> | All Rights Reserved
        </p>
    </div>
</div>



    <!-- End Copyright Area -->

    <!-- Start Go Top Area -->
    <div class="go-top">
        <i class="bx bx-chevrons-up"></i>
        <i class="bx bx-chevrons-up"></i>
    </div>
    <!-- End Go Top Area -->
     <!-- Chat Toggle Button -->
<div class="chat-float-btn" id="chatToggle"><img src="assets/img/download.png" alt=""></div>

<!-- Chat Box -->
<div class="chat-box" id="chatBox">

  <!-- Header -->
  <div class="chat-header">
    <span>Fortis</span>
    <button id="closeChat">✕</button>
  </div>

  <!-- Body -->
  <div class="chat-body" id="chatBody">

    <div class="chat-msg bot">
      <p>
        Hi! I am <b>FaBian</b>, your Fortis appointment assistant.<br>
        By continuing, you agree to our T&C.
      </p>
    </div>

    <!-- BUTTON OPTIONS -->
    <div class="chat-options">
      <button onclick="botAction('appointment')">Book an Appointment</button>
      <button onclick="botAction('checkup')">Book a Health Checkup</button>
      <button onclick="botAction('callback')">Request a Callback</button>
      <button onclick="botAction('doctor')">Search Doctor</button>
    </div>

  </div>

  <!-- Input -->
  <div class="chat-input">
    <input type="text" id="chatInput" placeholder="Type your message...">
    <button id="sendMsg">➤</button>
  </div>

</div>
<!-- ASK THE EXPERT TAB -->
<div class="expert-tab" id="expertTab">
  Ask the <span>Expert</span>
</div>

<!-- ASK THE EXPERT PANEL -->
<div class="expert-panel" id="expertPanel">

  <div class="expert-header">
    <h5>Ask the Expert</h5>
    <span id="closeExpert">✕</span>
  </div>

  <div class="expert-body">
    <p class="text-muted small">
      Get expert guidance for therapy, counselling, academics & mental health.
    </p>

<form action="" method="POST">

  <label>Full Name</label>
  <input type="text" name="name" placeholder="Your Name">

  <label>Mobile Number</label>
  <input type="tel" name="mobile" placeholder="+91 XXXXX XXXXX">

  <label>Services Assessment </label>
  <select name="expert">
    <option>Select Services </option>
    <option>Online therapy</option>
    <option>Offline therapy</option>
    <option>Internship </option>
    <option>Academic Programmes</option>
    <option>Research and Publication</option>
  </select>



  <label>Your Question</label>
  <textarea name="message" rows="3"></textarea>

  <button type="submit" name="submit_query">Submit Query</button>
</form>


  </div>

</div>

<style>
    button#closeChat {
    /* padding: 10px; */
    background: none;
}
    .chat-float-btn {
  position: fixed;
  bottom: 25px;
  right: 25px;
  background: #ffb800;
  color: #fff;
  padding: 14px;
  border-radius: 50%;
  cursor: pointer;
  font-size: 22px;
  z-index: 9999;
}

.chat-box {
  width: 340px;
  background: #fff;
  position: fixed;
  bottom: 90px;
  right: 25px;
  border-radius: 14px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.2);
  display: none;
  flex-direction: column;
  z-index: 9999;
}

.chat-header {
  background: #ffb800;
  color: #fff;
  padding: 12px;
  font-weight: bold;
  display: flex;
  justify-content: space-between;
}

.chat-body {
  padding: 12px;
  height: 320px;
  overflow-y: auto;
}

.chat-msg {
  margin-bottom: 10px;
}

.chat-msg.bot p {
  background: #f1f1f1;
  padding: 10px;
  border-radius: 12px;
}

.chat-msg.user p {
  background: #16a34a;
  color: #fff;
  padding: 10px;
  border-radius: 12px;
  text-align: right;
}

.chat-options button {
  width: 100%;
  margin: 5px 0;
  background: #4b4fb2;
  border: none;
  color: #fff;
  padding: 8px;
  border-radius: 8px;
  cursor: pointer;
}

.chat-input {
  display: flex;
  border-top: 1px solid #eee;
}

.chat-input input {
  flex: 1;
  padding: 10px;
  border: none;
  outline: none;
}

.chat-input button {
  background: #ffb800;
  border: none;
  color: white;
  padding: 0 18px;
  font-size: 18px;
  cursor: pointer;
}
 .expert-panel {
  position: fixed;
  right: -360px;
  top: 0;
  width: 360px;
  height: 100vh;
  background: #fff;
  box-shadow: -10px 0 30px rgba(0,0,0,.15);
  transition: 0.4s;
  z-index: 10000;
  display: flex;
  flex-direction: column;   /* ✅ important */
}

/* Header fixed height */
.expert-header {
  flex-shrink: 0;
}

/* Body scrollable */
.expert-body {
  padding: 20px;
  overflow-y: auto;  
text-align: left;
 
}

</style>
    <script>
const chatToggle = document.getElementById("chatToggle");
const chatBox = document.getElementById("chatBox");
const closeChat = document.getElementById("closeChat");
const chatBody = document.getElementById("chatBody");
const chatInput = document.getElementById("chatInput");
const sendMsg = document.getElementById("sendMsg");

chatToggle.onclick = () => {
  chatBox.style.display = "flex";
  chatToggle.style.display = "none";
};

closeChat.onclick = () => {
  chatBox.style.display = "none";
  chatToggle.style.display = "block";
};

sendMsg.onclick = sendMessage;
chatInput.addEventListener("keypress", e => {
  if (e.key === "Enter") sendMessage();
});

function sendMessage() {
  let msg = chatInput.value.trim();
  if (!msg) return;

  chatBody.innerHTML += `
    <div class="chat-msg user"><p>${msg}</p></div>
  `;
  chatInput.value = "";
  chatBody.scrollTop = chatBody.scrollHeight;

  setTimeout(() => autoReply(msg), 800);
}

function botAction(type) {
  let message = "";

  if (type === "appointment")
    message = "To book appointment, please visit our booking page.";
  if (type === "checkup")
    message = "Health checkup packages are available. Want details?";
  if (type === "callback")
    message = "Please share your phone number. Our team will call you.";
  if (type === "doctor")
    message = "You can search doctor by department or location.";

  chatBody.innerHTML += `
    <div class="chat-msg bot"><p>${message}</p></div>
  `;
  chatBody.scrollTop = chatBody.scrollHeight;
}

function autoReply(msg) {
  let reply = "Can you please choose one of the options above?";

  if (msg.includes("appointment")) reply = "Sure 🙂 booking ke liye guide karta hoon.";
  if (msg.includes("doctor")) reply = "Doctor search option explained above.";
  if (msg.includes("payment")) reply = "We accept UPI, Card, NetBanking.";

  chatBody.innerHTML += `
    <div class="chat-msg bot"><p>${reply}</p></div>
  `;
  chatBody.scrollTop = chatBody.scrollHeight;
}
</script>
<script>
const expertTab = document.getElementById("expertTab");
const expertPanel = document.getElementById("expertPanel");
const closeExpert = document.getElementById("closeExpert");

expertTab.onclick = () => {
  expertPanel.classList.add("open");
};

closeExpert.onclick = () => {
  expertPanel.classList.remove("open");
};
</script>
