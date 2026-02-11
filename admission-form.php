<?php
session_start();
require_once 'admin/includes/db.php';

// --- 1. URL REWRITE LOGIC (Hide ID, Show Name) ---
if (isset($_GET['program']) && is_numeric($_GET['program'])) {
    $progId = (int) $_GET['program'];
    $stmt = $conn->prepare("SELECT Title FROM academic_programs WHERE ProgramId = ?");
    $stmt->bind_param("i", $progId);
    $stmt->execute();
    $stmt->bind_result($progTitle);
    if ($stmt->fetch()) {
        // Redirect to same page but with Course Name instead of ID
        $cleanTitle = str_replace(' ', '-', $progTitle);
        header("Location: admission-form.php?course=" . urlencode($cleanTitle));
        exit;
    }
    $stmt->close();
}

// Get Course Name from URL for Display
$urlCourseName = isset($_GET['course']) ? str_replace('-', ' ', htmlspecialchars($_GET['course'])) : '';

// --- FETCH USER DATA IF LOGGED IN ---
$user = [
    'Name' => '',
    'Email' => '',
    'Phone' => '',
    'Address' => '',
    'City' => '',
    'State' => '',
    'ZipCode' => ''
];

if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    $uQ = $conn->query("SELECT * FROM userlogin WHERE UserId = $uid");
    if ($uQ && $uRow = $uQ->fetch_assoc()) {
        $user = array_merge($user, $uRow);
    }
}

// --- HANDLE FORM SUBMISSION ---
$msg = "";
$msgType = "";
$submittedCode = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Personal Details
    $cName = $_POST['candidate_name'];
    $fName = $_POST['father_name'];
    $mName = $_POST['mother_name'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $category = $_POST['category'];
    $employed = $_POST['employed'];
    $aadhaar = $_POST['aadhaar'];

    // Address
    $empName = $_POST['employer_name'];
    $desig = $_POST['designation'];
    $contact = $_POST['contact_number'];
    $altContact = $_POST['alt_number'];
    $email = $_POST['email'];
    $currAddr = $_POST['current_address'];
    $permAddr = $_POST['permanent_address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $nation = $_POST['nationality'];
    $country = $_POST['country'];
    $pin = $_POST['pincode'];

    // Course
    $cType = $_POST['course_type'];
    $faculty = $_POST['faculty'];
    $course = $_POST['course'];
    $stream = $_POST['stream'];
    $year = $_POST['year'];
    $month = $_POST['month_session'];
    $session = $_POST['session'];
    $hostel = $_POST['hostel'];
    $fee = $_POST['course_fee'];
    $duration = $_POST['duration'];

    // File Upload Helper
    function uploadFile($fileKey)
    {
        if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === 0) {
            $dir = 'assets/uploads/admissions/';
            if (!file_exists($dir))
                mkdir($dir, 0777, true);
            $name = time() . '_' . basename($_FILES[$fileKey]['name']);
            if (move_uploaded_file($_FILES[$fileKey]['tmp_name'], $dir . $name)) {
                return $dir . $name;
            }
        }
        return '';
    }

    $photo = uploadFile('photo');
    $sig = uploadFile('signature');
    $aadhaarF = uploadFile('aadhaar_front');
    $aadhaarB = uploadFile('aadhaar_back');

    // Qualifications (JSON)
    $qualifications = [];
    $exams = $_POST['exam'] ?? [];
    $years = $_POST['q_year'] ?? [];
    $boards = $_POST['board'] ?? [];
    $totals = $_POST['total'] ?? [];
    $marks = $_POST['obtained'] ?? [];
    $grades = $_POST['grade'] ?? [];

    for ($i = 0; $i < 5; $i++) {
        $docPath = '';
        if (isset($_FILES['q_doc']['name'][$i]) && $_FILES['q_doc']['error'][$i] === 0) {
            $dir = 'assets/uploads/admissions/';
            if (!file_exists($dir))
                mkdir($dir, 0777, true);
            $name = time() . '_' . $i . '_' . basename($_FILES['q_doc']['name'][$i]);
            move_uploaded_file($_FILES['q_doc']['tmp_name'][$i], $dir . $name);
            $docPath = $dir . $name;
        }

        $qualifications[] = [
            'exam' => $exams[$i] ?? '',
            'year' => $years[$i] ?? '',
            'board' => $boards[$i] ?? '',
            'total' => $totals[$i] ?? '',
            'obtained' => $marks[$i] ?? '',
            'grade' => $grades[$i] ?? '',
            'doc' => $docPath
        ];
    }
    $qualJson = json_encode($qualifications);

    // Insert
    $sql = "INSERT INTO admissions (
        UserId, CandidateName, FatherName, MotherName, DOB, Gender, Category, IsEmployed, AadhaarNumber,
        EmployerName, Designation, ContactNumber, AlternateNumber, Email, CurrentAddress, PermanentAddress, City, State, Nationality, Country, Pincode,
        CourseType, Faculty, Course, Stream, Year, MonthSession, Session, HostelFacility, CourseFee, Duration,
        PhotoPath, SignaturePath, AadhaarFrontPath, AadhaarBackPath, Qualifications
    ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

    $stmt = $conn->prepare($sql);
    $uid = $_SESSION['user_id'] ?? NULL;
    $stmt->bind_param(
        "isssssssssssssssssssssssssssssssssss",
        $uid,
        $cName,
        $fName,
        $mName,
        $dob,
        $gender,
        $category,
        $employed,
        $aadhaar,
        $empName,
        $desig,
        $contact,
        $altContact,
        $email,
        $currAddr,
        $permAddr,
        $city,
        $state,
        $nation,
        $country,
        $pin,
        $cType,
        $faculty,
        $course,
        $stream,
        $year,
        $month,
        $session,
        $hostel,
        $fee,
        $duration,
        $photo,
        $sig,
        $aadhaarF,
        $aadhaarB,
        $qualJson
    );

    if ($stmt->execute()) {
        $insertId = $conn->insert_id;
        $submittedCode = "IMHRC-" . date('Y') . "-" . str_pad($insertId, 4, '0', STR_PAD_LEFT);

        $msg = "<h4 class='alert-heading fw-bold mb-3'>Submission Successful!</h4>
                <p>You have successfully applied for: <strong>$course ($stream)</strong></p>
                <div class='bg-white p-3 rounded text-dark border d-inline-block mt-2'>
                    <strong>Application Code:</strong> <span class='text-primary fs-5'>#$submittedCode</span>
                </div>
                <p class='mb-0 mt-3 small'>Please save this code for future reference.</p>";
        $msgType = "success";
    } else {
        $msg = "Error submitting form: " . $conn->error;
        $msgType = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- SEO Meta Tags -->
    <meta name="description"
        content="Apply for academic programs at IMHRC. Fill out the admission form for Certificate, Diploma, Undergraduate, and Postgraduate courses in Psychology and Mental Health.">
    <meta name="keywords"
        content="IMHRC Admission, Psychology Courses, Mental Health Education, Diploma in Psychology, Undergraduate Admission, Postgraduate Admission, Online Courses">

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

    <title>Admission Form - IMHRC</title>

    <style>
        .form-control {
            /* height: 50px; */
            color: #324cc5;
            border: 1px solid #cccccc;
            background-color: transparent;
            border-radius: 0;
            font-size: 16px;
            /* padding: 10px 20px; */
            width: 100%;
            border-radius: 5px;
        }

        .form-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
            padding: 25px;
            margin-bottom: 30px;
        }

        .section-title {
            font-weight: 700;
            color: #1d2b53;
            border-left: 5px solid #dc3545;
            padding-left: 10px;
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
        }

        .file-box {
            border: 2px dashed #dee2e6;
            padding: 12px;
            border-radius: 10px;
            text-align: center;
            font-size: 14px;
        }

        .submit-btn {
            background: #1d274b;
            color: #fff;
            font-size: 18px;
            padding: 12px;
            border-radius: 10px;
            border: none;
            width: 100%;
        }

        .submit-btn:hover {
            background: #ffb800;
        }

        .apply-banner {
            background: #e0f2fe;
            color: #0284c7;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            text-align: center;
            border: 1px solid #bae6fd;
        }
    </style>
</head>

<body>

    <?php include 'includes/header.php'; ?>

    <!-- Start Page Title Area -->
    <div class="page-title-wave">
        <div class="container">
            <h2>Admission Form</h2>
        </div>
        <svg class="wave" viewBox="0 0 1440 320" preserveAspectRatio="none">
            <path fill="#f8f9fa" fill-opacity="1"
                d="M0,64L48,90.7C96,117,192,171,288,170.7C384,171,480,117,576,85.3C672,53,768,43,864,74.7C960,107,1056,181,1152,192C1248,203,1344,149,1392,122.7L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
            </path>
        </svg>
    </div>

    <section class="py-5 bg-light">
        <div class="container">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4 p-md-5">

                    <h3 class="text-center fw-bold mb-4">🎓 Student Admission Form</h3>

                    <!-- DYNAMIC HEADER IF CAME FROM COURSE LINK -->
                    <?php if ($urlCourseName && !$msg): ?>
                        <div class="apply-banner">
                            <i class="bi bi-info-circle-fill me-2"></i> You are applying for:
                            <strong><?php echo $urlCourseName; ?></strong>
                        </div>
                    <?php endif; ?>

                    <?php if ($msg): ?>
                        <div class="alert alert-<?php echo $msgType; ?> text-center shadow-sm border-0"><?php echo $msg; ?>
                        </div>

                        <!-- Hide form if success -->
                        <?php if ($msgType == 'success'): ?>
                            <div class="text-center mt-4">
                                <a href="index.php" class="btn btn-outline-primary me-2">Go Home</a>
                                <a href="admission-form.php" class="btn btn-primary">Apply Another</a>
                            </div>
                        <?php endif; ?>

                    <?php endif; ?>

                    <?php if ($msgType != 'success'): ?>
                        <form method="POST" enctype="multipart/form-data">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Candidate Name *</label>
                                    <input type="text" name="candidate_name" class="form-control"
                                        placeholder="Enter full name" value="<?php echo htmlspecialchars($user['Name']); ?>"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Father's Name *</label>
                                    <input type="text" name="father_name" class="form-control"
                                        placeholder="Enter father's name" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Mother's Name *</label>
                                    <input type="text" name="mother_name" class="form-control"
                                        placeholder="Enter mother's name" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Date of Birth *</label>
                                    <input type="date" name="dob" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Gender *</label>
                                    <select name="gender" class="form-select" required>
                                        <option value="">Select Gender</option>
                                        <option>Male</option>
                                        <option>Female</option>
                                        <option>Other</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Category *</label>
                                    <select name="category" class="form-select" required>
                                        <option value="">Select Category</option>
                                        <option>General</option>
                                        <option>OBC</option>
                                        <option>SC</option>
                                        <option>ST</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Aadhaar Number *</label>
                                    <input type="text" name="aadhaar" class="form-control" maxlength="12"
                                        placeholder="Enter 12 digit Aadhaar" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Aadhaar Front *</label>
                                    <input type="file" name="aadhaar_front" class="form-control" accept="image/*" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Aadhaar Back *</label>
                                    <input type="file" name="aadhaar_back" class="form-control" accept="image/*" required>
                                </div>
                            </div>

                            <div class="row pt-3">

                                <!-- Are you employed -->
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Are you employed? *</label>
                                    <div class="d-flex gap-4 mt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="employed" id="employedYes"
                                                value="Yes" required>
                                            <label class="form-check-label" for="employedYes">Yes</label>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="employed" id="employedNo"
                                                value="No">
                                            <label class="form-check-label" for="employedNo">No</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Employer Name -->
                                <div class="col-md-4 employed-fields d-none">
                                    <label class="form-label">Employer Name *</label>
                                    <input type="text" name="employer_name" id="employerName" class="form-control"
                                        placeholder="Employer Name">
                                </div>

                                <!-- Designation -->
                                <div class="col-md-4 employed-fields d-none">
                                    <label class="form-label">Designation *</label>
                                    <input type="text" name="designation" id="designation" class="form-control"
                                        placeholder="Designation">
                                </div>

                            </div>


                            <div class="row g-3 py-3">
                                <div class="col-md-6">
                                    <label class="form-label">Passport Size Photo *</label>
                                    <input type="file" name="photo" class="form-control" accept="image/*" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Signature *</label>
                                    <input type="file" name="signature" class="form-control" accept="image/*" required>
                                </div>

                            </div>

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label">Contact Number *</label>
                                    <input type="tel" name="contact_number" class="form-control"
                                        placeholder="Contact Number" value="<?php echo htmlspecialchars($user['Phone']); ?>"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Alternate No. *</label>
                                    <input type="tel" name="alt_number" class="form-control" placeholder="Alternate No."
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email Address *</label>
                                    <input type="email" name="email" class="form-control" placeholder="Email Address"
                                        value="<?php echo htmlspecialchars($user['Email']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Current Address *</label>
                                    <input type="text" name="current_address" class="form-control"
                                        placeholder="Current Address"
                                        value="<?php echo htmlspecialchars($user['Address'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Permanent Address *</label>
                                    <input type="text" name="permanent_address" class="form-control"
                                        placeholder="Permanent Address" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">City *</label>
                                    <input type="text" name="city" class="form-control" placeholder="City"
                                        value="<?php echo htmlspecialchars($user['City'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">State *</label>
                                    <input type="text" name="state" class="form-control" placeholder="State"
                                        value="<?php echo htmlspecialchars($user['State'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Nationality *</label>
                                    <input type="text" name="nationality" class="form-control" placeholder="Nationality"
                                        value="Indian" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Country *</label>
                                    <select name="country" class="form-select" required>
                                        <option value="India">India</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Pincode *</label>
                                    <input type="text" name="pincode" class="form-control" placeholder="Pincode"
                                        value="<?php echo htmlspecialchars($user['ZipCode'] ?? ''); ?>" required>
                                </div>
                            </div>

                            <div class="row g-3 py-3">
                                <div class="col-md-6">
                                    <label class="form-label">Course Type *</label>
                                    <select name="course_type" class="form-select" required>
                                        <option value="">Select Course Type</option>
                                        <option>Certificate</option>
                                        <option>Diploma</option>
                                        <option>Undergraduate</option>
                                        <option>Postgraduate</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Faculty *</label>
                                    <select name="faculty" class="form-select" required>
                                        <option value="">Select Faculty</option>
                                        <option>Arts</option>
                                        <option>Science</option>
                                        <option>Commerce</option>
                                        <option>Management</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Course *</label>
                                    <select name="course" class="form-select" required>
                                        <option value="">Select Course</option>
                                        <option>B.A</option>
                                        <option>B.Sc</option>
                                        <option>B.Com</option>
                                        <option>M.A</option>
                                        <option>M.Sc</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Stream *</label>
                                    <select name="stream" class="form-select" required>
                                        <option value="">Select Stream</option>
                                        <option>General</option>
                                        <option>Computer Science</option>
                                        <option>Biology</option>
                                        <option>Commerce</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Year *</label>
                                    <select name="year" class="form-select" required>
                                        <option>2024</option>
                                        <option selected>2025</option>
                                        <option>2026</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Month Session *</label>
                                    <select name="month_session" class="form-select" required>
                                        <option>January</option>
                                        <option>April</option>
                                        <option selected>July</option>
                                        <option>October</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Session *</label>
                                    <select name="session" class="form-select" required>
                                        <option>2024-25</option>
                                        <option selected>2025-26</option>
                                        <option>2026-27</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Hostel Facility *</label>
                                    <select name="hostel" class="form-select" required>
                                        <option>No</option>
                                        <option>Yes</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Course Fee *</label>
                                    <input type="text" name="course_fee" class="form-control" placeholder="Enter course fee"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Duration *</label>
                                    <input type="text" name="duration" class="form-control"
                                        placeholder="Eg: 1 Year / 6 Months" required>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Examination</th>
                                            <th>Year</th>
                                            <th>Board / University</th>
                                            <th>Total Marks</th>
                                            <th>Marks Obtained</th>
                                            <th>Grade</th>
                                            <th>Document</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $exams = ['Secondary', 'Sr. Secondary', 'Graduation', 'Post Graduation', 'Other'];
                                        foreach ($exams as $key => $exam):
                                            ?>
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="exam[<?php echo $key; ?>]"
                                                        value="<?php echo $exam; ?>">
                                                    <?php echo $exam; ?>
                                                </td>
                                                <td><input name="q_year[<?php echo $key; ?>]" class="form-control"
                                                        placeholder="Year"></td>
                                                <td><input name="board[<?php echo $key; ?>]" class="form-control"
                                                        placeholder="Board"></td>
                                                <td><input name="total[<?php echo $key; ?>]" class="form-control"
                                                        placeholder="Total"></td>
                                                <td><input name="obtained[<?php echo $key; ?>]" class="form-control"
                                                        placeholder="Obtained"></td>
                                                <td><input name="grade[<?php echo $key; ?>]" class="form-control"
                                                        placeholder="Grade"></td>
                                                <td><input type="file" name="q_doc[<?php echo $key; ?>]" class="form-control">
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- ================= DECLARATION ================= -->
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" required>
                                <label class="form-check-label">
                                    I hereby declare that all the information provided above is true and correct.
                                </label>
                            </div>

                            <!-- ================= SUBMIT ================= -->
                            <div class="text-center mt-4">
                                <button type="submit" class="submit-btn">Submit Admission Form</button>
                            </div>

                        </form>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <!-- JS Files -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/meanmenu.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/magnific-popup.min.js"></script>
    <script src="assets/js/custom.js"></script>
    <script>
        $(document).ready(function () {

            $('input[name="employed"]').on('change', function () {

                if ($('#employedYes').is(':checked')) {
                    $('.employed-fields').removeClass('d-none');
                    $('#employerName, #designation').prop('required', true);
                } else {
                    $('.employed-fields').addClass('d-none');
                    $('#employerName, #designation')
                        .prop('required', false)
                        .val('');
                }

            });

        });
    </script>

</body>

</html>