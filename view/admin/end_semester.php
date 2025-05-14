<?php
session_start();
require_once '../../backend/backend_admin.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['admin_id_number'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Get JSON data from request
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['semester']) || !isset($data['academic_year'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$semester = $data['semester'];
$academic_year = $data['academic_year'];

// Validate academic year format
if (!preg_match('/^\d{4}-\d{4}$/', $academic_year)) {
    echo json_encode(['success' => false, 'message' => 'Invalid academic year format']);
    exit;
}

try {
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    // Start transaction
    $con->begin_transaction();
    
    // 1. Archive current semester points
    $archive_sql = "INSERT INTO points_archive (student_id, points, semester, academic_year, archived_at)
                   SELECT id_number, points, semester, academic_year, NOW()
                   FROM student_points";
    $con->query($archive_sql);
    
    // 2. Reset all student points
    $reset_sql = "UPDATE students SET points = 0 WHERE status = 'TRUE'";
    $con->query($reset_sql);
    
    // 3. Clear current semester table
    $clear_sql = "TRUNCATE TABLE current_semester";
    $con->query($clear_sql);
    
    // 4. Insert new semester
    $insert_sql = "INSERT INTO current_semester (semester, academic_year) VALUES (?, ?)";
    $stmt = $con->prepare($insert_sql);
    $stmt->bind_param("ss", $semester, $academic_year);
    $stmt->execute();
    
    // 5. Add notification for all active students
    $notify_sql = "INSERT INTO notification (id_number, message)
                  SELECT id_number, CONCAT('New semester has started: ', ?, ' ', ?)
                  FROM students
                  WHERE status = 'TRUE'";
    $stmt = $con->prepare($notify_sql);
    $stmt->bind_param("ss", $semester, $academic_year);
    $stmt->execute();
    
    // Commit transaction
    $con->commit();
    
    echo json_encode(['success' => true, 'message' => 'Semester ended successfully']);
    
} catch (Exception $e) {
    // Rollback transaction on error
    if (isset($con)) {
        $con->rollback();
    }
    error_log("Error ending semester: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Failed to end semester: ' . $e->getMessage()]);
}
?> 