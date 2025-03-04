<?php
// Database Configuration
$host = "localhost";       // Change this if using a remote server
$dbname = "ccs_system";     // Your database name
$username = "root";        // Your database username (default for XAMPP)
$password = "";            // Your database password (default is empty for XAMPP)

try {
    // Create a PDO database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Set error mode to Exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    // If connection fails, display error message
    die("Database connection failed: " . $e->getMessage());
}

// Start a session if not already started
if (!isset($_SESSION)) {
    session_start();
}

// Define site URL (optional, useful for linking)
define("SITE_URL", "http://localhost/sit-in-monitoring-system");

// Set default timezone
date_default_timezone_set("Asia/Manila");

?>
