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

$pageTitle = 'Services Management - ' . APP_NAME;
$currentCategory = $_GET['category'] ?? '';
$searchQuery = $_GET['search'] ?? '';

// Get all categories for filter
$categories = getRows("SELECT * FROM service_categories WHERE Status = 'active' ORDER BY DisplayOrder");

// Build query
$where = "1=1";
if ($currentCategory) {
    $where .= " AND s.CategoryId = " . (int)$currentCategory;
}
if ($searchQuery) {
    $searchQuery = sanitize($searchQuery);
    $where .= " AND (s.ServiceName LIKE '%$searchQuery%' OR s.Description LIKE '%$searchQuery%')";
}

// Get services
$services = getRows("
    SELECT s.*, sc.CategoryName 
    FROM services s
    LEFT JOIN service_categories sc ON s.CategoryId = sc.CategoryId
    WHERE $where
    ORDER BY sc.DisplayOrder, s.DisplayOrder, s.ServiceName
");

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
                <div class="col-12 col-md-8 mb-2 mb-md-0">
                    <h1 class="page-title mb-0">
                        <i class="fas fa-cogs"></i> Services Management
                    </h1>
                    <p class="text-muted small mt-1 mb-0">Manage all clinical and therapeutic services</p>
                </div>
                <div class="col-12 col-md-4 text-left text-md-right mt-2 mt-md-0">
                    <a href="<?php echo BASE_URL; ?>service-add.php" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> <span class="d-none d-sm-inline">Add Service</span><span class="d-inline d-sm-none">Add</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form method="get" class="form-inline" id="filterForm">
                    <div class="row w-100 g-2">
                        <div class="col-12 col-md-6">
                            <input 
                                type="text" 
                                class="form-control form-control-sm w-100" 
                                name="search" 
                                placeholder="Search services..." 
                                value="<?php echo htmlspecialchars($searchQuery); ?>"
                            />
                        </div>
                        <div class="col-12 col-md-4">
                            <select name="category" class="form-control form-control-sm w-100">
                                <option value="">All Categories</option>
                                <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['CategoryId']; ?>" <?php echo $currentCategory == $cat['CategoryId'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['CategoryName']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-2">
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="fas fa-search"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Services Table -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Services List (<?php echo count($services); ?>)</h5>
                <button class="btn btn-sm btn-outline-secondary" onclick="location.reload()">
                    <i class="fas fa-refresh"></i> Refresh
                </button>
            </div>
            <div class="card-body">
                <?php if (empty($services)): ?>
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle"></i> No services found. 
                    <a href="<?php echo BASE_URL; ?>service-add.php">Create first service</a>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="25%">Service Name</th>
                                <th width="20%">Category</th>
                                <th width="20%">Status</th>
                                <th width="30%" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; foreach ($services as $service): ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($service['ServiceName']); ?></strong>
                                    <?php if ($service['ShortDescription']): ?>
                                    <br><small class="text-muted text-truncate d-block"><?php echo htmlspecialchars($service['ShortDescription']); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        <?php echo htmlspecialchars($service['CategoryName']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo $service['Status'] === 'active' ? 'success' : 'danger'; ?>">
                                        <?php echo ucfirst($service['Status']); ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="<?php echo BASE_URL; ?>service-edit.php?id=<?php echo $service['ServiceId']; ?>" 
                                       class="btn btn-sm btn-warning btn-icon" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?php echo BASE_URL; ?>service-delete.php?id=<?php echo $service['ServiceId']; ?>" 
                                       class="btn btn-sm btn-danger btn-icon" title="Delete"
                                       onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Category Management Section -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-list"></i> Service Categories</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($categories as $cat): ?>
                            <div class="col-12 col-sm-6 col-md-4 mb-3">
                                <div class="card border-left-primary h-100">
                                    <div class="card-body">
                                        <h6 class="font-weight-bold text-primary mb-2">
                                            <?php echo htmlspecialchars($cat['CategoryName']); ?>
                                        </h6>
                                        <p class="text-muted small mb-0">
                                            <?php 
                                            $count = getRow("SELECT COUNT(*) as cnt FROM services WHERE CategoryId = " . $cat['CategoryId']);
                                            echo $count['cnt'] ?? 0;
                                            ?> services
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .btn-icon {
        padding: 0.35rem 0.5rem;
        font-size: 0.85rem;
    }

    .border-left-primary {
        border-left: 4px solid #667eea;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.05);
    }

    @media (max-width: 767px) {
        .table {
            font-size: 12px;
        }

        .btn-sm {
            padding: 0.3rem 0.6rem;
        }
    }
</style>

<?php require_once 'includes/footer.php'; ?>
</body>
</html>
