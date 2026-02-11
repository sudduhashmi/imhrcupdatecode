<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';
requireLogin();
$pageTitle = 'Upload Media - ' . APP_NAME;

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$title = trim($_POST['title'] ?? '');
	$mediaType = trim($_POST['media_type'] ?? 'image');
	$status = trim($_POST['status'] ?? 'active');

	if ($title==='') $errors[]='Title is required';
	if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
		$errors[] = 'Valid file is required';
	}

	if (!$errors) {
		$uploadDir = dirname(__DIR__) . '/uploads';
		if (!is_dir($uploadDir)) { @mkdir($uploadDir, 0775, true); }
		$tmp = $_FILES['file']['tmp_name'];
		$name = preg_replace('/[^A-Za-z0-9._-]/', '_', basename($_FILES['file']['name']));
		$target = $uploadDir . '/' . $name;
		if (move_uploaded_file($tmp, $target)) {
			$filePath = '/uploads/' . $name;
			$fileSize = intval($_FILES['file']['size']);
			$mimeType = $_FILES['file']['type'] ?? '';
			$uploadedBy = currentUserId();
			$stmt = db()->prepare("INSERT INTO media (Title, FilePath, MediaType, FileSize, MimeType, UploadedBy, Status) VALUES (?, ?, ?, ?, ?, ?, ?)");
			$stmt->bind_param('sssisss', $title, $filePath, $mediaType, $fileSize, $mimeType, $uploadedBy, $status);
			if ($stmt->execute()) { header('Location: media-gallery.php?msg=uploaded'); exit; }
			else { $errors[] = 'Failed to save media record'; }
		} else {
			$errors[] = 'Failed to move uploaded file';
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
		<div class="page-header mb-4 mt-3 d-flex justify-content-between align-items-center">
			<h1 class="page-title mb-0"><i class="ti-cloud-up"></i> Upload Media</h1>
			<a href="media-gallery.php" class="btn btn-secondary">Back to Gallery</a>
		</div>
		<div class="card"><div class="card-body">
			<?php if ($errors): ?>
				<div class="alert alert-danger"><?= implode('<br>', array_map('htmlspecialchars', $errors)) ?></div>
			<?php endif; ?>
			<form method="post" enctype="multipart/form-data">
				<div class="row g-3">
					<div class="col-md-6">
						<label class="form-label">Title</label>
						<input type="text" name="title" class="form-control" required>
					</div>
					<div class="col-md-6">
						<label class="form-label">Type</label>
						<select name="media_type" class="form-select">
							<option value="image">Image</option>
							<option value="video">Video</option>
							<option value="document">Document</option>
						</select>
					</div>
					<div class="col-md-6">
						<label class="form-label">File</label>
						<input type="file" name="file" class="form-control" required>
					</div>
					<div class="col-md-6">
						<label class="form-label">Status</label>
						<select name="status" class="form-select">
							<option value="active">Active</option>
							<option value="archived">Archived</option>
						</select>
					</div>
				</div>
				<div class="mt-3">
					<button class="btn btn-primary"><i class="ti-check me-1"></i> Upload</button>
					<a href="media-gallery.php" class="btn btn-secondary">Cancel</a>
				</div>
			</form>
		</div></div>
	</div>
</div>
<?php require_once 'includes/footer.php'; ?>
