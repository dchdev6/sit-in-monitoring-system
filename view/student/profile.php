<?php
// Debug session issues
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if essential session variables exist
if (!isset($_SESSION['id_number'])) {
    // Log the error and redirect to login
    error_log("Missing session data: id_number not set");
    header("Location: ../../index.php");
    exit;
}

require_once '../../includes/navbar_student.php';
require_once '../../api/api_student.php';

// Check for messages
$successMessage = '';
$errorMessage = '';
$uploadError = '';

if(isset($_SESSION['success_message'])) {
    $successMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

if(isset($_SESSION['error_message'])) {
    $errorMessage = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

if(isset($_SESSION['upload_error'])) {
    $uploadError = $_SESSION['upload_error'];
    unset($_SESSION['upload_error']);
}

// Debug: Log session data
error_log("Current profile session data: " . print_r($_SESSION, true));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Student Profile Management - Programming Lab">
    <title>Student Profile</title>
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
        .profile-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .profile-card:hover {
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
        .hover-scale {
            transition: transform 0.3s ease;
        }
        .hover-scale:hover {
            transform: scale(1.02);
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
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        .stagger-item {
            opacity: 0;
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
                            <i class="fas fa-user text-primary-600"></i>
                        </div>
                        User Profile
                    </h1>
                    <p class="text-gray-500 mt-1 ml-12">Manage your personal information</p>
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
                            <span class="text-primary-600 font-medium">User Profile</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <form id="profileForm" action="../../api/api_student.php" method="POST" enctype="multipart/form-data" class="profile-form">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Profile Picture Card -->
                <div class="lg:col-span-1 stagger-item">
                    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 profile-card hover:border-primary-200">
                        <div class="flex flex-col items-center">
                            <div class="relative mb-4">
                                <div class="w-40 h-40 rounded-full border-4 border-primary-100 shadow-lg overflow-hidden bg-gray-100">
                                    <img id="profilePreview" class="w-full h-full object-cover" 
                                        src="<?php echo '../../assets/images/' . ($_SESSION['profile_image'] ?? 'default-profile.jpg')?>" 
                                        alt="Profile Picture">
                                </div>
                                <div class="absolute bottom-0 right-0">
                                    <label for="profileImage" class="bg-primary-600 hover:bg-primary-700 text-white p-2 rounded-full cursor-pointer shadow-md transition duration-300">
                                        <i class="fas fa-camera"></i>
                                        <input type="file" id="profileImage" name="profile_image" accept="image/*" onchange="previewImage(event)" class="hidden">
                                    </label>
                                </div>
                            </div>
                            
                            <h3 class="text-lg font-semibold text-gray-800 mt-2"><?php echo $_SESSION["fname"] . " " . $_SESSION["lname"]; ?></h3>
                            <p class="text-gray-500 mb-4"><?php echo $_SESSION["course"] . " - Year " . $_SESSION["yearLevel"]; ?></p>
                            
                            <div class="bg-blue-50 rounded-lg p-4 w-full mt-2">
                                <h4 class="text-sm font-semibold text-blue-700 mb-2"><i class="fas fa-info-circle mr-1"></i> Student Information</h4>
                                <p class="text-sm text-gray-600 flex items-center mb-2">
                                    <i class="fas fa-id-card text-gray-400 w-5"></i> 
                                    <span class="ml-2"><?php echo $_SESSION["id_number"]; ?></span>
                                </p>
                                <p class="text-sm text-gray-600 flex items-center">
                                    <i class="fas fa-envelope text-gray-400 w-5"></i> 
                                    <span class="ml-2"><?php echo $_SESSION["email"]; ?></span>
                                </p>
                            </div>
                            
                            <!-- Hidden field to retain existing image if no new file is uploaded -->
                            <input type="hidden" name="existing_profile_image" value="<?php echo $_SESSION['profile_image']; ?>">
                        </div>
                    </div>
                </div>
                
                <!-- Profile Details Card -->
                <div class="lg:col-span-2 stagger-item">
                    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 profile-card hover:border-primary-200">
                        <h2 class="text-xl font-semibold text-gray-800 mb-5 flex items-center">
                            <i class="fas fa-user-cog text-primary-600 mr-2"></i>
                            Personal Information
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            <div>
                                <label for="idNumber" class="block text-sm font-medium text-gray-700 mb-1">ID Number</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-id-badge text-gray-400"></i>
                                    </div>
                                    <input type="text" value="<?php echo $_SESSION["id_number"]; ?>" id="idNumber" 
                                        class="border border-gray-300 text-gray-700 rounded-lg block w-full pl-10 p-2.5 bg-gray-100 cursor-not-allowed focus:outline-none" 
                                        name="id_number" readonly>
                                </div>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-gray-400"></i>
                                    </div>
                                    <input type="email" value="<?php echo $_SESSION["email"]; ?>" id="email" 
                                        class="border border-gray-300 text-gray-700 rounded-lg block w-full pl-10 p-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" 
                                        name="email" required>
                                </div>
                            </div>
                        
                            <div>
                                <label for="fName" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    <input type="text" value="<?php echo $_SESSION["fname"]; ?>" id="fName" 
                                        class="border border-gray-300 text-gray-700 rounded-lg block w-full pl-10 p-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" 
                                        name="fName" required>
                                </div>
                            </div>
                            
                            <div>
                                <label for="mName" class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    <input type="text" value="<?php echo $_SESSION["mname"]; ?>" id="mName" 
                                        class="border border-gray-300 text-gray-700 rounded-lg block w-full pl-10 p-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" 
                                        name="mName">
                                </div>
                            </div>

                            <div>
                                <label for="lName" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    <input type="text" value="<?php echo $_SESSION["lname"]; ?>" id="lName" 
                                        class="border border-gray-300 text-gray-700 rounded-lg block w-full pl-10 p-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" 
                                        name="lName" required>
                                </div>
                            </div>
                        
                            <div>
                                <label for="courseLevel" class="block text-sm font-medium text-gray-700 mb-1">Year Level</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-layer-group text-gray-400"></i>
                                    </div>
                                    <select name="courseLevel" id="courseLevel" 
                                        class="border border-gray-300 text-gray-700 rounded-lg block w-full pl-10 p-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" 
                                        required>
                                        <option value="1" <?php echo ($_SESSION["yearLevel"] == 1) ? 'selected' : ''; ?>>1</option>
                                        <option value="2" <?php echo ($_SESSION["yearLevel"] == 2) ? 'selected' : ''; ?>>2</option>
                                        <option value="3" <?php echo ($_SESSION["yearLevel"] == 3) ? 'selected' : ''; ?>>3</option>
                                        <option value="4" <?php echo ($_SESSION["yearLevel"] == 4) ? 'selected' : ''; ?>>4</option>
                                    </select>
                                </div>
                            </div>
                        
                            <div>
                                <label for="course" class="block text-sm font-medium text-gray-700 mb-1">Course</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-graduation-cap text-gray-400"></i>
                                    </div>
                                    <select name="course" id="course" 
                                        class="border border-gray-300 text-gray-700 rounded-lg block w-full pl-10 p-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" 
                                        required>
                                        <optgroup label="College of Computer Studies">
                                            <option value="BSIT" <?php echo ($_SESSION["course"] == 'BSIT') ? 'selected' : ''; ?>>BS in Information Technology</option>
                                            <option value="BSCS" <?php echo ($_SESSION["course"] == 'BSCS') ? 'selected' : ''; ?>>BS in Computer Science</option>
                                            <option value="BSIS" <?php echo ($_SESSION["course"] == 'BSIS') ? 'selected' : ''; ?>>BS in Information Systems</option>
                                            <option value="MIT" <?php echo ($_SESSION["course"] == 'MIT') ? 'selected' : ''; ?>>Master in Information Technology</option>
                                            <option value="MSCS" <?php echo ($_SESSION["course"] == 'MSCS') ? 'selected' : ''; ?>>MS in Computer Science</option>
                                            <option value="DIT" <?php echo ($_SESSION["course"] == 'DIT') ? 'selected' : ''; ?>>Doctor in Information Technology</option>
                                        </optgroup>
                                        
                                        <optgroup label="College of Engineering">
                                            <option value="BSCE" <?php echo ($_SESSION["course"] == 'BSCE') ? 'selected' : ''; ?>>BS in Civil Engineering</option>
                                            <option value="BSEE" <?php echo ($_SESSION["course"] == 'BSEE') ? 'selected' : ''; ?>>BS in Electrical Engineering</option>
                                            <option value="BSME" <?php echo ($_SESSION["course"] == 'BSME') ? 'selected' : ''; ?>>BS in Mechanical Engineering</option>
                                            <option value="BSECE" <?php echo ($_SESSION["course"] == 'BSECE') ? 'selected' : ''; ?>>BS in Electronics & Communications Engineering</option>
                                            <option value="BSCPE" <?php echo ($_SESSION["course"] == 'BSCPE') ? 'selected' : ''; ?>>BS in Computer Engineering</option>
                                            <option value="BSIE" <?php echo ($_SESSION["course"] == 'BSIE') ? 'selected' : ''; ?>>BS in Industrial Engineering</option>
                                            <option value="BSCHE" <?php echo ($_SESSION["course"] == 'BSCHE') ? 'selected' : ''; ?>>BS in Chemical Engineering</option>
                                        </optgroup>
                                        
                                        <optgroup label="College of Business Administration">
                                            <option value="BSBA-FM" <?php echo ($_SESSION["course"] == 'BSBA-FM') ? 'selected' : ''; ?>>BSBA in Financial Management</option>
                                            <option value="BSBA-HRM" <?php echo ($_SESSION["course"] == 'BSBA-HRM') ? 'selected' : ''; ?>>BSBA in Human Resource Management</option>
                                            <option value="BSBA-MM" <?php echo ($_SESSION["course"] == 'BSBA-MM') ? 'selected' : ''; ?>>BSBA in Marketing Management</option>
                                            <option value="BSBA-OM" <?php echo ($_SESSION["course"] == 'BSBA-OM') ? 'selected' : ''; ?>>BSBA in Operations Management</option>
                                            <option value="BSBA-BEM" <?php echo ($_SESSION["course"] == 'BSBA-BEM') ? 'selected' : ''; ?>>BSBA in Business Economics Management</option>
                                            <option value="BSA" <?php echo ($_SESSION["course"] == 'BSA') ? 'selected' : ''; ?>>BS in Accountancy</option>
                                            <option value="BSMA" <?php echo ($_SESSION["course"] == 'BSMA') ? 'selected' : ''; ?>>BS in Management Accounting</option>
                                            <option value="BSE" <?php echo ($_SESSION["course"] == 'BSE') ? 'selected' : ''; ?>>BS in Economics</option>
                                            <option value="BSREM" <?php echo ($_SESSION["course"] == 'BSREM') ? 'selected' : ''; ?>>BS in Real Estate Management</option>
                                            <option value="MBA" <?php echo ($_SESSION["course"] == 'MBA') ? 'selected' : ''; ?>>Master in Business Administration</option>
                                        </optgroup>
                                        
                                        <optgroup label="College of Education">
                                            <option value="BEED" <?php echo ($_SESSION["course"] == 'BEED') ? 'selected' : ''; ?>>Bachelor of Elementary Education</option>
                                            <option value="BSED-ENG" <?php echo ($_SESSION["course"] == 'BSED-ENG') ? 'selected' : ''; ?>>BS in Secondary Education - English</option>
                                            <option value="BSED-FIL" <?php echo ($_SESSION["course"] == 'BSED-FIL') ? 'selected' : ''; ?>>BS in Secondary Education - Filipino</option>
                                            <option value="BSED-MATH" <?php echo ($_SESSION["course"] == 'BSED-MATH') ? 'selected' : ''; ?>>BS in Secondary Education - Mathematics</option>
                                            <option value="BSED-SCI" <?php echo ($_SESSION["course"] == 'BSED-SCI') ? 'selected' : ''; ?>>BS in Secondary Education - Science</option>
                                            <option value="BSED-SS" <?php echo ($_SESSION["course"] == 'BSED-SS') ? 'selected' : ''; ?>>BS in Secondary Education - Social Studies</option>
                                            <option value="BPE" <?php echo ($_SESSION["course"] == 'BPE') ? 'selected' : ''; ?>>Bachelor of Physical Education</option>
                                            <option value="BECE" <?php echo ($_SESSION["course"] == 'BECE') ? 'selected' : ''; ?>>Bachelor of Early Childhood Education</option>
                                            <option value="BSED-MAPEH" <?php echo ($_SESSION["course"] == 'BSED-MAPEH') ? 'selected' : ''; ?>>BS in Secondary Education - MAPEH</option>
                                            <option value="MAEd" <?php echo ($_SESSION["course"] == 'MAEd') ? 'selected' : ''; ?>>Master of Arts in Education</option>
                                            <option value="PhD-Ed" <?php echo ($_SESSION["course"] == 'PhD-Ed') ? 'selected' : ''; ?>>Doctor of Philosophy in Education</option>
                                        </optgroup>
                                        
                                        <optgroup label="College of Arts and Sciences">
                                            <option value="BAC" <?php echo ($_SESSION["course"] == 'BAC') ? 'selected' : ''; ?>>BA in Communication</option>
                                            <option value="BAMC" <?php echo ($_SESSION["course"] == 'BAMC') ? 'selected' : ''; ?>>BA in Mass Communication</option>
                                            <option value="BAJ" <?php echo ($_SESSION["course"] == 'BAJ') ? 'selected' : ''; ?>>BA in Journalism</option>
                                            <option value="BAE" <?php echo ($_SESSION["course"] == 'BAE') ? 'selected' : ''; ?>>BA in English</option>
                                            <option value="BAL" <?php echo ($_SESSION["course"] == 'BAL') ? 'selected' : ''; ?>>BA in Literature</option>
                                            <option value="BAPsych" <?php echo ($_SESSION["course"] == 'BAPsych') ? 'selected' : ''; ?>>BA in Psychology</option>
                                            <option value="BSPsych" <?php echo ($_SESSION["course"] == 'BSPsych') ? 'selected' : ''; ?>>BS in Psychology</option>
                                            <option value="BSBio" <?php echo ($_SESSION["course"] == 'BSBio') ? 'selected' : ''; ?>>BS in Biology</option>
                                            <option value="BSChem" <?php echo ($_SESSION["course"] == 'BSChem') ? 'selected' : ''; ?>>BS in Chemistry</option>
                                            <option value="BSMath" <?php echo ($_SESSION["course"] == 'BSMath') ? 'selected' : ''; ?>>BS in Mathematics</option>
                                            <option value="BSPhys" <?php echo ($_SESSION["course"] == 'BSPhys') ? 'selected' : ''; ?>>BS in Physics</option>
                                            <option value="BSND" <?php echo ($_SESSION["course"] == 'BSND') ? 'selected' : ''; ?>>BS in Nutrition and Dietetics</option>
                                            <option value="BSFT" <?php echo ($_SESSION["course"] == 'BSFT') ? 'selected' : ''; ?>>BS in Food Technology</option>
                                            <option value="BSS" <?php echo ($_SESSION["course"] == 'BSS') ? 'selected' : ''; ?>>BS in Statistics</option>
                                        </optgroup>
                                        
                                        <optgroup label="College of Health Sciences">
                                            <option value="BSN" <?php echo ($_SESSION["course"] == 'BSN') ? 'selected' : ''; ?>>BS in Nursing</option>
                                            <option value="BSPH" <?php echo ($_SESSION["course"] == 'BSPH') ? 'selected' : ''; ?>>BS in Public Health</option>
                                            <option value="BSMLS" <?php echo ($_SESSION["course"] == 'BSMLS') ? 'selected' : ''; ?>>BS in Medical Laboratory Science</option>
                                            <option value="BSPharma" <?php echo ($_SESSION["course"] == 'BSPharma') ? 'selected' : ''; ?>>BS in Pharmacy</option>
                                            <option value="BSOT" <?php echo ($_SESSION["course"] == 'BSOT') ? 'selected' : ''; ?>>BS in Occupational Therapy</option>
                                            <option value="BSPT" <?php echo ($_SESSION["course"] == 'BSPT') ? 'selected' : ''; ?>>BS in Physical Therapy</option>
                                            <option value="BSRT" <?php echo ($_SESSION["course"] == 'BSRT') ? 'selected' : ''; ?>>BS in Respiratory Therapy</option>
                                            <option value="BSSLP" <?php echo ($_SESSION["course"] == 'BSSLP') ? 'selected' : ''; ?>>BS in Speech-Language Pathology</option>
                                            <option value="BSM" <?php echo ($_SESSION["course"] == 'BSM') ? 'selected' : ''; ?>>BS in Midwifery</option>
                                            <option value="BSND" <?php echo ($_SESSION["course"] == 'BSND') ? 'selected' : ''; ?>>BS in Nutrition and Dietetics</option>
                                        </optgroup>
                                        
                                        <optgroup label="College of Tourism and Hospitality Management">
                                            <option value="BSHM" <?php echo ($_SESSION["course"] == 'BSHM') ? 'selected' : ''; ?>>BS in Hospitality Management</option>
                                            <option value="BSTM" <?php echo ($_SESSION["course"] == 'BSTM') ? 'selected' : ''; ?>>BS in Tourism Management</option>
                                            <option value="BSCA" <?php echo ($_SESSION["course"] == 'BSCA') ? 'selected' : ''; ?>>BS in Culinary Arts</option>
                                            <option value="BSFB" <?php echo ($_SESSION["course"] == 'BSFB') ? 'selected' : ''; ?>>BS in Food and Beverage Management</option>
                                            <option value="BSIHRM" <?php echo ($_SESSION["course"] == 'BSIHRM') ? 'selected' : ''; ?>>BS in International Hospitality and Restaurant Management</option>
                                        </optgroup>
                                        
                                        <optgroup label="College of Architecture and Fine Arts">
                                            <option value="BSArch" <?php echo ($_SESSION["course"] == 'BSArch') ? 'selected' : ''; ?>>BS in Architecture</option>
                                            <option value="BSID" <?php echo ($_SESSION["course"] == 'BSID') ? 'selected' : ''; ?>>BS in Interior Design</option>
                                            <option value="BFA-GD" <?php echo ($_SESSION["course"] == 'BFA-GD') ? 'selected' : ''; ?>>BFA in Graphic Design</option>
                                            <option value="BFA-ID" <?php echo ($_SESSION["course"] == 'BFA-ID') ? 'selected' : ''; ?>>BFA in Industrial Design</option>
                                            <option value="BFA-FA" <?php echo ($_SESSION["course"] == 'BFA-FA') ? 'selected' : ''; ?>>BFA in Fine Arts</option>
                                            <option value="BFA-PA" <?php echo ($_SESSION["course"] == 'BFA-PA') ? 'selected' : ''; ?>>BFA in Performing Arts</option>
                                            <option value="BFA-VA" <?php echo ($_SESSION["course"] == 'BFA-VA') ? 'selected' : ''; ?>>BFA in Visual Arts</option>
                                            <option value="BLA" <?php echo ($_SESSION["course"] == 'BLA') ? 'selected' : ''; ?>>Bachelor of Landscape Architecture</option>
                                        </optgroup>
                                        
                                        <optgroup label="College of Law">
                                            <option value="JD" <?php echo ($_SESSION["course"] == 'JD') ? 'selected' : ''; ?>>Juris Doctor</option>
                                            <option value="LLB" <?php echo ($_SESSION["course"] == 'LLB') ? 'selected' : ''; ?>>Bachelor of Laws</option>
                                            <option value="LLM" <?php echo ($_SESSION["course"] == 'LLM') ? 'selected' : ''; ?>>Master of Laws</option>
                                        </optgroup>
                                        
                                        <optgroup label="College of Agriculture">
                                            <option value="BSA" <?php echo ($_SESSION["course"] == 'BSA') ? 'selected' : ''; ?>>BS in Agriculture</option>
                                            <option value="BSABE" <?php echo ($_SESSION["course"] == 'BSABE') ? 'selected' : ''; ?>>BS in Agricultural and Biosystems Engineering</option>
                                            <option value="BSAHT" <?php echo ($_SESSION["course"] == 'BSAHT') ? 'selected' : ''; ?>>BS in Agricultural and Horticultural Technology</option>
                                            <option value="BSF" <?php echo ($_SESSION["course"] == 'BSF') ? 'selected' : ''; ?>>BS in Forestry</option>
                                            <option value="BSFT" <?php echo ($_SESSION["course"] == 'BSFT') ? 'selected' : ''; ?>>BS in Food Technology</option>
                                            <option value="BSAT" <?php echo ($_SESSION["course"] == 'BSAT') ? 'selected' : ''; ?>>BS in Agricultural Technology</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        
                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-home text-gray-400"></i>
                                    </div>
                                    <input type="text" value="<?php echo $_SESSION["address"]; ?>" id="address" 
                                        class="border border-gray-300 text-gray-700 rounded-lg block w-full pl-10 p-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" 
                                        name="address" required>
                                </div>
                            </div>
                        </div>
                    
                    <!-- Hidden field to pass action type -->
                    <input type="hidden" name="action" value="update_profile">
                    
                    <div class="flex justify-end space-x-3 mt-8">
                        <button type="button" onclick="resetForm()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300 transition duration-300">
                            <i class="fas fa-undo mr-2"></i>Reset
                        </button>
                        <button type="submit" name="submit" class="px-6 py-2 bg-primary-600 rounded-lg text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 transition duration-300 shadow-md">
                            <i class="fas fa-save mr-2"></i>Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="py-10"></div>

<script>
// Fade in the body when page loads
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
    
    // Show error alerts with SweetAlert2
    <?php if(!empty($errorMessage)): ?>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '<?php echo $errorMessage; ?>',
        confirmButtonColor: '#0284c7'
    });
    <?php endif; ?>
    
    // Show upload error alerts with SweetAlert2
    <?php if(!empty($uploadError)): ?>
    Swal.fire({
        icon: 'warning',
        title: 'Upload Issue',
        text: '<?php echo $uploadError; ?>',
        confirmButtonColor: '#0284c7'
    });
    <?php endif; ?>
    
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

// Preview image before upload
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function() {
        var output = document.getElementById('profilePreview');
        output.src = reader.result;
        
        // Add a little animation to highlight the change
        output.classList.add('animate__animated', 'animate__pulse');
        setTimeout(() => {
            output.classList.remove('animate__animated', 'animate__pulse');
        }, 1000);
    };
    reader.readAsDataURL(event.target.files[0]);
}

// Form reset confirmation
function resetForm() {
    Swal.fire({
        title: 'Reset Form?',
        text: 'This will revert all changes you made',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#0284c7',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, reset it!',
        showClass: {
            popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('profileForm').reset();
            // Reset profile picture preview to current saved image
            document.getElementById('profilePreview').src = '../../assets/images/<?php echo $_SESSION['profile_image'] ?? 'default-profile.jpg'; ?>';
            Swal.fire({
                title: 'Reset Complete',
                text: 'Form has been reset to original values',
                icon: 'success',
                confirmButtonColor: '#0284c7',
                timer: 2000,
                timerProgressBar: true
            });
        }
    });
}

// Form submission with fetch API for better error handling
document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validate form fields
    const requiredFields = ['email', 'lName', 'fName', 'mName', 'courseLevel', 'course', 'address'];
    let isValid = true;
    let emptyFields = [];
    
    requiredFields.forEach(field => {
        const element = document.getElementById(field);
        if (!element || !element.value.trim()) {
            isValid = false;
            emptyFields.push(field);
        }
    });
    
    if (!isValid) {
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: 'Please fill in all required fields: ' + emptyFields.join(', ')
        });
        return;
    }
    
    Swal.fire({
        title: 'Save Changes?',
        text: 'Update your profile information',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#0284c7',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, save changes'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading indicator
            Swal.fire({
                title: 'Saving...',
                html: 'Updating your profile information',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Use fetch API for JSON response
            const formData = new FormData(this);
            
            fetch('../../api/profile_update.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.message,
                        timer: 2000,
                        timerProgressBar: true
                    }).then(() => {
                        window.location.href = 'profile.php';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Unknown error occurred'
                    });
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Form submission failed: ' + error.message
                });
            });
        }
    });
});
</script>

</body>
</html>
