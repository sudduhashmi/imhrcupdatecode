<?php
require 'includes/send-mail.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ignou-curriculum-internship.php");
    exit;
}

/* ---- FORM DATA ---- */
$email = $_POST['email'];
$prefix = $_POST['prefix'];
$name = $_POST['name'];
$gender = $_POST['gender'];
$age = $_POST['age'];
$contact = $_POST['contact'];
$address = $_POST['address'];
$enrolment = $_POST['enrolment'];
$rc = $_POST['regional_centre'];
$course = $_POST['course'];
$course_code = $_POST['course_code'];
$payment_mode = $_POST['payment_mode'];
$transaction_id = $_POST['transaction_id'];

/* ---- FILE UPLOAD SAME AS BEFORE ---- */
$uploadDir = 'uploads/internship/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

$filesUploaded = [];
$fileFields = ['reference_letter','internship_letter','transaction_receipt','id_proof','photo'];

foreach ($fileFields as $fileField) {
    if(isset($_FILES[$fileField]) && $_FILES[$fileField]['error'] == 0){
        $fileName = time().'_'.basename($_FILES[$fileField]['name']);
        move_uploaded_file($_FILES[$fileField]['tmp_name'], $uploadDir.$fileName);
        $filesUploaded[$fileField] = $uploadDir.$fileName;
    }
}

/* ---- EMAIL BODY ---- */
$adminBody = "<h3>New Internship Registration</h3>
<p>Name: $prefix $name</p>
<p>Email: $email</p>";

foreach($filesUploaded as $key=>$file){
    $adminBody .= "<p>$key: <a href='$file'>Download</a></p>";
}

$sentAdmin = sendMail('info.imhrc@gmail.com', 'New Internship Registration', $adminBody, 'IMHRC Form');
$sentUser  = sendMail($email, 'Internship Registration Received',
            "Hi $name,<br>Thank you for registering.", 'IMHRC');

if($sentAdmin && $sentUser){
    header("Location: ignou-curriculum-internship.php?success=1");
} else {
    header("Location: ignou-curriculum-internship.php?error=1");
}
exit;
