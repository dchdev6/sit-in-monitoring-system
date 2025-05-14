<?php
// Include database connection
require_once '../backend/database_connection.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if student_id is provided
if (!isset($_GET['student_id']) || empty($_GET['student_id'])) {
    echo json_encode(['error' => 'Missing student ID parameter']);
    exit;
}

$student_id = $_GET['student_id'];
error_log("Fetching point history for student ID: " . $student_id);

try {
    // Get database connection
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    if (!$con) {
        throw new Exception("Database connection failed");
    }
    
    // Direct join approach to get history
    $query = "SELECT 
                ph.id, 
                ph.student_id, 
                ph.points_amount as points, 
                ph.description as reason, 
                ph.created_at 
              FROM 
                points_history ph
              JOIN 
                students s ON s.id_number = ph.student_id
              WHERE 
                s.id = ?
              ORDER BY 
                ph.created_at DESC";
                
    $stmt = $con->prepare($query);
    if (!$stmt) {
        throw new Exception("Prepare statement failed: " . $con->error);
    }
    
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $history = [];
    while ($row = $result->fetch_assoc()) {
        $history[] = $row;
    }
    
    // If no records found, create a dummy record for testing
    if (count($history) === 0) {
        // Get student id_number
        $id_query = "SELECT id_number FROM students WHERE id = ?";
        $id_stmt = $con->prepare($id_query);
        $id_stmt->bind_param("i", $student_id);
        $id_stmt->execute();
        $id_result = $id_stmt->get_result();
        
        if ($id_row = $id_result->fetch_assoc()) {
            $student_id_number = $id_row['id_number'];
            
            // Return a sample record
            $sample_record = [
                'id' => 0,
                'student_id' => $student_id_number,
                'points' => 0,
                'reason' => 'No point history found',
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            echo json_encode([$sample_record]);
            exit;
        }
    }
    
    echo json_encode($history);
    
} catch (Exception $e) {
    error_log('Error retrieving point history: ' . $e->getMessage());
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>