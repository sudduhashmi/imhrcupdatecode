<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Check if student is logged in
if (!isset($_SESSION['student_id']) || $_SESSION['user_type'] !== 'student') {
    header('Location: login.php');
    exit;
}

$student = [
    'Name' => $_SESSION['student_name'] ?? 'Student',
    'Email' => $_SESSION['student_email'] ?? ''
];

// Get student statistics
$stats = [];
try {
    global $conn;
    $studentId = $_SESSION['student_id'];
    
    // Enrolled courses count
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM enrollments WHERE StudentID = ?");
    $stmt->bind_param('i', $studentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $enrolledCount = $result->fetch_assoc();
    $stats['enrolled'] = $enrolledCount['count'] ?? 0;
    
    // Completed courses
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM enrollments WHERE StudentID = ? AND status = 'completed'");
    $stmt->bind_param('i', $studentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $completedCount = $result->fetch_assoc();
    $stats['completed'] = $completedCount['count'] ?? 0;
    
    // Certificates
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM certificates WHERE StudentID = ?");
    $stmt->bind_param('i', $studentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $certCount = $result->fetch_assoc();
    $stats['certificates'] = $certCount['count'] ?? 0;
    
} catch (Exception $e) {
    // Tables might not exist
}

$pageTitle = 'Student Dashboard - ' . APP_NAME;
?>
<?php require_once 'includes/head.php'; ?>

<style>
    .student-dashboard {
        background: #f8f9fa;
        min-height: 100vh;
        padding: 20px;
    }
    .dashboard-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s;
        margin-bottom: 20px;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        margin-bottom: 15px;
    }
    .bg-primary-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .bg-success-gradient {
        background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    }
    .bg-warning-gradient {
        background: linear-gradient(135deg, #f6ad55 0%, #ed8936 100%);
    }
    .bg-info-gradient {
        background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
    }
    .quick-link-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s;
        text-decoration: none;
        display: block;
        margin-bottom: 20px;
    }
    .quick-link-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    .logout-btn {
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid white;
        color: white;
        padding: 10px 25px;
        border-radius: 8px;
        transition: all 0.3s;
    }
    .logout-btn:hover {
        background: white;
        color: #667eea;
    }
</style>

<div class="student-dashboard">
    <div class="container">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="mb-2">
                        <i class="ti-user"></i> Welcome, <?php echo htmlspecialchars($student['Name']); ?>!
                    </h2>
                    <p class="mb-0 opacity-75">
                        <i class="ti-email"></i> <?php echo htmlspecialchars($student['Email']); ?>
                    </p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="logout.php" class="btn logout-btn">
                        <i class="ti-power-off"></i> Logout
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Row -->
        <div class="row">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon bg-primary-gradient">
                        <i class="ti-book"></i>
                    </div>
                    <h3 class="mb-1"><?php echo $stats['enrolled'] ?? 0; ?></h3>
                    <p class="text-muted mb-0">Enrolled Courses</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon bg-success-gradient">
                        <i class="ti-check-box"></i>
                    </div>
                    <h3 class="mb-1"><?php echo $stats['completed'] ?? 0; ?></h3>
                    <p class="text-muted mb-0">Completed</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon bg-warning-gradient">
                        <i class="ti-medall"></i>
                    </div>
                    <h3 class="mb-1"><?php echo $stats['certificates'] ?? 0; ?></h3>
                    <p class="text-muted mb-0">Certificates</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon bg-info-gradient">
                        <i class="ti-calendar"></i>
                    </div>
                    <h3 class="mb-1"><?php echo date('d M'); ?></h3>
                    <p class="text-muted mb-0">Today's Date</p>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="row mt-4">
            <div class="col-12">
                <h4 class="mb-3"><i class="ti-layout-grid2"></i> Quick Access</h4>
            </div>
            <div class="col-md-3">
                <a href="#" class="quick-link-card">
                    <i class="ti-book" style="font-size: 40px; color: #667eea;"></i>
                    <h6 class="mt-3 mb-0">My Courses</h6>
                </a>
            </div>
            <div class="col-md-3">
                <a href="#" class="quick-link-card">
                    <i class="ti-medall" style="font-size: 40px; color: #48bb78;"></i>
                    <h6 class="mt-3 mb-0">Certificates</h6>
                </a>
            </div>
            <div class="col-md-3">
                <a href="#" class="quick-link-card">
                    <i class="ti-user" style="font-size: 40px; color: #f6ad55;"></i>
                    <h6 class="mt-3 mb-0">My Profile</h6>
                </a>
            </div>
            <div class="col-md-3">
                <a href="#" class="quick-link-card">
                    <i class="ti-help-alt" style="font-size: 40px; color: #4299e1;"></i>
                    <h6 class="mt-3 mb-0">Support</h6>
                </a>
            </div>
        </div>

        <!-- Information Cards -->
        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="ti-announcement"></i> Announcements</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <strong>Welcome to IMHRC Student Portal!</strong><br>
                            Access your courses, certificates, and resources from this dashboard.
                        </div>
                        <p class="text-muted">No new announcements at this time.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="ti-info-alt"></i> Account Info</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Name:</strong><br><?php echo htmlspecialchars($student['Name']); ?></p>
                        <p><strong>Email:</strong><br><?php echo htmlspecialchars($student['Email']); ?></p>
                        <p><strong>Role:</strong><br><span class="badge bg-success">Student</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
