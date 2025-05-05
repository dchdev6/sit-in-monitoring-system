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

// Ensure points column exists in students table
$db = Database::getInstance();
$con = $db->getConnection();

// Check if points column exists in students table and add it if needed
$checkPointsColumn = $con->query("SHOW COLUMNS FROM students LIKE 'points'");
if ($checkPointsColumn->num_rows == 0) {
    // Add points column to students table
    $con->query("ALTER TABLE students ADD COLUMN points INT DEFAULT 0");
}

// Get leaderboard data - top 20 students by points
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 20;
if ($limit <= 0) $limit = 20;

$leaderboard = get_leaderboard($limit);

// Get current student's rank and points
$student_rank = 0;
$current_points = get_student_points($student_id);

// Make sure points are in session for navbar
$_SESSION['points'] = $current_points;

// Calculate the student's rank
$rank_query = "SELECT 
               COUNT(*) + 1 as rank
               FROM students 
               WHERE points > (SELECT IFNULL(points, 0) FROM students WHERE id_number = ?)";
$rank_stmt = $con->prepare($rank_query);
$rank_stmt->bind_param("s", $student_id);
$rank_stmt->execute();
$rank_result = $rank_stmt->get_result();

if ($row = $rank_result->fetch_assoc()) {
    $student_rank = $row['rank'];
}

// Get statistics about the points distribution
$stats_query = "SELECT 
    COUNT(*) as total_students,
    IFNULL(AVG(points), 0) as avg_points,
    IFNULL(MAX(points), 0) as max_points
FROM students 
WHERE points > 0";
$stats_result = $con->query($stats_query);
$stats = $stats_result->fetch_assoc();

// Include the navbar - must come after all processing to avoid circular references
include_once '../../includes/navbar_student.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Tailwind CSS is included via navbar -->
    <title>Leaderboard</title>
</head>
<body class="bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Points Leaderboard</h1>
                <p class="text-gray-500 mt-1">See how you rank against other students</p>
            </div>
            
            <div class="mt-4 md:mt-0 flex items-center space-x-4">
                <div class="bg-white border border-gray-200 rounded-lg px-4 py-2 shadow-sm">
                    <span class="text-sm text-gray-500">Your Rank</span>
                    <div class="text-2xl font-bold text-[#0284c7]">#<?php echo $student_rank; ?></div>
                </div>
                <div class="bg-[#0284c7] text-white px-4 py-2 rounded-lg font-medium">
                    <span class="text-sm opacity-90">Your Points</span>
                    <div class="text-2xl font-bold"><?php echo $current_points; ?></div>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                <div class="text-sm text-gray-500">Total Students with Points</div>
                <div class="text-2xl font-bold text-gray-800"><?php echo $stats['total_students']; ?></div>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                <div class="text-sm text-gray-500">Average Points</div>
                <div class="text-2xl font-bold text-gray-800"><?php echo round($stats['avg_points'], 1); ?></div>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                <div class="text-sm text-gray-500">Highest Score</div>
                <div class="text-2xl font-bold text-gray-800"><?php echo $stats['max_points']; ?></div>
            </div>
        </div>
        
        <!-- Leaderboard Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Number</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Points</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($leaderboard)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">No leaderboard data available yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($leaderboard as $index => $student): ?>
                                <?php $isCurrentStudent = ($student['id_number'] == $student_id); ?>
                                <tr class="<?php echo $isCurrentStudent ? 'bg-blue-50' : 'hover:bg-gray-50'; ?>">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($index < 3): ?>
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full 
                                                <?php 
                                                    if ($index === 0) echo 'bg-yellow-100 text-yellow-800'; 
                                                    else if ($index === 1) echo 'bg-gray-100 text-gray-800'; 
                                                    else if ($index === 2) echo 'bg-amber-100 text-amber-800';
                                                ?>
                                                font-bold text-sm">
                                                <?php echo $index + 1; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-gray-700 font-medium"><?php echo $index + 1; ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium <?php echo $isCurrentStudent ? 'text-blue-700' : 'text-gray-900'; ?>">
                                                <?php echo htmlspecialchars($student['last_name'] . ', ' . $student['first_name']); ?>
                                                <?php if ($isCurrentStudent): ?>
                                                    <span class="ml-1 px-2 py-0.5 text-xs bg-[#0284c7]/10 text-[#0284c7] rounded-full">You</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        <?php echo htmlspecialchars($student['id_number']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        <?php echo htmlspecialchars($student['program'] ?? 'N/A'); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right text-green-600">
                                        <?php echo $student['points']; ?> pts
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