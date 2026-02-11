<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';
requireLogin();

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $summary = trim($_POST['summary'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $category = trim($_POST['category'] ?? 'General');
    $status = trim($_POST['status'] ?? 'draft');

    if ($title === '') $errors[] = 'Title is required';
    if ($slug === '') $errors[] = 'Slug is required';
    if ($content === '') $errors[] = 'Content is required';

    if (!$errors) {
        $createdBy = $_SESSION['user_id'] ?? null;
        global $conn;
        $stmt = $conn->prepare("INSERT INTO articles (Title, Slug, Content, Summary, Author, Category, Status, CreatedBy) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssssss', $title, $slug, $content, $summary, $author, $category, $status, $createdBy);
        $success = $stmt->execute();
        if ($success) {
            header('Location: articles-manage.php?msg=created');
            exit;
        } else {
            $errors[] = 'Failed to create article';
        }
    }
}
?>
<?php require_once 'includes/head.php'; ?>
<body>
<?php require_once 'includes/app-header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<div class="mobile-menu-overlay"></div>

<div class="main-container">
<div class="xs-pd-20-10 pd-ltr-20">
        <div class="page-title d-flex align-items-center justify-content-between">
            <h4><i class="ti-write me-2"></i> Add Article</h4>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <?php if ($errors): ?>
                            <div class="alert alert-danger"><?= implode('<br>', array_map('htmlspecialchars', $errors)) ?></div>
                        <?php endif; ?>
                        <form method="post">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Title</label>
                                    <input type="text" name="title" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Slug</label>
                                    <input type="text" name="slug" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Author</label>
                                    <input type="text" name="author" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Category</label>
                                    <input type="text" name="category" class="form-control" value="General">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Summary</label>
                                    <textarea name="summary" class="form-control" rows="3"></textarea>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Content</label>
                                    <textarea name="content" class="form-control" rows="8" required></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="draft">Draft</option>
                                        <option value="published">Published</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-3">
                                <button class="btn btn-primary"><i class="ti-check me-1"></i> Save Article</button>
                                <a href="articles-manage.php" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>
