<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include necessary files
require_once '../../backend/database_connection.php';
require_once '../../backend/backend_admin.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Check if user is logged in and is an admin
if (!isset($_SESSION['admin_id_number'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized access. Please log in as admin.'
    ]);
    exit;
}

try {
    // Get database connection to verify it's working
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    if (!$con) {
        throw new Exception("Database connection failed");
    }
    
    // Attempt to reset points
    if (reset_all_points()) {
        echo json_encode([
            'success' => true,
            'message' => 'All points have been reset successfully.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to reset points. Please check the server logs for details.'
        ]);
    }
} catch (Exception $e) {
    error_log("Reset points error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while resetting points: ' . $e->getMessage()
    ]);
}
?> 