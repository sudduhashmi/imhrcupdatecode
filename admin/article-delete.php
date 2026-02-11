<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';
requireLogin();

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: articles-manage.php'); exit; }

$errors = [];
$deleted = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = db()->prepare("DELETE FROM articles WHERE ArticleId = ?");
    $stmt->bind_param('i', $id);
    $deleted = $stmt->execute();
    if ($deleted) {
        header('Location: articles-manage.php?msg=deleted');
        exit;
    } else {
        $errors[] = 'Failed to delete article';
    }
}

$stmt = db()->prepare("SELECT ArticleId, Title, Category, Status FROM articles WHERE ArticleId = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$article = $stmt->get_result()->fetch_assoc();
if (!$article) { header('Location: articles-manage.php'); exit; }
?>
<?php require_once 'includes/head.php'; ?>
<body>
<?php require_once 'includes/app-header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<div class="mobile-menu-overlay"></div>

<div class="main-container">
<div class="xs-pd-20-10 pd-ltr-20">
        <div class="page-title d-flex align-items-center justify-content-between">
            <h4><i class="ti-trash me-2"></i> Delete Article</h4>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <?php if ($errors): ?>
                            <div class="alert alert-danger"><?= implode('<br>', array_map('htmlspecialchars', $errors)) ?></div>
                        <?php endif; ?>
                        <p>Are you sure you want to delete this article?</p>
                        <ul>
                            <li><strong>Title:</strong> <?= htmlspecialchars($article['Title']) ?></li>
                            <li><strong>Category:</strong> <?= htmlspecialchars($article['Category']) ?></li>
                            <li><strong>Status:</strong> <?= htmlspecialchars($article['Status']) ?></li>
                        </ul>
                        <form method="post">
                            <button class="btn btn-danger"><i class="ti-trash me-1"></i> Confirm Delete</button>
                            <a href="articles-manage.php" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>
