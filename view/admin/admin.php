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
        /* Enhanced card animations */
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
        .stagger-item {
            opacity: 0;
        }
        .chart-container {
            position: relative;
            overflow: hidden;
        }
        .chart-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0) 100%);
            z-index: 10;
            animation: revealChart 1.5s ease-out forwards;
        }
        @keyframes revealChart {
            0% { left: 0; }
            100% { left: 100%; }
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
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6" data-aos="fade-down">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                        <div class="bg-primary-100 p-2 rounded-lg mr-3 shadow-sm">
                            <i class="fas fa-gauge-high text-primary-600"></i>
                        </div>
                        Dashboard Overview
                    </h1>
                    <p class="text-gray-500 mt-1 ml-12">Monitor system activities and key metrics</p>
                </div>
                
                <!-- No buttons needed in the dashboard header -->
            </div>
            
            <!-- No breadcrumbs needed for admin dashboard since it's the main page -->
        </div>

        <!-- Charts & Announcements Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Programming Languages Chart -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 md:col-span-1 transition duration-300 hover:border-primary-200 hover-scale stagger-item">
                <div class="border-b border-gray-100 px-6 py-4 flex justify-between items-center">
                    <h2 class="font-semibold text-gray-800">
                        <i class="fas fa-code text-primary-600 mr-2 animate-bounce-subtle"></i>
                        Programming Languages
                    </h2>
                </div>
                <div class="p-6 chart-container">
                    <canvas id="programmingLanguagesChart" class="max-h-80"></canvas>
                </div>
            </div>

            <!-- Announcement Card -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 md:col-span-2 transition duration-300 hover:border-primary-200 hover-scale stagger-item">
                <div class="border-b border-gray-100 px-6 py-4 flex justify-between items-center">
                    <h2 class="font-semibold text-gray-800">
                        <i class="fas fa-bullhorn text-primary-600 mr-2 animate-bounce-subtle"></i>
                        Announcements
                    </h2>
                    <button type="button" id="newAnnouncementBtn" class="text-sm bg-primary-600 hover:bg-primary-700 text-white py-2 px-4 rounded-lg transition duration-300 flex items-center shadow-sm">
                        <i class="fas fa-plus mr-2"></i> New
                    </button>
                </div>
                
                <!-- New Announcement Form (Initially Hidden) -->
                <div id="announcementForm" class="hidden p-5 bg-gray-50 border-b border-gray-100 animate-fade-in">
                    <form action="admin.php" method="POST" class="space-y-4" id="announcement-form">
                        <div>
                            <label for="an" class="block text-sm font-medium text-gray-700 mb-1">Announcement Message</label>
                            <textarea name="announcement_text" id="an" class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" rows="3" placeholder="Type your announcement here..."></textarea>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="button" id="cancelAnnouncement" class="bg-white border border-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-50 transition duration-300">Cancel</button>
                            <button type="submit" name="post_announcement" class="bg-primary-600 hover:bg-primary-700 text-white py-2 px-4 rounded-lg transition duration-300 shadow-sm">Post Announcement</button>
                        </div>
                    </form>
                </div>
                
                <!-- Announcements List -->
                <div class="p-5">
                    <div class="max-h-96 overflow-y-auto pr-2 space-y-4 scrollbar-thin announcement-container">
                        <?php if (empty($announce)): ?>
                            <div class="text-center py-8 text-gray-500">
                                <div class="bg-gray-100 rounded-full w-16 h-16 mx-auto mb-3 flex items-center justify-center">
                                    <i class="fas fa-bullhorn text-gray-300 text-3xl animate-bounce-subtle"></i>
                                </div>
                                <p>No announcements posted yet</p>
                                <p class="text-sm text-gray-400 mt-1">Create your first announcement to keep everyone informed</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($announce as $index => $row): ?>
                                <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-all duration-300 announcement-item relative group" style="transition-delay: <?php echo $index * 100; ?>ms">
                                    <div class="absolute top-3 right-3 transition-opacity">
                                        <button type="button" class="text-gray-400 hover:text-red-500 transition-colors p-1" 
                                                onclick="confirmDeleteAnnouncement(<?php echo $row['announcement_id']; ?>)">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                    <div class="flex items-center mb-2">
                                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white mr-3 shadow-sm">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($row['admin_name']); ?></p>
                                            <p class="text-xs text-gray-500"><?php echo htmlspecialchars($row['date']); ?></p>
                                        </div>
                                    </div>
                                    <p class="text-gray-700 ml-12"><?php echo htmlspecialchars($row['message']); ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students Year Level Card -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 transition duration-300 hover:border-primary-200 hover-scale stagger-item">
            <div class="border-b border-gray-100 px-6 py-4">
                <h2 class="font-semibold text-gray-800">
                    <i class="fas fa-chalkboard-user text-primary-600 mr-2 animate-bounce-subtle"></i>
                    College of Computer Studies - Students by Year Level
                </h2>
            </div>
            <div class="p-6 chart-container">
                <canvas id="studentYearLevelChart" class="max-h-96"></canvas>
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
            <a href="manage_students.php" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-primary-50 transition-colors" role="menuitem">
                <i class="fas fa-user-graduate mr-3 text-primary-500 w-5"></i>
                Manage Students
            </a>
            <a href="sit_in_records.php" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-primary-50 transition-colors" role="menuitem">
                <i class="fas fa-clipboard-list mr-3 text-primary-500 w-5"></i>
                View Records
            </a>
            <a href="generate_reports.php" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-primary-50 transition-colors" role="menuitem">
                <i class="fas fa-chart-bar mr-3 text-primary-500 w-5"></i>
                Generate Reports
            </a>
            <button id="quickNewAnnouncement" class="flex w-full items-center px-4 py-2 text-sm text-gray-700 hover:bg-primary-50 transition-colors" role="menuitem">
                <i class="fas fa-bullhorn mr-3 text-primary-500 w-5"></i>
                New Announcement
            </button>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- CountUp.js for animated counters -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/countup.js/2.0.8/countUp.min.js"></script>
    
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
            
            // Animate counters
            const counterElements = document.querySelectorAll('.counter-animate');
            counterElements.forEach(element => {
                const targetValue = parseInt(element.textContent);
                const countUp = new CountUp(element, 0, targetValue, 0, 2.5, {
                    useEasing: true,
                    useGrouping: true,
                    separator: ',',
                });
                
                if (!countUp.error) {
                    setTimeout(() => {
                        countUp.start();
                    }, 500);
                } else {
                    console.error(countUp.error);
                }
            });

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
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 15,
                                    usePointStyle: true,
                                    pointStyle: 'circle'
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                titleFont: {
                                    size: 14
                                },
                                bodyFont: {
                                    size: 13
                                },
                                displayColors: true,
                                boxPadding: 8,
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
                            animateScale: true,
                            animateRotate: true,
                            duration: 2000,
                            easing: 'easeOutBounce'
                        }
                    }
                });
            }

            // Students by Year Level Chart
            const studentYearLevelCtx = document.getElementById('studentYearLevelChart');
            if (studentYearLevelCtx) {
                const studentYearLevelChart = new Chart(studentYearLevelCtx, {
                    type: 'bar',
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
                            borderWidth: 1,
                            borderRadius: 8,
                            maxBarThickness: 70
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    display: true,
                                    drawBorder: false,
                                    color: 'rgba(0, 0, 0, 0.05)'
                                },
                                ticks: {
                                    precision: 0
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                titleFont: {
                                    size: 14
                                },
                                bodyFont: {
                                    size: 13
                                },
                                displayColors: true,
                                callbacks: {
                                    label: function (context) {
                                        return `Students: ${context.raw}`;
                                    }
                                }
                            }
                        },
                        animation: {
                            delay: function (context) {
                                return context.dataIndex * 200;
                            },
                            duration: 1500,
                            easing: 'easeOutQuart'
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
            timerProgressBar: true,
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        });
        <?php endif; ?>
        
        // Form submission without SweetAlert confirmation
        document.getElementById('announcement-form').addEventListener('submit', function(e) {
            const textarea = document.getElementById('an');
            
            if (textarea.value.trim() === '') {
                e.preventDefault(); // Prevent form submission
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please enter an announcement message!',
                    confirmButtonColor: '#0284c7',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
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
                confirmButtonText: 'Yes, delete it!',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to the deletion script
                    window.location.href = `delete_announcement.php?id=${id}`;
                }
            });
        }
        
        // Toggle announcement form with animation
        document.getElementById('newAnnouncementBtn').addEventListener('click', function() {
            const form = document.getElementById('announcementForm');
            
            if (form.classList.contains('hidden')) {
                form.classList.remove('hidden');
                form.classList.add('animate__animated', 'animate__fadeIn');
                document.getElementById('an').focus();
            } else {
                form.classList.add('animate__animated', 'animate__fadeOut');
                setTimeout(() => {
                    form.classList.add('hidden');
                    form.classList.remove('animate__animated', 'animate__fadeOut');
                }, 500);
            }
        });
        
        document.getElementById('cancelAnnouncement').addEventListener('click', function() {
            const form = document.getElementById('announcementForm');
            form.classList.add('animate__animated', 'animate__fadeOut');
            setTimeout(() => {
                form.classList.add('hidden');
                form.classList.remove('animate__animated', 'animate__fadeOut');
            }, 500);
        });
    
        // Add notification system for demo purposes
        setTimeout(() => {
            const toastTypes = [
                { 
                    title: 'New Student Registered', 
                    message: 'A new student has just registered in the system', 
                    icon: 'fas fa-user-plus',
                    color: 'blue'
                },
                { 
                    title: 'System Update', 
                    message: 'The system will undergo maintenance tonight at 2 AM', 
                    icon: 'fas fa-wrench',
                    color: 'yellow'
                },
                { 
                    title: 'Reminder', 
                    message: 'Faculty meeting scheduled for tomorrow at 10 AM', 
                    icon: 'fas fa-bell',
                    color: 'green'
                }
            ];
            
            // Show a random toast notification
            const randomToast = toastTypes[Math.floor(Math.random() * toastTypes.length)];
            
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
                },
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: 'info',
                title: `<i class="${randomToast.icon} text-${randomToast.color}-500 mr-2"></i> ${randomToast.title}`,
                html: `<span class="text-sm">${randomToast.message}</span>`
            });
        }, 5000);  // Show after 5 seconds for demo

        // Add this to your existing JavaScript
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

        document.getElementById('quickNewAnnouncement').addEventListener('click', function() {
            // Hide the menu
            document.getElementById('quickActionMenu').classList.add('invisible', 'opacity-0', 'translate-y-2');
            document.getElementById('quickActionMenu').classList.remove('opacity-100', 'translate-y-0');
            document.getElementById('quickActionBtn').innerHTML = '<i class="fas fa-plus text-xl"></i>';
            
            // Show the announcement form
            const form = document.getElementById('announcementForm');
            form.classList.remove('hidden');
            form.classList.add('animate__animated', 'animate__fadeIn');
            document.getElementById('an').focus();
            
            // Scroll to the form
            form.scrollIntoView({ behavior: 'smooth' });
        });
    </script>
</body>

</html>