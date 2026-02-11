<?php
session_start();
// Include database connection
require_once 'admin/includes/db.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';
$success = '';
$info = ''; // Message for pending approval

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // UPDATED: Default role logic
    $role = isset($_POST['role']) ? $_POST['role'] : 'student'; 

    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Please fill all required fields.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if email exists
        $stmt = $conn->prepare("SELECT UserId FROM userlogin WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $error = "Email already registered. Please login.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Logic: Center accounts need approval (IsActive=0), others are active immediately (IsActive=1)
            $isActive = ($role === 'center') ? 0 : 1;

            // Insert user into userlogin table
            $stmt = $conn->prepare("INSERT INTO userlogin (Name, Email, Phone, Password, Role, IsActive) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssi", $name, $email, $phone, $hashed_password, $role, $isActive);
            
            if ($stmt->execute()) {
                if ($role === 'center') {
                    $info = "Registration successful! <br><strong>Your Center account is pending approval from Admin.</strong><br> You will be able to login once approved.";
                } else {
                    $success = "Registration successful! You can now <a href='login.php' class='alert-link'>Login here</a>.";
                }
            } else {
                $error = "Error: " . $conn->error;
            }
        }
        $stmt->close();
    }
}
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
    <title>INDIAN MENTAL HEALTH AND RESEARCH CENTRE (IMHRC)</title>
    
    <style>
        .login-section {
            padding: 80px 0;
            background-color: #f9fbfd;
            min-height: 80vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            background: #fff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            border: 1px solid #eee;
        }
        .form-control, .form-select {
            height: 50px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            padding: 10px 15px;
            font-size: 15px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #0b2c57;
            box-shadow: none;
        }
        .btn-login {
            background: #0b2c57;
            color: #fff;
            height: 50px;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            transition: 0.3s;
            border: none;
        }
        .btn-login:hover {
            background: #ffb703;
            color: #000;
        }
        .divider {
            border-top: 1px solid #eee;
            margin: 25px 0;
            position: relative;
        }
        .divider span {
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: #fff;
            padding: 0 10px;
            color: #777;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <?php include 'includes/header.php'; ?>

    <!-- Page Title -->
    <div class="page-title-wave">
        <div class="container">
            <h2>Create Account</h2>
            <p>Join our community today.</p>
        </div>
        <svg class="wave" viewBox="0 0 1440 320" preserveAspectRatio="none">
            <path fill="#f9fbfd" fill-opacity="1" d="M0,64L48,90.7C96,117,192,171,288,170.7C384,171,480,117,576,85.3C672,53,768,43,864,74.7C960,107,1056,181,1152,192C1248,203,1344,149,1392,122.7L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>

    <!-- Register Form -->
    <section class="login-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="login-card">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold" style="color: #0b2c57;">Register</h3>
                            <p class="text-muted">Create your account to continue</p>
                        </div>

                        <?php if($error): ?>
                            <div class="alert alert-danger text-center"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <?php if($success): ?>
                            <div class="alert alert-success text-center"><?php echo $success; ?></div>
                        <?php endif; ?>

                        <?php if($info): ?>
                            <div class="alert alert-info text-center"><?php echo $info; ?></div>
                        <?php endif; ?>

                        <?php if(!$success && !$info): ?>
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-muted">Full Name / Center Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-muted">Phone Number</label>
                                    <input type="text" name="phone" class="form-control" placeholder="+91 XXXXX XXXXX">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                            </div>
                            
                            <!-- UPDATED ROLE SELECTION -->
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Account Type</label>
                                <select name="role" class="form-select">
                                    <option value="student">Student (Buy Books/Courses)</option>
                                    <option value="patient">Patient (Book Appointments)</option>
                                    <option value="center">Training Center (Offline Classes)</option>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-muted">Password</label>
                                    <div class="input-group">
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePass('password')">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-muted">Confirm Password</label>
                                    <div class="input-group">
                                        <input type="password" name="confirm_password" id="cpassword" class="form-control" placeholder="Confirm" required>
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePass('cpassword')">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn-login mt-3">Create Account</button>
                        </form>
                        <?php endif; ?>

                        <div class="divider"><span>OR</span></div>

                        <div class="text-center">
                            <p class="mb-0">Already have an account? <a href="login.php" class="fw-bold text-decoration-none" style="color: #ffb703;">Login Here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/custom.js"></script>
    
    <script>
        function togglePass(id) {
            var x = document.getElementById(id);
            var icon = document.querySelector(`button[onclick="togglePass('${id}')"] i`);
            if (x.type === "password") {
                x.type = "text";
                if(icon) { icon.classList.remove('bi-eye'); icon.classList.add('bi-eye-slash'); }
            } else {
                x.type = "password";
                if(icon) { icon.classList.remove('bi-eye-slash'); icon.classList.add('bi-eye'); }
            }
        }
    </script>

</body>
</html>