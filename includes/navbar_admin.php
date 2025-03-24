<?php 
include '../../api/api_admin.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.6/css/dataTables.bootstrap5.css">
  <link rel="icon" href="ccsLogo.ico" type="image/x-icon">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

  <title>CCS | Home</title>

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
          },
          boxShadow: {
            'smooth': '0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)',
            'hover': '0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.03)',
            'card': '0 1px 3px 0 rgba(0, 0, 0, 0.04), 0 1px 2px 0 rgba(0, 0, 0, 0.02)',
          },
          transitionProperty: {
            'height': 'height',
            'spacing': 'margin, padding',
          }
        }
      }
    }
  </script>

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f9fafb;
    }
    
    .nav-link-hover {
      position: relative;
      transition: all 0.3s ease;
    }
    
    .nav-link-hover:hover {
      background-color: rgba(14, 165, 233, 0.05);
      color: #0284c7;
      border-radius: 6px;
      transform: translateY(-1px);
    }
    
    .nav-link-hover::after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      bottom: 0;
      left: 50%;
      background-color: #0284c7;
      transition: all 0.3s ease;
      transform: translateX(-50%);
    }
    
    .nav-link-hover:hover::after {
      width: 80%;
    }
    
    .dropdown-item {
      transition: all 0.2s ease;
    }
    
    .dropdown-item:hover {
      background-color: rgba(14, 165, 233, 0.05);
      color: #0284c7;
      transform: translateX(3px);
    }
    
    .dropdown-menu {
      border: none;
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
      border-radius: 8px;
      padding: 0.5rem 0;
      opacity: 0;
      transform: translateY(-10px);
      transition: opacity 0.3s ease, transform 0.3s ease;
    }
    
    .group:hover .dropdown-menu {
      opacity: 1;
      transform: translateY(0);
    }
    
    .btn-primary {
      background-color: #0284c7;
      border-color: #0284c7;
      box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
      transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
      background-color: #0369a1;
      border-color: #0369a1;
      transform: translateY(-1px);
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    /* Remove all text underlines */
    a, a:hover, a:focus, a:active {
      text-decoration: none !important;
    }
    
    /* Override any bootstrap underlines */
    .dropdown-item {
      text-decoration: none !important;
    }
    
    /* Mobile menu animation */
    #mobile-menu {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.5s ease-out;
    }
    
    #mobile-menu.show {
      max-height: 500px;
    }
    
    /* Logo animation */
    .logo-container {
      transition: transform 0.3s ease;
    }
    
    .logo-container:hover {
      transform: scale(1.05);
    }
    
    /* Fade-in animation for page load */
    .fade-in {
      animation: fadeIn 0.5s ease-in-out;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>

</head>

<body class="bg-gray-50 font-sans fade-in">
  <nav class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <!-- Logo section -->
        <div class="flex-shrink-0 flex items-center logo-container">
          <a href="Admin.php" class="flex items-center space-x-2">
            <img src="../../assets/images/uc.png" class="h-10 w-auto" alt="UC Logo">
            <img src="../../assets/images/ccs.png" class="h-10 w-auto" alt="CCS Logo">
            <span class="hidden md:block text-primary-700 font-semibold text-lg">Admin Dashboard</span>
          </a>
        </div>
        
        <!-- Mobile menu button -->
        <div class="flex items-center md:hidden">
          <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-primary-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500 transition duration-150" id="mobile-menu-button">
            <span class="sr-only">Open main menu</span>
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
        </div>

        <!-- Desktop Navigation Links -->
        <div class="hidden md:flex md:items-center md:space-x-4">
          <a href="Admin.php" class="px-3 py-2 text-sm font-medium text-gray-700 nav-link-hover transition duration-200">Home</a>
          <a type="button" class="px-3 py-2 text-sm font-medium text-gray-700 nav-link-hover transition duration-200 cursor-pointer" data-toggle="modal" data-target="#exampleModal" onclick="animateSearch()">Search</a>
          <a href="Students.php" class="px-3 py-2 text-sm font-medium text-gray-700 nav-link-hover transition duration-200">Students</a>
          <a href="Sit_in.php" class="px-3 py-2 text-sm font-medium text-gray-700 nav-link-hover transition duration-200">Sit-in</a>
          
          <!-- Dropdown for Reports -->
          <div class="relative group">
            <button type="button" class="px-3 py-2 text-sm font-medium text-gray-700 nav-link-hover rounded-md inline-flex items-center transition duration-200">
              <span>Reports</span>
              <svg class="ml-1 h-4 w-4 transform transition-transform duration-200 group-hover:rotate-180" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </button>
            <div class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10 hidden group-hover:block dropdown-menu">
              <a href="ViewRecords.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-all duration-200">View Sit-in Records</a>
              <a href="Report.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-all duration-200">Sit-in Reports</a>
              <a href="Feedback_Report.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-all duration-200">Feedback Reports</a>
            </div>
          </div>
          
          <a href="Reservation.php" class="px-3 py-2 text-sm font-medium text-gray-700 nav-link-hover transition duration-200">Reservation</a>
          
          <!-- Logout Button -->
          <a href="../../auth/logout.php" class="ml-4 px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-md shadow-sm transition duration-200 flex items-center" onclick="handleLogout(event)">
            <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Log out
          </a>
        </div>
      </div>
    </div>

    <!-- Mobile menu, show/hide based on menu state -->
    <div class="md:hidden bg-white shadow-sm" id="mobile-menu">
      <div class="px-2 pt-2 pb-3 space-y-1">
        <a href="Admin.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-all duration-200">Home</a>
        <a type="button" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-all duration-200" data-toggle="modal" data-target="#exampleModal" onclick="animateSearch()">Search</a>
        <a href="Students.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-all duration-200">Students</a>
        <a href="Sit_in.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-all duration-200">Sit-in</a>
        <a href="ViewRecords.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-all duration-200">View Sit-in Records</a>
        <a href="Report.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-all duration-200">Sit-in Reports</a>
        <a href="Feedback_Report.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-all duration-200">Feedback Reports</a>
        <a href="Reservation.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-all duration-200">Reservation</a>
        <div class="pt-4 pb-3 border-t border-gray-200">
          <a href="../../auth/logout.php" class="block w-full text-left px-4 py-2 text-base font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-md transition-all duration-200" onclick="handleLogout(event)">Log out</a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Your HTML content goes here -->

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
  <script src="https://cdn.datatables.net/2.0.6/js/dataTables.bootstrap5.js"></script>
  <script src="https://cdn.datatables.net/2.0.6/js/dataTables.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.dataTables.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.print.min.js"></script>
  
  <script>
    // Mobile menu toggle with animation
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
      const menu = document.getElementById('mobile-menu');
      menu.classList.toggle('show');
    });
    
    // Sweet Alert for logout confirmation
    function handleLogout(event) {
      event.preventDefault();
      const href = event.currentTarget.getAttribute('href');
      
      Swal.fire({
        title: 'Logout Confirmation',
        text: 'Are you sure you want to log out?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#0284c7',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, log out',
        cancelButtonText: 'Cancel',
        backdrop: true,
        customClass: {
          popup: 'animate__animated animate__fadeIn',
          confirmButton: 'animate__animated animate__pulse'
        }
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = href;
        }
      });
    }
    
    // Animation for search modal
    function animateSearch() {
      $('#exampleModal').on('show.bs.modal', function() {
        $(this).find('.modal-dialog').removeClass('animate__animated animate__fadeIn');
        $(this).find('.modal-dialog').addClass('animate__animated animate__fadeIn');
      });
    }
    
    // SweetAlert for success and error notifications
    function showSuccessAlert(message) {
      Swal.fire({
        title: 'Success!',
        text: message,
        icon: 'success',
        confirmButtonColor: '#0284c7',
        timer: 3000,
        timerProgressBar: true,
        customClass: {
          popup: 'animate__animated animate__fadeIn',
          confirmButton: 'animate__animated animate__pulse'
        }
      });
    }
    
    function showErrorAlert(message) {
      Swal.fire({
        title: 'Error!',
        text: message,
        icon: 'error',
        confirmButtonColor: '#0284c7',
        customClass: {
          popup: 'animate__animated animate__shakeX'
        }
      });
    }
    
    // Animation for DataTables
    $(document).ready(function() {
      if ($.fn.DataTable) {
        $.extend(true, $.fn.dataTable.defaults, {
          drawCallback: function() {
            $('.paginate_button:not(.disabled)').addClass('transition-all duration-200 hover:bg-primary-50');
          }
        });
      }
      
      // Additional script to override any potential Bootstrap underlines
      const links = document.querySelectorAll('a');
      links.forEach(link => {
        link.style.textDecoration = 'none';
      });
      
      // Page load animations
      document.body.classList.add('fade-in');
    });
  </script>
</body>

</html>