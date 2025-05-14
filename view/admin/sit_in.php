<?php
include '../../includes/navbar_admin.php';

// Get sit-in records and initialize as an empty array if null
$listPerson = retrieve_sit_in();
if (!is_array($listPerson)) {
    $listPerson = [];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sit In Records</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Animation Library - Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  
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
                    },
                    fontFamily: {
                        sans: ['Inter', 'Segoe UI', 'Tahoma', 'sans-serif'],
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        pulse: {
                            '0%, 100%': { transform: 'scale(1)' },
                            '50%': { transform: 'scale(1.05)' },
                        },
                        shimmer: {
                            '0%': { backgroundPosition: '-1000px 0' },
                            '100%': { backgroundPosition: '1000px 0' },
                        },
                    },
                    animation: {
                        fadeIn: 'fadeIn 0.5s ease-out',
                        slideUp: 'slideUp 0.5s ease-out',
                        pulse: 'pulse 2s infinite',
                        shimmer: 'shimmer 2s infinite linear',
                    },
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        /* DataTables Custom Styling */
        .dataTables_wrapper {
            background-color: transparent;
            padding: 0.5rem;
        }
        
        .dataTables_filter input {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem; /* Remove left padding that was accommodating the icon */
            margin-left: 0.5rem;
            font-size: 0.875rem;
            transition: all 0.2s;
            background-image: none; /* Remove the background image */
        }
        
        .dataTables_filter input:focus {
            outline: none;
            border-color: #0ea5e9;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15);
        }
        
        .dataTables_length select {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem; /* Adjust padding (remove extra right padding) */
            font-size: 0.875rem;
            transition: all 0.2s;
            background-image: none;
            -webkit-appearance: auto; /* Reset to browser default */
            appearance: auto; /* Reset to browser default */
        }
        
        .dataTables_length select:focus {
            outline: none;
            border-color: #0ea5e9;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15);
        }
        
        .dataTables_info, .dataTables_length, .dataTables_filter {
            margin-bottom: 1rem;
            font-size: 0.875rem;
            color: #4b5563;
        }
        
        .dataTables_paginate {
            margin-top: 1.5rem;
            display: flex;
            justify-content: center;
        }
        
        .dataTables_paginate .paginate_button {
            padding: 0.5rem 0.75rem;
            margin: 0 0.25rem;
            border-radius: 0.375rem;
            border: 1px solid #e5e7eb;
            background-color: #fff;
            color: #374151;
            transition: all 0.2s;
        }
        
        .dataTables_paginate .paginate_button.current {
            background-color: #0ea5e9 !important;
            border-color: #0ea5e9 !important;
            color: white !important;
            font-weight: 500;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
        
        .dataTables_paginate .paginate_button:hover:not(.current):not(.disabled) {
            background-color: #f3f4f6 !important;
            color: #111827 !important;
            border-color: #e5e7eb !important;
        }
        
        .dataTables_paginate .paginate_button.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        table.dataTable {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            border-radius: 0.5rem;
            overflow: hidden;
        }
        
        table.dataTable thead th {
            background: #f9fafb;
            color: #374151;
            font-weight: 600;
            padding: 1rem;
            text-align: left;
            border-bottom: 2px solid #e5e7eb;
            white-space: nowrap;
            position: relative;
        }
        
        table.dataTable thead th::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            height: 0;
            width: 0;
            background-color: transparent;
            transition: none;
        }
        
        table.dataTable thead th:hover::after {
            width: 0;
        }
        
        table.dataTable tbody tr {
            transition: all 0.3s ease;
        }
        
        table.dataTable tbody tr:hover {
            background-color: #f0f9ff;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        table.dataTable tbody td {
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
            transition: all 0.2s ease;
        }
        
        /* Status Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.875rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            text-align: center;
            transition: all 0.3s ease;
            white-space: nowrap;
        }
        
        .status-badge.low {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        
        .status-badge.medium {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .status-badge.good {
            background-color: #d1fae5;
            color: #047857;
        }
        
        .status-badge.active {
            background-color: #d1fae5;
            color: #047857;
        }
        
        .status-badge.inactive {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        
        .status-badge i {
            margin-right: 0.375rem;
            font-size: 0.75rem;
        }
        
        /* Button Animations */
        .btn-animated {
            position: relative;
            overflow: hidden;
            transform: translateZ(0);
        }
        
        .btn-animated::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300%;
            height: 300%;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(-50%, -50%) scale(0);
            transition: transform 0.6s ease-out;
        }
        
        .btn-animated:hover::before {
            transform: translate(-50%, -50%) scale(1);
        }
        
        /* Card hover effects */
        .stat-card {
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .stat-card:hover .icon-container {
            transform: scale(1.1);
        }
        
        .icon-container {
            transition: transform 0.3s ease;
        }
        
        /* Shimmer effect */
        .shimmer {
            background: linear-gradient(to right, #f6f7f8 0%, #edeef1 20%, #f6f7f8 40%, #f6f7f8 100%);
            background-size: 1000px 100%;
            animation: shimmer 2s infinite linear;
        }
        
        /* Counter Animation */
        .counter-value {
            display: inline-block;
            transition: all 0.5s ease;
        }
        
        /* Action buttons */
        .action-btn {
            width: 2.25rem;
            height: 2.25rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .action-btn::after {
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            background-image: radial-gradient(circle, #fff 10%, transparent 10.01%);
            background-repeat: no-repeat;
            background-position: 50%;
            transform: scale(10, 10);
            opacity: 0;
            transition: transform 0.5s, opacity 1s;
        }
        
        .action-btn:active::after {
            transform: scale(0, 0);
            opacity: 0.3;
            transition: 0s;
        }
        
        /* Action button tooltips */
        .tooltip-container {
            position: relative;
        }
        
        .tooltip {
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%) translateY(10px);
            background-color: #1f2937;
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s ease;
            z-index: 10;
        }
        
        .tooltip::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border-width: 5px;
            border-style: solid;
            border-color: #1f2937 transparent transparent transparent;
        }
        
        .tooltip-container:hover .tooltip {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
        }
    </style>
</head>

<body class="bg-gray-50 font-sans text-gray-800">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                        <div class="bg-primary-100 p-2 rounded-lg mr-3 shadow-sm">
                            <i class="fas fa-users text-primary-600"></i>
                        </div>
                        Active Sit-In Sessions
                    </h1>
                    <p class="text-gray-500 mt-1 ml-12">Monitor students currently using laboratory facilities</p>
                </div>
                <div class="flex space-x-3 mt-4 md:mt-0">
                    <button id="refreshButton" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-2.5 px-4 rounded-lg transition duration-300 flex items-center shadow-sm">
                        <i class="fas fa-sync-alt mr-2 text-gray-500"></i>
                        Refresh Data
                    </button>
                </div>
            </div>
            
            <!-- Breadcrumbs -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm">
                    <li class="inline-flex items-center">
                        <a href="admin.php" class="text-gray-500 hover:text-primary-600 transition-colors inline-flex items-center">
                            <i class="fas fa-home mr-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <span class="text-gray-400 mx-2">/</span>
                            <span class="text-primary-600 font-medium">Sit-In Management</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8 animate__animated animate__fadeInUp animate__faster">
            <div class="border-b border-gray-100 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-desktop text-primary-500 mr-2"></i>
                    Active Sit-In Sessions
                </h2>
            </div>
            <div class="p-6">
                <table id="sitInTable" class="w-full">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student Name</th>
                            <th>ID Number</th>
                            <th>Purpose</th>
                            <th>Laboratory</th>
                            <th>Remaining Sessions</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($listPerson as $person) : ?>
                            <tr class="row-animation">
                                <td class="font-medium"><?php echo $person['sit_id']; ?></td>
                                <td><?php echo $person['firstName'] . " " . $person['middleName'] . ". " . $person['lastName']; ?></td>
                                <td><?php echo $person['id_number']; ?></td>
                                <td><?php echo $person['sit_purpose']; ?></td>
                                <td>
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <?php echo $person['sit_lab']; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php 
                                        $session = $person['session'];
                                        $statusClass = '';
                                        $statusIcon = '';
                                        
                                        if($session <= 6) {
                                            $statusClass = 'low';
                                            $statusIcon = 'fa-exclamation-circle';
                                        } else if($session <= 15) {
                                            $statusClass = 'medium';
                                            $statusIcon = 'fa-clock';
                                        } else {
                                            $statusClass = 'good';
                                            $statusIcon = 'fa-check-circle';
                                        }
                                    ?>
                                    <span class="status-badge <?php echo $statusClass; ?>">
                                        <i class="fas <?php echo $statusIcon; ?>"></i>
                                        <?php echo $session; ?> sessions
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge active">
                                        <i class="fas fa-circle text-xs mr-1"></i>
                                        <?php echo $person['status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="flex justify-center space-x-2">
                                        <div class="tooltip-container">
                                            <button type="button" class="action-btn bg-primary-50 hover:bg-primary-100 text-primary-600 end-session" data-id="<?php echo $person['sit_id']; ?>" data-student="<?php echo $person['id_number']; ?>">
                                                <i class="fas fa-sign-out-alt"></i>
                                            </button>
                                            <div class="tooltip">End Session</div>
                                        </div>
                                        
                                        <div class="tooltip-container">
                                            <button class="action-btn bg-gray-50 hover:bg-gray-100 text-gray-700 view-details" data-id="<?php echo $person['id_number']; ?>">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <div class="tooltip">View Details</div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Quick Help Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8 animate__animated animate__fadeInUp animate__faster">
            <div class="flex items-start">
                <div class="flex-shrink-0 bg-primary-50 rounded-lg p-3 mr-4">
                    <i class="fas fa-lightbulb text-primary-500 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-800 mb-2">Quick Tips</h3>
                    <div class="text-sm text-gray-600 space-y-2">
                        <p>• Use the search box to quickly find active sit-in sessions by student name or ID</p>
                        <p>• Click "End Session" to log a student out from their current sit-in session</p>
                        <p>• Students with fewer than 6 sessions remaining are marked in red</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery (required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Animation Library - Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    
    <script>
        $(document).ready(function() {
            // Initialize animations for page elements
            function animateElements() {
                $('.animate__animated').each(function(i) {
                    $(this).css('opacity', '0');
                    
                    setTimeout(() => {
                        $(this).css('opacity', '1');
                    }, i * 100);
                });
            }
            
            animateElements();
            
            // Initialize DataTable with row animation
            const table = $('#sitInTable').DataTable({
                responsive: true,
                language: {
                    search: "",
                    searchPlaceholder: "Search sessions...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ sessions",
                    paginate: {
                        first: '<i class="fas fa-angle-double-left"></i>',
                        previous: '<i class="fas fa-angle-left"></i>',
                        next: '<i class="fas fa-angle-right"></i>',
                        last: '<i class="fas fa-angle-double-right"></i>'
                    }
                },
                order: [[0, 'desc']], // Order by ID descending
                columnDefs: [
                    { orderable: false, targets: 7 } // Disable sorting on actions column
                ],
                "drawCallback": function() {
                    // Animate rows when table is drawn or redrawn
                    $('.row-animation').each(function(i) {
                        const $row = $(this);
                        $row.css('opacity', 0);
                        
                        setTimeout(function() {
                            $row.animate({
                                opacity: 1,
                                transform: 'translateY(0)'
                            }, {
                                duration: 300,
                                start: function() {
                                    $row.css('transform', 'translateY(0px)');
                                }
                            });
                        }, 50 * i); // Stagger the animations
                    });
                    
                    // Enhance pagination
                    $('.dataTables_paginate .paginate_button').addClass('hover:shadow-sm');
                    $('.dataTables_paginate .paginate_button.current').css('background-color', '#0284c7').css('border-color', '#0284c7');
                    
                    // Add icons to pagination buttons if not already present
                    if ($('.dataTables_paginate .previous i').length === 0) {
                        $('.dataTables_paginate .previous').html('<i class="fas fa-angle-left"></i>');
                        $('.dataTables_paginate .next').html('<i class="fas fa-angle-right"></i>');
                        $('.dataTables_paginate .first').html('<i class="fas fa-angle-double-left"></i>');
                        $('.dataTables_paginate .last').html('<i class="fas fa-angle-double-right"></i>');
                    }
                },
                initComplete: function() {
                    // Add custom classes to DataTable elements
                    $('.dataTables_filter').addClass('relative');
                    $('.dataTables_filter label').addClass('flex items-center');
                    $('.dataTables_filter input').addClass('focus:border-[#0284c7] focus:ring focus:ring-[#0284c7] focus:ring-opacity-20');
                    $('.dataTables_length select').addClass('focus:border-[#0284c7] focus:ring focus:ring-[#0284c7] focus:ring-opacity-20');
                    
                    // Add icon to search input
                    $('.dataTables_filter label').prepend('<i class="fas fa-search text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>');
                    $('.dataTables_filter input').addClass('pl-10').css('padding-left', '2.5rem');
                }
            });
            
            // Add shimmer effect to search when typing
            $('.dataTables_filter input').on('input', function() {
                $(this).addClass('shimmer');
                setTimeout(() => {
                    $(this).removeClass('shimmer');
                }, 500);
            });
            
            // Refresh button functionality
            $('#refreshButton').on('click', function() {
                $(this).addClass('animate-pulse');
                
                // Show loading indicator
                Swal.fire({
                    title: 'Refreshing...',
                    html: 'Updating sit-in session data',
                    timer: 1000,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    willClose: () => {
                        $(this).removeClass('animate-pulse');
                        // Reload the page to fetch fresh data
                        window.location.reload();
                    }
                });
            });
            
            // End session functionality
            $('.end-session').on('click', function() {
                const sessionId = $(this).data('id');
                const studentId = $(this).data('student');
                
                Swal.fire({
                    title: '<div class="flex items-center"><i class="fas fa-door-open text-yellow-500 mr-3"></i>End Session</div>',
                    html: `
                        <div class="text-left">
                            <p class="mb-3">End sit-in session for ID: <strong>${sessionId}</strong>?</p>
                            <div class="bg-yellow-50 text-yellow-800 p-3 rounded-lg text-sm flex items-start mb-4">
                                <i class="fas fa-info-circle mr-2 mt-0.5"></i>
                                <span>This will log out the student and decrease their session count by 1.</span>
                            </div>
                            
                            <!-- Point Award Option -->
                            <div class="mt-4 border-t pt-3 border-gray-200">
                                <div class="text-gray-700 font-medium mb-2">Reward Student:</div>
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" id="awardPoints" class="form-checkbox h-5 w-5 text-primary-600 rounded border-gray-300 focus:ring-primary-500" checked>
                                    <label for="awardPoints" class="text-gray-700 flex items-center">
                                        <span class="mr-1">Award 1 point to student</span>
                                        <span class="inline-flex items-center justify-center text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">
                                            <i class="fas fa-award mr-1"></i> +1
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonColor: '#0ea5e9',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: '<i class="fas fa-check mr-2"></i>Yes, end session',
                    cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown animate__faster'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp animate__faster'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Get the award points value
                        const awardPoints = $('#awardPoints').is(':checked');
                        
                        // Show loading state
                        Swal.fire({
                            title: 'Processing...',
                            html: `Ending sit-in session ${sessionId}`,
                            timer: 1500,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading();
                                
                                // You would add an AJAX request to your endpoint here
                                // Include the awardPoints parameter in your request
                                $.ajax({
                                    url: '../../api/api_admin.php', // You'll need to create/update this endpoint
                                    type: 'POST',
                                    data: {
                                        action: 'end_session',
                                        sessionId: sessionId,
                                        studentId: studentId,
                                        awardPoints: awardPoints ? 1 : 0
                                    },
                                    dataType: 'json',
                                    success: function(response) {
                                        // Handle success
                                        let message = 'The student has been logged out successfully.';
                                        if (awardPoints) {
                                            message += ' 1 point has been awarded.';
                                        }
                                        
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Session Ended!',
                                            html: message,
                                            confirmButtonColor: '#0ea5e9'
                                        }).then(() => {
                                            // Reload the page to refresh the data
                                            window.location.reload();
                                        });
                                    },
                                    error: function(xhr, status, error) {
                                        // Handle error
                                        console.error("Error ending session:", error);
                                        
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error!',
                                            text: 'Could not end the session. Please try again.',
                                            confirmButtonColor: '#0ea5e9'
                                        });
                                    }
                                });
                            },
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false
                        });
                    }
                });
            });
            
            // View student details functionality
            $('.view-details').on('click', function() {
                const studentId = $(this).data('id');
                
                // Add spin animation
                $(this).addClass('animate-spin');
                setTimeout(() => {
                    $(this).removeClass('animate-spin');
                }, 500);
                
                // Extract data from the table row
                const rowData = $(this).closest('tr').find('td');
                const sessionId = rowData.eq(0).text().trim();
                const studentName = rowData.eq(1).text().trim();
                const purpose = rowData.eq(3).text().trim();
                const lab = rowData.eq(4).text().trim();
                const sessions = rowData.eq(5).text().trim();
                
                // Show student details modal
                Swal.fire({
                    title: '<div class="flex items-center justify-between w-full pr-6"><span class="flex items-center"><i class="fas fa-user-graduate text-primary-600 mr-2"></i>Sit-In Details</span><span class="text-sm font-normal text-gray-500">ID: ' + studentId + '</span></div>',
                    html: `
                        <div class="bg-white rounded-lg border border-gray-200">
                            <!-- Name and badge row -->
                            <div class="px-4 py-3 flex items-center justify-between border-b border-gray-200">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-primary-600"></i>
                                    </div>
                                    <h3 class="font-bold text-gray-800">${studentName}</h3>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="bg-blue-50 px-2 py-0.5 rounded text-xs text-blue-700">${purpose}</span>
                                    <span class="bg-purple-50 px-2 py-0.5 rounded text-xs text-purple-700">${lab}</span>
                                    <span class="status-badge ${parseInt(sessions) <= 6 ? 'low' : (parseInt(sessions) <= 15 ? 'medium' : 'good')}">
                                        <i class="fas ${parseInt(sessions) <= 6 ? 'fa-exclamation-circle' : (parseInt(sessions) <= 15 ? 'fa-clock' : 'fa-check-circle')}"></i>
                                        ${sessions}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Information row - all in one line -->
                            <div class="grid grid-cols-3 divide-x divide-gray-200">
                                <div class="p-3 flex items-center">
                                    <i class="fas fa-clock text-primary-500 mr-2"></i>
                                    <div class="overflow-hidden">
                                        <div class="text-xs text-gray-500">Session ID</div>
                                        <div class="font-medium text-sm">#${sessionId}</div>
                                    </div>
                                </div>
                                
                                <div class="p-3 flex items-center">
                                    <i class="fas fa-calendar-alt text-primary-500 mr-2"></i>
                                    <div>
                                        <div class="text-xs text-gray-500">Start Time</div>
                                        <div class="font-medium text-sm">${new Date().toLocaleTimeString()}</div>
                                    </div>
                                </div>
                                
                                <div class="p-3 flex justify-center">
                                    <button type="button" class="text-primary-600 border border-primary-300 bg-primary-50 hover:bg-primary-100 text-sm font-medium py-1.5 px-4 rounded-lg transition-all duration-300 flex items-center" onclick="endSession('${sessionId}', '${studentId}')">
                                        <i class="fas fa-sign-out-alt mr-2"></i> End Session
                                    </button>
                                </div>
                            </div>
                        </div>
                    `,
                    showConfirmButton: false,
                    showCancelButton: false,
                    showCloseButton: true,
                    padding: '0.5rem',
                    width: 'auto',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown animate__faster'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp animate__faster'
                    },
                    customClass: {
                        container: 'student-details-modal',
                        popup: 'rounded-lg shadow-md max-w-4xl',
                        closeButton: 'focus:outline-none text-gray-500 hover:text-gray-700',
                        title: 'pr-8' // Add right padding to the title
                    }
                });
            });

            // Define the endSession function in the global scope
            window.endSession = function(sessionId, studentId) {
                Swal.fire({
                    title: '<div class="flex items-center"><i class="fas fa-door-open text-yellow-500 mr-3"></i>End Session</div>',
                    html: `
                        <div class="text-left">
                            <p class="mb-3">End sit-in session for ID: <strong>${sessionId}</strong>?</p>
                            <div class="bg-yellow-50 text-yellow-800 p-3 rounded-lg text-sm flex items-start mb-4">
                                <i class="fas fa-info-circle mr-2 mt-0.5"></i>
                                <span>This will log out the student and decrease their session count by 1.</span>
                            </div>
                            
                            <!-- Point Award Option -->
                            <div class="mt-4 border-t pt-3 border-gray-200">
                                <div class="text-gray-700 font-medium mb-2">Reward Student:</div>
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" id="awardPointsModal" class="form-checkbox h-5 w-5 text-primary-600 rounded border-gray-300 focus:ring-primary-500" checked>
                                    <label for="awardPointsModal" class="text-gray-700 flex items-center">
                                        <span class="mr-1">Award 1 point to student</span>
                                        <span class="inline-flex items-center justify-center text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">
                                            <i class="fas fa-award mr-1"></i> +1
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonColor: '#0ea5e9',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: '<i class="fas fa-check mr-2"></i>Yes, end session',
                    cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown animate__faster'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp animate__faster'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Get the award points value
                        const awardPoints = $('#awardPointsModal').is(':checked');
                        
                        // Show loading state
                        Swal.fire({
                            title: 'Processing...',
                            html: `Ending sit-in session ${sessionId}`,
                            timer: 1500,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading();
                                
                                // You would add an AJAX request to your endpoint here
                                // Include the awardPoints parameter in your request
                                $.ajax({
                                    url: '../../api/api_admin.php', // You'll need to create/update this endpoint
                                    type: 'POST',
                                    data: {
                                        action: 'end_session',
                                        sessionId: sessionId,
                                        studentId: studentId,
                                        awardPoints: awardPoints ? 1 : 0
                                    },
                                    dataType: 'json',
                                    success: function(response) {
                                        // Handle success
                                        let message = 'The student has been logged out successfully.';
                                        if (awardPoints) {
                                            message += ' 1 point has been awarded.';
                                        }
                                        
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Session Ended!',
                                            html: message,
                                            confirmButtonColor: '#0ea5e9'
                                        }).then(() => {
                                            // Reload the page to refresh the data
                                            window.location.reload();
                                        });
                                    },
                                    error: function(xhr, status, error) {
                                        // Handle error
                                        console.error("Error ending session:", error);
                                        
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error!',
                                            text: 'Could not end the session. Please try again.',
                                            confirmButtonColor: '#0ea5e9'
                                        });
                                    }
                                });
                            },
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false
                        });
                    }
                });
            };
        });
    </script>
</body>
</html>