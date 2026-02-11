<?php
// Enable Error Reporting to debug 500 Errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Session check removed here because auth.php handles it, preventing the "Session already active" notice.

// 1. Check if required files exist before requiring them
$required_files = ['includes/config.php', 'includes/db.php', 'includes/auth.php'];
foreach ($required_files as $file) {
    if (!file_exists(__DIR__ . '/' . $file)) {
        die("<strong>Error:</strong> The file <code>" . htmlspecialchars($file) . "</code> was not found. Please make sure it exists in the 'admin' folder.");
    }
    require_once __DIR__ . '/' . $file;
}

// 2. Redirect if already logged in (Check if function exists first)
if (function_exists('redirectIfLoggedIn')) {
    redirectIfLoggedIn();
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $userType = trim($_POST['user_type'] ?? 'admin');
    
    if ($email === '') $errors[] = 'Email is required';
    if ($password === '') $errors[] = 'Password is required';
    
    if (!$errors) {
        // Ensure Database connection is active
        if (!isset($conn) || $conn->connect_error) {
            die("Database connection failed. Please check includes/db.php");
        }

        if ($userType === 'admin') {
            // --- ADMIN LOGIN ---
            // Changed SELECT * to specific columns for bind_result compatibility
            $stmt = $conn->prepare("SELECT AdminId, Name, Email, Password, Role FROM admins WHERE Email = ? AND IsActive = 1");
            if(!$stmt) die("SQL Error: " . $conn->error);
            
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result(); // Required for num_rows check

            if ($stmt->num_rows > 0) {
                // Bind results to variables
                $stmt->bind_result($db_id, $db_name, $db_email, $db_password, $db_role);
                $stmt->fetch();

                if (password_verify($password, $db_password)) {
                    // Store Session Data
                    $_SESSION['admin_id'] = $db_id;
                    $_SESSION['admin_email'] = $db_email;
                    $_SESSION['admin_name'] = $db_name;
                    $_SESSION['admin_role'] = $db_role;
                    $_SESSION['user_type'] = 'admin';
                    
                    header('Location: index.php');
                    exit;
                } else {
                    $errors[] = 'Invalid email or password';
                }
            } else {
                $errors[] = 'Invalid email or password';
            }
            $stmt->close();
        } elseif ($userType === 'student') {
            // --- STUDENT LOGIN ---
            // Check if student table exists first to avoid crash
            $checkTable = $conn->query("SHOW TABLES LIKE 'studentlogin'");
            if($checkTable->num_rows == 0) {
                $errors[] = "Student login is not set up yet (Table missing).";
            } else {
                // Changed SELECT * to specific columns
                $stmt = $conn->prepare("SELECT StudentId, Name, Email, Password FROM studentlogin WHERE Email = ? AND IsActive = 1");
                if(!$stmt) die("SQL Error: " . $conn->error);

                $stmt->bind_param('s', $email);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $stmt->bind_result($db_id, $db_name, $db_email, $db_password);
                    $stmt->fetch();

                    if (password_verify($password, $db_password)) {
                        $_SESSION['student_id'] = $db_id;
                        $_SESSION['student_email'] = $db_email;
                        $_SESSION['student_name'] = $db_name;
                        $_SESSION['student_role'] = 'student';
                        $_SESSION['user_type'] = 'student';
                        
                        header('Location: student-dashboard.php');
                        exit;
                    } else {
                        $errors[] = 'Invalid email or password';
                    }
                } else {
                    $errors[] = 'Invalid email or password';
                }
                $stmt->close();
            }
        } else {
            $errors[] = 'Invalid user type selected';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMHRC Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html { height: 100%; margin: 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-family: 'Segoe UI', sans-serif; }
        .login-container { display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 20px; }
        .login-card { background: rgba(255, 255, 255, 0.95); border-radius: 15px; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3); overflow: hidden; max-width: 420px; width: 100%; }
        .login-header { background: #ffffff; padding: 30px; text-align: center; border-bottom: 1px solid #eee; }
        .login-header img { max-height: 100px; margin-bottom: 15px; }
        .login-body { padding: 40px; }
        .btn-login { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; padding: 12px; font-weight: 600; border-radius: 8px; color: white; width: 100%; }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4); }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <!-- Fallback image if logo missing -->
                <img src="../assets/img/logo.png" alt="IMHRC Logo" class="img-fluid" onerror="this.src='https://placehold.co/150x50?text=IMHRC'">
                <h3>Admin Panel</h3>
                <p class="text-muted mt-2">Sign in to your account</p>
            </div>
            <div class="login-body">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error) echo "<div>• " . htmlspecialchars($error) . "</div>"; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="mb-3 form-floating">
                        <select class="form-select" id="user_type" name="user_type">
                            <option value="admin">Admin Panel</option>
                            <option value="student">Student Portal</option>
                        </select>
                        <label for="user_type">Login As</label>
                    </div>
                    <div class="mb-3 form-floating">
                        <input type="email" class="form-control" name="email" placeholder="Email" required>
                        <label>Email address</label>
                    </div>
                    <div class="mb-3 form-floating">
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                        <label>Password</label>
                    </div>
                    <button type="submit" class="btn btn-login">Sign In</button>
                </form>
                <div class="text-center mt-4 text-muted small">© <?php echo date('Y'); ?> IMHRC</div>
            </div>
        </div>
    </div>
</body>
</html>