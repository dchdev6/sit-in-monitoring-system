<?php
// Buffer output to prevent "headers already sent" errors
ob_start();

include '../../includes/navbar_student.php';

// Fetch all schedules
$schedules = get_all_schedules();

// Filter schedules to only show upcoming ones
$upcoming_schedules = array_filter($schedules, function($schedule) {
    $end_date = new DateTime($schedule['end_date']);
    $today = new DateTime();
    return $end_date >= $today;
});

// Sort schedules by date
usort($upcoming_schedules, function($a, $b) {
    return strtotime($a['start_date']) - strtotime($b['start_date']);
});

// Filter for specific lab if set
$filter_lab = isset($_GET['lab']) ? $_GET['lab'] : '';
if (!empty($filter_lab)) {
    $upcoming_schedules = array_filter($upcoming_schedules, function($schedule) use ($filter_lab) {
        return $schedule['lab'] == $filter_lab;
    });
}

// Filter for specific resource if set
$filter_resource = isset($_GET['resource']) ? $_GET['resource'] : '';
if (!empty($filter_resource)) {
    $upcoming_schedules = array_filter($upcoming_schedules, function($schedule) use ($filter_resource) {
        return isset($schedule['resource']) && $schedule['resource'] == $filter_resource;
    });
}

// Check for success message
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
    <title>CCS | Lab Schedules</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- FullCalendar -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.js'></script>
    
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
        
        /* FullCalendar customizations */
        .fc .fc-button-primary {
            background-color: #0ea5e9;
            border-color: #0ea5e9;
        }
        .fc .fc-button-primary:hover {
            background-color: #0284c7;
            border-color: #0284c7;
        }
        .fc .fc-button-primary:disabled {
            background-color: #7dd3fc;
            border-color: #7dd3fc;
        }
        .fc .fc-event {
            border-radius: 4px;
            font-size: 0.8rem;
            cursor: pointer;
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
                        Lab Schedules
                    </h1>
                    <p class="text-gray-500 mt-1 ml-12">View upcoming lab schedules and sessions</p>
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
                            <span class="text-primary-600 font-medium">Lab Schedules</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Lab Filter Pills -->
        <div class="flex flex-wrap gap-2 mb-6">
            <a href="schedules.php" class="px-4 py-2 rounded-full <?php echo empty($filter_lab) ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'; ?> transition duration-300 text-sm font-medium">
                All Labs
            </a>
            <a href="schedules.php?lab=517" class="px-4 py-2 rounded-full <?php echo $filter_lab == '517' ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'; ?> transition duration-300 text-sm font-medium">
                Lab 517
            </a>
            <a href="schedules.php?lab=524" class="px-4 py-2 rounded-full <?php echo $filter_lab == '524' ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'; ?> transition duration-300 text-sm font-medium">
                Lab 524
            </a>
            <a href="schedules.php?lab=526" class="px-4 py-2 rounded-full <?php echo $filter_lab == '526' ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'; ?> transition duration-300 text-sm font-medium">
                Lab 526
            </a>
            <a href="schedules.php?lab=528" class="px-4 py-2 rounded-full <?php echo $filter_lab == '528' ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'; ?> transition duration-300 text-sm font-medium">
                Lab 528
            </a>
            <a href="schedules.php?lab=530" class="px-4 py-2 rounded-full <?php echo $filter_lab == '530' ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'; ?> transition duration-300 text-sm font-medium">
                Lab 530
            </a>
            <a href="schedules.php?lab=542" class="px-4 py-2 rounded-full <?php echo $filter_lab == '542' ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'; ?> transition duration-300 text-sm font-medium">
                Lab 542
            </a>
        </div>
        
        <!-- Resource Filter Pills -->
        <div class="flex flex-wrap gap-2 mb-6">
            <a href="schedules.php<?php echo !empty($filter_lab) ? '?lab=' . $filter_lab : ''; ?>" class="px-4 py-2 rounded-full <?php echo empty($filter_resource) ? 'bg-green-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'; ?> transition duration-300 text-sm font-medium">
                All Resources
            </a>
            <a href="schedules.php?<?php echo !empty($filter_lab) ? 'lab=' . $filter_lab . '&' : ''; ?>resource=C%20Programming" class="px-4 py-2 rounded-full <?php echo $filter_resource == 'C Programming' ? 'bg-green-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'; ?> transition duration-300 text-sm font-medium">
                C Programming
            </a>
            <a href="schedules.php?<?php echo !empty($filter_lab) ? 'lab=' . $filter_lab . '&' : ''; ?>resource=C%23" class="px-4 py-2 rounded-full <?php echo $filter_resource == 'C#' ? 'bg-green-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'; ?> transition duration-300 text-sm font-medium">
                C#
            </a>
            <a href="schedules.php?<?php echo !empty($filter_lab) ? 'lab=' . $filter_lab . '&' : ''; ?>resource=Java" class="px-4 py-2 rounded-full <?php echo $filter_resource == 'Java' ? 'bg-green-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'; ?> transition duration-300 text-sm font-medium">
                Java
            </a>
            <a href="schedules.php?<?php echo !empty($filter_lab) ? 'lab=' . $filter_lab . '&' : ''; ?>resource=PHP" class="px-4 py-2 rounded-full <?php echo $filter_resource == 'PHP' ? 'bg-green-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'; ?> transition duration-300 text-sm font-medium">
                PHP
            </a>
            <a href="schedules.php?<?php echo !empty($filter_lab) ? 'lab=' . $filter_lab . '&' : ''; ?>resource=Python%20Programming" class="px-4 py-2 rounded-full <?php echo $filter_resource == 'Python Programming' ? 'bg-green-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'; ?> transition duration-300 text-sm font-medium">
                Python
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Lab Calendar -->
            <div class="md:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 card">
                <div class="card-header flex items-center">
                    <i class="fas fa-calendar-week text-primary-600 mr-2"></i>
                    <h2 class="font-semibold text-gray-800">Lab Schedule Calendar</h2>
                </div>
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>

            <!-- Upcoming Schedule List -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 card">
                <div class="card-header flex items-center">
                    <i class="fas fa-list-alt text-primary-600 mr-2"></i>
                    <h2 class="font-semibold text-gray-800">Upcoming Sessions</h2>
                </div>
                <div class="card-body overflow-y-auto" style="max-height: 500px;">
                    <?php if (empty($upcoming_schedules)): ?>
                    <div class="text-center py-8 text-gray-500">
                        <div class="bg-gray-50 rounded-full w-16 h-16 mx-auto mb-3 flex items-center justify-center">
                            <i class="fas fa-calendar-xmark text-gray-300 text-3xl"></i>
                        </div>
                        <h3 class="font-medium text-gray-800 mb-1">No Upcoming Sessions</h3>
                        <p class="text-sm text-gray-500">
                            <?php if (!empty($filter_lab)): ?>
                                No scheduled sessions for Lab <?php echo htmlspecialchars($filter_lab); ?>.
                            <?php else: ?>
                                No lab sessions are currently scheduled.
                            <?php endif; ?>
                        </p>
                    </div>
                    <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($upcoming_schedules as $schedule): ?>
                        <div class="border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                            <div class="flex justify-between mb-1">
                                <h3 class="font-medium text-gray-800"><?php echo htmlspecialchars($schedule['title']); ?></h3>
                                <div class="flex gap-2">
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Lab <?php echo htmlspecialchars($schedule['lab']); ?></span>
                                    <?php if(isset($schedule['resource']) && !empty($schedule['resource'])): ?>
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded"><?php echo htmlspecialchars($schedule['resource']); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="text-sm text-gray-500 mb-3 space-y-1">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-day w-5 text-gray-400"></i>
                                    <?php 
                                    $start_date = date('M d, Y', strtotime($schedule['start_date']));
                                    $end_date = date('M d, Y', strtotime($schedule['end_date']));
                                    
                                    if ($start_date == $end_date) {
                                        echo $start_date;
                                    } else {
                                        echo $start_date . ' - ' . $end_date;
                                    }
                                    ?>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-clock w-5 text-gray-400"></i>
                                    <?php 
                                    echo date('h:i A', strtotime($schedule['start_time'])) . ' - ' . 
                                         date('h:i A', strtotime($schedule['end_time'])); 
                                    ?>
                                </div>
                            </div>
                            
                            <?php if (!empty($schedule['description'])): ?>
                            <p class="text-sm text-gray-600 mt-2"><?php echo htmlspecialchars($schedule['description']); ?></p>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
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
            
            // Initialize Calendar
            const calendarEl = document.getElementById('calendar');
            
            if (calendarEl) {
                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,listWeek'
                    },
                    events: [
                        <?php foreach ($upcoming_schedules as $schedule): ?>
                        {
                            title: '<?php echo addslashes($schedule['title']); ?> (Lab <?php echo $schedule['lab']; ?>) <?php echo isset($schedule['resource']) && !empty($schedule['resource']) ? '[' . $schedule['resource'] . ']' : ''; ?>',
                            start: '<?php echo $schedule['start_date']; ?>T<?php echo $schedule['start_time']; ?>',
                            end: '<?php echo $schedule['end_date']; ?>T<?php echo $schedule['end_time']; ?>',
                            backgroundColor: '#0ea5e9',
                            borderColor: '#0ea5e9',
                            textColor: 'white',
                            extendedProps: {
                                description: '<?php echo addslashes($schedule['description']); ?>',
                                lab: '<?php echo $schedule['lab']; ?>',
                                resource: '<?php echo isset($schedule['resource']) ? addslashes($schedule['resource']) : ""; ?>'
                            }
                        },
                        <?php endforeach; ?>
                    ],
                    eventClick: function(info) {
                        // Format the times with AM/PM to make them more visible
                        const formattedStartTime = info.event.start.toLocaleTimeString([], {
                            hour: '2-digit', 
                            minute:'2-digit',
                            hour12: true
                        });
                        const formattedEndTime = info.event.end.toLocaleTimeString([], {
                            hour: '2-digit', 
                            minute:'2-digit',
                            hour12: true
                        });
                        
                        Swal.fire({
                            title: info.event.title,
                            html: `
                                <div class="text-left">
                                    <p class="mb-2"><strong>Date:</strong> ${info.event.start.toLocaleDateString()}</p>
                                    <p class="mb-2"><strong>Time:</strong> <span class="font-semibold">${formattedStartTime}</span> - <span class="font-semibold">${formattedEndTime}</span></p>
                                    <p class="mb-2"><strong>Lab:</strong> ${info.event.extendedProps.lab}</p>
                                    ${info.event.extendedProps.resource ? `<p class="mb-2"><strong>Resource:</strong> ${info.event.extendedProps.resource}</p>` : ''}
                                    ${info.event.extendedProps.description ? `<p class="mb-2"><strong>Description:</strong> ${info.event.extendedProps.description}</p>` : ''}
                                </div>
                            `,
                            icon: 'info',
                            confirmButtonColor: '#0ea5e9'
                        });
                    }
                });
                
                calendar.render();
            }
            
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
        });
    </script>
</body>
</html> 