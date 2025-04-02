<?php
require_once '../api/api_index.php';
require_once '../includes/navbar.php';
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sit-in Monitoring System</title>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <style>
        .login-container {
            min-height: calc(100vh - 64px);
            padding-top: 2rem;
            padding-bottom: 2rem;
        }
        
        .login-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .login-card:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            transform: translateY(-5px);
        }
        
        .login-form-container {
            padding: 2.5rem;
        }
        
        .form-input {
                width: 100%;
                padding: 0.75rem 1rem;
                border-radius: 0.5rem;
                border: 1px solid #d1d5db;
                transition: border-color 0.2s, box-shadow 0.2s;
                outline: none;
            }
            .form-input:focus {
                border-color: #0ea5e9;
                box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.2);
            outline: none;
        }
        
        .form-input:focus {
            border-color: #0ea5e9;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.2);
        }
        
        .login-btn {
            background: linear-gradient(to right, #0284c7, #0ea5e9);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .login-btn:hover {
            background: linear-gradient(to right, #0369a1, #0284c7);
            transform: translateY(-1px);
        }
        
        .login-btn::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.3s, height 0.3s;
        }
        
        .login-btn:active::after {
            width: 300px;
            height: 300px;
        }
        
        .banner-image {
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .pulse-animation {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        
        .floating-animation {
            animation: floating 4s ease-in-out infinite;
        }
        
        @keyframes floating {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
        
        .checkbox-custom {
            position: relative;
            padding-left: 30px;
            cursor: pointer;
            display: block;
        }
        
        .checkbox-custom input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }
        
        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 20px;
            width: 20px;
            background-color: #eee;
            border-radius: 4px;
            transition: all 0.2s ease-in-out;
        }
        
        .checkbox-custom:hover input ~ .checkmark {
            background-color: #ccc;
        }
        
        .checkbox-custom input:checked ~ .checkmark {
            background-color: #0ea5e9;
        }
        
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }
        
        .checkbox-custom input:checked ~ .checkmark:after {
            display: block;
        }
        
        .checkbox-custom .checkmark:after {
            left: 7px;
            top: 3px;
            width: 6px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }
        
        .typing-animation {
            overflow: hidden;
            white-space: nowrap;
            margin: 0;
            animation: typing 3.5s steps(40, end);
        }
        
        @keyframes typing {
            from { width: 0 }
            to { width: 100% }
        }
        
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6b7280;
            transition: color 0.2s ease;
        }
        
        .password-toggle:hover {
            color: #0ea5e9;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans">
    <main class="login-container">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-6xl mx-auto">
                <div class="login-card bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="grid md:grid-cols-12">
                        <!-- Left side: Illustration and welcome message -->
                        <div class="md:col-span-7 bg-primary-50 p-8 flex flex-col justify-center items-center" data-aos="fade-right" data-aos-duration="1000">
                            <div class="text-center mb-8">
                                <h1 class="text-3xl font-bold mb-3 text-gray-800 typing-animation">
                                    Sit-in <span class="text-primary-600">Monitoring</span> System
                                </h1>
                                <p class="text-gray-600 max-w-md mx-auto" data-aos="fade-up" data-aos-delay="200">
                                    Track and manage sit-in sessions efficiently. Ensure proper attendance, monitor participants, and maintain organized records with ease!
                                </p>
                            </div>
                            
                            <!-- Replace the image div with this shield logo -->
                            <div class="relative w-full max-w-md floating-animation" data-aos="zoom-in" data-aos-delay="400">
                                <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center">
                                    <!-- Shield Logo created with CSS/SVG -->
                                    <div class="shield-logo relative mb-4">
                                        <div class="shield-outer">
                                            <svg width="180" height="200" viewBox="0 0 180 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M90 0L180 40V90C180 146.5 142 180 90 200C38 180 0 146.5 0 90V40L90 0Z" fill="url(#shield-gradient)"/>
                                                <defs>
                                                    <linearGradient id="shield-gradient" x1="0" y1="0" x2="180" y2="200" gradientUnits="userSpaceOnUse">
                                                        <stop offset="0%" stop-color="#0ea5e9"/>
                                                        <stop offset="100%" stop-color="#0284c7"/>
                                                    </linearGradient>
                                                </defs>
                                            </svg>
                                            <div class="shield-inner absolute inset-0 flex items-center justify-center">
                                                <div class="text-white flex flex-col items-center p-4">
                                                    <i class="fas fa-university text-4xl mb-2"></i>
                                                    <span class="font-bold text-xl">UC</span>
                                                    <div class="w-16 h-1 bg-white opacity-70 rounded-full my-2"></div>
                                                    <i class="fas fa-laptop-code text-3xl mb-1"></i>
                                                    <span class="font-bold">CCS</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h3 class="text-xl font-bold text-primary-700">Sit-in Monitoring System</h3>
                                    <p class="text-sm text-gray-600 text-center mt-2">Ensuring quality education through effective monitoring</p>
                                </div>
                                <div class="absolute -bottom-4 -right-4 bg-primary-600 text-white py-2 px-4 rounded-lg shadow-lg pulse-animation">
                                    <i class="fas fa-graduation-cap mr-2"></i> College of Computer Studies
                                </div>
                            </div>
                            
                            <div class="mt-8 grid grid-cols-3 gap-4 w-full max-w-md" data-aos="fade-up" data-aos-delay="600">
                                <div class="p-4 bg-white rounded-lg shadow-sm text-center">
                                    <i class="fas fa-users text-primary-500 text-2xl mb-2"></i>
                                    <p class="text-sm text-gray-600">Track Attendance</p>
                                </div>
                                <div class="p-4 bg-white rounded-lg shadow-sm text-center">
                                    <i class="fas fa-chart-line text-primary-500 text-2xl mb-2"></i>
                                    <p class="text-sm text-gray-600">Monitor Progress</p>
                                </div>
                                <div class="p-4 bg-white rounded-lg shadow-sm text-center">
                                    <i class="fas fa-file-alt text-primary-500 text-2xl mb-2"></i>
                                    <p class="text-sm text-gray-600">Generate Reports</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right side: Login form -->
                        <div class="md:col-span-5 login-form-container" data-aos="fade-left" data-aos-duration="1000">
                            <div class="mb-8 text-center">
                                <h2 class="text-2xl font-bold text-gray-800">Login to your account</h2>
                                <p class="text-gray-500 mt-2">Enter your credentials to access the system</p>
                            </div>
                            
                            <form action="login.php" method="POST">
                                <div class="mb-5">
                                    <label for="inputEmail" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text" class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition duration-200" 
                                               id="inputEmail" name="idNum" required placeholder="Enter your username">
                                    </div>
                                </div>
                                
                                <div class="mb-5">
                                    <label for="inputPassword" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" class="w-full pl-10 pr-10 py-3 rounded-lg border border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition duration-200" 
                                               id="inputPassword" name="password" required placeholder="Enter your password">
                                        <span class="password-toggle" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between mb-6">
                                    <label class="checkbox-custom flex items-center text-sm text-gray-600">
                                        <input type="checkbox" id="checkbox" class="mr-2">
                                        <span class="checkmark"></span>
                                        Remember Me
                                    </label>
                                    <a href="#" class="text-sm text-primary-600 hover:text-primary-700 transition duration-200">Forgot Password?</a>
                                </div>
                                
                                <button type="submit" name="submit" class="login-btn w-full py-3 px-4 rounded-lg text-white font-medium shadow-md hover:shadow-lg transition duration-300 flex items-center justify-center">
                                    <i class="fas fa-sign-in-alt mr-2"></i> Login
                                </button>
                                
                                <div class="mt-6 text-center">
                                    <p class="text-sm text-gray-600">
                                        Don't have an account? 
                                        <a href="register.php" class="text-primary-600 hover:text-primary-700 font-medium transition duration-200">
                                            Register here
                                        </a>
                                    </p>
                                </div>
                            </form>
                            
                            <div class="mt-8 pt-8 border-t border-gray-200">
                                <div class="flex items-center justify-center space-x-4">
                                    <a href="../view/index/help.php" class="text-gray-500 hover:text-gray-700 transition duration-200">
                                        <i class="fas fa-question-circle"></i> Help
                                    </a>
                                    <span class="text-gray-300">|</span>
                                    <a href="../view/index/privacy.php" class="text-gray-500 hover:text-gray-700 transition duration-200">
                                        <i class="fas fa-shield-alt"></i> Privacy Policy
                                    </a>
                                    <span class="text-gray-300">|</span>
                                    <a href="../view/index/terms.php" class="text-gray-500 hover:text-gray-700 transition duration-200">
                                        <i class="fas fa-file-contract"></i> Terms
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 text-center text-gray-500 text-sm">
                    &copy; <?php echo date('Y'); ?> College of Computer Studies, University of Cebu. All rights reserved.
                </div>
            </div>
        </div>
    </main>

    <!-- AOS Animation Library -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        // Initialize AOS animations
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                once: true,
                duration: 800
            });
            
            // Password visibility toggle
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('inputPassword');
            
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Toggle eye icon
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
            
            // Input animation on focus
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('scale-105');
                    this.parentElement.style.transition = 'transform 0.3s ease';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('scale-105');
                });
            });
            
            // Button hover effect enhancement
            const loginBtn = document.querySelector('.login-btn');
            loginBtn.addEventListener('mouseenter', function() {
                this.classList.add('shadow-lg');
            });
            
            loginBtn.addEventListener('mouseleave', function() {
                this.classList.remove('shadow-lg');
            });
        });
        
        // Custom SweetAlert styling
        const customSwal = Swal.mixin({
            customClass: {
                confirmButton: 'bg-primary-600 text-white py-2 px-4 rounded-lg hover:bg-primary-700 transition duration-200 mx-2',
                cancelButton: 'bg-gray-400 text-white py-2 px-4 rounded-lg hover:bg-gray-500 transition duration-200 mx-2',
                popup: 'rounded-xl shadow-xl border-0'
            },
            buttonsStyling: false
        });
    </script>
</body>
</html>

<?php
// Session Notification
if(isset($_GET['num']) && $_GET['num'] == 1) {
    echo '<script>
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        iconColor: "#0ea5e9",
        customClass: {
            popup: "colored-toast"
        },
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });
    
    Toast.fire({
        icon: "success",
        title: "Successfully registered!",
        text: "You can now login with your credentials."
    });
    </script>';
}

// Login error notification
if(isset($_GET['error']) && $_GET['error'] == 1) {
    echo '<script>
    Swal.fire({
        icon: "error",
        title: "Login Failed",
        text: "Invalid username or password. Please try again.",
        confirmButtonText: "Try Again",
        confirmButtonColor: "#0ea5e9",
        customClass: {
            popup: "rounded-xl shadow-xl border-0",
            confirmButton: "bg-primary-600 text-white py-2 px-4 rounded-lg hover:bg-primary-700 transition duration-200"
        },
        buttonsStyling: false
    });
    </script>';
}
?>