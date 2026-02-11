<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Require login
requireLogin();

// Log the logout action
$adminName = getAdminName();
logActivity('Logout', "Admin $adminName logged out");

// Perform logout
logoutAdmin();

// Redirect to login page with message
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Logged Out - <?php echo APP_NAME; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="stylesheet" href="<?php echo asset('plugins/bootstrap/bootstrap.min.css'); ?>">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .logout-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            padding: 40px;
            text-align: center;
            max-width: 450px;
            animation: slideUp 0.6s ease;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .logout-icon {
            font-size: 60px;
            color: #48bb78;
            margin-bottom: 20px;
        }
        
        .logout-title {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }
        
        .logout-message {
            color: #666;
            margin-bottom: 30px;
            font-size: 15px;
        }
        
        .logout-countdown {
            font-size: 14px;
            color: #999;
            margin-bottom: 20px;
        }
        
        .btn-login {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            text-decoration: none;
            color: white;
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <div class="logout-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1 class="logout-title">You have been logged out</h1>
        <p class="logout-message">
            Your session has been successfully ended. You have been logged out of the admin panel for security purposes.
        </p>
        <div class="logout-countdown">
            Redirecting to login page in <span id="countdown">5</span> seconds...
        </div>
        <a href="<?php echo BASE_URL; ?>admin-login.php" class="btn-login">
            <i class="fas fa-sign-in-alt"></i> Back to Login
        </a>
    </div>

    <script>
        let countdown = 5;
        const countdownElement = document.getElementById('countdown');
        
        const interval = setInterval(function() {
            countdown--;
            countdownElement.textContent = countdown;
            
            if (countdown === 0) {
                clearInterval(interval);
                window.location.href = '<?php echo BASE_URL; ?>admin-login.php';
            }
        }, 1000);
    </script>
</body>
</html>
