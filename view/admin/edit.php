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
</head>

<body class="bg-gray-50">
  <div class="min-h-screen py-8">
    <div class="container mx-auto px-4">
      <div class="max-w-6xl mx-auto animate__animated animate__fadeIn">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
          <div class="md:flex">
            <div class="md:w-1/2 p-8 md:p-12 animate__animated animate__fadeInLeft">
              <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">
                <i class="fas fa-user-edit mr-2 text-blue-600"></i>Edit Profile
              </h1>
              
              <form action="Edit.php" method="post" id="editProfileForm">
                <div class="space-y-5">
                  <!-- ID Number -->
                  <div class="relative transition-all duration-300 ease-in-out hover:scale-102">
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="idNumber">ID Number</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-id-card text-gray-400"></i>
                      </div>
                      <input type="text" value="<?php echo $_SESSION["id_number"]; ?>" id="idNumber" name="idNumber" readonly
                        class="bg-gray-100 focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-4 py-3 border-gray-300 rounded-lg text-gray-500"
                        placeholder="ID Number" />
                    </div>
                  </div>

                  <!-- Last Name -->
                  <div class="relative transition-all duration-300 ease-in-out hover:scale-102">
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="lName">Last Name</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                      </div>
                      <input type="text" value="<?php echo $_SESSION["lname"]; ?>" id="lName" name="lName" required
                        class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-4 py-3 border-gray-300 rounded-lg"
                        placeholder="Last Name" />
                    </div>
                  </div>

                  <!-- First Name -->
                  <div class="relative transition-all duration-300 ease-in-out hover:scale-102">
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="fName">First Name</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                      </div>
                      <input type="text" value="<?php echo $_SESSION["fname"]; ?>" id="fName" name="fName" required
                        class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-4 py-3 border-gray-300 rounded-lg"
                        placeholder="First Name" />
                    </div>
                  </div>

                  <!-- Middle Name -->
                  <div class="relative transition-all duration-300 ease-in-out hover:scale-102">
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="mName">Middle Name</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                      </div>
                      <input type="text" value="<?php echo $_SESSION["mname"]; ?>" id="mName" name="mName" required
                        class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-4 py-3 border-gray-300 rounded-lg"
                        placeholder="Middle Name" />
                    </div>
                  </div>

                  <!-- Year Level -->
                  <div class="relative transition-all duration-300 ease-in-out hover:scale-102">
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="level">Course Level</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-layer-group text-gray-400"></i>
                      </div>
                      <select name="level" id="level" 
                        class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-4 py-3 border-gray-300 rounded-lg">
                        <option value="1" <?php echo ($_SESSION["yearLevel"] == 1) ? 'selected' : ''; ?>>1</option>
                        <option value="2" <?php echo ($_SESSION["yearLevel"] == 2) ? 'selected' : ''; ?>>2</option>
                        <option value="3" <?php echo ($_SESSION["yearLevel"] == 3) ? 'selected' : ''; ?>>3</option>
                        <option value="4" <?php echo ($_SESSION["yearLevel"] == 4) ? 'selected' : ''; ?>>4</option>
                      </select>
                    </div>
                  </div>

                  <!-- Email -->
                  <div class="relative transition-all duration-300 ease-in-out hover:scale-102">
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="email">Email</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-400"></i>
                      </div>
                      <input type="email" value="<?php echo $_SESSION["email"]; ?>" id="email" name="email" required
                        class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-4 py-3 border-gray-300 rounded-lg"
                        placeholder="Email" />
                    </div>
                  </div>

                  <!-- Course -->
                  <div class="relative transition-all duration-300 ease-in-out hover:scale-102">
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="course">Course</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-graduation-cap text-gray-400"></i>
                      </div>
                      <select name="course" id="course" 
                        class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-4 py-3 border-gray-300 rounded-lg">
                        <option value="BSIT" <?php echo ($_SESSION["course"] == "BSIT") ? 'selected' : ''; ?>>BSIT</option>
                        <option value="BSCS" <?php echo ($_SESSION["course"] == "BSCS") ? 'selected' : ''; ?>>BSCS</option>
                        <option value="ACT" <?php echo ($_SESSION["course"] == "ACT") ? 'selected' : ''; ?>>ACT</option>
                      </select>
                    </div>
                  </div>

                  <!-- Address -->
                  <div class="relative transition-all duration-300 ease-in-out hover:scale-102">
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="address">Address</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-map-marker-alt text-gray-400"></i>
                      </div>
                      <input type="text" value="<?php echo $_SESSION["address"]; ?>" id="address" name="address" required
                        class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-4 py-3 border-gray-300 rounded-lg"
                        placeholder="Address" />
                    </div>
                  </div>

                  <!-- Buttons -->
                  <div class="flex space-x-4 pt-4">
                    <button type="submit" name="submitEdit" id="saveButton"
                      class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg shadow transition-all duration-300 ease-in-out hover:shadow-lg transform hover:-translate-y-1 flex items-center justify-center">
                      <i class="fas fa-save mr-2"></i> Save Changes
                    </button>
                    <button type="button" id="resetPasswordBtn"
                      class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg shadow transition-all duration-300 ease-in-out hover:shadow-lg transform hover:-translate-y-1 flex items-center justify-center">
                      <i class="fas fa-key mr-2"></i> Reset Password
                    </button>
                  </div>
                </div>
              </form>
            </div>
            
            <div class="md:w-1/2 bg-gradient-to-br from-blue-500 to-indigo-600 p-8 md:p-12 flex items-center justify-center animate__animated animate__fadeInRight">
              <div class="text-center">
                <img src="../../images/sign.webp" alt="Profile Illustration" class="w-3/4 mx-auto rounded-lg shadow-lg transform transition-all duration-500 hover:scale-105">
                <div class="mt-8 text-white">
                  <h3 class="text-2xl font-bold mb-2"><?php echo $_SESSION["name"]; ?></h3>
                  <p class="text-blue-100 mb-1"><i class="fas fa-graduation-cap mr-2"></i><?php echo $_SESSION["course"]; ?> - Year <?php echo $_SESSION["yearLevel"]; ?></p>
                  <p class="text-blue-100"><i class="fas fa-envelope mr-2"></i><?php echo $_SESSION["email"]; ?></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Initialize form elements to show currently selected options
    document.addEventListener('DOMContentLoaded', function() {
      // Form submission with SweetAlert2
      document.getElementById('editProfileForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
          title: 'Save Changes?',
          text: 'Do you want to update your profile information?',
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
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
              html: 'Updating your profile information',
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
          text: 'Are you sure you want to reset your password?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, reset it!',
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
      
      // Add input field animations
      const inputFields = document.querySelectorAll('input, select');
      inputFields.forEach(field => {
        field.addEventListener('focus', function() {
          this.parentElement.parentElement.classList.add('scale-105');
          this.parentElement.parentElement.classList.add('shadow-md');
        });
        
        field.addEventListener('blur', function() {
          this.parentElement.parentElement.classList.remove('scale-105');
          this.parentElement.parentElement.classList.remove('shadow-md');
        });
      });
    });

    // Success message if coming back from a successful update
    <?php if (isset($_GET['success']) && $_GET['success'] == 'true'): ?>
    Swal.fire({
      title: 'Success!',
      text: 'Your profile has been updated successfully.',
      icon: 'success',
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
      text: 'There was a problem updating your profile.',
      icon: 'error',
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