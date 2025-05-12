<?php
require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Generate unique application ID
    $application_id = uniqid('APP_');
    
    // Get form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $position = $_POST['position'];
    $experience = $_POST['experience'];
    $education = $_POST['education'];
    $cover_letter = $_POST['cover_letter'];
    
    // Handle file uploads
    $resume_path = '';
    $cover_letter_path = '';
    
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
        $resume_path = 'uploads/' . uniqid() . '_' . basename($_FILES['resume']['name']);
        move_uploaded_file($_FILES['resume']['tmp_name'], $resume_path);
    }
    
    if (isset($_FILES['cover_letter_file']) && $_FILES['cover_letter_file']['error'] === UPLOAD_ERR_OK) {
        $cover_letter_path = 'uploads/' . uniqid() . '_' . basename($_FILES['cover_letter_file']['name']);
        move_uploaded_file($_FILES['cover_letter_file']['tmp_name'], $cover_letter_path);
    }
    
    try {
        // Insert into database
        $sql = "INSERT INTO applications (application_id, first_name, last_name, email, phone, position, 
                experience, education, cover_letter, resume_path, cover_letter_path, status, application_date) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', NOW())";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssssssss', 
            $application_id, $first_name, $last_name, $email, $phone, $position,
            $experience, $education, $cover_letter, $resume_path, $cover_letter_path
        );
        
        if ($stmt->execute()) {
            // Send confirmation email
            $to = $email;
            $subject = "Job Application Confirmation - " . $position;
            
            $status_link = "http://" . $_SERVER['HTTP_HOST'] . "/application_status.php?id=" . $application_id;
            
            $message = "Dear $first_name $last_name,\n\n";
            $message .= "Thank you for applying for the $position position.\n\n";
            $message .= "Your application has been received and is being reviewed by our team.\n";
            $message .= "Your application ID is: $application_id\n\n";
            $message .= "You can track your application status using this link:\n";
            $message .= $status_link . "\n\n";
            $message .= "We will contact you soon regarding the next steps.\n\n";
            $message .= "Best regards,\nThe Hiring Team";
            
            $headers = "From: noreply@yourcompany.com\r\n";
            $headers .= "Reply-To: hr@yourcompany.com\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();
            
            if (mail($to, $subject, $message, $headers)) {
                echo json_encode(['status' => 'success', 'message' => 'Application submitted successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error sending confirmation email']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error saving application']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>