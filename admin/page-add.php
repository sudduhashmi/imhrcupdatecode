<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/cms.php';

requireLogin();
cmsEnsurePagesTable();

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
        // Ensure unique slug
        $exists = getRow("SELECT PageId FROM pages WHERE Slug = '$slug' LIMIT 1");
        if ($exists) {
            $error = 'Slug already exists. Choose a different one.';
        } else {
            $sql = "INSERT INTO pages (Title, Slug, Content, MetaDescription, MetaKeywords, Status, CreatedBy) 
                    VALUES ('$title', '$slug', '$content', '$metaDesc', '$metaKeywords', '$status', '{$_SESSION['admin_id']}')";
        }
        
        if (empty($error) && execute($sql)) {
            logActivity('Create Page', "Created page: $title");
            $success = 'Page created successfully!';
            echo "<script>setTimeout(() => window.location.href = '" . BASE_URL . "pages-list.php', 2000);</script>";
        } else {
            $error = 'Failed to create page!';
        }
    }
}

$pageTitle = 'Add New Page - ' . APP_NAME;
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
                            <h1 class="page-title mb-0"><i class="fas fa-plus-circle"></i> Add New Page</h1>
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
                                        <input type="text" name="title" class="form-control" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Slug *</label>
                                        <input type="text" name="slug" class="form-control" required>
                                        <small class="form-text text-muted">URL friendly name (e.g., about-us)</small>
                                    </div>

                                    <div class="form-group">
                                        <label>Content</label>
                                        <textarea name="content" class="form-control" rows="10" id="editor"></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label>Meta Description</label>
                                        <input type="text" name="meta_description" class="form-control" maxlength="255">
                                    </div>

                                    <div class="form-group">
                                        <label>Meta Keywords</label>
                                        <input type="text" name="meta_keywords" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="draft">Draft</option>
                                            <option value="published">Published</option>
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Create Page</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-header">
                                <h6 class="mb-0">Quick Info</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Page Template:</strong> This is a generic content page template.</p>
                                <p><strong>Status:</strong></p>
                                <ul class="small">
                                    <li><code>Draft</code> - Not visible to public</li>
                                    <li><code>Published</code> - Visible to public</li>
                                </ul>
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
