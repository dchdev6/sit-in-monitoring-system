<?php
// Include database connection and functions
require_once '../backend/database_connection.php';
require_once '../backend/backend_admin.php';

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if we have all required data
if (!isset($_POST['idNumber'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required data']);
    exit;
}

// Get form data
$idNum = $_POST['idNumber'];
$last_Name = $_POST['lName'] ?? '';
$first_Name = $_POST['fName'] ?? '';
$middle_Name = $_POST['mName'] ?? '';
$course_Level = $_POST['courseLevel'] ?? '';
$email = $_POST['email'] ?? '';
$course = $_POST['course'] ?? '';
$address = $_POST['address'] ?? '';

// Use the function from backend_admin.php to update student
$result = edit_student_admin($idNum, $last_Name, $first_Name, $middle_Name, $course_Level, $email, $course, $address);

if ($result) {
    echo json_encode([
        'success' => true, 
        'message' => 'Student information updated successfully'
    ]);
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Could not update student information'
    ]);
}
?>