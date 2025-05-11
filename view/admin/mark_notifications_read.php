<?php
session_start();
require_once '../../backend/backend_admin.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['id_number'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

try {
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    // Mark all notifications for this admin as read
    $sql = "UPDATE notification SET is_read = 1 WHERE id_number = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $_SESSION['id_number']);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update notifications']);
    }
} catch (Exception $e) {
    error_log("Error marking notifications as read: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Internal server error']);
}
?> 