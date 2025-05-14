<?php

// Include database connection
require_once '../backend/database_connection.php';

// Check if this is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    
    // Default value to reset sessions to
    $defaultSessions = 30;
    
    try {
        // Get database connection using your Database class
        $db = Database::getInstance();
        $conn = $db->getConnection();
        
        if (!$conn) {
            file_put_contents('reset_log.txt', "Database connection failed\n", FILE_APPEND);
            throw new Exception("Database connection failed");
        }
        
        file_put_contents('reset_log.txt', "Database connection successful\n", FILE_APPEND);
        
        // Update all students' session values to 30 in the student_session table
        $sql = "UPDATE student_session SET session = ?";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            file_put_contents('reset_log.txt', "Prepare statement failed: " . $conn->error . "\n", FILE_APPEND);
            throw new Exception("Prepare statement failed: " . $conn->error);
        }
        
        $stmt->bind_param("i", $defaultSessions);
        $result = $stmt->execute();
        
        file_put_contents('reset_log.txt', "Query executed. Result: " . ($result ? "success" : "failed") . "\n", FILE_APPEND);
        
        // Send JSON response
        header('Content-Type: application/json');
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'All student sessions have been reset to ' . $defaultSessions,
                'defaultValue' => $defaultSessions
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to reset student sessions: ' . $conn->error
            ]);
        }
    } catch (Exception $e) {
        // Handle errors
        file_put_contents('reset_log.txt', "Exception: " . $e->getMessage() . "\n", FILE_APPEND);
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
    
    exit;
} else {
    // If not a POST request, return error
    file_put_contents('reset_log.txt', "Non-POST request received: " . $_SERVER['REQUEST_METHOD'] . "\n", FILE_APPEND);
    
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>