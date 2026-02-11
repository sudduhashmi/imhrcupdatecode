<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Initialize variables
$pageTitle = 'Manage Experts - ' . APP_NAME;
$pageScripts = [];
$pageStyles = [];

// Login Check
requireLogin();

// --- 1. HANDLE DELETE EXPERT ---
if (isset($_GET['del_expert'])) {
    $id = (int)$_GET['del_expert'];
    
    // First, get the photo path to delete the file
    $q = $conn->query("SELECT Photo FROM experts WHERE ExpertId = $id");
    if ($row = $q->fetch_assoc()) {
        if (!empty($row['Photo'])) {
            $filePath = '../' . $row['Photo']; 
            if (file_exists($filePath)) unlink($filePath);
        }
    }
    
    $conn->query("DELETE FROM experts WHERE ExpertId = $id");
    
    header("Location: clinical-experts.php?msg=expert_deleted");
    exit;
}

// --- 2. HANDLE EXPERTISE CATEGORY ACTIONS ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $catName = trim($_POST['category_name']);
    if (!empty($catName)) {
        $check = $conn->query("SELECT id FROM expertise_categories WHERE name = '$catName'");
        if ($check->num_rows == 0) {
            $stmt = $conn->prepare("INSERT INTO expertise_categories (name) VALUES (?)");
            $stmt->bind_param("s", $catName);
            $stmt->execute();
        }
    }
    header("Location: clinical-experts.php?msg=cat_added");
    exit;
}

if (isset($_GET['delete_cat'])) {
    $catId = (int)$_GET['delete_cat'];
    $conn->query("DELETE FROM expertise_categories WHERE id = $catId");
    header("Location: clinical-experts.php?msg=cat_deleted");
    exit;
}

// Fetch Categories
try {
    $categoriesList = getRows("SELECT * FROM expertise_categories ORDER BY name ASC");
} catch (Exception $e) {
    $categoriesList = []; 
}


// --- 3. HANDLE EXPERT FORM SUBMISSION (ADD & EDIT) ---
$message = '';
$messageType = '';

if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'cat_added') { $message = "Category added successfully!"; $messageType = "success"; }
    if ($_GET['msg'] == 'cat_deleted') { $message = "Category deleted successfully!"; $messageType = "warning"; }
    if ($_GET['msg'] == 'expert_deleted') { $message = "Expert profile deleted successfully!"; $messageType = "warning"; }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_expert'])) {
    // Collect Data
    $expertId = $_POST['expert_id'] ?? '';
    $name = trim($_POST['name']);
    $designation = trim($_POST['designation']);
    $qualification = trim($_POST['qualification']);
    $experience = trim($_POST['experience']);
    $expertise = trim($_POST['expertise']);
    $status = $_POST['status'] ?? 'active';
    
    // New Fields
    $feeOnline = $_POST['fee_online'] ?? 0;
    $feeOffline = $_POST['fee_offline'] ?? 0;
    $languages = trim($_POST['languages']);
    $availDays = trim($_POST['availability_days']);
    $availTime = trim($_POST['availability_time']);
    $about = trim($_POST['about']);
    
    // File Upload Logic
    $photoPath = $_POST['existing_photo'] ?? '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $uploadDir = '../assets/img/experts/';
        if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
        
        $fileName = time() . '_' . basename($_FILES['photo']['name']);
        $targetFile = $uploadDir . $fileName;
        
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'webp'])) {
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
                $photoPath = 'assets/img/experts/' . $fileName; 
            }
        }
    }

    if ($expertId) {
        // Update
        $stmt = $conn->prepare("UPDATE experts SET Name=?, Designation=?, Qualification=?, Experience=?, Expertise=?, Photo=?, Status=?, FeeOnline=?, FeeOffline=?, Languages=?, AvailabilityDays=?, AvailabilityTime=?, About=? WHERE ExpertId=?");
        // s=string, d=double/decimal, i=integer
        $stmt->bind_param("sssssssddssssi", $name, $designation, $qualification, $experience, $expertise, $photoPath, $status, $feeOnline, $feeOffline, $languages, $availDays, $availTime, $about, $expertId);
        if ($stmt->execute()) {
            $message = "Expert updated successfully!";
            $messageType = "success";
        } else {
            $message = "Error: " . $conn->error;
            $messageType = "danger";
        }
    } else {
        // Add
        $stmt = $conn->prepare("INSERT INTO experts (Name, Designation, Qualification, Experience, Expertise, Photo, Status, FeeOnline, FeeOffline, Languages, AvailabilityDays, AvailabilityTime, About) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssddssss", $name, $designation, $qualification, $experience, $expertise, $photoPath, $status, $feeOnline, $feeOffline, $languages, $availDays, $availTime, $about);
        if ($stmt->execute()) {
            $message = "New expert added successfully!";
            $messageType = "success";
        } else {
            $message = "Error: " . $conn->error;
            $messageType = "danger";
        }
    }
}

// --- SEARCH & FILTER LOGIC ---
$search = $_GET['search'] ?? '';
$catFilter = $_GET['cat'] ?? '';

$where = "1=1";

if ($search) {
    $s = $conn->real_escape_string($search);
    $where .= " AND (Name LIKE '%$s%' OR Designation LIKE '%$s%' OR Expertise LIKE '%$s%')";
}

if ($catFilter) {
    $c = $conn->real_escape_string($catFilter);
    $where .= " AND Expertise LIKE '%$c%'";
}

// Fetch Experts
$sql = "SELECT * FROM experts WHERE $where ORDER BY CreatedAt DESC";
$experts = getRows($sql);
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
                <div class="col-12 col-md-6">
                    <h4 class="page-title mb-0 text-primary">
                        <i class="fas fa-user-md"></i> Clinical Experts
                    </h4>
                    <p class="text-muted small mt-1 mb-0">Manage doctors profiles</p>
                </div>
                <div class="col-12 col-md-6 text-end">
                    <button type="button" class="btn btn-outline-primary btn-sm me-2" onclick="openCategoryModal()">
                        <i class="bi bi-tags"></i> Manage Expertise
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="openModal()">
                        <i class="bi bi-plus-lg"></i> Add New Expert
                    </button>
                </div>
            </div>
        </div>

        <!-- Search & Filter Section -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form method="get" class="row g-3">
                    <div class="col-md-4">
                        <select name="cat" class="form-select" onchange="this.form.submit()">
                            <option value="">All Expertise</option>
                            <?php if(!empty($categoriesList)): ?>
                                <?php foreach($categoriesList as $cat): ?>
                                    <option value="<?php echo htmlspecialchars($cat['name']); ?>" <?php echo $catFilter === $cat['name'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search by Name or Expertise..." value="<?php echo htmlspecialchars($search); ?>">
                            <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Search</button>
                            <?php if($search || $catFilter): ?>
                                <a href="clinical-experts.php" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i> Clear</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Experts Table -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Photo</th>
                                <th>Name & Details</th>
                                <th>Fees (On/Off)</th>
                                <th>Expertise</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($experts) > 0): ?>
                                <?php $i = 1; foreach ($experts as $row): ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td>
                                        <?php 
                                            $imgSrc = !empty($row['Photo']) ? '../' . $row['Photo'] : '../assets/img/default-expert.png';
                                        ?>
                                        <img src="<?php echo htmlspecialchars($imgSrc); ?>" 
                                             class="rounded-circle border" 
                                             width="50" height="50" 
                                             style="object-fit: cover;" 
                                             onerror="this.src='../assets/img/default-expert.png'">
                                    </td>
                                    <td>
                                        <strong class="text-dark"><?php echo htmlspecialchars($row['Name']); ?></strong><br>
                                        <small class="text-muted"><?php echo htmlspecialchars($row['Designation']); ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">₹<?php echo $row['FeeOnline']; ?> / ₹<?php echo $row['FeeOffline']; ?></span>
                                    </td>
                                    <td>
                                        <small class="d-block text-wrap" style="max-width: 200px;">
                                            <?php echo htmlspecialchars($row['Expertise']); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $row['Status'] === 'active' ? 'success' : 'secondary'; ?>">
                                            <?php echo ucfirst($row['Status']); ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick='editExpert(<?php echo json_encode($row); ?>)'>
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            
                                            <a href="clinical-experts.php?del_expert=<?php echo $row['ExpertId']; ?>" 
                                               class="btn btn-sm btn-outline-danger" 
                                               onclick="return confirm('Are you sure you want to delete this expert?');">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="7" class="text-center py-4 text-muted">No experts found matching your criteria.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- EXPERTISE CATEGORY MANAGER MODAL -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manage Expertise Categories</h5>
                <button type="button" class="btn-close" onclick="categoryModal.hide()" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" class="d-flex gap-2 mb-4">
                    <input type="text" name="category_name" class="form-control" placeholder="New Category (e.g. CBT)" required>
                    <button type="submit" name="add_category" class="btn btn-success text-nowrap"><i class="bi bi-plus-lg"></i> Add</button>
                </form>
                
                <h6 class="text-muted border-bottom pb-2">Existing Categories</h6>
                <div class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
                    <?php if(!empty($categoriesList)): ?>
                        <?php foreach($categoriesList as $cat): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-0">
                            <span><?php echo htmlspecialchars($cat['name']); ?></span>
                            <a href="clinical-experts.php?delete_cat=<?php echo $cat['id']; ?>" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Delete this category?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted small text-center my-3">No categories found. Add one above.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ADD/EDIT EXPERT MODAL -->
<div class="modal fade" id="expertModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add New Expert</h5>
                <button type="button" class="btn-close" onclick="expertModal.hide()" aria-label="Close"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="expert_id" id="expert_id">
                    <input type="hidden" name="existing_photo" id="existing_photo">

                    <div class="row g-3">
                        <!-- Basic Info -->
                        <div class="col-md-6">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="name" required placeholder="e.g. Dr. A. Sharma">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Designation <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="designation" id="designation" required placeholder="e.g. Clinical Psychologist">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Qualification</label>
                            <input type="text" class="form-control" name="qualification" id="qualification" placeholder="e.g. MBBS, MD">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Experience</label>
                            <input type="text" class="form-control" name="experience" id="experience" placeholder="e.g. 10 Years">
                        </div>
                        
                        <!-- Fees -->
                        <div class="col-md-6">
                            <label class="form-label">Online Fee (₹)</label>
                            <input type="number" class="form-control" name="fee_online" id="fee_online" placeholder="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Clinic Fee (₹)</label>
                            <input type="number" class="form-control" name="fee_offline" id="fee_offline" placeholder="0">
                        </div>

                        <!-- Availability -->
                        <div class="col-md-6">
                            <label class="form-label">Available Days</label>
                            <input type="text" class="form-control" name="availability_days" id="availability_days" placeholder="e.g. Mon - Sat">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Available Time</label>
                            <input type="text" class="form-control" name="availability_time" id="availability_time" placeholder="e.g. 10:00 AM - 06:00 PM">
                        </div>

                        <!-- Languages -->
                        <div class="col-md-12">
                            <label class="form-label">Languages Spoken</label>
                            <input type="text" class="form-control" name="languages" id="languages" placeholder="e.g. English, Hindi">
                        </div>

                        <!-- Expertise -->
                        <div class="col-12">
                            <label class="form-label">Expertise (Comma separated)</label>
                            <div class="input-group mb-2">
                                <input type="text" id="custom_expertise" class="form-control" placeholder="Type custom expertise...">
                                <button type="button" class="btn btn-outline-secondary" onclick="addCustomExpertise()">Add</button>
                            </div>
                            <textarea class="form-control mb-2" name="expertise" id="expertise" rows="2" placeholder="e.g. CBT, Depression"></textarea>
                            
                            <div class="card bg-light border-0 p-2">
                                <small class="d-block text-muted mb-2">Click to add expertise:</small>
                                <div class="d-flex flex-wrap gap-2">
                                    <?php if(!empty($categoriesList)): ?>
                                        <?php foreach($categoriesList as $cat): ?>
                                            <span class="badge bg-white text-primary border cursor-pointer" style="cursor: pointer;" onclick="addExpertise('<?php echo addslashes($cat['name']); ?>')">
                                                <i class="bi bi-plus"></i> <?php echo htmlspecialchars($cat['name']); ?>
                                            </span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <small class="text-muted">No categories created yet.</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- About -->
                        <div class="col-12">
                            <label class="form-label">About Doctor</label>
                            <textarea class="form-control" name="about" id="about" rows="3" placeholder="Brief biography..."></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" id="status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Photo</label>
                            <input type="file" class="form-control" name="photo" id="photo_input" accept="image/*" onchange="previewImage(this)">
                            <small class="text-muted" id="photo_help">Leave empty to keep current photo</small>
                            <div id="photo_preview_container" class="mt-2" style="display:none;">
                                <img id="preview_img" src="" class="img-thumbnail" style="max-height: 100px;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="expertModal.hide()">Close</button>
                    <button type="submit" name="save_expert" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var expertModal, categoryModal;
    
    document.addEventListener("DOMContentLoaded", function() {
        expertModal = new bootstrap.Modal(document.getElementById('expertModal'), { backdrop: 'static' });
        categoryModal = new bootstrap.Modal(document.getElementById('categoryModal'));
    });

    function openModal() {
        document.getElementById('modalTitle').innerText = 'Add New Expert';
        document.getElementById('expert_id').value = '';
        document.getElementById('existing_photo').value = '';
        document.getElementById('name').value = '';
        document.getElementById('designation').value = '';
        document.getElementById('qualification').value = '';
        document.getElementById('experience').value = '';
        document.getElementById('expertise').value = '';
        document.getElementById('fee_online').value = '';
        document.getElementById('fee_offline').value = '';
        document.getElementById('languages').value = '';
        document.getElementById('availability_days').value = '';
        document.getElementById('availability_time').value = '';
        document.getElementById('about').value = '';
        document.getElementById('status').value = 'active';
        document.getElementById('photo_help').innerText = 'Upload a new photo';
        document.getElementById('photo_input').value = '';
        document.getElementById('photo_preview_container').style.display = 'none';
        expertModal.show();
    }

    function editExpert(data) {
        document.getElementById('modalTitle').innerText = 'Edit Expert';
        document.getElementById('expert_id').value = data.ExpertId;
        document.getElementById('existing_photo').value = data.Photo;
        document.getElementById('name').value = data.Name;
        document.getElementById('designation').value = data.Designation;
        document.getElementById('qualification').value = data.Qualification;
        document.getElementById('experience').value = data.Experience;
        document.getElementById('expertise').value = data.Expertise;
        document.getElementById('fee_online').value = data.FeeOnline;
        document.getElementById('fee_offline').value = data.FeeOffline;
        document.getElementById('languages').value = data.Languages;
        document.getElementById('availability_days').value = data.AvailabilityDays;
        document.getElementById('availability_time').value = data.AvailabilityTime;
        document.getElementById('about').value = data.About;
        document.getElementById('status').value = data.Status;
        document.getElementById('photo_help').innerText = 'Current: ' + (data.Photo ? 'Photo Uploaded' : 'No Photo');
        
        var previewContainer = document.getElementById('photo_preview_container');
        if (data.Photo) {
            document.getElementById('preview_img').src = '../' + data.Photo;
            previewContainer.style.display = 'block';
        } else {
            previewContainer.style.display = 'none';
        }
        expertModal.show();
    }

    function openCategoryModal() {
        categoryModal.show();
    }

    function addExpertise(text) {
        var textArea = document.getElementById('expertise');
        var currentVal = textArea.value.trim();
        if(currentVal.toLowerCase().includes(text.toLowerCase())) return;
        if (currentVal.length > 0 && currentVal.slice(-1) !== ',') {
            textArea.value = currentVal + ', ' + text;
        } else {
            textArea.value = currentVal + text;
        }
    }
    
    function addCustomExpertise() {
        var input = document.getElementById('custom_expertise');
        var val = input.value.trim();
        if(val) {
            addExpertise(val);
            input.value = '';
        }
    }

    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview_img').src = e.target.result;
                document.getElementById('photo_preview_container').style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<?php require_once 'includes/footer.php'; ?>
</body>
</html>