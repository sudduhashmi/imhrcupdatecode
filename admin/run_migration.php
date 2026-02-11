<?php
require_once 'includes/db.php';

echo "Running migration to add IsDeleted column...\n\n";

$conn = db();

// Check if column exists
$checkSql = "SHOW COLUMNS FROM admins LIKE 'IsDeleted'";
$result = $conn->query($checkSql);

if ($result && $result->num_rows > 0) {
    echo "✓ IsDeleted column already exists!\n";
} else {
    echo "Adding IsDeleted column...\n";
    $sql = "ALTER TABLE admins ADD COLUMN IsDeleted TINYINT(1) DEFAULT 0 AFTER IsActive";
    if ($conn->query($sql)) {
        echo "✓ IsDeleted column added successfully!\n";
    } else {
        echo "✗ Error adding IsDeleted: " . $conn->error . "\n";
    }
}

// Check DeletedAt column
$checkSql2 = "SHOW COLUMNS FROM admins LIKE 'DeletedAt'";
$result2 = $conn->query($checkSql2);

if ($result2 && $result2->num_rows > 0) {
    echo "✓ DeletedAt column already exists!\n";
} else {
    echo "Adding DeletedAt column...\n";
    $sql2 = "ALTER TABLE admins ADD COLUMN DeletedAt DATETIME NULL AFTER IsDeleted";
    if ($conn->query($sql2)) {
        echo "✓ DeletedAt column added successfully!\n";
    } else {
        echo "✗ Error adding DeletedAt: " . $conn->error . "\n";
    }
}

// Update existing records
echo "\nUpdating existing records...\n";
$updateSql = "UPDATE admins SET IsDeleted = 0 WHERE IsDeleted IS NULL";
if ($conn->query($updateSql)) {
    echo "✓ Updated " . $conn->affected_rows . " records\n";
}

echo "\n✓ Migration completed successfully!\n";
?>
