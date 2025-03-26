<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Student - Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.12/sweetalert2.all.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/js-confetti@latest/dist/js-confetti.browser.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
    .fade-in-up {
      animation: fadeInUp 0.6s ease-out;
    }
    
    @keyframes fadeInUp {
      from {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .input-field {
      transition: all 0.3s ease;
    }
    
    .input-field:focus {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px -5px rgba(14, 165, 233, 0.25);
      border-color: #0ea5e9;
    }
    
    .form-group {
      opacity: 0;
      transform: translateY(20px);
      animation: staggerFade 0.5s ease forwards;
    }
    
    @keyframes staggerFade {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .section-title {
      position: relative;
      display: inline-block;
    }
    
    .section-title::after {
      content: '';
      position: absolute;
      left: 0;
      bottom: -4px;
      width: 0;
      height: 2px;
      background-color: #0ea5e9;
      transition: width 0.6s ease;
    }
    
    .section-title:hover::after {
      width: 100%;
    }
    
    .btn-pulse {
      animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
      0% {
        box-shadow: 0 0 0 0 rgba(14, 165, 233, 0.7);
      }
      70% {
        box-shadow: 0 0 0 10px rgba(14, 165, 233, 0);
      }
      100% {
        box-shadow: 0 0 0 0 rgba(14, 165, 233, 0);
      }
    }
    
    /* New styles for enhanced form */
    .input-field.is-valid {
      border-color: #10b981;
    }
    
    .input-field.is-invalid {
      border-color: #ef4444;
    }
    
    .form-hint {
      font-size: 0.75rem;
      color: #6b7280;
      margin-top: 0.25rem;
      transition: all 0.3s ease;
    }
    
    .group:hover .icon-hint {
      opacity: 1;
      transform: translateY(0);
    }
    
    .icon-hint {
      opacity: 0;
      transform: translateY(5px);
      transition: all 0.3s ease;
    }
    
    /* Progress steps */
    .progress-steps {
      display: flex;
      justify-content: space-between;
      position: relative;
      margin-bottom: 2rem;
    }
    
    .progress-step {
      flex: 1;
      text-align: center;
      position: relative;
      z-index: 1;
    }
    
    .progress-step-icon {
      width: 2.5rem;
      height: 2.5rem;
      border-radius: 50%;
      background-color: #e5e7eb;
      color: #9ca3af;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 0.5rem;
      transition: all 0.3s ease;
    }
    
    .progress-step.active .progress-step-icon {
      background-color: #4f46e5;
      color: white;
      transform: scale(1.1);
    }
    
    .progress-step.completed .progress-step-icon {
      background-color: #10b981;
      color: white;
    }
    
    .progress-step-label {
      font-size: 0.875rem;
      color: #6b7280;
      transition: all 0.3s ease;
    }
    
    .progress-step.active .progress-step-label {
      color: #4f46e5;
      font-weight: 500;
    }
    
    .progress-step.completed .progress-step-label {
      color: #10b981;
    }
    
    .progress-bar {
      position: absolute;
      top: 1.25rem;
      left: 0;
      height: 2px;
      background-color: #e5e7eb;
      width: 100%;
      z-index: 0;
    }
    
    .progress-bar-fill {
      position: absolute;
      top: 0;
      left: 0;
      height: 100%;
      background-color: #4f46e5;
      transition: width 0.5s ease;
      width: 0%;
    }
    
    /* Updated styles to match students.php */
    .bg-gradient-to-br {
      background-image: linear-gradient(to bottom right, #f9fafb, #f3f4f6);
    }
  </style>
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
          keyframes: {
            fadeIn: {
              '0%': { opacity: '0' },
              '100%': { opacity: '1' },
            },
            slideUp: {
              '0%': { transform: 'translateY(20px)', opacity: '0' },
              '100%': { transform: 'translateY(0)', opacity: '1' },
            },
            pulse: {
              '0%, 100%': { transform: 'scale(1)' },
              '50%': { transform: 'scale(1.05)' },
            }
          },
          animation: {
            fadeIn: 'fadeIn 0.5s ease-out',
            slideUp: 'slideUp 0.5s ease-out',
            pulse: 'pulse 2s infinite',
          },
        }
      }
    }
  </script>
</head>
<body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen">
  <!-- Navbar would be included here in your PHP file -->
  
  <div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto animate__animated animate__fadeIn">
      <!-- Back button -->
      <div class="mb-6 animate__animated animate__slideInLeft">
        <a href="Students.php" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-400 hover:-translate-x-1">
          <i class="fas fa-arrow-left mr-2"></i> Back to Students
        </a>
      </div>
      
      <!-- Add after back button -->
      <!-- Breadcrumbs -->
      <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm">
          <li class="inline-flex items-center">
            <a href="Admin.php" class="text-gray-500 hover:text-primary-600 transition-colors inline-flex items-center">
              <i class="fas fa-home mr-2"></i>
              Dashboard
            </a>
          </li>
          <li>
            <div class="flex items-center">
              <span class="text-gray-400 mx-2">/</span>
              <a href="Students.php" class="text-gray-500 hover:text-primary-600 transition-colors">
                Students
              </a>
            </div>
          </li>
          <li>
            <div class="flex items-center">
              <span class="text-gray-400 mx-2">/</span>
              <span class="text-primary-600 font-medium">Add Student</span>
            </div>
          </li>
        </ol>
      </nav>

      <div class="bg-white rounded-2xl shadow-xl overflow-hidden fade-in-up">
        <div class="p-8">
          <h1 class="text-3xl font-bold text-center text-gray-800 mb-8 animate__animated animate__fadeInDown flex items-center justify-center">
            <div class="bg-primary-100 p-3 rounded-lg mr-3 shadow-sm">
              <i class="fas fa-user-plus text-primary-600"></i>
            </div>
            <span class="section-title">Add Student</span>
          </h1>
          
          <!-- Progress Steps -->
          <div class="progress-steps mb-8">
            <div class="progress-bar">
              <div class="progress-bar-fill" id="progressBarFill"></div>
            </div>
            
            <div class="progress-step active" id="step1">
              <div class="progress-step-icon">
                <i class="fas fa-id-card"></i>
              </div>
              <div class="progress-step-label">Basic Info</div>
            </div>
            
            <div class="progress-step" id="step2">
              <div class="progress-step-icon">
                <i class="fas fa-graduation-cap"></i>
              </div>
              <div class="progress-step-label">Academic</div>
            </div>
            
            <div class="progress-step" id="step3">
              <div class="progress-step-icon">
                <i class="fas fa-lock"></i>
              </div>
              <div class="progress-step-label">Security</div>
            </div>
            
            <div class="progress-step" id="step4">
              <div class="progress-step-icon">
                <i class="fas fa-envelope"></i>
              </div>
              <div class="progress-step-label">Contact</div>
            </div>
          </div>
          
          <form id="studentForm" action="Add.php" method="POST" class="space-y-6">
            <!-- ID Number -->
            <div class="form-group" style="animation-delay: 0.1s;">
              <div class="space-y-2">
                <label for="idNumber" class="block text-sm font-medium text-gray-700">ID Number</label>
                <div class="relative rounded-md shadow-sm overflow-hidden group">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none group-hover:text-primary-600 transition-colors duration-300">
                    <i class="fas fa-id-card text-gray-400"></i>
                  </div>
                  <input type="text" id="idNumber" name="idNumber" required class="input-field pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 bg-gray-50 py-3 transition-all duration-300 hover:bg-gray-100">
                </div>
              </div>
            </div>
            
            <!-- Name Fields -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div class="form-group" style="animation-delay: 0.2s;">
                <div class="space-y-2">
                  <label for="lName" class="block text-sm font-medium text-gray-700">Last Name</label>
                  <div class="relative rounded-md shadow-sm overflow-hidden group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none group-hover:text-indigo-600 transition-colors duration-300">
                      <i class="fas fa-user text-gray-400"></i>
                    </div>
                    <input type="text" id="lName" name="lName" required class="input-field pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 py-3 transition-all duration-300 hover:bg-gray-100">
                  </div>
                </div>
              </div>
              
              <div class="form-group" style="animation-delay: 0.3s;">
                <div class="space-y-2">
                  <label for="fName" class="block text-sm font-medium text-gray-700">First Name</label>
                  <div class="relative rounded-md shadow-sm overflow-hidden group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none group-hover:text-indigo-600 transition-colors duration-300">
                      <i class="fas fa-user text-gray-400"></i>
                    </div>
                    <input type="text" id="fName" name="fName" required class="input-field pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 py-3 transition-all duration-300 hover:bg-gray-100">
                  </div>
                </div>
              </div>
              
              <div class="form-group" style="animation-delay: 0.4s;">
                <div class="space-y-2">
                  <label for="mName" class="block text-sm font-medium text-gray-700">Middle Name</label>
                  <div class="relative rounded-md shadow-sm overflow-hidden group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none group-hover:text-indigo-600 transition-colors duration-300">
                      <i class="fas fa-user text-gray-400"></i>
                    </div>
                    <input type="text" id="mName" name="mName" required class="input-field pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 py-3 transition-all duration-300 hover:bg-gray-100">
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Academic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="form-group" style="animation-delay: 0.5s;">
                <div class="space-y-2">
                  <label for="level" class="block text-sm font-medium text-gray-700">Course Level</label>
                  <div class="relative rounded-md shadow-sm overflow-hidden group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none group-hover:text-indigo-600 transition-colors duration-300">
                      <i class="fas fa-layer-group text-gray-400"></i>
                    </div>
                    <select id="level" name="level" class="input-field pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 py-3 transition-all duration-300 hover:bg-gray-100">
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                    </select>
                  </div>
                </div>
              </div>
              
              <div class="form-group" style="animation-delay: 0.6s;">
                <div class="space-y-2">
                  <label for="course" class="block text-sm font-medium text-gray-700">Course</label>
                  <div class="relative rounded-md shadow-sm overflow-hidden group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none group-hover:text-indigo-600 transition-colors duration-300">
                      <i class="fas fa-graduation-cap text-gray-400"></i>
                    </div>
                    <select id="course" name="course" class="input-field pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 py-3 transition-all duration-300 hover:bg-gray-100">
                      <option value="BSIT">BSIT</option>
                      <option value="BSCS">BSCS</option>
                      <option value="ACT">ACT</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Password Fields -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
  <div class="form-group" style="animation-delay: 0.7s;">
    <div class="space-y-2">
      <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
      <div class="relative rounded-md shadow-sm overflow-hidden group">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none group-hover:text-indigo-600 transition-colors duration-300">
          <i class="fas fa-lock text-gray-400"></i>
        </div>
        <input type="password" id="password" name="password" required 
               class="input-field pl-10 pr-10 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 py-3 transition-all duration-300 hover:bg-gray-100"
               autocomplete="new-password">
        <div class="absolute inset-y-0 right-0 flex items-center">
          <button type="button" id="togglePassword" class="pr-3 text-gray-400 hover:text-indigo-600 focus:outline-none" tabindex="-1">
            <i class="fas fa-eye"></i>
          </button>
        </div>
      </div>
      <div class="mt-1">
        <div class="flex items-center space-x-2 mb-1">
          <span class="text-xs text-gray-500">Password strength:</span>
          <span id="passwordStrengthText" class="text-xs font-medium text-gray-500">None</span>
        </div>
        <div class="h-1.5 w-full bg-gray-200 rounded-full overflow-hidden">
          <div id="passwordStrengthBar" class="h-full bg-gray-300 transition-all duration-300" style="width: 0%"></div>
        </div>
      </div>
      <ul class="mt-2 space-y-1 text-xs text-gray-500">
        <li id="lengthCriterion" class="flex items-center opacity-50 transition-opacity duration-300">
          <i class="fas fa-circle text-[0.5rem] mr-2 text-gray-300"></i>
          At least 8 characters long
        </li>
        <li id="numberCriterion" class="flex items-center opacity-50 transition-opacity duration-300">
          <i class="fas fa-circle text-[0.5rem] mr-2 text-gray-300"></i>
          Contains at least one number
        </li>
        <li id="uppercaseCriterion" class="flex items-center opacity-50 transition-opacity duration-300">
          <i class="fas fa-circle text-[0.5rem] mr-2 text-gray-300"></i>
          Contains at least one uppercase letter
        </li>
        <li id="specialCriterion" class="flex items-center opacity-50 transition-opacity duration-300">
          <i class="fas fa-circle text-[0.5rem] mr-2 text-gray-300"></i>
          Contains at least one special character
        </li>
      </ul>
    </div>
  </div>
  
  <div class="form-group" style="animation-delay: 0.8s;">
    <div class="space-y-2">
      <label for="confirmPassword" class="block text-sm font-medium text-gray-700">Confirm Password</label>
      <div class="relative rounded-md shadow-sm overflow-hidden group">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none group-hover:text-indigo-600 transition-colors duration-300">
          <i class="fas fa-key text-gray-400"></i>
        </div>
        <input type="password" id="confirmPassword" name="confirmPassword" required 
               class="input-field pl-10 pr-10 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 py-3 transition-all duration-300 hover:bg-gray-100"
               autocomplete="new-password">
        <div class="absolute inset-y-0 right-0 flex items-center">
          <button type="button" id="toggleConfirmPassword" class="pr-3 text-gray-400 hover:text-indigo-600 focus:outline-none" tabindex="-1">
            <i class="fas fa-eye"></i>
          </button>
        </div>
        <div id="passwordMatchIndicator" class="absolute right-10 top-1/2 -translate-y-1/2 hidden">
          <i id="passwordMatchIcon" class="fas fa-check-circle text-green-500"></i>
        </div>
      </div>
      <p id="passwordMatchMessage" class="mt-1 text-xs text-gray-500 hidden">Passwords match</p>
    </div>
  </div>
</div>
            
            <!-- Contact Info -->
            <div class="form-group" style="animation-delay: 0.9s;">
              <div class="space-y-2">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <div class="relative rounded-md shadow-sm overflow-hidden group">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none group-hover:text-indigo-600 transition-colors duration-300">
                    <i class="fas fa-envelope text-gray-400"></i>
                  </div>
                  <input type="email" id="email" name="email" required class="input-field pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 py-3 transition-all duration-300 hover:bg-gray-100">
                </div>
              </div>
            </div>
            
            <div class="form-group" style="animation-delay: 1s;">
              <div class="space-y-2">
                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                <div class="relative rounded-md shadow-sm overflow-hidden group">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none group-hover:text-indigo-600 transition-colors duration-300">
                    <i class="fas fa-home text-gray-400"></i>
                  </div>
                  <input type="text" id="address" name="address" required class="input-field pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 py-3 transition-all duration-300 hover:bg-gray-100">
                </div>
              </div>
            </div>
            
            <!-- Submit Button -->
            <div class="pt-4 form-group" style="animation-delay: 1.1s;">
              <button type="submit" name="submitRegister" id="registerBtn" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg btn-pulse">
                <i class="fas fa-user-plus mr-2"></i> Register Student
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Initialize form animations
    document.addEventListener('DOMContentLoaded', function() {
      // Apply staggered animation to form groups
      const formGroups = document.querySelectorAll('.form-group');
      formGroups.forEach((group, index) => {
        setTimeout(() => {
          group.style.animation = `staggerFade 0.5s ease forwards`;
        }, 150 * index);
      });
      
      // Password validation
      // Removed duplicate declaration of confirmField
      
      confirmField.addEventListener('input', function() {
        if (this.value !== passwordField.value) {
          this.setCustomValidity('Passwords do not match');
        } else {
          this.setCustomValidity('');
        }
        
        // Update visual feedback for password match
        if (this.value && passwordField.value) {
          const matchIndicator = document.getElementById('passwordMatchIndicator');
          const matchIcon = document.getElementById('passwordMatchIcon');
          const matchMessage = document.getElementById('passwordMatchMessage');
          
          if (matchIndicator && matchIcon && matchMessage) {
            matchIndicator.classList.remove('hidden');
            matchMessage.classList.remove('hidden');
            
            if (this.value === passwordField.value) {
              matchIcon.classList.remove('fa-times-circle', 'text-red-500');
              matchIcon.classList.add('fa-check-circle', 'text-green-500');
              matchMessage.textContent = 'Passwords match';
              matchMessage.classList.remove('text-red-500');
              matchMessage.classList.add('text-green-500');
            } else {
              matchIcon.classList.remove('fa-check-circle', 'text-green-500');
              matchIcon.classList.add('fa-times-circle', 'text-red-500');
              matchMessage.textContent = 'Passwords do not match';
              matchMessage.classList.remove('text-green-500');
              matchMessage.classList.add('text-red-500');
            }
          }
        }
      });
      
      // Handle form submission with SweetAlert2 and confetti
      const studentForm = document.getElementById('studentForm');
      studentForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate form
        if (!this.checkValidity()) {
          Swal.fire({
            title: 'Validation Error',
            text: 'Please fill out all required fields correctly',
            icon: 'error',
            confirmButtonColor: '#0ea5e9'  // Use primary-500 color code
          });
          return;
        }
        
        // Show loading state with custom animation
        Swal.fire({
          title: 'Processing Registration',
          html: `
            <div class="flex flex-col items-center">
              <div class="w-16 h-16 relative">
                <div class="w-16 h-16 rounded-full border-4 border-indigo-100 border-t-indigo-500 animate-spin"></div>
                <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center">
                  <i class="fas fa-user-plus text-indigo-500"></i>
                </div>
              </div>
              <p class="mt-4 text-gray-600">Creating student account...</p>
            </div>
          `,
          showConfirmButton: false,
          allowOutsideClick: false,
          allowEscapeKey: false
        });
        
        // Here you would normally use AJAX to submit the form without page reload
        // For demonstration purposes, we'll use a timeout to simulate server processing
        setTimeout(() => {
          // Load confetti script dynamically if not already loaded
          if (typeof JSConfetti === 'undefined') {
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/js-confetti@latest/dist/js-confetti.browser.js';
            script.onload = function() {
              // Once loaded, show success message and confetti
              showSuccessWithConfetti();
            };
            document.head.appendChild(script);
          } else {
            // Show success message and confetti
            showSuccessWithConfetti();
          }
        }, 1500);
      });
      
      // Add hover effects to input icons
      const inputGroups = document.querySelectorAll('.input-field');
      inputGroups.forEach(input => {
        input.addEventListener('focus', function() {
          const icon = this.parentElement.querySelector('i');
          if (icon) icon.classList.add('text-indigo-600');
          
          // Add subtle scale effect on focus
          this.parentElement.classList.add('scale-[1.02]');
        });
        
        input.addEventListener('blur', function() {
          const icon = this.parentElement.querySelector('i');
          if (icon) icon.classList.remove('text-indigo-600');
          
          // Remove scale effect on blur
          this.parentElement.classList.remove('scale-[1.02]');
          
          // Add validation feedback
          if (this.checkValidity()) {
            if (!this.classList.contains('is-valid')) {
              this.classList.add('is-valid');
              this.parentElement.classList.add('ring-1', 'ring-green-500/30');
              
              // Add validation icon if not already present
              if (!this.parentElement.querySelector('.valid-icon')) {
                const validIcon = document.createElement('div');
                validIcon.className = 'absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none valid-icon';
                validIcon.innerHTML = '<i class="fas fa-check-circle text-green-500"></i>';
                this.parentElement.appendChild(validIcon);
              }
            }
          } else {
            if (this.value !== '') {
              this.classList.add('is-invalid');
              this.parentElement.classList.add('ring-1', 'ring-red-500/30');
              
              // Add validation icon if not already present
              if (!this.parentElement.querySelector('.invalid-icon')) {
                const invalidIcon = document.createElement('div');
                invalidIcon.className = 'absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none invalid-icon';
                invalidIcon.innerHTML = '<i class="fas fa-exclamation-circle text-red-500"></i>';
                this.parentElement.appendChild(invalidIcon);
              }
            }
          }
        });
      });
      
      // Add password strength meter
      // Removed duplicate declaration of passwordField
      const strengthMeter = document.createElement('div');
      strengthMeter.className = 'h-1 w-full mt-1 rounded-full bg-gray-200 overflow-hidden transition-all duration-300';
      strengthMeter.innerHTML = '<div class="h-full bg-gray-400 transition-all duration-300" style="width: 0%"></div>';
      passwordField.parentElement.appendChild(strengthMeter);
      
      passwordField.addEventListener('input', function() {
        const strength = calculatePasswordStrength(this.value);
        const meter = strengthMeter.querySelector('div');
        
        // Update strength meter
        meter.style.width = strength.percent + '%';
        
        // Update color based on strength
        if (strength.score === 0) {
          meter.className = 'h-full bg-gray-400 transition-all duration-300';
        } else if (strength.score === 1) {
          meter.className = 'h-full bg-red-500 transition-all duration-300';
        } else if (strength.score === 2) {
          meter.className = 'h-full bg-yellow-500 transition-all duration-300';
        } else if (strength.score === 3) {
          meter.className = 'h-full bg-blue-500 transition-all duration-300';
        } else {
          meter.className = 'h-full bg-green-500 transition-all duration-300';
        }
      });
      
      // Password strength calculator
      function calculatePasswordStrength(password) {
        // Simple password strength calculation
        let score = 0;
        let percent = 0;
        
        if (password.length > 6) score += 1;
        if (password.length > 10) score += 1;
        if (/[A-Z]/.test(password)) score += 1;
        if (/[0-9]/.test(password)) score += 1;
        if (/[^A-Za-z0-9]/.test(password)) score += 1;
        
        percent = Math.min(100, score * 20);
        
        return { score, percent };
      }
      
      // Add confetti animation on successful form submission
      function showSuccessAnimation() {
        const canvas = document.createElement('canvas');
        canvas.id = 'confetti-canvas';
        canvas.style.position = 'fixed';
        canvas.style.top = '0';
        canvas.style.left = '0';
        canvas.style.width = '100vw';
        canvas.style.height = '100vh';
        canvas.style.pointerEvents = 'none';
        canvas.style.zIndex = '9999';
        document.body.appendChild(canvas);
        
        const confetti = new window.JSConfetti({ canvas });
        confetti.addConfetti({
          emojis: ['✨', '🎓', '👨‍🎓', '👩‍🎓', '🎉'],
          confettiNumber: 100,
        }).then(() => {
          setTimeout(() => {
            document.body.removeChild(canvas);
          }, 3000);
        });
      }

      // Add form step navigation logic
      const formSections = [
        ['idNumber', 'lName', 'fName', 'mName'], // Basic Info
        ['level', 'course'], // Academic
        ['password', 'confirmPassword'], // Security
        ['email', 'address'] // Contact
      ];

      let currentStep = 0;
      const progressBarFill = document.getElementById('progressBarFill');

      // Initially, hide all form groups except the first step
      formSections.forEach((sectionFields, index) => {
        if (index > 0) {
          sectionFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
              const formGroup = field.closest('.form-group');
              if (formGroup) formGroup.style.display = 'none';
            }
          });
        }
      });

      // Add navigation buttons
      const formButtons = document.createElement('div');
      formButtons.className = 'flex justify-between mt-6 form-group';
      formButtons.style.animationDelay = '1.2s';
      formButtons.innerHTML = `
        <button type="button" id="prevStepBtn" class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-gray-400 hidden">
          <i class="fas fa-arrow-left mr-2"></i> Previous
        </button>
        <button type="button" id="nextStepBtn" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-400 ml-auto">
          Next <i class="fas fa-arrow-right ml-2"></i>
        </button>
      `;

      // Insert navigation buttons before the submit button
      const submitButtonGroup = document.getElementById('registerBtn').closest('.form-group');
      submitButtonGroup.before(formButtons);

      // Initially hide submit button
      submitButtonGroup.style.display = 'none';

      // Setup event handlers for step navigation
      document.getElementById('nextStepBtn').addEventListener('click', function() {
        // Validate current step fields
        let isValid = true;
        formSections[currentStep].forEach(fieldId => {
          const field = document.getElementById(fieldId);
          if (field && !field.checkValidity()) {
            isValid = false;
            field.reportValidity();
          }
        });
        
        if (!isValid) return;
        
        // Hide current step fields
        formSections[currentStep].forEach(fieldId => {
          const field = document.getElementById(fieldId);
          if (field) {
            const formGroup = field.closest('.form-group');
            if (formGroup) {
              formGroup.classList.add('animate__animated', 'animate__fadeOutLeft');
              setTimeout(() => {
                formGroup.style.display = 'none';
                formGroup.classList.remove('animate__animated', 'animate__fadeOutLeft');
              }, 300);
            }
          }
        });
        
        // Update progress step indicators
        document.getElementById('step' + (currentStep + 1)).classList.add('completed');
        document.getElementById('step' + (currentStep + 1)).classList.remove('active');
        
        // Move to next step
        currentStep++;
        
        // Update progress bar fill
        progressBarFill.style.width = ((currentStep) / (formSections.length - 1) * 100) + '%';
        
        // Show next step fields
        formSections[currentStep].forEach(fieldId => {
          const field = document.getElementById(fieldId);
          if (field) {
            const formGroup = field.closest('.form-group');
            if (formGroup) {
              setTimeout(() => {
                formGroup.style.display = '';
                formGroup.classList.add('animate__animated', 'animate__fadeInRight');
                setTimeout(() => {
                  formGroup.classList.remove('animate__animated', 'animate__fadeInRight');
                }, 500);
              }, 300);
            }
          }
        });
        
        // Activate current step indicator
        document.getElementById('step' + (currentStep + 1)).classList.add('active');
        
        // Show/hide navigation buttons as needed
        document.getElementById('prevStepBtn').classList.remove('hidden');
        
        if (currentStep === formSections.length - 1) {
          // Last step - hide next, show submit
          this.style.display = 'none';
          submitButtonGroup.style.display = '';
          submitButtonGroup.classList.add('animate__animated', 'animate__fadeInUp');
        }
      });

      document.getElementById('prevStepBtn').addEventListener('click', function() {
        if (currentStep === 0) return;
        
        // Hide current step fields
        formSections[currentStep].forEach(fieldId => {
          const field = document.getElementById(fieldId);
          if (field) {
            const formGroup = field.closest('.form-group');
            if (formGroup) {
              formGroup.classList.add('animate__animated', 'animate__fadeOutRight');
              setTimeout(() => {
                formGroup.style.display = 'none';
                formGroup.classList.remove('animate__animated', 'animate__fadeOutRight');
              }, 300);
            }
          }
        });
        
        // Update progress step indicators
        document.getElementById('step' + (currentStep + 1)).classList.remove('active');
        
        // Move to previous step
        currentStep--;
        
        // Update progress bar fill
        progressBarFill.style.width = ((currentStep) / (formSections.length - 1) * 100) + '%';
        
        // Show previous step fields
        formSections[currentStep].forEach(fieldId => {
          const field = document.getElementById(fieldId);
          if (field) {
            const formGroup = field.closest('.form-group');
            if (formGroup) {
              setTimeout(() => {
                formGroup.style.display = '';
                formGroup.classList.add('animate__animated', 'animate__fadeInLeft');
                setTimeout(() => {
                  formGroup.classList.remove('animate__animated', 'animate__fadeInLeft');
                }, 500);
              }, 300);
            }
          }
        });
        
        // Activate current step indicator
        document.getElementById('step' + (currentStep + 1)).classList.remove('completed');
        document.getElementById('step' + (currentStep + 1)).classList.add('active');
        
        // Show/hide navigation buttons as needed
        if (currentStep === 0) {
          this.classList.add('hidden');
        }
        
        // Make sure next button is visible when going back
        document.getElementById('nextStepBtn').style.display = '';
        submitButtonGroup.style.display = 'none';
      });

      // Add this inside your existing DOMContentLoaded event handler

// Password visibility toggle
document.getElementById('togglePassword').addEventListener('click', function() {
  const passwordField = document.getElementById('password');
  const icon = this.querySelector('i');
  
  if (passwordField.type === 'password') {
    passwordField.type = 'text';
    icon.classList.remove('fa-eye');
    icon.classList.add('fa-eye-slash');
    
    // Auto-hide after 2 seconds for security
    setTimeout(() => {
      passwordField.type = 'password';
      icon.classList.remove('fa-eye-slash');
      icon.classList.add('fa-eye');
    }, 2000);
  } else {
    passwordField.type = 'password';
    icon.classList.remove('fa-eye-slash');
    icon.classList.add('fa-eye');
  }
});

document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
  const confirmField = document.getElementById('confirmPassword');
  const icon = this.querySelector('i');
  
  if (confirmField.type === 'password') {
    confirmField.type = 'text';
    icon.classList.remove('fa-eye');
    icon.classList.add('fa-eye-slash');
    
    // Auto-hide after 2 seconds for security
    setTimeout(() => {
      confirmField.type = 'password';
      icon.classList.remove('fa-eye-slash');
      icon.classList.add('fa-eye');
    }, 2000);
  } else {
    confirmField.type = 'password';
    icon.classList.remove('fa-eye-slash');
    icon.classList.add('fa-eye');
  }
});

// Enhanced password strength meter
const passwordField = document.getElementById('password');
const confirmField = document.getElementById('confirmPassword');
const strengthBar = document.getElementById('passwordStrengthBar');
const strengthText = document.getElementById('passwordStrengthText');

// Password criteria elements
const lengthCriterion = document.getElementById('lengthCriterion');
const numberCriterion = document.getElementById('numberCriterion');
const uppercaseCriterion = document.getElementById('uppercaseCriterion');
const specialCriterion = document.getElementById('specialCriterion');

// Password match elements
const matchIndicator = document.getElementById('passwordMatchIndicator');
const matchIcon = document.getElementById('passwordMatchIcon');
const matchMessage = document.getElementById('passwordMatchMessage');

passwordField.addEventListener('input', function() {
  const password = this.value;
  const strength = calculatePasswordStrength(password);
  
  // Update strength bar
  strengthBar.style.width = strength.percent + '%';
  
  // Update color based on strength using primary colors
  if (strength.score === 0) {
    strengthBar.className = 'h-full bg-gray-300 transition-all duration-300';
    strengthText.textContent = 'None';
    strengthText.className = 'text-xs font-medium text-gray-500';
  } else if (strength.score === 1) {
    strengthBar.className = 'h-full bg-red-500 transition-all duration-300';
    strengthText.textContent = 'Weak';
    strengthText.className = 'text-xs font-medium text-red-500';
  } else if (strength.score === 2) {
    strengthBar.className = 'h-full bg-yellow-500 transition-all duration-300';
    strengthText.textContent = 'Fair';
    strengthText.className = 'text-xs font-medium text-yellow-600';
  } else if (strength.score === 3) {
    strengthBar.className = 'h-full bg-primary-400 transition-all duration-300';
    strengthText.textContent = 'Good';
    strengthText.className = 'text-xs font-medium text-primary-500';
  } else {
    strengthBar.className = 'h-full bg-green-500 transition-all duration-300';
    strengthText.textContent = 'Strong';
    strengthText.className = 'text-xs font-medium text-green-500';
  }
});

confirmField.addEventListener('input', checkPasswordMatch);

function updateCriterion(element, isValid) {
  const icon = element.querySelector('i');
  
  if (isValid) {
    element.classList.remove('opacity-50');
    icon.classList.remove('fa-circle', 'text-gray-300');
    icon.classList.add('fa-check-circle', 'text-green-500');
  } else {
    element.classList.add('opacity-50');
    icon.classList.remove('fa-check-circle', 'text-green-500');
    icon.classList.add('fa-circle', 'text-gray-300');
  }
}

function checkPasswordMatch() {
  const password = passwordField.value;
  const confirmPassword = confirmField.value;
  
  if (confirmPassword === '') {
    // If confirm field is empty, hide match indicator
    matchIndicator.classList.add('hidden');
    matchMessage.classList.add('hidden');
    confirmField.classList.remove('is-valid', 'is-invalid');
    return;
  }
  
  matchIndicator.classList.remove('hidden');
  matchMessage.classList.remove('hidden');
  
  if (password === confirmPassword) {
    // Passwords match
    matchIcon.classList.remove('fa-times-circle', 'text-red-500');
    matchIcon.classList.add('fa-check-circle', 'text-green-500');
    matchMessage.textContent = 'Passwords match';
    matchMessage.classList.remove('text-red-500');
    matchMessage.classList.add('text-green-500');
    confirmField.classList.add('is-valid');
    confirmField.classList.remove('is-invalid');
    confirmField.setCustomValidity('');
  } else {
    // Passwords don't match
    matchIcon.classList.remove('fa-check-circle', 'text-green-500');
    matchIcon.classList.add('fa-times-circle', 'text-red-500');
    matchMessage.textContent = 'Passwords do not match';
    matchMessage.classList.remove('text-green-500');
    matchMessage.classList.add('text-red-500');
    confirmField.classList.add('is-invalid');
    confirmField.classList.remove('is-valid');
    confirmField.setCustomValidity('Passwords do not match');
  }
}

function calculatePasswordStrength(password) {
  // More comprehensive password strength calculation
  let score = 0;
  let percent = 0;
  
  // Length check (up to 2 points)
  if (password.length >= 8) score += 1;
  if (password.length >= 12) score += 1;
  
  // Complexity checks (1 point each)
  if (/[A-Z]/.test(password)) score += 1; // Uppercase
  if (/[0-9]/.test(password)) score += 1; // Numbers
  if (/[^A-Za-z0-9]/.test(password)) score += 1; // Special chars
  
  // Mix of character types (1 point)
  let charTypes = 0;
  if (/[a-z]/.test(password)) charTypes++;
  if (/[A-Z]/.test(password)) charTypes++;
  if (/[0-9]/.test(password)) charTypes++;
  if (/[^A-Za-z0-9]/.test(password)) charTypes++;
  if (charTypes >= 3) score += 1;
  
  // Calculate percentage (max score is 6)
  percent = Math.min(100, Math.round((score / 6) * 100));
  
  // Normalize score to 0-4 range
  let normalizedScore = Math.min(4, Math.floor(score * 0.7));
  
  return { score: normalizedScore, percent };
}

// Show a notification if caps lock is on
passwordField.addEventListener('keydown', function(e) {
  const capsLockOn = e.getModifierState('CapsLock');
  let capsLockWarning = document.getElementById('capsLockWarning');
  
  if (capsLockOn) {
    if (!capsLockWarning) {
      capsLockWarning = document.createElement('div');
      capsLockWarning.id = 'capsLockWarning';
      capsLockWarning.className = 'text-xs text-amber-600 mt-1 flex items-center animate__animated animate__fadeIn';
      capsLockWarning.innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i> Caps Lock is on';
      this.parentElement.appendChild(capsLockWarning);
    }
  } else if (capsLockWarning) {
    capsLockWarning.classList.add('animate__fadeOut');
    setTimeout(() => {
      capsLockWarning.remove();
    }, 300);
  }
});

confirmField.addEventListener('keydown', function(e) {
  const capsLockOn = e.getModifierState('CapsLock');
  let capsLockWarning = document.getElementById('capsLockWarningConfirm');
  
  if (capsLockOn) {
    if (!capsLockWarning) {
      capsLockWarning = document.createElement('div');
      capsLockWarning.id = 'capsLockWarningConfirm';
      capsLockWarning.className = 'text-xs text-amber-600 mt-1 flex items-center animate__animated animate__fadeIn';
      capsLockWarning.innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i> Caps Lock is on';
      this.parentElement.appendChild(capsLockWarning);
    }
  } else if (capsLockWarning) {
    capsLockWarning.classList.add('animate__fadeOut');
    setTimeout(() => {
      capsLockWarning.remove();
    }, 300);
  }
});

// Additional feature: Password generation
const generatePasswordBtn = document.createElement('button');
generatePasswordBtn.type = 'button';
generatePasswordBtn.className = 'text-xs mt-1 text-primary-600 hover:text-primary-800 focus:outline-none inline-flex items-center';
generatePasswordBtn.innerHTML = '<i class="fas fa-magic mr-1"></i> Generate secure password';
passwordField.parentElement.insertBefore(generatePasswordBtn, strengthBar.parentElement);

generatePasswordBtn.addEventListener('click', function() {
  const generatedPassword = generateSecurePassword(12);
  passwordField.value = generatedPassword;
  passwordField.type = 'text';
  
  // Trigger the input event to update strength meter
  const inputEvent = new Event('input', { bubbles: true });
  passwordField.dispatchEvent(inputEvent);
  
  // Auto-fill confirm password
  confirmField.value = generatedPassword;
  confirmField.type = 'text';
  confirmField.dispatchEvent(inputEvent);
  
  // Show the password for 3 seconds then hide it
  setTimeout(() => {
    passwordField.type = 'password';
    confirmField.type = 'password';
    document.getElementById('togglePassword').querySelector('i').className = 'fas fa-eye';
    document.getElementById('toggleConfirmPassword').querySelector('i').className = 'fas fa-eye';
    
    // Show success notification
    const notification = document.createElement('div');
    notification.className = 'text-xs text-green-600 mt-1 flex items-center animate__animated animate__fadeIn';
    notification.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Secure password generated and copied to clipboard';
    passwordField.parentElement.appendChild(notification);
    
    // Copy to clipboard
    navigator.clipboard.writeText(generatedPassword).catch(err => {
      console.error('Could not copy password: ', err);
    });
    
    // Remove notification after 3 seconds
    setTimeout(() => {
      notification.classList.add('animate__fadeOut');
      setTimeout(() => notification.remove(), 500);
    }, 3000);
  }, 3000);
});

function generateSecurePassword(length = 12) {
  const lowercase = 'abcdefghijklmnopqrstuvwxyz';
  const uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  const numbers = '0123456789';
  const special = '!@#$%^&*()-_=+[]{}|;:,.<>?';
  
  const allChars = lowercase + uppercase + numbers + special;
  let password = '';
  
  // Ensure at least one of each character type
  password += lowercase.charAt(Math.floor(Math.random() * lowercase.length));
  password += uppercase.charAt(Math.floor(Math.random() * uppercase.length));
  password += numbers.charAt(Math.floor(Math.random() * numbers.length));
  password += special.charAt(Math.floor(Math.random() * special.length));
  
  // Fill the rest with random characters
  for (let i = 4; i < length; i++) {
    password += allChars.charAt(Math.floor(Math.random() * allChars.length));
  }
  
  // Shuffle the password
  return password.split('').sort(() => 0.5 - Math.random()).join('');
}
    });
  </script>
</body>
</html>