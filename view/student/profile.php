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
<body class="bg-gray-50 font-sans text-gray-800">

<div class="container mx-auto px-4 py-8 max-w-5xl">
    <h1 class="text-2xl font-bold text-gray-800 mb-6 flex items-center animate-slide-in-left">
        <i class="fas fa-user-edit mr-3 text-primary-600 animate-float"></i>
        Edit Profile
    </h1>

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
                                    class="bg-gray-50 border border-gray-300 text-gray-700 rounded-lg block w-full pl-10 p-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                    name="idNumber" readonly>
                            </div>
                            <input type="hidden" name="idNumber" value="<?php echo $_SESSION['id_number']; ?>">
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
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
                                    name="mName" required>
                            </div>
                        </div>
                        
                        <div>
                            <label for="courseLevel" class="block text-sm font-medium text-gray-700 mb-1">Course Level</label>
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
                                    <option value="BSCS" <?php echo ($_SESSION["course"] == 'BSCS') ? 'selected' : ''; ?>>Bachelor of Science in Computer Science</option>
                                    <option value="BSIT" <?php echo ($_SESSION["course"] == 'BSIT') ? 'selected' : ''; ?>>Bachelor of Science in Information Technology</option>
                                    <option value="BSIS" <?php echo ($_SESSION["course"] == 'BSIS') ? 'selected' : ''; ?>>Bachelor of Science in Information System</option>
                                    <option value="BSP" <?php echo ($_SESSION["course"] == 'BSP') ? 'selected' : ''; ?>>Bachelor of Science in Psychology</option>
                                    <option value="BSBA" <?php echo ($_SESSION["course"] == 'BSBA') ? 'selected' : ''; ?>>Bachelor of Science in Business Administration</option>
                                    <option value="BSN" <?php echo ($_SESSION["course"] == 'BSN') ? 'selected' : ''; ?>>Bachelor of Science in Nursing</option>
                                    <option value="BSM" <?php echo ($_SESSION["course"] == 'BSM') ? 'selected' : ''; ?>>Bachelor of Science in Midwifery</option>
                                    <option value="BAB" <?php echo ($_SESSION["course"] == 'BAB') ? 'selected' : ''; ?>>Bachelor of Arts in Broadcasting</option>
                                    <option value="BAC" <?php echo ($_SESSION["course"] == 'BAC') ? 'selected' : ''; ?>>Bachelor of Arts in Communication</option>
                                    <option value="BADC" <?php echo ($_SESSION["course"] == 'BADC') ? 'selected' : ''; ?>>Bachelor of Arts in Development Communication</option>
                                    <option value="BAJ" <?php echo ($_SESSION["course"] == 'BAJ') ? 'selected' : ''; ?>>Bachelor of Arts in Journalism</option>
                                    <option value="BAMC" <?php echo ($_SESSION["course"] == 'BAMC') ? 'selected' : ''; ?>>Bachelor of Arts in Mass Communication</option>
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
