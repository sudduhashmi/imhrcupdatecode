<?php
// --- DEBUGGING ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

$pageTitle = 'Manage Academic Programs - ' . APP_NAME;
requireLogin();

// --- HANDLE CLEAR COOKIES ---
if (isset($_GET['action']) && $_GET['action'] == 'clear_cookies') {
    if (isset($_SERVER['HTTP_COOKIE'])) {
        $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
        foreach($cookies as $cookie) {
            $parts = explode('=', $cookie);
            $name = trim($parts[0]);
            setcookie($name, '', time()-1000);
            setcookie($name, '', time()-1000, '/');
        }
    }
    header("Location: academic-programs.php?msg=cookies_cleared");
    exit;
}

// --- 1. HANDLE DELETE ---
if (isset($_GET['del'])) {
    $id = (int)$_GET['del'];
    
    // Icon delete logic
    try {
        $q = $conn->query("SELECT Icon FROM academic_programs WHERE ProgramId = $id");
        if ($q && $row = $q->fetch_assoc()) {
            if (!empty($row['Icon'])) {
                $filePath = '../' . $row['Icon']; 
                if (file_exists($filePath)) unlink($filePath);
            }
        }
        
        $conn->query("DELETE FROM academic_programs WHERE ProgramId = $id");
        // Updated redirect URL
        header("Location: academic-programs.php?msg=deleted");
        exit;
    } catch (Exception $e) {
        $error = "Error deleting: " . $e->getMessage();
    }
}

// --- 2. HANDLE SAVE (ADD / EDIT) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_program'])) {
    $id = $_POST['program_id'] ?? '';
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $link = trim($_POST['link']);
    $color = $_POST['color_class'];
    $order = (int)$_POST['display_order'];
    $status = $_POST['status'];

    // Icon Upload Logic
    $iconPath = $_POST['existing_icon'] ?? '';
    if (isset($_FILES['icon']) && $_FILES['icon']['error'] === 0) {
        $uploadDir = '../assets/icons/';
        // Create directory if not exists
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileName = time() . '_' . basename($_FILES['icon']['name']);
        if (move_uploaded_file($_FILES['icon']['tmp_name'], $uploadDir . $fileName)) {
            $iconPath = 'assets/icons/' . $fileName;
        }
    }

    try {
        if ($id) {
            // Update
            $stmt = $conn->prepare("UPDATE academic_programs SET Title=?, Description=?, Icon=?, Link=?, ColorClass=?, Status=?, DisplayOrder=? WHERE ProgramId=?");
            if ($stmt) {
                $stmt->bind_param("ssssssii", $title, $description, $iconPath, $link, $color, $status, $order, $id);
                if ($stmt->execute()) {
                    $msg = "updated";
                } else {
                    $error = "Execute failed: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $error = "Prepare failed: " . $conn->error;
            }
        } else {
            // Insert
            $stmt = $conn->prepare("INSERT INTO academic_programs (Title, Description, Icon, Link, ColorClass, Status, DisplayOrder) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("ssssssi", $title, $description, $iconPath, $link, $color, $status, $order);
                if ($stmt->execute()) {
                    $msg = "added";
                } else {
                    $error = "Execute failed: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $error = "Prepare failed: " . $conn->error;
            }
        }

        if (isset($msg)) {
            // Updated redirect URL
            header("Location: academic-programs.php?msg=$msg");
            exit;
        }
    } catch (Exception $e) {
        $error = "Database Error: " . $e->getMessage();
    }
}

// Fetch Programs (Direct Query for Robustness)
$programs = [];
try {
    // Check if table exists
    $check = $conn->query("SHOW TABLES LIKE 'academic_programs'");
    
    // IF TABLE DOES NOT EXIST, CREATE IT AUTOMATICALLY
    if($check && $check->num_rows == 0) {
        $createSql = "CREATE TABLE academic_programs (
            ProgramId INT AUTO_INCREMENT PRIMARY KEY,
            Title VARCHAR(255) NOT NULL,
            Description TEXT,
            Icon VARCHAR(255),
            Link VARCHAR(255) DEFAULT '#',
            ColorClass VARCHAR(50) DEFAULT 'blue',
            Status ENUM('active', 'inactive') DEFAULT 'active',
            DisplayOrder INT DEFAULT 0,
            CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        
        if($conn->query($createSql)) {
            // Insert Default Data
             $conn->query("INSERT INTO academic_programs (Title, Description, Icon, Link, ColorClass, DisplayOrder) VALUES 
             ('Executive Education Programs', 'Executive-level programs designed for working professionals to upgrade leadership skills.', 'assets/icons/ceo.png', 'offerings.diploma-programs.php', 'pink', 1),
             ('Undergraduate Programs', 'Undergraduate courses structured to combine academic excellence and holistic development.', 'assets/icons/undergraduate1.png', 'offerings-undergraduate-programs.php', 'teal', 2),
             ('Postgraduate Diploma', 'Professional postgraduate diplomas focusing on advanced skills.', 'assets/icons/undergraduate1.png', 'offerings-postgraduate-diploma.php', 'red', 3),
             ('Postgraduate Programs', 'Postgraduate degree programs that emphasize research.', 'assets/icons/postgraduate.png', 'offerings-postgraduate-programs.php', 'dark', 4),
             ('Doctoral Research (Ph.D.)', 'Doctoral research programs designed for scholars.', 'assets/icons/doctor.png', 'doctoral-research-phd.php', 'green-1', 5)");
             
             // Re-check
             $check = $conn->query("SHOW TABLES LIKE 'academic_programs'");
        }
    }

    if($check && $check->num_rows > 0) {
        $res = $conn->query("SELECT * FROM academic_programs ORDER BY DisplayOrder ASC");
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $programs[] = $row;
            }
        }
    } else {
        $error = "Table 'academic_programs' could not be created automatically. Please check database permissions.";
    }
} catch (Exception $e) {
    $error = "Error fetching data: " . $e->getMessage();
}
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
            <i class="bi bi-check-circle-fill me-2"></i> 
            <?php 
                if($_GET['msg'] == 'deleted') echo "Action completed successfully.";
                elseif($_GET['msg'] == 'cookies_cleared') echo "Cookies cleared successfully.";
                else echo "Action completed successfully.";
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <?php if(isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 border-start border-danger border-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="page-header mb-4 mt-3 bg-white p-4 rounded-3 shadow-sm d-flex justify-content-between align-items-center">
            <div>
                <h4 class="page-title mb-1 text-primary fw-bold">
                    <i class="bi bi-mortarboard me-2"></i> Manage Academic Programs
                </h4>
                <p class="text-muted small mb-0">Add, edit or delete academic programs displayed on the website.</p>
            </div>
            <div>
                <a href="academic-programs.php?action=clear_cookies" class="btn btn-outline-secondary shadow-sm me-2" onclick="return confirm('Are you sure you want to clear all cookies? This might log you out.')">
                    <i class="bi bi-eraser"></i> Clear Cookies
                </a>
                <button class="btn btn-primary shadow-sm" onclick="openModal()">
                    <i class="bi bi-plus-lg me-1"></i> Add Program
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
                                <th>Title & Description</th>
                                <th>Theme Color</th>
                                <th>Link</th>
                                <th>Order</th>
                                <th>Status</th>
                                <th class="text-center pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($programs) > 0): ?>
                            <?php foreach($programs as $row): ?>
                            <tr>
                                <td class="ps-4 fw-bold text-muted"><?php echo $row['DisplayOrder']; ?></td>
                                <td>
                                    <div class="bg-light rounded p-2 d-inline-block border">
                                        <img src="../<?php echo !empty($row['Icon']) ? htmlspecialchars($row['Icon']) : 'assets/icons/default.png'; ?>" 
                                             width="30" height="30" style="object-fit:contain;" 
                                             onerror="this.src='../assets/icons/default-program.png'">
                                    </div>
                                </td>
                                <td>
                                    <strong class="text-dark d-block"><?php echo htmlspecialchars($row['Title']); ?></strong>
                                    <small class="text-muted d-block text-truncate" style="max-width: 250px;">
                                        <?php echo htmlspecialchars($row['Description']); ?>
                                    </small>
                                </td>
                                <td>
                                    <span class="badge text-white text-uppercase" style="background-color: var(--<?php echo $row['ColorClass']; ?>-color, #666);">
                                        <?php echo htmlspecialchars($row['ColorClass']); ?>
                                    </span>
                                </td>
                                <td><small class="text-muted"><?php echo htmlspecialchars($row['Link']); ?></small></td>
                                <td><?php echo $row['DisplayOrder']; ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $row['Status'] == 'active' ? 'success' : 'secondary'; ?>-subtle text-<?php echo $row['Status'] == 'active' ? 'success' : 'secondary'; ?>">
                                        <?php echo ucfirst($row['Status']); ?>
                                    </span>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group shadow-sm">
                                        <button class="btn btn-sm btn-light border text-primary" onclick='editProgram(<?php echo json_encode($row); ?>)' title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <!-- Updated Delete Link -->
                                        <a href="academic-programs.php?del=<?php echo $row['ProgramId']; ?>" class="btn btn-sm btn-light border text-danger" onclick="return confirm('Delete this program?')" title="Delete">
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
                                    No programs found.
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
<div class="modal fade" id="programModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Add Program</h5>
                <button type="button" class="btn-close" onclick="programModal.hide()"></button>
            </div>
            <div class="modal-body p-4 pt-3">
                <form method="POST" enctype="multipart/form-data" id="programForm">
                    <input type="hidden" name="program_id" id="program_id">
                    <input type="hidden" name="existing_icon" id="existing_icon">

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold text-muted">Program Title</label>
                            <input type="text" name="title" id="title" class="form-control" required placeholder="e.g. Undergraduate Programs">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Color Theme</label>
                            <select name="color_class" id="color_class" class="form-select">
                                <option value="blue">Blue</option>
                                <option value="purple">Purple</option>
                                <option value="green">Green</option>
                                <option value="orange">Orange</option>
                                <option value="pink">Pink</option>
                                <option value="teal">Teal</option>
                                <option value="red">Red</option>
                                <option value="dark">Dark</option>
                                <option value="green-1">Green 1</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3" placeholder="Short description..."></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Link URL</label>
                            <input type="text" name="link" id="link" class="form-control" placeholder="e.g. undergraduate.php">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Sort Order</label>
                            <input type="number" name="display_order" id="display_order" class="form-control" value="0">
                        </div>

                        <div class="col-md-8">
                            <label class="form-label small fw-bold text-muted">Icon (SVG/PNG)</label>
                            <input type="file" name="icon" id="icon" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Status</label>
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
                        <button type="submit" name="save_program" class="btn btn-primary rounded-pill">Save Program</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var programModal;
    document.addEventListener("DOMContentLoaded", function() {
        programModal = new bootstrap.Modal(document.getElementById('programModal'));
    });

    function openModal() {
        document.getElementById('programForm').reset();
        document.getElementById('program_id').value = '';
        document.getElementById('existing_icon').value = '';
        document.getElementById('modalTitle').innerText = 'Add Program';
        document.getElementById('icon_preview_box').classList.add('d-none');
        document.getElementById('status').value = 'active';
        programModal.show();
    }

    function editProgram(data) {
        document.getElementById('modalTitle').innerText = 'Edit Program';
        document.getElementById('program_id').value = data.ProgramId;
        document.getElementById('existing_icon').value = data.Icon;
        document.getElementById('title').value = data.Title;
        document.getElementById('description').value = data.Description;
        document.getElementById('link').value = data.Link;
        document.getElementById('color_class').value = data.ColorClass;
        document.getElementById('display_order').value = data.DisplayOrder;
        document.getElementById('status').value = data.Status;

        if(data.Icon) {
            document.getElementById('icon_preview').src = '../' + data.Icon;
            document.getElementById('icon_preview_box').classList.remove('d-none');
        } else {
            document.getElementById('icon_preview_box').classList.add('d-none');
        }
        
        programModal.show();
    }
</script>

<!-- Add Custom CSS Colors for Admin Preview -->
<style>
    :root {
        --purple-color: #6a11cb;
        --blue-color: #1cb5e0;
        --green-color: #11998e;
        --orange-color: #f7971e;
        --pink-color: #ff758c;
        --teal-color: #43cea2;
        --red-color: #ff416c;
        --dark-color: #232526;
        --green-1-color: #4dd50b;
    }
</style>

<?php require_once 'includes/footer.php'; ?>
</body>
</html>