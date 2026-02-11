<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

$pageTitle = 'Manage Admissions - ' . APP_NAME;
requireLogin();

// --- 1. HANDLE DELETE ---
if (isset($_GET['del'])) {
    $id = (int)$_GET['del'];
    // Optional: Delete uploaded files logic could go here
    $conn->query("DELETE FROM admissions WHERE AdmissionId = $id");
    header("Location: manageAdmissionForm.php?msg=deleted");
    exit;
}

// --- 2. HANDLE STATUS UPDATE ---
if (isset($_GET['status']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $status = $_GET['status']; // 'approved', 'rejected', 'pending'
    $stmt = $conn->prepare("UPDATE admissions SET Status = ? WHERE AdmissionId = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    header("Location: manageAdmissionForm.php?msg=updated");
    exit;
}

// --- FETCH DATA ---
$search = $_GET['search'] ?? '';
$where = "1=1";
if ($search) {
    $s = $conn->real_escape_string($search);
    $where .= " AND (CandidateName LIKE '%$s%' OR Email LIKE '%$s%' OR ContactNumber LIKE '%$s%' OR Course LIKE '%$s%')";
}

$sql = "SELECT * FROM admissions WHERE $where ORDER BY AppliedAt DESC";
$applications = getRows($sql);
?>
<?php require_once 'includes/head.php'; ?>

<!-- Custom Styles for View Modal -->
<style>
    .view-label { font-weight: 600; color: #6c757d; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .view-value { font-weight: 500; color: #333; font-size: 1rem; margin-bottom: 15px; display: block; border-bottom: 1px dashed #eee; padding-bottom: 5px;}
    .doc-preview { width: 100%; height: 150px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd; padding: 4px; background: #fff; cursor: pointer; transition: 0.3s; }
    .doc-preview:hover { transform: scale(1.02); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    .nav-tabs .nav-link.active { background-color: #0b2c57; color: white; border-color: #0b2c57; }
    .nav-tabs .nav-link { color: #555; font-weight: 600; }
    .section-header { background: #f8f9fa; padding: 10px 15px; border-left: 4px solid #0b2c57; font-weight: 700; color: #0b2c57; margin-bottom: 20px; border-radius: 4px; }
</style>

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

        <div class="page-header mb-4 mt-3 bg-white p-4 rounded-3 shadow-sm d-flex justify-content-between align-items-center">
            <div>
                <h4 class="page-title mb-1 text-primary fw-bold">
                    <i class="bi bi-mortarboard-fill me-2"></i> Admission Applications
                </h4>
                <p class="text-muted small mb-0">Review and manage student admission forms.</p>
            </div>
            <div>
                <form class="d-flex" method="GET">
                    <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Search applicant..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
                </form>
            </div>
        </div>

        <div class="card shadow border-0 rounded-3 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Candidate</th>
                                <th>Contact</th>
                                <th>Course Applied</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th class="text-center pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($applications) > 0): ?>
                            <?php foreach($applications as $row): ?>
                            <tr>
                                <td class="ps-4 text-muted">#<?php echo $row['AdmissionId']; ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if(!empty($row['PhotoPath'])): ?>
                                            <img src="../<?php echo $row['PhotoPath']; ?>" class="rounded-circle me-3 border" width="40" height="40" style="object-fit:cover;" onerror="this.src='../assets/img/default-user.png'">
                                        <?php else: ?>
                                            <div class="rounded-circle me-3 bg-light d-flex align-items-center justify-content-center border" style="width:40px; height:40px;"><i class="bi bi-person"></i></div>
                                        <?php endif; ?>
                                        <div>
                                            <strong class="text-dark d-block"><?php echo htmlspecialchars($row['CandidateName']); ?></strong>
                                            <small class="text-muted"><?php echo htmlspecialchars($row['Gender']); ?>, <?php echo htmlspecialchars($row['Category']); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        <i class="bi bi-envelope"></i> <?php echo htmlspecialchars($row['Email']); ?><br>
                                        <i class="bi bi-phone"></i> <?php echo htmlspecialchars($row['ContactNumber']); ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border"><?php echo htmlspecialchars($row['Course']); ?></span>
                                    <div class="small text-muted mt-1"><?php echo htmlspecialchars($row['CourseType']); ?></div>
                                </td>
                                <td><small class="text-muted"><?php echo date('d M Y', strtotime($row['AppliedAt'])); ?></small></td>
                                <td>
                                    <?php 
                                        $st = $row['Status'];
                                        $badge = $st == 'approved' ? 'success' : ($st == 'rejected' ? 'danger' : 'warning');
                                    ?>
                                    <span class="badge bg-<?php echo $badge; ?>"><?php echo ucfirst($st); ?></span>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group shadow-sm">
                                        <!-- View Button -->
                                        <button class="btn btn-sm btn-light border text-primary" 
                                            onclick='viewApplication(<?php echo json_encode($row, JSON_HEX_APOS|JSON_HEX_QUOT); ?>)' 
                                            title="View Details">
                                            <i class="bi bi-eye-fill"></i>
                                        </button>
                                        
                                        <!-- Approve -->
                                        <a href="?status=approved&id=<?php echo $row['AdmissionId']; ?>" class="btn btn-sm btn-light border text-success" title="Approve" onclick="return confirm('Approve this application?')">
                                            <i class="bi bi-check-lg"></i>
                                        </a>
                                        
                                        <!-- Reject -->
                                        <a href="?status=rejected&id=<?php echo $row['AdmissionId']; ?>" class="btn btn-sm btn-light border text-secondary" title="Reject" onclick="return confirm('Reject this application?')">
                                            <i class="bi bi-x-lg"></i>
                                        </a>

                                        <!-- Delete -->
                                        <a href="?del=<?php echo $row['AdmissionId']; ?>" class="btn btn-sm btn-light border text-danger" title="Delete" onclick="return confirm('Permanently delete this application?')">
                                            <i class="bi bi-trash3-fill"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr><td colspan="7" class="text-center py-5 text-muted">No applications found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- VIEW APPLICATION MODAL -->
<div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-white border-bottom">
                <div>
                    <h5 class="modal-title fw-bold text-primary" id="vTitle">Application Details</h5>
                    <small class="text-muted" id="vSub">ID: #---</small>
                </div>
                <button type="button" class="btn-close" onclick="viewModal.hide()"></button>
            </div>
            
            <div class="modal-body bg-light p-4">
                
                <!-- Tabs -->
                <ul class="nav nav-tabs mb-4" id="appTabs" role="tablist">
                    <li class="nav-item"><button class="nav-link active" id="tab-personal" data-bs-toggle="tab" data-bs-target="#content-personal" type="button">Personal Info</button></li>
                    <li class="nav-item"><button class="nav-link" id="tab-address" data-bs-toggle="tab" data-bs-target="#content-address" type="button">Contact & Address</button></li>
                    <li class="nav-item"><button class="nav-link" id="tab-course" data-bs-toggle="tab" data-bs-target="#content-course" type="button">Course Info</button></li>
                    <li class="nav-item"><button class="nav-link" id="tab-education" data-bs-toggle="tab" data-bs-target="#content-education" type="button">Qualifications</button></li>
                    <li class="nav-item"><button class="nav-link" id="tab-uploads" data-bs-toggle="tab" data-bs-target="#content-uploads" type="button">Documents</button></li>
                </ul>

                <div class="tab-content">
                    
                    <!-- 1. PERSONAL DETAILS -->
                    <div class="tab-pane fade show active" id="content-personal">
                        <div class="card border-0 shadow-sm p-4">
                            <h6 class="section-header">Basic Details</h6>
                            <div class="row">
                                <div class="col-md-4"><span class="view-label">Full Name</span><span class="view-value" id="vpName"></span></div>
                                <div class="col-md-4"><span class="view-label">Father's Name</span><span class="view-value" id="vpFather"></span></div>
                                <div class="col-md-4"><span class="view-label">Mother's Name</span><span class="view-value" id="vpMother"></span></div>
                                <div class="col-md-3"><span class="view-label">DOB</span><span class="view-value" id="vpDob"></span></div>
                                <div class="col-md-3"><span class="view-label">Gender</span><span class="view-value" id="vpGender"></span></div>
                                <div class="col-md-3"><span class="view-label">Category</span><span class="view-value" id="vpCat"></span></div>
                                <div class="col-md-3"><span class="view-label">Employed</span><span class="view-value" id="vpEmp"></span></div>
                                <div class="col-md-4"><span class="view-label">Aadhaar No.</span><span class="view-value" id="vpAadhar"></span></div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. CONTACT & ADDRESS -->
                    <div class="tab-pane fade" id="content-address">
                        <div class="card border-0 shadow-sm p-4">
                            <h6 class="section-header">Contact Information</h6>
                            <div class="row mb-4">
                                <div class="col-md-4"><span class="view-label">Phone</span><span class="view-value" id="vcPhone"></span></div>
                                <div class="col-md-4"><span class="view-label">Alternate Phone</span><span class="view-value" id="vcAlt"></span></div>
                                <div class="col-md-4"><span class="view-label">Email</span><span class="view-value" id="vcEmail"></span></div>
                                <div class="col-md-6"><span class="view-label">Employer Name</span><span class="view-value" id="vcEmployer"></span></div>
                                <div class="col-md-6"><span class="view-label">Designation</span><span class="view-value" id="vcDesignation"></span></div>
                            </div>
                            
                            <h6 class="section-header">Address Details</h6>
                            <div class="row">
                                <div class="col-md-6"><span class="view-label">Current Address</span><span class="view-value" id="vcCurr"></span></div>
                                <div class="col-md-6"><span class="view-label">Permanent Address</span><span class="view-value" id="vcPerm"></span></div>
                                <div class="col-md-3"><span class="view-label">City</span><span class="view-value" id="vcCity"></span></div>
                                <div class="col-md-3"><span class="view-label">State</span><span class="view-value" id="vcState"></span></div>
                                <div class="col-md-3"><span class="view-label">Country</span><span class="view-value" id="vcCountry"></span></div>
                                <div class="col-md-3"><span class="view-label">Pincode</span><span class="view-value" id="vcPin"></span></div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. COURSE DETAILS -->
                    <div class="tab-pane fade" id="content-course">
                        <div class="card border-0 shadow-sm p-4">
                            <h6 class="section-header">Program Selection</h6>
                            <div class="row">
                                <div class="col-md-4"><span class="view-label">Course Type</span><span class="view-value" id="vcrType"></span></div>
                                <div class="col-md-4"><span class="view-label">Faculty</span><span class="view-value" id="vcrFaculty"></span></div>
                                <div class="col-md-4"><span class="view-label">Course Name</span><span class="view-value" id="vcrName"></span></div>
                                <div class="col-md-4"><span class="view-label">Stream</span><span class="view-value" id="vcrStream"></span></div>
                                <div class="col-md-4"><span class="view-label">Session Year</span><span class="view-value" id="vcrSession"></span></div>
                                <div class="col-md-4"><span class="view-label">Hostel Required</span><span class="view-value" id="vcrHostel"></span></div>
                                <div class="col-md-4"><span class="view-label">Fee</span><span class="view-value" id="vcrFee"></span></div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. EDUCATION (JSON) -->
                    <div class="tab-pane fade" id="content-education">
                        <div class="card border-0 shadow-sm p-4">
                            <h6 class="section-header">Academic History</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Exam</th><th>Year</th><th>Board/Univ</th><th>Total</th><th>Obtained</th><th>Grade</th><th>Doc</th>
                                        </tr>
                                    </thead>
                                    <tbody id="eduTableBody">
                                        <!-- JS will populate -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- 5. UPLOADS -->
                    <div class="tab-pane fade" id="content-uploads">
                        <div class="card border-0 shadow-sm p-4">
                            <h6 class="section-header">Uploaded Documents</h6>
                            <div class="row g-4 text-center">
                                <div class="col-md-3">
                                    <p class="small fw-bold">Candidate Photo</p>
                                    <img id="uPhoto" src="" class="doc-preview" onclick="openFull(this.src)" onerror="this.src='../assets/img/default-doc.png'">
                                </div>
                                <div class="col-md-3">
                                    <p class="small fw-bold">Signature</p>
                                    <img id="uSig" src="" class="doc-preview" onclick="openFull(this.src)" onerror="this.src='../assets/img/default-doc.png'">
                                </div>
                                <div class="col-md-3">
                                    <p class="small fw-bold">Aadhaar Front</p>
                                    <img id="uAdF" src="" class="doc-preview" onclick="openFull(this.src)" onerror="this.src='../assets/img/default-doc.png'">
                                </div>
                                <div class="col-md-3">
                                    <p class="small fw-bold">Aadhaar Back</p>
                                    <img id="uAdB" src="" class="doc-preview" onclick="openFull(this.src)" onerror="this.src='../assets/img/default-doc.png'">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="modal-footer bg-white border-top-0">
                <button type="button" class="btn btn-secondary" onclick="viewModal.hide()">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var viewModal;
    document.addEventListener("DOMContentLoaded", function() {
        viewModal = new bootstrap.Modal(document.getElementById('viewModal'));
    });

    function viewApplication(data) {
        // --- 1. Populate Basic Fields ---
        document.getElementById('vTitle').innerText = 'Application: ' + data.CandidateName;
        document.getElementById('vSub').innerText = 'ID: #' + data.AdmissionId + ' | Applied: ' + data.AppliedAt;
        
        // Personal
        document.getElementById('vpName').innerText = data.CandidateName;
        document.getElementById('vpFather').innerText = data.FatherName;
        document.getElementById('vpMother').innerText = data.MotherName;
        document.getElementById('vpDob').innerText = data.DOB;
        document.getElementById('vpGender').innerText = data.Gender;
        document.getElementById('vpCat').innerText = data.Category;
        document.getElementById('vpEmp').innerText = data.IsEmployed;
        document.getElementById('vpAadhar').innerText = data.AadhaarNumber;

        // Contact
        document.getElementById('vcPhone').innerText = data.ContactNumber;
        document.getElementById('vcAlt').innerText = data.AlternateNumber;
        document.getElementById('vcEmail').innerText = data.Email;
        document.getElementById('vcEmployer').innerText = data.EmployerName;
        document.getElementById('vcDesignation').innerText = data.Designation;
        document.getElementById('vcCurr').innerText = data.CurrentAddress;
        document.getElementById('vcPerm').innerText = data.PermanentAddress;
        document.getElementById('vcCity').innerText = data.City;
        document.getElementById('vcState').innerText = data.State;
        document.getElementById('vcCountry').innerText = data.Country;
        document.getElementById('vcPin').innerText = data.Pincode;

        // Course
        document.getElementById('vcrType').innerText = data.CourseType;
        document.getElementById('vcrFaculty').innerText = data.Faculty;
        document.getElementById('vcrName').innerText = data.Course;
        document.getElementById('vcrStream').innerText = data.Stream;
        document.getElementById('vcrSession').innerText = data.Session + ' (' + data.MonthSession + ' ' + data.Year + ')';
        document.getElementById('vcrHostel').innerText = data.HostelFacility;
        document.getElementById('vcrFee').innerText = data.CourseFee;

        // --- 2. Populate Images ---
        const setImg = (id, path) => {
            const el = document.getElementById(id);
            if(path) {
                el.src = '../' + path; // Adjust path for admin view
                el.parentElement.style.display = 'block';
            } else {
                el.parentElement.style.display = 'none';
            }
        };
        setImg('uPhoto', data.PhotoPath);
        setImg('uSig', data.SignaturePath);
        setImg('uAdF', data.AadhaarFrontPath);
        setImg('uAdB', data.AadhaarBackPath);

        // --- 3. Populate Qualifications Table ---
        const eduBody = document.getElementById('eduTableBody');
        eduBody.innerHTML = ''; // Clear previous
        
        let quals = [];
        try { quals = JSON.parse(data.Qualifications); } catch(e){}

        if(quals && quals.length > 0) {
            quals.forEach(q => {
                // Only show rows that have data
                if(q.exam || q.year) {
                    let docLink = q.doc ? `<a href="../${q.doc}" target="_blank" class="btn btn-xs btn-outline-primary"><i class="bi bi-file-earmark-text"></i> View</a>` : '-';
                    let row = `
                        <tr>
                            <td class="fw-bold">${q.exam}</td>
                            <td>${q.year}</td>
                            <td>${q.board}</td>
                            <td>${q.total}</td>
                            <td>${q.obtained}</td>
                            <td>${q.grade}</td>
                            <td>${docLink}</td>
                        </tr>
                    `;
                    eduBody.innerHTML += row;
                }
            });
        } else {
            eduBody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No qualification details provided.</td></tr>';
        }

        viewModal.show();
    }

    function openFull(src) {
        window.open(src, '_blank');
    }
</script>

<?php require_once 'includes/footer.php'; ?>
</body>
</html>