<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    // Include the navbar which includes the API
    include '../../includes/navbar_admin.php';
    require_once '../../includes/points_functions.php';
    
    // Process form submission to award points
    $message = '';
    $message_type = '';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['award_points'])) {
        $student_id = $_POST['student_id'] ?? '';
        $points = intval($_POST['points_amount']);
        $reason = $_POST['reason'] ?? '';
        
        if (empty($student_id)) {
            $message = 'Please select a student.';
            $message_type = 'error';
        } elseif ($points <= 0) {
            $message = 'Please enter a positive number of points.';
            $message_type = 'error';
        } elseif (empty($reason)) {
            $message = 'Please provide a reason for awarding points.';
            $message_type = 'error';
        } else {
            // Award the points
            $result = award_points_to_student($student_id, $points, $reason);
            
            if ($result) {
                $message = 'Points awarded successfully!';
                $message_type = 'success';
            } else {
                $message = 'Failed to award points. Please check the student ID and try again.';
                $message_type = 'error';
            }
        }
    }
    
    // Get database connection for queries
    $db = Database::getInstance();
    $con = $db->getConnection();
    
    if (!$con) {
        throw new Exception("Database connection failed");
    }
    
    // Get all students for the dropdown
    $students = [];
    $query = "SELECT id_number, firstName, lastName, course, yearLevel, points FROM students WHERE status = 'TRUE' ORDER BY lastName, firstName";
    $result = $con->query($query);
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
    } else {
        error_log("Error fetching students: " . $con->error);
    }
    
    // Get recent point awards
    $recent_awards = [];
    $query = "SELECT ph.*, s.firstName, s.lastName, s.id_number 
              FROM points_history ph
              JOIN students s ON ph.student_id = s.id_number
              WHERE ph.transaction_type = 'add' AND ph.points_amount > 0
              ORDER BY ph.created_at DESC
              LIMIT 10";
    $result = $con->query($query);
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $recent_awards[] = $row;
        }
    } else {
        error_log("Error fetching recent awards: " . $con->error);
    }
    
    // Check if points_history table exists
    $check_history_query = "SHOW TABLES LIKE 'points_history'";
    $history_exists = false;
    
    if ($result = $con->query($check_history_query)) {
        if ($result->num_rows > 0) {
            $history_exists = true;
        }
    }
    
    if (!$history_exists) {
        // Create the points_history table
        $create_history_query = "CREATE TABLE IF NOT EXISTS `points_history` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `student_id` varchar(50) NOT NULL,
            `points_amount` int(11) NOT NULL,
            `transaction_type` enum('add','deduct') NOT NULL,
            `description` text DEFAULT NULL,
            `request_id` int(11) DEFAULT NULL,
            `created_at` datetime DEFAULT current_timestamp(),
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        $con->query($create_history_query);
        error_log("Created points_history table");
    }
    
    // Check if points column exists in students table
    $check_points_column_query = "SHOW COLUMNS FROM `students` LIKE 'points'";
    $points_column_exists = false;
    
    if ($result = $con->query($check_points_column_query)) {
        if ($result->num_rows > 0) {
            $points_column_exists = true;
        }
    }
    
    if (!$points_column_exists) {
        // Add points column to students table
        $add_points_column_query = "ALTER TABLE `students` ADD COLUMN `points` INT NOT NULL DEFAULT 0";
        $con->query($add_points_column_query);
        error_log("Added points column to students table");
    }
} catch (Exception $e) {
    error_log("Critical error in reward_points.php: " . $e->getMessage());
    echo '<div style="margin: 50px auto; max-width: 800px; padding: 20px; background-color: #fff3f3; border-left: 4px solid #f44336; color: #333;">
            <h2>System Error</h2>
            <p>There was a problem loading this page. Please try again later or contact the administrator.</p>
            <p><small>Error details have been logged.</small></p>
          </div>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Award Points</title>
</head>
<body>
<div class="container max-w-7xl mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Award Points</h1>
        <p class="text-gray-600 mt-2">Give reward points to students for achievements and participation</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Award Points Form -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-xl font-semibold text-gray-800">Award Points to Student</h2>
                </div>
                
                <div class="p-6">
                    <?php if (!empty($message)): ?>
                        <div class="mb-6 p-4 rounded-md <?php echo $message_type === 'success' ? 'bg-green-50 border-green-500 text-green-700' : 'bg-red-50 border-red-500 text-red-700'; ?> border-l-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <?php if ($message_type === 'success'): ?>
                                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    <?php else: ?>
                                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    <?php endif; ?>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm"><?php echo $message; ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form action="" method="post" class="space-y-6">
                        <div>
                            <label for="student_id" class="block text-sm font-medium text-gray-700">Student</label>
                            <select id="student_id" name="student_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value="">Select a student</option>
                                <?php foreach ($students as $student): ?>
                                    <option value="<?php echo $student['id_number']; ?>">
                                        <?php echo htmlspecialchars($student['lastName'] . ', ' . $student['firstName']); ?> 
                                        (ID: <?php echo $student['id_number']; ?>, 
                                         <?php echo htmlspecialchars($student['course'] . ' ' . $student['yearLevel']); ?>,
                                         Current Points: <?php echo $student['points'] ?? 0; ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label for="points_amount" class="block text-sm font-medium text-gray-700 mb-1">Points Amount</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-star text-primary-400"></i>
                                </div>
                                <input type="number" name="points_amount" id="points_amount" 
                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 pr-12 py-3 
                                    border border-gray-300 rounded-lg bg-gray-50 hover:bg-white transition-all duration-200 
                                    shadow-sm text-lg font-medium text-gray-700" 
                                    placeholder="0" min="1" max="100" required>
                                <div class="absolute inset-y-0 right-0 flex items-center">
                                    <div class="flex items-center mr-3 px-2 py-1 bg-primary-50 rounded-full">
                                        <span id="pointsCounter" class="text-primary-700 font-semibold mr-1">0</span>
                                        <span class="text-primary-600 text-sm">points</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-1.5 w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                                <div id="pointsProgressBar" class="bg-primary-500 h-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                        </div>

                        <div>
                            <label for="reason" class="block text-sm font-medium text-gray-700">Reason</label>
                            <div class="mt-1">
                                <select id="reason" name="reason" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                    <option value="">Select a reason</option>
                                    <option value="Perfect Attendance">Perfect Attendance</option>
                                    <option value="Excellent Performance">Excellent Performance</option>
                                    <option value="Lab Activity Completion">Lab Activity Completion</option>
                                    <option value="Special Project Contribution">Special Project Contribution</option>
                                    <option value="Helping Other Students">Helping Other Students</option>
                                    <option value="Competition Participation">Competition Participation</option>
                                    <option value="Other Achievement">Other Achievement</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <button type="submit" name="award_points" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                Award Points
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-6 bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-xl font-semibold text-gray-800">Point Award Templates</h2>
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                        <h3 class="font-medium">Perfect Attendance</h3>
                        <p class="text-gray-500 text-sm">+10 points</p>
                        <button type="button" onclick="fillAwardForm(10, 'Perfect Attendance')" class="mt-2 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Use Template
                        </button>
                    </div>
                    
                    <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                        <h3 class="font-medium">Excellent Performance</h3>
                        <p class="text-gray-500 text-sm">+15 points</p>
                        <button type="button" onclick="fillAwardForm(15, 'Excellent Performance')" class="mt-2 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Use Template
                        </button>
                    </div>
                    
                    <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                        <h3 class="font-medium">Lab Activity Completion</h3>
                        <p class="text-gray-500 text-sm">+5 points</p>
                        <button type="button" onclick="fillAwardForm(5, 'Lab Activity Completion')" class="mt-2 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Use Template
                        </button>
                    </div>
                    
                    <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                        <h3 class="font-medium">Competition Winner</h3>
                        <p class="text-gray-500 text-sm">+25 points</p>
                        <button type="button" onclick="fillAwardForm(25, 'Competition Participation')" class="mt-2 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Use Template
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Awards and Info -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-xl font-semibold text-gray-800">Recent Awards</h2>
                </div>
                
                <div class="p-6">
                    <?php if (empty($recent_awards)): ?>
                        <p class="text-gray-500 text-center py-4">No recent awards found.</p>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($recent_awards as $award): ?>
                                <div class="border-b border-gray-200 pb-4 last:border-b-0 last:pb-0">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-medium"><?php echo htmlspecialchars($award['firstName'] . ' ' . $award['lastName']); ?></p>
                                            <p class="text-sm text-gray-500"><?php echo htmlspecialchars($award['description']); ?></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-green-600">+<?php echo $award['points_amount']; ?></p>
                                            <p class="text-xs text-gray-500"><?php echo date('M j, Y', strtotime($award['created_at'])); ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="mt-4 text-center">
                            <a href="pending_points.php" class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                View All Activity →
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            </div>
        </div>
    </div>
</div>

<script>
function fillAwardForm(points, reason) {
    document.getElementById('points_amount').value = points;
    
    // Set the selected option in the dropdown
    const reasonSelect = document.getElementById('reason');
    for (let i = 0; i < reasonSelect.options.length; i++) {
        if (reasonSelect.options[i].value === reason) {
            reasonSelect.selectedIndex = i;
            break;
        }
    }
    
    // Focus on the student dropdown to draw attention to it
    document.getElementById('student_id').focus();
    
    // Scroll to the form if needed
    document.querySelector('form').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

document.addEventListener('DOMContentLoaded', function() {
    // Enable search in student dropdown
    const studentSelect = document.getElementById('student_id');
    if (studentSelect) {
        // Initialize select2 if available
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $(studentSelect).select2({
                placeholder: 'Search for a student...',
                allowClear: true
            });
        }
    }
    
    // Points amount input visualization
    const pointsInput = document.getElementById('points_amount');
    const pointsCounter = document.getElementById('pointsCounter');
    const pointsProgressBar = document.getElementById('pointsProgressBar');
    
    if (pointsInput && pointsCounter && pointsProgressBar) {
        // Update on input change
        pointsInput.addEventListener('input', function() {
            const value = parseInt(this.value) || 0;
            pointsCounter.textContent = value;
            
            // Calculate percentage (max 100 points = 100%)
            const percentage = Math.min(100, (value / 100) * 100);
            pointsProgressBar.style.width = percentage + '%';
            
            // Change color based on amount
            if (value <= 5) {
                pointsProgressBar.className = 'bg-blue-400 h-full transition-all duration-300';
            } else if (value <= 15) {
                pointsProgressBar.className = 'bg-primary-500 h-full transition-all duration-300';
            } else if (value <= 30) {
                pointsProgressBar.className = 'bg-green-500 h-full transition-all duration-300';
            } else {
                pointsProgressBar.className = 'bg-purple-500 h-full transition-all duration-300';
            }
            
            // Add animation effect on change
            pointsCounter.classList.remove('animate-pulse');
            void pointsCounter.offsetWidth; // Trigger reflow to restart animation
            pointsCounter.classList.add('animate-pulse');
        });
        
        // Initialize values if pre-filled
        if (pointsInput.value) {
            const event = new Event('input');
            pointsInput.dispatchEvent(event);
        }
    }
    
    <?php if ($message_type === 'success'): ?>
    // Show success message with SweetAlert if available
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Success!',
            text: '<?php echo addslashes($message); ?>',
            icon: 'success',
            confirmButtonColor: '#3B82F6'
        });
    }
    <?php endif; ?>
});
</script>

<style>
body {
    animation: fadeIn 0.5s ease-in-out forwards;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>
</body>
</html>