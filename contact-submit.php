<?php
require_once __DIR__ . '/includes/send-mail.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit("Invalid Request");
}

$name    = $_POST['name'] ?? '';
$email   = $_POST['email'] ?? '';
$subject = $_POST['msg_subject'] ?? '';
$phone   = $_POST['phone_number'] ?? '';
$message = $_POST['message'] ?? '';

// ---------------- ADMIN MAIL ----------------
$adminBody = "
<h3>New Contact Message</h3>
<p><b>Name:</b> $name</p>
<p><b>Email:</b> $email</p>
<p><b>Phone:</b> $phone</p>
<p><b>Subject:</b> $subject</p>
<p><b>Message:</b><br>$message</p>
";

$sentAdmin = sendMail(
    "info.imhrc@gmail.com",
    "Contact Us: $subject",
    $adminBody,
    "IMHRC Contact Form"
);

// ---------------- USER AUTO-REPLY ----------------
$userBody = "
<p>Hi <b>$name</b>,</p>

<p>Thank you for contacting <b>IMHRC</b>.</p>

<p>We have received your message and our team will get back to you shortly.</p>

<hr>
<p><b>Your Message:</b></p>
<p>$message</p>

<p>Regards,<br>
IMHRC Team</p>
";

$sentUser = sendMail(
    $email,                       // 👈 USER EMAIL
    "We received your message",   // 👈 USER SUBJECT
    $userBody,
    "IMHRC Support"
);

// ---------------- FINAL RESPONSE ----------------
if ($sentAdmin && $sentUser) {
    echo "✅ Message sent successfully!";
} else {
    echo "❌ Message sending failed. Please try again.";
}
