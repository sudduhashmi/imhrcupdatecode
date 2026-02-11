<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Require login and admin role
requireLogin();
requireRole('admin');

$adminId = isset($_GET['id']) ? sanitize($_GET['id']) : '';
$confirm = isset($_POST['confirm']) ? (int)$_POST['confirm'] : 0;
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

// Prevent deleting self
if ($adminData['AdminId'] === $_SESSION['admin_id']) {
    die('<div class="alert alert-danger">You cannot delete your own account!</div>');
}

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $confirm === 1) {
    // Delete admin_logs first (foreign key constraint)
    execute("DELETE FROM admin_logs WHERE AdminId = '$adminId'");
    
    // Delete admin
    $sql = "DELETE FROM admins WHERE AdminId = '$adminId'";
    
    if (execute($sql)) {
        logActivity('Delete Admin', "Deleted admin: " . $adminData['Name'] . " (" . $adminData['Email'] . ")");
        $success = 'Admin user deleted successfully!';
        
        echo '<div class="alert alert-success">
            <strong>Success!</strong> Admin user deleted successfully. Redirecting...
        </div>';
        
        // Redirect after 2 seconds
        echo "<script>
            setTimeout(function() {
                window.location.href = '" . BASE_URL . "admins-list.php';
            }, 2000);
        </script>";
    } else {
        $error = 'Failed to delete admin user. Please try again.';
    }
}

$pageTitle = 'Delete Admin - ' . APP_NAME;
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
                                <i class="fas fa-trash-alt"></i> Delete Admin User
                            </h1>
                            <p class="text-muted small mt-1">Permanently remove an administrator account</p>
                        </div>
                        <div class="col-md-4 text-right">
                            <a href="<?php echo BASE_URL; ?>admins-list.php" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Confirmation Section -->
                <div class="row">
                    <div class="col-md-8 mx-auto">
                        <!-- Warning Alert -->
                        <div class="alert alert-danger alert-lg border-0" role="alert">
                            <div class="d-flex">
                                <div class="mr-3">
                                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                                </div>
                                <div>
                                    <h4 class="alert-heading">Warning!</h4>
                                    <p class="mb-0">
                                        This action will <strong>permanently delete</strong> the following admin user and 
                                        all their associated activity logs. This action cannot be undone.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Admin Details Card -->
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-danger text-white">
                                <h5 class="mb-0"><i class="fas fa-user"></i> Admin to be Deleted</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p>
                                            <strong>Admin ID:</strong><br>
                                            <code><?php echo htmlspecialchars($adminData['AdminId']); ?></code>
                                        </p>
                                        <p>
                                            <strong>Full Name:</strong><br>
                                            <?php echo htmlspecialchars($adminData['Name']); ?>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p>
                                            <strong>Email:</strong><br>
                                            <?php echo htmlspecialchars($adminData['Email']); ?>
                                        </p>
                                        <p>
                                            <strong>Role:</strong><br>
                                            <span class="badge badge-<?php echo $adminData['Role'] === 'admin' ? 'danger' : ($adminData['Role'] === 'manager' ? 'warning' : 'success'); ?>">
                                                <?php echo ucfirst($adminData['Role']); ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <hr>

                                <p class="text-muted">
                                    <strong>Account Created:</strong> <?php echo date('M d, Y H:i', strtotime($adminData['CreatedAt'])); ?><br>
                                    <strong>Last Login:</strong> <?php echo $adminData['LastLogin'] ? date('M d, Y H:i', strtotime($adminData['LastLogin'])) : 'Never'; ?>
                                </p>
                            </div>
                        </div>

                        <!-- Impact Card -->
                        <div class="card shadow-sm border-0 bg-light mb-4">
                            <div class="card-header bg-warning">
                                <h6 class="mb-0"><i class="fas fa-info-circle"></i> Impact of Deletion</h6>
                            </div>
                            <div class="card-body">
                                <ul class="mb-0">
                                    <li>All login credentials will be permanently removed</li>
                                    <li>Access to the admin panel will be revoked immediately</li>
                                    <li>Activity logs for this admin will be permanently deleted</li>
                                    <li>This action cannot be reversed</li>
                                </ul>
                            </div>
                        </div>

                        <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> <?php echo $error; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php endif; ?>

                        <!-- Confirmation Form -->
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <form method="POST" action="">
                                    <!-- Double Check -->
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input 
                                                type="checkbox" 
                                                class="custom-control-input" 
                                                id="confirmation1"
                                                name="confirmation1"
                                                required
                                            >
                                            <label class="custom-control-label" for="confirmation1">
                                                I understand this will <strong>permanently delete</strong> this admin account
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input 
                                                type="checkbox" 
                                                class="custom-control-input" 
                                                id="confirmation2"
                                                name="confirmation2"
                                                required
                                            >
                                            <label class="custom-control-label" for="confirmation2">
                                                I have verified that this is the correct admin to delete
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input 
                                                type="checkbox" 
                                                class="custom-control-input" 
                                                id="confirmation3"
                                                name="confirmation3"
                                                required
                                            >
                                            <label class="custom-control-label" for="confirmation3">
                                                I am fully responsible for this deletion
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Hidden Confirm Field -->
                                    <input type="hidden" name="confirm" value="1">

                                    <!-- Action Buttons -->
                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash-alt"></i> Delete Admin Permanently
                                        </button>
                                        <a href="<?php echo BASE_URL; ?>admins-list.php" class="btn btn-secondary ml-2">
                                            <i class="fas fa-times"></i> Cancel
                                        </a>
                                    </div>
                                </form>
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

        .alert-lg {
            padding: 25px !important;
        }

        code {
            background-color: #f4f4f4;
            padding: 4px 8px;
            border-radius: 3px;
            font-family: monospace;
        }

        .custom-checkbox .custom-control-label {
            cursor: pointer;
        }

        .bg-warning {
            background-color: #ffc107 !important;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
    </style>

    <?php require_once 'includes/footer.php'; ?>
</body>
</html>
