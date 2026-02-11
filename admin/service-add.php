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

$pageTitle = 'Add Service - ' . APP_NAME;
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
        // Insert service
        $sql = "INSERT INTO services 
                (ServiceName, CategoryId, ShortDescription, Description, DetailedDescription, Icon, Status, DisplayOrder, CreatedBy)
                VALUES 
                ('$serviceName', $categoryId, '$shortDescription', '$description', '$detailedDescription', '$icon', '$status', $displayOrder, {$_SESSION['admin_id']})";
        
        $result = execute($sql);
        
        if ($result['success']) {
            $_SESSION['success'] = 'Service added successfully!';
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
                        <i class="fas fa-plus-circle"></i> Add New Service
                    </h1>
                    <p class="text-muted small mt-1 mb-0">Create a new service with description and category</p>
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
                                    placeholder="e.g., Speech Therapy"
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
                                    <option value="<?php echo $cat['CategoryId']; ?>">
                                        <?php echo htmlspecialchars($cat['CategoryName']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-muted">Choose the service category</small>
                            </div>

                            <!-- Short Description -->
                            <div class="form-group mb-3">
                                <label class="form-label font-weight-bold">Short Description</label>
                                <input 
                                    type="text" 
                                    name="shortDescription" 
                                    class="form-control" 
                                    placeholder="Brief one-line description"
                                    maxlength="255"
                                />
                                <small class="text-muted">Maximum 255 characters</small>
                            </div>

                            <!-- Icon -->
                            <div class="form-group mb-3">
                                <label class="form-label font-weight-bold">Icon (FontAwesome Class)</label>
                                <input 
                                    type="text" 
                                    name="icon" 
                                    class="form-control" 
                                    placeholder="e.g., fas fa-microphone"
                                />
                                <small class="text-muted">FontAwesome icon class for display</small>
                            </div>

                            <!-- Display Order -->
                            <div class="form-group mb-3">
                                <label class="form-label font-weight-bold">Display Order</label>
                                <input 
                                    type="number" 
                                    name="displayOrder" 
                                    class="form-control" 
                                    value="0"
                                    min="0"
                                />
                                <small class="text-muted">Lower number = higher priority</small>
                            </div>

                            <!-- Status -->
                            <div class="form-group mb-4">
                                <label class="form-label font-weight-bold">Status</label>
                                <select name="status" class="form-control">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>

                            <!-- Description (WYSIWYG) -->
                            <div class="form-group mb-3">
                                <label class="form-label font-weight-bold">Description</label>
                                <textarea 
                                    name="description" 
                                    class="form-control" 
                                    rows="4"
                                    placeholder="Detailed description of the service"
                                ></textarea>
                            </div>

                            <!-- Detailed Description (WYSIWYG) -->
                            <div class="form-group mb-4">
                                <label class="form-label font-weight-bold">Detailed Description</label>
                                <textarea 
                                    name="detailedDescription" 
                                    class="form-control" 
                                    rows="6"
                                    placeholder="Complete detailed information about the service"
                                ></textarea>
                                <small class="text-muted">Include benefits, eligibility, process, etc.</small>
                            </div>

                            <!-- Buttons -->
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Create Service
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
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Categories</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($categories as $cat): ?>
                        <div class="mb-3 pb-3 border-bottom">
                            <h6 class="font-weight-bold mb-1"><?php echo htmlspecialchars($cat['CategoryName']); ?></h6>
                            <small class="text-muted"><?php echo htmlspecialchars($cat['Description']); ?></small>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-lightbulb"></i> Tips</h5>
                    </div>
                    <div class="card-body">
                        <ul class="small text-muted">
                            <li>Use clear and descriptive names</li>
                            <li>Short description shows in lists</li>
                            <li>Icon should be FontAwesome class</li>
                            <li>Display order affects listing</li>
                            <li>Status: Active/Inactive for visibility</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
</body>
</html>
