<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

requireLogin();

$pageId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$confirm = isset($_POST['confirm']) ? 1 : 0;

$pageData = getRow("SELECT * FROM pages WHERE PageId = $pageId");

if (!$pageData) {
    header('Location: ' . BASE_URL . 'pages-list.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $confirm) {
    execute("DELETE FROM pages WHERE PageId = $pageId");
    logActivity('Delete Page', "Deleted page: {$pageData['Title']}");
    echo '<div class="alert alert-success">Page deleted successfully! Redirecting...</div>';
    echo "<script>setTimeout(() => window.location.href = '" . BASE_URL . "pages-list.php', 2000);</script>";
    exit;
}

$pageTitle = 'Delete Page - ' . APP_NAME;
?>
<?php require_once 'includes/head.php'; ?>

<body>
<?php require_once 'includes/app-header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<div class="mobile-menu-overlay"></div>

<div class="main-container">
    <div class="xs-pd-20-10 pd-ltr-20">
                <div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Are you sure you want to delete this page?</div>

                <div class="card">
                    <div class="card-body">
                        <p><strong>Title:</strong> <?php echo htmlspecialchars($pageData['Title']); ?></p>
                        <p><strong>Slug:</strong> <?php echo htmlspecialchars($pageData['Slug']); ?></p>
                        <p><strong>Status:</strong> <?php echo ucfirst($pageData['Status']); ?></p>

                        <form method="POST">
                            <input type="hidden" name="confirm" value="1">
                            <button type="submit" class="btn btn-danger">Delete</button>
                            <a href="<?php echo BASE_URL; ?>pages-list.php" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once 'includes/footer.php'; ?>
</body>
</html>
