<?php
include '../../includes/navbar_student.php';

// Check if user is logged in
if (!isset($_SESSION['id_number'])) {
    header("Location: ../../index.php");
    exit();
}

$id_number = $_SESSION['id_number'];
$student_points = get_student_points($id_number);
$leaderboard = get_leaderboard();

// Get current semester
$current_semester = get_current_semester();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Points Leaderboard</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Inter font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f9fafb;
            font-family: 'Inter', sans-serif;
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
        .table-row {
            transition: all 0.2s ease;
        }
        .table-row:hover {
            background-color: rgba(243, 244, 246, 0.8);
            transform: scale(1.01);
        }
        .medal-gold {
            background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .medal-silver {
            background: linear-gradient(135deg, #e5e7eb 0%, #9ca3af 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .medal-bronze {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                        <div class="bg-primary-100 p-3 rounded-xl mr-4 shadow-sm">
                            <i class="fas fa-trophy text-primary-600 text-xl"></i>
                        </div>
                        Points Leaderboard
                    </h1>
                    <p class="text-gray-500 mt-2 ml-16 text-lg">View current semester rankings</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <span class="text-sm font-medium text-gray-600 bg-gray-100 px-4 py-2 rounded-full">
                        <?php echo htmlspecialchars($current_semester['semester'] . ' ' . $current_semester['academic_year']); ?>
                    </span>
                </div>
            </div>
            
            <!-- Breadcrumbs -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm">
                    <li class="inline-flex items-center">
                        <a href="dashboard.php" class="text-gray-500 hover:text-primary-600 flex items-center">
                            <i class="fas fa-home mr-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-primary-600 font-medium">Leaderboard</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Leaderboard Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 card">
            <div class="p-6">
                <?php if (empty($leaderboard)): ?>
                    <div class="text-center py-12">
                        <div class="bg-gray-50 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                            <i class="fas fa-trophy text-gray-300 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Rankings Available</h3>
                        <p class="text-gray-500">Start attending lab sessions to earn points and appear on the leaderboard!</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Points</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sessions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($leaderboard as $index => $student): ?>
                                    <tr class="table-row <?php echo $index < 3 ? 'bg-gray-50' : ''; ?>">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <?php if ($index < 3): ?>
                                                    <span class="text-2xl mr-3
                                                        <?php echo $index === 0 ? 'medal-gold' : 
                                                            ($index === 1 ? 'medal-silver' : 'medal-bronze'); ?>">
                                                        <i class="fas fa-medal"></i>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-lg font-medium text-gray-500 ml-2"><?php echo $index + 1; ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center">
                                                        <i class="fas fa-user text-primary-600"></i>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?php echo htmlspecialchars($student['name']); ?>
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        <?php echo htmlspecialchars($student['id_number']); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo htmlspecialchars($student['course']); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-primary-100 text-primary-800">
                                                <?php echo $student['points']; ?> pts
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo floor($student['points'] / 3); ?> sessions
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- How to Earn Points Section -->
        <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-100 card">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">How to Earn Points</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <div class="bg-primary-100 p-2 rounded-lg mr-3">
                                <i class="fas fa-clock text-primary-600"></i>
                            </div>
                            <h3 class="font-medium text-gray-800">Regular Attendance</h3>
                        </div>
                        <p class="text-sm text-gray-600">Earn 3 points for each lab session you attend.</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <div class="bg-primary-100 p-2 rounded-lg mr-3">
                                <i class="fas fa-star text-primary-600"></i>
                            </div>
                            <h3 class="font-medium text-gray-800">Active Participation</h3>
                        </div>
                        <p class="text-sm text-gray-600">Get bonus points for actively participating in lab activities.</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <div class="bg-primary-100 p-2 rounded-lg mr-3">
                                <i class="fas fa-calendar-check text-primary-600"></i>
                            </div>
                            <h3 class="font-medium text-gray-800">Consistent Attendance</h3>
                        </div>
                        <p class="text-sm text-gray-600">Maintain regular attendance to climb the leaderboard.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 