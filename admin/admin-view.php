<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Require login
requireLogin();

$adminId = isset($_GET['id']) ? sanitize($_GET['id']) : '';

if (empty($adminId)) {
    header('Location: ' . BASE_URL . 'admins-list.php');
    exit;
}

// Fetch admin data
$adminData = getRow("SELECT * FROM admins WHERE AdminId = '$adminId'");

if (empty($adminData)) {
    die('<div class="alert alert-danger">Admin not found!</div>');
}

// Get admin activity log
$activities = getRows("SELECT * FROM admin_logs WHERE AdminId = '$adminId' ORDER BY CreatedAt DESC LIMIT 20");

$pageTitle = 'View Admin - ' . APP_NAME;
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
                                <i class="fas fa-user-circle"></i> View Admin Profile
                            </h1>
                            <p class="text-muted small mt-1">Administrator profile and activity log</p>
                        </div>
                        <div class="col-md-4 text-right">
                            <a href="<?php echo BASE_URL; ?>admin-edit.php?id=<?php echo urlencode($adminData['AdminId']); ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="<?php echo BASE_URL; ?>admins-list.php" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Profile Information -->
                <div class="row">
                    <!-- Main Profile Card -->
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-body text-center">
                                <!-- Avatar -->
                                <div class="avatar-circle mb-3">
                                    <?php if (!empty($adminData['Photo'])): ?>
                                        <img src="<?php echo htmlspecialchars($adminData['Photo']); ?>" alt="<?php echo htmlspecialchars($adminData['Name']); ?>" class="img-fluid rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="avatar-placeholder rounded-circle d-flex align-items-center justify-content-center" style="width: 120px; height: 120px; background: #667eea; color: white; margin: 0 auto;">
                                            <i class="fas fa-user fa-3x"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Name and Email -->
                                <h4 class="mb-1"><?php echo htmlspecialchars($adminData['Name']); ?></h4>
                                <p class="text-muted small mb-3"><?php echo htmlspecialchars($adminData['Email']); ?></p>

                                <!-- Role Badge -->
                                <div class="mb-3">
                                    <span class="badge badge-<?php echo $adminData['Role'] === 'admin' ? 'danger' : ($adminData['Role'] === 'manager' ? 'warning' : 'success'); ?> badge-lg">
                                        <?php echo strtoupper($adminData['Role']); ?>
                                    </span>
                                </div>

                                <!-- Status Badge -->
                                <div class="mb-3">
                                    <?php if ($adminData['IsActive']): ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Inactive</span>
                                    <?php endif; ?>
                                </div>

                                <hr>

                                <!-- Quick Info -->
                                <div class="text-left">
                                    <p class="mb-2">
                                        <strong>Admin ID:</strong><br>
                                        <code style="font-size: 11px;"><?php echo htmlspecialchars($adminData['AdminId']); ?></code>
                                    </p>
                                    <p class="mb-2">
                                        <strong>Phone:</strong><br>
                                        <?php echo !empty($adminData['Phone']) ? htmlspecialchars($adminData['Phone']) : '<span class="text-muted">Not provided</span>'; ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Account Status Card -->
                        <div class="card shadow-sm border-0 mt-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-clock"></i> Account Timeline</h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-3">
                                    <strong>Created:</strong><br>
                                    <small class="text-muted">
                                        <?php echo date('M d, Y H:i', strtotime($adminData['CreatedAt'])); ?>
                                    </small>
                                </p>

                                <p class="mb-3">
                                    <strong>Last Updated:</strong><br>
                                    <small class="text-muted">
                                        <?php echo $adminData['UpdatedAt'] ? date('M d, Y H:i', strtotime($adminData['UpdatedAt'])) : 'Never'; ?>
                                    </small>
                                </p>

                                <p class="mb-0">
                                    <strong>Last Login:</strong><br>
                                    <small class="text-muted">
                                        <?php echo $adminData['LastLogin'] ? date('M d, Y H:i', strtotime($adminData['LastLogin'])) : 'Never logged in'; ?>
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Detailed Information -->
                    <div class="col-md-8">
                        <!-- All Information Card -->
                        <div class="card shadow-sm border-0 mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-info-circle"></i> Complete Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p>
                                            <strong>Full Name</strong><br>
                                            <span><?php echo htmlspecialchars($adminData['Name']); ?></span>
                                        </p>
                                        <p>
                                            <strong>Email Address</strong><br>
                                            <span><?php echo htmlspecialchars($adminData['Email']); ?></span>
                                        </p>
                                        <p>
                                            <strong>Phone Number</strong><br>
                                            <span><?php echo !empty($adminData['Phone']) ? htmlspecialchars($adminData['Phone']) : '<span class="text-muted">Not provided</span>'; ?></span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p>
                                            <strong>Role</strong><br>
                                            <span class="badge badge-<?php echo $adminData['Role'] === 'admin' ? 'danger' : ($adminData['Role'] === 'manager' ? 'warning' : 'success'); ?>">
                                                <?php echo ucfirst($adminData['Role']); ?>
                                            </span>
                                        </p>
                                        <p>
                                            <strong>Status</strong><br>
                                            <span class="badge <?php echo $adminData['IsActive'] ? 'badge-success' : 'badge-secondary'; ?>">
                                                <?php echo $adminData['IsActive'] ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </p>
                                        <p>
                                            <strong>Admin ID</strong><br>
                                            <code style="font-size: 11px;"><?php echo htmlspecialchars($adminData['AdminId']); ?></code>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Activity Log Card -->
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-list"></i> Activity Log (Last 20 Actions)</h6>
                            </div>
                            <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                                <?php if (!empty($activities)): ?>
                                <div class="timeline">
                                    <?php foreach ($activities as $activity): ?>
                                    <div class="timeline-item">
                                        <div class="timeline-content">
                                            <p class="mb-1">
                                                <strong><?php echo htmlspecialchars($activity['Action']); ?></strong>
                                            </p>
                                            <small class="text-muted">
                                                <?php echo htmlspecialchars($activity['Details']); ?>
                                            </small><br>
                                            <small class="text-muted">
                                                <i class="fas fa-clock"></i> 
                                                <?php echo date('M d, Y H:i', strtotime($activity['CreatedAt'])); ?>
                                            </small>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php else: ?>
                                <p class="text-muted text-center py-4">No activity recorded</p>
                                <?php endif; ?>
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

        .avatar-circle {
            position: relative;
        }

        .avatar-placeholder {
            margin: 0 auto;
        }

        .badge-lg {
            padding: 10px 15px;
            font-size: 14px;
        }

        code {
            background-color: #f4f4f4;
            padding: 4px 8px;
            border-radius: 3px;
            font-family: monospace;
            display: inline-block;
        }

        .timeline {
            position: relative;
            padding-left: 20px;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 20px;
            border-left: 2px solid #e0e0e0;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -7px;
            top: 5px;
            width: 12px;
            height: 12px;
            background: #667eea;
            border-radius: 50%;
            border: 2px solid white;
        }

        .timeline-content {
            padding-left: 10px;
        }
    </style>

    <?php require_once 'includes/footer.php'; ?>
</body>
</html>
