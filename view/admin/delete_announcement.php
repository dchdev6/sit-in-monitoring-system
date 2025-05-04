<?php
session_start();

// For debugging: Save all GET parameters
file_put_contents('debug_delete_id.txt', "GET parameters: " . print_r($_GET, true));

// Check if ID exists and is valid
if (!isset($_GET['id']) || empty($_GET['id']) || $_GET['id'] === 'undefined') {
    $_SESSION['error_message'] = "Invalid announcement ID provided.";
    header("Location: admin.php");
    exit();
}

// For debugging: Save the ID for verification
$debug_id = $_GET['id'];
file_put_contents('debug_delete_id.txt', "\nAttempting to delete ID: " . $debug_id, FILE_APPEND);

// CORRECTED PATH: Include database connection
$db_connection_file = '../../backend/database_connection.php';
file_put_contents('debug_delete_id.txt', "\nAttempting to include: " . $db_connection_file, FILE_APPEND);

// Check if file exists before including
if (!file_exists($db_connection_file)) {
    $_SESSION['error_message'] = "Database connection file not found.";
    file_put_contents('debug_delete_id.txt', "\nDatabase connection file not found at: " . $db_connection_file, FILE_APPEND);
    header("Location: admin.php");
    exit();
}

include_once $db_connection_file;

// Debug all available variables to find the connection
$all_vars = get_defined_vars();
$potential_conn_vars = [];
foreach ($all_vars as $key => $value) {
    if (is_object($value) && (get_class($value) === 'mysqli' || get_class($value) === 'PDO')) {
        $potential_conn_vars[$key] = get_class($value);
    }
}
file_put_contents('debug_delete_id.txt', "\nAvailable connection variables: " . print_r($potential_conn_vars, true), FILE_APPEND);

// Check if any database connection variable is available
if (!isset($conn)) {
    // Try to find alternative variable names
    if (isset($connection)) {
        $conn = $connection;
        file_put_contents('debug_delete_id.txt', "\nUsing variable 'connection' instead", FILE_APPEND);
    } elseif (isset($db)) {
        $conn = $db;
        file_put_contents('debug_delete_id.txt', "\nUsing variable 'db' instead", FILE_APPEND);
    } elseif (isset($mysqli)) {
        $conn = $mysqli;
        file_put_contents('debug_delete_id.txt', "\nUsing variable 'mysqli' instead", FILE_APPEND);
    } elseif (isset($database)) {
        $conn = $database;
        file_put_contents('debug_delete_id.txt', "\nUsing variable 'database' instead", FILE_APPEND);
    } else {
        // Create a new connection if none exists
        $hostname = "localhost";
        $username = "root"; 
        $password = ""; 
        $database = "ccs_system"; // Adjust if your database name is different

        $conn = mysqli_connect($hostname, $username, $password, $database);
        
        if (!$conn) {
            $_SESSION['error_message'] = "Database connection failed: " . mysqli_connect_error();
            file_put_contents('debug_delete_id.txt', "\nFailed to create new connection: " . mysqli_connect_error(), FILE_APPEND);
            header("Location: admin.php");
            exit();
        }
        
        file_put_contents('debug_delete_id.txt', "\nCreated new database connection", FILE_APPEND);
    }
}

// Now we should have a connection in $conn
$announcementId = intval($_GET['id']); // Sanitize the input

// Delete the announcement from the database
$query = "DELETE FROM announce WHERE announce_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $announcementId);

$result = $stmt->execute();

// Debug: Log the result
file_put_contents('debug_delete_id.txt', "\nDelete result: " . ($result ? "success" : "failed") . 
                 "\nAffected rows: " . $stmt->affected_rows, FILE_APPEND);

if ($result && $stmt->affected_rows > 0) {
    $_SESSION['success_message'] = "Announcement deleted successfully!";
} else {
    $_SESSION['error_message'] = "Failed to delete the announcement. Record may not exist.";
}

$stmt->close();
$conn->close();

// Redirect back to the admin page
header("Location: admin.php");
exit();
?>