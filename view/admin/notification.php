<?php
require_once '../../includes/navbar_admin.php';

// Function to mark notifications as read
function mark_notifications_as_read($admin_id) {
    try {
        $db = Database::getInstance();
        $con = $db->getConnection();
        
        // First check if the is_read column exists
        $check_column = "SHOW COLUMNS FROM notification LIKE 'is_read'";
        $result = mysqli_query($con, $check_column);
        
        if (mysqli_num_rows($result) == 0) {
            // Column doesn't exist, add it
            $alter_table = "ALTER TABLE notification ADD COLUMN is_read TINYINT(1) DEFAULT 0";
            if (!mysqli_query($con, $alter_table)) {
                error_log("Failed to add is_read column: " . mysqli_error($con));
                return false;
            }
        }
        
        // Now update the notifications
        $sql = "UPDATE notification SET is_read = 1 WHERE id_number = ?";
        $stmt = $con->prepare($sql);
        
        if ($stmt === false) {
            error_log("Failed to prepare statement: " . mysqli_error($con));
            return false;
        }
        
        $stmt->bind_param("s", $admin_id);
        return $stmt->execute();
    } catch (Exception $e) {
        error_log("Error in mark_notifications_as_read: " . $e->getMessage());
        return false;
    }
}

// Mark notifications as read when page is loaded
if (isset($_SESSION['admin_id_number'])) {
    mark_notifications_as_read($_SESSION['admin_id_number']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications | Admin Dashboard</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Animation library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <!-- Inter font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .notification-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .notification-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        .animate-slide-in-up {
            animation: slideInUp 0.5s ease-in-out;
        }
        .stagger-item {
            opacity: 0;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                        <div class="bg-primary-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-bell text-primary-600"></i>
                        </div>
                        Notifications
                    </h1>
                    <p class="text-gray-500 mt-1 ml-12">View and manage your system notifications</p>
                </div>
            </div>
            
            <!-- Breadcrumbs -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm">
                    <li class="inline-flex items-center">
                        <a href="Admin.php" class="text-gray-500 hover:text-primary-600 transition-colors inline-flex items-center">
                            <i class="fas fa-home mr-2"></i>
                            Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <span class="text-gray-400 mx-2">/</span>
                            <span class="text-primary-600 font-medium">Notifications</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Notifications Section -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 mb-8 notification-card animate-fade-in">
            <div class="border-b border-gray-100 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">All Notifications</h2>
            </div>
            
            <div class="p-6">
                <div class="max-h-[30rem] overflow-y-auto pr-2 space-y-4">
                    <?php
                    $db = Database::getInstance();
                    $con = $db->getConnection();
                    $admin_id = $_SESSION['admin_id_number'];
                    
                    try {
                        $sql = "SELECT * FROM notification WHERE id_number = ? ORDER BY created_at DESC";
                        $stmt = $con->prepare($sql);
                        
                        if ($stmt === false) {
                            error_log("Failed to prepare statement: " . mysqli_error($con));
                            $notifications = [];
                        } else {
                            $stmt->bind_param("s", $admin_id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $notifications = $result->fetch_all(MYSQLI_ASSOC);
                        }
                    } catch (Exception $e) {
                        error_log("Error retrieving notifications: " . $e->getMessage());
                        $notifications = [];
                    }
                    
                    if (empty($notifications)): 
                    ?>
                        <div class="text-center py-16 text-gray-500">
                            <div class="bg-gray-100 rounded-full w-20 h-20 mx-auto mb-4 flex items-center justify-center">
                                <i class="fas fa-bell-slash text-gray-300 text-3xl"></i>
                            </div>
                            <p class="text-gray-600 font-medium text-lg">No notifications yet</p>
                            <p class="text-sm text-gray-400 mt-1">Your notifications will appear here</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($notifications as $index => $notification): ?>
                            <div class="rounded-lg border border-gray-200 p-5 notification-item stagger-item" style="transition-delay: <?php echo $index * 100; ?>ms">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mt-1">
                                        <div class="w-12 h-12 rounded-full flex items-center justify-center text-primary-600 shadow-md bg-white">
                                            <i class="fas fa-bell text-2xl"></i>
                                        </div>
                                    </div>
                                    <div class="ml-5 flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <h3 class="font-semibold text-gray-800 text-lg">Notification</h3>
                                            <span class="text-xs text-gray-500">
                                                <?php echo date('F j, Y g:i A', strtotime($notification['created_at'])); ?>
                                            </span>
                                        </div>
                                        <p class="text-gray-600"><?php echo htmlspecialchars($notification['message']); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Animate notification items on page load
        document.addEventListener('DOMContentLoaded', function() {
            const items = document.querySelectorAll('.stagger-item');
            items.forEach((item, index) => {
                setTimeout(() => {
                    item.style.opacity = '1';
                    item.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html> 