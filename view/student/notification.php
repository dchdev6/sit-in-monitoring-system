<?php
require_once '../../includes/navbar_student.php';

// Check for success message for sweet alert
$successMessage = '';
if(isset($_SESSION['success_message'])) {
    $successMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Student Notifications - Lab Sit-in Reservation System">
    <title>Notifications | CCS Lab System</title>
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
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        success: {
                            50: '#ecfdf5',
                            500: '#10b981',
                            600: '#059669',
                        },
                        warning: {
                            50: '#fffbeb',
                            500: '#f59e0b',
                            600: '#d97706',
                        },
                        danger: {
                            50: '#fef2f2',
                            500: '#ef4444',
                            600: '#dc2626',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'Segoe UI', 'Tahoma', 'sans-serif'],
                    },
                }
            }
        }
    </script>
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
        .animate-pulse-slow {
            animation: pulseSlow 3s infinite ease-in-out;
        }
        @keyframes pulseSlow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        .scrollbar-thin::-webkit-scrollbar {
            width: 4px;
        }
        .scrollbar-thin::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 10px;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
        .notification-item {
            transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }
        .notification-item:hover {
            background-color: #f9fafb;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans text-gray-800 opacity-0 transition-opacity duration-500">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                        <div class="bg-primary-100 p-2 rounded-lg mr-3 shadow-sm">
                            <i class="fas fa-bell text-primary-600"></i>
                        </div>
                        Notifications
                    </h1>
                    <p class="text-gray-500 mt-1 ml-12">View your system notifications and updates</p>
                </div>
                <div class="flex space-x-3 mt-4 md:mt-0">
                    <button id="refreshButton" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-2.5 px-4 rounded-lg transition duration-300 flex items-center shadow-sm">
                        <i class="fas fa-sync-alt mr-2 text-gray-500"></i>
                        Refresh
                    </button>
                </div>
            </div>
            
            <!-- Breadcrumbs -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm">
                    <li class="inline-flex items-center">
                        <a href="homepage.php" class="text-gray-500 hover:text-primary-600 transition-colors inline-flex items-center">
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

        <!-- Updated Notification Section -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 mb-8 notification-card animate-fade-in hover:border-primary-200">
            <div class="border-b border-gray-100 px-6 py-4 flex items-center">
                <div class=" p-3 mr-3">
                    <i class="fas fa-bell text-primary-600 animate-pulse-slow"></i>
                </div>
                <h2 class="text-lg font-semibold text-gray-800">Reservation Status Updates</h2>
            </div>
            
            <div class="p-6">
                <div class="max-h-[30rem] overflow-y-auto pr-2 space-y-4 scrollbar-thin">
                    <?php
                    $reservations = retrieve_student_reservation_logs($_SESSION['id_number']);
                    if(empty($reservations)): 
                    ?>
                        <div class="text-center py-16 text-gray-500">
                            <div class="bg-gray-100 rounded-full w-20 h-20 mx-auto mb-4 flex items-center justify-center">
                                <i class="fas fa-bell-slash text-gray-300 text-3xl"></i>
                            </div>
                            <p class="text-gray-600 font-medium text-lg">No notifications yet</p>
                            <p class="text-sm text-gray-400 mt-1">Your reservation updates will appear here</p>
                            <a href="reservation.php" class="mt-4 inline-block px-5 py-2 bg-primary-500 hover:bg-primary-600 text-white font-medium rounded-lg transition duration-300 text-sm shadow-md">
                                <i class="fas fa-plus-circle mr-2"></i>Create Reservation
                            </a>
                        </div>
                    <?php 
                    else: 
                        foreach($reservations as $index => $row): 
                            // Determine status styling
                            $statusColor = 'gray';
                            $statusBg = 'bg-gray-50';
                            $statusIcon = 'fa-question-circle';
                            
                            if(strtolower($row['status']) == 'approved') {
                                $statusColor = 'success';
                                $statusBg = 'bg-success-50';
                                $statusIcon = 'fa-check-circle';
                            } elseif(strtolower($row['status']) == 'pending') {
                                $statusColor = 'warning';
                                $statusBg = 'bg-warning-50';
                                $statusIcon = 'fa-clock';
                            } elseif(strtolower($row['status']) == 'rejected' || strtolower($row['status']) == 'declined') {
                                $statusColor = 'danger';
                                $statusBg = 'bg-danger-50';
                                $statusIcon = 'fa-times-circle';
                            }
                    ?>
                        <div class="rounded-lg border border-gray-200 p-5 notification-item stagger-item <?php echo $statusBg; ?>" style="transition-delay: <?php echo $index * 100; ?>ms">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mt-1">
                                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-<?php echo $statusColor; ?>-600 shadow-md bg-white">
                                        <i class="fas <?php echo $statusIcon; ?> text-2xl"></i>
                                    </div>
                                </div>
                                <div class="ml-5 flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="font-semibold text-gray-800 text-lg">Reservation #<?php echo substr(md5($row['id_number'] . $row['reservation_date']), 0, 8); ?></h3>
                                        <span class="text-xs font-medium px-3 py-1 rounded-full bg-<?php echo $statusColor; ?>-100 text-<?php echo $statusColor; ?>-800">
                                            <?php echo $row['status']; ?>
                                        </span>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm mt-3">
                                        <div>
                                            <p class="text-gray-500 flex items-center">
                                                <i class="fas fa-calendar-day w-5 text-primary-500"></i>
                                                <span class="font-medium mr-1">Date:</span> 
                                                <?php echo date('F j, Y', strtotime($row['reservation_date'])); ?>
                                            </p>
                                            <p class="text-gray-500 flex items-center mt-1">
                                                <i class="fas fa-clock w-5 text-primary-500"></i>
                                                <span class="font-medium mr-1">Time:</span> 
                                                <?php echo date('g:i A', strtotime($row['reservation_time'])); ?>
                                            </p>
                                            <p class="text-gray-500 flex items-center mt-1">
                                                <i class="fas fa-laptop-code w-5 text-primary-500"></i>
                                                <span class="font-medium mr-1">Lab:</span> 
                                                <?php echo $row['lab']; ?>
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500 flex items-center">
                                                <i class="fas fa-desktop w-5 text-primary-500"></i>
                                                <span class="font-medium mr-1">PC:</span> 
                                                <?php echo $row['pc_number'] ?: 'Not assigned yet'; ?>
                                            </p>
                                            <p class="text-gray-500 flex items-center mt-1">
                                                <i class="fas fa-code w-5 text-primary-500"></i>
                                                <span class="font-medium mr-1">Purpose:</span> 
                                                <?php echo $row['purpose']; ?>
                                            </p>
                                            <p class="text-gray-500 flex items-center mt-1">
                                                <i class="fas fa-id-card w-5 text-primary-500"></i>
                                                <span class="font-medium mr-1">ID:</span> 
                                                <?php echo $row['id_number']; ?>
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <?php if(strtolower($row['status']) == 'approved'): ?>
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <div class="flex justify-end">
                                            <a href="check_in.php?id=<?php echo $row['reservation_id']; ?>" class="text-sm bg-success-500 hover:bg-success-400 text-white py-2 px-4 rounded-lg transition duration-300 flex items-center shadow-md">
                                                <i class="fas fa-sign-in-alt mr-2"></i> Check In
                                            </a>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php 
                        endforeach; 
                    endif; 
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Page load animation
        document.addEventListener('DOMContentLoaded', function() {
            // Fade in the body
            setTimeout(() => {
                document.body.style.opacity = "1";
            }, 100);
            
            // Refresh Button functionality
            const refreshButton = document.getElementById('refreshButton');
            if (refreshButton) {
                refreshButton.addEventListener('click', function() {
                    // Add rotate animation to the icon
                    const icon = this.querySelector('i');
                    icon.classList.add('animate-spin');
                    
                    // Disable the button temporarily
                    this.disabled = true;
                    
                    // Reload the page after a short delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                });
            }
            
            // Stagger in elements with class .stagger-item
            const staggerItems = document.querySelectorAll('.stagger-item');
            staggerItems.forEach((item, index) => {
                setTimeout(() => {
                    item.style.opacity = "1";
                    item.classList.add('animate-slide-in-up');
                }, 300 + (index * 150));
            });
            
            // Show success message if available
            <?php if(!empty($successMessage)): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '<?php echo $successMessage; ?>',
                confirmButtonColor: '#0284c7',
                timer: 3000,
                timerProgressBar: true,
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            });
            <?php endif; ?>
        });
    </script>
</body>
</html>