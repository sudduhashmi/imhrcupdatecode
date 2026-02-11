<?php
// Include database connection
// Assuming this file is in the root directory and db.php is in admin/includes/
require_once 'admin/includes/db.php'; 

// --- 1. FETCH CATEGORIES (Dynamic Dropdown) ---
$catSql = "SELECT * FROM expertise_categories ORDER BY name ASC";
$categories = [];
try {
    $catResult = $conn->query($catSql);
    if ($catResult) {
        // Compatibility check for fetch_all
        if (method_exists($catResult, 'fetch_all')) {
            $categories = $catResult->fetch_all(MYSQLI_ASSOC);
        } else {
            while ($row = $catResult->fetch_assoc()) {
                $categories[] = $row;
            }
        }
    }
} catch (Exception $e) { $categories = []; }

// --- 2. SEARCH & FILTER ---
$expertiseFilter = $_GET['expertise'] ?? '';
$searchQuery = $_GET['search'] ?? '';

// Build SQL Query
$sql = "SELECT * FROM experts WHERE Status = 'active'";
$params = [];
$types = "";

if (!empty($expertiseFilter) && $expertiseFilter != 'All Expertise') {
    $sql .= " AND Expertise LIKE ?";
    $params[] = "%" . $expertiseFilter . "%";
    $types .= "s";
}

if (!empty($searchQuery)) {
    $sql .= " AND (Name LIKE ? OR Designation LIKE ? OR Expertise LIKE ?)";
    $term = "%" . $searchQuery . "%";
    $params[] = $term;
    $params[] = $term;
    $params[] = $term;
    $types .= "sss";
}

$sql .= " ORDER BY CreatedAt DESC";

// Execute Query Safely
$experts = [];
$stmt = $conn->prepare($sql);
if ($stmt) {
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    
    // Check if get_result is available
    if (method_exists($stmt, 'get_result')) {
        $result = $stmt->get_result();
        if ($result) {
            $experts = $result->fetch_all(MYSQLI_ASSOC);
        }
    } else {
        // Fallback for older PHP versions
        $stmt->store_result();
        $meta = $stmt->result_metadata();
        $bindVars = [];
        $row = [];
        while ($field = $meta->fetch_field()) {
            $bindVars[] = &$row[$field->name];
        }
        call_user_func_array([$stmt, 'bind_result'], $bindVars);
        while ($stmt->fetch()) {
            $item = [];
            foreach ($row as $k => $v) { $item[$k] = $v; }
            $experts[] = $item;
        }
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Assets -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/boxicons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="icon" type="image/png" href="assets/img/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

    <title>Clinical Experts - IMHRC</title>
    
    <style>
        :root {
            --primary-color: #bfa15f;       /* Elegant Gold */
            --dark-blue: #0a1f33;          /* Deep Royal Navy */
            --bg-light: #f9fbfd;           /* Crisp Off-White */
            --text-heading: #111827;       
        }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-light); color: #333; }

        /* Header */
        .page-title-wave {
            background: linear-gradient(135deg, #0a1f33 0%, #1c3d5a 100%);
            padding-top: 120px; padding-bottom: 80px; text-align: center; color: white;
            position: relative;
        }
        .page-title-wave h2 { font-family: 'Playfair Display', serif; }

        /* Filters */
        .stylish-filter {
            background: #fff; padding: 25px; border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.06); display: flex;
            gap: 20px; flex-wrap: wrap; margin-bottom: 50px;
            margin-top: -40px; position: relative; z-index: 10; align-items: center;
        }
        .field { flex: 1; min-width: 200px; }
        .field select, .field input {
            width: 100%; padding: 14px 16px; border: 1px solid #e0e0e0;
            border-radius: 10px; outline: none; font-size: 0.95rem;
            color: #444; background-color: #fcfcfc; transition: border 0.3s;
        }
        .field select:focus, .field input:focus { border-color: var(--primary-color); background: #fff; }
        .apply-btn {
            width: 100%; padding: 14px; background: var(--dark-blue); color: #fff;
            border: none; border-radius: 10px; font-weight: 600; cursor: pointer;
            transition: 0.3s; font-size: 1rem;
        }
        .apply-btn:hover { background: var(--primary-color); }
        .btn-reset { display: block; text-align: center; color: #666; margin-top: 5px; font-size: 0.9rem; text-decoration: underline; }

        /* Expert Cards */
        .experts-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px; max-width: 1200px; margin: auto; padding: 0 20px;
        }
        .expert-card {
            background: #fff; border-radius: 16px; overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.06); transition: .35s;
            display: flex; flex-direction: column; height: 100%; border: 1px solid #f0f0f0;
        }
        .expert-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(10, 31, 51, 0.15); }
        
        .expert-img { height: 280px; overflow: hidden; position: relative; }
        .expert-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
        .expert-card:hover .expert-img img { transform: scale(1.05); }

        .expert-body { padding: 25px; text-align: center; flex-grow: 1; }
        .expert-body h3 { font-family: 'Playfair Display', serif; font-size: 1.5rem; margin-bottom: 5px; color: var(--text-heading); }
        .designation { color: var(--primary-color); font-size: 0.9rem; font-weight: 600; text-transform: uppercase; margin-bottom: 10px; display: block; }
        .qualification { font-size: 0.9rem; color: #666; margin-bottom: 15px; font-style: italic; }

        .expert-info { list-style: none; padding: 0; margin: 0; text-align: left; background: #f8fafc; padding: 15px; border-radius: 8px; font-size: 0.9rem; }
        .expert-info li { margin-bottom: 8px; display: flex; color: #555; }
        .expert-info li strong { min-width: 90px; color: var(--dark-blue); font-weight: 600; }
        
        .expert-actions { display: flex; }
        .btn-action {
            flex: 1; padding: 15px; text-align: center; text-decoration: none;
            font-weight: 600; font-size: 0.9rem; transition: 0.3s;
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        .btn-outline { background: #fff; color: var(--dark-blue); border-top: 1px solid #eee; cursor: pointer; }
        .btn-outline:hover { background: #f4f7fa; }
        .btn-solid { background: var(--primary-color); color: #000; }
        .btn-solid:hover { background: #e0a100; }

        /* Modal Styles */
        .modal-content { border-radius: 16px; border: none; overflow: hidden; }
        .modal-header { background: var(--dark-blue); color: white; padding: 15px 25px; }
        .modal-title { font-family: 'Playfair Display', serif; }
        .section-title { color: var(--dark-blue); font-weight: 700; border-bottom: 2px solid var(--primary-color); display: inline-block; margin-bottom: 15px; padding-bottom: 5px; }
        .terms-list li { margin-bottom: 8px; color: #555; font-size: 0.95rem; }
        .badge-custom { background: #eef2ff; color: var(--dark-blue); border: 1px solid #c7d2fe; font-weight: 600; }
    </style>
</head>

<body>

    <?php include 'includes/header.php'; ?>

    <!-- Title -->
    <div class="page-title-wave">
        <div class="container">
            <h2>Meet Our Clinical Experts</h2>
            <p style="opacity: 0.9;">Experienced professionals dedicated to mental well-being</p>
        </div>
        <svg class="wave" viewBox="0 0 1440 320" preserveAspectRatio="none" style="display: block; margin-bottom: -1px; height: 100px; width: 100%; position: absolute; bottom: 0; left: 0;">
            <path fill="#f9fbfd" fill-opacity="1" d="M0,64L48,90.7C96,117,192,171,288,170.7C384,171,480,117,576,85.3C672,53,768,43,864,74.7C960,107,1056,181,1152,192C1248,203,1344,149,1392,122.7L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>

    <!-- Filter -->
    <div class="container">
        <form method="GET" action="expert.php" class="stylish-filter">
            <div class="field">
                <select name="expertise">
                    <option value="">All Expertise</option>
                    <?php 
                    if (!empty($categories)) {
                        foreach ($categories as $cat) {
                            $selected = ($expertiseFilter == $cat['name']) ? 'selected' : '';
                            echo '<option value="'.htmlspecialchars($cat['name']).'" '.$selected.'>'.htmlspecialchars($cat['name']).'</option>';
                        }
                    } else {
                        // Fallback
                        $fallback = ['CBT', 'Child Psychology', 'Depression', 'Addiction'];
                        foreach($fallback as $opt) echo '<option>'.$opt.'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="field">
                <input type="text" name="search" placeholder="Search by name or keyword..." value="<?php echo htmlspecialchars($searchQuery); ?>">
            </div>
            <div class="field" style="flex: 0 0 150px;">
                <button type="submit" class="apply-btn">Find Expert</button>
            </div>
            <?php if(!empty($expertiseFilter) || !empty($searchQuery)): ?>
            <div class="field" style="flex: 0 0 100px;">
                <a href="expert.php" class="btn-reset">Reset</a>
            </div>
            <?php endif; ?>
        </form>
    </div>

    <!-- Experts Grid -->
    <section class="experts-section pb-5">
        <div class="experts-grid">
            <?php if (count($experts) > 0): ?>
                <?php foreach ($experts as $expert): ?>
                <div class="expert-card">
                    <div class="expert-img">
                        <?php 
                            // Handle image path from admin uploads
                            $imgSrc = !empty($expert['Photo']) ? $expert['Photo'] : 'assets/img/default-expert.png';
                        ?>
                        <img src="<?php echo htmlspecialchars($imgSrc); ?>" 
                             alt="<?php echo htmlspecialchars($expert['Name']); ?>"
                             onerror="this.src='assets/img/default-expert.png'">
                    </div>

                    <div class="expert-body">
                        <h3><?php echo htmlspecialchars($expert['Name']); ?></h3>
                        <span class="designation"><?php echo htmlspecialchars($expert['Designation']); ?></span>
                        <p class="qualification"><?php echo htmlspecialchars($expert['Qualification']); ?></p>

                        <ul class="expert-info">
                            <li><strong>Experience:</strong> <?php echo htmlspecialchars($expert['Experience']); ?></li>
                            <li><strong>Expertise:</strong> <?php echo htmlspecialchars(substr($expert['Expertise'], 0, 50)) . (strlen($expert['Expertise']) > 50 ? '...' : ''); ?></li>
                        </ul>
                    </div>
                    
                    <div class="expert-actions">
                        <!-- Open Dynamic Modal -->
                        <a href="javascript:void(0)" class="btn-action btn-outline" 
                           onclick='openExpertModal(<?php echo json_encode($expert); ?>)'>
                           View Profile
                        </a>
                        <!-- Book Now -->
                        <!-- Passes the doctor name in URL for auto-selection -->
                        <a href="book-appointment.php?doctor=<?php echo urlencode($expert['Name']); ?>" class="btn-action btn-solid">Book Now</a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <div class="alert alert-light d-inline-block px-5 shadow-sm border">
                        <h4 class="text-muted"><i class="bx bx-search-alt"></i> No experts found</h4>
                        <p class="mb-0 text-muted">Try adjusting your search filters.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- DYNAMIC MODAL -->
    <div class="modal fade" id="expertModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Expert Profile</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row align-items-start mb-4">
                        <div class="col-md-4 text-center">
                            <img id="modalImg" src="" class="img-fluid rounded-3 mb-3 shadow-sm border" style="width: 100%; height: 200px; object-fit: cover;">
                        </div>
                        <div class="col-md-8">
                            <h3 class="fw-bold" id="modalName" style="color: #0a1f33; font-family: 'Playfair Display', serif;"></h3>
                            <p class="mb-2 text-primary fw-bold" id="modalDesignation"></p>
                            <p class="text-muted mb-3 fst-italic" id="modalQual"></p>

                            <p class="mb-1"><strong>Experience:</strong> <span id="modalExp"></span></p>
                            <p class="mb-3"><strong>Expertise:</strong> <span id="modalExpertise"></span></p>

                            <div class="d-flex gap-2 flex-wrap">
                                <span class="badge badge-custom px-3 py-2" id="modalDays"></span>
                                <span class="badge badge-custom px-3 py-2" id="modalTime"></span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="section-title">About Expert</h5>
                        <p id="modalAbout" style="line-height: 1.7; color: #555;"></p>
                    </div>

                    <div>
                        <h5 class="section-title">Fees & Languages</h5>
                        <ul class="terms-list list-unstyled">
                            <li><i class="bx bx-check-circle text-success me-2"></i> <strong>Online Fee:</strong> ₹<span id="modalFeeOn"></span></li>
                            <li><i class="bx bx-check-circle text-success me-2"></i> <strong>Clinic Visit:</strong> ₹<span id="modalFeeOff"></span></li>
                            <li><i class="bx bx-globe text-primary me-2"></i> <strong>Languages:</strong> <span id="modalLang"></span></li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="#" id="modalBookBtn" class="btn btn-primary" style="background: #bfa15f; border: none; font-weight: 600;">Book Appointment</a>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/meanmenu.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/custom.js"></script>

    <script>
        // Javascript to handle Dynamic Modal Data
        var expertModal = new bootstrap.Modal(document.getElementById('expertModal'));

        function openExpertModal(data) {
            document.getElementById('modalName').innerText = data.Name;
            document.getElementById('modalDesignation').innerText = data.Designation;
            document.getElementById('modalQual').innerText = data.Qualification;
            document.getElementById('modalExp').innerText = data.Experience;
            document.getElementById('modalExpertise').innerText = data.Expertise;
            document.getElementById('modalDays').innerText = data.AvailabilityDays || 'Mon - Sat';
            document.getElementById('modalTime').innerText = data.AvailabilityTime || '10:00 AM - 06:00 PM';
            document.getElementById('modalAbout').innerText = data.About || 'No details available.';
            document.getElementById('modalFeeOn').innerText = data.FeeOnline || '0';
            document.getElementById('modalFeeOff').innerText = data.FeeOffline || '0';
            document.getElementById('modalLang').innerText = data.Languages || 'English';

            // Image
            let imgSrc = data.Photo ? data.Photo : 'assets/img/default-expert.png';
            document.getElementById('modalImg').src = imgSrc;

            // Update Booking Link to pass doctor name
            document.getElementById('modalBookBtn').href = 'book-appointment.php?doctor=' + encodeURIComponent(data.Name);

            expertModal.show();
        }
    </script>
  
</body>
</html>