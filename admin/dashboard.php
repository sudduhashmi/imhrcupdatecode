<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Require login
requireLogin();

$admin = getCurrentAdmin();
$adminRole = $_SESSION['admin_role'] ?? '';

// Get dashboard statistics
$stats = [];

if ($adminRole === 'admin' || $adminRole === 'manager' || $adminRole === 'staff') {
    // Students count
    $studentCount = getRow("SELECT COUNT(*) as count FROM studentlogin WHERE IsActive = 1");
    $stats['students'] = $studentCount['count'] ?? 0;
    
    // Courses count
    $coursesCount = getRow("SELECT COUNT(*) as count FROM courses WHERE status = 'active'");
    $stats['courses'] = $coursesCount['count'] ?? 0;
    
    // Experts count
    $expertsCount = getRow("SELECT COUNT(*) as count FROM experts WHERE status = 'active'");
    $stats['experts'] = $expertsCount['count'] ?? 0;
    
    // Applications count
    $appsCount = getRow("SELECT COUNT(*) as count FROM applications WHERE status != 'rejected'");
    $stats['applications'] = $appsCount['count'] ?? 0;
    
    // Services count
    $servicesCount = getRow("SELECT COUNT(*) as count FROM services WHERE Status = 'active'");
    $stats['services'] = $servicesCount['count'] ?? 0;
}

// Admin-only statistics
if ($adminRole === 'admin') {
    $adminsCount = getRow("SELECT COUNT(*) as count FROM admins WHERE IsActive = 1");
    $stats['admins'] = $adminsCount['count'] ?? 0;
}

$pageTitle = 'Dashboard - ' . APP_NAME;
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
                        <div class="col-12 col-md-8 mb-2 mb-md-0">
                            <h1 class="page-title mb-0">
                                <i class="fas fa-home"></i> Dashboard
                            </h1>
                            <p class="text-muted small mt-1 mb-0">
                                Welcome back, <strong><?php echo htmlspecialchars($admin['Name']); ?></strong>
                            </p>
                        </div>
                        <div class="col-12 col-md-4 text-left text-md-right mt-2 mt-md-0">
                            <span class="badge badge-<?php echo $adminRole === 'admin' ? 'danger' : ($adminRole === 'manager' ? 'warning' : 'success'); ?>">
                                <?php echo ucfirst($adminRole); ?> Role
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Statistics Row -->
                <div class="row g-3">
                    <!-- Students Card -->
                    <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                    <div class="flex-grow-1">
                                        <h6 class="text-muted mb-2">Total Students</h6>
                                        <h2 class="mb-0"><?php echo $stats['students'] ?? 0; ?></h2>
                                    </div>
                                    <div class="icon-circle bg-primary ms-2">
                                        <i class="fas fa-users text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Courses Card -->
                    <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                    <div class="flex-grow-1">
                                        <h6 class="text-muted mb-2">Active Courses</h6>
                                        <h2 class="mb-0"><?php echo $stats['courses'] ?? 0; ?></h2>
                                    </div>
                                    <div class="icon-circle bg-success ms-2">
                                        <i class="fas fa-book text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Experts Card -->
                    <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                    <div class="flex-grow-1">
                                        <h6 class="text-muted mb-2">Clinical Experts</h6>
                                        <h2 class="mb-0"><?php echo $stats['experts'] ?? 0; ?></h2>
                                    </div>
                                    <div class="icon-circle bg-info ms-2">
                                        <i class="fas fa-user-md text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Applications Card -->
                    <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                    <div class="flex-grow-1">
                                        <h6 class="text-muted mb-2">Applications</h6>
                                        <h2 class="mb-0"><?php echo $stats['applications'] ?? 0; ?></h2>
                                    </div>
                                    <div class="icon-circle bg-warning ms-2">
                                        <i class="fas fa-file-alt text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Services Card -->
                    <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                    <div class="flex-grow-1">
                                        <h6 class="text-muted mb-2">Active Services</h6>
                                        <h2 class="mb-0"><?php echo $stats['services'] ?? 0; ?></h2>
                                    </div>
                                    <div class="icon-circle bg-success ms-2">
                                        <i class="fas fa-cogs text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Admins Card (Admin Only) -->
                    <?php if ($adminRole === 'admin'): ?>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                    <div class="flex-grow-1">
                                        <h6 class="text-muted mb-2">Admin Users</h6>
                                        <h2 class="mb-0"><?php echo $stats['admins'] ?? 0; ?></h2>
                                    </div>
                                    <div class="icon-circle bg-danger ms-2">
                                        <i class="fas fa-lock text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Quick Links Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-link"></i> Quick Links</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <?php if (canPerform('manage_students')): ?>
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                        <a href="<?php echo BASE_URL; ?>students-list.php" class="btn btn-outline-primary w-100">
                                            <i class="fas fa-users"></i> <span class="d-none d-sm-inline">View Students</span><span class="d-inline d-sm-none">Students</span>
                                        </a>
                                    </div>
                                    <?php endif; ?>

                                    <?php if (canPerform('manage_courses')): ?>
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                        <a href="<?php echo BASE_URL; ?>courses-list.php" class="btn btn-outline-success w-100">
                                            <i class="fas fa-book"></i> <span class="d-none d-sm-inline">View Courses</span><span class="d-inline d-sm-none">Courses</span>
                                        </a>
                                    </div>
                                    <?php endif; ?>

                                    <?php if (canPerform('manage_experts')): ?>
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                        <a href="<?php echo BASE_URL; ?>experts-list.php" class="btn btn-outline-info w-100">
                                            <i class="fas fa-user-md"></i> <span class="d-none d-sm-inline">View Experts</span><span class="d-inline d-sm-none">Experts</span>
                                        </a>
                                    </div>
                                    <?php endif; ?>

                                    <?php if (canPerform('manage_services')): ?>
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                        <a href="<?php echo BASE_URL; ?>services-list.php" class="btn btn-outline-success w-100">
                                            <i class="fas fa-cogs"></i> <span class="d-none d-sm-inline">Services</span><span class="d-inline d-sm-none">Services</span>
                                        </a>
                                    </div>
                                    <?php endif; ?>

                                    <?php if (canPerform('manage_admins')): ?>
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                        <a href="<?php echo BASE_URL; ?>admins-list.php" class="btn btn-outline-danger w-100">
                                            <i class="fas fa-lock"></i> <span class="d-none d-sm-inline">Manage Admins</span><span class="d-inline d-sm-none">Admins</span>
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-history"></i> Recent Activity</h5>
                            </div>
                            <div class="card-body">
                                <?php
                                $activities = getRows("SELECT * FROM admin_logs ORDER BY CreatedAt DESC LIMIT 10");
                                if (!empty($activities)):
                                ?>
                                <div class="timeline">
                                    <?php foreach ($activities as $activity): ?>
                                    <div class="timeline-item">
                                        <div class="timeline-content">
                                            <p class="mb-0">
                                                <strong class="d-block text-truncate"><?php echo htmlspecialchars($activity['Action']); ?></strong>
                                                <small class="text-muted d-block text-truncate"><?php echo htmlspecialchars($activity['Details']); ?></small>
                                                <small class="text-muted">
                                                    <i class="fas fa-clock"></i> <?php echo date('M d, Y H:i', strtotime($activity['CreatedAt'])); ?>
                                                </small>
                                            </p>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php else: ?>
                                <p class="text-muted mb-0">No recent activity</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- CSS for Dashboard -->
    <style>
        .icon-circle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-shrink: 0;
            min-width: 50px;
        }

        .icon-circle i {
            font-size: 1.75rem;
        }

        .bg-primary {
            background-color: #667eea !important;
        }

        .bg-success {
            background-color: #48bb78 !important;
        }

        .bg-info {
            background-color: #38b6ff !important;
        }

        .bg-warning {
            background-color: #ed8936 !important;
        }

        .bg-danger {
            background-color: #f56565 !important;
        }

        .page-header {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .badge {
            padding: 8px 12px;
            font-size: 12px;
            font-weight: 600;
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
            top: 0;
            width: 12px;
            height: 12px;
            background: #667eea;
            border-radius: 50%;
            border: 2px solid white;
        }

        .timeline-content {
            padding-left: 20px;
        }

        .card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        }

        .h-100 {
            height: 100%;
        }

        .g-3 > div {
            padding: 0.75rem;
        }

        .g-2 > div {
            padding: 0.5rem;
        }

        /* Mobile specific optimizations */
        @media (max-width: 767px) {
            .icon-circle {
                width: 50px;
                height: 50px;
                min-width: 45px;
            }

            .icon-circle i {
                font-size: 1.25rem;
            }

            .page-header {
                padding: 15px;
                margin-bottom: 15px;
            }

            .card-body {
                padding: 12px;
            }

            h2 {
                font-size: 1.5rem;
            }

            h6 {
                font-size: 0.85rem;
            }

            .btn {
                padding: 8px 12px;
                font-size: 13px;
            }

            .btn i {
                margin-right: 4px;
            }
        }

        @media (max-width: 479px) {
            .icon-circle {
                width: 45px;
                height: 45px;
                min-width: 40px;
            }

            .icon-circle i {
                font-size: 1rem;
            }

            .page-header {
                padding: 12px;
                margin-bottom: 12px;
            }

            .page-title {
                font-size: 1.25rem;
            }

            h2 {
                font-size: 1.25rem;
            }

            .btn {
                padding: 6px 10px;
                font-size: 12px;
            }
        }
    </style>

    <?php require_once 'includes/footer.php'; ?>
</body>
</html>
