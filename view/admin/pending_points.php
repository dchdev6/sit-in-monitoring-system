<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include the navbar which includes the API
include '../../includes/navbar_admin.php';
require_once '../../includes/points_functions.php';

// Process approvals
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve']) && isset($_POST['request_id'])) {
        $request_id = $_POST['request_id'];
        $success = process_points_request($request_id, 'approved');
        
        if ($success) {
            $message = 'Request approved successfully.';
            $message_type = 'success';
        } else {
            $message = 'Failed to approve request.';
            $message_type = 'error';
        }
    } elseif (isset($_POST['reject']) && isset($_POST['request_id'])) {
        $request_id = $_POST['request_id'];
        $success = process_points_request($request_id, 'rejected');
        
        if ($success) {
            $message = 'Request rejected successfully.';
            $message_type = 'success';
        } else {
            $message = 'Failed to reject request.';
            $message_type = 'error';
        }
    }
}

// Get all pending requests (with error handling)
try {
    $pending_requests = get_pending_point_requests();
    if (!is_array($pending_requests)) {
        $pending_requests = [];
    }
    $pending_count = count($pending_requests);
} catch (Exception $e) {
    error_log('Error getting pending requests: ' . $e->getMessage());
    $pending_requests = [];
    $pending_count = 0;
}

// Get database connection for queries
try {
    $db = Database::getInstance();
    $con = $db->getConnection();

    // Get today's statistics for approved and rejected requests
    $approved_today = 0;
    $rejected_today = 0;

    $today = date('Y-m-d');
    
    // Count approved requests today
    $approved_query = "SELECT COUNT(*) as count FROM points_requests 
                      WHERE status = 'approved' 
                      AND DATE(processed_date) = ?";
    $approved_stmt = $con->prepare($approved_query);
    if ($approved_stmt) {
        $approved_stmt->bind_param("s", $today);
        $approved_stmt->execute();
        $approved_result = $approved_stmt->get_result();
        
        if ($row = $approved_result->fetch_assoc()) {
            $approved_today = $row['count'];
        }
    }
    
    // Count rejected requests today
    $rejected_query = "SELECT COUNT(*) as count FROM points_requests 
                      WHERE status = 'rejected' 
                      AND DATE(processed_date) = ?";
    $rejected_stmt = $con->prepare($rejected_query);
    if ($rejected_stmt) {
        $rejected_stmt->bind_param("s", $today);
        $rejected_stmt->execute();
        $rejected_result = $rejected_stmt->get_result();
        
        if ($row = $rejected_result->fetch_assoc()) {
            $rejected_today = $row['count'];
        }
    }
} catch (Exception $e) {
    error_log('Error counting processed requests: ' . $e->getMessage());
    $approved_today = 0;
    $rejected_today = 0;
}

// Check if the points_requests table exists
try {
    $check_table_query = "SHOW TABLES LIKE 'points_requests'";
    $table_exists = false;
    
    if ($con && $result = $con->query($check_table_query)) {
        if ($result->num_rows > 0) {
            $table_exists = true;
        }
    }
    
    if (!$table_exists) {
        // Create the points_requests table
        $create_table_query = "CREATE TABLE IF NOT EXISTS `points_requests` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `student_id` varchar(50) NOT NULL,
            `points_amount` int(11) NOT NULL DEFAULT 0,
            `request_type` varchar(50) NOT NULL,
            `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
            `request_date` datetime DEFAULT current_timestamp(),
            `processed_date` datetime DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        if ($con) {
            $con->query($create_table_query);
            error_log("Created points_requests table");
        }
    }
    
    // Check if points_history table exists
    $check_history_query = "SHOW TABLES LIKE 'points_history'";
    $history_exists = false;
    
    if ($con && $result = $con->query($check_history_query)) {
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
        
        if ($con) {
            $con->query($create_history_query);
            error_log("Created points_history table");
        }
    }
    
} catch (Exception $e) {
    error_log('Error checking/creating tables: ' . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Points Approval</title>
</head>
<body>

<div class="container max-w-7xl mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Pending Points Approval</h1>
        <p class="text-gray-600 mt-2">Manage student point requests that need your approval</p>
    </div>
    
    <!-- Alert Message -->
    <?php if (!empty($message)): ?>
        <div class="mb-6">
            <?php if ($message_type === 'success'): ?>
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700"><?php echo $message; ?></p>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700"><?php echo $message; ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 mr-4">
                    <i class="fas fa-clock text-blue-500 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm font-medium uppercase">Pending Requests</p>
                    <p class="text-2xl font-bold text-gray-800"><?php echo $pending_count; ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 mr-4">
                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm font-medium uppercase">Approved Today</p>
                    <p class="text-2xl font-bold text-gray-800"><?php echo $approved_today; ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 mr-4">
                    <i class="fas fa-times-circle text-red-500 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm font-medium uppercase">Rejected Today</p>
                    <p class="text-2xl font-bold text-gray-800"><?php echo $rejected_today; ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pending Requests Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Pending Point Requests</h3>
        </div>
        
        <?php if (!empty($pending_requests)): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="pendingPoints">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Points</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($pending_requests as $request): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo htmlspecialchars($request['id_number'] ?? ''); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?php 
                                        $last_name = $request['lastName'] ?? $request['last_name'] ?? '';
                                        $first_name = $request['firstName'] ?? $request['first_name'] ?? '';
                                        echo htmlspecialchars("$last_name, $first_name"); 
                                        ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        <?php echo ($request['request_type'] ?? '') === 'login' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'; ?>">
                                        <?php echo ucfirst($request['request_type'] ?? 'Unknown'); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-green-600">+<?php echo $request['points_amount'] ?? 0; ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500"><?php echo isset($request['request_date']) ? date('M j, Y g:i A', strtotime($request['request_date'])) : 'N/A'; ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <form method="post" class="inline-block">
                                        <input type="hidden" name="request_id" value="<?php echo $request['id'] ?? 0; ?>">
                                        <button type="submit" name="approve" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            <i class="fas fa-check mr-2"></i> Approve
                                        </button>
                                    </form>
                                    <form method="post" class="inline-block ml-2">
                                        <input type="hidden" name="request_id" value="<?php echo $request['id'] ?? 0; ?>">
                                        <button type="submit" name="reject" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <i class="fas fa-times mr-2"></i> Reject
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-8">
                <div class="mx-auto h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center mb-4">
                    <i class="fas fa-check text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">All caught up!</h3>
                <p class="mt-1 text-sm text-gray-500">There are no pending point requests at the moment.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable if it exists
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        $('#pendingPoints').DataTable({
            "pageLength": 10,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "columnDefs": [
                { "orderable": false, "targets": 5 } // Disable sorting on action buttons column
            ],
            "order": [[4, 'desc']], // Sort by request date by default
            "language": {
                "emptyTable": "No pending requests found",
                "zeroRecords": "No matching requests found"
            }
        });
    }
    
    <?php if (!empty($message) && $message_type === 'success'): ?>
    // SweetAlert for success notification if SweetAlert exists
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Success!',
            text: '<?php echo addslashes($message); ?>',
            icon: 'success',
            confirmButtonColor: '#0284c7',
            timer: 3000,
            timerProgressBar: true
        });
    }
    <?php elseif (!empty($message) && $message_type === 'error'): ?>
    // SweetAlert for error notification if SweetAlert exists
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Error!',
            text: '<?php echo addslashes($message); ?>',
            icon: 'error',
            confirmButtonColor: '#0284c7'
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