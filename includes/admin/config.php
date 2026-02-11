<?php
$host = "localhost";
$username = "root";      // ✅ XAMPP default
$password = "";          // ✅ blank
$database = "imhrcorg"; // jo aapne banayi hai

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
