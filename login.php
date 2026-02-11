<?php
// --- DEBUGGING: Enable Error Reporting to fix 500 Error ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// --- INCLUDE DATABASE CONNECTION ---
// Check path dynamically
$dbPath = 'admin/includes/db.php';
if (file_exists($dbPath)) {
    require_once $dbPath;
} else {
    die("Error: Database connection file not found at '$dbPath'.");
}

// --- HANDLE REDIRECT ---
$redirect = isset($_GET['redirect']) && !empty($_GET['redirect']) ? trim($_GET['redirect']) : 'user-dashboard.php';

// Agar pehle se login hai to redirect kar do
if (isset($_SESSION['user_id'])) {
    header("Location: $redirect");
    exit;
}

$error = '';

// --- LOGIN LOGIC ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "Please enter email and password.";
    } else {
        // Sirf userlogin table check karein
        $stmt = $conn->prepare("SELECT UserId, Name, Email, Password, Role FROM userlogin WHERE Email = ? AND IsActive = 1");
        
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            
            // FIX: Using store_result + bind_result instead of get_result for compatibility
            $stmt->store_result();
            
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($dbUserId, $dbName, $dbEmail, $dbPassword, $dbRole);
                $stmt->fetch();

                if (password_verify($password, $dbPassword)) {
                    // Session Set
                    $_SESSION['user_id'] = $dbUserId;
                    $_SESSION['user_name'] = $dbName;
                    $_SESSION['user_email'] = $dbEmail;
                    $_SESSION['user_role'] = $dbRole;
                    
                    // Redirect to Dashboard or previous page
                    header("Location: $redirect");
                    exit;
                } else {
                    $error = "Invalid password.";
                }
            } else {
                $error = "Account not found. Please register first.";
            }
            $stmt->close();
        } else {
            $error = "Database error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login - IMHRC</title>

    <!-- CSS Links -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/boxicons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="icon" type="image/png" href="assets/img/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
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
        .form-control {
            height: 50px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            padding: 10px 15px;
            font-size: 15px;
        }
        .form-control:focus {
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
            <h2>Login</h2>
            <p>Welcome back! Please login to your account.</p>
        </div>
        <svg class="wave" viewBox="0 0 1440 320" preserveAspectRatio="none">
            <path fill="#f9fbfd" fill-opacity="1" d="M0,64L48,90.7C96,117,192,171,288,170.7C384,171,480,117,576,85.3C672,53,768,43,864,74.7C960,107,1056,181,1152,192C1248,203,1344,149,1392,122.7L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>

    <!-- Login Form -->
    <section class="login-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-8">
                    <div class="login-card">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold" style="color: #0b2c57;">Sign In</h3>
                            <p class="text-muted">Enter your credentials to continue</p>
                        </div>

                        <?php if($error): ?>
                            <div class="alert alert-danger text-center"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label fw-bold small text-muted">Password</label>
                                    <!-- <a href="forgot-password.php" class="small text-decoration-none">Forgot?</a> -->
                                </div>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Enter password" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePass()">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <button type="submit" class="btn-login mt-3">Login</button>
                        </form>

                        <div class="divider"><span>OR</span></div>

                        <div class="text-center">
                            <p class="mb-0">Don't have an account? <a href="register.php" class="fw-bold text-decoration-none" style="color: #ffb703;">Register Here</a></p>
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
        function togglePass() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>

</body>
</html>