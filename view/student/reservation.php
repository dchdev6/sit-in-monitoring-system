<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../includes/navbar_student.php';

// Check if user is logged in
if (!isset($_SESSION['id_number'])) {
    header('Location: ../../Login.php');
    exit;
}

// Define the retrieve_pc function locally to avoid including all of backend_admin.php
if (!function_exists('retrieve_pc')) {
    function retrieve_pc($lab) {
        $db = Database::getInstance();
        $con = $db->getConnection();

        $sql = "SELECT pc_id, `$lab` as lab2 FROM student_pc";
        $result = mysqli_query($con, $sql);
        
        $pc = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $pc[] = $row;
            }
        }
        return $pc;
    }
}

// Fetch the latest session data from the database
$db = Database::getInstance();
$conn = $db->getConnection();
$idNumber = $_SESSION['id_number'];

// Query to get the latest session count
$stmt = $conn->prepare("SELECT session FROM student_session WHERE id_number = ?");
$stmt->bind_param("s", $idNumber);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Update the session variable with the current count
    $_SESSION['remaining'] = $row['session'];
} else {
    $_SESSION['remaining'] = "N/A"; // Default if no record found
}

// Check for messages for sweet alert
$successMessage = '';
$errorMessage = '';

if(isset($_SESSION['success_message'])) {
    $successMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

if(isset($_SESSION['error_message'])) {
    $errorMessage = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

// Get initial PCs for default lab (Lab 524)
$selectedLab = 'lab_524';

try {
    // Now we can safely call the retrieve_pc function
    $pcData = retrieve_pc($selectedLab);
    
    // Initialize as empty array if null is returned
    if ($pcData === null) {
        $pcData = [];
    }
} catch (Exception $e) {
    // If there's an error, log it and initialize as empty array
    error_log("Error loading PC data: " . $e->getMessage());
    $pcData = [];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Lab Sit-in Reservation System for Students">
    <title>Reservation</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Animation library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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
                            500: '#10b981',
                            600: '#059669'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'Segoe UI', 'Tahoma', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .stagger-item {
            opacity: 0;
            transform: translateY(20px);
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .scrollable-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            gap: 12px;
        }
        
        /* Custom scrollbar */
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
        
        .lab-selector {
            transition: all 0.3s ease;
        }
        
        .lab-selector:hover {
            transform: translateY(-3px);
        }
        
        .lab-selector.active {
            border-color: #0ea5e9;
            background-color: #f0f9ff;
        }
        
        /* Time selector styles */
        .time-selector-group {
            position: relative;
        }
        
        .time-selector-group::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 12px;
            height: 2px;
            background-color: #cbd5e1;
            transform: translate(-50%, -50%);
            z-index: 1;
        }
        
        /* Select styles for time */
        select.time-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
        
        /* PC item styling */
        .pc-item {
            aspect-ratio: 1/1;
            width: 80px;
            height: 80px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .pc-item label {
            height: 100%;
            width: 100%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .pc-item[data-available="1"]:hover {
            transform: translateY(-3px);
        }
        
        .pc-item[data-available="1"] label:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .pc-item label.ring-2 {
            box-shadow: 0 4px 8px rgba(14, 165, 233, 0.2);
        }
        
        /* End time indicator */
        .end-time-indicator {
            display: flex;
            align-items: center;
            margin-top: 0.75rem;
            font-size: 0.875rem;
            color: #64748b;
            background-color: #ffffff;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            border: 1px dashed #e2e8f0;
        }
        
        .end-time-indicator i {
            margin-right: 0.5rem;
            color: #0ea5e9;
            font-size: 0.875rem;
        }
        
        .end-time-badge {
            display: inline-block;
            padding: 0.2rem 0.5rem;
            background-color: #f0f9ff;
            color: #0ea5e9;
            border-radius: 0.375rem;
            font-weight: 600;
            margin-left: 0.375rem;
            border: 1px solid #e0f2fe;
            font-size: 0.875rem;
        }
        
        .scrollable-area {
            max-height: 400px;
            overflow-y: auto;
        }
        
        /* PC Selection Grid */
        #pc-selection-area {
            height: 300px;
            overflow-y: auto;
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
                            <i class="fas fa-calendar-plus text-primary-600"></i>
                        </div>
                        Lab Reservations
                    </h1>
                    <p class="text-gray-500 mt-1 ml-12">Request and manage your lab seat reservations</p>
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
                            <span class="text-primary-600 font-medium">Lab Reservations</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Display messages using SweetAlert -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                <?php if(!empty($successMessage)): ?>
                    Swal.fire({
                        title: 'Success!',
                        text: '<?php echo $successMessage; ?>',
                        icon: 'success',
                        confirmButtonColor: '#0ea5e9'
                    });
                <?php endif; ?>
                
                <?php if(!empty($errorMessage)): ?>
                    Swal.fire({
                        title: 'Error!',
                        text: '<?php echo $errorMessage; ?>',
                        icon: 'error',
                        confirmButtonColor: '#0ea5e9'
                    });
                <?php endif; ?>
            });
        </script>

        <!-- Main Content -->
        <div class="container mx-auto px-4 max-w-6xl">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Reservation Form Card -->
                <div class="md:col-span-1">
                    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden stagger-item h-full" style="min-height: 580px;" data-aos="fade-up">
                        <div class="bg-[#0284c7] px-6 py-4 border-b border-[#0369a1] flex items-center">
                            <i class="fas fa-edit text-white mr-3"></i>
                            <h2 class="text-white text-lg font-semibold">Reservation Details</h2>
                        </div>
                        
                        <form action="../../api/api_student.php" method="POST" id="reservation-form" class="p-6 h-full">
                            <input type="hidden" name="reserve_user" value="true">
                            <input type="hidden" name="id_number" value="<?php echo $_SESSION['id_number']; ?>">
                            <input type="hidden" id="pc_number" name="pc_number" value="">
                            <input type="hidden" id="lab" name="lab" value="">

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div>
                                    <label for="purpose" class="block text-sm font-medium text-gray-700 mb-1">Purpose</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-tasks text-gray-400"></i>
                                        </div>
                                        <select class="form-select pl-10 w-full border border-gray-300 rounded-lg py-3 px-4 text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" id="purpose" name="purpose" required>
                                            <option value="" disabled selected>Select purpose</option>
                                            <option value="C-Programming">C Programming</option>
                                            <option value="C#">C#</option>
                                            <option value="Java">Java</option>
                                            <option value="PHP">PHP</option>
                                            <option value="Database">Database</option>
                                            <option value="Digital-Logic-Design">Digital Logic & Design</option>
                                            <option value="Embedded-Systems-IoT">Embedded Systems & IoT</option>
                                            <option value="Python-Programming">Python Programming</option>
                                            <option value="Systems-Integration-Architecture">Systems Integration & Architecture</option>
                                            <option value="Computer-Application">Computer Application</option>
                                            <option value="Web-Design-Development">Web Design & Development</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-calendar text-gray-400"></i>
                                        </div>
                                        <input class="form-input pl-10 w-full border border-gray-300 rounded-lg py-3 px-4 text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm" type="date" id="date" name="date" required min="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                </div>

                                <div class="lg:col-span-2">
                                    <label for="time" class="block text-sm font-medium text-gray-700 mb-3">Time Selection</label>
                                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200" style="min-height: 165px;">
                                        <div class="flex flex-row gap-3">
                                            <div class="relative flex-1">
                                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                    <i class="fas fa-clock text-gray-400 text-base"></i>
                                                </div>
                                                <select class="time-select pl-11 w-full border border-gray-300 rounded-lg py-3 px-4 text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm font-medium" id="start_hour" name="start_hour" required>
                                                    <option value="" disabled selected>Start Time</option>
                                                    <option value="08">8:00 AM</option>
                                                    <option value="09">9:00 AM</option>
                                                    <option value="10">10:00 AM</option>
                                                    <option value="11">11:00 AM</option>
                                                    <option value="12">12:00 PM</option>
                                                    <option value="13">1:00 PM</option>
                                                    <option value="14">2:00 PM</option>
                                                    <option value="15">3:00 PM</option>
                                                    <option value="16">4:00 PM</option>
                                                    <option value="17">5:00 PM</option>
                                                    <option value="18">6:00 PM</option>
                                                    <option value="19">7:00 PM</option>
                                                </select>
                                            </div>
                                            <div class="relative flex-1">
                                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                    <i class="fas fa-hourglass-end text-gray-400 text-base"></i>
                                                </div>
                                                <select class="time-select pl-11 w-full border border-gray-300 rounded-lg py-3 px-4 text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm font-medium" id="duration" name="duration" required>
                                                    <option value="" disabled selected>Duration</option>
                                                    <option value="1">1 Hour</option>
                                                    <option value="2">2 Hours</option>
                                                    <option value="3">3 Hours</option>
                                                </select>
                                            </div>
                                        </div>
                                        <input type="hidden" id="time" name="time" value="">
                                        <div class="end-time-indicator mt-3">
                                            <i class="fas fa-stopwatch text-sm"></i>
                                            <span class="text-sm">Session ends at</span>
                                            <span id="end_time" class="end-time-badge">--:-- --</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="lg:col-span-2">
                                    <label for="remaining" class="block text-sm font-medium text-gray-700 mb-1">Remaining Sessions</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-hourglass-half text-gray-400"></i>
                                        </div>
                                        <input class="form-input pl-10 w-full border border-gray-300 bg-gray-50 rounded-lg py-3 px-4 text-gray-700 text-sm" type="text" id="remaining" name="remaining" value="<?php echo isset($_SESSION['remaining']) ? $_SESSION['remaining'] : 'N/A'; ?>" readonly>
                                    </div>
                                    <?php if (isset($_SESSION['remaining']) && $_SESSION['remaining'] <= 3): ?>
                                    <p class="mt-1 text-xs text-amber-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        <?php echo $_SESSION['remaining'] > 0 
                                            ? 'You are low on available sessions.' 
                                            : 'You have no available sessions left.'; ?>
                                    </p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="mt-6">
                                <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-md flex justify-center items-center disabled:bg-gray-300 disabled:cursor-not-allowed" id="submit-btn" disabled>
                                    <span class="mr-2">Reserve Now</span>
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                                <p class="text-xs text-center text-gray-500 mt-2" id="form-status">Complete all fields and select an available computer to continue</p>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Laboratory Selection Card - Separate from PC selection -->
                <div class="md:col-span-1">
                    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden stagger-item h-full" style="min-height: 580px;" data-aos="fade-up" data-aos-delay="100">
                        <div class="bg-[#0284c7] px-6 py-4 border-b border-[#0369a1]">
                            <h2 class="text-white text-lg font-semibold flex items-center">
                                <i class="fas fa-building mr-2"></i>
                                Select a Laboratory
                            </h2>
                        </div>
                        <div class="p-4">
                            <!-- Visual Representation of Lab Layout -->
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 mb-4" style="min-height: 120px;">
                                <div class="lab-selector cursor-pointer flex flex-col items-center border border-gray-300 rounded-lg p-2 hover:bg-primary-50" data-lab="517" data-required="true">
                                    <i class="fas fa-laptop text-gray-600 mb-1"></i>
                                    <span class="text-sm">Lab 517</span>
                                </div>
                                <div class="lab-selector cursor-pointer flex flex-col items-center border border-gray-300 rounded-lg p-2 hover:bg-primary-50" data-lab="524" data-required="true">
                                    <i class="fas fa-laptop text-gray-600 mb-1"></i>
                                    <span class="text-sm">Lab 524</span>
                                </div>
                                <div class="lab-selector cursor-pointer flex flex-col items-center border border-gray-300 rounded-lg p-2 hover:bg-primary-50" data-lab="526" data-required="true">
                                    <i class="fas fa-laptop text-gray-600 mb-1"></i>
                                    <span class="text-sm">Lab 526</span>
                                </div>
                                <div class="lab-selector cursor-pointer flex flex-col items-center border border-gray-300 rounded-lg p-2 hover:bg-primary-50" data-lab="528" data-required="true">
                                    <i class="fas fa-laptop text-gray-600 mb-1"></i>
                                    <span class="text-sm">Lab 528</span>
                                </div>
                                <div class="lab-selector cursor-pointer flex flex-col items-center border border-gray-300 rounded-lg p-2 hover:bg-primary-50" data-lab="530" data-required="true">
                                    <i class="fas fa-laptop text-gray-600 mb-1"></i>
                                    <span class="text-sm">Lab 530</span>
                                </div>
                                <div class="lab-selector cursor-pointer flex flex-col items-center border border-gray-300 rounded-lg p-2 hover:bg-primary-50" data-lab="542" data-required="true">
                                    <i class="fas fa-laptop text-gray-600 mb-1"></i>
                                    <span class="text-sm">Lab 542</span>
                                </div>
                            </div>
                            
                            <div class="text-center mb-4" style="min-height: 60px;">
                                <p class="text-sm text-gray-600">Select a laboratory to view available computers</p>
                                <div id="lab-status" class="mt-2 p-2 rounded-lg bg-gray-50 text-gray-500 text-sm hidden">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    <span>No laboratory selected</span>
                                </div>
                                <div id="lab-selected" class="mt-2 p-2 rounded-lg bg-green-50 text-green-700 text-sm hidden">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    <span>Laboratory selected: <strong id="selected-lab-name">Lab</strong></span>
                                </div>
                            </div>
                            
                            <!-- PC Selection Area (Moved from below) -->
                            <div class="border-t border-gray-200 pt-3">
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Available Computers</h3>
                                <!-- Legend -->
                                <div class="flex items-center gap-2 mb-3 justify-center bg-gray-50 p-2 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="h-2 w-2 bg-green-500 rounded-full mr-1"></div>
                                        <span class="text-xs text-gray-600">Available</span>
                                    </div>
                                    <div class="flex items-center mx-2">
                                        <div class="h-2 w-2 bg-red-500 rounded-full mr-1"></div>
                                        <span class="text-xs text-gray-600">In Use</span>
                                    </div>
                                    <div class="flex items-center mx-2">
                                        <div class="h-2 w-2 bg-amber-500 rounded-full mr-1"></div>
                                        <span class="text-xs text-gray-600">Reserved</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="h-2 w-2 border border-primary-600 bg-primary-100 rounded-full mr-1"></div>
                                        <span class="text-xs text-gray-600">Selected</span>
                                    </div>
                                </div>

                                <!-- PC Selection Grid -->
                                <div id="pc-selection-area" class="scrollable-area" style="height: 300px;">
                                    <div class="scrollable-grid" id="pc-grid">
                                        <div class="text-center py-8 text-gray-500 col-span-full flex flex-col items-center justify-center h-full" id="pc-grid-placeholder">
                                            <i class="fas fa-arrow-up text-gray-400 text-2xl block mb-2"></i>
                                            <p class="text-sm">Please select a laboratory above</p>
                                            <p class="text-xs text-gray-400 mt-1">No computers available to display</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Hide the original Available Computers Card since we moved it -->
            <div class="hidden md:hidden">
                <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden stagger-item" data-aos="fade-up" data-aos-delay="200">
                    <div class="bg-[#0284c7] px-6 py-4 border-b border-[#0369a1]">
                        <h2 class="text-white text-lg font-semibold flex items-center">
                            <i class="fas fa-desktop mr-2"></i>
                            Available Computers
                        </h2>
                    </div>
                    <div class="p-6">
                        <!-- Legend -->
                        <div class="flex items-center gap-4 mb-4 justify-center">
                            <div class="flex items-center">
                                <div class="h-3 w-3 bg-green-500 rounded-full mr-2"></div>
                                <span class="text-xs text-gray-600">Available</span>
                            </div>
                            <div class="flex items-center">
                                <div class="h-3 w-3 bg-red-500 rounded-full mr-2"></div>
                                <span class="text-xs text-gray-600">In Use</span>
                            </div>
                            <div class="flex items-center">
                                <div class="h-3 w-3 border border-primary-600 bg-primary-100 rounded-full mr-2"></div>
                                <span class="text-xs text-gray-600">Selected</span>
                            </div>
                        </div>

                        <!-- PC Selection Grid -->
                        <div id="pc-selection-area-original" class="max-h-80 overflow-y-auto scrollable-area">
                            <div class="scrollable-grid" id="pc-grid-original">
                                <div class="text-center py-12 text-gray-500 col-span-full" id="pc-grid-placeholder-original">
                                    <i class="fas fa-arrow-up text-gray-400 text-3xl block mb-3"></i>
                                    <p class="text-lg">Please select a laboratory above</p>
                                    <p class="text-sm text-gray-400 mt-2">No computers available to display</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-10"></div>

        <script>
            // Initialize animations
            document.addEventListener('DOMContentLoaded', function() {
                AOS.init({
                    duration: 800,
                    easing: 'ease-in-out',
                    once: true
                });

                // Check if remaining sessions are displayed correctly
                const remainingField = document.getElementById('remaining');
                if (!remainingField.value || remainingField.value === 'undefined' || remainingField.value === 'null') {
                    // Fetch session count via AJAX
                    fetch('../../includes/get_session_count.php')
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                remainingField.value = data.count;
                            }
                        })
                        .catch(error => {});
                }
                
                // Make elements visible with stagger effect
                const staggerItems = document.querySelectorAll('.stagger-item');
                staggerItems.forEach((item, index) => {
                    setTimeout(() => {
                        item.style.animation = `fadeInUp 0.6s ${index * 0.1}s forwards ease-out`;
                    }, 100);
                });
                
                // Show page
                document.body.classList.add('opacity-100');
                
                // Initialize form validation
                initializeFormValidation();
                
                // Add lab selection functionality
                initializeLabSelection();

                // Initialize time selection functionality
                initializeTimeSelection();

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
            });
            
            // Time selection functionality
            function initializeTimeSelection() {
                const startHourSelect = document.getElementById('start_hour');
                const durationSelect = document.getElementById('duration');
                const endTimeSpan = document.getElementById('end_time');
                const timeInput = document.getElementById('time');
                
                function updateEndTime() {
                    if (!startHourSelect.value || !durationSelect.value) {
                        endTimeSpan.textContent = '--:-- --';
                        timeInput.value = '';
                        return;
                    }
                    
                    const startHour = parseInt(startHourSelect.value);
                    const duration = parseInt(durationSelect.value);
                    const endHour = startHour + duration;
                    
                    // Format start time (for display)
                    const startTime = formatTime(startHour);
                    
                    // Format end time (for display)
                    const endTime = formatTime(endHour);
                    
                    // Update end time display
                    endTimeSpan.textContent = endTime;
                    
                    // Update hidden time input with the format expected by the backend
                    timeInput.value = `${startTime} - ${endTime}`;
                    
                    // Trigger change event for validation
                    timeInput.dispatchEvent(new Event('change'));
                }
                
                function formatTime(hour) {
                    if (hour < 12) {
                        return `${hour === 0 ? 12 : hour}:00 AM`;
                    } else {
                        return `${hour === 12 ? 12 : hour - 12}:00 PM`;
                    }
                }
                
                // Add event listeners to update end time when start time or duration changes
                startHourSelect.addEventListener('change', updateEndTime);
                durationSelect.addEventListener('change', updateEndTime);
            }
            
            // Form validation
            function initializeFormValidation() {
                const form = document.getElementById('reservation-form');
                const submitBtn = document.getElementById('submit-btn');
                const formStatus = document.getElementById('form-status');
                const requiredFields = ['purpose', 'lab', 'time', 'date', 'pc_number'];
                
                // Check form completeness
                function validateForm() {
                    let isValid = true;
                    let missingFields = [];
                    
                    requiredFields.forEach(field => {
                        const input = document.getElementById(field) || document.querySelector(`[name="${field}"]`);
                        const value = input ? input.value : null;
                        
                        if (!input || !value) {
                            isValid = false;
                            missingFields.push(field);
                        }
                    });
                    
                    const remainingField = document.getElementById('remaining');
                    const remainingValue = parseInt(remainingField.value) || 0;
                    
                    if (remainingValue <= 0) {
                        isValid = false;
                        formStatus.innerHTML = '<i class="fas fa-exclamation-circle text-red-500 mr-1"></i> You have no remaining sessions';
                        formStatus.className = 'text-xs text-center text-red-500 mt-2';
                        submitBtn.disabled = true;
                        return;
                    }
                    
                    if (isValid) {
                        submitBtn.disabled = false;
                        formStatus.innerHTML = 'Ready to submit';
                        formStatus.className = 'text-xs text-center text-green-600 mt-2';
                    } else {
                        submitBtn.disabled = true;
                        formStatus.innerHTML = 'Complete all fields and select an available computer to continue';
                        formStatus.className = 'text-xs text-center text-gray-500 mt-2';
                    }
                }
                
                // Add event listeners to all required fields
                requiredFields.forEach(field => {
                    const input = document.getElementById(field) || document.querySelector(`[name="${field}"]`);
                    if (input) {
                        input.addEventListener('change', validateForm);
                    }
                });
                
                // Listen to start_hour and duration fields since they update the hidden time field
                const startHourSelect = document.getElementById('start_hour');
                const durationSelect = document.getElementById('duration');
                if (startHourSelect && durationSelect) {
                    startHourSelect.addEventListener('change', validateForm);
                    durationSelect.addEventListener('change', validateForm);
                }
                
                // Initial validation
                validateForm();
                
                // Submit form with loading state
                form.addEventListener('submit', function(e) {
                    if (!submitBtn.disabled) {
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
                        submitBtn.disabled = true;
                    }
                });
            }
            
            // Lab selection functionality - Updated to fix selection behavior
            function initializeLabSelection() {
                const labSelectors = document.querySelectorAll('.lab-selector');
                const pcGrid = document.getElementById('pc-grid');
                const pcGridPlaceholder = document.getElementById('pc-grid-placeholder');
                const labInput = document.getElementById('lab');
                const labStatus = document.getElementById('lab-status');
                const labSelected = document.getElementById('lab-selected');
                const selectedLabName = document.getElementById('selected-lab-name');
                
                labSelectors.forEach(selector => {
                    selector.addEventListener('click', function() {
                        const lab = this.dataset.lab;
                        
                        // Update the hidden lab input - Fixed to use the correct format lab_XXX
                        if (labInput) {
                            labInput.value = "lab_" + lab;
                            labInput.dispatchEvent(new Event('change'));
                        }
                        
                        // Update visual selection
                        labSelectors.forEach(item => item.classList.remove('active'));
                        this.classList.add('active');
                        
                        // Update lab selection status
                        labStatus.classList.add('hidden');
                        labSelected.classList.remove('hidden');
                        selectedLabName.textContent = "Lab " + lab;
                        
                        // Show loading state
                        pcGrid.innerHTML = ''; // Clear existing content
                        pcGridPlaceholder.style.display = 'block';
                        pcGridPlaceholder.innerHTML = `
                            <div class="flex flex-col items-center justify-center py-12">
                                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-500 mb-4"></div>
                                <p class="text-lg font-medium text-gray-700">Loading computers...</p>
                                <p class="text-sm text-gray-500 mt-1">Please wait while we fetch available PCs</p>
                            </div>
                        `;
                        
                        // Fetch computers for the selected lab
                        fetch('../../api/get_available_pcs.php?lab=lab_' + lab)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    renderPCGrid(data.pcs, 'lab_' + lab);
                                } else {
                                    // Handle API error
                                    pcGridPlaceholder.innerHTML = `
                                        <div class="flex flex-col items-center justify-center py-12">
                                            <div class="bg-red-100 p-3 rounded-full mb-4">
                                                <i class="fas fa-exclamation-circle text-red-500 text-3xl"></i>
                                            </div>
                                            <p class="text-lg font-medium text-red-500">Error: ${data.message || 'Failed to load computers'}</p>
                                            <p class="text-sm text-gray-500 mt-2">Please try selecting another laboratory</p>
                                        </div>
                                    `;
                                }
                            })
                            .catch(error => {
                                pcGridPlaceholder.innerHTML = `
                                    <div class="flex flex-col items-center justify-center py-12">
                                        <div class="bg-red-100 p-3 rounded-full mb-4">
                                            <i class="fas fa-exclamation-circle text-red-500 text-3xl"></i>
                                        </div>
                                        <p class="text-lg font-medium text-red-500">Failed to load computers</p>
                                        <p class="text-sm text-gray-500 mt-2">Please check your connection and try again</p>
                                    </div>
                                `;
                            });
                    });
                });
                
                function renderPCGrid(pcData, labName) {
                    // Clear the placeholder
                    pcGridPlaceholder.style.display = 'none';
                    
                    if (!pcData || pcData.length === 0) {
                        pcGrid.innerHTML = `
                            <div class="col-span-full text-center py-6 text-gray-500">
                                <div class="bg-gray-100 p-3 rounded-full inline-block mb-2">
                                    <i class="fas fa-info-circle text-xl text-gray-400"></i>
                                </div>
                                <p class="text-sm font-medium">No computers available</p>
                                <p class="text-xs text-gray-400 mt-1">This laboratory has no computers registered</p>
                            </div>
                        `;
                        return;
                    }
                    
                    let gridHTML = '';
                    pcData.forEach((pc, index) => {
                        const isAvailable = pc.lab2 === '1';
                        const isReserved = pc.lab2 === '2';
                        const animationDelay = index * 30; // Faster staggered animation
                        
                        let statusStyle = '';
                        let statusIcon = '';
                        let statusText = '';
                        
                        if (isAvailable) {
                            statusStyle = 'bg-green-50 hover:bg-green-100';
                            statusIcon = 'text-green-500';
                            statusText = 'text-green-700';
                        } else if (isReserved) {
                            statusStyle = 'bg-amber-50';
                            statusIcon = 'text-amber-500';
                            statusText = 'text-amber-700';
                        } else {
                            statusStyle = 'bg-red-50';
                            statusIcon = 'text-red-500';
                            statusText = 'text-red-700';
                        }
                        
                        gridHTML += `
                            <div class="pc-item ${isAvailable ? 'cursor-pointer' : 'opacity-70 cursor-not-allowed'}" 
                                  data-pc="${pc.pc_id}" 
                                  data-available="${isAvailable ? '1' : '0'}" 
                                  style="opacity: 0; animation: fadeInUp 0.4s ${animationDelay}ms forwards ease-out;">
                                <label for="pc${pc.pc_id}" class="flex flex-col items-center justify-center p-2 rounded-lg ${statusStyle} transition-all">
                                    <i class="fas fa-desktop ${statusIcon} text-sm"></i>
                                    <span class="text-xs font-medium ${statusText} mt-1">
                                        PC ${pc.pc_id}
                                    </span>
                                    ${isReserved ? `<span class="text-xs ${statusText}">Reserved</span>` : ''}
                                </label>
                            </div>
                        `;
                    });
                    
                    pcGrid.innerHTML = gridHTML;
                    
                    // Add click handlers to PC items - Now all available PCs are selectable
                    document.querySelectorAll('.pc-item[data-available="1"]').forEach(item => {
                        item.addEventListener('click', function() {
                            // Deselect all PCs
                            document.querySelectorAll('.pc-item label').forEach(label => {
                                label.classList.remove('ring-2', 'ring-primary-500', 'border-primary-500');
                            });
                            
                            // Select this PC
                            const label = this.querySelector('label');
                            label.classList.add('ring-2', 'ring-primary-500', 'border-primary-500');
                            
                            // Get the PC number
                            const pcNumber = this.dataset.pc;
                            
                            // Find the hidden input and update it
                            const pcInput = document.getElementById('pc_number');
                            if (pcInput) {
                                pcInput.value = pcNumber;
                                pcInput.dispatchEvent(new Event('change'));
                            }
                            
                            // Show selection
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: `PC ${pcNumber} selected`,
                                showConfirmButton: false,
                                timer: 1000,
                                toast: true
                            });
                        });
                    });
                }
            }
        </script>
    </div>
</body>
</html>
