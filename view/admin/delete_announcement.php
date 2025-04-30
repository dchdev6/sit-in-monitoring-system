<?php
session_start();
include '../../includes/db_connection.php'; // Ensure this file connects to the database

if (isset($_GET['id'])) {
    $announcementId = intval($_GET['id']); // Sanitize the input

    // Delete the announcement from the database
    $query = "DELETE FROM announcements WHERE announcement_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $announcementId);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Announcement deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Failed to delete the announcement. Please try again.";
    }

    $stmt->close();
    $conn->close();

    // Redirect back to the admin page
    header("Location: admin.php");
    exit();
} else {
    $_SESSION['error_message'] = "Invalid request.";
    header("Location: admin.php");
    exit();
}