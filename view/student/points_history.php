<?php
// Start session if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection first
require_once '../../backend/database_connection.php';

// Include points functions
require_once '../../includes/points_functions.php';

// Check if user is logged in
if (!isset($_SESSION['id_number'])) {
    // Redirect to login if not logged in
    header('Location: ../../auth/login.php');
    exit;
}

// Get student ID from session
$student_id = $_SESSION['id_number'];

// Ensure the points_history table exists
$db = Database::getInstance();
$con = $db->getConnection();

// Check if points_history table exists and create it if not
$tableCheck = $con->query("SHOW TABLES LIKE 'points_history'");
if ($tableCheck->num_rows == 0) {
    // Create the table
    $sql = "CREATE TABLE points_history (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id VARCHAR(50) NOT NULL,
        points_amount INT NOT NULL,
        transaction_type ENUM('add', 'deduct') NOT NULL DEFAULT 'add',
        description VARCHAR(255) NOT NULL,
        request_id INT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX (student_id)
    )";
    $con->query($sql);
}

// Get points history
$history = get_points_history($student_id);

// Get current points balance
$current_points = get_student_points($student_id);

// Make sure points are in session for navbar
$_SESSION['points'] = $current_points;

// Include the navbar - must come after all processing to avoid circular references
include_once '../../includes/navbar_student.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Tailwind CSS is included via navbar -->
    <title>Points History</title>
</head>
<body class="bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Points History</h1>
                <p class="text-gray-500 mt-1">View your point transactions and current balance</p>
            </div>
            <div class="mt-4 md:mt-0">
                <div class="bg-[#0284c7] text-white px-4 py-2 rounded-lg font-medium">
                    Current Balance: <span class="font-bold"><?php echo $current_points; ?> points</span>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Points</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($history)): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">No point history found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($history as $record): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        <?php echo date('M j, Y g:i A', strtotime($record['created_at'] ?? 'now')); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if (($record['transaction_type'] ?? 'add') === 'add'): ?>
                                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Added</span>
                                        <?php else: ?>
                                            <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Used</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        <?php echo htmlspecialchars($record['reason'] ?? 'N/A'); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right 
                                        <?php echo ($record['points'] >= 0) ? 'text-green-600' : 'text-red-600'; ?>">
                                        <?php echo ($record['transaction_type'] === 'add') ? '+' : '-'; ?><?php echo abs($record['points']); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>