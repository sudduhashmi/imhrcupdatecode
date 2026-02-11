<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Require login and admin role
requireLogin();
requireRole('admin');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? sanitize($_POST['name']) : '';
    $email = isset($_POST['email']) ? sanitize($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    $role = isset($_POST['role']) ? sanitize($_POST['role']) : '';
    $phone = isset($_POST['phone']) ? sanitize($_POST['phone']) : '';
    $isActive = isset($_POST['is_active']) ? 1 : 0;

    // Validation
    $errors = [];
    
    if (empty($name)) $errors[] = 'Name is required';
    if (empty($email)) $errors[] = 'Email is required';
    if (empty($password)) $errors[] = 'Password is required';
    if (empty($confirmPassword)) $errors[] = 'Please confirm password';
    if ($password !== $confirmPassword) $errors[] = 'Passwords do not match';
    if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters';
    if (!in_array($role, ['admin', 'manager', 'staff'])) $errors[] = 'Invalid role selected';
    
    // Check if email already exists
    $existingEmail = getRow("SELECT AdminId FROM admins WHERE Email = '$email'");
    if (!empty($existingEmail)) {
        $errors[] = 'Email already exists in the system';
    }

    if (!empty($errors)) {
        $error = implode('<br>', $errors);
    } else {
        // Generate AdminId
        $adminId = 'ADMIN' . strtoupper(substr(md5(time()), 0, 8));
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        // Insert admin
        $sql = "INSERT INTO admins (AdminId, Name, Email, Password, Role, Phone, IsActive, CreatedAt) 
                VALUES ('$adminId', '$name', '$email', '$hashedPassword', '$role', '$phone', $isActive, NOW())";
        
        if (execute($sql)) {
            logActivity('Create Admin', "Created new admin: $name ($email) with role: $role");
            $success = 'Admin user created successfully!';
            
            // Redirect after 2 seconds
            echo "<script>
                setTimeout(function() {
                    window.location.href = '" . BASE_URL . "admins-list.php';
                }, 2000);
            </script>";
        } else {
            $error = 'Failed to create admin user. Please try again.';
        }
    }
}

$pageTitle = 'Add Admin User - ' . APP_NAME;
?>
<?php require_once 'includes/head.php'; ?>

<body>
<?php require_once 'includes/app-header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<div class="mobile-menu-overlay"></div>

<div class="main-container">
    <div class="xs-pd-20-10 pd-ltr-20">
        <!-- Main Content -->
                <!-- Page Header -->
                <div class="page-header mb-4 mt-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="page-title mb-0">
                                <i class="fas fa-user-plus"></i> Add New Admin
                            </h1>
                            <p class="text-muted small mt-1">Create a new administrator account</p>
                        </div>
                        <div class="col-md-4 text-right">
                            <a href="<?php echo BASE_URL; ?>admins-list.php" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Form Card -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <?php if (!empty($error)): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Error!</strong> <?php echo $error; ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <?php endif; ?>

                                <?php if (!empty($success)): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Success!</strong> <?php echo $success; ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <?php endif; ?>

                                <form method="POST" action="">
                                    <!-- Basic Information -->
                                    <div class="form-group">
                                        <label for="name">Full Name <span class="text-danger">*</span></label>
                                        <input 
                                            type="text" 
                                            id="name" 
                                            name="name" 
                                            class="form-control"
                                            placeholder="Enter admin full name"
                                            required
                                            value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
                                        >
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email Address <span class="text-danger">*</span></label>
                                        <input 
                                            type="email" 
                                            id="email" 
                                            name="email" 
                                            class="form-control"
                                            placeholder="Enter admin email"
                                            required
                                            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                                        >
                                    </div>

                                    <div class="form-group">
                                        <label for="phone">Phone Number</label>
                                        <input 
                                            type="text" 
                                            id="phone" 
                                            name="phone" 
                                            class="form-control"
                                            placeholder="Enter phone number"
                                            value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
                                        >
                                    </div>

                                    <div class="form-group">
                                        <label for="role">Role <span class="text-danger">*</span></label>
                                        <select id="role" name="role" class="form-control" required>
                                            <option value="">-- Select Role --</option>
                                            <option value="admin" <?php echo isset($_POST['role']) && $_POST['role'] === 'admin' ? 'selected' : ''; ?>>
                                                Admin (Full Access)
                                            </option>
                                            <option value="manager" <?php echo isset($_POST['role']) && $_POST['role'] === 'manager' ? 'selected' : ''; ?>>
                                                Manager (Limited Access)
                                            </option>
                                            <option value="staff" <?php echo isset($_POST['role']) && $_POST['role'] === 'staff' ? 'selected' : ''; ?>>
                                                Staff (View Only)
                                            </option>
                                        </select>
                                    </div>

                                    <!-- Password -->
                                    <div class="form-group">
                                        <label for="password">Password <span class="text-danger">*</span></label>
                                        <input 
                                            type="password" 
                                            id="password" 
                                            name="password" 
                                            class="form-control"
                                            placeholder="Enter password (min 6 characters)"
                                            required
                                        >
                                        <small class="form-text text-muted">Minimum 6 characters required</small>
                                    </div>

                                    <div class="form-group">
                                        <label for="confirm_password">Confirm Password <span class="text-danger">*</span></label>
                                        <input 
                                            type="password" 
                                            id="confirm_password" 
                                            name="confirm_password" 
                                            class="form-control"
                                            placeholder="Re-enter password"
                                            required
                                        >
                                    </div>

                                    <!-- Status -->
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input 
                                                type="checkbox" 
                                                class="custom-control-input" 
                                                id="is_active" 
                                                name="is_active"
                                                checked
                                            >
                                            <label class="custom-control-label" for="is_active">
                                                Set as Active
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Create Admin
                                        </button>
                                        <a href="<?php echo BASE_URL; ?>admins-list.php" class="btn btn-secondary ml-2">
                                            <i class="fas fa-times"></i> Cancel
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Help Card -->
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 bg-light">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="fas fa-info-circle"></i> User Roles</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <h6 class="text-danger">Admin</h6>
                                    <small>Full access to all system features and user management</small>
                                </div>

                                <hr>

                                <div class="mb-3">
                                    <h6 class="text-warning">Manager</h6>
                                    <small>Limited access - can view students, manage admissions, and view reports</small>
                                </div>

                                <hr>

                                <div>
                                    <h6 class="text-success">Staff</h6>
                                    <small>View-only access - can only view dashboard and data</small>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm border-0 mt-3 bg-info text-white">
                            <div class="card-body">
                                <h6 class="mb-2"><i class="fas fa-lock"></i> Security Tips</h6>
                                <ul class="mb-0 small">
                                    <li>Use strong passwords with mixed case and numbers</li>
                                    <li>Don't share admin credentials</li>
                                    <li>Review login history regularly</li>
                                    <li>Deactivate unused accounts</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

    <!-- CSS -->
    <style>
        .page-header {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .custom-checkbox .custom-control-label {
            cursor: pointer;
        }

        .text-danger {
            color: #dc3545 !important;
        }
    </style>

    <?php require_once 'includes/footer.php'; ?>
</body>
</html>
