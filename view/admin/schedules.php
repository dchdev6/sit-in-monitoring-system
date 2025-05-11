<?php
// Buffer output to prevent "headers already sent" errors
ob_start();

include '../../includes/navbar_admin.php';

// Process form submissions
if (isset($_POST['add_schedule'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $lab = $_POST['lab'];
    $resource = $_POST['resource'];
    $posted_by = $_SESSION['admin_id_number']; // Admin ID
    
    if (add_schedule($title, $description, $start_date, $end_date, $start_time, $end_time, $lab, $resource, $posted_by)) {
        $_SESSION['success_message'] = "Schedule added successfully!";
    } else {
        $_SESSION['error_message'] = "Failed to add schedule. Please try again.";
    }
    
    // Redirect to prevent form resubmission
    header("Location: schedules.php");
    exit();
}

// Process schedule update
if (isset($_POST['update_schedule'])) {
    $id = $_POST['schedule_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $lab = $_POST['lab'];
    $resource = $_POST['resource'];
    
    if (update_schedule($id, $title, $description, $start_date, $end_date, $start_time, $end_time, $lab, $resource)) {
        $_SESSION['success_message'] = "Schedule updated successfully!";
    } else {
        $_SESSION['error_message'] = "Failed to update schedule. Please try again.";
    }
    
    // Redirect to prevent form resubmission
    header("Location: schedules.php");
    exit();
}

// Process schedule deletion
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    
    if (delete_schedule($id)) {
        $_SESSION['success_message'] = "Schedule deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Failed to delete schedule. Please try again.";
    }
    
    // Redirect to prevent resubmission
    header("Location: schedules.php");
    exit();
}

// Load schedule for editing if ID is provided
$schedule_to_edit = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $schedule_to_edit = get_schedule($_GET['id']);
}

// Fetch all schedules
$schedules = get_all_schedules();

// Check for success/error messages
$successMessage = '';
$errorMessage = '';

if (isset($_SESSION['success_message'])) {
    $successMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    $errorMessage = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Schedules Management</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Datepicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    
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
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #f9fafb;
        }
        .card {
            transition: all 0.2s ease;
            border-radius: 0.75rem;
        }
        .card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            border-bottom: 1px solid #f3f4f6;
            padding: 1rem 1.5rem;
        }
        .card-body {
            padding: 1.5rem;
        }
        .animate-spin {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
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
            /* Override any potential DataTables internal hover styles */
            background: #f3f4f6 !important;
            background-image: none !important;
            box-shadow: none !important;
        }
        
        /* Additional specificity to override DataTables defaults */
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #f3f4f6 !important;
            color: #111827 !important;
            border-color: #e5e7eb !important;
            background: #f3f4f6 !important;
            background-image: none !important;
        }
        
        /* Shimmer effect */
        .shimmer {
            background: linear-gradient(to right, #f6f7f8 0%, #edeef1 20%, #f6f7f8 40%, #f6f7f8 100%);
            background-size: 1000px 100%;
            animation: shimmer 2s infinite linear;
        }
        
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }
    </style>
</head>

<body class="font-sans text-gray-800 transition-opacity duration-300 opacity-0">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                        <div class="bg-primary-100 p-2 rounded-lg mr-3 shadow-sm">
                            <i class="fas fa-calendar-alt text-primary-600"></i>
                        </div>
                        Lab Schedules Management
                    </h1>
                    <p class="text-gray-500 mt-1 ml-12">Manage and publish lab schedules</p>
                </div>
                <div class="flex space-x-3 mt-4 md:mt-0">
                    <button id="refreshButton" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-2.5 px-4 rounded-lg transition duration-300 flex items-center shadow-sm">
                        <i class="fas fa-sync-alt mr-2 text-gray-500"></i>
                        Refresh
                    </button>
                    <button id="newScheduleBtn" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-300 flex items-center shadow-sm">
                        <i class="fas fa-plus mr-2"></i>
                        New Schedule
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
                            <span class="text-primary-600 font-medium">Lab Schedules</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <?php if ($schedule_to_edit): ?>
        <!-- Edit Schedule Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 card mb-8 fade-in">
            <div class="card-header flex items-center">
                <i class="fas fa-edit text-primary-600 mr-2"></i>
                <h2 class="font-semibold text-gray-800">Edit Schedule</h2>
            </div>
            <div class="card-body">
                <form action="schedules.php" method="POST" class="space-y-4">
                    <input type="hidden" name="schedule_id" value="<?php echo $schedule_to_edit['id']; ?>">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                            <input type="text" id="title" name="title" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" required value="<?php echo htmlspecialchars($schedule_to_edit['title']); ?>">
                        </div>
                        
                        <div>
                            <label for="lab" class="block text-sm font-medium text-gray-700 mb-1">Laboratory</label>
                            <select id="lab" name="lab" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                                <option value="">Select Laboratory</option>
                                <option value="524" <?php echo $schedule_to_edit['lab'] == '524' ? 'selected' : ''; ?>>524</option>
                                <option value="526" <?php echo $schedule_to_edit['lab'] == '526' ? 'selected' : ''; ?>>526</option>
                                <option value="528" <?php echo $schedule_to_edit['lab'] == '528' ? 'selected' : ''; ?>>528</option>
                                <option value="530" <?php echo $schedule_to_edit['lab'] == '530' ? 'selected' : ''; ?>>530</option>
                                <option value="542" <?php echo $schedule_to_edit['lab'] == '542' ? 'selected' : ''; ?>>542</option>
                                <option value="517" <?php echo $schedule_to_edit['lab'] == '517' ? 'selected' : ''; ?>>517</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="resource" class="block text-sm font-medium text-gray-700 mb-1">Resource</label>
                            <select id="resource" name="resource" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                                <option value="">Select Resource</option>
                                <option value="C Programming" <?php echo isset($schedule_to_edit['resource']) && $schedule_to_edit['resource'] == 'C Programming' ? 'selected' : ''; ?>>C Programming</option>
                                <option value="C#" <?php echo isset($schedule_to_edit['resource']) && $schedule_to_edit['resource'] == 'C#' ? 'selected' : ''; ?>>C#</option>
                                <option value="Java" <?php echo isset($schedule_to_edit['resource']) && $schedule_to_edit['resource'] == 'Java' ? 'selected' : ''; ?>>Java</option>
                                <option value="PHP" <?php echo isset($schedule_to_edit['resource']) && $schedule_to_edit['resource'] == 'PHP' ? 'selected' : ''; ?>>PHP</option>
                                <option value="Database" <?php echo isset($schedule_to_edit['resource']) && $schedule_to_edit['resource'] == 'Database' ? 'selected' : ''; ?>>Database</option>
                                <option value="Digital Logic & Design" <?php echo isset($schedule_to_edit['resource']) && $schedule_to_edit['resource'] == 'Digital Logic & Design' ? 'selected' : ''; ?>>Digital Logic & Design</option>
                                <option value="Embedded Systems & IoT" <?php echo isset($schedule_to_edit['resource']) && $schedule_to_edit['resource'] == 'Embedded Systems & IoT' ? 'selected' : ''; ?>>Embedded Systems & IoT</option>
                                <option value="Python Programming" <?php echo isset($schedule_to_edit['resource']) && $schedule_to_edit['resource'] == 'Python Programming' ? 'selected' : ''; ?>>Python Programming</option>
                                <option value="Systems Integration & Architecture" <?php echo isset($schedule_to_edit['resource']) && $schedule_to_edit['resource'] == 'Systems Integration & Architecture' ? 'selected' : ''; ?>>Systems Integration & Architecture</option>
                                <option value="Computer Application" <?php echo isset($schedule_to_edit['resource']) && $schedule_to_edit['resource'] == 'Computer Application' ? 'selected' : ''; ?>>Computer Application</option>
                                <option value="Web Design & Development" <?php echo isset($schedule_to_edit['resource']) && $schedule_to_edit['resource'] == 'Web Design & Development' ? 'selected' : ''; ?>>Web Design & Development</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="description" name="description" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"><?php echo htmlspecialchars($schedule_to_edit['description']); ?></textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" id="start_date" name="start_date" class="datepicker w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" required value="<?php echo $schedule_to_edit['start_date']; ?>">
                        </div>
                        
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" id="end_date" name="end_date" class="datepicker w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" required value="<?php echo $schedule_to_edit['end_date']; ?>">
                        </div>
                        
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Start Time <span class="text-xs text-gray-500">(e.g. 08:00 AM)</span></label>
                            <input type="time" id="start_time" name="start_time" class="timepicker w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" required value="<?php echo $schedule_to_edit['start_time']; ?>">
                        </div>
                        
                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">End Time <span class="text-xs text-gray-500">(e.g. 10:00 AM)</span></label>
                            <input type="time" id="end_time" name="end_time" class="timepicker w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" required value="<?php echo $schedule_to_edit['end_time']; ?>">
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-4">
                        <a href="schedules.php" class="bg-white border border-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-50 transition duration-200">Cancel</a>
                        <button type="submit" name="update_schedule" class="bg-primary-600 hover:bg-primary-700 text-white py-2 px-4 rounded-md transition duration-200">
                            <i class="fas fa-save mr-2"></i> Update Schedule
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php else: ?>
        <!-- Add Schedule Form (Initially Hidden) -->
        <div id="scheduleForm" class="bg-white rounded-xl shadow-sm border border-gray-100 card mb-8 hidden fade-in">
            <div class="card-header flex items-center">
                <i class="fas fa-plus-circle text-primary-600 mr-2"></i>
                <h2 class="font-semibold text-gray-800">Add New Schedule</h2>
            </div>
            <div class="card-body">
                <form action="schedules.php" method="POST" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                            <input type="text" id="title" name="title" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" required placeholder="e.g., Programming Lab Session">
                        </div>
                        
                        <div>
                            <label for="lab" class="block text-sm font-medium text-gray-700 mb-1">Laboratory</label>
                            <select id="lab" name="lab" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                                <option value="">Select Laboratory</option>
                                <option value="524">524</option>
                                <option value="526">526</option>
                                <option value="528">528</option>
                                <option value="530">530</option>
                                <option value="542">542</option>
                                <option value="517">517</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="resource" class="block text-sm font-medium text-gray-700 mb-1">Resource</label>
                            <select id="resource" name="resource" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                                <option value="">Select Resource</option>
                                <option value="C Programming">C Programming</option>
                                <option value="C#">C#</option>
                                <option value="Java">Java</option>
                                <option value="PHP">PHP</option>
                                <option value="Database">Database</option>
                                <option value="Digital Logic & Design">Digital Logic & Design</option>
                                <option value="Embedded Systems & IoT">Embedded Systems & IoT</option>
                                <option value="Python Programming">Python Programming</option>
                                <option value="Systems Integration & Architecture">Systems Integration & Architecture</option>
                                <option value="Computer Application">Computer Application</option>
                                <option value="Web Design & Development">Web Design & Development</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="description" name="description" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Provide details about this lab schedule..."></textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" id="start_date" name="start_date" class="datepicker w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                        </div>
                        
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" id="end_date" name="end_date" class="datepicker w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                        </div>
                        
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Start Time <span class="text-xs text-gray-500">(e.g. 08:00 AM)</span></label>
                            <input type="time" id="start_time" name="start_time" class="timepicker w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                        </div>
                        
                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">End Time <span class="text-xs text-gray-500">(e.g. 10:00 AM)</span></label>
                            <input type="time" id="end_time" name="end_time" class="timepicker w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" id="cancelSchedule" class="bg-white border border-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-50 transition duration-200">Cancel</button>
                        <button type="submit" name="add_schedule" class="bg-primary-600 hover:bg-primary-700 text-white py-2 px-4 rounded-md transition duration-200">
                            <i class="fas fa-calendar-plus mr-2"></i> Add Schedule
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Schedules Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 card">
            <div class="card-header flex items-center">
                <i class="fas fa-list text-primary-600 mr-2"></i>
                <h2 class="font-semibold text-gray-800">All Schedules</h2>
            </div>
            <div class="card-body overflow-x-auto">
                <?php if (empty($schedules)): ?>
                <div class="text-center py-6 text-gray-500">
                    <div class="bg-gray-50 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-calendar-xmark text-gray-300 text-3xl"></i>
                    </div>
                    <h3 class="font-medium text-gray-800 mb-1">No Schedules Found</h3>
                    <p class="text-sm">Start by adding your first lab schedule.</p>
                </div>
                <?php else: ?>
                <table id="schedulesTable" class="min-w-full divide-y divide-gray-200 table-auto">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lab</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resource</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Range</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posted By</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($schedules as $schedule): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900"><?php echo htmlspecialchars($schedule['title']); ?></div>
                                <div class="text-xs text-gray-500 mt-1 max-w-xs truncate"><?php echo htmlspecialchars($schedule['description']); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Lab <?php echo htmlspecialchars($schedule['lab']); ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded"><?php echo htmlspecialchars($schedule['resource']); ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php 
                                $start_date = date('M d, Y', strtotime($schedule['start_date']));
                                $end_date = date('M d, Y', strtotime($schedule['end_date']));
                                
                                if ($start_date == $end_date) {
                                    echo $start_date;
                                } else {
                                    echo $start_date . ' - ' . $end_date;
                                }
                                ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php 
                                echo date('h:i A', strtotime($schedule['start_time'])) . ' - ' . 
                                     date('h:i A', strtotime($schedule['end_time'])); 
                                ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                <div class="flex items-center">
                                    <div class="bg-primary-100 p-1 rounded-full mr-2">
                                        <i class="fas fa-user text-primary-600 text-xs"></i>
                                    </div>
                                    <?php echo htmlspecialchars($schedule['posted_by']); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="schedules.php?action=edit&id=<?php echo $schedule['id']; ?>" class="text-primary-600 hover:text-primary-800 mr-3">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="#" class="text-red-600 hover:text-red-800" onclick="confirmDelete(<?php echo $schedule['id']; ?>)">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
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
            
            // Initialize DataTable if table exists
            if (document.getElementById('schedulesTable')) {
                $('#schedulesTable').DataTable({
                    "order": [[3, "desc"]], // Sort by date range column descending
                    "pageLength": 10,
                    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    "responsive": true,
                    language: {
                        search: "",
                        searchPlaceholder: "Search schedules...",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ schedules",
                        paginate: {
                            first: '<i class="fas fa-angle-double-left"></i>',
                            previous: '<i class="fas fa-angle-left"></i>',
                            next: '<i class="fas fa-angle-right"></i>',
                            last: '<i class="fas fa-angle-double-right"></i>'
                        }
                    },
                    drawCallback: function() {
                        // Animate rows when table is drawn or redrawn
                        $('tbody tr').each(function(i) {
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
            }
            
            // Initialize date pickers
            flatpickr(".datepicker", {
                dateFormat: "Y-m-d",
                minDate: "today"
            });
            
            // Initialize time pickers
            flatpickr(".timepicker", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "h:i K", // 12-hour format with AM/PM
                time_24hr: false,
                onOpen: function(selectedDates, dateStr, instance) {
                    // Add a helpful tooltip about time format
                    const tooltipEl = document.createElement('div');
                    tooltipEl.classList.add('flatpickr-time-format-tip');
                    tooltipEl.innerHTML = '<div style="padding: 5px 10px; background: #f0f9ff; color: #0284c7; font-size: 12px; border-radius: 4px; margin-bottom: 5px; text-align: center;">Times are now in 12-hour format (AM/PM)</div>';
                    
                    // Insert it at the top of the flatpickr calendar
                    const calendarContainer = instance.calendarContainer;
                    calendarContainer.insertBefore(tooltipEl, calendarContainer.firstChild);
                },
                onClose: function() {
                    // Remove the tooltip when closing
                    const tooltips = document.querySelectorAll('.flatpickr-time-format-tip');
                    tooltips.forEach(tip => tip.remove());
                }
            });
        });
        
        // Show/hide the schedule form
        document.getElementById('newScheduleBtn')?.addEventListener('click', function() {
            const form = document.getElementById('scheduleForm');
            form.classList.remove('hidden');
        });
        
        document.getElementById('cancelSchedule')?.addEventListener('click', function() {
            const form = document.getElementById('scheduleForm');
            form.classList.add('hidden');
        });
        
        // Refresh Button functionality
        document.getElementById('refreshButton').addEventListener('click', function() {
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
        
        // Confirm deletion
        function confirmDelete(id) {
            event.preventDefault();
            
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `schedules.php?action=delete&id=${id}`;
                }
            });
        }
        
        // Display success message if exists
        <?php if (!empty($successMessage)): ?>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '<?php echo $successMessage; ?>',
            timer: 3000,
            timerProgressBar: true
        });
        <?php endif; ?>
        
        // Display error message if exists
        <?php if (!empty($errorMessage)): ?>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '<?php echo $errorMessage; ?>',
            timer: 3000,
            timerProgressBar: true
        });
        <?php endif; ?>
    </script>
</body>
</html> 