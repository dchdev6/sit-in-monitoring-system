<?php 
// Start the session at the very beginning of the file
session_start();

// Now include your other required files
require_once '../includes/navbar.php';
require_once '../api/api_index.php';
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
        
        /* Replace @apply directives with explicit Tailwind classes */
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid #d1d5db;
            transition: all 0.2s ease;
        }
        
        .form-control:focus {
            border-color: #0ea5e9;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
            outline: none;
        }
        
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.25rem;
        }
        
        .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid #d1d5db;
            transition: all 0.2s ease;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
        
        .form-select:focus {
            border-color: #0ea5e9;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
            outline: none;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
        }
        
        .btn-primary {
            background-color: #0284c7;
            color: white;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        
        .btn-primary:hover {
            background-color: #0369a1;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .btn-danger {
            background-color: #dc2626;
            color: white;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        
        .btn-danger:hover {
            background-color: #b91c1c;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .floating-anim {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        
        .pulse-anim {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .slide-in {
            animation: slideIn 0.8s ease-out forwards;
        }
        
        @keyframes slideIn {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .password-strength {
            height: 5px;
            transition: all 0.3s ease;
        }
        
        .password-toggle:hover {
            color: #0ea5e9;
        }
        
        /* Add these classes to fix the illustration panel */
        .info-panel {
            background: linear-gradient(135deg, #0284c7 0%, #075985 100%);
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .info-panel-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.1;
        }
        
        .info-panel-content {
            position: relative;
            z-index: 10;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .info-icon {
            width: 10rem;
            height: 10rem;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .circle-decoration {
            position: absolute;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.2);
        }

        /* SVG animations for information panel - FIXED VERSION */
        .moving-dot {
            animation: moveDot 8s infinite ease-in-out;
        }

        /* Fix nth-child selectors for SVG dots */
        .moving-dot:nth-of-type(1) {
            animation-delay: 0s;
        }

        .moving-dot:nth-of-type(2) {
            animation-delay: 2s;
        }

        .moving-dot:nth-of-type(3) {
            animation-delay: 4s;
        }

        .moving-dot:nth-of-type(4) {
            animation-delay: 6s;
        }

        @keyframes moveDot {
            0%, 100% { 
                opacity: 0.8;
                /* Remove r attribute change as it causes issues */
                transform: scale(1);
            }
            50% { 
                opacity: 0.3;
                transform: scale(1.5);
            }
        }

        /* Enhanced floating animation for SVG shield - FIXED */
        .floating-anim svg {
            animation: floatSvg 6s infinite ease-in-out;
        }

        @keyframes floatSvg {
            0%, 100% { 
                transform: translateY(0px); 
            }
            50% { 
                transform: translateY(-15px); 
            }
        }

        /* Pulse animation for shield inner circle - FIXED */
        .pulse-anim-svg {
            animation: pulseSvg 3s infinite ease-in-out;
        }

        @keyframes pulseSvg {
            0%, 100% { 
                opacity: 0.8;
                fill-opacity: 0.2;
            }
            50% { 
                opacity: 1;
                fill-opacity: 0.4;
            }
        }

        /* Text color fix for information panel */
        .info-panel-content p,
        .info-panel-content h3 {
            color: white;
        }

        /* Fix for SVG text issues */
        .info-feature-text {
            color: white !important;
            font-size: 0.875rem;
            font-weight: normal;
        }

        /* Fix for security message */
        .security-message {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 0.5rem;
            padding: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        .security-message p {
            color: white !important;
            font-size: 0.875rem;
            font-weight: 500;
            margin: 0;
        }

        .security-message svg {
            margin-right: 0.5rem;
        }

        /* Icon container styling - FIXED */
        .info-panel-content .flex-shrink-0 {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            width: 48px;
            height: 48px;
            transition: all 0.3s ease;
        }

        .info-panel-content .flex-shrink-0:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
        }

        /* Icon styling - FIXED */
        .info-panel-content .flex-shrink-0 i {
            font-size: 1.25rem;
            color: white;
        }

        /* Feature text styling - FIXED */
        .info-feature-text {
            color: white;
            font-size: 0.875rem;
            font-weight: normal;
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <main class="flex-grow">
            <div class="max-w-7xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
                <!-- Page Header -->
                <div class="text-center mb-10" data-aos="fade-down" data-aos-duration="800">
                    <h1 class="text-3xl font-bold text-gray-900 sm:text-4xl">Create Your Account</h1>
                    <p class="mt-3 text-lg text-gray-600 max-w-2xl mx-auto">
                        Join the Sit-in Monitoring System to track and enhance educational quality
                    </p>
                </div>
                
                <!-- Registration Card -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden" data-aos="fade-up" data-aos-duration="1000">
                    <div class="md:flex">
                        <!-- Form Section -->
                        <div class="md:w-2/3 p-6 md:p-10">
                            <form action="register.php" method="POST" id="registerForm" class="space-y-6">
                                <div class="grid md:grid-cols-2 gap-6">
                                    <!-- Left Column -->
                                    <div class="space-y-4">
                                        <div class="relative" data-aos="fade-right" data-aos-delay="100">
                                            <label for="idNumber" class="form-label">ID Number</label>
                                            <input type="text" id="idNumber" class="form-control" name="idNumber" required>
                                            <p class="mt-1 text-xs text-gray-500">Enter your university ID number</p>
                                        </div>
                                        
                                        <div class="relative" data-aos="fade-right" data-aos-delay="150">
                                            <label for="lName" class="form-label">Last Name</label>
                                            <input type="text" id="lName" class="form-control" name="lName" required>
                                        </div>
                                        
                                        <div class="relative" data-aos="fade-right" data-aos-delay="200">
                                            <label for="fName" class="form-label">First Name</label>
                                            <input type="text" id="fName" class="form-control" name="fName" required>
                                        </div>
                                        
                                        <div class="relative" data-aos="fade-right" data-aos-delay="250">
                                            <label for="mName" class="form-label">Middle Name</label>
                                            <input type="text" id="mName" class="form-control" name="mName">
                                            <p class="mt-1 text-xs text-gray-500">Optional</p>
                                        </div>
                                        
                                        <div class="relative" data-aos="fade-right" data-aos-delay="300">
                                            <label for="level" class="form-label">Course Level</label>
                                            <select name="level" id="level" class="form-select">
                                                <option value="1">1st Year</option>
                                                <option value="2">2nd Year</option>
                                                <option value="3">3rd Year</option>
                                                <option value="4">4th Year</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <!-- Right Column -->
                                    <div class="space-y-4">
                                        <div class="relative" data-aos="fade-left" data-aos-delay="100">
                                            <label for="password" class="form-label">Password</label>
                                            <div class="relative">
                                                <input type="password" id="password" class="form-control pr-10" name="password" required>
                                                <button type="button" id="togglePassword" class="password-toggle absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 focus:outline-none">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            <div class="mt-2 bg-gray-200 rounded-full overflow-hidden">
                                                <div id="passwordStrength" class="password-strength bg-gray-500 w-0"></div>
                                            </div>
                                            <p id="passwordStrengthText" class="mt-1 text-xs text-gray-500">Password strength indicator</p>
                                        </div>
                                        
                                        <div class="relative" data-aos="fade-left" data-aos-delay="150">
                                            <label for="confirmPassword" class="form-label">Confirm Password</label>
                                            <div class="relative">
                                                <input type="password" id="confirmPassword" class="form-control pr-10" name="confirmPassword" required>
                                                <button type="button" id="toggleConfirmPassword" class="password-toggle absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 focus:outline-none">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            <p id="passwordMatch" class="mt-1 text-xs"></p>
                                        </div>
                                        
                                        <div class="relative" data-aos="fade-left" data-aos-delay="200">
                                            <label for="email" class="form-label">Email Address</label>
                                            <input type="email" id="email" class="form-control" name="email" required>
                                            <p class="mt-1 text-xs text-gray-500">We'll send verification details to this email</p>
                                        </div>
                                        
                                        <div class="relative" data-aos="fade-left" data-aos-delay="250">
                                            <label for="course" class="form-label">Course</label>
                                            <select name="course" id="course" class="form-select">
                                                <option value="BSIT">BS in Information Technology</option>
                                                <option value="BSCS">BS in Computer Science</option>
                                                <option value="ACT">Associate in Computer Technology</option>
                                            </select>
                                        </div>
                                        
                                        <div class="relative" data-aos="fade-left" data-aos-delay="300">
                                            <label for="address" class="form-label">Address</label>
                                            <input type="text" id="address" class="form-control" name="address" required>
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
                                    <button type="submit" name="submitRegister" class="btn btn-primary flex items-center" id="registerBtn">
                                        <i class="fas fa-user-plus mr-2"></i> Create Account
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Illustration/Information Section -->
                        <div class="md:w-1/3 info-panel p-8 relative hidden md:block">
                            <!-- SVG Background Elements -->
                            <svg class="absolute top-0 left-0 w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 600" fill="none">
                                <!-- Abstract shapes -->
                                <circle cx="50" cy="100" r="40" fill="white" fill-opacity="0.1" />
                                <circle cx="350" cy="500" r="50" fill="white" fill-opacity="0.1" />
                                <circle cx="320" cy="250" r="30" fill="white" fill-opacity="0.1" />
                                <circle cx="80" cy="400" r="25" fill="white" fill-opacity="0.08" />
                                
                                <!-- Connecting lines -->
                                <path d="M50 100 L320 250" stroke="white" stroke-opacity="0.05" stroke-width="2" />
                                <path d="M320 250 L350 500" stroke="white" stroke-opacity="0.05" stroke-width="2" />
                                <path d="M80 400 L350 500" stroke="white" stroke-opacity="0.05" stroke-width="2" />
                                
                                <!-- Animated dots (will be animated with CSS) -->
                                <circle class="moving-dot" cx="50" cy="100" r="3" fill="white" fill-opacity="0.5" />
                                <circle class="moving-dot" cx="320" cy="250" r="3" fill="white" fill-opacity="0.5" />
                                <circle class="moving-dot" cx="350" cy="500" r="3" fill="white" fill-opacity="0.5" />
                                <circle class="moving-dot" cx="80" cy="400" r="3" fill="white" fill-opacity="0.5" />
                                
                                <!-- Abstract wave pattern -->
                                <path d="M0 550 C100 500, 200 600, 400 520" stroke="white" stroke-opacity="0.1" stroke-width="3" />
                            </svg>
                            
                            <div class="info-panel-content">
                                <div class="text-center mb-10">
                                    <!-- Replace icon with SVG shield -->
                                    <div class="inline-block mb-6">
                                        <svg class="w-24 h-24 floating-anim" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 2L4 5V11.09C4 16.14 7.41 20.85 12 22C16.59 20.85 20 16.14 20 11.09V5L12 2Z" fill="white" fill-opacity="0.2" stroke="white" stroke-width="1.5"/>
                                            <path class="pulse-anim-svg" d="M12 17.25C14.8995 17.25 17.25 14.8995 17.25 12C17.25 9.10051 14.8995 6.75 12 6.75C9.10051 6.75 6.75 9.10051 6.75 12C6.75 14.8995 9.10051 17.25 12 17.25Z" fill="white" fill-opacity="0.2" stroke="white" stroke-width="1.5"/>
                                            <path d="M12 13.75C12.9665 13.75 13.75 12.9665 13.75 12C13.75 11.0335 12.9665 10.25 12 10.25C11.0335 10.25 10.25 11.0335 10.25 12C10.25 12.9665 11.0335 13.75 12 13.75Z" fill="white"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-2xl font-bold mb-3">Join Our Community</h3>
                                    <p class="text-blue-100">Become part of the College of Computer Studies</p>
                                </div>
                                
                                <!-- Replace the feature section in your information panel with this fixed version -->
                                <div class="space-y-8">
                                    <div class="flex items-center space-x-4">
                                        <!-- Fixed Laptop Code Icon -->
                                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-transparent flex items-center justify-center">
                                            <i class="fas fa-laptop-code text-white text-2xl"></i>
                                        </div>
                                        <p class="info-feature-text">Access our comprehensive sit-in monitoring tools</p>
                                    </div>
                                    
                                    <div class="flex items-center space-x-4">
                                        <!-- Fixed Chart Line Icon -->
                                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-transparent flex items-center justify-center">
                                            <i class="fas fa-chart-line text-white text-2xl"></i>
                                        </div>
                                        <p class="info-feature-text">Track educational progress with real-time analytics</p>
                                    </div>
                                    
                                    <div class="flex items-center space-x-4">
                                        <!-- Fixed Bell Icon -->
                                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-transparent flex items-center justify-center">
                                            <i class="fas fa-bell text-white text-2xl"></i>
                                        </div>
                                        <p class="info-feature-text">Receive notifications about important events</p>
                                    </div>
                                </div>
                                
                                <div class="mt-12 mb-4">
                                    <div class="security-message">
                                        <i class="fas fa-shield-alt text-white mr-2"></i>
                                        <p>Your data is secure with us</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="mt-8 text-center" data-aos="fade-up" data-aos-delay="500">
                    <p class="text-sm text-gray-500">
                        &copy; <?php echo date('Y'); ?> College of Computer Studies, University of Cebu. All rights reserved.
                    </p>
                </div>
            </div>
        </main>
    </div>

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
            
            // Password strength indicator
            const passwordStrength = document.getElementById('passwordStrength');
            const passwordStrengthText = document.getElementById('passwordStrengthText');
            
            password.addEventListener('input', function() {
                const value = this.value;
                let strength = 0;
                
                // Check for length
                if (value.length >= 8) strength += 1;
                
                // Check for mixed case
                if (value.match(/[a-z]/) && value.match(/[A-Z]/)) strength += 1;
                
                // Check for numbers
                if (value.match(/\d/)) strength += 1;
                
                // Check for special characters
                if (value.match(/[^a-zA-Z0-9]/)) strength += 1;
                
                // Update UI based on strength
                switch (strength) {
                    case 0:
                        passwordStrength.className = 'password-strength bg-gray-400 w-0';
                        passwordStrengthText.textContent = 'Password strength indicator';
                        passwordStrengthText.className = 'mt-1 text-xs text-gray-500';
                        break;
                    case 1:
                        passwordStrength.className = 'password-strength bg-red-500 w-1/4';
                        passwordStrengthText.textContent = 'Weak';
                        passwordStrengthText.className = 'mt-1 text-xs text-red-500';
                        break;
                    case 2:
                        passwordStrength.className = 'password-strength bg-yellow-500 w-2/4';
                        passwordStrengthText.textContent = 'Fair';
                        passwordStrengthText.className = 'mt-1 text-xs text-yellow-500';
                        break;
                    case 3:
                        passwordStrength.className = 'password-strength bg-blue-500 w-3/4';
                        passwordStrengthText.textContent = 'Good';
                        passwordStrengthText.className = 'mt-1 text-xs text-blue-500';
                        break;
                    case 4:
                        passwordStrength.className = 'password-strength bg-green-500 w-full';
                        passwordStrengthText.textContent = 'Strong';
                        passwordStrengthText.className = 'mt-1 text-xs text-green-500';
                        break;
                }
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
            const registerBtn = document.getElementById('registerBtn');
            
            form.addEventListener('submit', function(e) {
                // Perform client-side validation
                if (password.value !== confirmPassword.value) {
                    e.preventDefault();
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Passwords Do Not Match',
                        text: 'Please make sure your passwords match before submitting.',
                        confirmButtonColor: '#0284c7'
                    });
                    
                    return false;
                }
                
                // Check if terms are accepted
                if (!document.getElementById('terms').checked) {
                    e.preventDefault();
                    
                    Swal.fire({
                        icon: 'warning',
                        title: 'Terms & Conditions',
                        text: 'Please agree to our Terms and Privacy Policy to continue.',
                        confirmButtonColor: '#0284c7'
                    });
                    
                    return false;
                }
                
                // Show loading state for button
                registerBtn.disabled = true;
                registerBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
                
                // Form will submit if all validations pass
            });
            
            // Apply subtle animations to form inputs
            const formInputs = document.querySelectorAll('.form-control, .form-select');
            
            formInputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.classList.add('ring', 'ring-primary-200', 'ring-opacity-50');
                    this.parentElement.classList.add('transform', 'scale-[1.01]');
                    this.parentElement.style.transition = 'transform 0.2s ease';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('transform', 'scale-[1.01]');
                });
            });
        });
    </script>

    <?php
    // Handle form submission response
    if(isset($_GET['num']) && $_GET['num'] == 2) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Registration Failed',
                text: 'This ID number is already registered in our system.',
                confirmButtonColor: '#0284c7'
            });
        </script>";
    }
    ?>
</body>
</html>