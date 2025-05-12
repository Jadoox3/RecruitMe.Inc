<?php
$db_host = 'localhost';
$db_user = 'your_username';
$db_pass = 'your_password';
$db_name = 'job_applications';

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]));
}

// Create applications table if not exists
$sql = "CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    position VARCHAR(100) NOT NULL,
    experience TEXT NOT NULL,
    education TEXT NOT NULL,
    cover_letter TEXT,
    resume_path VARCHAR(255),
    cover_letter_path VARCHAR(255),
    status VARCHAR(20) DEFAULT 'Pending',
    cancellation_reason TEXT,
    application_date DATETIME DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($sql)) {
    die(json_encode(['status' => 'error', 'message' => 'Error creating table: ' . $conn->error]));
}
?>