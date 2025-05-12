<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "job_portal";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $position = $_POST["position"];
    $message = $_POST["message"];
    
    $upload_dir = "uploads/";
    
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $resume_path = "";
    if (isset($_FILES["resume"]) && $_FILES["resume"]["error"] == 0) {
        $resume_name = time() . "_" . basename($_FILES["resume"]["name"]);
        $resume_path = $upload_dir . $resume_name;
        
        if (move_uploaded_file($_FILES["resume"]["tmp_name"], $resume_path)) {
            $resume_path = $resume_path;
        } else {
            echo "Error uploading resume.";
            exit;
        }
    } else {
        echo "Resume is required.";
        exit;
    }
    
    $cover_letter_path = null;
    if (isset($_FILES["cover_letter"]) && $_FILES["cover_letter"]["error"] == 0) {
        $cover_letter_name = time() . "_" . basename($_FILES["cover_letter"]["name"]);
        $cover_letter_path = $upload_dir . $cover_letter_name;
        
        if (move_uploaded_file($_FILES["cover_letter"]["tmp_name"], $cover_letter_path)) {
            $cover_letter_path = $cover_letter_path;
        } else {
            echo "Error uploading cover letter.";
            exit;
        }
    }
    
    $sql = "INSERT INTO applications (first_name, last_name, email, position, message, resume_path, cover_letter_path) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $first_name, $last_name, $email, $position, $message, $resume_path, $cover_letter_path);
    
    if ($stmt->execute()) {
        $application_id = $stmt->insert_id;
        echo json_encode([
            "status" => "success", 
            "message" => "Application submitted successfully!",
            "application_id" => $application_id,
            "name" => htmlspecialchars($first_name . ' ' . $last_name),
            "position" => htmlspecialchars($position)
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
    }
    
    $stmt->close();
    $conn->close();
    exit;
}
?>