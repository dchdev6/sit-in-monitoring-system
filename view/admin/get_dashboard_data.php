<?php
// Backend includes
include_once '../../backend/database_connection.php';
include_once '../../backend/backend_admin.php';

// Set header to return JSON
header('Content-Type: application/json');

// Get the current date
$today = date('Y-m-d');

// Initialize the database connection
$db = Database::getInstance();
$conn = $db->getConnection();

// Get active students count (students with accounts)
$activeStudents = 0;
$activeStudentsQuery = "SELECT COUNT(*) as count FROM students";
$result = $conn->query($activeStudentsQuery);
if ($result && $row = $result->fetch_assoc()) {
    $activeStudents = $row['count'];
}

// Get today's reservations
$todayReservations = 0;
$reservationsQuery = "SELECT COUNT(*) as count FROM reservation WHERE reservation_date = ?";
$stmt = $conn->prepare($reservationsQuery);
$stmt->bind_param("s", $today);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $row = $result->fetch_assoc()) {
    $todayReservations = $row['count'];
}

// Get available PCs count
$availablePCs = 0;
$reservedPCs = 0;
$usedPCs = 0;
$totalPCs = 0;
$labs = ['lab_517', 'lab_524', 'lab_526', 'lab_528', 'lab_530', 'lab_542'];

foreach ($labs as $lab) {
    $pcQuery = "SELECT 
                SUM(CASE WHEN `$lab` = 1 THEN 1 ELSE 0 END) as available,
                SUM(CASE WHEN `$lab` = 2 THEN 1 ELSE 0 END) as reserved,
                SUM(CASE WHEN `$lab` = 0 THEN 1 ELSE 0 END) as used,
                COUNT(*) as total 
              FROM student_pc";
    $result = $conn->query($pcQuery);
    if ($result && $row = $result->fetch_assoc()) {
        $availablePCs += (int)$row['available'];
        $reservedPCs += (int)$row['reserved'];
        $usedPCs += (int)$row['used'];
        $totalPCs += (int)$row['total'];
    }
}

// Calculate PC availability percentage
$availablePCsPercentage = ($totalPCs > 0) ? round(($availablePCs / $totalPCs) * 100) . '%' : '0%';
$reservedPCsPercentage = ($totalPCs > 0) ? round(($reservedPCs / $totalPCs) * 100) . '%' : '0%';

// Get pending approvals
$pendingApprovals = 0;
$pendingQuery = "SELECT COUNT(*) as count FROM reservation WHERE status = 'Pending'";
$result = $conn->query($pendingQuery);
if ($result && $row = $result->fetch_assoc()) {
    $pendingApprovals = $row['count'];
}

// Calculate growth statistics (for demo purposes - normally would compare to previous day)
$activeStudentsGrowth = rand(1, 15) . '%'; // Mock data

// Return the data as JSON
echo json_encode([
    'activeStudents' => $activeStudents,
    'activeStudentsGrowth' => $activeStudentsGrowth,
    'todayReservations' => $todayReservations,
    'availablePCs' => $availablePCs,
    'availablePCsPercentage' => $availablePCsPercentage,
    'reservedPCs' => $reservedPCs,
    'reservedPCsPercentage' => $reservedPCsPercentage,
    'pendingApprovals' => $pendingApprovals,
    'timestamp' => date('Y-m-d H:i:s')
]);