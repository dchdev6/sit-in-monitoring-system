<?php
include '../../includes/navbar_admin.php';

$user = retrieve_edit_student($_SESSION["editNum"]);

if ($user["id_number"] != null) {
  $_SESSION['id_number'] = $user["id_number"];
  $_SESSION['name'] =  $user["firstName"] . " " . $user["middleName"] . " " . $user["lastName"];
  $_SESSION['fname'] = $user["firstName"];
  $_SESSION['lname'] = $user["lastName"];
  $_SESSION['mname'] = $user["middleName"];
  $_SESSION['yearLevel'] = $user["yearLevel"];
  $_SESSION['course'] = $user["course"];
  $_SESSION['email'] = $user["email"];
  $_SESSION['address'] = $user["address"];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Profile</title>
  
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <!-- Animation library -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  
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
            }
          },
          boxShadow: {
            'input-focus': '0 0 0 3px rgba(14, 165, 233, 0.15)',
          }
        }
      }
    }
  </script>
</head>

<body class="bg-gray-50">
  <div class="min-h-screen py-8">
    <div class="container mx-auto px-4">
      <div class="max-w-6xl mx-auto animate__animated animate__fadeIn">

        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
          <div class="md:flex">
            <div class="md:w-1/2 p-8 md:p-10 animate__animated animate__fadeInLeft">
              <div class="flex items-center justify-center mb-8">
                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-primary-100 mr-3">
                  <i class="fas fa-user-edit text-primary-600 text-xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Edit Student Profile</h1>
              </div>
              
              <form action="Edit.php" method="post" id="editProfileForm">
                <div class="space-y-5">
                  <!-- ID Number -->
                  <div class="group">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5" for="idNumber">ID Number</label>
                    <div class="relative rounded-lg shadow-sm">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-id-card text-gray-400"></i>
                      </div>
                      <input type="text" value="<?php echo $_SESSION["id_number"]; ?>" id="idNumber" name="idNumber" readonly
                        class="bg-gray-50 focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 pr-4 py-3.5 border border-gray-300 rounded-lg text-gray-500 transition-all duration-200"
                        placeholder="ID Number" />
                    </div>
                    <p class="mt-1 text-xs text-gray-500 pl-1">Student identification number (cannot be changed)</p>
                  </div>

                  <!-- Name Section -->
                  <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="font-medium text-gray-700 mb-3 flex items-center text-sm">
                      <i class="fas fa-user-circle mr-2 text-primary-500"></i>
                      Personal Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                      <!-- Last Name -->
                      <div class="group">
                        <label class="block text-xs font-medium text-gray-700 mb-1" for="lName">Last Name</label>
                        <div class="relative rounded-lg">
                          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-primary-400"></i>
                          </div>
                          <input type="text" value="<?php echo $_SESSION["lname"]; ?>" id="lName" name="lName" required
                            class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg transition-all duration-200 focus:shadow-input-focus"
                            placeholder="Last Name" />
                        </div>
                      </div>

                      <!-- First Name -->
                      <div class="group">
                        <label class="block text-xs font-medium text-gray-700 mb-1" for="fName">First Name</label>
                        <div class="relative rounded-lg">
                          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-primary-400"></i>
                          </div>
                          <input type="text" value="<?php echo $_SESSION["fname"]; ?>" id="fName" name="fName" required
                            class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg transition-all duration-200 focus:shadow-input-focus"
                            placeholder="First Name" />
                        </div>
                      </div>
                    </div>

                    <!-- Middle Name -->
                    <div class="group mt-4">
                      <label class="block text-xs font-medium text-gray-700 mb-1" for="mName">Middle Name</label>
                      <div class="relative rounded-lg">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                          <i class="fas fa-user text-primary-400"></i>
                        </div>
                        <input type="text" value="<?php echo $_SESSION["mname"]; ?>" id="mName" name="mName"
                          class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg transition-all duration-200 focus:shadow-input-focus"
                          placeholder="Middle Name" />
                      </div>
                    </div>
                  </div>

                  <!-- Academic Information -->
                  <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="font-medium text-gray-700 mb-3 flex items-center text-sm">
                      <i class="fas fa-graduation-cap mr-2 text-primary-500"></i>
                      Academic Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                      <!-- Year Level -->
                      <div class="group">
                        <label class="block text-xs font-medium text-gray-700 mb-1" for="level">Year Level</label>
                        <div class="relative rounded-lg">
                          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-layer-group text-primary-400"></i>
                          </div>
                          <select name="level" id="level" 
                            class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg appearance-none transition-all duration-200 focus:shadow-input-focus">
                            <option value="1" <?php echo ($_SESSION["yearLevel"] == 1) ? 'selected' : ''; ?>>First Year</option>
                            <option value="2" <?php echo ($_SESSION["yearLevel"] == 2) ? 'selected' : ''; ?>>Second Year</option>
                            <option value="3" <?php echo ($_SESSION["yearLevel"] == 3) ? 'selected' : ''; ?>>Third Year</option>
                            <option value="4" <?php echo ($_SESSION["yearLevel"] == 4) ? 'selected' : ''; ?>>Fourth Year</option>
                          </select>
                          <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400"></i>
                          </div>
                        </div>
                      </div>

                      <!-- Course -->
                      <div class="group">
                        <label class="block text-xs font-medium text-gray-700 mb-1" for="course">Course</label>
                        <div class="relative rounded-lg">
                          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-graduation-cap text-primary-400"></i>
                          </div>
                          <select name="course" id="course" 
                            class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg appearance-none transition-all duration-200 focus:shadow-input-focus">
                            <option value="BSIT" <?php echo ($_SESSION["course"] == "BSIT") ? 'selected' : ''; ?>>BS Information Technology</option>
                            <option value="BSCS" <?php echo ($_SESSION["course"] == "BSCS") ? 'selected' : ''; ?>>BS Computer Science</option>
                            <option value="ACT" <?php echo ($_SESSION["course"] == "ACT") ? 'selected' : ''; ?>>Associate in Computer Technology</option>
                          </select>
                          <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Contact Information -->
                  <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="font-medium text-gray-700 mb-3 flex items-center text-sm">
                      <i class="fas fa-address-card mr-2 text-primary-500"></i>
                      Contact Information
                    </h3>

                    <!-- Email -->
                    <div class="group mb-4">
                      <label class="block text-xs font-medium text-gray-700 mb-1" for="email">Email Address</label>
                      <div class="relative rounded-lg">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                          <i class="fas fa-envelope text-primary-400"></i>
                        </div>
                        <input type="email" value="<?php echo $_SESSION["email"]; ?>" id="email" name="email" required
                          class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg transition-all duration-200 focus:shadow-input-focus"
                          placeholder="student@example.com" />
                      </div>
                    </div>

                    <!-- Address -->
                    <div class="group">
                      <label class="block text-xs font-medium text-gray-700 mb-1" for="address">Complete Address</label>
                      <div class="relative rounded-lg">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                          <i class="fas fa-map-marker-alt text-primary-400"></i>
                        </div>
                        <input type="text" value="<?php echo $_SESSION["address"]; ?>" id="address" name="address" required
                          class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg transition-all duration-200 focus:shadow-input-focus"
                          placeholder="Student residential address" />
                      </div>
                    </div>
                  </div>

                  <!-- Buttons -->
                  <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    <button type="submit" name="submitEdit" id="saveButton"
                      class="flex-1 bg-primary-500 hover:bg-primary-600 text-white font-medium py-3 px-4 rounded-lg shadow-sm transition-all duration-300 flex items-center justify-center hover:shadow">
                      <i class="fas fa-save mr-2"></i> Save Changes
                    </button>
                    <button type="button" id="resetPasswordBtn"
                      class="flex-1 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-3 px-4 rounded-lg shadow-sm transition-all duration-300 flex items-center justify-center hover:shadow">
                      <i class="fas fa-key mr-2 text-red-500"></i> Reset Password
                    </button>
                  </div>
                </div>
              </form>
            </div>
            
            <div class="md:w-1/2 bg-gradient-to-br from-primary-500 to-blue-600 p-8 md:p-10 flex items-center justify-center animate__animated animate__fadeInRight">
              <div class="text-center">
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-5 shadow-lg border border-white/20">
                  <div class="w-24 h-24 rounded-full bg-white/20 backdrop-blur mx-auto mb-4 flex items-center justify-center">
                    <i class="fas fa-user-graduate text-white text-4xl"></i>
                  </div>
                  
                  <h3 class="text-2xl font-bold text-white mb-3"><?php echo $_SESSION["name"]; ?></h3>
                  
                  <div class="space-y-3">
                    <div class="bg-white/10 backdrop-blur rounded-lg px-4 py-3 flex items-center text-white">
                      <i class="fas fa-id-card mr-3 text-primary-200"></i>
                      <div class="text-left">
                        <div class="text-xs text-primary-100">Student ID</div>
                        <div class="font-medium"><?php echo $_SESSION["id_number"]; ?></div>
                      </div>
                    </div>
                    
                    <div class="bg-white/10 backdrop-blur rounded-lg px-4 py-3 flex items-center text-white">
                      <i class="fas fa-graduation-cap mr-3 text-primary-200"></i>
                      <div class="text-left">
                        <div class="text-xs text-primary-100">Program</div>
                        <div class="font-medium"><?php echo $_SESSION["course"]; ?> - Year <?php echo $_SESSION["yearLevel"]; ?></div>
                      </div>
                    </div>
                    
                    <div class="bg-white/10 backdrop-blur rounded-lg px-4 py-3 flex items-center text-white">
                      <i class="fas fa-envelope mr-3 text-primary-200"></i>
                      <div class="text-left">
                        <div class="text-xs text-primary-100">Email Address</div>
                        <div class="font-medium"><?php echo $_SESSION["email"]; ?></div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="mt-6 text-xs text-primary-100">
                    <p>This information will update when you save your changes.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
      

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Form submission with SweetAlert2
      document.getElementById('editProfileForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
          title: 'Save Changes?',
          text: 'Do you want to update this student\'s profile information?',
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#0ea5e9',
          cancelButtonColor: '#64748b',
          confirmButtonText: 'Yes, save it!',
          cancelButtonText: 'Cancel',
          backdrop: `rgba(0,0,123,0.4)`,
          showClass: {
            popup: 'animate__animated animate__fadeInDown'
          },
          hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
          }
        }).then((result) => {
          if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
              title: 'Saving...',
              html: 'Updating student profile information',
              timer: 1000,
              timerProgressBar: true,
              didOpen: () => {
                Swal.showLoading();
              }
            }).then(() => {
              // Submit the form
              this.submit();
            });
          }
        });
      });

      // Reset Password button
      document.getElementById('resetPasswordBtn').addEventListener('click', function() {
        Swal.fire({
          title: 'Reset Password?',
          html: 'Are you sure you want to reset this student\'s password? <br><small class="text-gray-500">The password will be reset to the default.</small>',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#0ea5e9',
          cancelButtonColor: '#64748b',
          confirmButtonText: '<i class="fas fa-key mr-2"></i>Reset Password',
          cancelButtonText: 'Cancel',
          backdrop: `rgba(0,0,123,0.4)`,
          showClass: {
            popup: 'animate__animated animate__fadeInDown'
          },
          hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
          }
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = 'Reset.php';
          }
        });
      });
      
      // Add input field enhancements
      const inputFields = document.querySelectorAll('input, select');
      inputFields.forEach(field => {
        field.addEventListener('focus', function() {
          this.closest('.group').classList.add('is-focused');
        });
        
        field.addEventListener('blur', function() {
          this.closest('.group').classList.remove('is-focused');
        });
      });
    });

    // Success message if coming back from a successful update
    <?php if (isset($_GET['success']) && $_GET['success'] == 'true'): ?>
    Swal.fire({
      title: 'Success!',
      text: 'Student profile has been updated successfully.',
      icon: 'success',
      confirmButtonColor: '#0ea5e9',
      showClass: {
        popup: 'animate__animated animate__fadeInDown'
      },
      hideClass: {
        popup: 'animate__animated animate__fadeOutUp'
      }
    });
    <?php endif; ?>
    
    // Error message
    <?php if (isset($_GET['error']) && $_GET['error'] == 'true'): ?>
    Swal.fire({
      title: 'Error!',
      text: 'There was a problem updating the student profile.',
      icon: 'error',
      confirmButtonColor: '#0ea5e9',
      showClass: {
        popup: 'animate__animated animate__fadeInDown'
      },
      hideClass: {
        popup: 'animate__animated animate__fadeOutUp'
      }
    });
    <?php endif; ?>
  </script>
</body>
</html>