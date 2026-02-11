<?php
// --- DEBUGGING ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

$pageTitle = 'Manage Internships - ' . APP_NAME;
requireLogin();

// --- 1. HANDLE DELETE ---
if (isset($_GET['del_prog'])) {
    $id = (int)$_GET['del_prog'];
    $conn->query("DELETE FROM internships WHERE InternshipId = $id");
    header("Location: internships-manage.php?msg=deleted");
    exit;
}
if (isset($_GET['del_app'])) {
    $id = (int)$_GET['del_app'];
    $conn->query("DELETE FROM internship_applications WHERE ApplicationId = $id");
    header("Location: internships-manage.php?tab=applications&msg=app_deleted");
    exit;
}

// --- 2. HANDLE SAVE INTERNSHIP (ADD / EDIT PROGRAM) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_internship'])) {
    $id = $_POST['internship_id'] ?? '';
    $title = trim($_POST['title']);
    $duration = trim($_POST['duration']);
    $specialization = trim($_POST['specialization']);
    $fee = (float)$_POST['fee'];
    $suitable = trim($_POST['suitable']);
    $curriculum = trim($_POST['curriculum']); 
    $status = $_POST['status'];
    $order = (int)$_POST['display_order'];

    if ($id) {
        $stmt = $conn->prepare("UPDATE internships SET Title=?, Duration=?, Specialization=?, Fee=?, SuitableFor=?, Curriculum=?, Status=?, DisplayOrder=? WHERE InternshipId=?");
        $stmt->bind_param("sssdsssii", $title, $duration, $specialization, $fee, $suitable, $curriculum, $status, $order, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO internships (Title, Duration, Specialization, Fee, SuitableFor, Curriculum, Status, DisplayOrder) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssdsssi", $title, $duration, $specialization, $fee, $suitable, $curriculum, $status, $order);
    }

    if ($stmt->execute()) {
        header("Location: internships-manage.php?msg=saved");
        exit;
    } else {
        $error = "Database Error: " . $conn->error;
    }
}

// --- 3. HANDLE UPDATE APPLICATION ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_application'])) {
    $appId = (int)$_POST['app_id'];
    $progSelected = trim($_POST['program_selected']);
    $coursePursuing = trim($_POST['course_pursuing']);
    $specialization = trim($_POST['app_specialization']);
    $status = $_POST['app_status'];

    $stmt = $conn->prepare("UPDATE internship_applications SET ProgramSelected=?, CoursePursuing=?, Specialization=?, Status=? WHERE ApplicationId=?");
    $stmt->bind_param("ssssi", $progSelected, $coursePursuing, $specialization, $status, $appId);

    if ($stmt->execute()) {
        header("Location: internships-manage.php?tab=applications&msg=app_updated");
        exit;
    } else {
        $error = "Update Error: " . $conn->error;
    }
}

// --- 4. HANDLE SAVE GENERAL SETTINGS (Specific to Internships) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_general_settings'])) {
    $paymentStatus = isset($_POST['payment_status']) ? '1' : '0';
    $notifyEmail = trim($_POST['notify_email']);
    
    // Save Specific Payment Status for Internships
    $keyPay = 'internship_payment_status';
    $check = $conn->query("SELECT id FROM site_settings WHERE SettingKey = '$keyPay'");
    if($check->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE site_settings SET SettingValue = ? WHERE SettingKey = ?");
        $stmt->bind_param("ss", $paymentStatus, $keyPay);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO site_settings (SettingKey, SettingValue) VALUES (?, ?)");
        $stmt->bind_param("ss", $keyPay, $paymentStatus);
        $stmt->execute();
    }

    // Save Email
    $keyEmail = 'internship_notify_email';
    $check = $conn->query("SELECT id FROM site_settings WHERE SettingKey = '$keyEmail'");
    if($check->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE site_settings SET SettingValue = ? WHERE SettingKey = ?");
        $stmt->bind_param("ss", $notifyEmail, $keyEmail);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO site_settings (SettingKey, SettingValue) VALUES (?, ?)");
        $stmt->bind_param("ss", $keyEmail, $notifyEmail);
        $stmt->execute();
    }
    
    header("Location: internships-manage.php?msg=settings_saved");
    exit;
}

// --- 5. HANDLE SAVE DROPDOWN LISTS ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_lists'])) {
    // Save Specializations
    $specs = trim($_POST['specializations_list']);
    $keySpec = 'internship_specializations';
    $check = $conn->query("SELECT id FROM site_settings WHERE SettingKey = '$keySpec'");
    if($check->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE site_settings SET SettingValue = ? WHERE SettingKey = ?");
        $stmt->bind_param("ss", $specs, $keySpec);
    } else {
        $stmt = $conn->prepare("INSERT INTO site_settings (SettingKey, SettingValue) VALUES (?, ?)");
        $stmt->bind_param("ss", $keySpec, $specs);
    }
    $stmt->execute();

    // Save Courses List
    $courses = trim($_POST['courses_list']);
    $keyCourse = 'internship_courses';
    $check = $conn->query("SELECT id FROM site_settings WHERE SettingKey = '$keyCourse'");
    if($check->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE site_settings SET SettingValue = ? WHERE SettingKey = ?");
        $stmt->bind_param("ss", $courses, $keyCourse);
    } else {
        $stmt = $conn->prepare("INSERT INTO site_settings (SettingKey, SettingValue) VALUES (?, ?)");
        $stmt->bind_param("ss", $keyCourse, $courses);
    }
    $stmt->execute();

    header("Location: internships-manage.php?msg=lists_saved");
    exit;
}

// --- FETCH DATA ---
$programs = [];
$res = $conn->query("SELECT * FROM internships ORDER BY DisplayOrder ASC");
if ($res) $programs = $res->fetch_all(MYSQLI_ASSOC);

$applications = [];
$resApp = $conn->query("SELECT * FROM internship_applications ORDER BY AppliedAt DESC");
if ($resApp) $applications = $resApp->fetch_all(MYSQLI_ASSOC);

// Fetch Academic Courses List for Dropdown
$academicList = [];
$resAcad = $conn->query("SELECT Title FROM academic_programs WHERE Status='active' ORDER BY Title ASC");
if($resAcad) {
    while($r = $resAcad->fetch_assoc()) $academicList[] = $r['Title'];
}

// Fetch Settings
$specializationList = [];
$courseList = [];
$paymentEnabled = '1'; // Default On
$notifyEmail = '';

$resSet = $conn->query("SELECT * FROM site_settings WHERE SettingKey IN ('internship_specializations', 'internship_courses', 'internship_payment_status', 'internship_notify_email')");
if($resSet) {
    while($row = $resSet->fetch_assoc()) {
        if($row['SettingKey'] == 'internship_specializations') $specializationList = array_map('trim', explode(',', $row['SettingValue']));
        if($row['SettingKey'] == 'internship_courses') $courseList = array_map('trim', explode(',', $row['SettingValue']));
        if($row['SettingKey'] == 'internship_payment_status') $paymentEnabled = $row['SettingValue'];
        if($row['SettingKey'] == 'internship_notify_email') $notifyEmail = $row['SettingValue'];
    }
}
$specsString = implode(", ", $specializationList);
$coursesString = implode(", ", $courseList);

// Active Tab Logic
$activeTab = isset($_GET['tab']) && $_GET['tab'] === 'applications' ? 'applications' : 'programs';
?>
<?php require_once 'includes/head.php'; ?>

<body>
<?php require_once 'includes/app-header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<div class="mobile-menu-overlay"></div>

<div class="main-container">
    <div class="xs-pd-20-10 pd-ltr-20">
        
        <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Action completed successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="page-header mb-4 mt-3 bg-white p-4 rounded-3 shadow-sm d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h4 class="page-title mb-1 text-primary fw-bold">
                    <i class="bi bi-briefcase-fill me-2"></i> Manage Internships
                </h4>
                <p class="text-muted small mb-0">Manage programs, applications, and settings.</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-dark shadow-sm" onclick="openSettingsModal()">
                    <i class="bi bi-gear-fill me-1"></i> Settings
                </button>
                <button class="btn btn-outline-secondary shadow-sm" onclick="openListModal()">
                    <i class="bi bi-list-task me-1"></i> Lists
                </button>
                <button class="btn btn-primary shadow-sm" onclick="openModal()">
                    <i class="bi bi-plus-lg me-1"></i> Add Program
                </button>
            </div>
        </div>

        <!-- TABS -->
        <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
            <li class="nav-item">
                <button class="nav-link <?php echo $activeTab == 'programs' ? 'active' : ''; ?>" id="prog-tab" data-bs-toggle="tab" data-bs-target="#prog-tab-pane" type="button">Internship Programs</button>
            </li>
            <li class="nav-item">
                <button class="nav-link <?php echo $activeTab == 'applications' ? 'active' : ''; ?>" id="app-tab" data-bs-toggle="tab" data-bs-target="#app-tab-pane" type="button">
                    Applications <span class="badge bg-danger rounded-pill ms-1"><?php echo count($applications); ?></span>
                </button>
            </li>
        </ul>

        <div class="tab-content">
            
            <!-- PROGRAMS TAB -->
            <div class="tab-pane fade <?php echo $activeTab == 'programs' ? 'show active' : ''; ?>" id="prog-tab-pane">
                <div class="card shadow border-0 rounded-3 overflow-hidden">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-secondary">
                                    <tr>
                                        <th class="ps-4">Title</th>
                                        <th>Duration</th>
                                        <th>Fee</th>
                                        <th>Order</th>
                                        <th>Status</th>
                                        <th class="text-center pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($programs): foreach($programs as $row): ?>
                                    <tr>
                                        <td class="ps-4 fw-bold"><?php echo htmlspecialchars($row['Title']); ?></td>
                                        <td><?php echo htmlspecialchars($row['Duration']); ?></td>
                                        <td>₹<?php echo number_format($row['Fee']); ?></td>
                                        <td><?php echo $row['DisplayOrder']; ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $row['Status']=='active'?'success':'secondary'; ?>-subtle text-<?php echo $row['Status']=='active'?'success':'secondary'; ?>">
                                                <?php echo ucfirst($row['Status']); ?>
                                            </span>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="btn-group shadow-sm">
                                                <button class="btn btn-sm btn-light border text-primary" onclick='editProgram(<?php echo json_encode($row); ?>)'>
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <a href="internships-manage.php?del_prog=<?php echo $row['InternshipId']; ?>" class="btn btn-sm btn-light border text-danger" onclick="return confirm('Delete this program?')">
                                                    <i class="bi bi-trash3"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; else: ?>
                                    <tr><td colspan="6" class="text-center py-5 text-muted">No programs found.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- APPLICATIONS TAB -->
            <div class="tab-pane fade <?php echo $activeTab == 'applications' ? 'show active' : ''; ?>" id="app-tab-pane">
                <div class="card shadow border-0 rounded-3 overflow-hidden">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-secondary">
                                    <tr>
                                        <th class="ps-4">Applicant</th>
                                        <th>Program Selected</th>
                                        <th>Course Pursuing</th>
                                        <th>Specialization</th>
                                        <th>Status</th>
                                        <th class="text-center pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($applications): foreach($applications as $row): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <span class="d-block fw-bold"><?php echo htmlspecialchars($row['FullName']); ?></span>
                                            <small class="text-muted"><?php echo htmlspecialchars($row['Email']); ?></small>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['ProgramSelected']); ?></td>
                                        <td><?php echo htmlspecialchars($row['CoursePursuing']); ?></td>
                                        <td><?php echo htmlspecialchars($row['Specialization']); ?></td>
                                        <td>
                                            <?php if($row['PaymentStatus'] == 'success'): ?>
                                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Paid</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">Unpaid</span>
                                            <?php endif; ?>
                                            <br>
                                            <span class="badge bg-light text-dark border mt-1"><?php echo ucfirst($row['Status']); ?></span>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="btn-group shadow-sm">
                                                <button class="btn btn-sm btn-light border text-info" onclick='viewApp(<?php echo json_encode($row); ?>)' title="View Details">
                                                    <i class="bi bi-eye-fill"></i>
                                                </button>
                                                <!-- EDIT APPLICATION BUTTON -->
                                                <button class="btn btn-sm btn-light border text-primary" onclick='editApp(<?php echo json_encode($row); ?>)' title="Edit Application">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </button>
                                                <a href="internships-manage.php?del_app=<?php echo $row['ApplicationId']; ?>" class="btn btn-sm btn-light border text-danger" onclick="return confirm('Delete this application?')">
                                                    <i class="bi bi-trash3"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; else: ?>
                                    <tr><td colspan="6" class="text-center py-5 text-muted">No applications found.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- GENERAL SETTINGS MODAL -->
<div class="modal fade" id="settingsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Internship Settings</h5>
                <button type="button" class="btn-close" onclick="settingsModal.hide()"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <input type="hidden" name="save_general_settings" value="1">
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold d-block">Online Payment Status (Internships Only)</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="payment_status" name="payment_status" value="1" <?php echo ($paymentEnabled == '1') ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="payment_status">Enable Razorpay for Application Forms</label>
                        </div>
                        <small class="text-muted">If unchecked, the payment step will be skipped for students.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Admin Notification Email</label>
                        <input type="email" name="notify_email" class="form-control" value="<?php echo htmlspecialchars($notifyEmail); ?>" placeholder="admin@example.com">
                        <small class="text-muted">New application alerts will be sent here.</small>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MANAGE LISTS MODAL -->
<div class="modal fade" id="listModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Manage Dropdown Lists</h5>
                <button type="button" class="btn-close" onclick="listModal.hide()"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">1. Specializations</label>
                            <p class="small text-muted mb-2">Add items to the Specialization dropdown.</p>
                            
                            <div class="input-group mb-2">
                                <input type="text" id="new-spec-input" class="form-control" placeholder="Add new...">
                                <button type="button" class="btn btn-outline-primary" onclick="addTag('spec')"><i class="bi bi-plus-lg"></i> Add</button>
                            </div>
                            
                            <div class="border rounded p-2 bg-light" style="min-height: 100px; max-height: 200px; overflow-y: auto;" id="spec-tags-container">
                                <!-- Tags injected via JS -->
                            </div>
                            <input type="hidden" name="specializations_list" id="specializations_list_hidden" value="<?php echo htmlspecialchars($specsString); ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">2. Courses Pursuing</label>
                            <p class="small text-muted mb-2">Add items to the Course dropdown.</p>
                            
                            <div class="input-group mb-2">
                                <input type="text" id="new-course-input" class="form-control" placeholder="Add new...">
                                <button type="button" class="btn btn-outline-primary" onclick="addTag('course')"><i class="bi bi-plus-lg"></i> Add</button>
                            </div>

                            <div class="border rounded p-2 bg-light" style="min-height: 100px; max-height: 200px; overflow-y: auto;" id="course-tags-container">
                                <!-- Tags injected via JS -->
                            </div>
                            <input type="hidden" name="courses_list" id="courses_list_hidden" value="<?php echo htmlspecialchars($coursesString); ?>">
                        </div>
                    </div>
                    <div class="text-end mt-4">
                        <button type="submit" name="save_lists" class="btn btn-primary px-4">Save All Lists</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ADD/EDIT PROGRAM MODAL (IMPROVED UI) -->
<div class="modal fade" id="progModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-white border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Add Internship Program</h5>
                <button type="button" class="btn-close" onclick="progModal.hide()"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST">
                    <input type="hidden" name="internship_id" id="internship_id">
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold text-secondary small">PROGRAM NAME</label>
                            <input type="text" name="title" id="title" class="form-control form-control-lg bg-light border-0" placeholder="e.g. Clinical Psychology Internship" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary small">FEE (INR)</label>
                            <div class="input-group">
                                <span class="input-group-text border-0 bg-light">₹</span>
                                <input type="number" name="fee" id="fee" class="form-control bg-light border-0" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary small">DURATION</label>
                            <div class="input-group">
                                <span class="input-group-text border-0 bg-light"><i class="bi bi-clock"></i></span>
                                <input type="text" name="duration" id="duration" class="form-control bg-light border-0" placeholder="e.g. 1 Month">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary small">SPECIALIZATION</label>
                            <select name="specialization" id="specialization" class="form-select bg-light border-0">
                                <option value="">Select Specialization</option>
                                <?php foreach($specializationList as $sp): ?>
                                    <option value="<?php echo htmlspecialchars($sp); ?>"><?php echo htmlspecialchars($sp); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary small">SUITABLE FOR</label>
                            <input type="text" name="suitable" id="suitable" class="form-control bg-light border-0" placeholder="e.g. UG & PG Students">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold text-secondary small">CURRICULUM TOPICS</label>
                            <textarea name="curriculum" id="curriculum" class="form-control bg-light border-0" rows="3" placeholder="Enter topics separated by commas..."></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary small">SORT ORDER</label>
                            <input type="number" name="display_order" id="display_order" class="form-control bg-light border-0" value="0">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary small">STATUS</label>
                            <select name="status" id="status" class="form-select bg-light border-0">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top text-end">
                        <button type="button" class="btn btn-light border me-2" onclick="progModal.hide()">Cancel</button>
                        <button type="submit" name="save_internship" class="btn btn-primary px-4 fw-bold rounded-pill">Save Program</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- EDIT APPLICATION MODAL -->
<div class="modal fade" id="editAppModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Application Details</h5>
                <button type="button" class="btn-close" onclick="editAppModal.hide()"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <input type="hidden" name="update_application" value="1">
                    <input type="hidden" name="app_id" id="edit_app_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Applicant Name</label>
                        <input type="text" id="edit_app_name" class="form-control bg-light" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Program Selected</label>
                        <select name="program_selected" id="edit_prog_selected" class="form-select">
                            <option value="">-- Select Program --</option>
                            <?php foreach($programs as $p): ?>
                                <option value="<?php echo htmlspecialchars($p['Title']); ?>"><?php echo htmlspecialchars($p['Title']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Course Pursuing</label>
                        <select name="course_pursuing" id="edit_course_pursuing" class="form-select">
                            <option value="">-- Select Course --</option>
                            <?php foreach($courseList as $ac): ?>
                                <option value="<?php echo htmlspecialchars($ac); ?>"><?php echo htmlspecialchars($ac); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Specialization</label>
                        <select name="app_specialization" id="edit_app_specialization" class="form-select">
                            <option value="">-- Select Specialization --</option>
                            <?php foreach($specializationList as $sp): ?>
                                <option value="<?php echo htmlspecialchars($sp); ?>"><?php echo htmlspecialchars($sp); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Application Status</label>
                        <select name="app_status" id="edit_app_status" class="form-select">
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Update Application</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- VIEW APPLICATION MODAL -->
<div class="modal fade" id="viewAppModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Application Details</h5>
                <button type="button" class="btn-close" onclick="viewAppModal.hide()"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3"><strong>Name:</strong> <span id="vName"></span></div>
                    <div class="col-md-6 mb-3"><strong>Email:</strong> <span id="vEmail"></span></div>
                    <div class="col-md-6 mb-3"><strong>Phone:</strong> <span id="vPhone"></span></div>
                    <div class="col-md-6 mb-3"><strong>Gender:</strong> <span id="vGender"></span></div>
                    <div class="col-12 mb-3"><strong>Address:</strong> <span id="vAddress"></span></div>
                    <div class="col-12"><hr></div>
                    <div class="col-md-6 mb-3"><strong>Institute:</strong> <span id="vInstitute"></span></div>
                    <div class="col-md-6 mb-3"><strong>Course:</strong> <span id="vCourse"></span></div>
                    <div class="col-md-6 mb-3"><strong>Program Selected:</strong> <span id="vProg"></span></div>
                    <div class="col-md-6 mb-3"><strong>Start Date:</strong> <span id="vDate"></span></div>
                    <div class="col-12"><hr></div>
                    <div class="col-md-6 mb-3"><strong>Payment Status:</strong> <span id="vPayStatus" class="badge"></span></div>
                    <div class="col-md-6 mb-3"><strong>Payment ID:</strong> <span id="vPayId"></span></div>
                    <div class="col-12">
                        <h6>Documents:</h6>
                        <div id="vDocs" class="d-flex gap-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var progModal, viewAppModal, editAppModal, listModal, settingsModal;
    
    // JS Lists for Tag Manager
    let specList = <?php echo json_encode($specializationList); ?>;
    let courseList = <?php echo json_encode($courseList); ?>;

    // Ensure array
    if(!Array.isArray(specList)) specList = [];
    if(!Array.isArray(courseList)) courseList = [];

    document.addEventListener("DOMContentLoaded", function() {
        progModal = new bootstrap.Modal(document.getElementById('progModal'));
        viewAppModal = new bootstrap.Modal(document.getElementById('viewAppModal'));
        editAppModal = new bootstrap.Modal(document.getElementById('editAppModal'));
        listModal = new bootstrap.Modal(document.getElementById('listModal'));
        settingsModal = new bootstrap.Modal(document.getElementById('settingsModal'));
    });

    function openModal() {
        document.querySelector('#progModal form').reset();
        document.getElementById('internship_id').value = '';
        document.getElementById('modalTitle').innerText = "Add Internship Program";
        document.getElementById('status').value = "active";
        progModal.show();
    }
    
    function openListModal() {
        renderTags('spec');
        renderTags('course');
        listModal.show();
    }
    function openSettingsModal() { settingsModal.show(); }

    // --- Tag Manager Functions ---
    function renderTags(type) {
        const container = document.getElementById(type + '-tags-container');
        const list = type === 'spec' ? specList : courseList;
        const hiddenInput = document.getElementById(type === 'spec' ? 'specializations_list_hidden' : 'courses_list_hidden');
        
        container.innerHTML = '';
        list.forEach((item, index) => {
            if(item.trim() === '') return;
            const tag = document.createElement('span');
            tag.className = 'badge bg-white text-dark border me-1 mb-1 p-2 shadow-sm';
            tag.innerHTML = `${item} <i class="bi bi-x-circle text-danger ms-2" style="cursor:pointer;" onclick="removeTag('${type}', ${index})"></i>`;
            container.appendChild(tag);
        });

        hiddenInput.value = list.join(', ');
    }

    function addTag(type) {
        const input = document.getElementById('new-' + type + '-input');
        const val = input.value.trim();
        if(val) {
            const list = type === 'spec' ? specList : courseList;
            // Prevent duplicates
            if(!list.includes(val)) {
                list.push(val);
                renderTags(type);
            }
            input.value = '';
        }
    }

    function removeTag(type, index) {
        const list = type === 'spec' ? specList : courseList;
        list.splice(index, 1);
        renderTags(type);
    }
    
    // Allow 'Enter' key to add tag
    document.getElementById('new-spec-input').addEventListener('keypress', function(e) {
        if(e.key === 'Enter') { e.preventDefault(); addTag('spec'); }
    });
    document.getElementById('new-course-input').addEventListener('keypress', function(e) {
        if(e.key === 'Enter') { e.preventDefault(); addTag('course'); }
    });
    // --- End Tag Manager ---

    function editProgram(data) {
        document.getElementById('modalTitle').innerText = "Edit Internship Program";
        document.getElementById('internship_id').value = data.InternshipId;
        document.getElementById('title').value = data.Title;
        document.getElementById('duration').value = data.Duration;
        document.getElementById('specialization').value = data.Specialization;
        document.getElementById('fee').value = data.Fee;
        document.getElementById('suitable').value = data.SuitableFor;
        document.getElementById('curriculum').value = data.Curriculum;
        document.getElementById('display_order').value = data.DisplayOrder;
        document.getElementById('status').value = data.Status;
        progModal.show();
    }

    function editApp(data) {
        document.getElementById('edit_app_id').value = data.ApplicationId;
        document.getElementById('edit_app_name').value = data.FullName;
        document.getElementById('edit_prog_selected').value = data.ProgramSelected;
        document.getElementById('edit_course_pursuing').value = data.CoursePursuing;
        document.getElementById('edit_app_specialization').value = data.Specialization;
        document.getElementById('edit_app_status').value = data.Status;
        editAppModal.show();
    }

    function viewApp(data) {
        document.getElementById('vName').innerText = (data.Prefix ? data.Prefix + ' ' : '') + data.FullName;
        document.getElementById('vEmail').innerText = data.Email;
        document.getElementById('vPhone').innerText = data.ContactNumber;
        document.getElementById('vGender').innerText = data.Gender;
        document.getElementById('vAddress').innerText = data.Address;
        
        document.getElementById('vInstitute').innerText = data.InstituteName;
        document.getElementById('vCourse').innerText = data.CoursePursuing;
        document.getElementById('vProg').innerText = data.ProgramSelected;
        document.getElementById('vDate').innerText = data.ProposedStartDate;
        
        const payBadge = document.getElementById('vPayStatus');
        payBadge.innerText = data.PaymentStatus.toUpperCase();
        payBadge.className = 'badge ' + (data.PaymentStatus == 'success' ? 'bg-success' : 'bg-warning text-dark');
        document.getElementById('vPayId').innerText = data.RazorpayPaymentId || '-';

        // Documents
        const docsDiv = document.getElementById('vDocs');
        docsDiv.innerHTML = '';
        const docs = [
            { name: 'ID Proof', file: data.IDProof },
            { name: 'Recommendation', file: data.RecommendationLetter },
            { name: 'Photo', file: data.Photograph },
            { name: 'Receipt', file: data.FeeReceipt }
        ];
        
        docs.forEach(d => {
            if(d.file) {
                docsDiv.innerHTML += `<a href="../${d.file}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-file-earmark-text"></i> ${d.name}</a>`;
            }
        });

        viewAppModal.show();
    }
</script>

<?php require_once 'includes/footer.php'; ?>
</body>
</html>