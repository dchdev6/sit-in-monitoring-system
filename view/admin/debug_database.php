<?php
// This script shows the structure of the students table to diagnose leaderboard issues
session_start();

// Check if the user is an admin
if (!isset($_SESSION['admin_id'])) {
    echo "Please login as admin to view this page";
    exit;
}

// Include necessary files
require_once '../../backend/database_connection.php';

// Database connection
$db = Database::getInstance();
$con = $db->getConnection();

// Get table structure
echo "<h2>Students Table Structure</h2>";
$result = $con->query("DESCRIBE students");
if ($result) {
    echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach ($row as $key => $value) {
            echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Error getting table structure: " . $con->error;
}

// Check for students with points
echo "<h2>Students with Points</h2>";
$result = $con->query("SELECT * FROM students WHERE points > 0");
if ($result) {
    if ($result->num_rows > 0) {
        echo "<p>Found " . $result->num_rows . " students with points > 0</p>";
        echo "<table border='1'><tr>";
        
        // Get column names
        $first_row = $result->fetch_assoc();
        foreach ($first_row as $key => $value) {
            echo "<th>" . htmlspecialchars($key) . "</th>";
        }
        echo "</tr>";
        
        // Output first row
        echo "<tr>";
        foreach ($first_row as $value) {
            echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
        }
        echo "</tr>";
        
        // Output other rows
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No students found with points > 0</p>";
        
        // Check if there are students with any points
        $all_result = $con->query("SELECT COUNT(*) as count, SUM(points) as total FROM students");
        $counts = $all_result->fetch_assoc();
        echo "<p>Total students: " . $counts['count'] . ", Total points across all students: " . ($counts['total'] ?? 0) . "</p>";
    }
} else {
    echo "Error querying students with points: " . $con->error;
}
?>