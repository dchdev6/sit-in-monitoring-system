<?php
// Allow cross-origin requests
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection and functions
require_once '../backend/database_connection.php';
require_once '../backend/backend_admin.php';

// Check if lab parameter is provided
if (!isset($_GET['lab']) || empty($_GET['lab'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Lab parameter is required',
        'pcs' => []
    ]);
    exit;
}

// Get the lab parameter from URL
$lab = $_GET['lab'];

// Validate lab parameter (must be one of the valid labs)
$validLabs = ['lab_517', 'lab_524', 'lab_526', 'lab_528', 'lab_530', 'lab_542'];
if (!in_array($lab, $validLabs)) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid lab parameter',
        'pcs' => []
    ]);
    exit;
}

try {
    // Get database connection
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    if (!$con) {
        throw new Exception("Database connection failed");
    }
    
    // Query to get PC availability for the specified lab
    $sql = "SELECT pc_id, `$lab` as lab2 FROM student_pc ORDER BY pc_id";
    $result = mysqli_query($con, $sql);
    
    if (!$result) {
        throw new Exception("Query failed: " . mysqli_error($con));
    }
    
    // Fetch all PCs and their status
    $pcs = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Convert status to appropriate integer values for client-side handling
        // 1 = Available, 2 = Reserved, 0 = Used
        $pcs[] = [
            'pc_id' => $row['pc_id'],
            'lab2' => $row['lab2']  // This will be 0, 1, or 2
        ];
    }
    
    // Return data as JSON
    echo json_encode([
        'success' => true,
        'message' => 'PCs retrieved successfully',
        'pcs' => $pcs
    ]);
    
} catch (Exception $e) {
    // Log error for debugging
    error_log("Error in get_available_pcs.php: " . $e->getMessage());
    
    // Return error response
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while fetching PC data: ' . $e->getMessage(),
        'pcs' => []
    ]);
}
?>