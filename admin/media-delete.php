<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';
requireLogin();

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: media-gallery.php'); exit; }

$errors = [];
$done = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = db()->prepare("UPDATE media SET Status = 'deleted' WHERE MediaId = ?");
    $stmt->bind_param('i', $id);
    $done = $stmt->execute();
    $msg = $done ? 'deleted' : 'failed';
    header('Location: media-gallery.php?msg=' . $msg);
    exit;
}

$stmt = db()->prepare("SELECT MediaId, Title, MediaType, FilePath, Status FROM media WHERE MediaId = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$media = $stmt->get_result()->fetch_assoc();
if (!$media) { header('Location: media-gallery.php'); exit; }
?>
<?php require_once 'includes/head.php'; ?>
<body>
<?php require_once 'includes/app-header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<div class="mobile-menu-overlay"></div>

<div class="main-container">
<div class="xs-pd-20-10 pd-ltr-20">
        <div class="page-title d-flex align-items-center justify-content-between">
            <h4><i class="ti-trash me-2"></i> Delete Media (Soft)</h4>
        </div>
        <div class="card"><div class="card-body">
            <p>Are you sure you want to mark this media as deleted?</p>
            <ul>
                <li><strong>Title:</strong> <?= htmlspecialchars($media['Title']) ?></li>
                <li><strong>Type:</strong> <?= htmlspecialchars($media['MediaType']) ?></li>
                <li><strong>File:</strong> <?= htmlspecialchars($media['FilePath']) ?></li>
                <li><strong>Status:</strong> <?= htmlspecialchars($media['Status']) ?></li>
            </ul>
            <form method="post">
                <button class="btn btn-danger"><i class="ti-trash me-1"></i> Confirm Soft Delete</button>
                <a href="media-gallery.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div></div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>
