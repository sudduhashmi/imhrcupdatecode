<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/cms.php';

requireLogin();
cmsEnsurePagesTable();

$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$status = isset($_GET['status']) ? sanitize($_GET['status']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

$where = "1=1";
if (!empty($search)) $where .= " AND (Title LIKE '%$search%' OR Slug LIKE '%$search%')";
if (!empty($status)) $where .= " AND Status = '$status'";

$countResult = getRow("SELECT COUNT(*) as total FROM pages WHERE $where");
$totalRecords = $countResult['total'] ?? 0;
$totalPages = ceil($totalRecords / $perPage);

$pages = getRows("SELECT * FROM pages WHERE $where ORDER BY CreatedAt DESC LIMIT $offset, $perPage");

$pageTitle = 'Pages Management - ' . APP_NAME;
?>
<?php require_once 'includes/head.php'; ?>

<body>
<?php require_once 'includes/app-header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<div class="mobile-menu-overlay"></div>

<div class="main-container">
    <div class="xs-pd-20-10 pd-ltr-20">
                <div class="page-header mb-4 mt-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="page-title mb-0">
                                <i class="fas fa-file-alt"></i> Pages
                            </h1>
                            <p class="text-muted small mt-1">Manage website pages and content</p>
                        </div>
                        <div class="col-md-4 text-right">
                            <a href="<?php echo BASE_URL; ?>page-add.php" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Add New Page
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <form method="GET" class="form-inline">
                            <input type="text" name="search" class="form-control mr-2" placeholder="Search pages..." value="<?php echo htmlspecialchars($search); ?>">
                            <select name="status" class="form-control mr-2">
                                <option value="">All Status</option>
                                <option value="draft" <?php echo $status === 'draft' ? 'selected' : ''; ?>>Draft</option>
                                <option value="published" <?php echo $status === 'published' ? 'selected' : ''; ?>>Published</option>
                                <option value="archived" <?php echo $status === 'archived' ? 'selected' : ''; ?>>Archived</option>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm">Search</button>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <?php if (!empty($pages)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead class="bg-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Slug</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sn = $offset + 1; foreach ($pages as $p): ?>
                                    <tr>
                                        <td><?php echo $sn++; ?></td>
                                        <td><strong><?php echo htmlspecialchars($p['Title']); ?></strong></td>
                                        <td><code><?php echo htmlspecialchars($p['Slug']); ?></code></td>
                                        <td>
                                            <span class="badge badge-<?php echo $p['Status'] === 'published' ? 'success' : ($p['Status'] === 'draft' ? 'warning' : 'secondary'); ?>">
                                                <?php echo ucfirst($p['Status']); ?>
                                            </span>
                                        </td>
                                        <td><small><?php echo date('M d, Y', strtotime($p['CreatedAt'])); ?></small></td>
                                        <td>
                                            <a href="<?php echo BASE_URL; ?>page-edit.php?id=<?php echo $p['PageId']; ?>" class="btn btn-outline-primary btn-sm">Edit</a>
                                            <a href="<?php echo BASE_URL; ?>page-delete.php?id=<?php echo $p['PageId']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this page?');">Delete</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if ($totalPages > 1): ?>
                        <nav class="mt-3">
                            <ul class="pagination pagination-sm justify-content-center">
                                <?php if ($page > 1): ?>
                                <li class="page-item"><a class="page-link" href="?page=1<?php echo $search ? '&search=' . urlencode($search) : ''; ?>">First</a></li>
                                <li class="page-item"><a class="page-link" href="?page=<?php echo $page - 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">Previous</a></li>
                                <?php endif; ?>

                                <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                                <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>"><?php echo $i; ?></a>
                                </li>
                                <?php endfor; ?>

                                <?php if ($page < $totalPages): ?>
                                <li class="page-item"><a class="page-link" href="?page=<?php echo $page + 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">Next</a></li>
                                <li class="page-item"><a class="page-link" href="?page=<?php echo $totalPages; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">Last</a></li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                        <?php endif; ?>

                        <?php else: ?>
                        <div class="alert alert-info">No pages found</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once 'includes/footer.php'; ?>
</body>
</html>
