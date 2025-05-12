<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "job_portal";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get application ID and new status from POST data
    $application_id = isset($_POST["application_id"]) ? intval($_POST["application_id"]) : 0;
    $new_status = isset($_POST["status"]) ? $_POST["status"] : "";
    $cancellation_reason = isset($_POST["cancellation_reason"]) ? $_POST["cancellation_reason"] : null;
    
    // Validate inputs
    if ($application_id <= 0) {
        echo json_encode(["status" => "error", "message" => "Invalid application ID"]);
        exit;
    }
    
    if (empty($new_status)) {
        echo json_encode(["status" => "error", "message" => "Status cannot be empty"]);
        exit;
    }
    
    // Prepare SQL statement to update application status
    if ($new_status == "Cancelled" && !empty($cancellation_reason)) {
        $sql = "UPDATE applications SET status = ?, cancellation_reason = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $new_status, $cancellation_reason, $application_id);
    } else {
        $sql = "UPDATE applications SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_status, $application_id);
    }
    
    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success", 
            "message" => "Application status updated successfully",
            "new_status" => $new_status
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error updating status: " . $stmt->error]);
    }
    
    $stmt->close();
    $conn->close();
    exit;
}
?>