<?php
// --- DEBUGGING: Enable Error Reporting ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

$pageTitle = 'Site Settings - ' . APP_NAME;
requireLogin();

// --- HANDLE SAVE SETTINGS ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_settings'])) {
    foreach ($_POST as $key => $value) {
        if ($key === 'save_settings') continue;
        
        $val = trim($value);
        
        // 1. Check if key exists using compatible method
        $stmt = $conn->prepare("SELECT id FROM site_settings WHERE SettingKey = ?");
        if ($stmt) {
            $stmt->bind_param("s", $key);
            $stmt->execute();
            $stmt->store_result(); // Store result to check num_rows
            
            if ($stmt->num_rows > 0) {
                // UPDATE existing
                $stmt->close(); // Close previous statement first
                $update = $conn->prepare("UPDATE site_settings SET SettingValue = ? WHERE SettingKey = ?");
                $update->bind_param("ss", $val, $key);
                $update->execute();
                $update->close();
            } else {
                // INSERT new
                $stmt->close(); // Close previous statement first
                $insert = $conn->prepare("INSERT INTO site_settings (SettingKey, SettingValue) VALUES (?, ?)");
                $insert->bind_param("ss", $key, $val);
                $insert->execute();
                $insert->close();
            }
        }
    }
    
    header("Location: site-settings.php?msg=updated");
    exit;
}

// Fetch All Settings (Compatible Method)
$settings = [];
$res = $conn->query("SELECT * FROM site_settings");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $settings[$row['SettingKey']] = $row['SettingValue'];
    }
}
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
            Settings updated successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="page-header mb-4 mt-3 bg-white p-4 rounded-3 shadow-sm d-flex justify-content-between align-items-center">
            <h4 class="text-primary mb-0"><i class="bi bi-sliders"></i> Site Configuration</h4>
            <button onclick="document.querySelector('[name=save_settings]').click()" class="btn btn-primary shadow-sm">
                <i class="bi bi-save"></i> Save Changes
            </button>
        </div>

        <form method="POST" enctype="multipart/form-data">
        <div class="row">
            
            <!-- 1. GENERAL SETTINGS -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white fw-bold">
                        <i class="bi bi-globe me-2 text-info"></i> General Information
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Website Title</label>
                            <input type="text" name="site_title" class="form-control" value="<?php echo htmlspecialchars($settings['site_title'] ?? ''); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Contact Email (Admin)</label>
                            <input type="email" name="contact_email" class="form-control" value="<?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?>">
                            <small class="text-muted">Notifications will be sent to this email.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Contact Phone</label>
                            <input type="text" name="contact_phone" class="form-control" value="<?php echo htmlspecialchars($settings['contact_phone'] ?? ''); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Address</label>
                            <textarea name="contact_address" class="form-control" rows="2"><?php echo htmlspecialchars($settings['contact_address'] ?? ''); ?></textarea>
                        </div>
                         <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Google Map Embed URL</label>
                            <input type="text" name="contact_map" class="form-control" value="<?php echo htmlspecialchars($settings['contact_map'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. EMAIL / SMTP SETTINGS -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white fw-bold">
                        <i class="bi bi-envelope-at-fill me-2 text-warning"></i> Email Configuration (SMTP)
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">From Name</label>
                                <input type="text" name="mail_from_name" class="form-control" value="<?php echo htmlspecialchars($settings['mail_from_name'] ?? 'IMHRC'); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">From Email</label>
                                <input type="email" name="mail_from_address" class="form-control" value="<?php echo htmlspecialchars($settings['mail_from_address'] ?? 'no-reply@imhrc.org'); ?>">
                            </div>
                            
                            <div class="col-12"><hr class="my-1"></div>
                            
                            <div class="col-md-8">
                                <label class="form-label small fw-bold text-muted">SMTP Host</label>
                                <input type="text" name="smtp_host" class="form-control font-monospace bg-light" placeholder="smtp.gmail.com" value="<?php echo htmlspecialchars($settings['smtp_host'] ?? ''); ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted">Port</label>
                                <input type="text" name="smtp_port" class="form-control font-monospace bg-light" placeholder="587" value="<?php echo htmlspecialchars($settings['smtp_port'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">SMTP Username</label>
                                <input type="text" name="smtp_user" class="form-control font-monospace bg-light" value="<?php echo htmlspecialchars($settings['smtp_user'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">SMTP Password</label>
                                <input type="password" name="smtp_pass" class="form-control font-monospace bg-light" value="<?php echo htmlspecialchars($settings['smtp_pass'] ?? ''); ?>">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label small fw-bold text-muted">Encryption</label>
                                <select name="smtp_encryption" class="form-select bg-light">
                                    <option value="tls" <?php echo ($settings['smtp_encryption'] ?? '') == 'tls' ? 'selected' : ''; ?>>TLS</option>
                                    <option value="ssl" <?php echo ($settings['smtp_encryption'] ?? '') == 'ssl' ? 'selected' : ''; ?>>SSL</option>
                                    <option value="none" <?php echo ($settings['smtp_encryption'] ?? '') == 'none' ? 'selected' : ''; ?>>None</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. PAYMENT GATEWAY SETTINGS -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white fw-bold">
                        <i class="bi bi-credit-card-2-front-fill me-2 text-success"></i> Payment Gateway (Razorpay)
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted">Active Mode</label>
                            <select name="razorpay_mode" class="form-select border-primary">
                                <option value="test" <?php echo ($settings['razorpay_mode'] ?? '') == 'test' ? 'selected' : ''; ?>>Test Mode (Sandbox)</option>
                                <option value="live" <?php echo ($settings['razorpay_mode'] ?? '') == 'live' ? 'selected' : ''; ?>>Live Mode (Production)</option>
                            </select>
                        </div>
                        
                        <h6 class="fw-bold text-secondary border-bottom pb-2 mb-3" style="font-size: 0.85rem;">TEST KEYS</h6>
                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Key ID</label>
                                <input type="text" name="razorpay_test_key_id" class="form-control font-monospace bg-light" value="<?php echo htmlspecialchars($settings['razorpay_test_key_id'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Key Secret</label>
                                <input type="password" name="razorpay_test_key_secret" class="form-control font-monospace bg-light" value="<?php echo htmlspecialchars($settings['razorpay_test_key_secret'] ?? ''); ?>">
                            </div>
                        </div>

                        <h6 class="fw-bold text-secondary border-bottom pb-2 mb-3" style="font-size: 0.85rem;">LIVE KEYS</h6>
                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Key ID</label>
                                <input type="text" name="razorpay_live_key_id" class="form-control font-monospace" value="<?php echo htmlspecialchars($settings['razorpay_live_key_id'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Key Secret</label>
                                <input type="password" name="razorpay_live_key_secret" class="form-control font-monospace" value="<?php echo htmlspecialchars($settings['razorpay_live_key_secret'] ?? ''); ?>">
                            </div>
                        </div>
                        
                         <div class="mb-3 mt-4 pt-3 border-top">
                            <label class="form-label small fw-bold text-muted">Currency</label>
                            <select name="currency" class="form-select">
                                <option value="INR" <?php echo ($settings['currency'] ?? '') == 'INR' ? 'selected' : ''; ?>>INR (Indian Rupee)</option>
                                <option value="USD" <?php echo ($settings['currency'] ?? '') == 'USD' ? 'selected' : ''; ?>>USD (US Dollar)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 4. SOCIAL MEDIA SETTINGS -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white fw-bold">
                        <i class="bi bi-share-fill me-2 text-primary"></i> Social Media Links
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Facebook</label>
                            <input type="text" name="social_facebook" class="form-control" value="<?php echo htmlspecialchars($settings['social_facebook'] ?? ''); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Instagram</label>
                            <input type="text" name="social_instagram" class="form-control" value="<?php echo htmlspecialchars($settings['social_instagram'] ?? ''); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Twitter</label>
                            <input type="text" name="social_twitter" class="form-control" value="<?php echo htmlspecialchars($settings['social_twitter'] ?? ''); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">LinkedIn</label>
                            <input type="text" name="social_linkedin" class="form-control" value="<?php echo htmlspecialchars($settings['social_linkedin'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 text-center mb-5">
                <button type="submit" name="save_settings" class="btn btn-lg btn-success px-5 rounded-pill shadow">
                    <i class="bi bi-check-circle-fill me-2"></i> Save All Settings
                </button>
            </div>

        </div>
        </form>

    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
</body>
</html>