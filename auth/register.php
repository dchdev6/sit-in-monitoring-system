<?php 
// Start the session at the very beginning of the file
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Output to error log to confirm script is running
error_log("Register.php script started at " . date('Y-m-d H:i:s'));

// Now include your other required files
require_once '../backend/backend_index.php';
require_once '../includes/navbar.php';

// Initialize variables to avoid undefined errors
$registrationSuccess = false;
$registrationError = null;

// Process registration form submission
if(isset($_POST["submitRegister"])){
    error_log("Registration form submitted in register.php");
    error_log("POST data: " . print_r($_POST, true));
    
    // Get and validate all form values
    $idNum = isset($_POST['idNumber']) ? trim($_POST['idNumber']) : '';
    $last_Name = isset($_POST['lName']) ? trim($_POST['lName']) : '';
    $first_Name = isset($_POST['fName']) ? trim($_POST['fName']) : '';
    $middle_Name = isset($_POST['mName']) ? trim($_POST['mName']) : '';
    $course_Level = isset($_POST['level']) ? trim($_POST['level']) : '';
    $passWord = isset($_POST['password']) ? $_POST['password'] : '';
    $confirmPassword = isset($_POST['confirmPassword']) ? $_POST['confirmPassword'] : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $course = isset($_POST['course']) ? trim($_POST['course']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    
    // Basic validation
    if(empty($idNum) || empty($last_Name) || empty($first_Name) || 
       empty($course_Level) || empty($passWord) || empty($email) || empty($course)) {
        $registrationError = "Please fill all required fields";
    }
    else if($passWord !== $confirmPassword) {
        $registrationError = "Passwords do not match";
    }
    else {
        // Call the student_register function
        error_log("About to call student_register with: ID=$idNum, Name=$first_Name $last_Name");
        $result = student_register($idNum, $last_Name, $first_Name, $middle_Name, $course_Level, $passWord, $course, $email, $address);
        error_log("student_register result: " . ($result ? "true" : "false"));
        
        if($result === true) {
            // Use JavaScript redirection instead of PHP header
            $registrationSuccess = true;
        } else {
            $registrationError = "This ID number is already registered in our system.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Sit-in Monitoring System</title>
    
    <!-- Styles and Scripts -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    
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
                            950: '#082f49',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .register-container {
            min-height: calc(100vh - 64px);
            padding-top: 2rem;
            padding-bottom: 2rem;
        }
        
        .register-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .register-card:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            transform: translateY(-5px);
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid #d1d5db;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }
        
        .form-control:focus {
            border-color: #0ea5e9;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.2);
        }
        
        .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid #d1d5db;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            padding-left: 2.5rem !important; /* Ensure consistent padding regardless of icon visibility */
        }
        
        .form-select:focus {
            border-color: #0ea5e9;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.2);
        }
        
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }
        
        .btn-primary {
            background: linear-gradient(to right, #0284c7, #0ea5e9);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .btn-primary:hover {
            background: linear-gradient(to right, #0369a1, #0284c7);
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .password-strength {
            height: 4px;
            transition: width 0.3s, background-color 0.3s;
        }
        
        .password-toggle {
            cursor: pointer;
        }
        
        .floating-animation {
            animation: floating 4s ease-in-out infinite;
        }
        
        @keyframes floating {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
        
        .info-panel {
            background: linear-gradient(135deg, #0ea5e9, #0284c7);
            color: white;
        }

        .input-icon {
            transition: opacity 0.3s ease;
            pointer-events: none; /* Ensures the icon doesn't interfere with clicks */
        }
        
        .has-value .input-icon {
            opacity: 0;
        }
    </style>
</head>

<body class="bg-gray-50">
    <main class="register-container">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-7xl mx-auto">
                <!-- Page Header -->
                <div class="text-center mb-10" data-aos="fade-down" data-aos-duration="800">
                    <h1 class="text-3xl font-bold text-gray-900 sm:text-4xl">Create Your Account</h1>
                    <p class="mt-3 text-lg text-gray-600 max-w-2xl mx-auto">
                        Join the Sit-in Monitoring System to track and enhance educational quality
                    </p>
                </div>
                
                <!-- Registration Card -->
                <div class="register-card bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="md:flex">
                        <!-- Form Section -->
                        <div class="md:w-7/12 p-8">
                            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="registerForm" class="space-y-6">
                                <div class="grid md:grid-cols-2 gap-6">
                                    <!-- Left Column -->
                                    <div class="space-y-4">
                                        <div class="relative" data-aos="fade-right" data-aos-delay="100">
                                            <label for="idNumber" class="form-label">ID Number</label>
                                            <div class="relative">
                                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                                    <i class="fas fa-id-card"></i>
                                                </span>
                                                <input type="text" id="idNumber" class="form-control pl-10" name="idNumber" required>
                                            </div>
                                        </div>
                                        
                                        <div class="relative" data-aos="fade-right" data-aos-delay="150">
                                            <label for="lName" class="form-label">Last Name</label>
                                            <div class="relative">
                                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                                    <i class="fas fa-user"></i>
                                                </span>
                                                <input type="text" id="lName" class="form-control pl-10" name="lName" required>
                                            </div>
                                        </div>
                                        
                                        <div class="relative" data-aos="fade-right" data-aos-delay="200">
                                            <label for="fName" class="form-label">First Name</label>
                                            <div class="relative">
                                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                                    <i class="fas fa-user"></i>
                                                </span>
                                                <input type="text" id="fName" class="form-control pl-10" name="fName" required>
                                            </div>
                                        </div>
                                        
                                        <div class="relative" data-aos="fade-right" data-aos-delay="250">
                                            <label for="mName" class="form-label">Middle Name</label>
                                            <div class="relative">
                                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                                    <i class="fas fa-user"></i>
                                                </span>
                                                <input type="text" id="mName" class="form-control pl-10" name="mName">
                                            </div>
                                        </div>
                                        
                                        <div class="relative" data-aos="fade-right" data-aos-delay="300">
                                            <label for="level" class="form-label">Course Level</label>
                                            <div class="relative">
                                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 input-icon">
                                                    <i class="fas fa-layer-group"></i>
                                                </span>
                                                <select name="level" id="level" class="form-select pl-10" required>
                                                    <option value="">Select your year level</option>
                                                    <option value="1">1st Year</option>
                                                    <option value="2">2nd Year</option>
                                                    <option value="3">3rd Year</option>
                                                    <option value="4">4th Year</option>
                                                </select>
                                            </div>
                                        </div> 
                                    </div>
                                    
                                    <!-- Right Column -->
                                    <div class="space-y-4">
                                        <div class="relative" data-aos="fade-left" data-aos-delay="100">
                                            <label for="password" class="form-label">Password</label>
                                            <div class="relative">
                                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                                    <i class="fas fa-lock"></i>
                                                </span>
                                                <input type="password" id="password" class="form-control pl-10 pr-10" name="password" required>
                                                <button type="button" id="togglePassword" class="password-toggle absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 focus:outline-none">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="relative" data-aos="fade-left" data-aos-delay="150">
                                            <label for="confirmPassword" class="form-label">Confirm Password</label>
                                            <div class="relative">
                                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                                    <i class="fas fa-lock"></i>
                                                </span>
                                                <input type="password" id="confirmPassword" class="form-control pl-10 pr-10" name="confirmPassword" required>
                                                <button type="button" id="toggleConfirmPassword" class="password-toggle absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 focus:outline-none">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            <p id="passwordMatch" class="mt-1 text-xs"></p>
                                        </div>
                                        
                                        <div class="relative" data-aos="fade-left" data-aos-delay="200">
                                            <label for="email" class="form-label">Email Address</label>
                                            <div class="relative">
                                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                                    <i class="fas fa-envelope"></i>
                                                </span>
                                                <input type="email" id="email" class="form-control pl-10" name="email" required>
                                            </div>
                                        </div>
                                        
                                        <!-- Replace your current course select dropdown with this comprehensive version -->
                                        <div class="relative" data-aos="fade-left" data-aos-delay="250">
                                            <label for="course" class="form-label">Course</label>
                                            <div class="relative">
                                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 input-icon">
                                                    <i class="fas fa-graduation-cap"></i>
                                                </span>
                                                <select name="course" id="course" class="form-select pl-10" required>
                                                    <option value="">Select your course</option>
                                                    
                                                    <optgroup label="College of Computer Studies">
                                                        <option value="BSIT">BS in Information Technology</option>
                                                        <option value="BSCS">BS in Computer Science</option>
                                                        <option value="BSIS">BS in Information Systems</option>
                                                        <option value="MIT">Master in Information Technology</option>
                                                        <option value="MSCS">MS in Computer Science</option>
                                                        <option value="DIT">Doctor in Information Technology</option>
                                                    </optgroup>
                                                    
                                                    <optgroup label="College of Engineering">
                                                        <option value="BSCE">BS in Civil Engineering</option>
                                                        <option value="BSEE">BS in Electrical Engineering</option>
                                                        <option value="BSME">BS in Mechanical Engineering</option>
                                                        <option value="BSECE">BS in Electronics & Communications Engineering</option>
                                                        <option value="BSCPE">BS in Computer Engineering</option>
                                                        <option value="BSIE">BS in Industrial Engineering</option>
                                                        <option value="BSCHE">BS in Chemical Engineering</option>
                                                    </optgroup>
                                                    
                                                    <optgroup label="College of Business Administration">
                                                        <option value="BSBA-FM">BSBA in Financial Management</option>
                                                        <option value="BSBA-HRM">BSBA in Human Resource Management</option>
                                                        <option value="BSBA-MM">BSBA in Marketing Management</option>
                                                        <option value="BSBA-OM">BSBA in Operations Management</option>
                                                        <option value="BSBA-BEM">BSBA in Business Economics Management</option>
                                                        <option value="BSA">BS in Accountancy</option>
                                                        <option value="BSMA">BS in Management Accounting</option>
                                                        <option value="BSE">BS in Economics</option>
                                                        <option value="BSREM">BS in Real Estate Management</option>
                                                        <option value="MBA">Master in Business Administration</option>
                                                    </optgroup>
                                                    
                                                    <optgroup label="College of Education">
                                                        <option value="BEED">Bachelor of Elementary Education</option>
                                                        <option value="BSED-ENG">BS in Secondary Education - English</option>
                                                        <option value="BSED-FIL">BS in Secondary Education - Filipino</option>
                                                        <option value="BSED-MATH">BS in Secondary Education - Mathematics</option>
                                                        <option value="BSED-SCI">BS in Secondary Education - Science</option>
                                                        <option value="BSED-SS">BS in Secondary Education - Social Studies</option>
                                                        <option value="BPE">Bachelor of Physical Education</option>
                                                        <option value="BECE">Bachelor of Early Childhood Education</option>
                                                        <option value="BSED-MAPEH">BS in Secondary Education - MAPEH</option>
                                                        <option value="MAEd">Master of Arts in Education</option>
                                                        <option value="PhD-Ed">Doctor of Philosophy in Education</option>
                                                    </optgroup>
                                                    
                                                    <optgroup label="College of Arts and Sciences">
                                                        <option value="BAC">BA in Communication</option>
                                                        <option value="BAMC">BA in Mass Communication</option>
                                                        <option value="BAJ">BA in Journalism</option>
                                                        <option value="BAE">BA in English</option>
                                                        <option value="BAL">BA in Literature</option>
                                                        <option value="BAPsych">BA in Psychology</option>
                                                        <option value="BSPsych">BS in Psychology</option>
                                                        <option value="BSBio">BS in Biology</option>
                                                        <option value="BSChem">BS in Chemistry</option>
                                                        <option value="BSMath">BS in Mathematics</option>
                                                        <option value="BSPhys">BS in Physics</option>
                                                        <option value="BSND">BS in Nutrition and Dietetics</option>
                                                        <option value="BSFT">BS in Food Technology</option>
                                                        <option value="BSS">BS in Statistics</option>
                                                    </optgroup>
                                                    
                                                    <optgroup label="College of Health Sciences">
                                                        <option value="BSN">BS in Nursing</option>
                                                        <option value="BSPH">BS in Public Health</option>
                                                        <option value="BSMLS">BS in Medical Laboratory Science</option>
                                                        <option value="BSPharma">BS in Pharmacy</option>
                                                        <option value="BSOT">BS in Occupational Therapy</option>
                                                        <option value="BSPT">BS in Physical Therapy</option>
                                                        <option value="BSRT">BS in Respiratory Therapy</option>
                                                        <option value="BSSLP">BS in Speech-Language Pathology</option>
                                                        <option value="BSM">BS in Midwifery</option>
                                                        <option value="BSND">BS in Nutrition and Dietetics</option>
                                                    </optgroup>
                                                    
                                                    <optgroup label="College of Tourism and Hospitality Management">
                                                        <option value="BSHM">BS in Hospitality Management</option>
                                                        <option value="BSTM">BS in Tourism Management</option>
                                                        <option value="BSCA">BS in Culinary Arts</option>
                                                        <option value="BSFB">BS in Food and Beverage Management</option>
                                                        <option value="BSIHRM">BS in International Hospitality and Restaurant Management</option>
                                                    </optgroup>
                                                    
                                                    <optgroup label="College of Architecture and Fine Arts">
                                                        <option value="BSArch">BS in Architecture</option>
                                                        <option value="BSID">BS in Interior Design</option>
                                                        <option value="BFA-GD">BFA in Graphic Design</option>
                                                        <option value="BFA-ID">BFA in Industrial Design</option>
                                                        <option value="BFA-FA">BFA in Fine Arts</option>
                                                        <option value="BFA-PA">BFA in Performing Arts</option>
                                                        <option value="BFA-VA">BFA in Visual Arts</option>
                                                        <option value="BLA">Bachelor of Landscape Architecture</option>
                                                    </optgroup>
                                                    
                                                    <optgroup label="College of Law">
                                                        <option value="JD">Juris Doctor</option>
                                                        <option value="LLB">Bachelor of Laws</option>
                                                        <option value="LLM">Master of Laws</option>
                                                    </optgroup>
                                                    
                                                    <optgroup label="College of Agriculture">
                                                        <option value="BSA">BS in Agriculture</option>
                                                        <option value="BSABE">BS in Agricultural and Biosystems Engineering</option>
                                                        <option value="BSAHT">BS in Agricultural and Horticultural Technology</option>
                                                        <option value="BSF">BS in Forestry</option>
                                                        <option value="BSFT">BS in Food Technology</option>
                                                        <option value="BSAT">BS in Agricultural Technology</option>
                                                    </optgroup>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="relative" data-aos="fade-left" data-aos-delay="300">
                                            <label for="address" class="form-label">Address</label>
                                            <div class="relative">
                                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                </span>
                                                <input type="text" id="address" class="form-control pl-10" name="address" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Terms and Conditions -->
                                <div class="bg-primary-50 p-4 rounded-lg mt-6" data-aos="fade-up" data-aos-delay="400">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="terms" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300" required>
                                        </div>
                                        <label for="terms" class="ml-2 text-sm text-gray-600">
                                            I agree to the <a href="../view/index/terms.php" class="text-primary-600 hover:underline">Terms of Service</a> and <a href="../view/index/privacy.php" class="text-primary-600 hover:underline">Privacy Policy</a>
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Form Actions -->
                                <div class="flex items-center justify-between pt-4" data-aos="fade-up" data-aos-delay="450">
                                    <a href="../auth/login.php" class="flex items-center text-gray-600 hover:text-primary-600 transition-colors duration-200">
                                        <i class="fas fa-arrow-left mr-2"></i> Back to Login
                                    </a>
                                    <button type="submit" name="submitRegister" class="btn btn-primary flex items-center" id="directSubmit" onclick="return true;">
                                        <i class="fas fa-user-plus mr-2"></i> Create Account
                                    </button>
                                </div>
                            </form>
                        </div>
                            
                        <!-- Illustration/Information Section -->
                        <div class="md:w-5/12 info-panel p-8 relative hidden md:flex flex-col justify-center items-center">
                            <div class="text-center mb-8">
                                <h2 class="text-2xl font-bold mb-3">Welcome to Our Community</h2>
                                <p class="text-white/80 max-w-md mx-auto">
                                    Create your account to access the Sit-in Monitoring System and join our educational community.
                                </p>
                            </div>
                            
                            <!-- Shield Logo -->
                            <div class="relative w-full max-w-xs floating-animation mb-8">
                                <div class="bg-white/10 backdrop-blur-sm rounded-lg shadow-md p-6 flex flex-col items-center">
                                    <!-- Shield Logo created with CSS/SVG -->
                                    <div class="shield-logo relative mb-4">
                                        <div class="shield-outer">
                                            <svg width="140" height="160" viewBox="0 0 180 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M90 0L180 40V90C180 146.5 142 180 90 200C38 180 0 146.5 0 90V40L90 0Z" fill="url(#shield-gradient)"/>
                                                <defs>
                                                    <linearGradient id="shield-gradient" x1="0" y1="0" x2="180" y2="200" gradientUnits="userSpaceOnUse">
                                                        <stop offset="0%" stop-color="#ffffff"/>
                                                        <stop offset="100%" stop-color="#e0f2fe"/>
                                                    </linearGradient>
                                                </defs>
                                            </svg>
                                            <div class="shield-inner absolute inset-0 flex items-center justify-center">
                                                <div class="text-primary-600 flex flex-col items-center p-4">
                                                    <i class="fas fa-university text-3xl mb-2"></i>
                                                    <span class="font-bold text-lg">UC</span>
                                                    <div class="w-12 h-0.5 bg-primary-500 opacity-70 rounded-full my-2"></div>
                                                    <i class="fas fa-laptop-code text-2xl mb-1"></i>
                                                    <span class="font-bold">CCS</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            
                            <div class="absolute bottom-4 left-0 right-0 text-center">
                                <p class="text-sm text-white/60">
                                    &copy; <?php echo date('Y'); ?> University of Cebu
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS animations
            AOS.init({
                once: true,
                duration: 800,
                easing: 'ease-out'
            });
            
            // Password toggle functionality
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');
            
            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                
                // Toggle icon
                const icon = this.querySelector('i');
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });
            
            const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
            const confirmPassword = document.getElementById('confirmPassword');
            
            toggleConfirmPassword.addEventListener('click', function() {
                const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmPassword.setAttribute('type', type);
                
                // Toggle icon
                const icon = this.querySelector('i');
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });
            
            
            // Password match validation
            const passwordMatch = document.getElementById('passwordMatch');
            
            confirmPassword.addEventListener('input', function() {
                if (this.value === '') {
                    passwordMatch.textContent = '';
                    return;
                }
                
                if (this.value === password.value) {
                    passwordMatch.textContent = 'Passwords match';
                    passwordMatch.className = 'mt-1 text-xs text-green-500';
                } else {
                    passwordMatch.textContent = 'Passwords do not match';
                    passwordMatch.className = 'mt-1 text-xs text-red-500';
                }
            });
            
            // Form submission handling
            const form = document.getElementById('registerForm');
            const oldForm = form.cloneNode(true);
            form.parentNode.replaceChild(oldForm, form);
            
            document.getElementById('registerForm').addEventListener('submit', function() {
                document.getElementById('registerBtn').disabled = true;
                document.getElementById('registerBtn').innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
            });
            
            // Input animation on focus
            const inputs = document.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('scale-105');
                    this.parentElement.style.transition = 'transform 0.3s ease';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('scale-105');
                });
            });

            // Hide icons when typing/focusing on inputs
            const inputContainers = document.querySelectorAll('.relative');
            
            inputContainers.forEach(container => {
                const input = container.querySelector('input, select');
                const iconSpan = container.querySelector('span');
                
                if (input && iconSpan) {
                    input.addEventListener('focus', function() {
                        iconSpan.style.opacity = '0';
                        iconSpan.style.transition = 'opacity 0.3s ease';
                    });
                    
                    input.addEventListener('blur', function() {
                        if (this.value === '') {
                            iconSpan.style.opacity = '1';
                        }
                    });
                    
                    // Check if the field already has a value
                    if (input.value !== '') {
                        iconSpan.style.opacity = '0';
                    }
                }
            });

            // Fix for select fields with icons
            const selectFields = document.querySelectorAll('select.form-select');
            selectFields.forEach(select => {
                // Initial check
                updateSelectIconVisibility(select);
                
                // Add event listeners
                select.addEventListener('change', function() {
                    updateSelectIconVisibility(this);
                });
                
                select.addEventListener('focus', function() {
                    const iconSpan = this.parentElement.querySelector('.input-icon');
                    if (iconSpan) {
                        iconSpan.style.opacity = '0';
                    }
                });
            });
            
            function updateSelectIconVisibility(selectElement) {
                const container = selectElement.parentElement;
                const iconSpan = container.querySelector('span');
                
                if (selectElement.value) {
                    container.classList.add('has-value');
                    if (iconSpan) iconSpan.style.opacity = '0';
                } else {
                    container.classList.remove('has-value');
                    if (iconSpan) iconSpan.style.opacity = '1';
                }
            }
        });
    </script>

    <?php
    // Display SweetAlert for any errors that happened during form processing
    if(isset($registrationError) && $registrationError) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Registration Failed',
                text: '" . addslashes($registrationError) . "',
                confirmButtonColor: '#0284c7'
            });
        </script>";
    }
    ?>

    <!-- Add this just before </body> -->
    <?php if(isset($registrationSuccess) && $registrationSuccess === true): ?>
    <script>
        // Show success message then redirect
        Swal.fire({
            icon: 'success',
            title: 'Registration Successful!',
            text: 'You can now log in with your credentials.',
            confirmButtonColor: '#0284c7'
        }).then(() => {
            window.location.href = 'login.php?num=1';
        });
    </script>
    <?php endif; ?>
</body>
</html>