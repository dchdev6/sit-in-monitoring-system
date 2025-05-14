<?php
include '../../includes/navbar_admin.php';

$announce = view_announcement();
$feedback = view_feedback();

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
    <meta name="description" content="Admin Dashboard for Student Programming Lab Management">
    <title>Admin Dashboard</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            transition: all 0.3s ease;
            border-radius: 1rem;
            border: 1px solid rgba(229, 231, 235, 0.5);
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
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
        .card-header {
            border-bottom: 1px solid rgba(243, 244, 246, 0.8);
            padding: 1.25rem 1.5rem;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 1rem 1rem 0 0;
        }
        .card-body {
            padding: 1.5rem;
        }
        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-spin {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .stat-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .stat-icon {
            transition: all 0.3s ease;
        }
        .stat-card:hover .stat-icon {
            transform: scale(1.1);
        }
        .btn-primary {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            transition: all 0.3s ease;
        }
        .btn-danger:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .announcement-card {
            transition: all 0.3s ease;
        }
        .announcement-card:hover {
            transform: translateX(5px);
            background: rgba(243, 244, 246, 0.8);
        }
        .quick-action-btn {
            transition: all 0.3s ease;
        }
        .quick-action-btn:hover {
            transform: scale(1.1);
        }
        .table-row {
            transition: all 0.2s ease;
        }
        .table-row:hover {
            background-color: rgba(243, 244, 246, 0.8);
            transform: scale(1.01);
        }
    </style>
</head>

<body class="font-sans text-gray-800 transition-opacity duration-300 opacity-0">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                        <div class="bg-primary-100 p-3 rounded-xl mr-4 shadow-sm">
                            <i class="fas fa-gauge-high text-primary-600 text-xl"></i>
                        </div>
                        Dashboard Overview
                    </h1>
                    <p class="text-gray-500 mt-2 ml-16 text-lg">Monitor system activities and key metrics</p>
                </div>
                <div class="flex space-x-3 mt-4 md:mt-0">
                    <button id="refreshButton" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-2.5 px-4 rounded-lg transition duration-300 flex items-center shadow-sm hover:shadow-md">
                        <i class="fas fa-sync-alt mr-2 text-gray-500"></i>
                        Refresh Data
                    </button>
                </div>
            </div>
            
            <!-- Breadcrumbs -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm">
                    <li class="inline-flex items-center">
                        <span class="text-primary-600 font-medium flex items-center">
                            <i class="fas fa-home mr-2"></i>
                            Dashboard
                        </span>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Quick Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Students -->
            <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Students</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1"><?php echo retrieve_students_dashboard(); ?></h3>
                    </div>
                    <div class="stat-icon bg-primary-100 p-3 rounded-lg">
                        <i class="fas fa-user-graduate text-primary-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Current Sit-ins -->
            <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Current Sit-ins</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1"><?php echo retrieve_current_sit_in_dashboard(); ?></h3>
                    </div>
                    <div class="stat-icon bg-green-100 p-3 rounded-lg">
                        <i class="fas fa-users text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Sit-ins -->
            <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Sit-ins</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1"><?php echo retrieve_total_sit_in_dashboard(); ?></h3>
                    </div>
                    <div class="stat-icon bg-blue-100 p-3 rounded-lg">
                        <i class="fas fa-clipboard-list text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Active Labs -->
            <div class="stat-card bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Active Labs</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">6</h3>
                    </div>
                    <div class="stat-icon bg-purple-100 p-3 rounded-lg">
                        <i class="fas fa-laptop-code text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Announcements Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Combined Charts Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 card">
                <div class="card-header flex items-center">
                    <i class="fas fa-chart-pie text-primary-600 mr-2"></i>
                    <h2 class="font-semibold text-gray-800">Student Statistics</h2>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Programming Languages Chart -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-600 mb-3 flex items-center">
                                <i class="fas fa-code text-primary-600 mr-2"></i>
                                Programming Languages
                            </h3>
                            <canvas id="programmingLanguagesChart" class="max-h-64"></canvas>
                        </div>
                        <!-- Students Year Level Chart -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-600 mb-3 flex items-center">
                                <i class="fas fa-chalkboard-user text-primary-600 mr-2"></i>
                                Students by Year Level
                            </h3>
                            <canvas id="studentYearLevelChart" class="max-h-64"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Announcement Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 card">
                <div class="card-header flex justify-between items-center">
                    <div class="flex items-center">
                        <i class="fas fa-bullhorn text-primary-600 mr-2"></i>
                        <h2 class="font-semibold text-gray-800">Announcements</h2>
                    </div>
                    <button type="button" id="newAnnouncementBtn" class="text-sm bg-primary-600 hover:bg-primary-700 text-white py-1.5 px-3 rounded-md transition duration-200 flex items-center">
                        <i class="fas fa-plus mr-1.5 text-xs"></i> New
                    </button>
                </div>
                
                <!-- New Announcement Form (Initially Hidden) -->
                <div id="announcementForm" class="hidden p-4 bg-gray-50 border-b border-gray-100 fade-in">
                    <form action="admin.php" method="POST" class="space-y-3" id="announcement-form">
                        <div>
                            <label for="an" class="block text-sm font-medium text-gray-700 mb-1">Announcement Message</label>
                            <textarea name="announcement_text" id="an" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" rows="3" placeholder="Type your announcement here..."></textarea>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="button" id="cancelAnnouncement" class="bg-white border border-gray-300 text-gray-700 py-1.5 px-3 rounded-md hover:bg-gray-50 transition duration-200 text-sm">Cancel</button>
                            <button type="submit" name="post_announcement" class="bg-primary-600 hover:bg-primary-700 text-white py-1.5 px-3 rounded-md transition duration-200 text-sm">Post</button>
                        </div>
                    </form>
                </div>
                
                <!-- Announcements List -->
                <div class="card-body pt-2">
                    <div class="max-h-[600px] overflow-y-auto pr-2 space-y-3 scrollbar-thin">
                        <?php if (empty($announce)): ?>
                            <div class="text-center py-6 text-gray-500">
                                <div class="bg-gray-50 rounded-full w-12 h-12 mx-auto mb-3 flex items-center justify-center">
                                    <i class="fas fa-bullhorn text-gray-300 text-xl"></i>
                                </div>
                                <p class="text-sm">No announcements posted yet</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($announce as $index => $row): ?>
                                <div class="announcement-card bg-gray-50 rounded-lg p-3 relative group">
                                    <div class="absolute top-3 right-3">
                                        <button type="button" class="text-gray-400 hover:text-red-500 transition-colors p-1" 
                                                onclick="confirmDeleteAnnouncement(<?php echo $row['announce_id']; ?>)">
                                            <i class="fas fa-trash-alt text-sm"></i>
                                        </button>
                                    </div>
                                    <div class="flex items-center mb-2">
                                        <div class="w-8 h-8 rounded-full bg-primary-500 flex items-center justify-center text-white mr-3">
                                            <i class="fas fa-user-tie text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800 text-sm"><?php echo htmlspecialchars($row['admin_name']); ?></p>
                                            <p class="text-xs text-gray-500"><?php echo htmlspecialchars($row['date']); ?></p>
                                        </div>
                                    </div>
                                    <p class="text-gray-700 text-sm ml-11"><?php echo htmlspecialchars($row['message']); ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Points Leaderboard Section -->
        <div class="mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 card">
                <div class="card-header flex justify-between items-center">
                    <div class="flex items-center">
                        <i class="fas fa-trophy text-primary-600 mr-2"></i>
                        <h2 class="font-semibold text-gray-800">Points Leaderboard</h2>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">
                            <?php 
                            $current_semester = get_current_semester();
                            echo htmlspecialchars($current_semester['semester'] . ' ' . $current_semester['academic_year']); 
                            ?>
                        </span>
                        <button id="endSemesterBtn" class="btn-danger text-sm text-white py-1.5 px-3 rounded-md flex items-center">
                            <i class="fas fa-calendar-times mr-1.5 text-xs"></i> End Semester
                        </button>
                        <button id="resetPointsBtn" class="btn-danger text-sm text-white py-1.5 px-3 rounded-md flex items-center">
                            <i class="fas fa-undo mr-1.5 text-xs"></i> Reset Points
                        </button>
                    </div>
                </div>
                <div class="card-body pt-2">
                    <div class="max-h-96 overflow-y-auto pr-2 scrollbar-thin">
                        <?php 
                        $leaderboard = get_leaderboard();
                        if (empty($leaderboard)): 
                        ?>
                            <div class="text-center py-6 text-gray-500">
                                <div class="bg-gray-50 rounded-full w-12 h-12 mx-auto mb-3 flex items-center justify-center">
                                    <i class="fas fa-trophy text-gray-300 text-xl"></i>
                                </div>
                                <p class="text-sm">No points data available for this semester</p>
                            </div>
                        <?php else: ?>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Points</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sessions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($leaderboard as $index => $student): ?>
                                        <tr class="table-row">
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <?php if ($index < 3): ?>
                                                        <span class="w-6 h-6 rounded-full flex items-center justify-center mr-2
                                                            <?php echo $index === 0 ? 'bg-yellow-100 text-yellow-800' : 
                                                                ($index === 1 ? 'bg-gray-100 text-gray-800' : 'bg-orange-100 text-orange-800'); ?>">
                                                            <i class="fas fa-medal text-xs"></i>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-sm text-gray-500 ml-2"><?php echo $index + 1; ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?php echo htmlspecialchars($student['name']); ?>
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    <?php echo htmlspecialchars($student['id_number']); ?>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                <?php echo htmlspecialchars($student['course']); ?>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-primary-100 text-primary-800">
                                                    <?php echo $student['points']; ?> pts
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                <?php echo floor($student['points'] / 3); ?> sessions
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lab Management Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Upcoming Schedules Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 card">
                <div class="card-header flex justify-between items-center">
                    <div class="flex items-center">
                        <i class="fas fa-calendar-alt text-primary-600 mr-2"></i>
                        <h2 class="font-semibold text-gray-800">Upcoming Lab Schedules</h2>
                    </div>
                    <a href="schedules.php" class="text-sm text-primary-600 hover:text-primary-800 flex items-center">
                        View All <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </div>
                <div class="card-body pt-2">
                    <div class="max-h-72 overflow-y-auto pr-2 scrollbar-thin">
                        <?php 
                        $upcoming_schedules = get_upcoming_schedules(5);
                        if (empty($upcoming_schedules)): 
                        ?>
                            <div class="text-center py-6 text-gray-500">
                                <div class="bg-gray-50 rounded-full w-12 h-12 mx-auto mb-3 flex items-center justify-center">
                                    <i class="fas fa-calendar text-gray-300 text-xl"></i>
                                </div>
                                <p class="text-sm">No upcoming schedules</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($upcoming_schedules as $schedule): ?>
                                <div class="py-3 border-b border-gray-100 last:border-0">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-medium text-gray-800 text-sm"><?php echo htmlspecialchars($schedule['title']); ?></h3>
                                            <p class="text-xs text-gray-500">
                                                <i class="fas fa-map-marker-alt mr-1"></i> Lab <?php echo htmlspecialchars($schedule['lab']); ?>
                                                <?php if(isset($schedule['resource']) && !empty($schedule['resource'])): ?>
                                                <span class="bg-green-100 text-green-800 text-xs font-medium px-1.5 py-0.5 rounded ml-1">
                                                    <?php echo htmlspecialchars($schedule['resource']); ?>
                                                </span>
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <?php 
                                            $start_date = date('M d', strtotime($schedule['start_date']));
                                            $start_time = date('h:i A', strtotime($schedule['start_time']));
                                            ?>
                                            <span class="text-xs font-medium text-primary-700 bg-primary-50 rounded-full px-2 py-1 inline-block">
                                                <?php echo $start_date; ?>
                                            </span>
                                            <p class="text-xs text-gray-500 mt-1"><?php echo $start_time; ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Latest Resources Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 card">
                <div class="card-header flex justify-between items-center">
                    <div class="flex items-center">
                        <i class="fas fa-file-alt text-primary-600 mr-2"></i>
                        <h2 class="font-semibold text-gray-800">Latest Learning Resources</h2>
                    </div>
                    <a href="resources.php" class="text-sm text-primary-600 hover:text-primary-800 flex items-center">
                        View All <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </div>
                <div class="card-body pt-2">
                    <div class="max-h-72 overflow-y-auto pr-2 scrollbar-thin">
                        <?php 
                        $latest_resources = get_latest_resources(5);
                        if (empty($latest_resources)): 
                        ?>
                            <div class="text-center py-6 text-gray-500">
                                <div class="bg-gray-50 rounded-full w-12 h-12 mx-auto mb-3 flex items-center justify-center">
                                    <i class="fas fa-file text-gray-300 text-xl"></i>
                                </div>
                                <p class="text-sm">No resources uploaded yet</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($latest_resources as $resource): ?>
                                <div class="py-3 border-b border-gray-100 last:border-0">
                                    <div class="flex justify-between items-start">
                                        <div class="flex items-start">
                                            <?php
                                            $icon_class = 'fas fa-file-alt';
                                            $icon_color = 'text-gray-500';
                                            
                                            switch ($resource['file_type']) {
                                                case 'pdf':
                                                    $icon_class = 'fas fa-file-pdf';
                                                    $icon_color = 'text-red-500';
                                                    break;
                                                case 'doc':
                                                case 'docx':
                                                    $icon_class = 'fas fa-file-word';
                                                    $icon_color = 'text-blue-500';
                                                    break;
                                                case 'ppt':
                                                case 'pptx':
                                                    $icon_class = 'fas fa-file-powerpoint';
                                                    $icon_color = 'text-orange-500';
                                                    break;
                                                case 'xls':
                                                case 'xlsx':
                                                    $icon_class = 'fas fa-file-excel';
                                                    $icon_color = 'text-green-500';
                                                    break;
                                                case 'zip':
                                                    $icon_class = 'fas fa-file-archive';
                                                    $icon_color = 'text-yellow-500';
                                                    break;
                                            }
                                            ?>
                                            <span class="bg-gray-50 p-2 rounded-lg mr-3 flex-shrink-0">
                                                <i class="<?php echo $icon_class . ' ' . $icon_color; ?>"></i>
                                            </span>
                                            <div>
                                                <h3 class="font-medium text-gray-800 text-sm"><?php echo htmlspecialchars($resource['title']); ?></h3>
                                                <p class="text-xs text-gray-500"><?php echo htmlspecialchars($resource['category']); ?></p>
                                            </div>
                                        </div>
                                        <div class="text-right text-xs text-gray-500">
                                            <?php echo date('M d', strtotime($resource['created_at'])); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Quick Action Button -->
    <div class="fixed bottom-6 right-6">
        <button id="quickActionBtn" class="quick-action-btn bg-primary-600 hover:bg-primary-700 w-12 h-12 rounded-full shadow-lg flex items-center justify-center text-white transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-300" aria-label="Quick actions menu">
            <i class="fas fa-plus"></i>
        </button>
        
        <!-- Quick Actions Menu -->
        <div id="quickActionMenu" class="absolute bottom-14 right-0 bg-white rounded-lg shadow-xl border border-gray-100 w-48 py-1 opacity-0 invisible transition-all duration-200 transform translate-y-2 z-10" role="menu">
            <a href="students.php" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors" role="menuitem">
                <i class="fas fa-user-graduate mr-3 text-primary-500 w-5"></i>
                Manage Students
            </a>
            <a href="viewrecords.php" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors" role="menuitem">
                <i class="fas fa-clipboard-list mr-3 text-primary-500 w-5"></i>
                View Records
            </a>
            <a href="report.php" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors" role="menuitem">
                <i class="fas fa-chart-bar mr-3 text-primary-500 w-5"></i>
                Generate Reports
            </a>
            <a href="schedules.php" class="flex items-center px-4 py-4 text-sm text-gray-700 hover:bg-gray-50 transition-colors" role="menuitem">
                <i class="fas fa-calendar-alt mr-3 text-primary-500 w-5"></i>
                Lab Schedules
            </a>
            <a href="resources.php" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors" role="menuitem">
                <i class="fas fa-file-upload mr-3 text-primary-500 w-5"></i>
                Learning Resources
            </a>
            <button id="quickNewAnnouncement" class="flex w-full items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors" role="menuitem">
                <i class="fas fa-bullhorn mr-3 text-primary-500 w-5"></i>
                New Announcement
            </button>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Page load animation
        document.addEventListener('DOMContentLoaded', function() {
            // Fade in the body
            setTimeout(() => {
                document.body.style.opacity = "1";
            }, 100);

            // Programming Languages Chart
            const programmingLanguagesCtx = document.getElementById('programmingLanguagesChart');
            if (programmingLanguagesCtx) {
                const programmingLanguagesChart = new Chart(programmingLanguagesCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['C#', 'C', 'Java', 'ASP.Net', 'PHP'],
                        datasets: [{
                            label: 'Programming Languages',
                            data: [
                                <?php echo retrieve_c_sharp_programming(); ?>,
                                <?php echo retrieve_c_programming(); ?>,
                                <?php echo retrieve_java_programming(); ?>,
                                <?php echo retrieve_asp_programming(); ?>,
                                <?php echo retrieve_php_programming(); ?>
                            ],
                            backgroundColor: [
                                '#3b82f6',
                                '#10b981',
                                '#f59e0b',
                                '#6366f1',
                                '#ec4899'
                            ],
                            borderWidth: 1,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 15,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    boxWidth: 8
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                callbacks: {
                                    label: function (context) {
                                        const label = context.label || '';
                                        const value = context.formattedValue;
                                        const total = context.chart.data.datasets[0].data.reduce((sum, val) => sum + val, 0);
                                        const percentage = Math.round((context.raw / total) * 100);
                                        return `${label}: ${value} (${percentage}%)`;
                                    }
                                }
                            }
                        },
                        animation: {
                            duration: 1000
                        }
                    }
                });
            }

            // Students by Year Level Chart
            const studentYearLevelCtx = document.getElementById('studentYearLevelChart');
            if (studentYearLevelCtx) {
                const studentYearLevelChart = new Chart(studentYearLevelCtx, {
                    type: 'polarArea',
                    data: {
                        labels: ['Freshmen', 'Sophomore', 'Junior', 'Senior'],
                        datasets: [{
                            label: 'Number of Students',
                            data: [
                                <?php echo retrieve_first(); ?>, 
                                <?php echo retrieve_second(); ?>,
                                <?php echo retrieve_third(); ?>,
                                <?php echo retrieve_fourth(); ?>
                            ],
                            backgroundColor: [
                                'rgba(59, 130, 246, 0.7)',
                                'rgba(16, 185, 129, 0.7)',
                                'rgba(245, 158, 11, 0.7)',
                                'rgba(99, 102, 241, 0.7)'
                            ],
                            borderColor: [
                                'rgb(59, 130, 246)',
                                'rgb(16, 185, 129)',
                                'rgb(245, 158, 11)',
                                'rgb(99, 102, 241)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 15,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    boxWidth: 8
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                callbacks: {
                                    label: function (context) {
                                        return `Students: ${context.raw}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            r: {
                                beginAtZero: true,
                                ticks: {
                                    display: false
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                }
                            }
                        },
                        animation: {
                            duration: 1000
                        }
                    }
                });
            }
        });
        
        // Show success alerts with SweetAlert2
        <?php if(!empty($successMessage)): ?>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '<?php echo $successMessage; ?>',
            confirmButtonColor: '#0284c7',
            timer: 3000,
            timerProgressBar: true
        });
        <?php endif; ?>
        
        // Form submission validation
        document.getElementById('announcement-form').addEventListener('submit', function(e) {
            const textarea = document.getElementById('an');
            
            if (textarea.value.trim() === '') {
                e.preventDefault(); // Prevent form submission
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please enter an announcement message!',
                    confirmButtonColor: '#0284c7'
                });
            }
        });
        
        // Function to confirm deletion of announcement
        function confirmDeleteAnnouncement(id) {
            Swal.fire({
                title: 'Delete Announcement?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'delete_announcement.php?id=' + id;
                }
            });
        }
        
        // Toggle announcement form
        document.getElementById('newAnnouncementBtn').addEventListener('click', function() {
            const form = document.getElementById('announcementForm');
            form.classList.toggle('hidden');
        });
        
        document.getElementById('cancelAnnouncement').addEventListener('click', function() {
            document.getElementById('announcementForm').classList.add('hidden');
        });
    
        // Quick actions menu toggle
        document.getElementById('quickActionBtn').addEventListener('click', function() {
            const menu = document.getElementById('quickActionMenu');
            
            if (menu.classList.contains('invisible')) {
                // Show menu
                menu.classList.remove('invisible', 'opacity-0', 'translate-y-2');
                menu.classList.add('opacity-100', 'translate-y-0');
                this.innerHTML = '<i class="fas fa-times"></i>';
                this.setAttribute('aria-expanded', 'true');
            } else {
                // Hide menu
                menu.classList.add('invisible', 'opacity-0', 'translate-y-2');
                menu.classList.remove('opacity-100', 'translate-y-0');
                this.innerHTML = '<i class="fas fa-plus"></i>';
                this.setAttribute('aria-expanded', 'false');
            }
        });

        document.getElementById('quickNewAnnouncement').addEventListener('click', function() {
            // Hide the menu
            document.getElementById('quickActionMenu').classList.add('invisible', 'opacity-0', 'translate-y-2');
            document.getElementById('quickActionMenu').classList.remove('opacity-100', 'translate-y-0');
            document.getElementById('quickActionBtn').innerHTML = '<i class="fas fa-plus"></i>';
            
            // Show the announcement form
            const form = document.getElementById('announcementForm');
            form.classList.remove('hidden');
            document.getElementById('an').focus();
            
            // Scroll to the form if needed
            const rect = form.getBoundingClientRect();
            if (rect.top < 0 || rect.bottom > window.innerHeight) {
                form.scrollIntoView({ behavior: 'smooth' });
            }
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

        // Reset Points functionality
        document.getElementById('resetPointsBtn').addEventListener('click', function() {
            Swal.fire({
                title: 'Reset All Points?',
                text: 'This will reset all student points to 0. This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, reset all points',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send AJAX request to reset points
                    fetch('reset_points.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'All points have been reset successfully.',
                                icon: 'success',
                                confirmButtonColor: '#0284c7'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: data.message || 'Failed to reset points. Please try again.',
                                icon: 'error',
                                confirmButtonColor: '#0284c7'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while resetting points. Please try again.',
                            icon: 'error',
                            confirmButtonColor: '#0284c7'
                        });
                    });
                }
            });
        });

        // Enhanced End Semester Modal functionality
        document.addEventListener('DOMContentLoaded', function() {
            const endSemesterModal = document.getElementById('endSemesterModal');
            const modalContent = document.getElementById('modalContent');
            const endSemesterBtn = document.getElementById('endSemesterBtn');
            const closeEndSemester = document.getElementById('closeEndSemester');
            const cancelEndSemester = document.getElementById('cancelEndSemester');
            const endSemesterForm = document.getElementById('endSemesterForm');
            const newAcademicYear = document.getElementById('newAcademicYear');

            // Debug logging
            console.log('Modal elements:', {
                endSemesterModal,
                modalContent,
                endSemesterBtn,
                closeEndSemester,
                cancelEndSemester,
                endSemesterForm,
                newAcademicYear
            });

            function showModal() {
                console.log('Showing modal...');
                endSemesterModal.classList.remove('hidden');
                // Trigger reflow
                endSemesterModal.offsetHeight;
                requestAnimationFrame(() => {
                    endSemesterModal.classList.add('opacity-100');
                    modalContent.classList.remove('scale-95', 'opacity-0');
                    modalContent.classList.add('scale-100', 'opacity-100');
                });
            }

            function hideModal() {
                console.log('Hiding modal...');
                endSemesterModal.classList.remove('opacity-100');
                modalContent.classList.remove('scale-100', 'opacity-100');
                modalContent.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    endSemesterModal.classList.add('hidden');
                }, 300);
            }

            // Ensure the button exists before adding event listener
            if (endSemesterBtn) {
                endSemesterBtn.addEventListener('click', function(e) {
                    console.log('End Semester button clicked');
                    e.preventDefault();
                    showModal();
                });
            } else {
                console.error('End Semester button not found!');
            }

            if (closeEndSemester) {
                closeEndSemester.addEventListener('click', hideModal);
            }

            if (cancelEndSemester) {
                cancelEndSemester.addEventListener('click', hideModal);
            }

            // Close modal when clicking outside
            if (endSemesterModal) {
                endSemesterModal.addEventListener('click', (e) => {
                    if (e.target === endSemesterModal) {
                        hideModal();
                    }
                });
            }

            // Academic year input formatting
            if (newAcademicYear) {
                newAcademicYear.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length > 4) {
                        value = value.slice(0, 4) + '-' + value.slice(4, 8);
                    }
                    e.target.value = value;
                });
            }

            // Form submission with enhanced validation
            if (endSemesterForm) {
                endSemesterForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    console.log('Form submitted');
                    
                    const semester = document.getElementById('newSemester').value;
                    const academicYear = newAcademicYear.value;
                    
                    // Validate academic year format
                    if (!/^\d{4}-\d{4}$/.test(academicYear)) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Academic Year',
                            text: 'Please enter the academic year in the format YYYY-YYYY (e.g., 2024-2025)',
                            confirmButtonColor: '#0284c7'
                        });
                        return;
                    }
                    
                    // Show confirmation dialog with custom styling
                    Swal.fire({
                        title: 'End Current Semester?',
                        html: `
                            <div class="text-left">
                                <p class="mb-2">This action will:</p>
                                <ul class="list-disc list-inside text-sm text-gray-600 mb-4">
                                    <li>Archive all current semester points</li>
                                    <li>Reset all student points to 0</li>
                                    <li>Start new semester: <strong>${semester} ${academicYear}</strong></li>
                                </ul>
                                <p class="text-red-600 text-sm"><i class="fas fa-exclamation-triangle mr-1"></i> This action cannot be undone.</p>
                            </div>
                        `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Yes, end semester',
                        cancelButtonText: 'Cancel',
                        customClass: {
                            popup: 'rounded-lg',
                            confirmButton: 'rounded-md',
                            cancelButton: 'rounded-md'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading state
                            Swal.fire({
                                title: 'Processing...',
                                html: 'Please wait while we end the semester',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            // Send AJAX request
                            fetch('end_semester.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify({
                                    semester: semester,
                                    academic_year: academicYear
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Semester has been ended and new semester has been set.',
                                        icon: 'success',
                                        confirmButtonColor: '#0284c7'
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: data.message || 'Failed to end semester. Please try again.',
                                        icon: 'error',
                                        confirmButtonColor: '#0284c7'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'An error occurred while ending the semester. Please try again.',
                                    icon: 'error',
                                    confirmButtonColor: '#0284c7'
                                });
                            });
                        }
                    });
                });
            }
        });
    </script>

    <!-- End Semester Modal -->
    <div id="endSemesterModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm hidden overflow-y-auto h-full w-full z-50 transition-opacity duration-300">
        <div class="relative top-20 mx-auto p-6 border w-[480px] shadow-2xl rounded-xl bg-white transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
            <div class="space-y-6">
                <!-- Header -->
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-primary-50 rounded-lg">
                            <i class="fas fa-calendar-times text-primary-600 text-lg"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">End Current Semester</h3>
                    </div>
                    <button type="button" id="closeEndSemester" class="text-gray-400 hover:text-gray-500 focus:outline-none transition-colors duration-200">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <!-- Warning Alert -->
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-amber-500 text-lg"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-amber-800 mb-1">Important Notice</h4>
                            <p class="text-sm text-amber-700">
                                This action will archive current points, reset all student points to 0, and start a new semester.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <form id="endSemesterForm" class="space-y-5">
                    <div>
                        <label for="newSemester" class="block text-sm font-medium text-gray-700 mb-1.5">New Semester</label>
                        <div class="relative">
                            <select id="newSemester" name="semester" 
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 bg-white pl-4 pr-10 py-2.5 text-gray-900 appearance-none">
                                <option value="First Semester">First Semester</option>
                                <option value="Second Semester">Second Semester</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="newAcademicYear" class="block text-sm font-medium text-gray-700 mb-1.5">Academic Year</label>
                        <div class="relative">
                            <input type="text" id="newAcademicYear" name="academic_year" 
                                   placeholder="e.g., 2024-2025" 
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 bg-white pl-4 pr-10 py-2.5 text-gray-900"
                                   pattern="\d{4}-\d{4}"
                                   title="Please enter in format YYYY-YYYY">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-calendar-alt text-gray-400"></i>
                            </div>
                        </div>
                        <p class="mt-1.5 text-xs text-gray-500">Format: YYYY-YYYY (e.g., 2024-2025)</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                        <button type="button" id="cancelEndSemester" 
                                class="px-4 py-2.5 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200 font-medium text-sm">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2.5 rounded-lg bg-primary-600 text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200 font-medium text-sm flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            End Semester
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>