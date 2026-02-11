<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Require login
requireLogin();

// Check permission
if (!canPerform('manage_services')) {
    die(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

$serviceId = (int)($_GET['id'] ?? 0);

// Get service
$service = getRow("SELECT * FROM services WHERE ServiceId = $serviceId");

if (!$service) {
    $_SESSION['error'] = 'Service not found';
    header('Location: ' . BASE_URL . 'services-list.php');
    exit;
}

$pageTitle = 'Edit Service - ' . APP_NAME;
$pageScripts = ['src/js/tinymce.js'];

// Get categories
$categories = getRows("SELECT * FROM service_categories WHERE Status = 'active' ORDER BY DisplayOrder");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serviceName = sanitize($_POST['serviceName'] ?? '');
    $categoryId = (int)($_POST['categoryId'] ?? 0);
    $shortDescription = sanitize($_POST['shortDescription'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $detailedDescription = sanitize($_POST['detailedDescription'] ?? '');
    $icon = sanitize($_POST['icon'] ?? '');
    $status = sanitize($_POST['status'] ?? 'active');
    $displayOrder = (int)($_POST['displayOrder'] ?? 0);

    // Validation
    if (!$serviceName || !$categoryId) {
        $error = 'Service name and category are required';
    } else {
        // Update service
        $sql = "UPDATE services SET 
                ServiceName = '$serviceName',
                CategoryId = $categoryId,
                ShortDescription = '$shortDescription',
                Description = '$description',
                DetailedDescription = '$detailedDescription',
                Icon = '$icon',
                Status = '$status',
                DisplayOrder = $displayOrder,
                UpdatedBy = {$_SESSION['admin_id']},
                UpdatedAt = NOW()
                WHERE ServiceId = $serviceId";
        
        $result = execute($sql);
        
        if ($result['success']) {
            $_SESSION['success'] = 'Service updated successfully!';
            header('Location: ' . BASE_URL . 'services-list.php');
            exit;
        } else {
            $error = $result['message'];
        }
    }
}

?>
<?php require_once 'includes/head.php'; ?>

<body>
<?php require_once 'includes/app-header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<div class="mobile-menu-overlay"></div>

<div class="main-container">
    <div class="xs-pd-20-10 pd-ltr-20">
        <!-- Page Header -->
        <div class="page-header mb-4 mt-3">
            <div class="row align-items-center">
                <div class="col-12 col-md-8">
                    <h1 class="page-title mb-0">
                        <i class="fas fa-edit"></i> Edit Service
                    </h1>
                    <p class="text-muted small mt-1 mb-0">Update service details and information</p>
                </div>
                <div class="col-12 col-md-4 text-left text-md-right mt-2 mt-md-0">
                    <a href="<?php echo BASE_URL; ?>services-list.php" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Error Message -->
        <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-3">
            <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Form -->
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <form method="POST" class="form">
                            
                            <!-- Service Name -->
                            <div class="form-group mb-3">
                                <label class="form-label font-weight-bold">
                                    Service Name <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="serviceName" 
                                    class="form-control" 
                                    value="<?php echo htmlspecialchars($service['ServiceName']); ?>"
                                    required
                                />
                            </div>

                            <!-- Category -->
                            <div class="form-group mb-3">
                                <label class="form-label font-weight-bold">
                                    Category <span class="text-danger">*</span>
                                </label>
                                <select name="categoryId" class="form-control" required>
                                    <option value="">-- Select Category --</option>
                                    <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['CategoryId']; ?>" 
                                        <?php echo $service['CategoryId'] == $cat['CategoryId'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['CategoryName']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Short Description -->
                            <div class="form-group mb-3">
                                <label class="form-label font-weight-bold">Short Description</label>
                                <input 
                                    type="text" 
                                    name="shortDescription" 
                                    class="form-control" 
                                    value="<?php echo htmlspecialchars($service['ShortDescription'] ?? ''); ?>"
                                    maxlength="255"
                                />
                            </div>

                            <!-- Icon -->
                            <div class="form-group mb-3">
                                <label class="form-label font-weight-bold">Icon (FontAwesome Class)</label>
                                <input 
                                    type="text" 
                                    name="icon" 
                                    class="form-control" 
                                    value="<?php echo htmlspecialchars($service['Icon'] ?? ''); ?>"
                                />
                            </div>

                            <!-- Display Order -->
                            <div class="form-group mb-3">
                                <label class="form-label font-weight-bold">Display Order</label>
                                <input 
                                    type="number" 
                                    name="displayOrder" 
                                    class="form-control" 
                                    value="<?php echo $service['DisplayOrder']; ?>"
                                    min="0"
                                />
                            </div>

                            <!-- Status -->
                            <div class="form-group mb-4">
                                <label class="form-label font-weight-bold">Status</label>
                                <select name="status" class="form-control">
                                    <option value="active" <?php echo $service['Status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                                    <option value="inactive" <?php echo $service['Status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>

                            <!-- Description -->
                            <div class="form-group mb-3">
                                <label class="form-label font-weight-bold">Description</label>
                                <textarea 
                                    name="description" 
                                    class="form-control" 
                                    rows="4"
                                ><?php echo htmlspecialchars($service['Description'] ?? ''); ?></textarea>
                            </div>

                            <!-- Detailed Description -->
                            <div class="form-group mb-4">
                                <label class="form-label font-weight-bold">Detailed Description</label>
                                <textarea 
                                    name="detailedDescription" 
                                    class="form-control" 
                                    rows="6"
                                ><?php echo htmlspecialchars($service['DetailedDescription'] ?? ''); ?></textarea>
                            </div>

                            <!-- Buttons -->
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Service
                                </button>
                                <a href="<?php echo BASE_URL; ?>services-list.php" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Info Sidebar -->
            <div class="col-12 col-lg-4">
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-history"></i> Information</h5>
                    </div>
                    <div class="card-body small">
                        <p class="mb-2">
                            <strong>Created:</strong><br>
                            <?php echo date('M d, Y H:i', strtotime($service['CreatedAt'])); ?>
                        </p>
                        <p class="mb-0">
                            <strong>Last Updated:</strong><br>
                            <?php echo date('M d, Y H:i', strtotime($service['UpdatedAt'])); ?>
                        </p>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-lightbulb"></i> Quick Links</h5>
                    </div>
                    <div class="card-body">
                        <a href="<?php echo BASE_URL; ?>service-delete.php?id=<?php echo $serviceId; ?>" 
                           class="btn btn-sm btn-danger w-100"
                           onclick="return confirm('Delete this service?')">
                            <i class="fas fa-trash"></i> Delete Service
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
</body>
</html>
