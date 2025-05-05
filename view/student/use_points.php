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

// Get current points balance
$current_points = get_student_points($student_id);

// Make sure points are in session for navbar
$_SESSION['points'] = $current_points;

// Ensure the points_history table exists
try {
    $db = Database::getInstance();
    $con = $db->getConnection();
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
} catch (Exception $e) {
    // Log error but continue
    error_log('Error checking/creating points_history table: ' . $e->getMessage());
}

// Handle form submission
$message = '';
$alertClass = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['use_points'])) {
    $points_to_use = isset($_POST['points_amount']) ? intval($_POST['points_amount']) : 0;
    $purpose = isset($_POST['purpose']) ? $_POST['purpose'] : '';
    
    if ($points_to_use <= 0) {
        $message = 'Please enter a valid number of points to use.';
        $alertClass = 'bg-red-100 border-red-400 text-red-700';
    } elseif ($points_to_use > $current_points) {
        $message = 'You don\'t have enough points. Your current balance is ' . $current_points . ' points.';
        $alertClass = 'bg-red-100 border-red-400 text-red-700';
    } elseif (empty($purpose)) {
        $message = 'Please specify a purpose for using points.';
        $alertClass = 'bg-red-100 border-red-400 text-red-700';
    } else {
        // Try to record the point usage
        $db = Database::getInstance();
        $con = $db->getConnection();
        
        try {
            $con->begin_transaction();
            
            // Deduct points from student
            $update_query = "UPDATE students SET points = points - ? WHERE id_number = ?";
            $update_stmt = $con->prepare($update_query);
            
            if (!$update_stmt) {
                throw new Exception("Error preparing update statement: " . $con->error);
            }
            
            $update_stmt->bind_param("is", $points_to_use, $student_id);
            
            if (!$update_stmt->execute()) {
                throw new Exception("Error executing update: " . $update_stmt->error);
            }
            
            // Record transaction in history
            $history_query = "INSERT INTO points_history 
                            (student_id, points_amount, transaction_type, description, created_at) 
                            VALUES (?, ?, 'deduct', ?, NOW())";
            $history_stmt = $con->prepare($history_query);
            
            if (!$history_stmt) {
                throw new Exception("Error preparing history statement: " . $con->error);
            }
            
            $history_stmt->bind_param("sis", $student_id, $points_to_use, $purpose);
            
            if (!$history_stmt->execute()) {
                throw new Exception("Error recording history: " . $history_stmt->error);
            }
            
            // Commit transaction
            $con->commit();
            
            // Update session and message
            $current_points -= $points_to_use;
            $_SESSION['points'] = $current_points;
            
            $message = 'Successfully used ' . $points_to_use . ' points for: ' . htmlspecialchars($purpose);
            $alertClass = 'bg-green-100 border-green-400 text-green-700';
            
        } catch (Exception $e) {
            // Roll back on error
            $con->rollback();
            $message = 'Error: ' . $e->getMessage();
            $alertClass = 'bg-red-100 border-red-400 text-red-700';
        }
    }
}

// Include the navbar - must come after all processing to avoid circular references
include_once '../../includes/navbar_student.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Tailwind CSS is included via navbar -->
    <title>Use Points</title>
    <style>
        /* Custom styles for modern inputs and selects */
        .modern-input, .modern-select {
            transition: all 0.2s ease-in-out;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }
        .modern-input:focus, .modern-select:focus {
            border-color: #0284c7;
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
            outline: none;
        }
        /* Hide number input spinner buttons */
        .modern-input[type="number"]::-webkit-outer-spin-button,
        .modern-input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .modern-input[type="number"] {
            -moz-appearance: textfield;
            appearance: textfield;
        }
        /* Enhanced number input */
        .points-input {
            font-weight: 500;
            color: #1f2937;
            padding: 0.625rem 0.75rem;
            height: 2.75rem;
            font-size: 1rem;
        }
        .modern-select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
        .input-container {
            position: relative;
        }
        .input-suffix {
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            display: flex;
            align-items: center;
            padding: 0 0.75rem;
            pointer-events: none;
            color: #6b7280;
            border-left: 1px solid #e5e7eb;
            background-color: #f9fafb;
            border-top-right-radius: 0.375rem;
            border-bottom-right-radius: 0.375rem;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Use Points</h1>
                <p class="text-gray-500 mt-1">Spend your earned points for sit-in reservations and other benefits</p>
            </div>
            <div class="mt-4 md:mt-0">
                <div class="bg-[#0284c7] text-white px-4 py-2 rounded-lg font-medium">
                    Current Balance: <span class="font-bold"><?php echo $current_points; ?> points</span>
                </div>
            </div>
        </div>
        
        <!-- Alert message -->
        <?php if (!empty($message)): ?>
            <div class="border px-4 py-3 rounded relative mb-6 <?php echo $alertClass; ?>" role="alert">
                <span class="block sm:inline"><?php echo $message; ?></span>
            </div>
        <?php endif; ?>
        
        <!-- Points usage card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Spend Your Points</h2>
                
                <form method="POST" action="">
                    <div class="space-y-4">
                        <div>
                            <label for="points_amount" class="block text-sm font-medium text-gray-700">
                                Points to Use
                            </label>
                            <div class="mt-1 input-container">
                                <input type="number" name="points_amount" id="points_amount" 
                                    class="modern-input points-input block w-full pr-12 sm:text-sm rounded-md" 
                                    placeholder="0" min="1" max="<?php echo $current_points; ?>" required>
                                <div class="input-suffix">points</div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">You have <?php echo $current_points; ?> points available.</p>
                        </div>
                        
                        <div>
                            <label for="purpose" class="block text-sm font-medium text-gray-700">
                                Purpose
                            </label>
                            <select name="purpose" id="purpose" class="modern-select mt-1 block w-full pl-3 pr-10 py-2 text-base rounded-md" required>
                                <option value="">Select purpose</option>
                                <option value="Sit-in Reservation">Sit-in Reservation</option>
                                <option value="Extra Lab Time">Extra Lab Time</option>
                                <option value="Priority Access">Priority Access</option>
                                <option value="Special Event">Special Event</option>
                            </select>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-700">Points Conversion Guide</h3>
                            <ul class="mt-2 text-sm text-gray-600 space-y-1">
                                <li>• 3 points = One sit-in session</li>
                                <li>• 5 points = Extra 30 minutes of lab time</li>
                                <li>• 10 points = Priority booking access</li>
                                <li>• 20 points = Access to special events</li>
                            </ul>
                        </div>
                        
                        <div class="flex justify-end mt-4">
                            <button type="submit" name="use_points" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#0284c7] hover:bg-[#0369a1] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0284c7]">
                                Use Points
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Recent transactions -->
        <div class="mt-8">
            <h2 class="text-lg font-medium text-gray-800 mb-4">Recent Transactions</h2>
            
            <?php
            // Get recent transactions
            $recent = get_points_history($student_id);
            $recent = array_slice($recent, 0, 5); // Only show 5 most recent
            ?>
            
            <?php if (empty($recent)): ?>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <p class="text-gray-500 text-center">No transaction history yet.</p>
                </div>
            <?php else: ?>
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
                                <?php foreach($recent as $record): ?>
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
                                            <?php echo ($record['transaction_type'] === 'add') ? 'text-green-600' : 'text-red-600'; ?>">
                                            <?php echo ($record['transaction_type'] === 'add') ? '+' : '-'; ?><?php echo abs($record['points']); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-4 text-right">
                    <a href="points_history.php" class="text-sm font-medium text-[#0284c7] hover:text-[#0369a1]">
                        View all transactions →
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>