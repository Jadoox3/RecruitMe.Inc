<?php
require_once 'db_config.php';

if (isset($_GET['id'])) {
    $application_id = $_GET['id'];
    
    try {
        $sql = "SELECT * FROM applications WHERE application_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $application_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $application = $result->fetch_assoc();
            
            // Format the response data
            $response = [
                'status' => 'success',
                'data' => [
                    'application_id' => $application['application_id'],
                    'first_name' => $application['first_name'],
                    'last_name' => $application['last_name'],
                    'email' => $application['email'],
                    'position' => $application['position'],
                    'status' => $application['status'],
                    'application_date' => $application['application_date']
                ]
            ];
            
            if ($application['status'] === 'Cancelled' && !empty($application['cancellation_reason'])) {
                $response['data']['cancellation_reason'] = $application['cancellation_reason'];
            }
            
            echo json_encode($response);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Application not found'
            ]);
        }
        
        $stmt->close();
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
    
    $conn->close();
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Application ID not provided'
    ]);
}
?>