<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

requireLogin();

$pageTitle = 'Clinical Services - ' . APP_NAME;

$message = '';
$messageType = 'info';
$allowedStatus = ['active','inactive'];

// Create / update handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_service'])) {
    $serviceId   = (int)($_POST['service_id'] ?? 0);
    $name        = trim($_POST['ServiceName'] ?? '');
    $description = trim($_POST['Description'] ?? '');
    $image       = trim($_POST['Image'] ?? '');
    $icon        = trim($_POST['Icon'] ?? '');
    $status      = $_POST['Status'] ?? 'active';

    if ($name === '') {
        $message = 'Service name is required.';
        $messageType = 'danger';
    } elseif (!in_array($status, $allowedStatus, true)) {
        $message = 'Invalid status value.';
        $messageType = 'danger';
    } else {
        try {
            if ($serviceId > 0) {
                $stmt = db()->prepare("UPDATE clinical_services SET ServiceName=?, Description=?, Image=?, Icon=?, Status=? WHERE ServiceId=?");
                if ($stmt) {
                    $stmt->bind_param('sssssi', $name, $description, $image, $icon, $status, $serviceId);
                    $stmt->execute();
                    $stmt->close();
                    $message = 'Service updated successfully.';
                    $messageType = 'success';
                } else {
                    $message = 'Failed to prepare update statement.';
                    $messageType = 'danger';
                }
            } else {
                $stmt = db()->prepare("INSERT INTO clinical_services (ServiceName, Description, Image, Icon, Status) VALUES (?,?,?,?,?)");
                if ($stmt) {
                    $stmt->bind_param('sssss', $name, $description, $image, $icon, $status);
                    $stmt->execute();
                    $stmt->close();
                    $message = 'Service added successfully.';
                    $messageType = 'success';
                } else {
                    $message = 'Failed to prepare insert statement.';
                    $messageType = 'danger';
                }
            }
        } catch (Exception $e) {
            $message = 'Error saving service: ' . htmlspecialchars($e->getMessage());
            $messageType = 'danger';
        }
    }
}

// Delete handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_service_id'])) {
    $deleteId = (int)$_POST['delete_service_id'];
    if ($deleteId > 0) {
        try {
            $stmt = db()->prepare("DELETE FROM clinical_services WHERE ServiceId=?");
            if ($stmt) {
                $stmt->bind_param('i', $deleteId);
                $stmt->execute();
                $stmt->close();
                $message = 'Service deleted successfully.';
                $messageType = 'success';
            } else {
                $message = 'Failed to prepare delete statement.';
                $messageType = 'danger';
            }
        } catch (Exception $e) {
            $message = 'Error deleting service: ' . htmlspecialchars($e->getMessage());
            $messageType = 'danger';
        }
    }
}

// Load record for editing
$editService = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    if ($editId > 0) {
        try {
            $editService = getRow("SELECT * FROM clinical_services WHERE ServiceId = {$editId}");
        } catch (Exception $e) {
            $editService = null;
        }
    }
}

// Fetch all services
try {
    $services = getRows("SELECT * FROM clinical_services ORDER BY CreatedAt DESC");
} catch (Exception $e) {
    $services = [];
}
?>
<?php require_once 'includes/head.php'; ?>
<body>
<?php require_once 'includes/app-header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<div class="mobile-menu-overlay"></div>

<div class="main-container">
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="page-header mb-4 mt-3">
            <div>
                <h1 class="page-title mb-0">Clinical Services</h1>
                <p class="text-muted mb-0">Add, edit, and manage the services displayed on the site.</p>
            </div>
        </div>

        <?php if (!empty($message)): ?>
        <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
            <?php echo $message; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-5 col-md-6 mb-20">
                <div class="card-box pd-20">
                    <div class="d-flex justify-content-between align-items-center mb-15">
                        <h5 class="mb-0"><?php echo $editService ? 'Edit Service' : 'Add Service'; ?></h5>
                        <?php if ($editService): ?>
                        <a href="clinical-services.php" class="btn btn-sm btn-outline-secondary">Clear</a>
                        <?php endif; ?>
                    </div>
                    <form method="post">
                        <input type="hidden" name="save_service" value="1" />
                        <input type="hidden" name="service_id" value="<?php echo $editService['ServiceId'] ?? 0; ?>" />
                        <div class="form-group">
                            <label>Service Name<span class="text-danger">*</span></label>
                            <input type="text" name="ServiceName" class="form-control" required value="<?php echo htmlspecialchars($editService['ServiceName'] ?? ''); ?>" />
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="Description" class="form-control" rows="3"><?php echo htmlspecialchars($editService['Description'] ?? ''); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Image URL</label>
                            <input type="text" name="Image" class="form-control" value="<?php echo htmlspecialchars($editService['Image'] ?? ''); ?>" />
                        </div>
                        <div class="form-group">
                            <label>Icon (CSS class)</label>
                            <input type="text" name="Icon" class="form-control" value="<?php echo htmlspecialchars($editService['Icon'] ?? ''); ?>" />
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="Status" class="form-control">
                                <?php foreach ($allowedStatus as $s): ?>
                                    <option value="<?php echo $s; ?>" <?php echo (($editService['Status'] ?? 'active') === $s) ? 'selected' : ''; ?>><?php echo ucfirst($s); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-7 col-md-6 mb-20">
                <div class="card-box pd-20">
                    <div class="d-flex justify-content-between align-items-center mb-15">
                        <h5 class="mb-0">Services List</h5>
                    </div>

                    <?php if (!empty($services)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($services as $service): ?>
                                <tr>
                                    <td class="weight-500">
                                        <?php echo htmlspecialchars($service['ServiceName']); ?>
                                        <?php if (!empty($service['Icon'])): ?>
                                            <span class="ml-1 text-secondary"><i class="<?php echo htmlspecialchars($service['Icon']); ?>"></i></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?php echo $service['Status'] === 'active' ? 'success' : 'secondary'; ?>"><?php echo ucfirst($service['Status']); ?></span>
                                    </td>
                                    <td class="text-right">
                                        <a href="clinical-services.php?edit=<?php echo (int)$service['ServiceId']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form method="post" class="d-inline" onsubmit="return confirm('Delete this service?');">
                                            <input type="hidden" name="delete_service_id" value="<?php echo (int)$service['ServiceId']; ?>" />
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="icon-copy bi bi-inbox" style="font-size: 48px;"></i>
                        <p class="mt-2 mb-0">No services found. Add your first service using the form.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
</body>
</html>
