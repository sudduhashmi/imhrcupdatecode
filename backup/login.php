<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMHRC Login</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <style>
        body, html {
            height: 100%;
            margin: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', sans-serif;
        }
        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 420px;
            width: 100%;
        }
        .login-header {
            background: #ffffff;
            padding: 30px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }
        .login-header img {
            height: 120px;
            margin-bottom: 15px;
        }
        .login-header h3 {
            color: #333;
            font-weight: 600;
            margin: 0;
        }
        .login-body {
            padding: 40px;
        }
        .form-floating > label {
            color: #666;
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }
        .forgot-link {
            font-size: 0.9rem;
        }
        @media (max-width: 576px) {
            .login-card {
                margin: 20px;
                border-radius: 10px;
            }
            .login-header {
                padding: 20px;
            }
            .login-body {
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <img src="https://imhrc.org/IMHRC-LOGO.jpg" alt="IMHRC Logo" class="img-fluid">
                <h3>Welcome to IMHRC</h3>
                <p class="text-muted mt-2">Sign in to your account</p>
            </div>
            <div class="login-body">
                <form>
                    <div class="mb-3 form-floating">
                        <input type="email" class="form-control" id="email" placeholder="name@example.com" required>
                        <label for="email">Email address</label>
                    </div>
                    <div class="mb-3 form-floating">
                        <input type="password" class="form-control" id="password" placeholder="Password" required>
                        <label for="password">Password</label>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                        <a href="#" class="text-decoration-none forgot-link">Forgot password?</a>
                    </div>
                    <button type="submit" class="btn btn-primary btn-login w-100">Sign In</button>
                </form>
                <div class="text-center mt-4">
                    <p class="mb-0 text-muted">Don't have an account? <a href="#" class="text-primary fw-bold">Register</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS (optional for better interactions) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>