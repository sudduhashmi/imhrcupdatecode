<?php
/**
 * Database Connection
 * Handles MySQL connection for IMHRC Admin Panel
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');   // blank hota hai
define('DB_NAME', 'imhrcorg'); // jo DB tumne banaya

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die(json_encode([
        'success' => false,
        'message' => 'Database Connection Failed: ' . $conn->connect_error
    ]));
}

// Set charset to UTF8
$conn->set_charset('utf8mb4');

// Function to sanitize input
function sanitize($input) {
    global $conn;
    return mysqli_real_escape_string($conn, trim($input));
}

// Function to execute queries
function query($sql) {
    global $conn;
    return $conn->query($sql);
}

// Function to get single row
function getRow($sql) {
    $result = query($sql);
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}

// Function to get all rows
function getRows($sql) {
    $result = query($sql);
    $data = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}

// Function to insert/update/delete
function execute($sql) {
    global $conn;
    if ($conn->query($sql) === TRUE) {
        return [
            'success' => true,
            'message' => 'Operation successful',
            'insert_id' => $conn->insert_id,
            'affected_rows' => $conn->affected_rows
        ];
    }
    return [
        'success' => false,
        'message' => 'Error: ' . $conn->error
    ];
}

// Function to get last insert ID
function getLastId() {
    global $conn;
    return $conn->insert_id;
}

// Function to get affected rows
function getAffectedRows() {
    global $conn;
    return $conn->affected_rows;
}

// Function to get database connection
function getDbConnection() {
    global $conn;
    return $conn;
}

// Alias for shorter access
function db() {
    global $conn;
    return $conn;
}

// Check if a table exists in the current database
function tableExists($tableName) {
    global $conn;
    if (!$tableName) return false;
    $tableName = sanitize($tableName);
    // Use INFORMATION_SCHEMA for reliability across MySQL versions
    $dbName = DB_NAME;
    $sql = "SELECT 1 FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='$dbName' AND TABLE_NAME='$tableName' LIMIT 1";
    $res = $conn->query($sql);
    return $res && $res->num_rows > 0;
}
?>
