<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "job_portal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h2>Updating Job Portal Database...</h2>";

// Read SQL file
$sql_file = file_get_contents('update_database.sql');

// Split SQL file into individual statements
$statements = explode(';', $sql_file);

// Execute each statement
$success = true;
foreach ($statements as $statement) {
    $statement = trim($statement);
    if (!empty($statement)) {
        if ($conn->query($statement) === TRUE) {
            echo "<p>Success: " . htmlspecialchars($statement) . "</p>";
        } else {
            echo "<p>Error: " . htmlspecialchars($statement) . "<br>" . $conn->error . "</p>";
            $success = false;
        }
    }
}

if ($success) {
    echo "<h3>Database updated successfully!</h3>";
    echo "<p>The following features have been added:</p>";
    echo "<ul>";
    echo "<li>Application status tracking</li>";
    echo "<li>Application cancellation with reason</li>";
    echo "<li>Last updated timestamp for applications</li>";
    echo "</ul>";
} else {
    echo "<h3>Database update completed with errors. Please check the messages above.</h3>";
}

// Close connection
$conn->close();

echo "<p><a href='view_applications.php'>Go to View Applications</a></p>";
echo "<p><a href='application_status.php'>Go to Application Status Page</a></p>";
?>