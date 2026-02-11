<?php
// --- DEBUGGING: Enable Error Reporting ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Check DB Connection Path
$dbPath = 'admin/includes/db.php';
if (file_exists($dbPath)) {
    require_once $dbPath;
} else {
    die("Error: Database file not found.");
}

// --- CHECK LOGIN ---
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$role = $_SESSION['user_role'] ?? 'customer'; 
$userEmail = $_SESSION['user_email'] ?? '';

// --- HELPER FUNCTION FOR COMPATIBILITY (Fixes get_result error) ---
function fetch_stmt_all($stmt) {
    $hits = [];
    $params = [];
    $row = [];
    $meta = $stmt->result_metadata();
    while ($field = $meta->fetch_field()) {
        $params[] = &$row[$field->name];
    }
    call_user_func_array(array($stmt, 'bind_result'), $params);
    while ($stmt->fetch()) {
        $c = [];
        foreach ($row as $key => $val) {
            $c[$key] = $val;
        }
        $hits[] = $c;
    }
    return $hits;
}

// --- FETCH USER DETAILS ---
$user = [];
$stmt = $conn->prepare("SELECT Name, Email, Phone, Address, City, State, Role FROM userlogin WHERE UserId = ?");
if ($stmt) {
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($uName, $uEmail, $uPhone, $uAddress, $uCity, $uState, $uRole);
    if ($stmt->fetch()) {
        $user = [
            'Name' => $uName,
            'Email' => $uEmail,
            'Phone' => $uPhone,
            'Address' => $uAddress,
            'City' => $uCity,
            'State' => $uState,
            'Role' => $uRole
        ];
        // Update session email/role just in case
        $_SESSION['user_email'] = $uEmail;
        $role = $uRole; 
    } else {
        session_destroy();
        header("Location: login.php");
        exit;
    }
    $stmt->close();
} else {
    die("User Query Failed: " . $conn->error);
}

// --- FETCH DATA BASED ON ROLE ---
$appointments = [];
$orders = [];
$proposals = [];
$enrolledCourses = []; // For students/customers

if ($role === 'patient') {
    // Fetch Appointments
    // Checking if tables exist handled by SQL script, assuming they exist now
    $stmt = $conn->prepare("
        SELECT a.*, e.Name as ExpertName, e.Designation 
        FROM appointments a 
        LEFT JOIN experts e ON a.expert_id = e.ExpertId 
        WHERE a.email = ? 
        ORDER BY a.appointment_date DESC
    ");
    if ($stmt) {
        $stmt->bind_param("s", $userEmail);
        $stmt->execute();
        $stmt->store_result();
        $appointments = fetch_stmt_all($stmt);
        $stmt->close();
    }
} else {
    // --- STUDENT / CUSTOMER VIEW ---

    // 1. Fetch Orders (Purchased Books/Tools)
    $stmt = $conn->prepare("
        SELECT o.*, p.Title as PubTitle, p.CoverImage 
        FROM publication_orders o
        LEFT JOIN publications p ON o.PubId = p.PubId
        WHERE o.CustomerEmail = ? 
        ORDER BY o.OrderDate DESC
    ");
    if ($stmt) {
        $stmt->bind_param("s", $userEmail);
        $stmt->execute();
        $stmt->store_result();
        $orders = fetch_stmt_all($stmt);
        $stmt->close();
    }

    // 2. Fetch Book Proposals
    $stmt = $conn->prepare("SELECT * FROM book_proposals WHERE Email = ? ORDER BY SubmittedAt DESC");
    if ($stmt) {
        $stmt->bind_param("s", $userEmail);
        $stmt->execute();
        $stmt->store_result();
        $proposals = fetch_stmt_all($stmt);
        $stmt->close();
    }

    // 3. Fetch Academic Programs (Simulating "My Learning" / Available Courses)
    // Since there is no 'enrollments' table in the provided schema, 
    // we fetch active programs to show in the "My Learning" tab as available/suggested courses.
    $stmt = $conn->prepare("SELECT * FROM academic_programs WHERE Status='active' ORDER BY DisplayOrder ASC LIMIT 6");
    if ($stmt) {
        $stmt->execute();
        $stmt->store_result();
        $enrolledCourses = fetch_stmt_all($stmt);
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap Min CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Owl Theme Default Min CSS -->
    <link rel="stylesheet" href="assets/css/owl.theme.default.min.css">
    <!-- Owl Carousel Min CSS -->
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <!-- Animate Min CSS -->
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <!-- Boxicons Min CSS -->
    <link rel="stylesheet" href="assets/css/boxicons.min.css">
    <!-- Magnific Popup Min CSS -->
    <link rel="stylesheet" href="assets/css/magnific-popup.min.css">
    <!-- Flaticon CSS -->
    <link rel="stylesheet" href="assets/css/flaticon.css">
    <!-- Meanmenu Min CSS -->
    <link rel="stylesheet" href="assets/css/meanmenu.min.css">

    <!-- Odometer Min CSS-->
    <link rel="stylesheet" href="assets/css/odometer.min.css">
    <!-- Style CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Dark CSS -->
    <link rel="stylesheet" href="assets/css/dark.css">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="assets/css/responsive.css">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/img/logo.png">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Title -->
    <title>INDIAN MENTAL HEALTH AND RESEARCH CENTRE (IMHRC)</title>

    <style>
        body { background-color: #f3f4f6; font-family: 'Inter', sans-serif; }
        
        /* Header */
        .dashboard-header { 
            background: linear-gradient(135deg, #0b2c57 0%, #164e94 100%); 
            color: white; 
            padding-top: 60px;
            padding-bottom: 100px; 
            position: relative;
        }
        .user-avatar { 
            width: 90px; height: 90px; 
            background: #fff; color: #0b2c57; 
            font-size: 36px; font-weight: bold;
            display: flex; align-items: center; justify-content: center; 
            border-radius: 50%; border: 4px solid rgba(255,255,255,0.3); 
        }

        /* Container adjustment to overlap header */
        .dashboard-container { margin-top: -60px; }
        
        /* Sidebar & Cards */
        .sidebar-card { border: none; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); overflow: hidden; background: #fff; }
        .content-card { border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); margin-bottom: 20px; overflow: hidden; background: #fff; }
        .card-header-custom { background: #fff; padding: 20px 25px; border-bottom: 1px solid #f0f0f0; }

        /* Nav Pills */
        .nav-pills .nav-link { 
            color: #555; font-weight: 600; padding: 14px 20px; 
            border-radius: 10px; margin-bottom: 8px; transition: all 0.3s; 
            display: flex; align-items: center;
        }
        .nav-pills .nav-link i { font-size: 1.2rem; margin-right: 12px; }
        .nav-pills .nav-link:hover { background-color: #f8f9fa; color: #0b2c57; transform: translateX(5px); }
        .nav-pills .nav-link.active { background-color: #0b2c57; color: white; box-shadow: 0 4px 15px rgba(11,44,87,0.3); }

        /* Stat Cards */
        .stat-card { transition: transform 0.3s; border: none; border-radius: 16px; overflow: hidden; }
        .stat-card:hover { transform: translateY(-5px); }

        /* Course Card */
        .course-card { 
            border: 1px solid #eee; border-radius: 12px; overflow: hidden; 
            transition: 0.3s; background: #fff; height: 100%;
        }
        .course-card:hover { box-shadow: 0 10px 25px rgba(0,0,0,0.08); border-color: transparent; }
        .course-thumb { height: 160px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .course-thumb img { width: 100%; height: 100%; object-fit: contain; padding: 20px; }
        
        /* Progress Bar (Mockup) */
        .progress-bar-custom { height: 6px; border-radius: 10px; background-color: #e9ecef; overflow: hidden; margin: 15px 0; }
        .progress-fill { height: 100%; background: #22c55e; border-radius: 10px; }

        /* Badges */
        .status-badge { padding: 6px 12px; border-radius: 30px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .bg-pending { background-color: #fff7ed; color: #c2410c; }
        .bg-confirmed, .bg-success { background-color: #f0fdf4; color: #15803d; }
        .bg-cancelled, .bg-failed { background-color: #fef2f2; color: #b91c1c; }
        .bg-completed { background-color: #eff6ff; color: #1d4ed8; }
    </style>
</head>
<body>

    <?php include 'includes/header.php'; ?>

    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="container">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <div class="user-avatar shadow">
                    <?php echo strtoupper(substr($user['Name'] ?? 'U', 0, 1)); ?>
                </div>
                <div>
                    <h2 class="fw-bold mb-1">Hello, <?php echo htmlspecialchars($user['Name']); ?>!</h2>
                    <p class="opacity-75 mb-0 d-flex align-items-center gap-3">
                        <span><i class="bi bi-envelope"></i> <?php echo htmlspecialchars($user['Email']); ?></span>
                        <span class="badge bg-warning text-dark border border-white"><?php echo ucfirst($role); ?> Account</span>
                    </p>
                </div>
                <div class="ms-auto">
                    <a href="admin/logout.php" class="btn btn-light rounded-pill px-4 fw-bold shadow-sm">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container dashboard-container mb-5">
        <div class="row">
            <!-- Sidebar Navigation -->
            <div class="col-lg-3 mb-4">
                <div class="sidebar-card card p-3">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist">
                        <button class="nav-link active" id="tab-dashboard" data-bs-toggle="pill" data-bs-target="#content-dashboard" type="button">
                            <i class="bi bi-grid-fill"></i> Dashboard
                        </button>
                        
                        <?php if($role === 'patient'): ?>
                        <button class="nav-link" id="tab-appointments" data-bs-toggle="pill" data-bs-target="#content-appointments" type="button">
                            <i class="bi bi-calendar-check-fill"></i> My Appointments
                        </button>
                        <?php else: ?>
                        <!-- Student / Customer Tabs -->
                        <button class="nav-link" id="tab-learning" data-bs-toggle="pill" data-bs-target="#content-learning" type="button">
                            <i class="bi bi-mortarboard-fill"></i> My Learning
                        </button>
                        <button class="nav-link" id="tab-orders" data-bs-toggle="pill" data-bs-target="#content-orders" type="button">
                            <i class="bi bi-bag-check-fill"></i> My Orders
                        </button>
                        <button class="nav-link" id="tab-proposals" data-bs-toggle="pill" data-bs-target="#content-proposals" type="button">
                            <i class="bi bi-file-earmark-text-fill"></i> Book Proposals
                        </button>
                        <?php endif; ?>

                        <button class="nav-link" id="tab-profile" data-bs-toggle="pill" data-bs-target="#content-profile" type="button">
                            <i class="bi bi-person-badge-fill"></i> Profile Settings
                        </button>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="col-lg-9">
                <div class="tab-content" id="v-pills-tabContent">
                    
                    <!-- 1. DASHBOARD OVERVIEW -->
                    <div class="tab-pane fade show active" id="content-dashboard">
                        <div class="row g-4 mb-4">
                            <?php if($role === 'patient'): ?>
                                <div class="col-md-6">
                                    <div class="stat-card card p-4 h-100 bg-white border-start border-5 border-primary shadow-sm">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-muted fw-bold mb-1">TOTAL APPOINTMENTS</h6>
                                                <h2 class="mb-0 fw-bold text-primary"><?php echo count($appointments); ?></h2>
                                            </div>
                                            <div class="bg-light p-3 rounded-circle text-primary"><i class="bi bi-calendar-event fs-3"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="stat-card card p-4 h-100 text-white shadow-sm" style="background: linear-gradient(135deg, #0b2c57, #2563eb);">
                                        <h4 class="fw-bold mb-2">Book a Session</h4>
                                        <p class="small opacity-75 mb-3">Consult with top mental health experts online.</p>
                                        <a href="book-appointment.php" class="btn btn-light rounded-pill fw-bold text-primary w-100">Book Now <i class="bi bi-arrow-right"></i></a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="col-md-4">
                                    <div class="stat-card card p-4 h-100 border-start border-5 border-success shadow-sm">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-muted fw-bold mb-1">COURSES</h6>
                                                <h2 class="mb-0 text-success fw-bold"><?php echo count($enrolledCourses); ?></h2>
                                            </div>
                                            <div class="bg-light p-3 rounded-circle text-success"><i class="bi bi-mortarboard fs-3"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="stat-card card p-4 h-100 border-start border-5 border-info shadow-sm">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-muted fw-bold mb-1">PURCHASES</h6>
                                                <h2 class="mb-0 text-info fw-bold"><?php echo count($orders); ?></h2>
                                            </div>
                                            <div class="bg-light p-3 rounded-circle text-info"><i class="bi bi-bag-check fs-3"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="stat-card card p-4 h-100 border-start border-5 border-warning shadow-sm">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-muted fw-bold mb-1">PROPOSALS</h6>
                                                <h2 class="mb-0 text-warning fw-bold"><?php echo count($proposals); ?></h2>
                                            </div>
                                            <div class="bg-light p-3 rounded-circle text-warning"><i class="bi bi-file-text fs-3"></i></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="content-card card bg-white">
                            <div class="card-body p-5 text-center">
                                <h4 class="fw-bold text-dark">Welcome to your dashboard</h4>
                                <p class="text-muted">Manage your activities, view history and update your profile from here.</p>
                            </div>
                        </div>
                    </div>

                    <!-- 2. PATIENT: APPOINTMENTS -->
                    <?php if($role === 'patient'): ?>
                    <div class="tab-pane fade" id="content-appointments">
                        <div class="content-card card">
                            <div class="card-header-custom d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold">Appointment History</h5>
                                <a href="book-appointment.php" class="btn btn-sm btn-primary rounded-pill px-3"><i class="bi bi-plus-lg"></i> Book New</a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4">Date</th>
                                            <th>Doctor / Expert</th>
                                            <th>Mode</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(empty($appointments)): ?>
                                            <tr><td colspan="4" class="text-center py-5 text-muted">No appointments found.</td></tr>
                                        <?php else: ?>
                                            <?php foreach($appointments as $app): ?>
                                            <tr>
                                                <td class="ps-4">
                                                    <span class="fw-bold d-block text-dark"><?php echo date('d M, Y', strtotime($app['appointment_date'])); ?></span>
                                                </td>
                                                <td>
                                                    <?php if($app['ExpertName']): ?>
                                                        <span class="fw-bold text-dark">Dr. <?php echo htmlspecialchars($app['ExpertName']); ?></span><br>
                                                        <small class="text-muted"><?php echo htmlspecialchars($app['Designation']); ?></small>
                                                    <?php else: ?>
                                                        General Consultation
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                        $isOnline = strpos(strtolower($app['message']), 'online') !== false;
                                                        echo $isOnline ? '<span class="text-primary"><i class="bi bi-camera-video-fill"></i> Online</span>' : '<span class="text-danger"><i class="bi bi-hospital-fill"></i> Offline</span>'; 
                                                    ?>
                                                </td>
                                                <td>
                                                    <span class="status-badge bg-<?php echo $app['status']; ?>">
                                                        <?php echo ucfirst($app['status']); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- 3. CUSTOMER: MY LEARNING (NEW) -->
                    <?php if($role !== 'patient'): ?>
                    <div class="tab-pane fade" id="content-learning">
                        <!-- Live Class Alert -->
                        <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
                            <div class="bg-white p-2 rounded-circle text-info me-3"><i class="bi bi-broadcast fs-4"></i></div>
                            <div>
                                <strong class="d-block">Upcoming Live Session: "Mental Health in Digital Age"</strong>
                                <small>Starts tomorrow at 10:00 AM via Zoom. Check your email for the link.</small>
                            </div>
                        </div>

                        <h5 class="fw-bold mb-3">Available Courses</h5>
                        <div class="row g-4">
                            <?php if(!empty($enrolledCourses)): foreach($enrolledCourses as $course): ?>
                            <div class="col-md-6">
                                <div class="course-card h-100 d-flex flex-column">
                                    <div class="course-thumb">
                                        <img src="<?php echo !empty($course['Icon']) ? str_replace('../','',$course['Icon']) : 'assets/img/logo.png'; ?>" onerror="this.src='assets/img/logo.png'">
                                    </div>
                                    <div class="p-3 d-flex flex-column flex-grow-1">
                                        <span class="badge bg-light text-dark border mb-2 align-self-start">Certificate</span>
                                        <h6 class="fw-bold mb-2"><?php echo htmlspecialchars($course['Title']); ?></h6>
                                        <p class="small text-muted mb-2 text-truncate"><?php echo htmlspecialchars($course['Description']); ?></p>
                                        
                                        <!-- Mock Progress (Since enrollment table logic isn't built yet) -->
                                        <div class="mt-auto">
                                            <div class="d-flex justify-content-between small mb-1">
                                                <span>Progress</span>
                                                <span class="fw-bold">0%</span>
                                            </div>
                                            <div class="progress-bar-custom">
                                                <div class="progress-fill" style="width: 0%;"></div>
                                            </div>
                                            <a href="<?php echo htmlspecialchars($course['Link']); ?>" class="btn btn-primary btn-sm w-100 rounded-pill mt-2">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; else: ?>
                                <div class="col-12 text-center py-5">
                                    <img src="assets/img/logo.png" width="60" class="mb-3 opacity-50">
                                    <h6 class="text-muted">No courses available at the moment.</h6>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- ORDERS -->
                    <div class="tab-pane fade" id="content-orders">
                        <div class="content-card card mb-4">
                            <div class="card-header-custom">
                                <h5 class="mb-0 fw-bold">Order History</h5>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4">Item</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(empty($orders)): ?>
                                            <tr><td colspan="4" class="text-center py-5 text-muted">No orders found.</td></tr>
                                        <?php else: ?>
                                            <?php foreach($orders as $ord): ?>
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center">
                                                        <?php if(!empty($ord['CoverImage'])): ?>
                                                            <img src="<?php echo str_replace('../', '', $ord['CoverImage']); ?>" width="40" class="rounded me-2 border">
                                                        <?php else: ?>
                                                            <div class="bg-light rounded p-2 me-2 text-secondary"><i class="bi bi-book"></i></div>
                                                        <?php endif; ?>
                                                        <span class="fw-bold text-dark"><?php echo htmlspecialchars($ord['PubTitle'] ?? 'Item Deleted'); ?></span>
                                                    </div>
                                                </td>
                                                <td><?php echo date('d M, Y', strtotime($ord['OrderDate'])); ?></td>
                                                <td>₹<?php echo number_format($ord['Amount']); ?></td>
                                                <td><span class="status-badge bg-<?php echo $ord['PaymentStatus']=='success'?'confirmed':'failed'; ?>"><?php echo ucfirst($ord['PaymentStatus']); ?></span></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- PROPOSALS -->
                    <div class="tab-pane fade" id="content-proposals">
                         <div class="content-card card">
                            <div class="card-header-custom">
                                <h5 class="mb-0 fw-bold">My Book Proposals</h5>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4">Book Title</th>
                                            <th>Genre</th>
                                            <th>Submitted On</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(empty($proposals)): ?>
                                            <tr><td colspan="3" class="text-center py-5 text-muted">No proposals submitted.</td></tr>
                                        <?php else: ?>
                                            <?php foreach($proposals as $prop): ?>
                                            <tr>
                                                <td class="ps-4 fw-bold text-primary"><?php echo htmlspecialchars($prop['BookTitle']); ?></td>
                                                <td><?php echo htmlspecialchars($prop['Genre']); ?></td>
                                                <td><?php echo date('d M, Y', strtotime($prop['SubmittedAt'])); ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- 5. PROFILE SETTINGS -->
                    <div class="tab-pane fade" id="content-profile">
                        <div class="content-card card p-4">
                            <h5 class="fw-bold mb-4 border-bottom pb-2">Profile Details</h5>
                            <form>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small fw-bold">Full Name</label>
                                        <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($user['Name']); ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small fw-bold">Email Address</label>
                                        <input type="email" class="form-control bg-light" value="<?php echo htmlspecialchars($user['Email']); ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small fw-bold">Phone Number</label>
                                        <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($user['Phone']); ?>" readonly>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label text-muted small fw-bold">Address</label>
                                        <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($user['Address'] ?? ''); ?>" readonly>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label text-muted small fw-bold">City</label>
                                        <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($user['City'] ?? ''); ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small fw-bold">State</label>
                                        <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($user['State'] ?? ''); ?>" readonly>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button type="button" class="btn btn-outline-secondary disabled">To Edit Profile, Contact Support</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>