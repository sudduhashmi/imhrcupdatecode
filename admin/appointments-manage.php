<?php
// --- DEBUGGING: Enable Error Reporting ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

$pageTitle = 'Appointments - ' . APP_NAME;
requireLogin();

// --- HELPER FUNCTION: EXTRACT TIME SLOT ---
function extractTimeSlot($message) {
    if (preg_match('/Time Slot:\s*(.*?)(\n|$)/i', $message, $matches)) {
        return trim($matches[1]);
    }
    return '-';
}

// --- 1. HANDLE DELETE APPOINTMENT ---
if (isset($_GET['del'])) {
    $id = (int)$_GET['del'];
    $conn->query("DELETE FROM appointments WHERE id = $id");
    header("Location: appointments-manage.php?msg=deleted");
    exit;
}

// --- 2. HANDLE STATUS UPDATE ---
if (isset($_GET['status']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $status = $_GET['status'];
    $allowed_statuses = ['pending', 'confirmed', 'completed', 'cancelled'];
    if(in_array($status, $allowed_statuses)) {
        $stmt = $conn->prepare("UPDATE appointments SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        $stmt->execute();
    }
    header("Location: appointments-manage.php?msg=updated");
    exit;
}

// --- 3. HANDLE SAVE APPOINTMENT (ADD / EDIT) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_appointment'])) {
    $id = $_POST['appointment_id'] ?? '';
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $expertId = !empty($_POST['expert_id']) ? $_POST['expert_id'] : NULL;
    $date = $_POST['date'];
    $status = $_POST['status'];
    
    // Construct Message with Details
    $time = $_POST['time'] ?? '';
    $mode = $_POST['mode'] ?? 'online';
    $notes = trim($_POST['notes']);
    
    // Combine details into message field
    $message = "Reason: $notes\nMode: $mode\nTime Slot: $time";

    if ($id) {
        // UPDATE
        $stmt = $conn->prepare("UPDATE appointments SET name=?, email=?, phone=?, expert_id=?, appointment_date=?, message=?, status=? WHERE id=?");
        $stmt->bind_param("sssisssi", $name, $email, $phone, $expertId, $date, $message, $status, $id);
        $msg = "updated";
    } else {
        // INSERT
        $stmt = $conn->prepare("INSERT INTO appointments (name, email, phone, expert_id, appointment_date, message, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssisss", $name, $email, $phone, $expertId, $date, $message, $status);
        $msg = "added";
    }

    if ($stmt->execute()) {
        header("Location: appointments-manage.php?msg=$msg");
        exit;
    }
}

// --- 4. HANDLE ADD NEW DOCTOR ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_new_doctor'])) {
    $name = trim($_POST['doc_name']);
    $designation = trim($_POST['doc_designation']);
    
    // Image Upload
    $photoPath = '';
    if (isset($_FILES['doc_photo']) && $_FILES['doc_photo']['error'] === 0) {
        $uploadDir = '../assets/img/experts/';
        if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
        
        $fileName = time() . '_' . basename($_FILES['doc_photo']['name']);
        if (move_uploaded_file($_FILES['doc_photo']['tmp_name'], $uploadDir . $fileName)) {
            $photoPath = 'assets/img/experts/' . $fileName;
        }
    }
    
    if (!empty($name)) {
        $stmt = $conn->prepare("INSERT INTO experts (Name, Designation, Photo, Status) VALUES (?, ?, ?, 'active')");
        $stmt->bind_param("sss", $name, $designation, $photoPath);
        if ($stmt->execute()) {
            header("Location: appointments-manage.php?msg=doc_added");
            exit;
        }
    }
}

// Fetch Experts for Dropdown
$expertsList = getRows("SELECT ExpertId, Name, Designation FROM experts WHERE Status = 'active' ORDER BY Name ASC");

// Fetch Appointments with Search
$search = $_GET['search'] ?? '';
$where = "1=1";
if ($search) {
    $s = $conn->real_escape_string($search);
    $where .= " AND (a.name LIKE '%$s%' OR a.phone LIKE '%$s%' OR e.Name LIKE '%$s%')";
}

$sql = "SELECT a.*, e.Name as ExpertName, e.Designation 
        FROM appointments a 
        LEFT JOIN experts e ON a.expert_id = e.ExpertId 
        WHERE $where
        ORDER BY a.appointment_date DESC, a.created_at DESC";
$appointments = getRows($sql);
?>
<?php require_once 'includes/head.php'; ?>

<!-- Styles -->
<style>
    #pdf-container { display: none; }
    
    .bg-gradient-primary { background: linear-gradient(135deg, #0b2c57 0%, #1a4b8c 100%); }
    .status-badge { font-size: 0.75rem; padding: 5px 10px; border-radius: 6px; font-weight: 600; text-transform: uppercase; width: 100%; text-align: center; }
    .avatar-circle { width: 40px; height: 40px; background-color: #eef2ff; color: #4f46e5; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 16px; }
    
    /* Table Fixes */
    .table td, .table th { vertical-align: middle; }
</style>

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
                if($_GET['msg'] == 'deleted') echo "Appointment deleted successfully.";
                if($_GET['msg'] == 'updated') echo "Appointment status updated.";
                if($_GET['msg'] == 'added') echo "New appointment booked successfully.";
                if($_GET['msg'] == 'doc_added') echo "New doctor added successfully.";
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Header -->
        <div class="page-header mb-4 mt-3 bg-white p-4 rounded-3 shadow-sm d-flex justify-content-between align-items-center">
            <div>
                <h4 class="page-title mb-1 text-primary fw-bold">
                    <i class="bi bi-calendar-check me-2"></i> Appointments
                </h4>
                <p class="text-muted small mb-0">Manage booking requests from patients.</p>
            </div>
            <div class="d-flex gap-2">
                <form class="d-flex" method="GET">
                    <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button>
                </form>
                <button class="btn btn-outline-primary btn-sm shadow-sm text-nowrap" onclick="openDoctorModal()">
                    <i class="bi bi-person-plus-fill me-1"></i> Add Doctor
                </button>
                <button class="btn btn-primary btn-sm shadow-sm text-nowrap" onclick="openModal()">
                    <i class="bi bi-plus-lg me-1"></i> Book Appointment
                </button>
            </div>
        </div>

        <div class="card shadow border-0 rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th class="ps-4">Date & Time</th>
                                <th>Patient Info</th>
                                <th>Doctor Requested</th>
                                <th>Status</th>
                                <th class="text-center pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($appointments) > 0): ?>
                            <?php foreach($appointments as $row): ?>
                            <tr>
                                <td class="ps-4 text-nowrap">
                                    <span class="d-block fw-bold text-dark">
                                        <?php echo date('d M, Y', strtotime($row['appointment_date'])); ?>
                                    </span>
                                    <span class="badge bg-light text-primary border border-primary-subtle mt-1">
                                        <i class="bi bi-clock me-1"></i> <?php echo extractTimeSlot($row['message']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-3">
                                            <?php echo strtoupper(substr($row['name'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <strong class="text-dark d-block"><?php echo htmlspecialchars($row['name']); ?></strong>
                                            <a href="tel:<?php echo $row['phone']; ?>" class="text-decoration-none text-muted small">
                                                <i class="bi bi-phone"></i> <?php echo htmlspecialchars($row['phone']); ?>
                                            </a>
                                            <!-- Hidden data for JS Modal -->
                                            <span class="d-none" id="email_<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['email']); ?></span>
                                            <span class="d-none" id="msg_<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['message']); ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if($row['ExpertName']): ?>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-person-badge-fill text-primary me-2"></i>
                                            <div>
                                                <span class="d-block fw-bold text-dark">Dr. <?php echo htmlspecialchars($row['ExpertName']); ?></span>
                                                <small class="text-muted"><?php echo htmlspecialchars($row['Designation']); ?></small>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-subtle text-secondary border">General Consultation</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php 
                                        $status = $row['status'];
                                        $statusColor = 'secondary';
                                        if($status == 'confirmed') $statusColor = 'success';
                                        if($status == 'pending') $statusColor = 'warning';
                                        if($status == 'cancelled') $statusColor = 'danger';
                                        if($status == 'completed') $statusColor = 'primary';
                                    ?>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-<?php echo $statusColor; ?> dropdown-toggle status-badge w-100" type="button" data-bs-toggle="dropdown" data-bs-display="static">
                                            <?php echo ucfirst($row['status']); ?>
                                        </button>
                                        <ul class="dropdown-menu shadow border-0">
                                            <li><h6 class="dropdown-header">Update Status</h6></li>
                                            <li><a class="dropdown-item" href="?status=pending&id=<?php echo $row['id']; ?>"><i class="bi bi-hourglass text-warning me-2"></i> Pending</a></li>
                                            <li><a class="dropdown-item" href="?status=confirmed&id=<?php echo $row['id']; ?>"><i class="bi bi-check-circle text-success me-2"></i> Confirmed</a></li>
                                            <li><a class="dropdown-item" href="?status=completed&id=<?php echo $row['id']; ?>"><i class="bi bi-flag text-primary me-2"></i> Completed</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="?status=cancelled&id=<?php echo $row['id']; ?>"><i class="bi bi-x-circle me-2"></i> Cancelled</a></li>
                                        </ul>
                                    </div>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group shadow-sm">
                                        <button class="btn btn-sm btn-light border text-primary" onclick='editAppointment(<?php echo json_encode($row); ?>)' title="Edit Details">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn btn-sm btn-light border text-info" onclick='viewDetails(<?php echo json_encode($row); ?>)' title="View & PDF">
                                            <i class="bi bi-eye-fill"></i>
                                        </button>
                                        <a href="appointments-manage.php?del=<?php echo $row['id']; ?>" class="btn btn-sm btn-light border text-danger" onclick="return confirm('Delete this record permanently?')" title="Delete">
                                            <i class="bi bi-trash3"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3 text-secondary-subtle"></i>
                                    No appointments found.
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

<!-- ADD DOCTOR MODAL -->
<div class="modal fade" id="doctorModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Add New Doctor</h5>
                <button type="button" class="btn-close" onclick="doctorModal.hide()"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Profile Photo</label>
                        <input type="file" name="doc_photo" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Doctor Name</label>
                        <input type="text" name="doc_name" class="form-control bg-light border-0" required placeholder="e.g. Dr. A. Sharma">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Designation</label>
                        <input type="text" name="doc_designation" class="form-control bg-light border-0" placeholder="e.g. Clinical Psychologist">
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="save_quick_expert" class="btn btn-primary rounded-pill">Add Doctor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ADD/EDIT APPOINTMENT MODAL -->
<div class="modal fade" id="appointmentModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Book Appointment</h5>
                <button type="button" class="btn-close" onclick="appointmentModal.hide()"></button>
            </div>
            <div class="modal-body p-4 pt-3">
                <form method="POST">
                    <input type="hidden" name="appointment_id" id="appointment_id">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Patient Name</label>
                            <input type="text" name="name" id="name" class="form-control bg-light border-0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Email</label>
                            <input type="email" name="email" id="email" class="form-control bg-light border-0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control bg-light border-0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Date</label>
                            <input type="date" name="date" id="date" class="form-control bg-light border-0" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Assign Doctor</label>
                            <select name="expert_id" id="expert_id" class="form-select bg-light border-0">
                                <option value="">General Consultation</option>
                                <?php foreach($expertsList as $exp): ?>
                                    <option value="<?php echo $exp['ExpertId']; ?>"><?php echo htmlspecialchars($exp['Name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                         <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted">Time Slot</label>
                            <select name="time" id="time" class="form-select bg-light border-0">
                                <option value="10:00 AM">10:00 AM</option>
                                <option value="11:00 AM">11:00 AM</option>
                                <option value="12:00 PM">12:00 PM</option>
                                <option value="01:00 PM">01:00 PM</option>
                                <option value="02:00 PM">02:00 PM</option>
                                <option value="03:00 PM">03:00 PM</option>
                                <option value="04:00 PM">04:00 PM</option>
                                <option value="05:00 PM">05:00 PM</option>
                                <option value="06:00 PM">06:00 PM</option>
                            </select>
                        </div>
                         <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted">Mode</label>
                            <select name="mode" id="mode" class="form-select bg-light border-0">
                                <option value="online">Online</option>
                                <option value="offline">Offline</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">Notes / Reason</label>
                            <textarea name="notes" id="notes" class="form-control bg-light border-0" rows="3"></textarea>
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label small fw-bold text-muted">Status</label>
                            <select name="status" id="status" class="form-select bg-light border-0">
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" name="save_appointment" class="btn btn-primary rounded-pill">Save Appointment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- VIEW DETAILS MODAL -->
<div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title text-white" style="color: white !important;"><i class="bi bi-file-earmark-text me-2"></i>Receipt</h5>
                <button type="button" class="btn-close btn-close-white" onclick="viewModal.hide()"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-3">
                    <h4 class="fw-bold text-primary mb-0" id="vName"></h4>
                    <p class="text-muted small" id="vEmail"></p>
                </div>
                <div class="row g-3 border-top pt-3">
                    <div class="col-6"><small class="text-muted d-block">Doctor</small><strong id="vDoc"></strong></div>
                    <div class="col-6"><small class="text-muted d-block">Date</small><strong id="vDate"></strong></div>
                    <div class="col-6"><small class="text-muted d-block">Phone</small><strong id="vPhone"></strong></div>
                    <div class="col-6"><small class="text-muted d-block">Status</small><strong id="vStatus"></strong></div>
                    <div class="col-12"><small class="text-muted d-block">Notes</small><p class="mb-0 bg-light p-2 rounded small" id="vMsg"></p></div>
                </div>
            </div>
            <div class="modal-footer bg-light border-top-0">
                <button type="button" class="btn btn-success" onclick="downloadPDF()"><i class="bi bi-download me-1"></i> Download PDF</button>
                <button type="button" class="btn btn-warning" onclick="printReceipt()"><i class="bi bi-printer me-1"></i> Print</button>
                <button type="button" class="btn btn-secondary" onclick="viewModal.hide()">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- HIDDEN PDF TEMPLATE -->
<div id="pdf-container" style="display:none;">
    <div style="font-family: Helvetica, sans-serif; padding: 40px; color: #333; position:relative; overflow:hidden;">
        <!-- Watermark -->
        <div style="position: absolute; top: 40%; left: 15%; transform: rotate(-45deg); font-size: 80px; color: rgba(0,0,0,0.04); font-weight: bold; pointer-events: none; white-space: nowrap; z-index:0;">IMHRC OFFICIAL</div>

        <div style="border-bottom: 3px solid #0b2c57; padding-bottom: 20px; margin-bottom: 30px; display: flex; justify-content: space-between; position:relative; z-index:1;">
            <div style="width: 50%;">
                <img src="../assets/img/logo.png" alt="IMHRC Logo" style="height: 70px;">
            </div>
            <div style="width: 50%; text-align: right; font-size: 12px; color: #555;">
                <strong style="color: #0b2c57; font-size: 16px;">Indian Mental Health and Research Centre</strong><br>
                1040 B, Near Mount Carmel School,<br>
                Sector-C, Mahanagar, Lucknow, UP 226006<br>
                info.imhrc@gmail.com | +91 6200479520
            </div>
        </div>

        <div style="text-align: center; margin-bottom: 40px; position:relative; z-index:1;">
            <h2 style="color: #0b2c57; margin: 0; text-transform: uppercase;">Appointment Receipt</h2>
            <p style="color: #777; font-size: 13px; margin-top: 5px;">This document serves as an official confirmation of your request.</p>
        </div>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 40px; font-size: 14px; position:relative; z-index:1;">
            <tr>
                <th style="text-align: left; padding: 12px; border-bottom: 1px solid #ddd; width: 35%; background: #f8f9fa; color: #0b2c57;">Patient Name</th>
                <td style="padding: 12px; border-bottom: 1px solid #ddd;" id="pdfName"></td>
            </tr>
            <tr>
                <th style="text-align: left; padding: 12px; border-bottom: 1px solid #ddd; background: #f8f9fa; color: #0b2c57;">Email</th>
                <td style="padding: 12px; border-bottom: 1px solid #ddd;" id="pdfEmail"></td>
            </tr>
            <tr>
                <th style="text-align: left; padding: 12px; border-bottom: 1px solid #ddd; background: #f8f9fa; color: #0b2c57;">Phone</th>
                <td style="padding: 12px; border-bottom: 1px solid #ddd;" id="pdfPhone"></td>
            </tr>
            <tr>
                <th style="text-align: left; padding: 12px; border-bottom: 1px solid #ddd; background: #f8f9fa; color: #0b2c57;">Doctor</th>
                <td style="padding: 12px; border-bottom: 1px solid #ddd;" id="pdfDoc"></td>
            </tr>
            <tr>
                <th style="text-align: left; padding: 12px; border-bottom: 1px solid #ddd; background: #f8f9fa; color: #0b2c57;">Scheduled Date</th>
                <td style="padding: 12px; border-bottom: 1px solid #ddd;" id="pdfDate"></td>
            </tr>
            <tr>
                <th style="text-align: left; padding: 12px; border-bottom: 1px solid #ddd; background: #f8f9fa; color: #0b2c57;">Current Status</th>
                <td style="padding: 12px; border-bottom: 1px solid #ddd; font-weight: bold; text-transform: uppercase;" id="pdfStatus"></td>
            </tr>
            <tr>
                <th style="text-align: left; padding: 12px; border-bottom: 1px solid #ddd; background: #f8f9fa; color: #0b2c57;">Notes</th>
                <td style="padding: 12px; border-bottom: 1px solid #ddd;" id="pdfMsg"></td>
            </tr>
        </table>

        <div style="margin-top: 60px; text-align: center; border-top: 1px solid #eee; padding-top: 15px; color: #999; font-size: 11px; position:relative; z-index:1;">
            <p>Generated on: <?php echo date('d-M-Y h:i A'); ?></p>
            <p>&copy; <?php echo date('Y'); ?> IMHRC. All rights reserved. Computer generated document.</p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Include html2pdf.js via CDN for direct download capability -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
    var appointmentModal, viewModal, doctorModal;
    var currentApp = {};

    document.addEventListener("DOMContentLoaded", function() {
        appointmentModal = new bootstrap.Modal(document.getElementById('appointmentModal'));
        viewModal = new bootstrap.Modal(document.getElementById('viewModal'));
        doctorModal = new bootstrap.Modal(document.getElementById('doctorModal'));
    });

    function openModal() {
        document.getElementById('modalTitle').innerText = "Book Appointment";
        document.getElementById('appointment_id').value = "";
        document.getElementById('name').value = "";
        document.getElementById('email').value = "";
        document.getElementById('phone').value = "";
        document.getElementById('date').value = "";
        document.getElementById('expert_id').value = "";
        document.getElementById('notes').value = "";
        document.getElementById('status').value = "confirmed";
        appointmentModal.show();
    }
    
    function openDoctorModal() {
        doctorModal.show();
    }

    function editAppointment(data) {
        document.getElementById('modalTitle').innerText = "Edit Appointment";
        document.getElementById('appointment_id').value = data.id;
        document.getElementById('name').value = data.name;
        document.getElementById('email').value = data.email;
        document.getElementById('phone').value = data.phone;
        document.getElementById('date').value = data.appointment_date;
        document.getElementById('expert_id').value = data.expert_id;
        document.getElementById('status').value = data.status;
        
        // Extract notes from message
        let msg = data.message || '';
        let notes = msg.split('Mode:')[0].replace('Reason: ', '').trim();
        document.getElementById('notes').value = notes;
        
        appointmentModal.show();
    }

    function viewDetails(data) {
        currentApp = data;
        document.getElementById('vName').innerText = data.name;
        document.getElementById('vEmail').innerText = data.email;
        document.getElementById('vPhone').innerText = data.phone;
        document.getElementById('vDate').innerText = data.appointment_date;
        document.getElementById('vDoc').innerText = data.ExpertName ? 'Dr. '+data.ExpertName : 'General';
        document.getElementById('vStatus').innerText = data.status.toUpperCase();
        document.getElementById('vMsg').innerText = data.message;
        viewModal.show();
    }

    function preparePDFContent() {
        document.getElementById('pdfName').innerText = currentApp.name;
        document.getElementById('pdfEmail').innerText = currentApp.email; 
        document.getElementById('pdfPhone').innerText = currentApp.phone;
        document.getElementById('pdfDoc').innerText = currentApp.ExpertName ? 'Dr. '+currentApp.ExpertName : 'General';
        
        const dateObj = new Date(currentApp.appointment_date);
        document.getElementById('pdfDate').innerText = dateObj.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        
        document.getElementById('pdfStatus').innerText = currentApp.status.toUpperCase();
        document.getElementById('pdfMsg').innerText = currentApp.message;
    }

    function downloadPDF() {
        preparePDFContent();
        const element = document.getElementById('pdf-container');
        element.style.display = 'block'; // Make visible for capture

        const opt = {
            margin:       0.3,
            filename:     'IMHRC_Appointment_Receipt.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true },
            jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' }
        };

        html2pdf().set(opt).from(element).save().then(function(){
            element.style.display = 'none'; // Hide again
        });
    }

    function printReceipt() {
        preparePDFContent();
        var content = document.getElementById('pdf-container').innerHTML;
        var win = window.open('', '', 'height=800,width=900');
        
        win.document.write('<html><head><title>Appointment Receipt</title>');
        win.document.write('<style>@page { size: A4; margin: 15mm; } body { font-family: Arial, sans-serif; -webkit-print-color-adjust: exact; margin: 0; padding: 20px; }</style>');
        win.document.write('</head><body>');
        win.document.write(content);
        win.document.write('</body></html>');
        
        win.document.close();
        win.focus();
        
        setTimeout(function(){ 
            win.print();
            win.close();
        }, 500);
    }
</script>

<?php require_once 'includes/footer.php'; ?>
</body>
</html>