<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

requireLogin();

$type = isset($_GET['type']) ? sanitize($_GET['type']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 20;
$offset = ($page - 1) * $perPage;

$where = "Status = 'active'";
if (!empty($type)) $where .= " AND MediaType = '$type'";

$countResult = getRow("SELECT COUNT(*) as total FROM media WHERE $where");
$totalRecords = $countResult['total'] ?? 0;
$totalPages = ceil($totalRecords / $perPage);

$media = getRows("SELECT * FROM media WHERE $where ORDER BY CreatedAt DESC LIMIT $offset, $perPage");

$pageTitle = 'Media Gallery - ' . APP_NAME;
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
                            <h1 class="page-title mb-0"><i class="fas fa-images"></i> Media Gallery</h1>
                        </div>
                        <div class="col-md-4 text-right">
                            <a href="<?php echo BASE_URL; ?>media-upload.php" class="btn btn-primary btn-sm">
                                <i class="fas fa-cloud-upload-alt"></i> Upload
                            </a>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <a href="<?php echo BASE_URL; ?>media-gallery.php" class="btn btn-sm btn-<?php echo empty($type) ? 'primary' : 'outline-primary'; ?>">All</a>
                    <a href="<?php echo BASE_URL; ?>media-gallery.php?type=image" class="btn btn-sm btn-<?php echo $type === 'image' ? 'primary' : 'outline-primary'; ?>">Images</a>
                    <a href="<?php echo BASE_URL; ?>media-gallery.php?type=video" class="btn btn-sm btn-<?php echo $type === 'video' ? 'primary' : 'outline-primary'; ?>">Videos</a>
                    <a href="<?php echo BASE_URL; ?>media-gallery.php?type=document" class="btn btn-sm btn-<?php echo $type === 'document' ? 'primary' : 'outline-primary'; ?>">Documents</a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <?php if (!empty($media)): ?>
                        <div class="row">
                            <?php foreach ($media as $m): ?>
                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    <div class="card-img-top" style="height: 150px; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                        <?php if ($m['MediaType'] === 'image'): ?>
                                            <img src="<?php echo htmlspecialchars($m['FilePath']); ?>" alt="Media" style="max-height: 100%; max-width: 100%;">
                                        <?php else: ?>
                                            <i class="fas fa-file fa-3x text-muted"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-body p-2">
                                        <p class="small mb-2"><strong><?php echo htmlspecialchars($m['Title'] ?? 'Untitled'); ?></strong></p>
                                        <small class="text-muted">Type: <?php echo ucfirst($m['MediaType']); ?></small><br>
                                        <small class="text-muted">Size: <?php echo round($m['FileSize']/1024, 2); ?> KB</small>
                                        <div class="mt-2 d-flex justify-content-between">
                                            <a href="<?php echo BASE_URL; ?>media-edit.php?id=<?php echo $m['MediaId']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                            <a href="<?php echo BASE_URL; ?>media-delete.php?id=<?php echo $m['MediaId']; ?>" class="btn btn-sm btn-outline-danger">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <div class="alert alert-info">No media found</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once 'includes/footer.php'; ?>
</body>
</html>
