<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Require login and admin role
requireLogin();
requireRole('admin');

$searchQuery = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

// Build query
$where = "1=1";
if (!empty($searchQuery)) {
    $where .= " AND (Name LIKE '%$searchQuery%' OR Email LIKE '%$searchQuery%')";
}

// Get total count
$countResult = getRow("SELECT COUNT(*) as total FROM admins WHERE $where");
$totalRecords = $countResult['total'] ?? 0;
$totalPages = ceil($totalRecords / $perPage);

// Get admins
$admins = getRows("SELECT * FROM admins WHERE $where ORDER BY CreatedAt DESC LIMIT $offset, $perPage");

$pageTitle = 'Admin Users - ' . APP_NAME;
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
                                <i class="fas fa-users-cog"></i> Admin Users
                            </h1>
                            <p class="text-muted small mt-1">Manage system administrators and their roles</p>
                        </div>
                        <div class="col-md-4 text-right">
                            <a href="<?php echo BASE_URL; ?>admin-add.php" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Add New Admin
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Search & Filter -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <form method="GET" action="" class="form-inline">
                            <div class="form-group mr-2 flex-grow-1">
                                <input 
                                    type="text" 
                                    name="search" 
                                    class="form-control form-control-sm w-100" 
                                    placeholder="Search by name or email..."
                                    value="<?php echo htmlspecialchars($searchQuery); ?>"
                                >
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-search"></i> Search
                            </button>
                            <?php if (!empty($searchQuery)): ?>
                            <a href="<?php echo BASE_URL; ?>admins-list.php" class="btn btn-secondary btn-sm ml-2">
                                <i class="fas fa-times"></i> Clear
                            </a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>

                <!-- Admin Table -->
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <?php if (!empty($admins)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead class="bg-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Last Login</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $sn = $offset + 1;
                                    foreach ($admins as $admin): 
                                    ?>
                                    <tr>
                                        <td><?php echo $sn++; ?></td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($admin['Name']); ?></strong>
                                        </td>
                                        <td><?php echo htmlspecialchars($admin['Email']); ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo $admin['Role'] === 'admin' ? 'danger' : ($admin['Role'] === 'manager' ? 'warning' : 'success'); ?>">
                                                <?php echo ucfirst($admin['Role']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($admin['Phone'] ?? '-'); ?></td>
                                        <td>
                                            <?php if ($admin['IsActive']): ?>
                                                <span class="badge badge-success">Active</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?php 
                                                echo $admin['LastLogin'] 
                                                    ? date('M d, Y H:i', strtotime($admin['LastLogin'])) 
                                                    : 'Never';
                                                ?>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="<?php echo BASE_URL; ?>admin-edit.php?id=<?php echo urlencode($admin['AdminId']); ?>" 
                                                   class="btn btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="<?php echo BASE_URL; ?>admin-view.php?id=<?php echo urlencode($admin['AdminId']); ?>" 
                                                   class="btn btn-outline-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if ($admin['AdminId'] !== $_SESSION['admin_id']): ?>
                                                <a href="<?php echo BASE_URL; ?>admin-delete.php?id=<?php echo urlencode($admin['AdminId']); ?>" 
                                                   class="btn btn-outline-danger" 
                                                   title="Delete"
                                                   onclick="return confirm('Are you sure you want to delete this admin?');">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <?php if ($totalPages > 1): ?>
                        <nav class="mt-3">
                            <ul class="pagination pagination-sm justify-content-center">
                                <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=1<?php echo !empty($searchQuery) ? '&search=' . urlencode($searchQuery) : ''; ?>">
                                        First
                                    </a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page - 1; ?><?php echo !empty($searchQuery) ? '&search=' . urlencode($searchQuery) : ''; ?>">
                                        Previous
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                                <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?><?php echo !empty($searchQuery) ? '&search=' . urlencode($searchQuery) : ''; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                                <?php endfor; ?>

                                <?php if ($page < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page + 1; ?><?php echo !empty($searchQuery) ? '&search=' . urlencode($searchQuery) : ''; ?>">
                                        Next
                                    </a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $totalPages; ?><?php echo !empty($searchQuery) ? '&search=' . urlencode($searchQuery) : ''; ?>">
                                        Last
                                    </a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                        <?php endif; ?>

                        <div class="alert alert-info mt-3 mb-0" role="alert">
                            <i class="fas fa-info-circle"></i>
                            <strong>Showing</strong> <?php echo $offset + 1; ?> to <?php echo min($offset + $perPage, $totalRecords); ?> 
                            of <?php echo $totalRecords; ?> records
                        </div>

                        <?php else: ?>
                        <div class="alert alert-warning" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> No admins found
                        </div>
                        <?php endif; ?>
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

        .badge {
            padding: 5px 10px;
            font-size: 11px;
            font-weight: 600;
        }

        .btn-group-sm .btn {
            padding: 4px 8px;
            font-size: 12px;
        }

        .table-hover tbody tr:hover {
            background-color: #f5f5f5;
        }
    </style>

    <?php require_once 'includes/footer.php'; ?>
</body>
</html>
