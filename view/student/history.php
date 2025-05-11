<?php
require_once '../../includes/navbar_student.php';

$listPerson = retrieve_student_history($_SESSION['id_number']);

// Check for success message for sweet alert
$successMessage = '';
if(isset($_SESSION['success_message'])) {
    $successMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

// Handle feedback submission
if (isset($_POST['submit_feedback'])) {
    $idNumber = $_POST['id_number'];
    $lab = $_POST['sit_lab'];
    $feedback = $_POST['feedback_text'];

    if (!empty($feedback)) {
        require_once '../../backend/database_connection.php'; // Use the correct database connection file
        require_once '../../backend/backend_student.php'; // Include the backend functions
        
        // Use the function to submit feedback
        $result = submit_feedback($idNumber, $lab, $feedback);
        
        if ($result) {
            $_SESSION['success_message'] = "Your feedback has been submitted successfully!";
        } else {
            $_SESSION['success_message'] = "Failed to save feedback. Please try again.";
        }
    } else {
        $_SESSION['success_message'] = "Feedback cannot be empty.";
    }

    header("Location: history.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Student Sit-in History and Feedback Management">
    <title>History | Sit-in Monitoring System</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Animation library -->
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
                }
            }
        }
    </script>
    <!-- Inter font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        .stat-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 0.75rem;
            box-shadow: 0 0 0 2px rgba(14, 165, 233, 0);
            transition: box-shadow 0.3s ease;
            pointer-events: none;
        }
        .stat-card:hover::after {
            box-shadow: 0 0 0 2px rgba(14, 165, 233, 0.3);
        }
        .stat-card .icon-wrapper {
            transition: transform 0.5s ease;
        }
        .stat-card:hover .icon-wrapper {
            transform: scale(1.1) rotate(5deg);
        }
        
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        .animate-slide-in-right {
            animation: slideInRight 0.5s ease-in-out;
        }
        .animate-slide-in-left {
            animation: slideInLeft 0.5s ease-in-out;
        }
        .animate-slide-in-up {
            animation: slideInUp 0.5s ease-in-out;
        }
        .animate-pulse-slow {
            animation: pulseSlow 3s infinite ease-in-out;
        }
        .animate-float {
            animation: float 3s infinite ease-in-out;
        }
        .animate-bounce-subtle {
            animation: bounceSlight 2s infinite ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; }
        }
        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulseSlow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        @keyframes bounceSlight {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-3px); }
        }

        .stagger-item {
            opacity: 0;
        }
        
        .hover-scale {
            transition: transform 0.3s ease;
        }
        .hover-scale:hover {
            transform: scale(1.02);
        }
        
        /* Modified DataTables styling - remove top padding */
        .dataTables_wrapper {
            background-color: white;
            border-bottom-left-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
            overflow: hidden;
            padding-bottom: 1rem;
        }
        
        /* Search bar styling - reduced padding */
        .dataTables_filter {
            margin-bottom: 0;
            padding: 0.5rem 1.5rem;
        }
        
        /* More compact filter layout */
        .dataTables_filter label {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            font-size: 0.875rem;
            color: #4b5563;
            font-weight: 500;
        }
        
        /* Adjust top margin for table to remove extra space */
        table.dataTable {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }
        
        /* DataTables customization - fixed version without @apply */
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #d1d5db; /* Tailwind's border-gray-300 */
            border-radius: 0.375rem; /* Tailwind's rounded-md */
            padding: 0.5rem 0.75rem; /* Tailwind's px-3 py-2 */
            outline: none; /* Tailwind's focus:outline-none */
            transition: box-shadow 0.2s ease, border-color 0.2s ease; /* Smooth focus effect */
        }
        
        .dataTables_wrapper .dataTables_length select:focus,
        .dataTables_wrapper .dataTables_filter input:focus {
            box-shadow: 0 0 0 2px rgba(14, 165, 233, 0.5); /* Tailwind's focus:ring-primary-500 */
            border-color: transparent; /* Tailwind's focus:border-transparent */
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #0284c7; /* Tailwind's bg-primary-600 */
            color: white !important; /* Tailwind's text-white */
            border: 0 !important; /* Tailwind's border-0 */
            padding: 0.5rem 1rem; /* Tailwind's px-4 py-2 */
            border-radius: 0.375rem; /* Tailwind's rounded-md */
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button:not(.current) {
            color: #374151 !important; /* Tailwind's text-gray-700 */
            background-color: #ffffff; /* Tailwind's bg-white */
            border: 1px solid #d1d5db; /* Tailwind's border-gray-300 */
            padding: 0.5rem 1rem; /* Tailwind's px-4 py-2 */
            border-radius: 0.375rem; /* Tailwind's rounded-md */
            transition: background-color 0.3s ease; /* Tailwind's transition-colors */
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button:not(.current):hover {
            background-color: #f3f4f6 !important; /* Tailwind's hover:bg-gray-100 */
            color: #1f2937 !important; /* Tailwind's text-gray-900 */
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
            background-color: #ffffff !important;
            border-color: #d1d5db !important;
        }
        
        .dataTables_wrapper .dataTables_info {
            font-size: 0.875rem; /* Tailwind's text-sm */
            color: #4B5563; /* Tailwind's text-gray-600 */
            padding: 0.5rem 0; /* Tailwind's py-2 */
        }
        
        /* Enhanced DataTables styling - without @apply */
        .dataTables_wrapper {
            background-color: white;
            border-bottom-left-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
            overflow: hidden;
            padding-bottom: 1rem;
        }
        
        /* Search bar styling */
        .dataTables_filter {
            margin-bottom: 1rem;
            padding-right: 1.5rem;
        }
        
        .dataTables_filter label {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            font-size: 0.875rem;
            color: #4b5563;
            font-weight: 500;
        }
        
        .dataTables_filter input {
            margin-left: 0.5rem;
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            transition: all 0.2s ease-in-out;
            background-color: white;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            width: 220px;
        }
        
        .dataTables_filter input:focus {
            box-shadow: 0 0 0 2px rgba(14, 165, 233, 0.5);
            border-color: transparent;
            outline: none;
        }
        
        .dataTables_filter input::placeholder {
            color: #9ca3af;
        }
        
        /* Entries length dropdown */
        .dataTables_length {
            margin-bottom: 1rem;
            padding-left: 1.5rem;
        }
        
        .dataTables_length label {
            display: flex;
            align-items: center;
            font-size: 0.875rem;
            color: #4b5563;
            font-weight: 500;
        }
        
        .dataTables_length select {
            margin: 0 0.5rem;
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            background-color: white;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
            max-width: 100%;
        }
        
        .dataTables_length select:not(:first-of-type) {
            display: none;
        }
        
        .dataTables_length select:focus {
            box-shadow: 0 0 0 2px rgba(14, 165, 233, 0.5);
            border-color: transparent;
            outline: none;
        }
        
        /* Info text */
        .dataTables_info {
            font-size: 0.875rem;
            color: #6b7280;
            padding: 0.75rem 1.5rem;
        }
        
        /* Pagination */
        .dataTables_paginate {
            padding-right: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            margin-top: 1rem;
        }
        
        .dataTables_paginate .paginate_button {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 0.25rem;
            min-width: 2.25rem;
            height: 2.25rem;
            padding: 0 0.75rem;
            border: 1px solid #d1d5db;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 0.5rem;
            color: #374151 !important;
            background: linear-gradient(to bottom, #ffffff 0%, #f9fafb 100%) !important;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        
        .dataTables_paginate .paginate_button.current {
            background: linear-gradient(to bottom, #0ea5e9 0%, #0284c7 100%) !important;
            color: white !important;
            border-color: #0284c7 !important;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(2, 132, 199, 0.25);
        }
        
        .dataTables_paginate .paginate_button:not(.current):hover {
            background: linear-gradient(to bottom, #f0f9ff 0%, #e0f2fe 100%) !important;
            color: #0284c7 !important;
            border-color: #bae6fd !important;
        }
        
        .dataTables_paginate .paginate_button.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .dataTables_paginate .paginate_button.disabled:hover {
            background: linear-gradient(to bottom, #ffffff 0%, #f9fafb 100%) !important;
            border-color: #d1d5db !important;
            color: #374151 !important;
        }
        
        .dataTables_wrapper .dataTables_info {
            font-size: 0.875rem;
            color: #6b7280;
            padding: 0.75rem 0;
        }

        /* Add these new styles for the enhanced pagination buttons */
        .ripple-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }
        
        .ripple {
            position: absolute;
            border-radius: 50%;
            transform: translate(-50%, -50%) scale(0);
            background: rgba(14, 165, 233, 0.3);
            animation: ripple-effect 0.6s linear;
            pointer-events: none;
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
                            <i class="fas fa-history text-primary-600"></i>
                        </div>
                        Reservation History
                    </h1>
                    <p class="text-gray-500 mt-1 ml-12">View your past and upcoming lab reservations</p>
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
                            <span class="text-primary-600 font-medium">Reservation History</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Remove the Summary Statistics cards and replace with just a small spacer -->
        <div class="h-4"></div>

        <!-- History Records Card with Enhanced Header -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 mb-8 transition duration-300 hover:border-primary-200 hover-scale stagger-item overflow-hidden">
            <!-- Card Header with Filter Controls -->
            <div class="bg-gray-50 border-b border-gray-100 p-5">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-history text-primary-500 mr-2"></i>
                        Your Sit-in History
                    </h2>
                    <div class="flex flex-wrap items-center gap-3">
                        <!-- Custom Search Box -->
                        <div class="relative">
                            <input type="text" id="custom-search" placeholder="Search records..." 
                                class="pl-9 pr-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        <!-- Date Filter Dropdown - removing "All Dates" option -->
                        <div class="relative inline-block">
                            <select id="date-filter" class="appearance-none bg-white border border-gray-200 text-gray-700 py-2 px-4 pr-8 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                <option value="today">Today</option>
                                <option value="week">This Week</option>
                                <option value="month">This Month</option>
                                <option value="year">This Year</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="overflow-x-auto">
                    <table id="historyTable" class="w-full table-auto border-collapse text-sm text-gray-700">
                    <thead>
                        <tr class="bg-primary-600 text-white">
                            <th class="px-4 py-3 text-left">ID Number</th>
                            <th class="px-4 py-3 text-left">Name</th>
                            <th class="px-4 py-3 text-left">Purpose</th>
                            <th class="px-4 py-3 text-left">Laboratory</th>
                            <th class="px-4 py-3 text-left">Time-In</th>
                            <th class="px-4 py-3 text-left">Time-Out</th>
                            <th class="px-4 py-3 text-left">Date</th>
                            <th class="px-4 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php if(empty($listPerson)): ?>
                            <tr>
                                <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                                    <p>No history records found.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($listPerson as $person): ?>
                                <tr>
                                    <td class="px-4 py-3"><?php echo htmlspecialchars($person['id_number']); ?></td>
                                    <td class="px-4 py-3"><?php echo htmlspecialchars($person['firstName'] . " " . $person['lastName']); ?></td>
                                    <td class="px-4 py-3"><?php echo htmlspecialchars($person['sit_purpose']); ?></td>
                                    <td class="px-4 py-3"><?php echo htmlspecialchars($person['sit_lab']); ?></td>
                                    <td class="px-4 py-3">
                                        <?php 
                                            // Format login time to show only hour and minutes with AM/PM in caps
                                            $loginTime = strtotime($person['sit_login']);
                                            echo date('h:i A', $loginTime);
                                        ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <?php 
                                            // Format logout time to show only hour and minutes with AM/PM in caps
                                            if(!empty($person['sit_logout'])) {
                                                $logoutTime = strtotime($person['sit_logout']);
                                                echo date('h:i A', $logoutTime);
                                            } else {
                                                echo 'In Progress';
                                            }
                                        ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <?php 
                                            // Format date to show as MM/DD/YY (2 digits for each)
                                            $date = strtotime($person['sit_date']);
                                            echo date('m/d/y', $date);
                                        ?>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button type="button" class="bg-primary-600 hover:bg-primary-700 text-white py-1 px-3 rounded feedback-btn" 
                                                data-id_number="<?php echo $person['id_number']; ?>" 
                                                data-sit_lab="<?php echo $person['sit_lab']; ?>">
                                            Feedback
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Floating Quick Action Button -->
    <div class="fixed bottom-8 right-8">
        <button id="quickActionBtn" class="bg-primary-600 hover:bg-primary-700 w-14 h-14 rounded-full shadow-lg flex items-center justify-center text-white transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-primary-300" aria-label="Quick actions menu">
            <i class="fas fa-plus text-xl"></i>
        </button>
        
        <!-- Quick Actions Menu -->
        <div id="quickActionMenu" class="absolute bottom-16 right-0 bg-white rounded-lg shadow-xl border border-gray-200 w-48 py-2 opacity-0 invisible transition-all duration-300 transform translate-y-2" role="menu">
            <a href="#" id="quickExport" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-primary-50 transition-colors" role="menuitem">
                <i class="fas fa-file-excel mr-3 text-green-500 w-5"></i>
                Export to Excel
            </a>
            <a href="../index.php" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-primary-50 transition-colors" role="menuitem">
                <i class="fas fa-home mr-3 text-primary-500 w-5"></i>
                Go to Dashboard
            </a>
            <button id="quickFeedback" class="flex w-full items-center px-4 py-2 text-sm text-gray-700 hover:bg-primary-50 transition-colors" role="menuitem">
                <i class="fas fa-question-circle mr-3 text-primary-500 w-5"></i>
                Help Center
            </button>
        </div>
    </div>

    <!-- Feedback Modal -->
    <div id="feedbackModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-600 bg-opacity-75" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                <form id="feedbackForm" method="POST" action="history.php">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-primary-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-comment-dots text-primary-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Share Your Feedback
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-4">
                                        Your feedback helps us improve the sit-in experience for everyone. Please be specific and constructive.
                                    </p>
                                    <input type="hidden" id="modal_id_number" name="id_number">
                                    <input type="hidden" id="modal_sit_lab" name="sit_lab">
                                    <div>
                                        <label for="feedback_text" class="block text-sm font-medium text-gray-700 mb-1">Your Experience</label>
                                        <textarea id="feedback_text" name="feedback_text" rows="4" 
                                                class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border"
                                                placeholder="Please share your experience with this session..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" name="submit_feedback" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Submit Feedback
                        </button>
                        <button type="button" id="cancelFeedback" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="py-10"></div>
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/countup.js/2.0.8/countUp.min.js"></script>
    
    <script>
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
            
            // Initialize DataTable without pagination
            const table = $('#historyTable').DataTable({
                responsive: true,
                dom: 'rt',
                buttons: [
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Export to Excel',
                        className: 'hidden', // Hide the default button
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6] // Don't export the Action column
                        },
                        title: 'My Sit-in History'
                    }
                ],
                language: {
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "No records available",
                    infoFiltered: "(filtered from _MAX_ total entries)",
                    processing: '<div class="processing-indicator"><div class="spinner"></div><span>Processing...</span></div>'
                },
                order: [[6, 'desc'], [4, 'desc']], // Sort by date (col 6) and login time (col 4)
                pageLength: -1,
                lengthChange: false
            });
            
            // Stagger in elements with class .stagger-item
            const staggerItems = document.querySelectorAll('.stagger-item');
            staggerItems.forEach((item, index) => {
                setTimeout(() => {
                    item.style.opacity = "1";
                    item.classList.add('animate-slide-in-up');
                }, 300 + (index * 150));
            });
            
            // Animate rows
            setTimeout(() => {
                document.querySelectorAll('.entry-row').forEach((row, index) => {
                    setTimeout(() => {
                        row.classList.add('animate-slide-in-right');
                    }, 500 + (index * 100));
                });
            }, 500);
            
            // Animate counters
            const counterElements = document.querySelectorAll('.counter-animate');
            counterElements.forEach(element => {
                const targetValue = parseFloat(element.textContent);
                const countUp = new CountUp(element, 0, targetValue, 1, 2.5, {
                    useEasing: true,
                    useGrouping: true,
                    separator: ',',
                    decimal: '.'
                });
                
                if (!countUp.error) {
                    setTimeout(() => {
                        countUp.start();
                    }, 500);
                } else {
                    console.error(countUp.error);
                }
            });
            
            // Export button handler (use custom button instead of DataTables button)
            document.getElementById('quickExport').addEventListener('click', function(e) {
                e.preventDefault();
                table.button(0).trigger(); // Trigger the first button (Excel export)
                
                // Hide the menu
                document.getElementById('quickActionMenu').classList.add('invisible', 'opacity-0', 'translate-y-2');
                document.getElementById('quickActionBtn').innerHTML = '<i class="fas fa-plus text-xl"></i>';
            });
            
            // Help button handler
            document.getElementById('quickFeedback').addEventListener('click', function() {
                Swal.fire({
                    title: 'History Page Help',
                    html: `
                        <div class="text-left">
                            <p class="mb-2"><i class="fas fa-info-circle text-primary-500 mr-2"></i> This page shows your sit-in history records.</p>
                            <ul class="list-disc pl-5 space-y-1 mb-2">
                                <li>View all your previous sit-in sessions</li>
                                <li>See when you logged in and out</li>
                                <li>Provide feedback about your experience</li>
                                <li>Export your history to Excel</li>
                            </ul>
                            <p><i class="fas fa-search text-primary-500 mr-2"></i> Use the search box to filter your records.</p>
                        </div>
                    `,
                    icon: 'info',
                    confirmButtonColor: '#0284c7',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                });
            });
            
            // Feedback button handlers
            document.querySelectorAll('.feedback-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const idNum = this.getAttribute('data-id_number');
                    const lab = this.getAttribute('data-sit_lab');
                    
                    // Use SweetAlert2 for a nicer feedback modal experience
                    Swal.fire({
                        title: 'Share Your Feedback',
                        html: `
                            <div class="text-left mb-4" style="max-height: 60vh; overflow-y: auto; overflow-x: hidden;">
                                <p class="text-sm text-gray-600">Your feedback helps us improve the ${lab} laboratory experience for everyone.</p>
                                <div class="mt-4">
                                    <input type="hidden" id="swal-id-number" value="${idNum}">
                                    <input type="hidden" id="swal-sit-lab" value="${lab}">
                                    <textarea id="swal-feedback" class="w-full border rounded-md p-3 text-sm" 
                                        rows="6" placeholder="Please share your thoughts on your experience..."></textarea>
                                </div>
                            </div>
                        `,
                        showCancelButton: true,
                        confirmButtonText: 'Submit Feedback',
                        confirmButtonColor: '#0284c7',
                        cancelButtonText: 'Cancel',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Create a form and submit it programmatically
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = 'history.php';
                            form.style.display = 'none';
                            
                            const idField = document.createElement('input');
                            idField.type = 'hidden';
                            idField.name = 'id_number';
                            idField.value = idNum;
                            form.appendChild(idField);
                            
                            const labField = document.createElement('input');
                            labField.type = 'hidden';
                            labField.name = 'sit_lab';
                            labField.value = lab;
                            form.appendChild(labField);
                            
                            const feedbackField = document.createElement('input');
                            feedbackField.type = 'hidden';
                            feedbackField.name = 'feedback_text';
                            feedbackField.value = document.getElementById('swal-feedback').value;
                            form.appendChild(feedbackField);
                            
                            const submitBtn = document.createElement('input');
                            submitBtn.type = 'hidden';
                            submitBtn.name = 'submit_feedback';
                            submitBtn.value = '1';
                            form.appendChild(submitBtn);
                            
                            document.body.appendChild(form);
                            
                            // Show loading indicator
                            Swal.fire({
                                title: 'Submitting Feedback',
                                text: 'Please wait...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            
                            form.submit();
                        }
                    });
                });
            });
            
            // Quick action button
            document.getElementById('quickActionBtn').addEventListener('click', function() {
                const menu = document.getElementById('quickActionMenu');
                
                if (menu.classList.contains('invisible')) {
                    // Show menu
                    menu.classList.remove('invisible', 'opacity-0', 'translate-y-2');
                    menu.classList.add('opacity-100', 'translate-y-0');
                    this.innerHTML = '<i class="fas fa-times text-xl"></i>';
                    this.setAttribute('aria-expanded', 'true');
                } else {
                    // Hide menu
                    menu.classList.add('invisible', 'opacity-0', 'translate-y-2');
                    menu.classList.remove('opacity-100', 'translate-y-0');
                    this.innerHTML = '<i class="fas fa-plus text-xl"></i>';
                    this.setAttribute('aria-expanded', 'false');
                }
            });

            // Quick menu actions
            document.getElementById('quickExport').addEventListener('click', function(e) {
                e.preventDefault();
                table.button(0).trigger();
                
                // Hide the menu
                document.getElementById('quickActionMenu').classList.add('invisible', 'opacity-0', 'translate-y-2');
                document.getElementById('quickActionBtn').innerHTML = '<i class="fas fa-plus text-xl"></i>';
            });

            document.getElementById('quickFeedback').addEventListener('click', function() {
                // Hide the menu
                document.getElementById('quickActionMenu').classList.add('invisible', 'opacity-0', 'translate-y-2');
                document.getElementById('quickActionBtn').innerHTML = '<i class="fas fa-plus text-xl"></i>';
                
                // Show help dialog
                document.getElementById('helpBtn').click();
            });

            // Add notification
            setTimeout(() => {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                    showClass: {
                        popup: 'animate__animated animate__fadeInRight'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutRight'
                    }
                });

                Toast.fire({
                    icon: 'info',
                    title: '<i class="fas fa-info-circle text-blue-500 mr-2"></i> Welcome back!',
                    html: '<span class="text-sm">Your sit-in history has been updated</span>'
                });
            }, 3000);

            // Display current date in header
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('currentDate').textContent = now.toLocaleDateString('en-US', options);
            
            // Connect custom search box to DataTable
            document.getElementById('custom-search').addEventListener('keyup', function() {
                table.search(this.value).draw();
            });
            
            // Date filter functionality
            document.getElementById('date-filter').addEventListener('change', function() {
                let dateFilterValue = this.value;
                
                // Clear existing search and then apply filter
                $.fn.dataTable.ext.search.pop();
                
                if (dateFilterValue !== 'all') {
                    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                        const dateStr = data[6]; // Date is in column 6
                        if (!dateStr) return true;
                        
                        // Parse date (format MM/DD/YY)
                        const parts = dateStr.split('/');
                        const dateObject = new Date(2000 + parseInt(parts[2]), parseInt(parts[0]) - 1, parseInt(parts[1]));
                        const now = new Date();
                        
                        if (dateFilterValue === 'today') {
                            return dateObject.toDateString() === now.toDateString();
                        } else if (dateFilterValue === 'week') {
                            // Get start of week (Sunday)
                            const startOfWeek = new Date(now);
                            startOfWeek.setDate(now.getDate() - now.getDay());
                            startOfWeek.setHours(0, 0, 0, 0);
                            return dateObject >= startOfWeek;
                        } else if (dateFilterValue === 'month') {
                            return dateObject.getMonth() === now.getMonth() && 
                                   dateObject.getFullYear() === now.getFullYear();
                        } else if (dateFilterValue === 'year') {
                            return dateObject.getFullYear() === now.getFullYear();
                        }
                        return true;
                    });
                }
                
                table.draw();
            });
            
            // Connect Export button to DataTable export
            document.getElementById('exportBtn').addEventListener('click', function() {
                table.button(0).trigger(); // Trigger Excel export
            });
            
            // Connect Help button
            document.getElementById('helpBtn').addEventListener('click', function() {
                Swal.fire({
                    title: 'History Page Help',
                    html: `
                        <div class="text-left">
                            <p class="mb-3"><i class="fas fa-info-circle text-primary-500 mr-2"></i> <b>Your Sit-in History</b></p>
                            <ul class="list-disc pl-5 space-y-1 mb-4">
                                <li>View all your laboratory sit-in sessions</li>
                                <li>Search for specific records using the search box</li>
                                <li>Filter by date range using the dropdown</li>
                                <li>Export your history to Excel for your records</li>
                                <li>Provide feedback for any of your sessions</li>
                            </ul>
                            <p class="mb-2"><i class="fas fa-search text-primary-500 mr-2"></i> <b>Search & Filtering:</b></p>
                            <p class="text-sm mb-3">Use the search box to find records by any keyword such as laboratory name, purpose, or date.</p>
                            <p class="mb-2"><i class="fas fa-filter text-primary-500 mr-2"></i> <b>Date Filtering:</b></p>
                            <p class="text-sm">Select from the dropdown to view records from today, this week, this month, or this year.</p>
                        </div>
                    `,
                    icon: 'info',
                    confirmButtonColor: '#0284c7',
                    confirmButtonText: 'Got it!',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                });
            });
        });
        
        // Show success alerts with SweetAlert2
        <?php if(!empty($successMessage)): ?>
        Swal.fire({
            icon: 'success',
            title: 'Feedback Submitted!',
            text: '<?php echo $successMessage; ?>',
            confirmButtonColor: '#0284c7',
            timer: 3000,
            timerProgressBar: true,
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            },
            didOpen: () => {
                // Add confetti effect for successful feedback submission
                if ('<?php echo $successMessage; ?>'.includes('submitted successfully')) {
                    const confettiColors = ['#0ea5e9', '#38bdf8', '#7dd3fc', '#bae6fd', '#ffffff'];
                    const confettiAnimation = setInterval(() => {
                        const confetti = document.createElement('div');
                        confetti.style.width = (Math.random() * 10 + 5) + 'px';
                        confetti.style.height = (Math.random() * 10 + 5) + 'px';
                        confetti.style.backgroundColor = confettiColors[Math.floor(Math.random() * confettiColors.length)];
                        confetti.style.position = 'absolute';
                        confetti.style.zIndex = '9999';
                        confetti.style.top = '-10px';
                        confetti.style.left = Math.random() * document.body.clientWidth + 'px';
                        confetti.style.borderRadius = '50%';
                        confetti.style.opacity = Math.random() + 0.5;
                        
                        document.body.appendChild(confetti);
                        
                        const animation = confetti.animate(
                            [
                                { transform: 'translate3d(0,0,0)', opacity: 1 },
                                { transform: `translate3d(${Math.random() * 100 - 50}px, ${Math.random() * 600 + 400}px, 0)`, opacity: 0 }
                            ],
                            {
                                duration: Math.random() * 1500 + 1500,
                                easing: 'cubic-bezier(0,0.9,0.57,1)',
                                delay: Math.random() * 100
                            }
                        );
                        
                        animation.onfinish = () => confetti.remove();
                    }, 100);
                    
                    setTimeout(() => clearInterval(confettiAnimation), 2000);
                }
            }
        });
        <?php endif; ?>
    </script>
</body>
</html>