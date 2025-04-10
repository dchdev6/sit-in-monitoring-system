<?php
// filepath: c:\xampp\htdocs\Sit-in-monitoring-system\includes\reset_single_student_session.php

// Error handling setup - enable for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Default response
$response = [
    'success' => false,
    'message' => 'An unknown error occurred',
    'defaultValue' => 0
];

try {
    // Include database connection using correct path
    require_once __DIR__ . '/../backend/database_connection.php';
    
    // Access the database connection properly using the Database class
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    // Check if request is POST and has the required parameter
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['studentId'])) {
        // Get the student ID
        $studentId = trim($_POST['studentId']);
        
        if (empty($studentId)) {
            throw new Exception("Student ID cannot be empty");
        }
        
        // Default session value to reset to
        $defaultValue = 30;
        
        // Update the student's session count in the student_session table
        $sql = "UPDATE student_session SET session = ? WHERE id_number = ?";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("is", $defaultValue, $studentId);
        $result = $stmt->execute();
        
        if ($result) {
            // Success
            $response['success'] = true;
            $response['message'] = 'Session reset successful';
            $response['defaultValue'] = $defaultValue;
        } else {
            // SQL execution failed
            throw new Exception("Database error: " . $stmt->error);
        }
        
        $stmt->close();
    } else {
        // Invalid request
        throw new Exception("Invalid request parameters. POST data: " . json_encode($_POST));
    }
} catch (Exception $e) {
    // Handle any exceptions
    $response['message'] = 'Error: ' . $e->getMessage();
    // Log the error for debugging
    error_log("Reset Single Student Session Error: " . $e->getMessage());
} finally {
    // Set the content type to JSON
    header('Content-Type: application/json');
    
    // Send the JSON response
    echo json_encode($response);
    exit;
}
?>