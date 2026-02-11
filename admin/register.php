<?php
session_start();
require_once 'includes/config.php'; // Ensure this file exists or remove if not needed
require_once 'includes/db.php';     // Database connection

$errors = [];
$success = '';

// Redirect if already logged in
if (isset($_SESSION['admin_id']) || isset($_SESSION['student_id'])) {
    header('Location: index.php'); // Or appropriate dashboard
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    $userType = trim($_POST['user_type'] ?? 'student'); // Default to student
    
    // Basic Validation
    if (empty($name)) $errors[] = 'Full Name is required.';
    if (empty($email)) $errors[] = 'Email is required.';
    if (empty($password)) $errors[] = 'Password is required.';
    if ($password !== $confirm_password) $errors[] = 'Passwords do not match.';
    if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters long.';
    
    // Database Logic
    if (empty($errors)) {
        global $conn;
        
        if ($userType === 'student') {
            // Check if student email exists
            $check = $conn->prepare("SELECT StudentId FROM studentlogin WHERE Email = ?");
            $check->bind_param("s", $email);
            $check->execute();
            if ($check->get_result()->num_rows > 0) {
                $errors[] = 'A student with this email already exists.';
            } else {
                // Insert Student
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO studentlogin (Name, Email, Password, IsActive) VALUES (?, ?, ?, 1)");
                $stmt->bind_param("sss", $name, $email, $hashed_password);
                
                if ($stmt->execute()) {
                    $success = 'Registration successful! You can now <a href="login.php">login here</a>.';
                } else {
                    $errors[] = 'Database error: ' . $stmt->error;
                }
            }
            $check->close();
            
        } elseif ($userType === 'admin') {
            // Check if admin email exists
            $check = $conn->prepare("SELECT AdminId FROM admins WHERE Email = ?");
            $check->bind_param("s", $email);
            $check->execute();
            if ($check->get_result()->num_rows > 0) {
                $errors[] = 'An account with this email already exists.';
            } else {
                // Insert Admin (Default Role: Staff)
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $default_role = 'staff';
                $stmt = $conn->prepare("INSERT INTO admins (Name, Email, Password, Role, IsActive) VALUES (?, ?, ?, ?, 1)");
                $stmt->bind_param("ssss", $name, $email, $hashed_password, $default_role);
                
                if ($stmt->execute()) {
                    $success = 'Staff registration successful! You can now <a href="login.php">login here</a>.';
                } else {
                    $errors[] = 'Database error: ' . $stmt->error;
                }
            }
            $check->close();
        } else {
            $errors[] = 'Invalid user type selected.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - IMHRC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', sans-serif;
        }
        .register-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }
        .register-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 100%;
            overflow: hidden;
        }
        .register-header {
            background: #fff;
            padding: 25px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }
        .register-header h3 {
            margin: 0;
            color: #333;
            font-weight: 600;
        }
        .register-body {
            padding: 30px;
        }
        .form-floating { margin-bottom: 15px; }
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
            font-size: 1rem;
            transition: 0.3s;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .login-link { text-align: center; margin-top: 15px; }
        .login-link a { color: #764ba2; text-decoration: none; font-weight: 600; }
        .login-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="register-container">
    <div class="register-card">
        <div class="register-header">
            <h3>Create an Account</h3>
            <p class="text-muted small mb-0">Join IMHRC Portal</p>
        </div>
        <div class="register-body">
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?php foreach ($errors as $error) echo "<div>• " . htmlspecialchars($error) . "</div>"; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <!-- User Type Selection -->
                <div class="form-floating">
                    <select class="form-select" id="user_type" name="user_type">
                        <option value="student" <?php echo (($_POST['user_type'] ?? '') === 'student') ? 'selected' : ''; ?>>Student</option>
                        <option value="admin" <?php echo (($_POST['user_type'] ?? '') === 'admin') ? 'selected' : ''; ?>>Staff / Admin</option>
                    </select>
                    <label for="user_type">I am registering as</label>
                </div>

                <!-- Full Name -->
                <div class="form-floating">
                    <input type="text" class="form-control" id="name" name="name" placeholder="John Doe" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>
                    <label for="name">Full Name</label>
                </div>

                <!-- Email -->
                <div class="form-floating">
                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                    <label for="email">Email Address</label>
                </div>

                <!-- Password -->
                <div class="form-floating">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password">Password</label>
                </div>

                <!-- Confirm Password -->
                <div class="form-floating">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                    <label for="confirm_password">Confirm Password</label>
                </div>

                <button type="submit" class="btn btn-primary btn-register w-100">Register Now</button>
            </form>

            <div class="login-link">
                Already have an account? <a href="login.php">Sign In</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>