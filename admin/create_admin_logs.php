<?php
require_once 'includes/db.php';

echo "Creating admin_logs table...\n\n";

$conn = db();

// Check if table exists
$checkSql = "SHOW TABLES LIKE 'admin_logs'";
$result = $conn->query($checkSql);

if ($result && $result->num_rows > 0) {
    echo "✓ admin_logs table already exists!\n";
} else {
    echo "Creating admin_logs table...\n";
    
    $sql = "CREATE TABLE IF NOT EXISTS admin_logs (
      LogId INT(11) NOT NULL AUTO_INCREMENT,
      AdminId INT(11) NOT NULL,
      Action VARCHAR(100) NOT NULL,
      Details TEXT,
      IpAddress VARCHAR(45),
      UserAgent VARCHAR(255),
      CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (LogId),
      KEY idx_admin (AdminId),
      KEY idx_created (CreatedAt)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    if ($conn->query($sql)) {
        echo "✓ admin_logs table created successfully!\n";
    } else {
        echo "✗ Error creating admin_logs: " . $conn->error . "\n";
        exit(1);
    }
}

echo "\n✓ Migration completed successfully!\n";
?>
