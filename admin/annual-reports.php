<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

$pageTitle = 'Manage Annual Reports - ' . APP_NAME;

// Login Check
requireLogin();

// --- HANDLE FORM SUBMISSION (ADD REPORT) ---
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_report'])) {
    $title = trim($_POST['title']);
    $year = (int)$_POST['year'];
    $description = trim($_POST['description']);
    $status = 'active'; // Default status

    // File Upload Logic
    $filePath = '';
    if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === 0) {
        // Upload to 'docs' folder in root (../docs/)
        $uploadDir = '../docs/';
        if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
        
        $fileExt = strtolower(pathinfo($_FILES['pdf_file']['name'], PATHINFO_EXTENSION));
        $fileName = 'annual-report-' . $year . '-' . time() . '.' . $fileExt;
        $targetFile = $uploadDir . $fileName;
        
        if ($fileExt === 'pdf') {
            if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $targetFile)) {
                $filePath = 'docs/' . $fileName; // Path to store in DB
            } else {
                $message = "Error uploading file.";
                $messageType = "danger";
            }
        } else {
            $message = "Only PDF files are allowed.";
            $messageType = "danger";
        }
    } else {
        $message = "Please select a PDF file.";
        $messageType = "danger";
    }

    if ($filePath && empty($message)) {
        $stmt = $conn->prepare("INSERT INTO annual_reports (Title, Year, Description, FilePath, Status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sisss", $title, $year, $description, $filePath, $status);
        
        if ($stmt->execute()) {
            $message = "Annual Report added successfully!";
            $messageType = "success";
        } else {
            $message = "Database Error: " . $conn->error;
            $messageType = "danger";
        }
    }
}

// --- HANDLE DELETE ---
if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    
    // Get file path to unlink
    $res = $conn->query("SELECT FilePath FROM annual_reports WHERE ReportId = $id");
    if ($row = $res->fetch_assoc()) {
        $fileToDelete = '../' . $row['FilePath'];
        if (file_exists($fileToDelete)) unlink($fileToDelete);
    }

    $conn->query("DELETE FROM annual_reports WHERE ReportId = $id");
    header("Location: annual-reports.php?msg=deleted");
    exit;
}

if (isset($_GET['msg']) && $_GET['msg'] == 'deleted') {
    $message = "Report deleted successfully.";
    $messageType = "warning";
}

// Fetch Reports
$sql = "SELECT * FROM annual_reports ORDER BY Year DESC";
$reports = getRows($sql);
?>
<?php require_once 'includes/head.php'; ?>

<body>
<?php require_once 'includes/app-header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<div class="mobile-menu-overlay"></div>

<div class="main-container">
    <div class="xs-pd-20-10 pd-ltr-20">
        
        <!-- Display Message -->
        <?php if($message): ?>
        <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <!-- Page Header -->
        <div class="page-header mb-4 mt-3 bg-white p-3 rounded shadow-sm">
            <div class="row align-items-center">
                <div class="col-12 col-md-8">
                    <h4 class="page-title mb-0 text-primary">
                        <i class="bi bi-file-earmark-pdf"></i> Annual Reports
                    </h4>
                    <p class="text-muted small mt-1 mb-0">Upload and manage annual performance reports</p>
                </div>
                <div class="col-12 col-md-4 text-end">
                    <button type="button" class="btn btn-primary btn-sm" onclick="openModal()">
                        <i class="bi bi-cloud-upload"></i> Upload New Report
                    </button>
                </div>
            </div>
        </div>

        <!-- Reports Table -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="10%">Year</th>
                                <th width="30%">Title</th>
                                <th width="35%">Description</th>
                                <th width="10%">File</th>
                                <th width="10%" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($reports) > 0): ?>
                                <?php $i = 1; foreach ($reports as $row): ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><span class="badge bg-info text-dark"><?php echo htmlspecialchars($row['Year']); ?></span></td>
                                    <td><strong><?php echo htmlspecialchars($row['Title']); ?></strong></td>
                                    <td><small class="text-muted"><?php echo substr(htmlspecialchars($row['Description']), 0, 80) . '...'; ?></small></td>
                                    <td>
                                        <a href="../<?php echo $row['FilePath']; ?>" target="_blank" class="btn btn-sm btn-outline-secondary" title="View PDF">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a href="annual-reports.php?delete_id=<?php echo $row['ReportId']; ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           onclick="return confirm('Are you sure you want to delete this report? This will also remove the PDF file.');">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-folder-x fs-1 d-block mb-2"></i>
                                        No annual reports found. Upload one to get started.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- UPLOAD MODAL -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Annual Report</h5>
                <button type="button" class="btn-close" onclick="reportModal.hide()" aria-label="Close"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Report Year <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="year" value="<?php echo date('Y'); ?>" required min="2000" max="2099">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Report Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="title" placeholder="e.g. IMHRC Annual Report 2024" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Brief summary of the report..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">PDF File <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="pdf_file" accept="application/pdf" required>
                        <small class="text-muted">Only .pdf files allowed</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="reportModal.hide()">Close</button>
                    <button type="submit" name="save_report" class="btn btn-primary">Upload Report</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var reportModal;
    document.addEventListener("DOMContentLoaded", function() {
        reportModal = new bootstrap.Modal(document.getElementById('reportModal'));
    });
    function openModal() {
        reportModal.show();
    }
</script>

<?php require_once 'includes/footer.php'; ?>
</body>
</html>