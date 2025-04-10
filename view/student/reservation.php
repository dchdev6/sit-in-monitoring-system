<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../includes/navbar_student.php';

// Check if user is logged in
if (!isset($_SESSION['id_number'])) {
    header('Location: ../../Login.php');
    exit;
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
                        success: {
                            50: '#ecfdf5',
                            500: '#10b981',
                            600: '#059669',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'Segoe UI', 'Tahoma', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        .form-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .form-card:hover {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .form-input {
            transition: all 0.3s ease;
        }
        .form-input:focus {
            border-color: #0ea5e9;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.2);
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        .animate-slide-in-up {
            animation: slideInUp 0.5s ease-in-out;
        }
        .stagger-item {
            opacity: 0;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-pulse-slow {
            animation: pulseSlow 3s infinite ease-in-out;
        }
        @keyframes pulseSlow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
    </style>
</head>
<body class="bg-gray-50 font-sans text-gray-800 opacity-0 transition-opacity duration-500">

<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="flex items-center justify-center mb-6 animate-slide-in-up">
        <div class="bg-primary-600 h-10 w-1 rounded mr-3"></div>
        <h1 class="text-2xl font-bold text-gray-800">Reservation Form</h1>
    </div>
    
    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 mb-8 form-card animate-fade-in">
        <div class="border-b border-gray-100 pb-4 mb-6">
            <div class="flex items-center">
                <i class="fas fa-calendar-check text-primary-600 mr-3 text-xl animate-pulse-slow"></i>
                <h2 class="text-lg font-semibold text-gray-800">Book your lab session</h2>
            </div>
            <p class="text-gray-500 text-sm mt-1 ml-8">Fill in the details below to reserve your programming lab time</p>
        </div>

        <!-- ✅ Form submits directly to api_student.php -->
        <form action="../../api/api_student.php" method="POST" id="reservationForm">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                <!-- Left Column -->
                <div class="space-y-4 stagger-item">
                    <div>
                        <label for="id" class="block text-sm font-medium text-gray-700 mb-1">ID Number</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-id-card text-gray-400"></i>
                            </div>
                            <input id="id" name="id_number" type="text" value="<?php echo $_SESSION['id_number'] ?? ''; ?>" readonly class="form-input pl-10 w-full border border-gray-300 rounded-lg py-2.5 px-4 bg-gray-50 text-gray-700 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Student Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input id="name" name="studentName" type="text" value="<?php echo $_SESSION['name'] ?? ''; ?>" readonly class="form-input pl-10 w-full border border-gray-300 rounded-lg py-2.5 px-4 bg-gray-50 text-gray-700 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label for="purposes" class="block text-sm font-medium text-gray-700 mb-1">Purpose</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-code text-gray-400"></i>
                            </div>
                            <select name="purpose" id="purposes" class="form-input pl-10 w-full border border-gray-300 rounded-lg py-2.5 px-4 text-gray-700 focus:outline-none appearance-none" required>
                                <option value="" disabled selected>Select programming language</option>
                                <option value="C Programming">C Programming</option>
                                <option value="Java Programming">Java Programming</option>
                                <option value="C# Programming">C# Programming</option>
                                <option value="Php Programming">PHP Programming</option>
                                <option value="ASP.Net Programming">ASP.NET Programming</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="lab" class="block text-sm font-medium text-gray-700 mb-1">Lab</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-laptop-code text-gray-400"></i>
                            </div>
                            <select name="lab" id="lab" class="form-input pl-10 w-full border border-gray-300 rounded-lg py-2.5 px-4 text-gray-700 focus:outline-none appearance-none" required>
                                <option value="" disabled selected>Select laboratory</option>
                                <option value="524">524</option>
                                <option value="526">526</option>
                                <option value="528">528</option>
                                <option value="530">530</option>
                                <option value="542">542</option>
                                <option value="Mac">Mac Laboratory</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4 stagger-item">
                    <div>
                        <label for="time" class="block text-sm font-medium text-gray-700 mb-1">Time In</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-clock text-gray-400"></i>
                            </div>
                            <input class="form-input pl-10 w-full border border-gray-300 rounded-lg py-2.5 px-4 text-gray-700 focus:outline-none" type="time" id="time" name="time" required>
                        </div>
                    </div>

                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-calendar text-gray-400"></i>
                            </div>
                            <input class="form-input pl-10 w-full border border-gray-300 rounded-lg py-2.5 px-4 text-gray-700 focus:outline-none" type="date" id="date" name="date" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>

                    <div>
                        <label for="remaining" class="block text-sm font-medium text-gray-700 mb-1">Remaining Sessions</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-hourglass-half text-gray-400"></i>
                            </div>
                            <input id="remaining" type="text" value="<?php echo isset($_SESSION['remaining']) ? $_SESSION['remaining'] : 'N/A'; ?>" readonly class="form-input pl-10 w-full border border-gray-300 rounded-lg py-2.5 px-4 bg-gray-50 text-gray-700 focus:outline-none">
                            
                            <?php if (isset($_SESSION['remaining']) && $_SESSION['remaining'] <= 3): ?>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium <?php echo $_SESSION['remaining'] > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'; ?>">
                                    <i class="fas <?php echo $_SESSION['remaining'] > 0 ? 'fa-exclamation-triangle mr-1' : 'fa-times-circle mr-1'; ?>"></i>
                                    <?php echo $_SESSION['remaining'] > 0 ? 'Low' : 'None'; ?>
                                </span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php if (isset($_SESSION['remaining']) && $_SESSION['remaining'] <= 3): ?>
                        <p class="mt-1 text-xs text-gray-500 italic">
                            <?php echo $_SESSION['remaining'] > 0 
                                ? 'You are low on available sessions.' 
                                : 'You have no available sessions left.'; ?>
                        </p>
                        <?php endif; ?>
                    </div>

                    <div class="mt-3">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-primary-600 mr-2"></i>
                            <p class="text-sm text-gray-500">Each reservation is for a 2-hour session.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end mt-8">
                <button type="submit" name="reserve_user" class="px-6 py-3 bg-success-600 hover:bg-success-500 text-white font-medium rounded-lg transition duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-success-500 focus:ring-opacity-50 shadow-md flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    Complete Reservation
                </button>
            </div>
        </form>
    </div>
    
    <!-- Reservation Guidelines Card -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 form-card stagger-item">
        <div class="flex items-center mb-4">
            <div class="rounded-full bg-primary-100 p-3 mr-3">
                <i class="fas fa-lightbulb text-primary-600"></i>
            </div>
            <h2 class="text-lg font-semibold text-gray-800">Reservation Guidelines</h2>
        </div>
        
        <ul class="space-y-3 ml-12">
            <li class="flex items-start">
                <i class="fas fa-check-circle text-success-500 mt-1 mr-2"></i>
                <span class="text-gray-600">Reservations must be made at least 1 day in advance</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check-circle text-success-500 mt-1 mr-2"></i>
                <span class="text-gray-600">Each student is allowed a maximum of 2 hours per session</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check-circle text-success-500 mt-1 mr-2"></i>
                <span class="text-gray-600">Labs are available from 8:00 AM to 7:00 PM on weekdays</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check-circle text-success-500 mt-1 mr-2"></i>
                <span class="text-gray-600">Cancellations must be made at least 3 hours before the scheduled time</span>
            </li>
        </ul>
    </div>
</div>

<script>
    // Page load animation
    document.addEventListener('DOMContentLoaded', function() {
        // Check if remaining sessions are displayed correctly
        const remainingField = document.getElementById('remaining');
        if (!remainingField.value || remainingField.value === 'undefined' || remainingField.value === 'null') {
            console.log("Session count not available, fetching from server...");
            
            // Fetch session count via AJAX
            fetch('../../includes/get_session_count.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        remainingField.value = data.count;
                        console.log("Updated remaining sessions to: " + data.count);
                    } else {
                        console.error("Error fetching session count:", data.message);
                    }
                })
                .catch(error => console.error("AJAX error:", error));
        }
        
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
        
        // Form validation and submission with SweetAlert
        document.getElementById('reservationForm').addEventListener('submit', function(e) {
            // We don't prevent default form submission anymore
            // e.preventDefault();
            
            // Basic validation still happens
            const purpose = document.getElementById('purposes').value;
            const lab = document.getElementById('lab').value;
            const time = document.getElementById('time').value;
            const date = document.getElementById('date').value;
            
            if (!purpose || !lab || !time || !date) {
                e.preventDefault(); // Only prevent if validation fails
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please fill in all required fields!',
                    confirmButtonColor: '#0284c7'
                });
                return;
            }
        });
        
        // Set min date for date input to today
        const dateInput = document.getElementById('date');
        const today = new Date().toISOString().split('T')[0];
        dateInput.min = today;
        
        // Show success/error message if available
        <?php if(!empty($successMessage)): ?>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '<?php echo $successMessage; ?>',
            confirmButtonColor: '#10b981',
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

        <?php if(!empty($errorMessage)): ?>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '<?php echo $errorMessage; ?>',
            confirmButtonColor: '#ef4444',
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        });
        <?php endif; ?>
    });
</script>

</body>
</html>
