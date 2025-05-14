<?php
// Include database connection and functions
require_once '../backend/database_connection.php';
require_once '../backend/backend_admin.php';

// Check if studentId parameter is provided
if (!isset($_POST['studentId'])) {
    echo json_encode(['success' => false, 'message' => 'No student ID provided']);
    exit;
}

// Get the student ID
$studentId = $_POST['studentId'];

// Database instance
$db = Database::getInstance();
$con = $db->getConnection();

// Prepare a secure query to fetch student data
$sql = "SELECT s.id_number, s.lastName, s.firstName, s.middleName, s.yearLevel, 
               s.email, s.course, s.address 
        FROM students s
        WHERE s.id_number = ?";

$stmt = $con->prepare($sql);
$stmt->bind_param("s", $studentId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Return the student data as JSON
    $student = $result->fetch_assoc();
    echo json_encode($student);
} else {
    // No student found
    echo json_encode(['success' => false, 'message' => 'Student not found']);
}

$stmt->close();
?>