<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Require login
requireLogin();

// Check permission
if (!canPerform('manage_services')) {
    $_SESSION['error'] = 'Unauthorized';
    header('Location: ' . BASE_URL . 'services-list.php');
    exit;
}

$serviceId = (int)($_GET['id'] ?? 0);

// Get service
$service = getRow("SELECT * FROM services WHERE ServiceId = $serviceId");

if (!$service) {
    $_SESSION['error'] = 'Service not found';
    header('Location: ' . BASE_URL . 'services-list.php');
    exit;
}

// Confirm parameter
$confirm = $_GET['confirm'] ?? '';

if ($confirm === 'yes') {
    // Delete service
    $sql = "DELETE FROM services WHERE ServiceId = $serviceId";
    $result = execute($sql);
    
    if ($result['success']) {
        $_SESSION['success'] = 'Service deleted successfully!';
    } else {
        $_SESSION['error'] = 'Failed to delete service';
    }
    
    header('Location: ' . BASE_URL . 'services-list.php');
    exit;
}

// Show confirmation
?>
<?php require_once 'includes/head.php'; ?>

<body>
<?php require_once 'includes/app-header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<div class="mobile-menu-overlay"></div>

<div class="main-container">
    <div class="xs-pd-20-10 pd-ltr-20">
        <!-- Confirmation Dialog -->
        <div class="row justify-content-center mt-5">
            <div class="col-12 col-md-6">
                <div class="card shadow-lg border-0 border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Confirm Deletion</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">Are you sure you want to delete this service?</p>
                        
                        <div class="alert alert-info mb-3">
                            <strong><?php echo htmlspecialchars($service['ServiceName']); ?></strong>
                        </div>

                        <p class="text-muted small mb-4">
                            <i class="fas fa-info-circle"></i> This action cannot be undone.
                        </p>

                        <div class="d-flex gap-2">
                            <a href="<?php echo BASE_URL; ?>service-delete.php?id=<?php echo $serviceId; ?>&confirm=yes" 
                               class="btn btn-danger flex-grow-1">
                                <i class="fas fa-check"></i> Yes, Delete
                            </a>
                            <a href="<?php echo BASE_URL; ?>services-list.php" 
                               class="btn btn-secondary flex-grow-1">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
</body>
</html>
