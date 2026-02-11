<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

requireLogin();

$pageTitle = 'Home Page - ' . APP_NAME;
?>
<?php require_once 'includes/head.php'; ?>

<body>
<?php require_once 'includes/app-header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<div class="mobile-menu-overlay"></div>

<div class="main-container">
    <div class="xs-pd-20-10 pd-ltr-20">
                <div class="page-header mb-4 mt-3">
                    <h1 class="page-title mb-0"><i class="fas fa-house"></i> Home Page</h1>
                </div>

                <div class="card">
                    <div class="card-body">
                        <p>View and manage the Home Page content from <a href="<?php echo BASE_URL; ?>pages-list.php">Pages Management</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once 'includes/footer.php'; ?>
</body>
</html>
