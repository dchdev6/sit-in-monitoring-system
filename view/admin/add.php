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
  <style>
    .fade-in-up {
      animation: fadeInUp 0.6s ease-out;
    }
    
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .input-field {
      transition: all 0.3s ease;
    }
    
    .input-field:focus {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.25);
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
      background-color: #4f46e5;
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
        box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.7);
      }
      70% {
        box-shadow: 0 0 0 10px rgba(79, 70, 229, 0);
      }
      100% {
        box-shadow: 0 0 0 0 rgba(79, 70, 229, 0);
      }
    }
  </style>
</head>
<body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen">
  <!-- Navbar would be included here in your PHP file -->
  
  <div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto animate__animated animate__fadeIn">
      <!-- Back button -->
      <div class="mb-6 animate__animated animate__slideInLeft">
        <a href="Students.php" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg transition duration-300 ease-in-out hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 hover:-translate-x-1">
          <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
      </div>
      
      <div class="bg-white rounded-2xl shadow-xl overflow-hidden fade-in-up">
        <div class="p-8">
          <h1 class="text-3xl font-bold text-center text-gray-800 mb-8 animate__animated animate__fadeInDown">
            <i class="fas fa-user-plus text-indigo-600 mr-2"></i>
            <span class="section-title">Add Student</span>
          </h1>
          
          <form id="studentForm" action="Add.php" method="POST" class="space-y-6">
            <!-- ID Number -->
            <div class="form-group" style="animation-delay: 0.1s;">
              <div class="space-y-2">
                <label for="idNumber" class="block text-sm font-medium text-gray-700">ID Number</label>
                <div class="relative rounded-md shadow-sm overflow-hidden group">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none group-hover:text-indigo-600 transition-colors duration-300">
                    <i class="fas fa-id-card text-gray-400"></i>
                  </div>
                  <input type="text" id="idNumber" name="idNumber" required class="input-field pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 py-3 transition-all duration-300 hover:bg-gray-100">
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
                    <input type="password" id="password" name="password" required class="input-field pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 py-3 transition-all duration-300 hover:bg-gray-100">
                  </div>
                </div>
              </div>
              
              <div class="form-group" style="animation-delay: 0.8s;">
                <div class="space-y-2">
                  <label for="confirmPassword" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                  <div class="relative rounded-md shadow-sm overflow-hidden group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none group-hover:text-indigo-600 transition-colors duration-300">
                      <i class="fas fa-key text-gray-400"></i>
                    </div>
                    <input type="password" id="confirmPassword" name="confirmPassword" required class="input-field pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 py-3 transition-all duration-300 hover:bg-gray-100">
                  </div>
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
              <button type="submit" name="submitRegister" id="registerBtn" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg btn-pulse">
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
      const passwordField = document.getElementById('password');
      const confirmField = document.getElementById('confirmPassword');
      
      confirmField.addEventListener('input', function() {
        if (this.value !== passwordField.value) {
          this.setCustomValidity('Passwords do not match');
        } else {
          this.setCustomValidity('');
        }
      });
      
      // Handle form submission with SweetAlert2
      const studentForm = document.getElementById('studentForm');
      studentForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate form
        if (!this.checkValidity()) {
          Swal.fire({
            title: 'Validation Error',
            text: 'Please fill out all required fields correctly',
            icon: 'error',
            confirmButtonColor: '#4F46E5'
          });
          return;
        }
        
        // Show loading state
        Swal.fire({
          title: 'Processing...',
          html: 'Registering new student',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });
        
        // Here you would normally use AJAX to submit the form, but for this example
        // we'll just simulate a successful submission after a short delay
        setTimeout(() => {
          Swal.fire({
            title: 'Success!',
            text: 'Student registered successfully',
            icon: 'success',
            confirmButtonColor: '#4F46E5'
          }).then((result) => {
            if (result.isConfirmed) {
              // In a real application, you might redirect here
              // window.location.href = 'Students.php';
              
              // For demo purposes, we'll just submit the form
              this.submit();
            }
          });
        }, 1500);
      });
      
      // Add hover effects to input icons
      const inputGroups = document.querySelectorAll('.input-field');
      inputGroups.forEach(input => {
        input.addEventListener('focus', function() {
          const icon = this.parentElement.querySelector('i');
          if (icon) icon.classList.add('text-indigo-600');
        });
        
        input.addEventListener('blur', function() {
          const icon = this.parentElement.querySelector('i');
          if (icon) icon.classList.remove('text-indigo-600');
        });
      });
    });
  </script>
</body>
</html>