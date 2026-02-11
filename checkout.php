<?php
// --- DEBUGGING: Enable Error Reporting ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
// Prevent Caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// --- INCLUDE DATABASE CONNECTION ---
$dbPath = 'admin/includes/db.php';
if (file_exists($dbPath)) {
    require_once $dbPath;
} else {
    die("Error: Database file not found at " . $dbPath);
}

// --- 1. FETCH SETTINGS (Razorpay) ---
$settings = [];
try {
    $checkSettings = $conn->query("SHOW TABLES LIKE 'site_settings'");
    if($checkSettings && $checkSettings->num_rows > 0) {
        $res = $conn->query("SELECT SettingKey, SettingValue FROM site_settings");
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $settings[$row['SettingKey']] = $row['SettingValue'];
            }
        }
    }
} catch (Exception $e) { }

$mode = $settings['razorpay_mode'] ?? 'test';
$keyId = ($mode == 'live') ? ($settings['razorpay_live_key_id'] ?? '') : ($settings['razorpay_test_key_id'] ?? '');
$currency = $settings['currency'] ?? 'INR';

// --- 2. GET PUBLICATION DETAILS ---
$pubId = isset($_GET['pub_id']) ? (int)$_GET['pub_id'] : 0;
$product = null;

if ($pubId) {
    // Fallback query if prepare fails
    $res = $conn->query("SELECT * FROM publications WHERE PubId = $pubId AND Status = 'published' LIMIT 1");
    if($res) $product = $res->fetch_assoc();
}

// Redirect if invalid product
if (!$product) {
    echo "<script>alert('Invalid Product'); window.location.href='research-publications.php';</script>";
    exit;
}

// --- 3. PRE-FILL USER DATA IF LOGGED IN ---
$user = [
    'Name' => '', 'Email' => '', 'Phone' => '', 'Address' => '', 'City' => '', 'State' => '', 'ZipCode' => ''
];
$isLoggedIn = false;

if (isset($_SESSION['user_id'])) {
    $isLoggedIn = true;
    $uid = $_SESSION['user_id'];
    $uQ = $conn->query("SELECT * FROM userlogin WHERE UserId = $uid");
    if ($uQ && $uRow = $uQ->fetch_assoc()) {
        $user = $uRow;
    }
}

// Fix Image Path
$imgSrc = !empty($product['CoverImage']) ? str_replace('../', '', $product['CoverImage']) : 'assets/img/default-book.png';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout - <?php echo htmlspecialchars($product['Title'] ?? 'Product'); ?></title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f3f4f6; font-family: 'Inter', sans-serif; }
        .checkout-wrapper { max-width: 1100px; margin: 50px auto; }
        .card { border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        .form-control { padding: 12px; border-radius: 8px; border: 1px solid #e5e7eb; }
        .form-control:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }
        .product-thumb { width: 80px; height: 110px; object-fit: cover; border-radius: 6px; border: 1px solid #eee; }
        .btn-pay { background: #4f46e5; color: white; padding: 14px; width: 100%; border-radius: 10px; font-weight: 600; border: none; transition: .3s; }
        .btn-pay:hover { background: #4338ca; }
        #processing-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.9); z-index: 9999; align-items: center; justify-content: center; flex-direction: column; }
        
        /* Password Toggle Icon */
        .password-toggle { cursor: pointer; background: white; border-left: none; }
        .password-field { border-right: none; }
        .input-group-text { border-color: #4f46e5; }
    </style>
</head>
<body>

<?php include 'includes/header.php'; ?>

<!-- Loader -->
<div id="processing-overlay">
    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>
    <h5 class="mt-3 text-dark">Processing Secure Payment...</h5>
</div>

<div class="container checkout-wrapper">
    <div class="row g-4">
        
        <!-- LEFT: Billing Form -->
        <div class="col-lg-7">
            <div class="card p-4">
                <h4 class="mb-4 fw-bold text-dark">Billing Details</h4>
                
                <?php if(!$isLoggedIn): ?>
                <div class="alert alert-info d-flex align-items-center mb-4">
                    <i class="bi bi-person-circle fs-4 me-2"></i>
                    <div>
                        Already have an account? <a href="admin/login.php?redirect=checkout.php?pub_id=<?php echo $pubId; ?>" class="fw-bold text-decoration-underline">Log in</a> for faster checkout.
                    </div>
                </div>
                <?php endif; ?>

                <form id="checkoutForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Full Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" class="form-control" value="<?php echo htmlspecialchars($user['Name']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Email Address <span class="text-danger">*</span></label>
                            <input type="email" id="email" class="form-control" value="<?php echo htmlspecialchars($user['Email']); ?>" <?php echo $isLoggedIn ? 'readonly' : 'required'; ?>>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" id="phone" class="form-control" value="<?php echo htmlspecialchars($user['Phone']); ?>" required>
                        </div>
                        
                        <!-- PASSWORD FIELDS (Only for New Users) -->
                        <?php if(!$isLoggedIn): ?>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-primary">Create Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" id="createPassword" class="form-control border-primary password-field" placeholder="Create password" required>
                                <span class="input-group-text border-primary password-toggle" onclick="togglePass('createPassword', this)">
                                    <i class="bi bi-eye"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-primary">Confirm Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" id="confirmPassword" class="form-control border-primary password-field" placeholder="Confirm password" required>
                                <span class="input-group-text border-primary password-toggle" onclick="togglePass('confirmPassword', this)">
                                    <i class="bi bi-eye"></i>
                                </span>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="col-md-6">
                             <label class="form-label small fw-bold text-muted">Account Status</label>
                             <div class="form-control bg-light text-success"><i class="bi bi-check-circle-fill"></i> Logged In</div>
                        </div>
                        <?php endif; ?>

                        <div class="col-md-12">
                            <label class="form-label small fw-bold text-muted">Address <span class="text-danger">*</span></label>
                            <input type="text" id="address" class="form-control" value="<?php echo htmlspecialchars($user['Address'] ?? ''); ?>" placeholder="Street address, Apt, Suite" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-bold text-muted">City <span class="text-danger">*</span></label>
                            <input type="text" id="city" class="form-control" value="<?php echo htmlspecialchars($user['City'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">State <span class="text-danger">*</span></label>
                            <input type="text" id="state" class="form-control" value="<?php echo htmlspecialchars($user['State'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted">Zip Code <span class="text-danger">*</span></label>
                            <input type="text" id="zip" class="form-control" value="<?php echo htmlspecialchars($user['ZipCode'] ?? ''); ?>" required>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- RIGHT: Order Summary -->
        <div class="col-lg-5">
            <div class="card p-4 h-100">
                <h4 class="mb-4 fw-bold text-dark">Order Summary</h4>
                
                <div class="d-flex align-items-center mb-4 pb-4 border-bottom">
                    <img src="<?php echo htmlspecialchars($imgSrc); ?>" class="product-thumb me-3" onerror="this.src='assets/img/default-book.png'">
                    <div>
                        <h6 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($product['Title']); ?></h6>
                        <span class="badge bg-light text-secondary border"><?php echo htmlspecialchars($product['Category']); ?></span>
                    </div>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal</span>
                    <span class="fw-bold">₹<?php echo number_format($product['Price'], 2); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-4">
                    <span class="text-muted">Tax (0%)</span>
                    <span class="fw-bold">₹0.00</span>
                </div>
                <div class="d-flex justify-content-between border-top pt-3 mb-4">
                    <span class="fw-bold fs-5">Total</span>
                    <span class="fw-bold fs-4 text-primary">₹<?php echo number_format($product['Price'], 2); ?></span>
                </div>

                <button type="button" class="btn-pay" onclick="initiatePayment()">
                    Pay & Place Order
                </button>
                
                <div class="text-center mt-3 small text-muted">
                    <i class="bi bi-shield-lock-fill text-success"></i> Secure payment powered by Razorpay
                </div>
            </div>
        </div>

    </div>
</div>

<?php include 'includes/footer.php'; ?>

<!-- Scripts -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
    const pubId = <?php echo $pubId; ?>;
    const razorpayKey = "<?php echo $keyId; ?>";
    const currency = "<?php echo $currency; ?>";
    const prodTitle = "<?php echo addslashes($product['Title']); ?>";

    // Toggle Password Visibility
    function togglePass(inputId, iconSpan) {
        const input = document.getElementById(inputId);
        const icon = iconSpan.querySelector('i');
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }

    function initiatePayment() {
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const phone = document.getElementById('phone').value;
        const address = document.getElementById('address').value;
        const city = document.getElementById('city').value;
        const state = document.getElementById('state').value;
        const zip = document.getElementById('zip').value;
        
        let newPassword = '';
        
        // Get password if user is NOT logged in (fields exist)
        const passwordField = document.getElementById('createPassword');
        const confirmField = document.getElementById('confirmPassword');
        
        if (passwordField) { // New User Check
            const p1 = passwordField.value.trim();
            const p2 = confirmField.value.trim();
            
            if(!p1 || !p2) {
                alert("Please create a password for your account.");
                passwordField.focus();
                return;
            }
            if(p1 !== p2) {
                alert("Passwords do not match!");
                confirmField.focus();
                return;
            }
            newPassword = p1;
        }

        if(!name || !email || !phone || !address || !city || !state || !zip) {
            alert("Please fill all required billing details.");
            return;
        }

        document.getElementById('processing-overlay').style.display = 'flex';

        // Create Order via AJAX
        $.post('research-publications.php', {
            action: 'create_order',
            pub_id: pubId
        }, function(res) {
            if(res.status === 'success') {
                var options = {
                    "key": razorpayKey, 
                    "amount": res.amount * 100, 
                    "currency": currency,
                    "name": "IMHRC",
                    "description": "Payment for " + prodTitle,
                    "order_id": res.order_id, 
                    "handler": function (response){
                        verifyPayment(response, newPassword);
                    },
                    "prefill": { "name": name, "email": email, "contact": phone },
                    "theme": { "color": "#4f46e5" },
                    "modal": { "ondismiss": function(){ document.getElementById('processing-overlay').style.display = 'none'; }}
                };
                var rzp1 = new Razorpay(options);
                rzp1.open();
            } else {
                alert(res.message);
                document.getElementById('processing-overlay').style.display = 'none';
            }
        }, 'json');
    }

    function verifyPayment(response, newPassword) {
        const formData = {
            action: 'verify_payment',
            razorpay_payment_id: response.razorpay_payment_id,
            razorpay_order_id: response.razorpay_order_id,
            razorpay_signature: response.razorpay_signature,
            pub_id: pubId,
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
            address: document.getElementById('address').value,
            city: document.getElementById('city').value,
            state: document.getElementById('state').value,
            zip: document.getElementById('zip').value,
            new_password: newPassword // Send password to backend to create account
        };

        $.post('research-publications.php', formData, function(res) {
            document.getElementById('processing-overlay').style.display = 'none';
            if(res.status === 'success') {
                alert("Thank you! Your order has been placed successfully." + (newPassword ? " Your account has been created." : ""));
                window.location.href = 'index.php'; 
            } else {
                alert(res.message);
            }
        }, 'json');
    }
</script>

</body>
</html>