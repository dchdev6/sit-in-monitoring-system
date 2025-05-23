<?php
// Buffer output to prevent "headers already sent" errors
ob_start();

session_start();
error_log("Homepage loaded. Current profile image: " . ($_SESSION["profile_image"] ?? 'not set'));
error_log("Session Profile Image: " . $_SESSION["profile_image"]);

require_once '../../includes/navbar_student.php';

// Add this code to fetch the latest session count from the database
if (isset($_SESSION['id_number'])) {
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
}

$announce = view_announcement(); 

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
  <meta name="description" content="Student Dashboard for Programming Lab Sit-in System">
  <title>Student Dashboard</title>
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
                },
                fontFamily: {
                    sans: ['Inter', 'Segoe UI', 'Tahoma', 'sans-serif'],
                },
            }
        }
    }
  </script>
  <style>
    body {
        opacity: 0;
        transition: opacity 0.5s ease;
    }
    .dashboard-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
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
    .animate-float {
        animation: float 3s infinite ease-in-out;
    }
    .animate-pulse-slow {
        animation: pulseSlow 3s infinite ease-in-out;
    }
    .animate-bounce-subtle {
        animation: bounceSlight 2s infinite ease-in-out;
    }
    .stagger-item {
        opacity: 0;
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
        to { opacity: 1; transform: translateX(0); }
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
    .profile-badge {
        position: relative;
    }
    .profile-badge::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        width: 20px;
        height: 20px;
        background-color: #10b981;
        border-radius: 50%;
        border: 3px solid white;
    }
    .hover-scale {
        transition: transform 0.3s ease;
    }
    .hover-scale:hover {
        transform: scale(1.02);
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
                            <i class="fas fa-home text-primary-600"></i>
                        </div>
                        Student Dashboard
                    </h1>
                    <p class="text-gray-500 mt-1 ml-12">Welcome to your student dashboard</p>
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
                        <a href="../index.php" class="text-gray-500 hover:text-primary-600 transition-colors inline-flex items-center">
                            <i class="fas fa-home mr-2"></i>
                            Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <span class="text-gray-400 mx-2">/</span>
                            <span class="text-primary-600 font-medium">Student Dashboard</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Student Information Card -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 transition duration-300 dashboard-card hover:border-primary-200 stagger-item">
                <div class="border-b border-gray-100 bg-primary-600 py-3 px-5 rounded-t-xl">
                    <h2 class="font-semibold text-white flex items-center">
                        <i class="fas fa-user-graduate mr-2 animate-bounce-subtle"></i>
                        Student Information
                    </h2>
                </div>
                <div class="p-6 flex flex-col items-center">
                    <div class="mb-4 profile-badge">
                        <img class="w-32 h-32 rounded-full border-4 border-primary-100 shadow-md object-cover" 
                            src="<?php echo '../../assets/images/' . ($_SESSION['profile_image'] ?? 'default-profile.jpg'); ?>" 
                            alt="Profile Picture">
                    </div>
                    
                    <h3 class="text-xl font-semibold text-gray-800 mb-2"><?php echo $_SESSION['name']; ?></h3>
                    <div class="inline-flex items-center bg-primary-50 rounded-full px-3 py-1 text-sm text-primary-700 mb-4">
                        <i class="fas fa-graduation-cap mr-2"></i>
                        <?php echo $_SESSION['course']; ?> (Year <?php echo $_SESSION['yearLevel']; ?>)
                    </div>
                    
                    <div class="w-full space-y-3 text-gray-600">
                        <div class="flex items-center">
                            <div class="w-8 text-gray-400"><i class="fas fa-envelope"></i></div>
                            <div class="flex-1"><?php echo $_SESSION['email']; ?></div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 text-gray-400"><i class="fas fa-map-marker-alt"></i></div>
                            <div class="flex-1"><?php echo $_SESSION['address']; ?></div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 text-gray-400"><i class="fas fa-clock"></i></div>
                            <div class="flex-1 font-medium">
                                Remaining Sessions: 
                                <span class="<?php echo ($_SESSION['remaining'] < 5) ? 'text-red-600' : 'text-green-600'; ?> font-bold">
                                    <?php echo $_SESSION['remaining']; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <a href="../student/profile.php" class="mt-6 inline-flex items-center text-primary-600 hover:text-primary-700 transition-colors">
                        <i class="fas fa-edit mr-1"></i> Edit Profile
                    </a>
                </div>
            </div>

            <!-- Announcement Card -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 transition duration-300 dashboard-card hover:border-primary-200 stagger-item">
                <div class="border-b border-gray-100 bg-primary-600 py-3 px-5 rounded-t-xl flex justify-between items-center">
                    <h2 class="font-semibold text-white flex items-center">
                        <i class="fas fa-bullhorn mr-2 animate-bounce-subtle"></i>
                        Announcements
                    </h2>
                    <div class="bg-white bg-opacity-20 text-white text-xs px-2 py-1 rounded-full">
                        <?php echo count($announce); ?> <?php echo (count($announce) == 1) ? 'Notice' : 'Notices'; ?>
                    </div>
                </div>
                <div class="p-4 h-[450px] overflow-y-auto scrollbar-thin">
                    <?php if (!empty($announce)) : ?>
                        <div class="space-y-4">
                            <?php foreach ($announce as $index => $row) : ?>
                                <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-all duration-300 announcement-item" 
                                     style="transition-delay: <?php echo $index * 100; ?>ms">
                                    <div class="flex items-center mb-2">
                                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white mr-3 shadow-sm">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($row['admin_name']); ?></p>
                                            <p class="text-xs text-gray-500"><?php echo htmlspecialchars($row['date']); ?></p>
                                        </div>
                                    </div>
                                    <p class="text-gray-700 pl-12"><?php echo htmlspecialchars($row['message']); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <div class="text-center py-8 text-gray-500">
                            <div class="bg-gray-100 rounded-full w-16 h-16 mx-auto mb-3 flex items-center justify-center">
                                <i class="fas fa-bullhorn text-gray-300 text-3xl animate-bounce-subtle"></i>
                            </div>
                            <p>No announcements available</p>
                            <p class="text-sm text-gray-400 mt-1">Check back later for updates</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Rules and Regulations Card -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 transition duration-300 dashboard-card hover:border-primary-200 stagger-item">
                <div class="border-b border-gray-100 bg-primary-600 py-3 px-5 rounded-t-xl">
                    <h2 class="font-semibold text-white flex items-center">
                        <i class="fas fa-clipboard-list mr-2 animate-bounce-subtle"></i>
                        Rules and Regulations
                    </h2>
                </div>
                <div class="p-5 h-[450px] overflow-y-auto scrollbar-thin">
                    <div class="text-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800">University of Cebu</h3>
                        <p class="text-sm font-semibold text-gray-700">COLLEGE OF INFORMATION & COMPUTER STUDIES</p>
                    </div>
                    
                    <div class="bg-amber-50 border-l-4 border-amber-500 p-4 mb-4">
                        <p class="text-amber-800 font-semibold flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            LABORATORY RULES AND REGULATIONS
                        </p>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-2 flex items-center">
                                <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                General Rules
                            </h4>
                            <ul class="ml-8 space-y-2 text-gray-700 list-disc">
                                <li>Maintain silence and discipline in the lab.</li>
                                <li>No games, unauthorized browsing, or downloads.</li>
                                <li>Do not tamper with computer settings or files.</li>
                                <li>Respect computer time limits.</li>
                                <li>No eating, drinking, or smoking inside the lab.</li>
                                <li>Follow instructor seating arrangements.</li>
                            </ul>
                        </div>
                        
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-2 flex items-center">
                                <i class="fas fa-exclamation-circle text-red-600 mr-2"></i>
                                Disciplinary Actions
                            </h4>
                            <ul class="ml-8 space-y-2 text-gray-700 list-disc">
                                <li><span class="font-medium">First Offense:</span> Suspension from lab sessions.</li>
                                <li><span class="font-medium">Second Offense:</span> Heavier disciplinary action.</li>
                            </ul>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <p class="text-blue-800 text-sm italic flex items-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    These rules are in place to ensure the safety and proper use of laboratory resources. Please adhere to these guidelines at all times.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Lab Resources & Schedules Row -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6 stagger-item" style="opacity: 0;">
            <!-- Upcoming Schedules Card -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 transition duration-300 dashboard-card hover:border-primary-200">
                <div class="border-b border-gray-100 bg-primary-600 py-3 px-5 rounded-t-xl flex justify-between items-center">
                    <h2 class="font-semibold text-white flex items-center">
                        <i class="fas fa-calendar-alt mr-2 animate-bounce-subtle"></i>
                        Upcoming Lab Schedules
                    </h2>
                    <a href="schedules.php" class="text-xs text-white bg-white bg-opacity-20 hover:bg-opacity-30 px-3 py-1 rounded-full transition-all duration-200">
                        View All
                    </a>
                </div>
                <div class="p-4 max-h-[300px] overflow-y-auto scrollbar-thin">
                    <?php 
                    $upcoming_schedules = get_upcoming_schedules(5);
                    if (empty($upcoming_schedules)): 
                    ?>
                        <div class="text-center py-8 text-gray-500">
                            <div class="bg-gray-100 rounded-full w-16 h-16 mx-auto mb-3 flex items-center justify-center">
                                <i class="fas fa-calendar text-gray-300 text-3xl animate-bounce-subtle"></i>
                            </div>
                            <p>No upcoming schedules available</p>
                            <p class="text-sm text-gray-400 mt-1">Check back later for scheduled lab sessions</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php foreach ($upcoming_schedules as $index => $schedule): ?>
                                <div class="bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-all duration-300">
                                    <div class="flex justify-between mb-2">
                                        <h3 class="font-medium text-gray-800"><?php echo htmlspecialchars($schedule['title']); ?></h3>
                                        <div class="flex gap-2">
                                            <span class="bg-primary-100 text-primary-800 text-xs font-medium px-2.5 py-0.5 rounded whitespace-nowrap">
                                                Lab <?php echo htmlspecialchars($schedule['lab']); ?>
                                            </span>
                                            <?php if(isset($schedule['resource']) && !empty($schedule['resource'])): ?>
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded whitespace-nowrap">
                                                <?php echo htmlspecialchars($schedule['resource']); ?>
                                            </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <?php if (!empty($schedule['description'])): ?>
                                        <p class="text-sm text-gray-600 mb-2 line-clamp-2"><?php echo htmlspecialchars($schedule['description']); ?></p>
                                    <?php endif; ?>
                                    
                                    <div class="flex justify-between text-xs">
                                        <div class="flex items-center text-gray-500">
                                            <i class="fas fa-calendar-day mr-1"></i>
                                            <?php 
                                            $start_date = date('M d', strtotime($schedule['start_date']));
                                            $end_date = date('M d', strtotime($schedule['end_date']));
                                            
                                            if ($start_date == $end_date) {
                                                echo $start_date;
                                            } else {
                                                echo $start_date . ' - ' . $end_date;
                                            }
                                            ?>
                                        </div>
                                        <div class="flex items-center text-gray-500">
                                            <i class="fas fa-clock mr-1"></i>
                                            <?php 
                                            echo date('h:i A', strtotime($schedule['start_time'])) . ' - ' . 
                                                 date('h:i A', strtotime($schedule['end_time'])); 
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Learning Resources Card -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 transition duration-300 dashboard-card hover:border-primary-200">
                <div class="border-b border-gray-100 bg-primary-600 py-3 px-5 rounded-t-xl flex justify-between items-center">
                    <h2 class="font-semibold text-white flex items-center">
                        <i class="fas fa-book mr-2 animate-bounce-subtle"></i>
                        Learning Resources
                    </h2>
                    <a href="resources.php" class="text-xs text-white bg-white bg-opacity-20 hover:bg-opacity-30 px-3 py-1 rounded-full transition-all duration-200">
                        View All
                    </a>
                </div>
                <div class="p-4 max-h-[300px] overflow-y-auto scrollbar-thin">
                    <?php 
                    $resources = get_latest_resources(5);
                    if (empty($resources)): 
                    ?>
                        <div class="text-center py-8 text-gray-500">
                            <div class="bg-gray-100 rounded-full w-16 h-16 mx-auto mb-3 flex items-center justify-center">
                                <i class="fas fa-book text-gray-300 text-3xl animate-bounce-subtle"></i>
                            </div>
                            <p>No learning resources available</p>
                            <p class="text-sm text-gray-400 mt-1">Check back later for uploaded resources</p>
                        </div>
                    <?php else: ?>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <?php foreach ($resources as $resource): ?>
                                <?php
                                // Determine icon and color based on file type
                                $icon_class = 'fas fa-file-alt';
                                $bg_color = 'bg-gray-100';
                                $text_color = 'text-gray-700';
                                
                                switch ($resource['file_type']) {
                                    case 'pdf':
                                        $icon_class = 'fas fa-file-pdf';
                                        $bg_color = 'bg-red-100';
                                        $text_color = 'text-red-700';
                                        break;
                                    case 'doc':
                                    case 'docx':
                                        $icon_class = 'fas fa-file-word';
                                        $bg_color = 'bg-blue-100';
                                        $text_color = 'text-blue-700';
                                        break;
                                    case 'ppt':
                                    case 'pptx':
                                        $icon_class = 'fas fa-file-powerpoint';
                                        $bg_color = 'bg-orange-100';
                                        $text_color = 'text-orange-700';
                                        break;
                                    case 'xls':
                                    case 'xlsx':
                                        $icon_class = 'fas fa-file-excel';
                                        $bg_color = 'bg-green-100';
                                        $text_color = 'text-green-700';
                                        break;
                                    case 'zip':
                                        $icon_class = 'fas fa-file-archive';
                                        $bg_color = 'bg-yellow-100';
                                        $text_color = 'text-yellow-700';
                                        break;
                                }
                                ?>
                                <div class="bg-white border border-gray-100 rounded-lg shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden flex flex-col">
                                    <div class="<?php echo $bg_color; ?> <?php echo $text_color; ?> p-3 flex justify-center border-b border-gray-100">
                                        <i class="<?php echo $icon_class; ?> text-xl"></i>
                                    </div>
                                    <div class="p-3 flex-grow">
                                        <h3 class="font-medium text-gray-800 text-sm mb-1 line-clamp-1"><?php echo htmlspecialchars($resource['title']); ?></h3>
                                        <p class="text-xs text-gray-500 mb-2"><?php echo htmlspecialchars($resource['category']); ?></p>
                                        <a href="<?php echo $resource['file_path']; ?>" download class="text-xs text-primary-600 hover:text-primary-800 flex items-center">
                                            <i class="fas fa-download mr-1"></i> Download
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Close the main container -->
    </div>

    <div class="py-10"></div>
    
    <script>
    // Page load animation
    document.addEventListener('DOMContentLoaded', function() {
        // Fade in the body
        setTimeout(() => {
            document.body.style.opacity = "1";
        }, 100);
        
        // Stagger in elements with class .stagger-item
        const staggerItems = document.querySelectorAll('.stagger-item');
        staggerItems.forEach((item, index) => {
            setTimeout(() => {
                item.style.opacity = "1";
                item.classList.add('animate-slide-in-up');
            }, 300 + (index * 150));
        });
        
        // Animate announcement items
        const announcementItems = document.querySelectorAll('.announcement-item');
        announcementItems.forEach((item, index) => {
            setTimeout(() => {
                item.classList.add('animate-slide-in-right');
            }, 500 + (index * 100));
        });
        
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
        
        // Show success alerts with SweetAlert2
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
        
        // Login success message
        if (<?php echo isset($_SESSION['login_success']) && $_SESSION['login_success'] ? 'true' : 'false'; ?>) {
            Swal.fire({
                title: "Welcome Back!",
                text: "Hello, <?php echo $_SESSION["name"]; ?>! You've successfully logged in.",
                icon: "success",
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
            <?php $_SESSION['login_success'] = false; // Reset the flag ?>
        }
        
        // Display remaining sessions warning if low
        <?php if(isset($_SESSION['remaining']) && $_SESSION['remaining'] < 5): ?>
        setTimeout(() => {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                showClass: {
                    popup: 'animate__animated animate__fadeInRight'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutRight'
                }
            });
            
            Toast.fire({
                icon: 'warning',
                title: '<i class="fas fa-exclamation-circle text-yellow-500 mr-2"></i> Low Sessions Remaining',
                html: '<span class="text-sm">You have only <?php echo $_SESSION['remaining']; ?> sessions remaining.</span>'
            });
        }, 3000);
        <?php endif; ?>
    });
    </script>

</body>
</html>