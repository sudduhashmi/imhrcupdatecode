<?php
// --- CONFIGURATION & ERROR HANDLING ---
// Buffering start karein taaki header issues na aayen
ob_start();

// Debugging ke liye Errors ON karein (Production me OFF rakhein)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Prevent Browser Caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Check Database File
$dbPath = 'admin/includes/db.php';
if (!file_exists($dbPath)) {
    die("Database connection file missing.");
}
require_once $dbPath;

// --- FETCH SETTINGS ---
$settings = [];
try {
    $res = $conn->query("SELECT SettingKey, SettingValue FROM site_settings");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $settings[$row['SettingKey']] = $row['SettingValue'];
        }
    }
} catch (Exception $e) { 
    // Silent fail for settings
}

$mode = $settings['razorpay_mode'] ?? 'test';
$keyId = ($mode == 'live') ? ($settings['razorpay_live_key_id'] ?? '') : ($settings['razorpay_test_key_id'] ?? '');
$keySecret = ($mode == 'live') ? ($settings['razorpay_live_key_secret'] ?? '') : ($settings['razorpay_test_key_secret'] ?? '');
$currency = $settings['currency'] ?? 'INR';

// --- HELPER: GENERATE TIME SLOTS ---
function generateTimeSlots($timeString) {
    // Default Static Slots (Fallback)
    $defaultSlots = ['10:00 AM','11:00 AM','12:00 PM','01:00 PM','02:00 PM','03:00 PM','04:00 PM','05:00 PM','06:00 PM'];
    
    if (empty($timeString)) return $defaultSlots;

    $timeString = str_ireplace([' to ', ' – ', ' - '], '-', $timeString);
    $parts = explode('-', $timeString);

    if (count($parts) !== 2) return $defaultSlots;

    $startTime = strtotime(trim($parts[0]));
    $endTime = strtotime(trim($parts[1]));

    if (!$startTime || !$endTime || $startTime >= $endTime) return $defaultSlots;

    $slots = [];
    while ($startTime < $endTime) {
        $slots[] = date('h:i A', $startTime);
        $startTime = strtotime('+1 hour', $startTime);
    }
    
    return !empty($slots) ? $slots : $defaultSlots;
}

// --- FETCH EXPERTS ---
$expertsData = []; 
$sql = "SELECT * FROM experts WHERE Status = 'active' ORDER BY Name ASC";
$result = $conn->query($sql);

if ($result) {
    while($row = $result->fetch_assoc()) {
        $availTime = $row['AvailabilityTime'] ?? ''; 
        $slots = generateTimeSlots($availTime);

        // Image Path Correction
        $photoPath = !empty($row['Photo']) ? str_replace('../', '', $row['Photo']) : 'assets/img/default-expert.png';

        $expertsData[] = [
            'id' => $row['ExpertId'],
            'name' => $row['Name'],
            'role' => $row['Designation'],
            'fee' => [
                'online' => (float)($row['FeeOnline'] ?? 0), 
                'offline' => (float)($row['FeeOffline'] ?? 0)
            ],
            'slots' => $slots,
            'avatar' => $photoPath,
            'sessions' => ($row['AvailabilityDays'] ?? 'Mon-Sat') . ' · ' . ($row['AvailabilityTime'] ?? '10 AM - 6 PM')
        ];
    }
}

// --- AJAX HANDLERS (JSON Responses) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    // Clear buffer to ensure clean JSON
    ob_clean();
    header('Content-Type: application/json');

    // 1. CHECK AVAILABILITY
    if ($_POST['action'] === 'check_availability') {
        $expertId = (int)$_POST['expert_id'];
        $date = $_POST['date'];
        
        $bookedSlots = [];
        $stmt = $conn->prepare("SELECT message FROM appointments WHERE expert_id = ? AND appointment_date = ? AND status != 'cancelled'");
        if ($stmt) {
            $stmt->bind_param("is", $expertId, $date);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($msgDB);
            
            while($stmt->fetch()) {
                if (preg_match('/Time Slot:\s*(.*?)(\n|$)/i', $msgDB, $matches)) {
                    $bookedSlots[] = trim($matches[1]);
                }
            }
            $stmt->close();
        }
        echo json_encode(['status' => 'success', 'booked' => $bookedSlots]);
        exit;
    }

    // 2. CREATE ORDER
    if ($_POST['action'] === 'create_order') {
        if (empty($keyId) || empty($keySecret)) {
             echo json_encode(['status' => 'error', 'message' => 'Payment Gateway Error: Keys not configured.']);
             exit;
        }

        $amount = (float)$_POST['amount'];
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $expertId = !empty($_POST['expert_id']) ? (int)$_POST['expert_id'] : NULL;
        $date = $_POST['date'];
        $time = $_POST['time'];
        $mode = $_POST['mode'];
        $userMsg = trim($_POST['message']);

        // Razorpay API
        $ch = curl_init("https://api.razorpay.com/v1/orders");
        curl_setopt($ch, CURLOPT_USERPWD, $keyId . ":" . $keySecret);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            "amount" => $amount * 100, 
            "currency" => $currency, 
            "receipt" => "appt_" . time(), 
            "payment_capture" => 1
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            echo json_encode(['status' => 'error', 'message' => 'Curl Error: ' . curl_error($ch)]);
            exit;
        }
        curl_close($ch);

        $order = json_decode($response, true);

        if (isset($order['id'])) {
            $fullMessage = "Reason: $userMsg\nMode: $mode\nTime Slot: $time\nPayment: Pending";
            
            // Save Pending Appointment
            $stmt = $conn->prepare("INSERT INTO appointments (name, email, phone, expert_id, appointment_date, message, status, razorpay_order_id, payment_status) VALUES (?, ?, ?, ?, ?, ?, 'pending', ?, 'pending')");
            if ($stmt) {
                $stmt->bind_param("sssissss", $name, $email, $phone, $expertId, $date, $fullMessage, $order['id']);
                if($stmt->execute()) {
                    echo json_encode(['status' => 'success', 'order_id' => $order['id']]);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Database save failed: ' . $stmt->error]);
                }
                $stmt->close();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Query Prepare Failed: ' . $conn->error]);
            }
        } else {
            $errorMsg = isset($order['error']['description']) ? $order['error']['description'] : 'Payment initiation failed.';
            echo json_encode(['status' => 'error', 'message' => $errorMsg]);
        }
        exit;
    }

    // 3. VERIFY PAYMENT
    if ($_POST['action'] === 'verify_payment') {
        $payment_id = $_POST['razorpay_payment_id'];
        $signature = $_POST['razorpay_signature'];
        $order_id = $_POST['razorpay_order_id'];
        
        $generated_signature = hash_hmac('sha256', $order_id . "|" . $payment_id, $keySecret);

        if ($generated_signature === $signature || $_POST['is_free'] == 'true') {
            $stmt = $conn->prepare("UPDATE appointments SET status = 'confirmed', payment_status = 'success', razorpay_payment_id = ? WHERE razorpay_order_id = ?");
            $stmt->bind_param("ss", $payment_id, $order_id);
            
            if ($stmt->execute()) {
                // Email Notification
                $toAdmin = "info.imhrc@gmail.com";
                $subject = "New Confirmed Appointment";
                $body = "Appointment ID: $order_id has been successfully paid and confirmed.";
                $headers = "From: no-reply@imhrc.org";
                @mail($toAdmin, $subject, $body, $headers);

                echo json_encode(['status' => 'success', 'message' => 'Appointment booked successfully!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Database update failed.']);
            }
            $stmt->close();
        } else {
            // Mark Failed
            $stmt = $conn->prepare("UPDATE appointments SET payment_status = 'failed' WHERE razorpay_order_id = ?");
            $stmt->bind_param("s", $order_id);
            $stmt->execute();
            $stmt->close();
            
            echo json_encode(['status' => 'error', 'message' => 'Invalid payment signature.']);
        }
        exit;
    }
}
// Flush buffer HTML output starts
ob_end_flush();
?>
<!DOCTYPE html>
<html lang="zxx">
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
    <title>IMHRC</title>

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f9fbfd; }
        
        .appointment-card {
            border: 1px solid rgba(13, 110, 253, 0.08);
            box-shadow: 0 15px 50px rgba(20,30,80,0.08);
            transition: transform 0.3s ease;
        }

        .btn-appt {
            background: linear-gradient(90deg, #0061ff, #3a8df5);
            font-size: 1.1rem; transition: 0.3s; color: #fff; border: none;
        }
        .btn-appt:hover { background: linear-gradient(90deg, #004ccc, #2a7ce0); color: #fff; }

        .form-control, .form-select {
            height: 52px; border-radius: 12px;
            border: 1px solid #dde3f0;
            background-color: #fcfcfc;
            padding: 10px 18px; font-size: 15px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #0061ff; box-shadow: 0 0 0 4px rgba(0, 97, 255, 0.1); background-color: #fff;
        }

        .slot-btn {
            width:100%; padding:10px; border-radius:8px;
            border:1px solid #e0e0e0; background:#fff;
            font-weight:600; cursor:pointer; color: #555;
            transition: all 0.2s; font-size: 0.85rem;
        }
        .slot-btn:hover:not(:disabled) { border-color: #0061ff; color: #0061ff; }
        .slot-btn.active { background: #0061ff; color:#fff; border-color:#0061ff; box-shadow: 0 4px 12px rgba(0,97,255,0.3); }
        .slot-btn:disabled { background: #f1f3f5; color: #adb5bd; cursor: not-allowed; border-color: #f1f3f5; text-decoration: line-through; }

        .doctor-list-card {
            background: #fff; border-radius: 14px; padding: 20px;
            box-shadow: 0 8px 30px rgba(30,41,59,0.06);
            max-height: 80vh; overflow-y: auto;
        }
        .doctor-item { 
            cursor:pointer; transition:all 0.2s ease; border-radius:12px; padding:12px; 
            border: 1px solid transparent; background: #fff; margin-bottom: 10px;
        }
        .doctor-item:hover { transform: translateY(-2px); border-color: #e0e7ff; }
        .doctor-item.active { background: #f0f7ff; border-color: #0061ff; }

        #processing-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.9); z-index: 9999; align-items: center; justify-content: center; flex-direction: column; }
    </style>
</head>

<body>

    <?php include 'includes/header.php'; ?>
    
    <div id="processing-overlay">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>
        <h5 class="mt-3 text-dark">Processing...</h5>
    </div>

    <!-- Page Title -->
    <div class="page-title-wave" style="background: linear-gradient(135deg, #0a1f33 0%, #1c3d5a 100%); padding: 120px 0 80px; text-align: center; color: white;">
        <div class="container">
            <h2>Book Appointment</h2>
        </div>
    </div>

    <section class="book-appt py-5">
        <div class="container">
            <div class="row justify-content-center">
                
                <!-- SIDEBAR -->
                <div class="col-lg-4 mb-4">
                    <div class="doctor-list-card">
                        <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">Select Therapis</h5>
                        <div id="doctorList"></div>

                        <!-- Profile Card -->
                        <div id="profileCard" class="mt-4 p-3 bg-light rounded border d-none">
                            <div class="d-flex align-items-center mb-3">
                                <img id="profileAvatar" src="" style="width:50px; height:50px; border-radius:50%; object-fit:cover; margin-right:15px;" onerror="this.src='assets/img/default-expert.png'">
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark" id="profileName"></h6>
                                    <small class="text-muted" id="profileRole"></small>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center bg-white p-2 rounded border">
                                <small class="fw-bold text-secondary">Consultation Fee</small>
                                <span id="feeTextSidebar" class="badge bg-primary">₹0</span>
                            </div>
                            <div class="mt-2 text-center">
                                <small class="text-success"><i class="bi bi-clock"></i> <span id="sessionTime"></span></small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FORM -->
                <div class="col-lg-8">
                    <div class="appointment-card p-5 bg-white shadow-lg rounded-4">
                        <form id="bookingForm">
                            <input type="hidden" id="expert_id">
                            <input type="hidden" id="time">
                            <input type="hidden" id="fee_amount">

                            <div class="mb-4">
                                <label class="form-label fw-bold">Consultation Mode</label>
                                <select class="form-select form-select-lg" id="modeSelect">
                                    <option value="online">Online (Video Call)</option>
                                    <option value="offline">Offline (Clinic Visit)</option>
                                </select>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Full Name</label>
                                    <input type="text" id="inputName" class="form-control" placeholder="Enter full name" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Email Address</label>
                                    <input type="email" id="inputEmail" class="form-control" placeholder="example@mail.com" required>
                                </div>
                              
                            </div>

                            <div class="mt-4">
                                <label class="form-label fw-bold">Select Therapis</label>
                                <select class="form-select form-select-lg" id="doctorSelect" onchange="selectDoctor(this.value)">
                                    <option value="">-- Choose Therapis --</option>
                                    <?php foreach($expertsData as $doc): ?>
                                        <option value="<?php echo $doc['id']; ?>"><?php echo htmlspecialchars($doc['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mt-4">
                                <label class="form-label fw-bold">Select Date</label>
                                <input type="date" id="dateInput" class="form-control" required>
                            </div>
                              <div class="mt-4">
                                <label class="form-label fw-bold">State</label>
                                <input type="text" id="State" class="form-control" placeholder="Enter State" required>
                            </div>
                              <div class="mt-4">
                                <label class="form-label fw-bold">city </label>
                                <input type="text" id="city" placeholder="Enter City" class="form-control" required>
                            </div>
                                 <div class="mt-4">
                                    <label class="form-label fw-bold">Mobile Number</label>
                                    <input type="tel" id="inputPhone" class="form-control" placeholder="Enter mobile number" required>
                                </div>
                            <!-- DYNAMIC SLOTS -->
                            <div class="mt-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <strong>Select Time Slot</strong>
                                    <span>Selected: <b id="selectedSlotText" class="text-primary">—</b></span>
                                </div>
                                <div class="row g-2" id="slotsGrid">
                                    <div class="col-12 text-center text-muted py-3 bg-light rounded small">Please select a doctor and date first.</div>
                                </div>
                                <div id="slotLoading" class="text-center small text-primary mt-2 d-none">
                                    <span class="spinner-border spinner-border-sm"></span> Checking availability...
                                </div>
                            </div>
                            
                            <div class="col-12 mt-3">
                                <label class="form-label fw-bold">Reason for consultation</label>
                                <textarea id="inputReason" class="form-control" rows="2" placeholder="Briefly describe your issue"></textarea>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4 border-top pt-3">
                                <span class="fw-bold fs-5 text-secondary">Total Payable</span>
                                <span class="fw-bold fs-3 text-primary" id="feeText">₹0</span>
                            </div>

                            <button type="button" id="proceedBtn" class="btn btn-primary w-100 py-3 rounded-pill mt-4 fw-bold shadow-sm btn-appt">
                                Proceed to Pay
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <script>
        const doctors = <?php echo json_encode($expertsData); ?>;
        // Inject keys from PHP to JS safely
        const razorpayKey = "<?php echo $keyId; ?>";
        const currency = "<?php echo $currency; ?>";
        const defaultSlots = ['10:00 AM','11:00 AM','12:00 PM','01:00 PM','02:00 PM','03:00 PM','04:00 PM','05:00 PM','06:00 PM'];

        // UI Refs
        const doctorSelect = document.getElementById('doctorSelect');
        const slotsGrid = document.getElementById('slotsGrid');
        const feeText = document.getElementById('feeText');
        const feeTextSidebar = document.getElementById('feeTextSidebar');
        const modeSelect = document.getElementById('modeSelect');
        const profileCard = document.getElementById('profileCard');
        const dateInput = document.getElementById('dateInput');
        
        // Hidden Inputs
        const hiddenExpertId = document.getElementById('expert_id');
        const hiddenTime = document.getElementById('time');
        const hiddenFee = document.getElementById('fee_amount');

        let selectedDoctor = null;
        let selectedMode = 'online';

        // Initialize Date
        const today = new Date().toISOString().split('T')[0];
        dateInput.min = today;
        dateInput.value = today;

        // Render Sidebar
        const doctorListEl = document.getElementById('doctorList');
        function renderDoctorList() {
            doctorListEl.innerHTML = '';
            doctors.forEach(doc => {
                const item = document.createElement('div');
                item.className = 'doctor-item d-flex align-items-center';
                item.dataset.id = doc.id;
                let imgSrc = doc.avatar.replace('../', '');
                
                item.innerHTML = `
                    <img src="${imgSrc}" width="40" height="40" style="border-radius:50%; object-fit:cover; margin-right:12px;" onerror="this.src='assets/img/default-expert.png'">
                    <div>
                        <div class="fw-bold text-dark" style="font-size:0.95rem;">${doc.name}</div>
                        <div class="small text-muted" style="font-size:0.8rem;">${doc.role}</div>
                    </div>
                `;
                item.addEventListener('click', () => selectDoctor(doc.id));
                doctorListEl.appendChild(item);
            });
        }

        // Select Logic
        function selectDoctor(id) {
            if(!id) {
                selectedDoctor = null;
                hiddenExpertId.value = '';
                hiddenFee.value = 500;
                profileCard.classList.add('d-none');
                renderSlots(defaultSlots);
                return;
            }

            selectedDoctor = doctors.find(d => d.id == id);
            
            if(selectedDoctor) {
                hiddenExpertId.value = id;
                if(doctorSelect.value != id) doctorSelect.value = id;
                
                document.querySelectorAll('.doctor-item').forEach(el => el.classList.toggle('active', el.dataset.id == id));

                profileCard.classList.remove('d-none');
                document.getElementById('profileName').innerText = selectedDoctor.name;
                document.getElementById('profileRole').innerText = selectedDoctor.role;
                document.getElementById('sessionTime').innerText = selectedDoctor.sessions;
                
                let profileImgSrc = selectedDoctor.avatar.replace('../', '');
                document.getElementById('profileAvatar').src = profileImgSrc;

                updateFee();
                // Check Availability Dynamically
                checkAvailability();
            }
        }

        function updateFee() {
            if(!selectedDoctor) return;
            const fee = selectedDoctor.fee[selectedMode];
            const text = fee > 0 ? '₹' + fee : 'Free';
            feeText.innerText = text;
            feeTextSidebar.innerText = text;
            hiddenFee.value = fee;
            document.getElementById('proceedBtn').innerText = fee > 0 ? `Pay ₹${fee} & Book` : 'Book Appointment';
        }

        // AJAX: Check Booked Slots
        function checkAvailability() {
            if(!selectedDoctor) return;
            const date = dateInput.value;
            const slots = (selectedDoctor.slots && selectedDoctor.slots.length > 0) ? selectedDoctor.slots : defaultSlots;
            
            document.getElementById('slotLoading').classList.remove('d-none');

            $.post('book-appointment.php', {
                action: 'check_availability',
                expert_id: selectedDoctor.id,
                date: date
            }, function(res) {
                document.getElementById('slotLoading').classList.add('d-none');
                if(res.status === 'success') {
                    renderSlots(slots, res.booked);
                } else {
                    renderSlots(slots, []);
                }
            }, 'json');
        }

        function renderSlots(slotsArray, bookedSlots = []) {
            slotsGrid.innerHTML = '';
            
            slotsArray.forEach(t => {
                const col = document.createElement('div');
                col.className = 'col-6 col-md-3';
                const btn = document.createElement('div');
                
                // Check if booked
                const isBooked = bookedSlots.includes(t);
                
                btn.className = 'slot-btn';
                btn.innerText = t;
                
                if(isBooked) {
                    btn.setAttribute('disabled', true);
                    btn.style.opacity = '0.5';
                    btn.style.textDecoration = 'line-through';
                    btn.innerText += " (Booked)";
                } else {
                    btn.onclick = function() {
                        document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('active'));
                        this.classList.add('active');
                        hiddenTime.value = t;
                        document.getElementById('selectedSlotText').innerText = t;
                    };
                }
                col.appendChild(btn);
                slotsGrid.appendChild(col);
            });
        }

        modeSelect.addEventListener('change', function() {
            selectedMode = this.value;
            updateFee();
        });

        dateInput.addEventListener('change', function() {
            checkAvailability(); // Re-check on date change
        });

        // Init
        renderDoctorList();
        
        // Auto Select from URL
        const urlParams = new URLSearchParams(window.location.search);
        const urlId = "<?php echo $selectedExpertId; ?>";
        
        if(urlId && urlId != '0') {
            document.getElementById('doctorSelect').value = urlId;
            selectDoctor(urlId);
        } else {
            renderSlots(defaultSlots);
        }

        // Payment Process
        document.getElementById('proceedBtn').addEventListener('click', function() {
            // Validate Keys first
            if (!razorpayKey) {
                alert("Payment system error: API Key missing. Please contact admin.");
                return;
            }

            const name = document.getElementById('inputName').value;
            const email = document.getElementById('inputEmail').value;
            const phone = document.getElementById('inputPhone').value;
            const date = document.getElementById('dateInput').value;
            const time = hiddenTime.value;
            const fee = parseFloat(hiddenFee.value);
            const msg = document.getElementById('inputReason').value;

            if(!name || !email || !date || !time) {
                alert("Please select a valid time slot and fill details.");
                return;
            }

            document.getElementById('processing-overlay').style.display = 'flex';

            // 1. Create Order (This now saves data to DB as 'Pending')
            $.post('book-appointment.php', { 
                action: 'create_order', 
                amount: fee,
                name: name,
                email: email,
                phone: phone,
                expert_id: hiddenExpertId.value,
                date: date,
                time: time,
                mode: selectedMode,
                message: msg
            }, function(res) {
                if(res.status === 'success') {
                    // 2. Open Razorpay
                    var options = {
                        "key": razorpayKey, 
                        "amount": fee * 100, 
                        "currency": currency,
                        "name": "IMHRC",
                        "order_id": res.order_id, 
                        "handler": function (response){ verifyPayment(response); },
                        "prefill": { "name": name, "email": email, "contact": phone },
                        "theme": { "color": "#0061ff" },
                        "modal": { "ondismiss": function(){ document.getElementById('processing-overlay').style.display = 'none'; }}
                    };
                    var rzp1 = new Razorpay(options);
                    rzp1.open();
                } else {
                    alert(res.message);
                    document.getElementById('processing-overlay').style.display = 'none';
                }
            }, 'json').fail(function() {
                alert("Server error occurred during order creation.");
                document.getElementById('processing-overlay').style.display = 'none';
            });
        });

        function verifyPayment(response, isFree = false) {
             const formData = {
                action: 'verify_payment',
                razorpay_payment_id: response.razorpay_payment_id,
                razorpay_order_id: response.razorpay_order_id,
                razorpay_signature: response.razorpay_signature,
                name: document.getElementById('inputName').value,
                email: document.getElementById('inputEmail').value,
                phone: document.getElementById('inputPhone').value,
                date: document.getElementById('dateInput').value,
                expert_id: hiddenExpertId.value,
                time: hiddenTime.value,
                mode: selectedMode,
                message: "Booking via Website",
                is_free: isFree
            };
            $.post('book-appointment.php', formData, function(res) {
                document.getElementById('processing-overlay').style.display = 'none';
                if(res.status === 'success') {
                    alert(res.message);
                    window.location.href = 'index.php';
                } else {
                    alert(res.message);
                }
            }, 'json').fail(function() {
                alert("Payment successful but verification failed locally. Contact support.");
                document.getElementById('processing-overlay').style.display = 'none';
            });
        }
    </script>
</body>
</html>