<?php
// --- DEBUGGING: Enable Error Reporting to identify 500 Errors ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Check Login
requireLogin();

// Initialize Variables
$articleId = 0;
$isEdit = false;
$title = '';
$author = $_SESSION['admin_name'] ?? 'Admin';
$category = '';
$readTime = '5 min read';
$tags = '';
$content = '';
$excerpt = '';
$isFeatured = 0;
$status = 'active';
$coverImage = '';

$message = '';
$msgType = '';

// --- CHECK IF EDIT MODE ---
if (isset($_GET['id'])) {
    $articleId = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM articles WHERE ArticleId = ?");
    if ($stmt) {
        $stmt->bind_param("i", $articleId);
        $stmt->execute();
        
        $row = null;
        
        // Compatibility check for get_result (mysqlnd driver)
        if (method_exists($stmt, 'get_result')) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
            }
        } else {
            // Fallback for servers without mysqlnd
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $meta = $stmt->result_metadata();
                $bindVars = [];
                $row_data = [];
                
                while ($field = $meta->fetch_field()) {
                    $bindVars[] = &$row_data[$field->name];
                }
                
                call_user_func_array([$stmt, 'bind_result'], $bindVars);
                $stmt->fetch();
                
                // Copy data to $row array
                $row = [];
                foreach ($row_data as $key => $val) {
                    $row[$key] = $val;
                }
            }
        }
        
        if ($row) {
            $isEdit = true;
            
            // Populate Variables
            $title = $row['Title'];
            $author = $row['Author'];
            $category = $row['Category'];
            $readTime = $row['ReadTime'];
            $tags = $row['Tags'];
            $content = $row['Content'];
            $excerpt = $row['Excerpt'];
            $isFeatured = $row['IsFeatured'];
            $status = $row['Status'];
            $coverImage = $row['CoverImage'];
        }
        $stmt->close();
    }
}

// --- HANDLE FORM SUBMISSION ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get Data
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $category = $_POST['category'];
    $readTime = trim($_POST['read_time']);
    $tags = trim($_POST['tags']);
    $content = trim($_POST['content']);
    $excerpt = trim($_POST['excerpt']);
    $status = $_POST['status'];
    $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
    
    // Auto-generate excerpt if empty
    if (empty($excerpt)) {
        $plainText = strip_tags($content);
        $excerpt = substr($plainText, 0, 150) . '...';
    }

    // Image Upload Logic
    $uploadPath = $coverImage; // Default to existing
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === 0) {
        $uploadDir = '../assets/img/blog/';
        if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
        
        $fileName = time() . '_' . basename($_FILES['cover_image']['name']);
        if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $uploadDir . $fileName)) {
            $uploadPath = 'assets/img/blog/' . $fileName;
        }
    }

    if ($isEdit) {
        // UPDATE QUERY
        // Fix: bind_param types string was missing one char. 
        // 11 vars: Title, Author, Category, ReadTime, Tags, Content, Excerpt, CoverImage, IsFeatured(i), Status, ArticleId(i)
        // Types: ssssssssisi -> 11 types for 11 vars.
        $stmt = $conn->prepare("UPDATE articles SET Title=?, Author=?, Category=?, ReadTime=?, Tags=?, Content=?, Excerpt=?, CoverImage=?, IsFeatured=?, Status=? WHERE ArticleId=?");
        if ($stmt) {
            $stmt->bind_param("ssssssssisi", $title, $author, $category, $readTime, $tags, $content, $excerpt, $uploadPath, $isFeatured, $status, $articleId);
            
            if ($stmt->execute()) {
                $message = "Article updated successfully!";
                $msgType = "success";
                $coverImage = $uploadPath; // Update view
            } else {
                $message = "Error updating article: " . $stmt->error;
                $msgType = "danger";
            }
            $stmt->close();
        } else {
            $message = "Prepare failed: " . $conn->error;
            $msgType = "danger";
        }
    } else {
        // INSERT QUERY
        // Fix: bind_param types string.
        // 10 vars: Title, Author, Category, ReadTime, Tags, Content, Excerpt, CoverImage, IsFeatured(i), Status
        // Types: ssssssssis -> 10 types for 10 vars.
        $stmt = $conn->prepare("INSERT INTO articles (Title, Author, Category, ReadTime, Tags, Content, Excerpt, CoverImage, IsFeatured, Status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ssssssssis", $title, $author, $category, $readTime, $tags, $content, $excerpt, $uploadPath, $isFeatured, $status);
            
            if ($stmt->execute()) {
                header("Location: articles-manage.php?msg=added");
                exit;
            } else {
                $message = "Error creating article: " . $stmt->error;
                $msgType = "danger";
            }
            $stmt->close();
        } else {
            $message = "Prepare failed: " . $conn->error;
            $msgType = "danger";
        }
    }
}

$pageTitle = ($isEdit ? 'Edit Article' : 'Add New Article') . ' - ' . APP_NAME;
?>
<?php require_once 'includes/head.php'; ?>

<!-- Summernote CSS for Rich Text Editor -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

<body>
<?php require_once 'includes/app-header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<div class="mobile-menu-overlay"></div>

<div class="main-container">
    <div class="xs-pd-20-10 pd-ltr-20">
        
        <?php if($message): ?>
        <div class="alert alert-<?php echo $msgType; ?> alert-dismissible fade show" role="alert">
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="page-header mb-4 mt-3 bg-white p-4 rounded-3 shadow-sm d-flex justify-content-between align-items-center">
            <div>
                <h4 class="page-title mb-1 text-primary fw-bold">
                    <i class="bi <?php echo $isEdit ? 'bi-pencil-square' : 'bi-plus-circle'; ?>"></i> 
                    <?php echo $isEdit ? 'Edit Article' : 'Add New Article'; ?>
                </h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="articles-manage.php">Articles</a></li>
                        <li class="breadcrumb-item active"><?php echo $isEdit ? htmlspecialchars($title) : 'New'; ?></li>
                    </ol>
                </nav>
            </div>
            <a href="articles-manage.php" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to List
            </a>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <!-- Left Column: Main Content -->
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted small">ARTICLE TITLE</label>
                                <input type="text" name="title" class="form-control form-control-lg" value="<?php echo htmlspecialchars($title); ?>" required placeholder="Enter article title">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted small">CONTENT</label>
                                <textarea name="content" id="summernote" required><?php echo htmlspecialchars($content); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted small">EXCERPT (Short Summary)</label>
                                <textarea name="excerpt" class="form-control" rows="3" placeholder="Brief description for blog list..."><?php echo htmlspecialchars($excerpt); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Meta Details -->
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white fw-bold">Publish Options</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label small text-muted">Status</label>
                                <select name="status" class="form-select">
                                    <option value="active" <?php echo $status == 'active' ? 'selected' : ''; ?>>Active (Published)</option>
                                    <option value="inactive" <?php echo $status == 'inactive' ? 'selected' : ''; ?>>Inactive (Draft)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small text-muted">Category</label>
                                <input type="text" name="category" class="form-control" list="catList" value="<?php echo htmlspecialchars($category); ?>" required placeholder="e.g. Wellness">
                                <datalist id="catList">
                                    <option value="Anxiety & Depression">
                                    <option value="Mindfulness">
                                    <option value="Student Mental Health">
                                    <option value="Research">
                                    <option value="Therapy">
                                </datalist>
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" <?php echo $isFeatured ? 'checked' : ''; ?>>
                                    <label class="form-check-label fw-bold text-warning" for="is_featured">
                                        <i class="bi bi-star-fill"></i> Mark as Featured
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                                <?php echo $isEdit ? 'Update Article' : 'Publish Article'; ?>
                            </button>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white fw-bold">Meta Data</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label small text-muted">Author</label>
                                <input type="text" name="author" class="form-control" value="<?php echo htmlspecialchars($author); ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label small text-muted">Read Time</label>
                                <input type="text" name="read_time" class="form-control" value="<?php echo htmlspecialchars($readTime); ?>" placeholder="e.g. 5 min read">
                            </div>

                            <div class="mb-3">
                                <label class="form-label small text-muted">Tags (Comma separated)</label>
                                <input type="text" name="tags" class="form-control" value="<?php echo htmlspecialchars($tags); ?>" placeholder="health, mind, care">
                            </div>

                            <div class="mb-3">
                                <label class="form-label small text-muted">Cover Image</label>
                                <input type="file" name="cover_image" class="form-control" accept="image/*" onchange="previewImage(this)">
                                <?php if($coverImage): ?>
                                    <div class="mt-2 text-center border rounded p-2 bg-light">
                                        <img id="imgPreview" src="../<?php echo htmlspecialchars($coverImage); ?>" class="img-fluid" style="max-height: 150px;">
                                        <div class="small text-muted mt-1">Current Image</div>
                                    </div>
                                <?php else: ?>
                                    <div id="previewBox" class="mt-2 text-center border rounded p-2 bg-light d-none">
                                        <img id="imgPreview" src="" class="img-fluid" style="max-height: 150px;">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            placeholder: 'Write your article content here...',
            tabsize: 2,
            height: 350,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });

    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var previewBox = document.getElementById('previewBox');
                if(previewBox) previewBox.classList.remove('d-none');
                
                document.getElementById('imgPreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
</body>
</html>