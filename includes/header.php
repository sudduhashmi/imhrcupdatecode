<?php
if (session_status() === PHP_SESSION_NONE) {
    // session_start();
}

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? explode(' ', $_SESSION['user_name'])[0] : ''; // Get first name
?>
    
    <!-- Custom CSS for Header Buttons -->
    <style>
     
   
        
        /* Navbar Toggler Customization */
        .navbar-toggler {
            border: none;
            padding: 0;
            outline: none;
        }
        .navbar-toggler:focus {
            box-shadow: none;
        }
        
        /* Expert Tab Styles */
        .expert-tab { position: fixed; right: -52px; top: 50%; transform: rotate(-90deg); background: #2563eb; color: #fff; padding: 10px 18px; font-weight: bold; border-radius: 8px 8px 0 0; cursor: pointer; z-index: 9999; }
        .expert-panel { position: fixed; right: -360px; top: 0; width: 360px; height: 100vh; background: #fff; box-shadow: -10px 0 30px rgba(0,0,0,.15); transition: 0.4s; z-index: 10000; }
        .expert-panel.open { right: 0; }
        .expert-header { background: #ffb800; color: white; padding: 15px; display: flex; justify-content: space-between; }
        .expert-body { padding: 20px; }
        .expert-body input, .expert-body select, .expert-body textarea { width: 100%; margin-bottom: 10px; padding: 10px; border: 1px solid #ddd; border-radius: 6px; }
        .expert-body button { width: 100%; background: #ffb800; color: #000000; border: none; padding: 12px; border-radius: 30px; font-weight: bold; }
        .bxl-twitter { position: relative; font-size: 20px; }
    </style>

    <!-- Start Header Area -->
    <header class="header-area">
        <!-- Top Bar (Visible only on Large screens) -->
        <div class="top__bar d-none d-lg-block">
            <div class="container-fluid custom__container">
                <div class="row align-items-center">
                    <div class="col-xl-3 col-md-6">
                        <div class="top__bar-left">
                            <a href="tel:+915223190284"> <i class="bx bxs-phone-call"></i>+915223190284</a>
                            <a href="mailto:imhrcindia@gmail.com"> <i class="bx bxs-envelope"></i>imhrcindia@gmail.com </a>
                        </div>
                    </div>
                    <div class="col-xl-9 col-md-6">
                        <div class="top__bar-right">
                            <div class="topbar__three-right t-right lg-t-center top-menu">
                                <ul>
                                    <li><a href="join-our-team.php">Join our Team</a></li>
                                    <li><a href="grow-with-us.php">Grow with us</a></li>
                                    <li><a href="internships-&-training.php">Internships & Training</a></li>
                                    <li><a href="fellowship.php">Fellowship</a></li>
                                    <li><a href="contact-us.php">Contact Us</a></li>
                                    
                                    <!-- DESKTOP LOGIN / REGISTER LOGIC -->
                                    <?php if ($isLoggedIn): ?>
                                        <!-- If Logged In: Show Dashboard -->
                                        <li>
                                            <a href="user-dashboard.php" class="auth-link text-primary">
                                                <i class="bx bx-grid-alt me-1"></i> Dashboard
                                            </a>
                                        </li>
                                        <li>
                                            <a href="admin/logout.php" class="auth-link text-danger">
                                                <i class="bx bx-log-out me-1"></i> Logout
                                            </a>
                                        </li>
                                    <?php else: ?>
                                        <!-- If Guest: Show Login/Register -->
                                        <li>
                                            <a href="login.php" class="auth-link">
                                                <i class="bx bx-log-in me-1"></i> Login
                                            </a>
                                        </li>
                                        <li>
                                            <a href="register.php" class="">  <i class="bx bx-user-plus me-1"></i>
                                                Register
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                </ul>
                            </div>
                            <div class="top__bar-right-social">
                                <ul>
                                    <li><a href="https://www.facebook.com/" target="_blank"><i class="bx bxl-facebook"></i></a></li>
                                    <li><a href="https://www.instagram.com/" target="_blank"><i class="bx bxl-instagram"></i></a></li>
                                    <li><a href="https://twitter.com/" target="_blank"><i class="bx bxl-twitter"></i></a></li>
                                    <li><a href="https://www.linkedin.com/" target="_blank"><i class="bx bxl-linkedin"></i></a></li>
                                    <li><a href="https://www.youtube.com/" target="_blank"><i class="bx bxl-youtube"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Start Navbar Area -->
        <div class="navbar-area">
            <!-- Mobile Menu (MeanMenu handles this automatically, but we keep structure clean) -->
            <div class="mobile-nav">
                <div class="container-fluid">
                    <div class="mobile-menu">
                        <div class="logo">
                            <a href="index.php">
                                <img src="assets/img/logo.png" alt="logo">
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="desktop-nav">
                <div class="container-fluid">
                    <nav class="navbar navbar-expand-md navbar-light">
                        <a class="navbar-brand" href="index.php">
                            <img src="assets/img/logo.png" alt="logo">
                        </a>

                        <!-- Mobile Toggler Button -->
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse mean-menu" id="navbarSupportedContent">
                            <ul class="navbar-nav m-auto">
                                <li class="nav-item">
                                    <a href="index.php" class="nav-link">Home</a>
                                </li>

                                <li class="nav-item">
                                    <a href="#" class="nav-link">About Us <i class="bx bx-chevron-down"></i></a>
                                    <ul class="dropdown-menu">
                                        <li class="nav-item"><a href="about-overview.php" class="nav-link">Overview</a></li>
                                        <li class="nav-item"><a href="global-collaborations.php" class="nav-link">Global Collaborations</a></li>
                                        <li class="nav-item"><a href="social-impact-&-SDG-contributions.php" class="nav-link">Social Impact & SDG Contributions</a></li>
                                        <li class="nav-item"><a href="leadership-team.php" class="nav-link">Leadership Team</a></li>
                                        <li class="nav-item"><a href="annual-report.php" class="nav-link">Annual Report</a></li>
                                    </ul>
                                </li>

                                <li class="nav-item">
                                    <a href="hausla.php" class="nav-link" target="blank">Hausla <i class="bx bx-chevron-down"></i></a>
                                    <ul class="dropdown-menu">
                                        <li class="nav-item"><a href="hausla-overview.php" class="nav-link">Overview</a></li>
                                        <li class="nav-item"><a href="our-services.php" class="nav-link">Our Services</a></li>
                                        <li class="nav-item"><a href="our-team.php" class="nav-link">Our Team</a></li>
                                        <li class="nav-item"><a href="book-appointment.php" class="nav-link">Book Appointment</a></li>
                                        <li class="nav-item"><a href="event-reports-media-gallery.php" class="nav-link">Event Reports & Media Gallery</a></li>
                                    </ul>
                                </li>

                                <li class="nav-item">
                                    <a href="clinical-&-diagnostic-services.php" class="nav-link">Clinical & Diagnostic Services <i class="bx bx-chevron-down"></i></a>
                                                                     <ul class="dropdown-menu">
                                        <li class="nav-item"><a href="adult-CTVS.php" class="nav-link">Career Counselling</a></li>
                                        <li class="nav-item"><a href="anaesthesia.php" class="nav-link">Behaviour Therapy</a>
                                        </li>
                                        <li class="nav-item"><a href="art-therapy.php" class="nav-link">Special Education </a>
                                        </li>
                                        <li class="nav-item"><a href="breast-and-endocrine-surgery.php"
                                                class="nav-link">Occupational Therapy</a></li>
                                        <li class="nav-item"><a href="cardiac-sciences.php" class="nav-link">Psychotherapy</a></li>
                                        <li class="nav-item"><a href="counselling-psychology.php"
                                                class="nav-link">Counselling Psychology</a></li>
                                        <li class="nav-item"><a href="critical-care.php" class="nav-link">Psychometric Assessments</a></li>
                                    </ul>
                                </li>

                                <li class="nav-item">
                                    <a href="academic-programs.php" class="nav-link">Academic Programs <i class="bx bx-chevron-down"></i></a>
                                <ul class="dropdown-menu">
                                        <li class="nav-item"><a href="certificate-&-online-programs.php"
                                                class="nav-link">Certificate Programs</a></li>
                                        <li class="nav-item"><a href="diploma-programs.php" class="nav-link">Diploma
                                                Programs</a></li>
                                        <li class="nav-item"><a href="undergraduate-programs.php"
                                                class="nav-link">Undergraduate Programs</a></li>
                                        <li class="nav-item"><a href="postgraduate-diploma.php"
                                                class="nav-link">Postgraduate Diploma</a></li>

                                        <li class="nav-item ">
                                            <a href="nihe.php" class="nav-link">
                                                NIHE offerings
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="nav-item">
                                    <a href="#" class="nav-link">Research & Publications <i class="bx bx-chevron-down"></i></a>
                                    <ul class="dropdown-menu">
                                        <li class="nav-item"><a href="research-&-publications.php" class="nav-link">Research & Publications</a></li>
                                        <li class="nav-item"><a href="ongoing-projects-collaborations.php" class="nav-link">Ongoing Projects</a></li>
                                        <li class="nav-item"><a href="research-ethics-integrity-guidelines.php" class="nav-link">Ethics & Guidelines</a></li>
                                    </ul>
                                </li>

                                <li class="nav-item">
                                    <a href="#" class="nav-link">Conferences & Events <i class="bx bx-chevron-down"></i></a>
                                    <ul class="dropdown-menu">
                                        <li class="nav-item"><a href="international-conference.php" class="nav-link">International Conference</a></li>
                                        <li class="nav-item"><a href="conference-proceedings-archive.php" class="nav-link">Conference Archive</a></li>
                                        <li class="nav-item"><a href="monthly-mental-health-forums.php" class="nav-link">Mental Health Forums</a></li>
                                        <li class="nav-item"><a href="past-event-reports-media-gallery.php" class="nav-link">Past Events</a></li>
                                    </ul>
                                </li>

                                <li class="nav-item">
                                    <a href="#" class="nav-link">Resources <i class="bx bx-chevron-down"></i></a>
                                    <ul class="dropdown-menu">
                                        <li class="nav-item"><a href="articles-blogs.php" class="nav-link">Articles & Blogs</a></li>
                                        <li class="nav-item"><a href="video-explainers-podcasts.php" class="nav-link">Videos & Podcasts</a></li>
                                        <li class="nav-item"><a href="self-help-resources-toolkits.php" class="nav-link">Self-Help Resources</a></li>
                                    </ul>
                                </li>
                                
                                <!-- MOBILE MENU LOGIN/REGISTER (Visible on Mobile & Tablet) -->
                                <?php if (!$isLoggedIn): ?>
                                    <li class="nav-item d-lg-none">
                                        <a href="login.php" class="nav-link" style="color: #0b2c57; font-weight: 700;">
                                            <i class="bx bx-log-in me-1"></i> Login
                                        </a>
                                    </li>
                                    <li class="nav-item d-lg-none">
                                        <a href="register.php" class="nav-link" style="color: #ffb703; font-weight: 700;">
                                            <i class="bx bx-user-plus me-1"></i> Register
                                        </a>
                                    </li>
                                <?php else: ?>
                                    <!-- If Logged In: Show Dashboard on Mobile -->
                                    <li class="nav-item d-lg-none">
                                        <a href="user-dashboard.php" class="nav-link" style="color: #0b2c57; font-weight: 700;">
                                            <i class="bx bxs-dashboard me-1"></i> Dashboard
                                        </a>
                                    </li>
                                    <li class="nav-item d-lg-none">
                                        <a href="admin/logout.php" class="nav-link" style="color: #dc3545; font-weight: 700;">
                                            <i class="bx bx-log-out me-1"></i> Logout
                                        </a>
                                    </li>
                                <?php endif; ?>

                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
        <!-- End Navbar Area -->
    </header>