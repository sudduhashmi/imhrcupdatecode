<?php
// --- DEBUGGING: Enable Error Reporting to identify 500 Errors ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

$pageTitle = 'Manage Articles - ' . APP_NAME;
requireLogin();

// --- 1. HANDLE DELETE ACTION ---
if (isset($_GET['del'])) {
    $id = (int)$_GET['del'];
    
    // Get image path to delete file from server
    $imgQ = $conn->query("SELECT CoverImage FROM articles WHERE ArticleId = $id");
    if ($imgQ && $img = $imgQ->fetch_assoc()) {
        if (!empty($img['CoverImage'])) {
            $filePath = '../' . $img['CoverImage']; // Path relative to admin folder
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }
    
    $stmt = $conn->prepare("DELETE FROM articles WHERE ArticleId = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: articles-manage.php?msg=deleted");
        exit;
    } else {
        $error = "Error deleting article.";
    }
}

// --- 2. HANDLE STATUS TOGGLE (Optional Quick Action) ---
if (isset($_GET['status']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $status = $_GET['status']; // 'active' or 'inactive'
    
    $stmt = $conn->prepare("UPDATE articles SET Status = ? WHERE ArticleId = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    
    header("Location: articles-manage.php?msg=updated");
    exit;
}

// --- 3. SEARCH & PAGINATION CONFIG ---
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

$whereSQL = "1=1";
$params = [];
$types = "";

if ($search) {
    $whereSQL .= " AND (Title LIKE ? OR Category LIKE ? OR Author LIKE ?)";
    $searchTerm = "%$search%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= "sss";
}

// Get Total Count for Pagination
$countSql = "SELECT COUNT(*) as total FROM articles WHERE $whereSQL";
$stmt = $conn->prepare($countSql);
if ($search) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
// Use bind_result for compatibility
$stmt->bind_result($totalRecords);
$stmt->fetch();
$stmt->close();

$totalPages = ceil($totalRecords / $perPage);

// Fetch Records
$sql = "SELECT * FROM articles WHERE $whereSQL ORDER BY CreatedAt DESC LIMIT ?, ?";
if ($search) {
    $params[] = $offset;
    $params[] = $perPage;
    $types .= "ii";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
} else {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $offset, $perPage);
}

$stmt->execute();

// --- ROBUST DATA FETCHING (Fix for 500 Error on some servers) ---
$articlesList = [];
if (method_exists($stmt, 'get_result')) {
    // If mysqlnd driver is present
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $articlesList[] = $row;
    }
} else {
    // Fallback if get_result() is missing
    $stmt->store_result();
    $meta = $stmt->result_metadata();
    $bindVars = [];
    $row = [];
    while ($field = $meta->fetch_field()) {
        $bindVars[] = &$row[$field->name];
    }
    call_user_func_array([$stmt, 'bind_result'], $bindVars);
    while ($stmt->fetch()) {
        $data = [];
        foreach ($row as $k => $v) {
            $data[$k] = $v;
        }
        $articlesList[] = $data;
    }
}
$stmt->close();
?>
<?php require_once 'includes/head.php'; ?>

<body>
<?php require_once 'includes/app-header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<div class="mobile-menu-overlay"></div>

<div class="main-container">
    <div class="xs-pd-20-10 pd-ltr-20">
        
        <!-- Alerts -->
        <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 border-start border-success border-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> 
            <?php 
                if($_GET['msg'] == 'deleted') echo "Article deleted successfully.";
                if($_GET['msg'] == 'updated') echo "Article status updated.";
                if($_GET['msg'] == 'added') echo "New article added successfully.";
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Page Header -->
        <div class="page-header mb-4 mt-3 bg-white p-4 rounded-3 shadow-sm d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="page-title mb-1 text-primary fw-bold">
                    <i class="bi bi-newspaper me-2"></i> Manage Articles
                </h4>
                <p class="text-muted small mb-0">Create and manage blog posts, news, and updates.</p>
            </div>
            <div>
                <!-- Link to the add page -->
                <a href="article-add.php" class="btn btn-primary shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> Add Article
                </a>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="card shadow-sm border-0 mb-4 rounded-3">
            <div class="card-body">
                <form method="GET" class="row g-2 align-items-center">
                    <div class="col-md-4 col-12">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 bg-light" placeholder="Search by title, author..." value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-dark">Search</button>
                        <?php if($search): ?>
                            <a href="articles-manage.php" class="btn btn-outline-secondary">Clear</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Articles Table -->
        <div class="card shadow border-0 rounded-3 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th class="ps-4">#</th>
                                <th>Cover</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Author</th>
                                <th>Status</th>
                                <th>Featured</th>
                                <th>Date</th>
                                <th class="text-center pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($articlesList) > 0): ?>
                            <?php $sn = $offset + 1; foreach($articlesList as $row): ?>
                            <tr>
                                <td class="ps-4 text-muted"><?php echo $sn++; ?></td>
                                <td>
                                    <?php if(!empty($row['CoverImage'])): ?>
                                        <img src="../<?php echo htmlspecialchars($row['CoverImage']); ?>" 
                                             alt="Cover" 
                                             class="rounded border" 
                                             style="width: 50px; height: 35px; object-fit: cover;"
                                             onerror="this.src='../assets/img/default-blog.jpg'">
                                    <?php else: ?>
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted small" style="width: 50px; height: 35px;">No Img</div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong class="text-dark d-block text-truncate" style="max-width: 250px;">
                                        <?php echo htmlspecialchars($row['Title']); ?>
                                    </strong>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($row['Category'] ?? ''); ?></span></td>
                                <td><?php echo htmlspecialchars($row['Author'] ?? ''); ?></td>
                                <td>
                                    <?php if($row['Status'] == 'active'): ?>
                                        <a href="?status=inactive&id=<?php echo $row['ArticleId']; ?>" class="badge bg-success-subtle text-success text-decoration-none border border-success-subtle" title="Click to Deactivate">Active</a>
                                    <?php else: ?>
                                        <a href="?status=active&id=<?php echo $row['ArticleId']; ?>" class="badge bg-secondary-subtle text-secondary text-decoration-none border border-secondary-subtle" title="Click to Activate">Inactive</a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($row['IsFeatured']): ?>
                                        <i class="bi bi-star-fill text-warning" title="Featured"></i>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><small class="text-muted"><?php echo date('d M Y', strtotime($row['CreatedAt'])); ?></small></td>
                                <td class="text-center pe-4">
                                    <div class="btn-group shadow-sm">
                                        <!-- Edit Link -->
                                        <a href="article-edit.php?id=<?php echo $row['ArticleId']; ?>" class="btn btn-sm btn-light border text-primary" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <!-- Delete Link -->
                                        <a href="articles-manage.php?del=<?php echo $row['ArticleId']; ?>" 
                                           class="btn btn-sm btn-light border text-danger" 
                                           onclick="return confirm('Are you sure you want to delete this article? This action cannot be undone.');" 
                                           title="Delete">
                                            <i class="bi bi-trash3"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="bi bi-journal-x fs-1 d-block mb-3 opacity-50"></i>
                                    No articles found. <a href="article-add.php">Create your first article</a>.
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Pagination -->
            <?php if($totalPages > 1): ?>
            <div class="card-footer bg-white py-3">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center mb-0">
                        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page-1; ?>&search=<?php echo urlencode($search); ?>">Previous</a>
                        </li>
                        
                        <?php for($i=1; $i<=$totalPages; $i++): ?>
                        <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                        </li>
                        <?php endfor; ?>
                        
                        <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page+1; ?>&search=<?php echo urlencode($search); ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
            
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
</body>
</html>