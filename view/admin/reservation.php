<?php
// Including the backend file that contains necessary functions
include '../../includes/navbar_admin.php';


$listPerson = [];
$data = []; // Initialize $data as empty array
$lab_final = isset($_POST['lab']) ? 'lab_' . $_POST['lab'] : 'lab_524'; // Default lab if not set

// Include necessary files and get data when lab is selected
if (isset($_POST['labSubmit']) && isset($_POST['lab'])) {
    $lab = $_POST['lab'];
    $lab_final = 'lab_' . $lab;
    $data = retrieve_pc($lab_final);
} else {
    // Default data for first load
    $data = retrieve_pc($lab_final);
}

// Get search query if exists
$search_query = isset($_POST['search_query']) ? $_POST['search_query'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CCS | Reservation Management</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Date Range Picker -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script>
        // Include Tailwind config to match the sit-in page
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
                            500: '#10b981',
                            600: '#059669'
                        }
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
                    },
                    animation: {
                        fadeIn: 'fadeIn 0.5s ease-out',
                        slideUp: 'slideUp 0.5s ease-out',
                        pulse: 'pulse 2s infinite',
                    },
                }
            }
        }
    </script>
    <style>
        /* Custom styles to complement Tailwind */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
        
        .animate-staggered {
            opacity: 0;
            animation: slideUp 0.5s ease-out forwards;
        }
        
        @keyframes slideUp {
            0% { transform: translateY(20px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }
        
        .scrollable-area::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        .scrollable-area::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .scrollable-area::-webkit-scrollbar-thumb {
            background: #0ea5e9;
            border-radius: 10px;
        }
        
        .scrollable-area::-webkit-scrollbar-thumb:hover {
            background: #0284c7;
        }
        
        /* Card styles */
        .card {
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-radius: 0.75rem;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .card-header {
            background: linear-gradient(to right, #0369a1, #0284c7);
            color: white;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #0369a1;
        }
        
        .status-badge {
            transition: all 0.3s ease;
        }
        
        .status-badge:hover {
            transform: scale(1.05);
        }
        
        .grid-view {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
            gap: 12px;
        }
        
        .list-view {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .pc-item-grid {
            aspect-ratio: 1/1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .pc-item-grid:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .pc-item-list {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
            transition: all 0.2s ease;
        }
        
        /* Form elements */
        select, input, button {
            border-radius: 0.5rem !important;
        }
        
        button {
            transition: all 0.3s ease;
        }
        
        button:hover {
            transform: translateY(-2px);
        }
        
        /* Grid items */
        .reservation-item, .log-item {
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .reservation-item:hover, .log-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        /* Stat cards */
        .stat-card {
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
            padding: 1rem;
            display: flex;
            align-items: center;
            background-color: white;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans">
    <!-- Include navbar -->
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                        <div class="bg-primary-100 p-2 rounded-lg mr-3 shadow-sm">
                            <i class="fas fa-calendar-check text-primary-600"></i>
                        </div>
                        Reservation Management
                    </h1>
                    <p class="text-gray-500 mt-1 ml-12">Manage laboratory reservations and computer availability</p>
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
                            <span class="text-primary-600 font-medium">Reservation Management</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            <!-- Reservation Request Card -->
            <div class="md:col-span-6 animate-staggered" style="animation-delay: 100ms;">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="border-b border-gray-100 px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-clipboard-list text-primary-500 mr-2"></i>
                            Reservation Requests
                        </h2>
                    </div>
                    <div class="p-6">
                        <!-- Search and Filter Controls -->
                        <div class="mb-4">
                            <form action="Reservation.php" method="POST" class="flex gap-2">
                                <div class="relative flex-grow">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="fas fa-search text-gray-400"></i>
                                    </div>
                                    <input type="text" name="search_query" placeholder="Search ID, lab or date..." class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all shadow-sm" value="<?php echo $search_query; ?>">
                                </div>
                                <button type="submit" name="search_submit" class="px-4 py-2.5 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white rounded-lg transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-opacity-50">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                        
                        <!-- Filter tabs -->
                        <div class="mb-4 border-b border-gray-200">
                            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="reservation-tabs" role="tablist">
                                <li class="mr-2" role="presentation">
                                    <button class="inline-block p-3 rounded-t-lg border-b-2 border-primary-500 text-primary-600 font-bold" id="all-tab" data-status="all" type="button" role="tab">
                                        <i class="fas fa-list-ul mr-2"></i> All Reservations
                                    </button>
                                </li>
                                <li class="mr-2" role="presentation">
                                    <button class="inline-block p-3 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300" id="pending-tab" data-status="pending" type="button" role="tab">
                                        <i class="fas fa-clock mr-2"></i> Pending Only
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <div class="max-h-96 overflow-y-auto scrollable-area pr-2">
                            <?php 
                            $reservations = retrieve_reservation();
                            if (empty($reservations)) : 
                            ?>
                                <div class="py-12 flex flex-col items-center justify-center text-center bg-gray-50 rounded-lg border border-gray-200 border-dashed">
                                    <div class="bg-gray-100 p-5 rounded-full mb-4 shadow-inner">
                                        <i class="fas fa-calendar-xmark text-gray-400 text-4xl"></i>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-700">No Reservation Requests</h3>
                                    <p class="text-gray-500 mt-2 max-w-xs">There are no pending reservation requests at the moment.</p>
                                </div>
                            <?php else : ?>
                                <?php foreach ($reservations as $index => $row) : ?>
                                    <div class="p-4 border border-gray-200 rounded-lg mb-4 table-row-animate hover:bg-primary-50 transition-all reservation-item shadow-sm" style="animation-delay: <?php echo $index * 50; ?>ms;" data-status="<?php echo strtolower($row['status']); ?>">
                                        <div class="flex justify-between items-start mb-3">
                                            <div class="font-semibold flex items-center">
                                                <div class="bg-primary-100 p-2 rounded-full mr-2">
                                                    <i class="fas fa-user-circle text-primary-600"></i>
                                                </div>
                                                <?php echo $row['id_number'] ?>
                                            </div>
                                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-800 status-badge">
                                                <i class="fas fa-clock mr-1"></i> Pending
                                            </span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3 mb-3">
                                            <div class="bg-gray-50 p-2 rounded">
                                                <p class="text-xs text-gray-500">Date</p>
                                                <p class="font-semibold"><?php echo $row['reservation_date'] ?></p>
                                            </div>
                                            <div class="bg-gray-50 p-2 rounded">
                                                <p class="text-xs text-gray-500">Time</p>
                                                <p class="font-semibold"><?php echo $row['reservation_time'] ?></p>
                                            </div>
                                            <div class="bg-gray-50 p-2 rounded">
                                                <p class="text-xs text-gray-500">Laboratory</p>
                                                <p class="font-semibold"><?php echo $row['lab'] ?></p>
                                            </div>
                                            <div class="bg-gray-50 p-2 rounded">
                                                <p class="text-xs text-gray-500">PC Number</p>
                                                <p class="font-semibold"><?php echo $row['pc_number'] ?></p>
                                            </div>
                                        </div>
                                        <div class="mb-3 bg-gray-50 p-2 rounded">
                                            <p class="text-xs text-gray-500">Purpose</p>
                                            <p class="text-sm"><?php echo $row['purpose'] ?></p>
                                        </div>
                                        <div class="flex gap-2">
                                            <form action="Reservation.php" method="POST" class="flex gap-2 w-full">
                                                <input name="reservation_id" value="<?php echo $row['reservation_id'] ?>" type="hidden">
                                                <input name="pc_number" value="<?php echo $row['pc_number'] ?>" type="hidden">
                                                <input name="lab" value="<?php echo $row['lab'] ?>" type="hidden">
                                                <input name="id_number" value="<?php echo $row['id_number'] ?>" type="hidden">
                                                <button type="button" onclick="confirmAccept(this.form)" class="w-1/2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                                                    <i class="fas fa-check mr-1"></i> Accept
                                                </button>
                                                <button type="button" onclick="confirmDeny(this.form)" class="w-1/2 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                                                    <i class="fas fa-times mr-1"></i> Deny
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Logs Card -->
            <div class="md:col-span-6 animate-staggered" style="animation-delay: 200ms;">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="border-b border-gray-100 px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-history text-primary-500 mr-2"></i>
                            Activity Logs
                        </h2>
                    </div>
                    <div class="p-6">
                        <!-- Date Filter -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Date</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="fas fa-calendar-alt text-gray-400"></i>
                                </div>
                                <input type="text" id="date-range" class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all shadow-sm" placeholder="Select date range">
                            </div>
                        </div>
                        
                        <div class="max-h-96 overflow-y-auto scrollable-area pr-2" id="logs-container">
                            <?php foreach (retrieve_reservation_logs() as $index => $row) : ?>
                                <div class="p-4 border-l-4 <?php echo ($row['status'] == 'Approve') ? 'border-l-green-500' : 'border-l-red-500'; ?> bg-gray-50 rounded-lg mb-4 hover:shadow-md transition-all table-row-animate log-item shadow-sm" style="animation-delay: <?php echo $index * 50; ?>ms;" data-date="<?php echo $row['reservation_date']; ?>">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="font-semibold flex items-center">
                                            <div class="p-2 rounded-full <?php echo ($row['status'] == 'Approve') ? 'bg-green-100' : 'bg-red-100'; ?> mr-2">
                                                <i class="fas fa-user-circle <?php echo ($row['status'] == 'Approve') ? 'text-green-600' : 'text-red-600'; ?>"></i>
                                            </div>
                                            <?php echo $row['id_number'] ?>
                                        </div>
                                        <div class="px-3 py-1 text-xs font-medium rounded-full <?php echo ($row['status'] == 'Approve') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?> status-badge">
                                            <i class="<?php echo ($row['status'] == 'Approve') ? 'fas fa-check-circle' : 'fas fa-times-circle'; ?> mr-1"></i>
                                            <?php echo $row['status'] ?>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-3 mb-3">
                                        <div class="bg-white p-2 rounded shadow-sm">
                                            <p class="text-xs text-gray-500">Date</p>
                                            <p class="font-medium"><?php echo $row['reservation_date'] ?></p>
                                        </div>
                                        <div class="bg-white p-2 rounded shadow-sm">
                                            <p class="text-xs text-gray-500">Time</p>
                                            <p class="font-medium"><?php echo $row['reservation_time'] ?></p>
                                        </div>
                                        <div class="bg-white p-2 rounded shadow-sm">
                                            <p class="text-xs text-gray-500">Laboratory</p>
                                            <p class="font-medium"><?php echo $row['lab'] ?></p>
                                        </div>
                                        <div class="bg-white p-2 rounded shadow-sm">
                                            <p class="text-xs text-gray-500">PC Number</p>
                                            <p class="font-medium"><?php echo $row['pc_number'] ?></p>
                                        </div>
                                    </div>
                                    <div class="bg-white p-2 rounded shadow-sm">
                                        <p class="text-xs text-gray-500">Purpose</p>
                                        <p class="text-sm"><?php echo $row['purpose'] ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Computer Control Card in a new row below -->
        <div class="mt-6 animate-staggered" style="animation-delay: 300ms;">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-desktop text-primary-500 mr-2"></i>
                        Computer Control
                    </h2>
                </div>
                <div class="p-6">
                    <form action="Reservation.php" method="POST" id="lab-form">
                        <div class="mb-4">
                            <label for="lab" class="block text-sm font-medium text-gray-700 mb-2">Laboratory</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-building text-gray-400"></i>
                                </div>
                                <select name="lab" id="lab" onchange="this.form.submit()" class="pl-10 w-full border border-gray-300 rounded-lg py-2.5 px-4 text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all bg-white shadow-sm">
                                    <option value="517" <?php echo ($lab_final == 'lab_517') ? 'selected' : ''; ?>>Lab 517</option>
                                    <option value="524" <?php echo ($lab_final == 'lab_524') ? 'selected' : ''; ?>>Lab 524</option>
                                    <option value="526" <?php echo ($lab_final == 'lab_526') ? 'selected' : ''; ?>>Lab 526</option>
                                    <option value="528" <?php echo ($lab_final == 'lab_528') ? 'selected' : ''; ?>>Lab 528</option>
                                    <option value="530" <?php echo ($lab_final == 'lab_530') ? 'selected' : ''; ?>>Lab 530</option>
                                    <option value="542" <?php echo ($lab_final == 'lab_542') ? 'selected' : ''; ?>>Lab 542</option>
                                </select>
                                <input type="hidden" name="labSubmit" value="true">
                            </div>
                        </div>
                    </form>

                    <div class="mt-6">
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-sm font-medium text-gray-700">Computer Status</label>
                            <div class="text-xs text-gray-500">
                                <span class="inline-block w-2 h-2 rounded-full bg-green-500 mr-1"></span> Available
                                <span class="inline-block w-2 h-2 rounded-full bg-red-500 ml-2 mr-1"></span> Used
                            </div>
                        </div>
                        
                        <form action="Reservation.php" method="POST">
                            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 xl:grid-cols-10 gap-4 max-h-96 overflow-y-auto scrollable-area pr-2">
                                <?php foreach ($data as $index => $row) : ?>
                                    <div class="pc-item-grid <?php echo $row['lab2'] == '1' ? 'bg-green-50 hover:bg-green-100 cursor-pointer' : 'bg-red-50 hover:bg-red-100 cursor-pointer' ?> table-row-animate shadow-sm" style="animation-delay: <?php echo $index * 50; ?>ms;">
                                        <input type="hidden" name="filter_lab" value="<?php echo $lab_final ?>">
                                        <div class="flex flex-col items-center justify-center p-3">
                                            <input type="checkbox" id="PC<?php echo $row['pc_id']; ?>" name="pc[]" value="<?php echo $row['pc_id']; ?>" class="w-4 h-4 text-primary-600 bg-gray-100 rounded border-gray-300 focus:ring-primary-500 focus:ring-2">
                                            <label for="PC<?php echo $row['pc_id']; ?>" class="mt-2 text-center cursor-pointer">
                                                <i class="fas fa-desktop <?php echo $row['lab2'] == '1' ? 'text-green-600' : 'text-red-600'; ?> text-xl"></i>
                                                <p class="text-sm font-medium mt-1">PC <?php echo $row['pc_id']; ?></p>
                                                <span class="text-xs <?php echo $row['lab2'] == '1' ? 'text-green-600' : 'text-red-600'; ?> font-medium">
                                                    <?php echo $row['lab2'] == '1' ? 'Available' : 'Used'; ?>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 mt-6">
                                <button type="button" onclick="confirmSetAvailable(this.form)" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                                    <i class="fas fa-check-circle mr-2"></i> Set Available
                                </button>
                                <button type="button" onclick="confirmSetUnavailable(this.form)" class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                                    <i class="fas fa-times-circle mr-2"></i> Set Used
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="py-8"></div>

    <!-- JavaScript for Interactions -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Make body visible with fade-in effect
            document.body.classList.add('transition-opacity', 'duration-500');
            setTimeout(() => document.body.classList.add('opacity-100'), 100);
            
            // Lab selection change event
            document.getElementById('lab').addEventListener('change', function() {
                showLoading('Loading laboratory data...');
            });
            
            // Refresh button functionality
            document.getElementById('refreshButton').addEventListener('click', function() {
                this.classList.add('animate-pulse');
                
                // Show loading indicator
                Swal.fire({
                    title: 'Refreshing...',
                    html: 'Updating reservation data',
                    timer: 1000,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    willClose: () => {
                        this.classList.remove('animate-pulse');
                        // Reload the page to fetch fresh data
                        window.location.reload();
                    }
                });
            });
            
            // Initialize date range picker
            $('#date-range').daterangepicker({
                opens: 'left',
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear',
                    format: 'YYYY-MM-DD'
                }
            });
            
            $('#date-range').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format('YYYY-MM-DD'));
                filterLogsByDate(picker.startDate.format('YYYY-MM-DD'), picker.endDate.format('YYYY-MM-DD'));
            });
            
            $('#date-range').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                $('.log-item').show();
            });
            
            // Filter logs by date range
            function filterLogsByDate(startDate, endDate) {
                $('.log-item').each(function() {
                    const logDate = $(this).data('date');
                    const date = moment(logDate, 'YYYY-MM-DD');
                    
                    if (date.isBetween(startDate, endDate, null, '[]')) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }
            
            // Initialize tab filtering with animation
            $('#reservation-tabs button').click(function() {
                $('#reservation-tabs button').removeClass('border-primary-500 text-primary-600 font-bold').addClass('border-transparent hover:text-gray-600 hover:border-gray-300');
                $(this).removeClass('border-transparent hover:text-gray-600 hover:border-gray-300').addClass('border-primary-500 text-primary-600 font-bold');
                
                const status = $(this).data('status');
                if (status === 'all') {
                    $('.reservation-item').show();
                } else {
                    $('.reservation-item').hide();
                    $(`.reservation-item[data-status="${status}"]`).show();
                }
            });
            
            // Count available and used PCs
            function updatePCCounts() {
                const availableCount = $('.pc-item-grid:contains("Available")').length;
                const usedCount = $('.pc-item-grid:contains("Used")').length;
                
                $('#available-count').text(availableCount);
                $('#used-count').text(usedCount);
            }
            
            updatePCCounts();
            
            // Select/Deselect all checkboxes with animation
            $('#select-all').click(function() {
                $('input[name="pc[]"]').prop('checked', true);
                // Add a quick pulse animation to show selection
                $('.pc-item-grid').addClass('animate-pulse');
                setTimeout(() => {
                    $('.pc-item-grid').removeClass('animate-pulse');
                }, 500);
            });
            
            $('#deselect-all').click(function() {
                $('input[name="pc[]"]').prop('checked', false);
                // Add a quick animation to show deselection
                $('.pc-item-grid').addClass('animate-pulse');
                setTimeout(() => {
                    $('.pc-item-grid').removeClass('animate-pulse');
                }, 500);
            });
            
            // Animate table rows with staggered effect
            function animateTableRows() {
                $('.animate-staggered').each(function(index) {
                    $(this).css({
                        'animation-delay': `${index * 0.1}s`,
                        'animation': 'slideUp 0.6s forwards ease-out'
                    });
                });
            }
            
            // Initialize animations
            animateTableRows();

            // Add hover effects to items
            $('.pc-item-grid').hover(
                function() { $(this).addClass('shadow-md').css('transform', 'translateY(-5px)'); },
                function() { $(this).removeClass('shadow-md').css('transform', ''); }
            );
            
            $('.reservation-item, .log-item').hover(
                function() { $(this).addClass('shadow-md'); },
                function() { $(this).removeClass('shadow-md'); }
            );
        });
        
        // Function to show loading state with SweetAlert2
        function showLoading(message) {
            Swal.fire({
                title: 'Processing',
                html: message,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
        
        // Function to confirm accept with SweetAlert2
        function confirmAccept(form) {
            Swal.fire({
                title: 'Accept Reservation?',
                text: 'This will approve the reservation request and notify the user.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0ea5e9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, accept',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading('Processing reservation approval...');
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'accept_reservation';
                    input.value = 'true';
                    form.appendChild(input);
                    form.submit();
                    
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Reservation Accepted',
                        showConfirmButton: false,
                        timer: 1500,
                        toast: true
                    });
                }
            });
        }
        
        // Function to confirm deny with SweetAlert2
        function confirmDeny(form) {
            Swal.fire({
                title: 'Deny Reservation?',
                text: 'This will reject the reservation request and notify the user.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, deny',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading('Processing reservation denial...');
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'deny_reservation';
                    input.value = 'true';
                    form.appendChild(input);
                    form.submit();
                    
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Reservation Denied',
                        showConfirmButton: false,
                        timer: 1500,
                        toast: true
                    });
                }
            });
        }

        // Function to confirm set available with SweetAlert2
        function confirmSetAvailable(form) {
            Swal.fire({
                title: 'Set Available?',
                text: 'This will mark the selected PCs as available.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0ea5e9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, set available',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading('Updating computer status...');
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'submitAvail';
                    input.value = 'true';
                    form.appendChild(input);
                    form.submit();
                    
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'PCs Set to Available',
                        showConfirmButton: false,
                        timer: 1500,
                        toast: true
                    });
                }
            });
        }

        // Function to confirm set unavailable with SweetAlert2
        function confirmSetUnavailable(form) {
            Swal.fire({
                title: 'Set Used?',
                text: 'This will mark the selected PCs as used.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, set used',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading('Updating computer status...');
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'submitDecline';
                    input.value = 'true';
                    form.appendChild(input);
                    form.submit();
                    
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'PCs Set to Used',
                        showConfirmButton: false,
                        timer: 1500,
                        toast: true
                    });
                }
            });
        }
    </script>
</body>
</html>