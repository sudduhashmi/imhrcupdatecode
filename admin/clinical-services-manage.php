<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

$pageTitle = 'Manage Clinical Services - ' . APP_NAME;
requireLogin();

// --- HANDLE ACTIONS ---

// 1. Delete Service
if (isset($_GET['del'])) {
    $id = (int)$_GET['del'];
    $conn->query("DELETE FROM clinical_services WHERE ServiceId = $id");
    header("Location: clinical-services-manage.php?msg=deleted");
    exit;
}

// 2. Add or Update Service
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_service'])) {
    $id = $_POST['service_id'] ?? '';
    $title = trim($_POST['title']);
    $category = $_POST['category'];
    $link = trim($_POST['link']);
    $status = $_POST['status'];
    $order = (int)$_POST['display_order'];

    // Icon Upload Logic
    $iconPath = $_POST['existing_icon'] ?? 'assets/icons/default.svg';
    if (isset($_FILES['icon']) && $_FILES['icon']['error'] === 0) {
        $uploadDir = '../assets/icons/';
        if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
        
        $fileName = time() . '_' . basename($_FILES['icon']['name']);
        if (move_uploaded_file($_FILES['icon']['tmp_name'], $uploadDir . $fileName)) {
            $iconPath = 'assets/icons/' . $fileName;
        }
    }

    if ($id) {
        // Update
        $stmt = $conn->prepare("UPDATE clinical_services SET Title=?, Category=?, Icon=?, Link=?, Status=?, DisplayOrder=? WHERE ServiceId=?");
        $stmt->bind_param("sssssii", $title, $category, $iconPath, $link, $status, $order, $id);
        $msg = "updated";
    } else {
        // Insert
        $stmt = $conn->prepare("INSERT INTO clinical_services (Title, Category, Icon, Link, Status, DisplayOrder) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $title, $category, $iconPath, $link, $status, $order);
        $msg = "added";
    }

    if ($stmt->execute()) {
        header("Location: clinical-services-manage.php?msg=$msg");
        exit;
    } else {
        $error = "Database Error: " . $conn->error;
    }
}

// Fetch Services
$services = getRows("SELECT * FROM clinical_services ORDER BY Category, DisplayOrder ASC");
?>
<?php require_once 'includes/head.php'; ?>

<body>
<?php require_once 'includes/app-header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<div class="mobile-menu-overlay"></div>

<div class="main-container">
    <div class="xs-pd-20-10 pd-ltr-20">
        
        <!-- Messages -->
        <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 border-start border-success border-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> Service successfully <?php echo htmlspecialchars($_GET['msg']); ?>.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="page-header mb-4 mt-3 bg-white p-4 rounded-3 shadow-sm d-flex justify-content-between align-items-center">
            <div>
                <h4 class="page-title mb-1 text-primary fw-bold">
                    <i class="bi bi-hospital me-2"></i> Clinical & Diagnostic Services
                </h4>
                <p class="text-muted small mb-0">Manage services displayed on the Clinical Services page.</p>
            </div>
            <div>
                <button class="btn btn-primary shadow-sm" onclick="openModal()">
                    <i class="bi bi-plus-lg me-1"></i> Add Service
                </button>
            </div>
        </div>

        <div class="card shadow border-0 rounded-3 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th class="ps-4">#</th>
                                <th>Icon</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Link</th>
                                <th>Order</th>
                                <th>Status</th>
                                <th class="text-center pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($services) > 0): ?>
                            <?php $i=1; foreach($services as $row): ?>
                            <tr>
                                <td class="ps-4 fw-bold text-muted"><?php echo $i++; ?></td>
                                <td>
                                    <div class="bg-light rounded p-2 d-inline-block border">
                                        <img src="../<?php echo htmlspecialchars($row['Icon']); ?>" width="30" height="30" style="object-fit:contain;" onerror="this.src='../assets/img/default-icon.png'">
                                    </div>
                                </td>
                                <td class="fw-bold text-dark"><?php echo htmlspecialchars($row['Title']); ?></td>
                                <td>
                                    <?php 
                                        $badgeClass = match($row['Category']) {
                                            'Assessment' => 'primary',
                                            'Specialized' => 'purple', 
                                            'Therapeutic' => 'success',
                                            default => 'secondary'
                                        };
                                        // Custom purple class handling since BS5 doesn't have bg-purple by default
                                        $bgClass = ($badgeClass == 'purple') ? 'background-color: #6f42c1; color: white;' : '';
                                        $bsClass = ($badgeClass != 'purple') ? "bg-$badgeClass-subtle text-$badgeClass border border-$badgeClass-subtle" : '';
                                    ?>
                                    <span class="badge <?php echo $bsClass; ?>" style="<?php echo $bgClass; ?>">
                                        <?php echo htmlspecialchars($row['Category']); ?>
                                    </span>
                                </td>
                                <td class="small text-muted"><?php echo htmlspecialchars($row['Link']); ?></td>
                                <td><?php echo $row['DisplayOrder']; ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $row['Status'] == 'active' ? 'success' : 'secondary'; ?>">
                                        <?php echo ucfirst($row['Status']); ?>
                                    </span>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group shadow-sm">
                                        <button class="btn btn-sm btn-light border text-primary" 
                                                onclick='editService(<?php echo json_encode($row); ?>)' title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <a href="clinical-services-manage.php?del=<?php echo $row['ServiceId']; ?>" 
                                           class="btn btn-sm btn-light border text-danger"
                                           title="Delete"
                                           onclick="return confirm('Are you sure you want to delete this service?')">
                                            <i class="bi bi-trash3"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3 text-secondary-subtle"></i>
                                    No services found.
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

<!-- ADD/EDIT MODAL -->
<div class="modal fade" id="serviceModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="modalTitle">Add Service</h5>
                <button type="button" class="btn-close" onclick="serviceModal.hide()"></button>
            </div>
            <div class="modal-body p-4 pt-3">
                <form method="POST" enctype="multipart/form-data" id="serviceForm">
                    <input type="hidden" name="service_id" id="service_id">
                    <input type="hidden" name="existing_icon" id="existing_icon">

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label text-muted small fw-bold">SERVICE TITLE</label>
                            <input type="text" name="title" id="title" class="form-control" required placeholder="e.g. Speech Therapy">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">CATEGORY</label>
                            <select name="category" id="category" class="form-select" required>
                                <option value="Assessment">Assessment Services</option>
                                <option value="Specialized">Specialized Services</option>
                                <option value="Therapeutic">Therapeutic Services</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">DISPLAY ORDER</label>
                            <input type="number" name="display_order" id="display_order" class="form-control" value="0">
                        </div>

                        <div class="col-12">
                            <label class="form-label text-muted small fw-bold">LINK URL</label>
                            <input type="text" name="link" id="link" class="form-control" value="clinical-experts.php" placeholder="Page link">
                        </div>

                        <div class="col-md-8">
                            <label class="form-label text-muted small fw-bold">ICON / IMAGE</label>
                            <input type="file" name="icon" id="icon" class="form-control" accept="image/svg+xml, image/png, image/jpeg">
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label text-muted small fw-bold">STATUS</label>
                            <select name="status" id="status" class="form-select">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        
                        <div class="col-12 text-center d-none" id="icon_preview_box">
                            <img id="icon_preview" src="" style="height: 50px; width: 50px; object-fit: contain;" class="border p-2 rounded">
                            <div class="small text-muted mt-1">Current Icon</div>
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" name="save_service" class="btn btn-primary rounded-pill">Save Service</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var serviceModal;
    document.addEventListener("DOMContentLoaded", function() {
        serviceModal = new bootstrap.Modal(document.getElementById('serviceModal'));
    });

    function openModal() {
        document.getElementById('serviceForm').reset();
        document.getElementById('service_id').value = '';
        document.getElementById('existing_icon').value = '';
        document.getElementById('modalTitle').innerText = 'Add New Service';
        document.getElementById('icon_preview_box').classList.add('d-none');
        document.getElementById('link').value = 'clinical-experts.php'; // Default
        serviceModal.show();
    }

    function editService(data) {
        document.getElementById('modalTitle').innerText = 'Edit Service';
        document.getElementById('service_id').value = data.ServiceId;
        document.getElementById('existing_icon').value = data.Icon;
        document.getElementById('title').value = data.Title;
        document.getElementById('category').value = data.Category;
        document.getElementById('link').value = data.Link;
        document.getElementById('display_order').value = data.DisplayOrder;
        document.getElementById('status').value = data.Status;

        // Show preview
        if(data.Icon) {
            document.getElementById('icon_preview').src = '../' + data.Icon;
            document.getElementById('icon_preview_box').classList.remove('d-none');
        } else {
            document.getElementById('icon_preview_box').classList.add('d-none');
        }

        serviceModal.show();
    }
</script>

<?php require_once 'includes/footer.php'; ?>
</body>
</html>