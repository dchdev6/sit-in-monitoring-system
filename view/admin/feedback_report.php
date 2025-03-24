<?php
include '../../includes/navbar_admin.php';

// Include backend files
require_once '../../backend/backend_admin.php'; 
require_once '../../backend/database_connection.php';

$listPerson = retrieve_current_sit_in();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sit In Records</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/2.0.2/css/dataTables.tailwind.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Animation CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    
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
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'pulse-slow': 'pulse 3s infinite'
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' }
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' }
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Custom Styles -->
    <style>
        .dataTables_wrapper .dataTables_length, 
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_processing,
        .dataTables_wrapper .dataTables_paginate {
            margin-top: 1rem;
            margin-bottom: 1rem;
            color: #374151;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
            margin-left: 0.25rem;
            margin-right: 0.25rem;
            border-radius: 0.25rem;
            border: 1px solid #d1d5db;
            background-color: white;
            transition: all 0.2s;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #e0f2fe;
            border-color: #0ea5e9;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #0284c7;
            color: white !important;
            border-color: #0284c7;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background-color: #0369a1;
        }
        
        .fade-in-element {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease forwards;
        }
        
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .staggered-animation > tr {
            opacity: 0;
            animation: fadeIn 0.5s ease forwards;
        }
        
        .staggered-animation > tr:nth-child(1) { animation-delay: 0.1s; }
        .staggered-animation > tr:nth-child(2) { animation-delay: 0.2s; }
        .staggered-animation > tr:nth-child(3) { animation-delay: 0.3s; }
        .staggered-animation > tr:nth-child(4) { animation-delay: 0.4s; }
        .staggered-animation > tr:nth-child(5) { animation-delay: 0.5s; }
        .staggered-animation > tr:nth-child(6) { animation-delay: 0.6s; }
        .staggered-animation > tr:nth-child(7) { animation-delay: 0.7s; }
        .staggered-animation > tr:nth-child(8) { animation-delay: 0.8s; }
        .staggered-animation > tr:nth-child(9) { animation-delay: 0.9s; }
        .staggered-animation > tr:nth-child(10) { animation-delay: 1s; }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">

    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <h1 class="text-3xl font-bold text-primary-700 mb-8 text-center animate-fade-in">
            <span class="inline-block transform hover:scale-105 transition-transform duration-300">
                <i class="fas fa-users mr-2"></i>Sit In Records
            </span>
        </h1>
        
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6 animate-slide-up">
            <div class="flex justify-between items-center flex-wrap mb-4">
                <div class="mb-4 lg:mb-0">
                    <h2 class="text-xl font-semibold text-gray-800">
                        <i class="fas fa-desktop text-primary-600 mr-2"></i>Current Sit In Overview
                    </h2>
                    <p class="text-gray-600 mt-1">Comprehensive view of all current laboratory users</p>
                </div>
                
                <div class="flex space-x-2">
                    <button id="refreshBtn" class="bg-primary-100 hover:bg-primary-200 text-primary-700 px-4 py-2 rounded-lg transition-colors duration-300 flex items-center">
                        <i class="fas fa-sync-alt mr-2"></i> Refresh
                    </button>
                    
                    <div class="dropdown relative inline-block">
                        <button class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg transition-colors duration-300 flex items-center">
                            <i class="fas fa-download mr-2"></i> Export
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table id="sitInTable" class="w-full text-sm text-left text-gray-700">
                    <thead class="text-xs uppercase bg-primary-600 text-white">
                        <tr>
                            <th class="px-4 py-3 text-center">Sit-in Number</th>
                            <th class="px-4 py-3 text-center">ID Number</th>
                            <th class="px-4 py-3 text-center">Name</th>
                            <th class="px-4 py-3 text-center">Purpose</th>
                            <th class="px-4 py-3 text-center">Lab</th>
                            <th class="px-4 py-3 text-center">Login</th>
                            <th class="px-4 py-3 text-center">Logout</th>
                            <th class="px-4 py-3 text-center">Date</th>
                        </tr>
                    </thead>
                    <tbody class="staggered-animation">
                        <?php if (!empty($listPerson)) : ?>
                            <?php foreach ($listPerson as $person) : ?>
                                <tr class="bg-white border-b hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 text-center"><?php echo htmlspecialchars($person['sit_id']); ?></td>
                                    <td class="px-4 py-3 text-center"><?php echo htmlspecialchars($person['id_number']); ?></td>
                                    <td class="px-4 py-3 text-center font-medium"><?php echo htmlspecialchars($person['firstName'] . " " . $person['lastName']); ?></td>
                                    <td class="px-4 py-3 text-center"><?php echo htmlspecialchars($person['sit_purpose']); ?></td>
                                    <td class="px-4 py-3 text-center"><?php echo htmlspecialchars($person['sit_lab']); ?></td>
                                    <td class="px-4 py-3 text-center"><?php echo htmlspecialchars($person['sit_login']); ?></td>
                                    <td class="px-4 py-3 text-center"><?php echo htmlspecialchars($person['sit_logout']); ?></td>
                                    <td class="px-4 py-3 text-center"><?php echo htmlspecialchars($person['sit_date']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="8" class="px-4 py-3 text-center text-gray-500">No sit-in data available</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Analytics Cards Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Users Card -->
            <div class="bg-white rounded-xl shadow-md p-6 opacity-0 animate-fade-in" style="animation-delay: 0.2s; animation-fill-mode: forwards;">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-primary-600">
                        <i class="fas fa-users fa-lg"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm">Total Users</h3>
                        <div class="flex items-center">
                            <span class="text-2xl font-bold text-gray-800">
                                <?php echo count($listPerson); ?>
                            </span>
                            <span class="ml-2 text-sm text-green-600">
                                <i class="fas fa-arrow-up"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Lab Utilization Card -->
            <div class="bg-white rounded-xl shadow-md p-6 opacity-0 animate-fade-in" style="animation-delay: 0.4s; animation-fill-mode: forwards;">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-desktop fa-lg"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm">Lab Utilization</h3>
                        <div class="flex items-center">
                            <span class="text-2xl font-bold text-gray-800">
                                <?php 
                                    // Get count of unique labs
                                    $uniqueLabs = array();
                                    if (!empty($listPerson)) {
                                        foreach ($listPerson as $person) {
                                            if (isset($person['sit_lab']) && !in_array($person['sit_lab'], $uniqueLabs)) {
                                                $uniqueLabs[] = $person['sit_lab'];
                                            }
                                        }
                                    }
                                    echo count($uniqueLabs);
                                ?>
                            </span>
                            <span class="ml-2 text-sm text-green-600">Labs</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Active Time Card -->
            <div class="bg-white rounded-xl shadow-md p-6 opacity-0 animate-fade-in" style="animation-delay: 0.6s; animation-fill-mode: forwards;">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-clock fa-lg"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm">Active Users</h3>
                        <div class="flex items-center">
                            <span class="text-2xl font-bold text-gray-800">
                                <?php 
                                    // Count users with no logout time
                                    $activeUsers = 0;
                                    if (!empty($listPerson)) {
                                        foreach ($listPerson as $person) {
                                            if (empty($person['sit_logout']) || $person['sit_logout'] == 'N/A') {
                                                $activeUsers++;
                                            }
                                        }
                                    }
                                    echo $activeUsers;
                                ?>
                            </span>
                            <span class="ml-2 text-sm text-purple-600">
                                <i class="fas fa-user-clock"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Today's Date Card -->
            <div class="bg-white rounded-xl shadow-md p-6 opacity-0 animate-fade-in" style="animation-delay: 0.8s; animation-fill-mode: forwards;">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-calendar-day fa-lg"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm">Today's Date</h3>
                        <div class="flex items-center">
                            <span class="text-lg font-bold text-gray-800">
                                <?php echo date("M d, Y"); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center text-gray-500 text-sm mt-8 opacity-0 animate-fade-in" style="animation-delay: 1s; animation-fill-mode: forwards;">
            <p>© <?php echo date("Y"); ?> Admin Dashboard. All rights reserved.</p>
        </div>
    </div>

    <!-- JS Libraries -->
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.tailwind.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- DataTables Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable with export buttons
            const table = $('#sitInTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        className: 'bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg mr-2',
                        text: '<i class="fas fa-file-excel mr-1"></i> Excel'
                    },
                    {
                        extend: 'pdf',
                        className: 'bg-primary-600 hover:bg-primary-700 text-white px-3 py-2 rounded-lg mr-2',
                        text: '<i class="fas fa-file-pdf mr-1"></i> PDF'
                    },
                    {
                        extend: 'print',
                        className: 'bg-primary-600 hover:bg-primary-700 text-white px-3 py-2 rounded-lg',
                        text: '<i class="fas fa-print mr-1"></i> Print'
                    }
                ],
                responsive: true,
                pageLength: 10,
                language: {
                    search: "<span class='text-gray-700 font-medium'>Search:</span>",
                    lengthMenu: "<span class='text-gray-700 font-medium'>Show _MENU_ entries</span>",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        first: '<i class="fas fa-angle-double-left"></i>',
                        last: '<i class="fas fa-angle-double-right"></i>',
                        previous: '<i class="fas fa-angle-left"></i>',
                        next: '<i class="fas fa-angle-right"></i>'
                    }
                }
            });
            
            // Apply staggered animation to table rows
            function applyRowAnimations() {
                $('#sitInTable tbody tr').each(function(index) {
                    const $row = $(this);
                    setTimeout(function() {
                        $row.addClass('fade-in-element');
                    }, index * 100);
                });
            }
            
            // Call once on initial load
            applyRowAnimations();
            
            // Refresh button animation and functionality
            $('#refreshBtn').on('click', function() {
                const $icon = $(this).find('i');
                $icon.addClass('animate-spin');
                
                // Simulate refresh with animation
                setTimeout(function() {
                    location.reload();
                }, 800);
            });
        });
    </script>
</body>
</html>