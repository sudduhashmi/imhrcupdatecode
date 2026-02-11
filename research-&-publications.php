<?php
session_start();
// Prevent Caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Include database connection
require_once 'admin/includes/db.php'; 

// --- 1. FETCH SETTINGS (For Razorpay) ---
$settings = [];
try {
    $res = $conn->query("SELECT SettingKey, SettingValue FROM site_settings");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $settings[$row['SettingKey']] = $row['SettingValue'];
        }
    }
} catch (Exception $e) { }

$mode = $settings['razorpay_mode'] ?? 'test';
$keyId = ($mode == 'live') ? ($settings['razorpay_live_key_id'] ?? '') : ($settings['razorpay_test_key_id'] ?? '');
$keySecret = ($mode == 'live') ? ($settings['razorpay_live_key_secret'] ?? '') : ($settings['razorpay_test_key_secret'] ?? '');
$currency = $settings['currency'] ?? 'INR';

// --- 2. AJAX HANDLER: PAYMENTS ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (ob_get_length()) ob_clean(); // Clean buffer
    header('Content-Type: application/json');

    // Create Order
    if ($_POST['action'] === 'create_order') {
        $pubId = (int)$_POST['pub_id'];
        
        // Fetch price securely from DB
        $stmt = $conn->prepare("SELECT Price, Title FROM publications WHERE PubId = ?");
        $stmt->bind_param("i", $pubId);
        $stmt->execute();
        $res = $stmt->get_result();
        $item = $res->fetch_assoc();

        if (!$item) {
            echo json_encode(['status' => 'error', 'message' => 'Item not found.']);
            exit;
        }

        $amount = (float)$item['Price'];

        // If free
        if ($amount <= 0) {
            echo json_encode(['status' => 'success', 'order_id' => 'FREE', 'amount' => 0]);
            exit;
        }

        // Razorpay Order via cURL
        $ch = curl_init("https://api.razorpay.com/v1/orders");
        curl_setopt($ch, CURLOPT_USERPWD, $keyId . ":" . $keySecret);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            "amount" => $amount * 100, 
            "currency" => $currency,
            "receipt" => "pub_" . time(),
            "payment_capture" => 1
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            $order = json_decode($response, true);
            echo json_encode(['status' => 'success', 'order_id' => $order['id'], 'amount' => $amount]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Payment initiation failed. Check API Keys.']);
        }
        exit;
    }

    // Verify Payment & Save Order/User
    if ($_POST['action'] === 'verify_payment') {
        $payment_id = $_POST['razorpay_payment_id'];
        $order_id = $_POST['razorpay_order_id'];
        $signature = $_POST['razorpay_signature'];
        
        // Skip verification for free items
        if ($order_id === 'FREE') {
            echo json_encode(['status' => 'success', 'message' => 'Purchase successful!']);
            exit;
        }

        $generated_signature = hash_hmac('sha256', $order_id . "|" . $payment_id, $keySecret);

        if ($generated_signature === $signature) {
            
            // --- DATA FROM CHECKOUT FORM ---
            $pubId = (int)$_POST['pub_id'];
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $address = trim($_POST['address']);
            $city = trim($_POST['city']);
            $state = trim($_POST['state']);
            $zip = trim($_POST['zip']);
            $newPassword = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';

            // Get Publication Details
            $q = $conn->query("SELECT Price, Title FROM publications WHERE PubId = $pubId");
            $r = $q->fetch_assoc();
            $amount = $r['Price'] ?? 0;
            $pubTitle = $r['Title'] ?? 'Publication';

            // 1. SAVE ORDER
            $stmt = $conn->prepare("INSERT INTO publication_orders (PubId, CustomerName, CustomerEmail, CustomerPhone, Address, City, State, ZipCode, Amount, RazorpayPaymentId, RazorpayOrderId, PaymentStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'success')");
            $stmt->bind_param("isssssssdds", $pubId, $name, $email, $phone, $address, $city, $state, $zip, $amount, $payment_id, $order_id);
            $stmt->execute();
            
            // 2. CREATE USER ACCOUNT (If Password Provided)
            if (!empty($newPassword) && !empty($email)) {
                // Check if user exists
                $check = $conn->prepare("SELECT UserId FROM userlogin WHERE Email = ?");
                $check->bind_param("s", $email);
                $check->execute();
                $check->store_result();
                
                if ($check->num_rows == 0) {
                    // Create new user
                    $hashedPwd = password_hash($newPassword, PASSWORD_DEFAULT);
                    $role = 'customer'; // Default role for book buyers
                    $ins = $conn->prepare("INSERT INTO userlogin (Name, Email, Phone, Password, Role, Address, City, State, ZipCode) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $ins->bind_param("sssssssss", $name, $email, $phone, $hashedPwd, $role, $address, $city, $state, $zip);
                    $ins->execute();
                    
                    // Optional: Log them in automatically
                    $_SESSION['user_id'] = $conn->insert_id;
                    $_SESSION['user_email'] = $email;
                    $_SESSION['user_name'] = $name;
                    $_SESSION['user_role'] = $role;
                }
            }

            // Send Email Notification
            $to = "info.imhrc@gmail.com";
            $subject = "New Purchase: " . $pubTitle;
            $msg = "A new purchase was made.\nItem: $pubTitle\nAmount: ₹$amount\nCustomer: $name\nPayment ID: $payment_id";
            @mail($to, $subject, $msg, "From: no-reply@imhrc.org");

            echo json_encode(['status' => 'success', 'message' => 'Payment successful! Order saved.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid payment signature.']);
        }
        exit;
    }
}

// --- 3. HANDLE BOOK PROPOSAL ---
$proposalMsg = '';
$proposalStatus = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_proposal'])) {
    $name = trim($_POST['fullName']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $bookTitle = trim($_POST['bookTitle']);
    $genre = trim($_POST['genre']);
    $desc = trim($_POST['description']);
    
    // File Upload
    $fileDest = '';
    if (isset($_FILES['manuscript']) && $_FILES['manuscript']['error'] === 0) {
        $uploadDir = 'assets/uploads/manuscripts/';
        if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
        
        $fileName = time() . '_' . basename($_FILES['manuscript']['name']);
        if (move_uploaded_file($_FILES['manuscript']['tmp_name'], $uploadDir . $fileName)) {
            $fileDest = $uploadDir . $fileName;
        }
    }

    if (!empty($name) && !empty($email) && !empty($bookTitle)) {
        // Matches table 'book_proposals' structure
        $stmt = $conn->prepare("INSERT INTO book_proposals (FullName, Email, Phone, BookTitle, Genre, Description, ManuscriptFile) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $name, $email, $phone, $bookTitle, $genre, $desc, $fileDest);
        
        if ($stmt->execute()) {
            $proposalMsg = "Proposal submitted successfully! We will contact you soon.";
            $proposalStatus = "success";
        } else {
            $proposalMsg = "Error submitting proposal. Please try again.";
            $proposalStatus = "danger";
        }
    }
}

// --- 4. FETCH PUBLICATIONS ---
$journals = [];
$tools = [];
$books = [];
$allPublications = [];

// Fetch 'published' AND 'active' status
$sql = "SELECT * FROM publications WHERE Status IN ('published', 'active') ORDER BY CreatedAt DESC";
$result = $conn->query($sql);

if ($result) {
    while($row = $result->fetch_assoc()) {
        // Fix Image Path (Remove ../ from admin path)
        if (!empty($row['CoverImage'])) {
            $row['CoverImage'] = str_replace('../', '', $row['CoverImage']);
        }

        // Decode JSON Details safely
        $details = json_decode($row['Details'] ?? '', true);
        $row['Details'] = is_array($details) ? $details : [];
        
        // Ensure Link is available
        $row['ExternalLink'] = !empty($row['Link']) ? $row['Link'] : ($row['Details']['link'] ?? '#');
        
        // Normalize Category (case insensitive)
        $cat = strtolower(trim($row['Category']));
        
        $allPublications[] = $row;
        
        if ($cat == 'journal') {
            $journals[] = $row;
        } elseif ($cat == 'tool') {
            $tools[] = $row;
        } elseif ($cat == 'book') {
            $books[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Research & Publications - IMHRC</title>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/boxicons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="icon" type="image/png" href="assets/img/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        .publication-card { background: #ffffff; border-radius: 18px; overflow: hidden; height: 100%; box-shadow: 0 15px 35px rgba(0,0,0,0.08); transition: all .35s ease; border: 1px solid #f0f0f0; }
        .publication-card:hover { transform: translateY(-8px); box-shadow: 0 18px 45px rgba(0,0,0,0.15); }
        .img-wrap { background: #f8fafc; text-align: center; padding: 20px; border-bottom: 1px solid #eee; }
        .img-wrap img { height: 220px; max-width: 100%; object-fit: contain; }
        
        .book-card { background: #fff; border-radius: 18px; overflow: hidden; box-shadow: 0 15px 40px rgba(0,0,0,0.08); transition: .4s; height: 100%; position: relative; display: flex; flex-direction: column; }
        .book-card:hover { transform: translateY(-8px); }
        .book-img { text-align: center; background: #f1f5f9; padding: 20px; position: relative; flex-shrink: 0; }
        .book-img img { width: auto; max-width: 100%; height: 250px; object-fit: cover; box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
        .book-badge { position: absolute; top: 12px; left: 12px; background: #ffb800; color: #fff; padding: 4px 12px; font-size: 12px; border-radius: 20px; font-weight: bold; }
        .book-body { padding: 20px; flex-grow: 1; display: flex; flex-direction: column; }
        #processing-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.9); z-index: 9999; align-items: center; justify-content: center; flex-direction: column; }
    </style>
</head>

<body>

    <?php include 'includes/header.php'; ?>
    
    <!-- Loader -->
    <div id="processing-overlay">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>
        <h5 class="mt-3 text-dark">Processing...</h5>
    </div>

    <!-- Title -->
    <div class="page-title-wave" style="background: linear-gradient(135deg, #0a1f33 0%, #1c3d5a 100%); padding: 120px 0 80px; text-align: center; color: white;">
        <div class="container">
            <h2>Research & Publications</h2>
        </div>
        <svg class="wave" viewBox="0 0 1440 320" preserveAspectRatio="none" style="display: block; margin-bottom: -1px; height: 100px; width: 100%; position: absolute; bottom: 0; left: 0;">
            <path fill="#ffffff" fill-opacity="1" d="M0,64L48,90.7C96,117,192,171,288,170.7C384,171,480,117,576,85.3C672,53,768,43,864,74.7C960,107,1056,181,1152,192C1248,203,1344,149,1392,122.7L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>

    <!-- NEW SECTION: ALL PUBLICATIONS (Unified Grid) -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <span class="badge bg-white text-dark border rounded-pill px-3 py-2 mb-2">Our Collection</span>
                <h2 class="fw-bold" style="color:#0b2c57">All <span style="color:#ffb800">Research & Publications</span></h2>
                <p class="text-muted">Browse through our complete collection of journals, tools, and books.</p>
            </div>
            
            <?php if (!empty($allPublications)): ?>
            <div class="row g-4">
                <?php foreach ($allPublications as $pub): ?>
                <div class="col-lg-3 col-md-6">
                    <div class="book-card h-100">
                        <div class="book-img">
                            <img src="<?php echo !empty($pub['CoverImage']) ? htmlspecialchars($pub['CoverImage']) : 'assets/img/default-book.png'; ?>" alt="<?php echo htmlspecialchars($pub['Title']); ?>">
                            <!-- Category Badge -->
                            <span class="book-badge" style="background: var(--primary-color); left: auto; right: 12px;">
                                <?php echo htmlspecialchars($pub['Category']); ?>
                            </span>
                        </div>
                        <div class="book-body p-3 d-flex flex-column h-100">
                            <h5 class="fw-bold text-dark mb-1 text-truncate" title="<?php echo htmlspecialchars($pub['Title']); ?>">
                                <?php echo htmlspecialchars($pub['Title']); ?>
                            </h5>
                            <p class="author small mb-3 text-muted">By <?php echo htmlspecialchars($pub['Author']); ?></p>
                            
                            <div class="mt-auto d-flex justify-content-between align-items-center pt-2 border-top">
                                <div class="text-warning fw-bold fs-6">
                                    <?php echo $pub['Price'] > 0 ? '₹' . number_format($pub['Price']) : 'Free'; ?>
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-outline-dark rounded-pill btn-sm px-3" 
                                            onclick='openDetails(<?php echo json_encode($pub, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)'>
                                        View
                                    </button>
                                    <!-- Buy Now Button (Shows for all) -->
                                    <a href="checkout.php?pub_id=<?php echo $pub['PubId']; ?>" class="btn btn-dark rounded-pill btn-sm px-3">
                                        Buy Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <h5 class="text-muted">No publications available yet.</h5>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- ORIGINAL SECTIONS -->
    
    <!-- JOURNALS SECTION -->
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h3 class="fw-bold">Academic Journals</h3>
            </div>
        </div>
        <div class="row g-4 justify-content-center">
            <?php if (!empty($journals)): foreach ($journals as $journal): ?>
            <div class="col-lg-8 col-md-10">
                <div class="publication-card d-md-flex align-items-center">
                    <div class="img-wrap" style="flex: 0 0 250px; height: auto; border-bottom: none; border-right: 1px solid #eee;">
                        <img src="<?php echo !empty($journal['CoverImage']) ? htmlspecialchars($journal['CoverImage']) : 'assets/img/default-book.png'; ?>" alt="<?php echo htmlspecialchars($journal['Title']); ?>">
                    </div>
                    <div class="card-body text-center text-md-start p-4 flex-grow-1">
                        <h5 class="fw-bold text-dark"><?php echo htmlspecialchars($journal['Title']); ?></h5>
                        <p class="small mb-3 text-muted"><?php echo htmlspecialchars($journal['Description']); ?></p>
                        <p class="small text-secondary mb-3"><strong>Publisher:</strong> <?php echo htmlspecialchars($journal['Author']); ?></p>
                        
                        <div class="d-flex justify-content-center justify-content-md-start gap-2 mt-3">
                            <button class="btn btn-outline-dark btn-sm rounded-pill px-4" 
                                    onclick='openDetails(<?php echo json_encode($journal, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)'>
                                View Details
                            </button>
                            <?php if(!empty($journal['ExternalLink']) && $journal['ExternalLink'] != '#'): ?>
                            <a href="<?php echo htmlspecialchars($journal['ExternalLink']); ?>" target="_blank" class="btn btn-success btn-sm rounded-pill px-4">Visit Journal</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; endif; ?>
        </div>
    </div>

    <!-- PSYCHOLOGICAL TOOLS -->
    <div class="container py-5" style="background-color: #fcfcfc;">
        <div class="text-center mb-5">
            <h2 class="fw-bold" style="color:#0b2c57">Psychological <span style="color:#ffb800">Tools</span></h2>
        </div>
        <div class="row g-4 justify-content-center">
            <?php if (!empty($tools)): foreach ($tools as $tool): ?>
            <div class="col-lg-4 col-md-6">
                <div class="publication-card h-100">
                    <div class="img-wrap">
                        <img src="<?php echo !empty($tool['CoverImage']) ? htmlspecialchars($tool['CoverImage']) : 'assets/img/default-book.png'; ?>" alt="Tool">
                    </div>
                    <div class="card-body text-center p-4">
                        <h5><?php echo htmlspecialchars($tool['Title']); ?></h5>
                        <p class="small mb-2 text-primary fw-bold">Published by <?php echo htmlspecialchars($tool['Author'] ?: 'IMHRC'); ?></p>
                        <p class="text-muted small mb-3"><?php echo htmlspecialchars(substr($tool['Description'], 0, 80)) . '...'; ?></p>
                        <div class="d-flex justify-content-center gap-2 mt-3">
                            <button class="btn btn-outline-dark btn-sm rounded-pill px-4" 
                                    onclick='openDetails(<?php echo json_encode($tool, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)'>
                                View Details
                            </button>
                            <a href="checkout.php?pub_id=<?php echo $tool['PubId']; ?>" class="btn btn-success btn-sm rounded-pill px-4">Buy Now</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; endif; ?>
        </div>
    </div>

    <!-- BOOKS SECTION -->
    <section class="py-5" style="background: #f8f9fa;">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold" style="color:#0b2c57">Published <span style="color:#ffb800">Books</span></h2>
                <p class="text-muted">Explore our professionally published books</p>
            </div>
            <div class="row g-4">
                <?php if (!empty($books)): foreach ($books as $book): ?>
                <div class="col-lg-3 col-md-6">
                    <div class="book-card">
                        <div class="book-img">
                            <img src="<?php echo !empty($book['CoverImage']) ? htmlspecialchars($book['CoverImage']) : 'assets/img/default-book.png'; ?>">
                            <?php if(!empty($book['IsFeatured'])): ?>
                                <span class="book-badge">Bestseller</span>
                            <?php endif; ?>
                        </div>
                        <div class="book-body p-3">
                            <h5 class="fw-bold text-dark mb-1" style="font-size:1.1rem;"><?php echo htmlspecialchars($book['Title']); ?></h5>
                            <p class="author small mb-3 text-muted">By <?php echo htmlspecialchars($book['Author']); ?></p>
                            
                            <div class="book-meta mb-3 d-flex gap-2 small text-secondary">
                                <?php if(!empty($book['Details']['pages'])): ?>
                                    <span><i class="bi bi-file-text text-warning"></i> <?php echo htmlspecialchars($book['Details']['pages']); ?> Pg</span>
                                <?php endif; ?>
                                <?php if(!empty($book['Details']['rating'])): ?>
                                    <span><i class="bi bi-star-fill text-warning"></i> <?php echo htmlspecialchars($book['Details']['rating']); ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-warning fw-bold fs-5">₹<?php echo number_format($book['Price']); ?></div>
                                <a href="checkout.php?pub_id=<?php echo $book['PubId']; ?>" class="btn btn-dark rounded-pill btn-sm px-3">Buy Now</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-5 bg-dark text-white text-center">
        <div class="container">
            <h2 class="text-warning mb-3">Become an Author</h2>
            <p class="mb-4 opacity-75">Start your journey to publish your book today with our professional support.</p>
            <button class="btn btn-warning btn-lg rounded-pill fw-bold" id="openFormBtn">Publish With Us</button>
        </div>
    </section>

    <!-- Proposal Modal -->
    <div id="publishFormModal" class="modal" style="display:none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5);">
        <div class="modal-content model-section" style="background-color: #fefefe; margin: 5% auto; padding: 25px; border: 1px solid #888; width: 90%; max-width: 500px; border-radius:15px; position:relative;">
            <span class="close-btn" style="position:absolute; top:15px; right:20px; font-size:28px; font-weight:bold; cursor:pointer;">&times;</span>
            <h3 class="mb-1 text-dark fw-bold">Submit Proposal</h3>
            <p class="small text-muted mb-4">Share your manuscript details with us.</p>
            
            <?php if($proposalMsg): ?>
                <div class="alert alert-<?php echo $proposalStatus; ?> mb-3"><?php echo $proposalMsg; ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="submit_proposal" value="1">
                <div class="mb-3"><input type="text" name="fullName" class="form-control" placeholder="Full Name*" required></div>
                <div class="mb-3"><input type="email" name="email" class="form-control" placeholder="Email Address*" required></div>
                <div class="mb-3"><input type="tel" name="phone" class="form-control" placeholder="Phone Number*" required></div>
                <div class="mb-3"><input type="text" name="bookTitle" class="form-control" placeholder="Book Title*" required></div>
                <div class="mb-3"><input type="text" name="genre" class="form-control" placeholder="Genre / Category"></div>
                <div class="mb-3"><textarea name="description" class="form-control" rows="3" placeholder="Short Description / Synopsis*" required></textarea></div>
                <div class="mb-3">
                    <label class="form-label small text-muted">Upload Manuscript (PDF/DOC)</label>
                    <input type="file" name="manuscript" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold">Submit Proposal</button>
            </form>
        </div>
    </div>

    <!-- Details Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-md-5 text-center">
                            <img id="modalImg" src="" class="img-fluid rounded shadow" style="max-height:300px;">
                        </div>
                        <div class="col-md-7">
                            <h4 id="modalTitle" class="fw-bold text-dark"></h4>
                            <p class="text-muted small mb-2">Author: <span id="modalAuthor"></span></p>
                            <p id="modalDesc" class="text-secondary"></p>
                            <div id="modalExtra" class="mt-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <!-- JS -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/custom.js"></script>

    <script>
        const modal = document.getElementById("publishFormModal");
        const btn = document.getElementById("openFormBtn");
        const span = document.getElementsByClassName("close-btn")[0];

        if(btn) btn.onclick = function() { modal.style.display = "block"; }
        if(span) span.onclick = function() { modal.style.display = "none"; }
        window.onclick = function(event) { if (event.target == modal) { modal.style.display = "none"; } }

        var detailModal = new bootstrap.Modal(document.getElementById('detailModal'));

        function openDetails(data) {
            document.getElementById('modalTitle').innerText = data.Title;
            document.getElementById('modalAuthor').innerText = data.Author || 'IMHRC';
            document.getElementById('modalDesc').innerText = data.Description;
            let imgSrc = data.CoverImage ? data.CoverImage : 'assets/img/default-book.png';
            document.getElementById('modalImg').src = imgSrc;
            
            const btnContainer = document.getElementById('modalExtra');
            btnContainer.innerHTML = ''; 
            
            if (data.ExternalLink && data.ExternalLink !== '#') {
                btnContainer.innerHTML += `<a href="${data.ExternalLink}" target="_blank" class="btn btn-success rounded-pill px-4 me-2">Visit Link</a>`;
            }
            
            // Buy Button in Modal (Always Visible)
            btnContainer.innerHTML += `<a href="checkout.php?pub_id=${data.PubId}" class="btn btn-warning rounded-pill px-4">Buy Now (₹${data.Price})</a>`;

            detailModal.show();
        }

        <?php if($proposalMsg): ?> modal.style.display = "block"; <?php endif; ?>
    </script>
</body>
</html>