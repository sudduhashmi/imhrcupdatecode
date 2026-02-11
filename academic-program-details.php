<?php
// --- CONFIGURATION ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Increase memory limit to prevent "Allowed memory size exhausted" error on large text fields
ini_set('memory_limit', '512M');

require_once 'admin/includes/db.php'; 

// --- AUTO-FIX DATABASE SCHEMA ---
try {
    $checkCol = $conn->query("SHOW COLUMNS FROM academic_programs LIKE 'Details'");
    if($checkCol && $checkCol->num_rows == 0) {
        $conn->query("ALTER TABLE academic_programs ADD COLUMN Details JSON AFTER Fee");
    }
} catch (Exception $e) { }

$program = null;
$progId = 0;

// --- FETCH DATA LOGIC ---
// 1. Check if accessed via Course Name (Slug)
if (isset($_GET['course'])) {
    $slug = urldecode($_GET['course']);
    $titleSearch = str_replace('-', ' ', $slug); // Convert "Clinical-Psychology" to "Clinical Psychology"
    
    // Use LIKE to find approximate title match
    $stmt = $conn->prepare("SELECT ProgramId, Title, Description, Icon, Fee, Link, ColorClass, Details FROM academic_programs WHERE Title LIKE ?");
    if ($stmt) {
        $likeParam = "%" . $titleSearch . "%";
        $stmt->bind_param("s", $likeParam);
        $stmt->execute();
        $stmt->store_result(); // Fix for memory issues
        $stmt->bind_result($pId, $title, $desc, $iconPath, $feeAmount, $linkUrl, $colorClass, $detailsJson);
        
        if ($stmt->fetch()) {
            $progId = $pId;
            $program = [
                'Title' => $title,
                'Description' => $desc,
                'Icon' => $iconPath,
                'Fee' => $feeAmount,
                'Link' => $linkUrl,
                'ColorClass' => $colorClass,
                'Details' => json_decode($detailsJson ?? '', true)
            ];
        }
        $stmt->close();
    }
} 
// 2. Fallback: Check if accessed via ID
elseif (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT ProgramId, Title, Description, Icon, Fee, Link, ColorClass, Details FROM academic_programs WHERE ProgramId = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->store_result(); // Fix for memory issues
        $stmt->bind_result($pId, $title, $desc, $iconPath, $feeAmount, $linkUrl, $colorClass, $detailsJson);
        
        if ($stmt->fetch()) {
            $progId = $pId;
            $program = [
                'Title' => $title,
                'Description' => $desc,
                'Icon' => $iconPath,
                'Fee' => $feeAmount,
                'Link' => $linkUrl,
                'ColorClass' => $colorClass,
                'Details' => json_decode($detailsJson ?? '', true)
            ];
        }
        $stmt->close();
    }
}

// Redirect if not found
if(!$program) {
    echo "<script>alert('Program not found.'); window.location.href='academic-programs.php';</script>";
    exit;
}

// Format Data for Display
$icon = !empty($program['Icon']) ? str_replace('../', '', $program['Icon']) : 'assets/img/logo.png';
$fee = ($program['Fee'] > 0) ? '₹' . number_format($program['Fee']) : 'Contact Us';

// Extract Extra Details or set Defaults
$duration = $program['Details']['duration'] ?? 'Varies';
$mode = $program['Details']['mode'] ?? 'Offline / Online';
$features = $program['Details']['features'] ?? [
    "Comprehensive understanding of core concepts.",
    "Practical skills in assessment and intervention.",
    "Research methodology and ethical practices.",
    "Exposure to real-world clinical case studies."
];
?>
<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo htmlspecialchars($program['Title']); ?> - IMHRC</title>
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/img/logo.png">
    
    <style>
        .prog-header { background: linear-gradient(135deg, #0b2c57 0%, #164e94 100%); padding: 80px 0; color: white; }
        .sidebar-card { background: #fff; border-radius: 12px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); position: sticky; top: 20px; }
        .feature-list li { margin-bottom: 12px; display: flex; align-items: start; }
        .feature-list i { color: #f6b400; margin-right: 10px; margin-top: 3px; }
        .btn-warning { background-color: #f6b400; border-color: #f6b400; color: #000; }
        .btn-warning:hover { background-color: #e0a800; border-color: #e0a800; }
    </style>
</head>
<body>

<?php include 'includes/header.php'; ?>

<!-- Header -->
<header class="prog-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-warning text-dark mb-2">Academic Program</span>
                <h1 class="fw-bold"><?php echo htmlspecialchars($program['Title']); ?></h1>
                <p class="lead opacity-75">Offered by Indian Mental Health and Research Centre</p>
            </div>
            <div class="col-lg-4 text-center">
                <img src="<?php echo $icon; ?>" style="width: 80px; height: 80px; object-fit: contain; background: rgba(255,255,255,0.1); border-radius: 50%; padding: 10px;" onerror="this.src='assets/img/logo.png'">
            </div>
        </div>
    </div>
</header>

<!-- Main Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            
            <!-- Left Content -->
            <div class="col-lg-8">
                <div class="mb-5">
                    <h3 class="fw-bold mb-3 text-dark">Program Overview</h3>
                    <p class="text-secondary" style="line-height: 1.8; font-size: 1.05rem;">
                        <?php echo nl2br(htmlspecialchars($program['Description'])); ?>
                    </p>
                </div>

                <div class="mb-5">
                    <h4 class="fw-bold mb-3">What You Will Learn</h4>
                    <ul class="list-unstyled feature-list text-secondary">
                        <?php if(!empty($features)): ?>
                            <?php foreach($features as $feat): ?>
                                <li><i class="bi bi-check-circle-fill"></i> <?php echo htmlspecialchars($feat); ?></li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li><i class="bi bi-info-circle"></i> Curriculum details available in brochure.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="col-lg-4">
                <div class="sidebar-card">
                    <h5 class="fw-bold mb-4">Program Details</h5>
                    
                    <div class="mb-3 d-flex justify-content-between border-bottom pb-2">
                        <span class="text-muted">Fees</span>
                        <span class="fw-bold text-success"><?php echo $fee; ?></span>
                    </div>
                    <div class="mb-3 d-flex justify-content-between border-bottom pb-2">
                        <span class="text-muted">Duration</span>
                        <span class="fw-bold text-dark"><?php echo htmlspecialchars($duration); ?></span>
                    </div>
                    <div class="mb-4 d-flex justify-content-between border-bottom pb-2">
                        <span class="text-muted">Mode</span>
                        <span class="fw-bold text-dark"><?php echo htmlspecialchars($mode); ?></span>
                    </div>

                    <a href="admission-form.php?program=<?php echo $progId; ?>" class="btn btn-warning w-100 rounded-pill fw-bold py-2 mb-2">Enroll Now</a>
                    <button class="btn btn-outline-dark w-100 rounded-pill py-2" onclick="alert('Brochure download coming soon!')"><i class="bi bi-download me-2"></i> Download Brochure</button>
                </div>
            </div>

        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/meanmenu.min.js"></script>
<script src="assets/js/custom.js"></script>
</body>
</html>