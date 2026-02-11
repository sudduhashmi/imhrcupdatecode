<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Require login and admin role
requireLogin();
requireRole('admin');

$adminId = isset($_GET['id']) ? sanitize($_GET['id']) : '';
$error = '';
$success = '';

if (empty($adminId)) {
    header('Location: ' . BASE_URL . 'admins-list.php');
    exit;
}

// Fetch admin data
$adminData = getRow("SELECT * FROM admins WHERE AdminId = '$adminId'");

if (empty($adminData)) {
    die('<div class="alert alert-danger">Admin not found!</div>');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? sanitize($_POST['name']) : '';
    $email = isset($_POST['email']) ? sanitize($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $role = isset($_POST['role']) ? sanitize($_POST['role']) : '';
    $phone = isset($_POST['phone']) ? sanitize($_POST['phone']) : '';
    $isActive = isset($_POST['is_active']) ? 1 : 0;

    // Validation
    $errors = [];
    
    if (empty($name)) $errors[] = 'Name is required';
    if (empty($email)) $errors[] = 'Email is required';
    if (!in_array($role, ['admin', 'manager', 'staff'])) $errors[] = 'Invalid role selected';
    
    // Check if email already exists (excluding current admin)
    $existingEmail = getRow("SELECT AdminId FROM admins WHERE Email = '$email' AND AdminId != '$adminId'");
    if (!empty($existingEmail)) {
        $errors[] = 'Email already exists in the system';
    }

    if (!empty($errors)) {
        $error = implode('<br>', $errors);
    } else {
        // Prepare update query
        $updates = "Name = '$name', Email = '$email', Role = '$role', Phone = '$phone', IsActive = $isActive, UpdatedAt = NOW()";
        
        // Add password if provided
        if (!empty($password)) {
            if (strlen($password) < 6) {
                $error = 'Password must be at least 6 characters';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $updates .= ", Password = '$hashedPassword'";
            }
        }

        if (empty($error)) {
            $sql = "UPDATE admins SET $updates WHERE AdminId = '$adminId'";
            
            if (execute($sql)) {
                logActivity('Update Admin', "Updated admin: $name ($email)");
                $success = 'Admin user updated successfully!';
                
                // Refresh admin data
                $adminData = getRow("SELECT * FROM admins WHERE AdminId = '$adminId'");
                
                // Redirect after 2 seconds
                echo "<script>
                    setTimeout(function() {
                        window.location.href = '" . BASE_URL . "admins-list.php';
                    }, 2000);
                </script>";
            } else {
                $error = 'Failed to update admin user. Please try again.';
            }
        }
    }
}

$pageTitle = 'Edit Admin User - ' . APP_NAME;
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
                                <i class="fas fa-user-edit"></i> Edit Admin User
                            </h1>
                            <p class="text-muted small mt-1">Update administrator account details</p>
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
                                            value="<?php echo htmlspecialchars($adminData['Name']); ?>"
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
                                            value="<?php echo htmlspecialchars($adminData['Email']); ?>"
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
                                            value="<?php echo htmlspecialchars($adminData['Phone'] ?? ''); ?>"
                                        >
                                    </div>

                                    <div class="form-group">
                                        <label for="role">Role <span class="text-danger">*</span></label>
                                        <select id="role" name="role" class="form-control" required>
                                            <option value="admin" <?php echo $adminData['Role'] === 'admin' ? 'selected' : ''; ?>>
                                                Admin (Full Access)
                                            </option>
                                            <option value="manager" <?php echo $adminData['Role'] === 'manager' ? 'selected' : ''; ?>>
                                                Manager (Limited Access)
                                            </option>
                                            <option value="staff" <?php echo $adminData['Role'] === 'staff' ? 'selected' : ''; ?>>
                                                Staff (View Only)
                                            </option>
                                        </select>
                                    </div>

                                    <!-- Password (Optional) -->
                                    <div class="alert alert-info">
                                        <strong>Password:</strong> Leave blank to keep current password
                                    </div>

                                    <div class="form-group">
                                        <label for="password">New Password (Optional)</label>
                                        <input 
                                            type="password" 
                                            id="password" 
                                            name="password" 
                                            class="form-control"
                                            placeholder="Enter new password (min 6 characters)"
                                        >
                                        <small class="form-text text-muted">Leave blank to keep current password</small>
                                    </div>

                                    <!-- Status -->
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input 
                                                type="checkbox" 
                                                class="custom-control-input" 
                                                id="is_active" 
                                                name="is_active"
                                                <?php echo $adminData['IsActive'] ? 'checked' : ''; ?>
                                            >
                                            <label class="custom-control-label" for="is_active">
                                                Set as Active
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Additional Info -->
                                    <div class="alert alert-secondary">
                                        <strong>Account Information:</strong><br>
                                        Created: <?php echo date('M d, Y H:i', strtotime($adminData['CreatedAt'])); ?><br>
                                        Last Updated: <?php echo $adminData['UpdatedAt'] ? date('M d, Y H:i', strtotime($adminData['UpdatedAt'])) : 'Never'; ?><br>
                                        Last Login: <?php echo $adminData['LastLogin'] ? date('M d, Y H:i', strtotime($adminData['LastLogin'])) : 'Never'; ?>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Update Admin
                                        </button>
                                        <a href="<?php echo BASE_URL; ?>admins-list.php" class="btn btn-secondary ml-2">
                                            <i class="fas fa-times"></i> Cancel
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Info Card -->
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="fas fa-user-circle"></i> Admin Details</h6>
                            </div>
                            <div class="card-body">
                                <p>
                                    <strong>Admin ID:</strong><br>
                                    <code><?php echo htmlspecialchars($adminData['AdminId']); ?></code>
                                </p>

                                <hr>

                                <p>
                                    <strong>Current Role:</strong><br>
                                    <span class="badge badge-<?php echo $adminData['Role'] === 'admin' ? 'danger' : ($adminData['Role'] === 'manager' ? 'warning' : 'success'); ?>">
                                        <?php echo ucfirst($adminData['Role']); ?>
                                    </span>
                                </p>

                                <hr>

                                <p>
                                    <strong>Status:</strong><br>
                                    <?php if ($adminData['IsActive']): ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Inactive</span>
                                    <?php endif; ?>
                                </p>
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

        .text-danger {
            color: #dc3545 !important;
        }

        code {
            background-color: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
        }
    </style>

    <?php require_once 'includes/footer.php'; ?>
</body>
</html>
