<?php
require_once 'includes/db.php';

echo "=== Creating Missing Tables ===\n\n";

$conn = db();

// Tables to create
$tables = [
    'articles' => "CREATE TABLE IF NOT EXISTS articles (
        ArticleId INT(11) NOT NULL AUTO_INCREMENT,
        Title VARCHAR(255) NOT NULL,
        Slug VARCHAR(255) NOT NULL,
        Content TEXT,
        Summary TEXT,
        Author VARCHAR(100),
        Category VARCHAR(100),
        Status VARCHAR(50) DEFAULT 'draft',
        ViewCount INT(11) DEFAULT 0,
        CreatedBy INT(11),
        UpdatedBy INT(11),
        CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UpdatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (ArticleId),
        UNIQUE KEY unique_slug (Slug),
        KEY idx_status (Status),
        KEY idx_category (Category),
        KEY idx_created (CreatedAt)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    
    'events' => "CREATE TABLE IF NOT EXISTS events (
        EventId INT(11) NOT NULL AUTO_INCREMENT,
        Title VARCHAR(255) NOT NULL,
        Description TEXT,
        EventType VARCHAR(50),
        StartDate DATETIME,
        EndDate DATETIME,
        Location VARCHAR(255),
        Status VARCHAR(50) DEFAULT 'upcoming',
        CreatedBy INT(11),
        UpdatedBy INT(11),
        CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UpdatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (EventId),
        KEY idx_status (Status),
        KEY idx_start_date (StartDate)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    
    'media' => "CREATE TABLE IF NOT EXISTS media (
        MediaId INT(11) NOT NULL AUTO_INCREMENT,
        Title VARCHAR(255) NOT NULL,
        FilePath VARCHAR(500) NOT NULL,
        MediaType VARCHAR(50),
        FileSize INT(11),
        MimeType VARCHAR(100),
        AltText VARCHAR(255),
        Status VARCHAR(50) DEFAULT 'active',
        UploadedBy INT(11),
        CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UpdatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (MediaId),
        KEY idx_media_type (MediaType),
        KEY idx_status (Status)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    
    'team_members' => "CREATE TABLE IF NOT EXISTS team_members (
        TeamMemberId INT(11) NOT NULL AUTO_INCREMENT,
        Name VARCHAR(255) NOT NULL,
        Position VARCHAR(255),
        Department VARCHAR(255),
        Email VARCHAR(255),
        Phone VARCHAR(50),
        Bio TEXT,
        Specialization TEXT,
        Experience VARCHAR(100),
        Status VARCHAR(50) DEFAULT 'active',
        IsLeadership TINYINT(1) DEFAULT 0,
        CreatedBy INT(11),
        UpdatedBy INT(11),
        CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UpdatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (TeamMemberId),
        KEY idx_status (Status),
        KEY idx_leadership (IsLeadership)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    
    'appointments' => "CREATE TABLE IF NOT EXISTS appointments (
        AppointmentId INT(11) NOT NULL AUTO_INCREMENT,
        PatientName VARCHAR(255) NOT NULL,
        PatientEmail VARCHAR(255),
        PatientPhone VARCHAR(50),
        DoctorId INT(11),
        AppointmentDate DATETIME,
        AppointmentType VARCHAR(100),
        Status VARCHAR(50) DEFAULT 'pending',
        Notes TEXT,
        CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UpdatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (AppointmentId),
        KEY idx_status (Status),
        KEY idx_date (AppointmentDate)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
];

foreach ($tables as $tableName => $sql) {
    // Check if table exists
    $checkSql = "SHOW TABLES LIKE '$tableName'";
    $result = $conn->query($checkSql);
    
    if ($result && $result->num_rows > 0) {
        echo "✓ Table '$tableName' already exists\n";
    } else {
        echo "Creating table '$tableName'...\n";
        if ($conn->query($sql)) {
            echo "✓ Table '$tableName' created successfully!\n";
        } else {
            echo "✗ Error creating '$tableName': " . $conn->error . "\n";
        }
    }
}

echo "\n=== Migration Completed! ===\n";
?>
