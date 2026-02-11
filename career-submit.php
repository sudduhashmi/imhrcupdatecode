<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // =======================
    // 1️⃣ Collect Form Data
    // =======================
    $centre_type       = $_POST['centre_type'];
    $applicant_type    = $_POST['applicant_type'];
    $full_name         = $_POST['full_name'];
    $organization_name = $_POST['organization_name'];
    $organization_type = $_POST['organization_type'];
    $email             = $_POST['email'];
    $phone             = $_POST['phone'];
    $proposed_territory= $_POST['proposed_territory'];
    $profile           = $_POST['profile'];
    $experience        = $_POST['experience'];
    $reason            = $_POST['reason'];
    $consent           = isset($_POST['consent']) ? 'Yes' : 'No';

    // =======================
    // 2️⃣ SMTP CONFIG
    // =======================
    $smtpHost = "smtp.gmail.com";
    $smtpUser = "info.imhrc@gmail.com"; // ADMIN EMAIL
    $smtpPass = "woptqgqmoljnbyjs";    // APP PASSWORD
    $smtpPort = 587;

    // =======================
    // 3️⃣ ADMIN EMAIL
    // =======================
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = $smtpHost;
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtpUser;
        $mail->Password   = $smtpPass;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $smtpPort;

        $mail->setFrom($smtpUser, "IMHRC Franchise Enquiry");
        $mail->addAddress($smtpUser); // Admin inbox
        $mail->addReplyTo($email, $full_name);

        // =======================
        // Attachments (if uploaded)
        // =======================
        $files = ['org_pan','reg_cert','auth_aadhar','auth_pan'];
        foreach ($files as $file) {
            if (!empty($_FILES[$file]['name'])) {
                $mail->addAttachment($_FILES[$file]['tmp_name'], $_FILES[$file]['name']);
            }
        }

        // Email Body (Admin)
        $mail->isHTML(true);
        $mail->Subject = "New Franchise Enquiry - $full_name";
        $mail->Body = "
            <h3>New Franchise Enquiry Received</h3>
            <p><b>Centre Type:</b> {$centre_type}</p>
            <p><b>Applicant Type:</b> {$applicant_type}</p>
            <p><b>Full Name:</b> {$full_name}</p>
            <p><b>Organization Name:</b> {$organization_name}</p>
            <p><b>Organization Type:</b> {$organization_type}</p>
            <p><b>Email:</b> {$email}</p>
            <p><b>Phone:</b> {$phone}</p>
            <p><b>Proposed Territory:</b> {$proposed_territory}</p>
            <p><b>Professional Background / Profile:</b><br>{$profile}</p>
            <p><b>Relevant Experience:</b><br>{$experience}</p>
            <p><b>Reason for Interest:</b><br>{$reason}</p>
            <p><b>Consent Given:</b> {$consent}</p>
        ";

        $mail->send();

    } catch (Exception $e) {
        header("Location: grow-with-us.php?error=1");
        exit;
    }

    // =======================
    // 4️⃣ USER CONFIRMATION EMAIL
    // =======================
    $userMail = new PHPMailer(true);

    try {
        $userMail->isSMTP();
        $userMail->Host       = $smtpHost;
        $userMail->SMTPAuth   = true;
        $userMail->Username   = $smtpUser;
        $userMail->Password   = $smtpPass;
        $userMail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $userMail->Port       = $smtpPort;

        $userMail->setFrom($smtpUser, "IMHRC Team");
        $userMail->addAddress($email, $full_name);
        $userMail->addReplyTo($smtpUser, "IMHRC Team");

        $userMail->isHTML(true);
        $userMail->Subject = "IMHRC Franchise Enquiry Received";
        $userMail->Body = "
            <p>Hi <b>{$full_name}</b>,</p>
            <p>Thank you for submitting your IMHRC Franchise Enquiry for <b>{$centre_type}</b>.</p>
            <p>Our team will review your application and contact you if your profile aligns with our franchise requirements.</p>
            <br>
            <p>Regards,<br>IMHRC Team</p>
        ";

        $userMail->send();

        header("Location: grow-with-us.php?success=1");
        exit;

    } catch (Exception $e) {
        header("Location: grow-with-us.php?error=1");
        exit;
    }

}
?>
