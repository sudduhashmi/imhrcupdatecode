<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/cms.php';

requireLogin();
cmsEnsurePagesTable();

$pageId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$pageData = getRow("SELECT * FROM pages WHERE PageId = $pageId");

if (!$pageData) die('<div class="alert alert-danger">Page not found!</div>');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title'] ?? '');
    $slug = sanitize($_POST['slug'] ?? '');
    $content = $_POST['content'] ?? '';
    $metaDesc = sanitize($_POST['meta_description'] ?? '');
    $metaKeywords = sanitize($_POST['meta_keywords'] ?? '');
    $status = sanitize($_POST['status'] ?? 'draft');

    if (empty($title) || empty($slug)) {
        $error = 'Title and Slug are required!';
    } else {
        // Check slug uniqueness for other pages
        $exists = getRow("SELECT PageId FROM pages WHERE Slug = '$slug' AND PageId <> $pageId LIMIT 1");
        if ($exists) {
            $error = 'Slug already in use by another page.';
        } else {
            $sql = "UPDATE pages SET Title='$title', Slug='$slug', Content='$content', MetaDescription='$metaDesc', MetaKeywords='$metaKeywords', Status='$status', UpdatedBy='{$_SESSION['admin_id']}' WHERE PageId=$pageId";
        }
        
        if (empty($error) && execute($sql)) {
            logActivity('Update Page', "Updated page: $title");
            $success = 'Page updated successfully!';
            $pageData = getRow("SELECT * FROM pages WHERE PageId = $pageId");
        } else {
            $error = 'Failed to update page!';
        }
    }
}

$pageTitle = 'Edit Page - ' . APP_NAME;
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
                            <h1 class="page-title mb-0"><i class="fas fa-edit"></i> Edit Page</h1>
                        </div>
                        <div class="col-md-4 text-right">
                            <a href="<?php echo BASE_URL; ?>pages-list.php" class="btn btn-secondary btn-sm">Back</a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-9">
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <?php if ($error): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                                <?php endif; ?>
                                <?php if ($success): ?>
                                <div class="alert alert-success"><?php echo $success; ?></div>
                                <?php endif; ?>

                                <form method="POST">
                                    <div class="form-group">
                                        <label>Page Title *</label>
                                        <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($pageData['Title']); ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Slug *</label>
                                        <input type="text" name="slug" class="form-control" value="<?php echo htmlspecialchars($pageData['Slug']); ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Content</label>
                                        <textarea name="content" class="form-control" rows="10" id="editor"><?php echo htmlspecialchars($pageData['Content']); ?></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label>Meta Description</label>
                                        <input type="text" name="meta_description" class="form-control" value="<?php echo htmlspecialchars($pageData['MetaDescription'] ?? ''); ?>" maxlength="255">
                                    </div>

                                    <div class="form-group">
                                        <label>Meta Keywords</label>
                                        <input type="text" name="meta_keywords" class="form-control" value="<?php echo htmlspecialchars($pageData['MetaKeywords'] ?? ''); ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="draft" <?php echo $pageData['Status'] === 'draft' ? 'selected' : ''; ?>>Draft</option>
                                            <option value="published" <?php echo $pageData['Status'] === 'published' ? 'selected' : ''; ?>>Published</option>
                                            <option value="archived" <?php echo $pageData['Status'] === 'archived' ? 'selected' : ''; ?>>Archived</option>
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Page</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-header">
                                <h6 class="mb-0">Page Info</h6>
                            </div>
                            <div class="card-body small">
                                <p><strong>Created:</strong> <?php echo date('M d, Y', strtotime($pageData['CreatedAt'])); ?></p>
                                <p><strong>Updated:</strong> <?php echo date('M d, Y', strtotime($pageData['UpdatedAt'])); ?></p>
                                <p><strong>Status:</strong> <span class="badge badge-<?php echo $pageData['Status'] === 'published' ? 'success' : 'warning'; ?>"><?php echo ucfirst($pageData['Status']); ?></span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo asset('plugins/bootstrap-wysihtml5-master/bootstrap-wysihtml5.all.min.js'); ?>"></script>
    <script>
        $(document).ready(function() {
            $('#editor').wysihtml5();
        });
    </script>

    <?php require_once 'includes/footer.php'; ?>
</body>
</html>
