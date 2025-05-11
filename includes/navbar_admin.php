<?php 
include '../../api/api_admin.php';
include '../../backend/backend_student.php';

// Calculate unread notifications count
$notifications = retrieve_notification($_SESSION['admin_id_number']);
$unread_count = 0;
foreach ($notifications as $notification) {
    if (!$notification['is_read']) {
        $unread_count++;
    }
}

// The points functions reference has been removed

// No need for pending_count variable anymore
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.6/css/dataTables.bootstrap5.css">
  <link rel="icon" href="../../assets/images/ccsLogo.ico" type="image/x-icon">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

  <title>CCS | Admin Dashboard</title>

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
      scroll-behavior: smooth;
    }
    
    /* Navigation link styling with improved interactions */
    .nav-link-hover {
      position: relative;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      border-radius: 6px;
    }
    
    .nav-link-hover:hover {
      background-color: rgba(14, 165, 233, 0.08);
      color: #0284c7;
      transform: translateY(-1px);
    }
    
    .nav-link-hover::after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      bottom: -1px;
      left: 50%;
      background-color: #0284c7;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      transform: translateX(-50%);
      border-radius: 2px;
    }
    
    .nav-link-hover:hover::after {
      width: 80%;
    }
    
    .nav-link-active {
      color: #0284c7;
      font-weight: 500;
    }
    
    .nav-link-active::after {
      width: 80%;
      background-color: #0284c7;
    }
    
    /* Enhanced dropdown styling */
    .dropdown-wrapper {
      position: relative;
    }
    
    .dropdown-menu {
      position: absolute;
      top: 100%;
      left: 0;
      min-width: 12rem;
      border: none;
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
      border-radius: 8px;
      padding: 0.5rem 0;
      background-color: white;
      opacity: 0;
      visibility: hidden;
      transform: translateY(-10px);
      transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s ease;
      z-index: 50;
      /* Add these properties to ensure the dropdown is positioned correctly */
      display: block;
      pointer-events: none;
    }
    
    .dropdown-wrapper:hover .dropdown-menu,
    .dropdown-wrapper:focus-within .dropdown-menu {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
      pointer-events: auto; /* Enable pointer events on hover */
    }
    
    .dropdown-item {
      display: flex;
      align-items: center;
      padding: 0.5rem 1rem;
      font-size: 0.875rem;
      color: #374151;
      transition: all 0.2s ease;
    }
    
    .dropdown-item:hover {
      background-color: rgba(14, 165, 233, 0.05);
      color: #0284c7;
      transform: translateX(3px);
    }
    
    .dropdown-item i {
      margin-right: 0.5rem;
      width: 1rem;
      text-align: center;
      color: #0ea5e9;
    }
    
    /* Enhanced button styling */
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
    
    /* Remove text decorations */
    a, a:hover, a:focus, a:active {
      text-decoration: none !important;
    }
    
    /* Mobile menu animation */
    #mobile-menu {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.5s cubic-bezier(0, 1, 0, 1);
    }
    
    #mobile-menu.show {
      max-height: 2000px;
      transition: max-height 0.5s cubic-bezier(0.17, 0.67, 0.83, 0.67);
    }
    
    /* Logo animation */
    .logo-container {
      transition: transform 0.3s ease, filter 0.3s ease;
    }
    
    .logo-container:hover {
      transform: scale(1.05);
      filter: drop-shadow(0 4px 3px rgba(0, 0, 0, 0.07));
    }
    
    /* Improved fade-in animation for page load */
    .fade-in {
      animation: fadeIn 0.6s cubic-bezier(0.39, 0.575, 0.565, 1);
    }
    
    @keyframes fadeIn {
      from { 
        opacity: 0; 
        transform: translateY(10px); 
        filter: blur(5px);
      }
      to { 
        opacity: 1; 
        transform: translateY(0); 
        filter: blur(0);
      }
    }
    
    /* Navbar shadow on scroll */
    .navbar-shadow {
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
      transition: box-shadow 0.3s ease;
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
      height: 8px;
    }
    
    ::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 4px;
      transition: background 0.3s ease;
    }
    
    ::-webkit-scrollbar-thumb:hover {
      background: #94a3b8;
    }
    
    /* Focus styles for accessibility */
    a:focus, button:focus {
      outline: 2px solid rgba(14, 165, 233, 0.5);
      outline-offset: 2px;
    }
    
    /* Prevent FOUC (Flash of Unstyled Content) */
    html {
      visibility: visible;
      opacity: 1;
    }
  </style>

</head>

<body class="bg-gray-50 font-sans fade-in">
  <nav class="bg-white sticky top-0 z-50 navbar-shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <!-- Logo section -->
        <div class="flex-shrink-0 flex items-center logo-container">
          <a href="Admin.php" class="flex items-center space-x-2" aria-label="Admin dashboard home">
            <img src="../../assets/images/uc.png" class="h-9 w-auto" alt="University of Cebu logo">
            <span class="text-lg font-semibold text-gray-800">|</span>
            <img src="../../assets/images/ccs.png" class="h-10 w-auto" alt="College of Computer Studies logo">
            <span class="hidden md:block text-primary-700 font-semibold text-lg tracking-tight">Admin Portal</span>
          </a>
        </div>
        
        <!-- Mobile menu button -->
        <div class="flex items-center md:hidden">
          <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-primary-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500 transition duration-150" 
            id="mobile-menu-button" 
            aria-expanded="false"
            aria-label="Open main menu">
            <span class="sr-only">Open main menu</span>
            <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
        </div>

        <!-- Desktop Navigation Links -->
        <div class="hidden md:flex md:items-center md:space-x-4">
          <a href="Admin.php" class="px-3 py-2 text-sm font-medium text-gray-700 nav-link-hover transition duration-200 <?php echo basename($_SERVER['PHP_SELF']) == 'Admin.php' ? 'nav-link-active' : ''; ?>">
            <i class="fas fa-home mr-1"></i>
            <span>Home</span>
          </a>
          
          <button type="button" class="px-3 py-2 text-sm font-medium text-gray-700 nav-link-hover transition duration-200 cursor-pointer flex items-center" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="animateSearch()">
            <i class="fas fa-search mr-1"></i>
            <span>Search</span>
          </button>
          
          <a href="Students.php" class="px-3 py-2 text-sm font-medium text-gray-700 nav-link-hover transition duration-200 flex items-center <?php echo basename($_SERVER['PHP_SELF']) == 'Students.php' ? 'nav-link-active' : ''; ?>">
            <i class="fas fa-user-graduate mr-1"></i>
            <span>Students</span>
          </a>
          
          <a href="Sit_in.php" class="px-3 py-2 text-sm font-medium text-gray-700 nav-link-hover transition duration-200 flex items-center <?php echo basename($_SERVER['PHP_SELF']) == 'Sit_in.php' ? 'nav-link-active' : ''; ?>">
            <i class="fas fa-laptop-code mr-1"></i>
            <span>Sit-in</span>
          </a>
          
          <!-- Reports Dropdown -->
          <div class="relative dropdown-wrapper">
            <button type="button" class="px-3 py-2 text-sm font-medium text-gray-700 nav-link-hover rounded-md inline-flex items-center transition duration-200 <?php echo in_array(basename($_SERVER['PHP_SELF']), ['ViewRecords.php', 'Report.php', 'Feedback_Report.php', 'Reservation.php']) ? 'nav-link-active' : ''; ?>">
              <i class="fas fa-file-lines mr-1"></i>
              <span>Reports</span>
              <svg class="ml-1 h-4 w-4 transform transition-transform duration-200 group-hover:rotate-180" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </button>
            <div class="absolute left-0 mt-1 w-48 bg-white rounded-md shadow-lg dropdown-menu z-50">
              <div class="py-1">
                <a href="ViewRecords.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 <?php echo basename($_SERVER['PHP_SELF']) == 'ViewRecords.php' ? 'bg-gray-100 text-gray-900' : ''; ?>">Student Records</a>
                <a href="Report.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 <?php echo basename($_SERVER['PHP_SELF']) == 'Report.php' ? 'bg-gray-100 text-gray-900' : ''; ?>">Usage Report</a>
                <a href="Feedback_Report.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 <?php echo basename($_SERVER['PHP_SELF']) == 'Feedback_Report.php' ? 'bg-gray-100 text-gray-900' : ''; ?>">Feedback Report</a>
                <a href="reservation.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 <?php echo basename($_SERVER['PHP_SELF']) == 'reservation.php' ? 'bg-gray-100 text-gray-900' : ''; ?>">Reservations</a>
              </div>
            </div>
          </div>
          
          <!-- More Dropdown -->
          <div class="relative dropdown-wrapper">
            <button type="button" class="px-3 py-2 text-sm font-medium text-gray-700 nav-link-hover rounded-md inline-flex items-center transition duration-200">
              <i class="fas fa-bars mr-1"></i>
              <span>More</span>
              <svg class="ml-1 h-4 w-4 transform transition-transform duration-200 group-hover:rotate-180" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </button>
            <div class="absolute left-0 mt-1 w-48 bg-white rounded-md shadow-lg dropdown-menu z-50">
              <div class="py-1">
                <a href="schedules.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 <?php echo basename($_SERVER['PHP_SELF']) == 'schedules.php' ? 'bg-gray-100 text-gray-900' : ''; ?>">
                  <i class="fas fa-calendar-alt mr-2 text-primary-500"></i>Lab Schedules
                </a>
                <a href="resources.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 <?php echo basename($_SERVER['PHP_SELF']) == 'resources.php' ? 'bg-gray-100 text-gray-900' : ''; ?>">
                  <i class="fas fa-file-upload mr-2 text-primary-500"></i>Resources
                </a>
                <div class="border-t border-gray-100 my-1"></div>
                <a href="notification.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 <?php echo basename($_SERVER['PHP_SELF']) == 'notification.php' ? 'bg-gray-100 text-gray-900' : ''; ?>">
                  <i class="fas fa-bell mr-2 text-primary-500"></i>Notifications
                  <?php if ($unread_count > 0): ?>
                    <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-500 rounded-full">
                      <?php echo $unread_count; ?>
                    </span>
                  <?php endif; ?>
                </a>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Logout Button -->
        <div class="hidden md:flex items-center ml-4">
          <a href="../../auth/logout.php" 
             class="px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-md shadow-sm transition-all duration-200 flex items-center space-x-1 hover:-translate-y-0.5 hover:shadow-md" 
             onclick="handleLogout(event)" 
             aria-label="Logout from admin dashboard">
            <i class="fas fa-sign-out-alt mr-2"></i>
            <span>Log out</span>
          </a>
        </div>
      </div>  
    </div>

    <!-- Mobile menu -->
    <div class="md:hidden bg-white border-t border-gray-100" id="mobile-menu">
      <div class="px-4 py-3 space-y-3">
        <a href="Admin.php" class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) == 'Admin.php' ? 'bg-primary-50 text-primary-600 font-semibold' : ''; ?>">
          <i class="fas fa-home mr-2 w-5 text-center"></i> Home
        </a>
        
        <button type="button" class="w-full text-left block px-3 py-3 rounded-md text-base font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-all duration-200" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="animateSearch()">
          <i class="fas fa-search mr-2 w-5 text-center"></i> Search
        </button>
        
        <a href="Students.php" class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) == 'Students.php' ? 'bg-primary-50 text-primary-600 font-semibold' : ''; ?>">
          <i class="fas fa-user-graduate mr-2 w-5 text-center"></i> Students
        </a>
        
        <a href="Sit_in.php" class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) == 'Sit_in.php' ? 'bg-primary-50 text-primary-600 font-semibold' : ''; ?>">
          <i class="fas fa-laptop-code mr-2 w-5 text-center"></i> Sit-in
        </a>
        
        <div class="pt-4 pb-2">
          <p class="px-3 text-xs uppercase tracking-wider font-semibold text-gray-500">Reports</p>
        </div>
        
        <a href="ViewRecords.php" class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) == 'ViewRecords.php' ? 'bg-primary-50 text-primary-600 font-semibold' : ''; ?>">
          <i class="fas fa-list-ul mr-2 w-5 text-center"></i> View Sit-in Records
        </a>
        
        <a href="Report.php" class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) == 'Report.php' ? 'bg-primary-50 text-primary-600 font-semibold' : ''; ?>">
          <i class="fas fa-file-lines mr-2 w-5 text-center"></i> Sit-in Reports
        </a>
        
        <a href="Feedback_Report.php" class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) == 'Feedback_Report.php' ? 'bg-primary-50 text-primary-600 font-semibold' : ''; ?>">
          <i class="fas fa-comments mr-2 w-5 text-center"></i> Feedback Reports
        </a>
        
        <a href="Reservation.php" class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) == 'Reservation.php' ? 'bg-primary-50 text-primary-600 font-semibold' : ''; ?>">
          <i class="fas fa-calendar-check mr-2 w-5 text-center"></i> Reservation
        </a>
        
        <div class="pt-4 pb-2">
          <p class="px-3 text-xs uppercase tracking-wider font-semibold text-gray-500">Upload</p>
        </div>
        
        <a href="schedules.php" class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) == 'schedules.php' ? 'bg-primary-50 text-primary-600 font-semibold' : ''; ?>">
          <i class="fas fa-calendar-alt mr-2 w-5 text-center"></i> Lab Schedules
        </a>
        
        <a href="resources.php" class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) == 'resources.php' ? 'bg-primary-50 text-primary-600 font-semibold' : ''; ?>">
          <i class="fas fa-file-upload mr-2 w-5 text-center"></i> Resources
        </a>
        
        <div class="pt-6 pb-4 border-t border-gray-200 mt-2">
          <a href="notification.php" class="flex items-center justify-center w-full text-center px-4 py-3 text-base font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-all duration-200 relative">
            <i class="fas fa-bell mr-2 w-5 text-center"></i> Notifications
            <?php if ($unread_count > 0): ?>
                <span class="absolute top-2 right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center animate-pulse">
                    <?php echo $unread_count; ?>
                </span>
            <?php endif; ?>
          </a>
        </div>
        
        <div class="pt-6 pb-4 border-t border-gray-200 mt-2">
          <a href="../../auth/logout.php" class="flex items-center justify-center w-full text-center px-4 py-3 text-base font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-md transition-all duration-200" onclick="handleLogout(event)">
            <i class="fas fa-sign-out-alt mr-2"></i> Log out
          </a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Your HTML content goes here -->

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.datatables.net/2.0.6/js/dataTables.js"></script>
  <script src="https://cdn.datatables.net/2.0.6/js/dataTables.bootstrap5.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.dataTables.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.print.min.js"></script>
  
  <script>
    // Mobile menu toggle with improved animation
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
      const menu = document.getElementById('mobile-menu');
      menu.classList.toggle('show');
      
      // Update aria-expanded attribute for accessibility
      const expanded = menu.classList.contains('show');
      this.setAttribute('aria-expanded', expanded);
    });
    
    // Navbar shadow on scroll
    window.addEventListener('scroll', function() {
      const navbar = document.querySelector('nav');
      if (window.scrollY > 10) {
        navbar.classList.add('navbar-shadow');
      } else {
        navbar.classList.remove('navbar-shadow');
      }
    });
    
    // Sweet Alert for logout confirmation with improved animation
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
        },
        heightAuto: false
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = href;
        }
      });
    }
    
    // Enhanced animation for search modal
    function animateSearch() {
      $('#exampleModal').on('show.bs.modal', function() {
        $(this).find('.modal-dialog').removeClass('animate__animated animate__fadeInDown');
        $(this).find('.modal-dialog').addClass('animate__animated animate__fadeInDown');
        
        // Auto-focus the search input field when modal opens
        $(this).on('shown.bs.modal', function() {
          $(this).find('input[type="search"]').focus();
        });
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
        },
        backdrop: `
          rgba(0,0,123,0.1)
        `
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
    
    // Enhanced DataTables and animations
    $(document).ready(function() {
      // Fix jQuery conflicts if they exist
      let jq = $.noConflict(true);
      $ = jq;
      
      // DataTable styling
      if ($.fn.DataTable) {
        $.extend(true, $.fn.dataTable.defaults, {
          drawCallback: function() {
            $('.paginate_button:not(.disabled)').addClass('transition-all duration-200 hover:bg-primary-50');
          },
          language: {
            search: "<i class='fas fa-search'></i>",
            searchPlaceholder: "Search records...",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            paginate: {
              first: "<i class='fas fa-angle-double-left'></i>",
              last: "<i class='fas fa-angle-double-right'></i>",
              next: "<i class='fas fa-angle-right'></i>",
              previous: "<i class='fas fa-angle-left'></i>"
            }
          }
        });
      }
      
      // Remove underlines from links for consistent styling
      const links = document.querySelectorAll('a');
      links.forEach(link => {
        link.style.textDecoration = 'none';
      });
      
      // Enhanced dropdown for touch devices
      if ('ontouchstart' in window) {
        const dropdownWrappers = document.querySelectorAll('.dropdown-wrapper');
        
        dropdownWrappers.forEach(dropdown => {
          const button = dropdown.querySelector('button');
          const menu = dropdown.querySelector('.dropdown-menu');
          
          button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Close other dropdowns
            dropdownWrappers.forEach(other => {
              if (other !== dropdown && other.querySelector('.dropdown-menu').classList.contains('show')) {
                other.querySelector('.dropdown-menu').classList.remove('show');
              }
            });
            
            // Add show class which overrides the hover styles
            menu.classList.toggle('show');
          });
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
          dropdownWrappers.forEach(dropdown => {
            if (!dropdown.contains(e.target)) {
              const menu = dropdown.querySelector('.dropdown-menu');
              if (menu && menu.classList.contains('show')) {
                menu.classList.remove('show');
              }
            }
          });
        });
        
        // Add this style dynamically for touch devices
        const style = document.createElement('style');
        style.textContent = `
          .dropdown-menu.show {
            opacity: 1 !important;
            visibility: visible !important;
            transform: translateY(0) !important;
            pointer-events: auto !important;
          }
        `;
        document.head.appendChild(style);
      }
    });
  </script>
</body>

<!-- Search Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-lg shadow-lg animate__animated animate__fadeIn">
      <div class="modal-header border-b border-gray-200 px-4 py-3">
        <h5 class="modal-title font-semibold text-gray-800" id="exampleModalLabel">
          <i class="fas fa-search mr-2 text-primary-500"></i>
          Search Students
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <form action="" method="GET" class="space-y-4">
          <div class="form-group">
            <label for="searchBar" class="text-sm font-medium text-gray-700 mb-1 block">
              Enter Student ID Number:
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-id-card text-gray-400"></i>
              </div>
              <input type="text" name="searchBar" id="searchBar" class="form-control pl-10 py-2 border border-gray-300 rounded-md w-full focus:ring-primary-500 focus:border-primary-500 transition-all" placeholder="ex. XXXX-XXXX" required>
            </div>
          </div>
          <input type="hidden" name="search" value="true">
          <div class="flex justify-end space-x-2 mt-4">
            <button type="button" class="btn btn-secondary px-4 py-2 rounded-md text-gray-700 bg-gray-200 hover:bg-gray-300 transition-all" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary px-4 py-2 rounded-md text-white bg-primary-600 hover:bg-primary-700 transition-all">
              <i class="fas fa-search mr-1"></i> Search
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Student Results Modal -->
<?php if(isset($displayModal) && $displayModal): ?>
<div class="modal fade" id="studentResultsModal" tabindex="-1" aria-labelledby="studentResultsModalLabel" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-lg shadow-lg border-0 animate__animated animate__fadeIn">
      <div class="modal-header bg-primary-50 py-3">
        <h5 class="modal-title font-semibold text-primary-800" id="studentResultsModalLabel">
          <i class="fas fa-user-graduate mr-2"></i>Student Information
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="mb-4 text-center">
          <div class="inline-block h-20 w-20 rounded-full bg-primary-100 p-2 mb-2 overflow-hidden">
            <img src="../../assets/images/<?php echo htmlspecialchars($student->profile_image); ?>" alt="<?php echo htmlspecialchars($student->name); ?>"
              class="h-full w-full object-cover rounded-full" onerror="this.src='../../assets/images/default-profile.jpg'">
          </div>
          <h4 class="text-xl font-semibold text-gray-800"><?php echo htmlspecialchars($student->name); ?></h4>
          <p class="text-gray-500"><?php echo htmlspecialchars($student->id); ?></p>
        </div>
        
        <div class="grid grid-cols-1 gap-3 mt-4">
          <div class="bg-gray-50 p-3 rounded-lg">
            <p class="text-sm text-gray-500 mb-1">Available Sessions</p>
            <p class="text-lg font-semibold"><?php echo $student->records; ?> sessions</p>
          </div>
          
          <!-- Actions -->
          <div class="mt-3 flex justify-between gap-2">
            <button type="button" class="btn btn-primary flex-1 py-2 px-4 rounded-md bg-blue-100 text-blue-700 hover:bg-blue-200 transition-all text-center"
                    data-bs-toggle="modal" data-bs-target="#studentProfileModal" data-bs-dismiss="modal">
              <i class="fas fa-user-edit mr-1"></i> View Profile
            </button>
            <?php if($student->records > 0): ?>
            <form action="" method="POST" class="flex-1">
              <input type="hidden" name="studentID" value="<?php echo $student->id; ?>">
              <button type="button" class="w-full py-2 px-4 rounded-md bg-green-100 text-green-700 hover:bg-green-200 transition-all" 
                      data-bs-toggle="modal" data-bs-target="#sitInModal" data-student-id="<?php echo $student->id; ?>" 
                      data-student-name="<?php echo $student->name; ?>" data-sessions="<?php echo $student->records; ?>">
                <i class="fas fa-sign-in-alt mr-1"></i> Sit-in
              </button>
            </form>
            <?php else: ?>
            <button disabled class="flex-1 py-2 px-4 rounded-md bg-gray-200 text-gray-400 cursor-not-allowed">
              <i class="fas fa-sign-in-alt mr-1"></i> No Sessions
            </button>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Student Profile Modal -->
<div class="modal fade" id="studentProfileModal" tabindex="-1" aria-labelledby="studentProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-lg shadow-lg border-0 animate__animated animate__fadeIn">
      <div class="modal-header bg-primary-50 py-3">
        <h5 class="modal-title font-semibold text-primary-800" id="studentProfileModalLabel">
          <i class="fas fa-id-card mr-2"></i>Student Profile
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <!-- Profile Image Section -->
          <div class="md:col-span-1 flex flex-col items-center justify-start p-4 bg-gray-50 rounded-lg">
            <div class="relative w-40 h-40 rounded-full overflow-hidden mb-4 border-4 border-white shadow-md">
              <img src="../../assets/images/<?php echo htmlspecialchars($student->profile_image); ?>" 
                alt="<?php echo htmlspecialchars($student->name); ?>" 
                class="w-full h-full object-cover"
                onerror="this.src='../../assets/images/default-profile.jpg'">
            </div>
            <h4 class="text-xl font-bold text-center"><?php echo htmlspecialchars($student->name); ?></h4>
            <p class="text-gray-500 text-center mb-2"><?php echo htmlspecialchars($student->id); ?></p>
            <div class="mt-2 bg-primary-100 text-primary-700 px-3 py-1 rounded-full text-sm font-medium">
              <?php 
                $yearLevel = $student->yearLevel;
                $yearText = "";
                
                switch($yearLevel) {
                  case "1st Year":
                  case "1":
                    $yearText = "Freshmen";
                    break;
                  case "2nd Year":
                  case "2":
                    $yearText = "Sophomore";
                    break;
                  case "3rd Year":
                  case "3":
                    $yearText = "Junior";
                    break;
                  case "4th Year":
                  case "4":
                    $yearText = "Senior";
                    break;
                  default:
                    $yearText = $yearLevel;
                }
                
                echo $yearText;
              ?>
            </div>
            <div class="mt-2 bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-medium">
              <?php echo htmlspecialchars($student->course); ?>
            </div>
          </div>
          
          <!-- Student Details Section -->
          <div class="md:col-span-2">
            <div class="bg-white rounded-lg shadow-sm">
              <div class="p-4 border-b border-gray-100">
                <h5 class="text-lg font-semibold text-gray-800 mb-1">Contact Information</h5>
              </div>
              <div class="p-4 space-y-3">
                <!-- Email -->
                <div class="flex items-start">
                  <div class="flex-shrink-0 h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center">
                    <i class="fas fa-envelope text-primary-600"></i>
                  </div>
                  <div class="ml-3">
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="text-gray-800"><?php echo htmlspecialchars($student->email); ?></p>
                  </div>
                </div>
                
                <!-- Address -->
                <div class="flex items-start">
                  <div class="flex-shrink-0 h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center">
                    <i class="fas fa-map-marker-alt text-primary-600"></i>
                  </div>
                  <div class="ml-3">
                    <p class="text-sm text-gray-500">Address</p>
                    <p class="text-gray-800"><?php echo htmlspecialchars($student->address); ?></p>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Sit-in Session Info -->
            <div class="mt-4 bg-white rounded-lg shadow-sm">
              <div class="p-4 border-b border-gray-100">
                <h5 class="text-lg font-semibold text-gray-800 mb-1">Lab Sessions</h5>
              </div>
              <div class="p-4">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-sm text-gray-500">Available Sessions</p>
                    <p class="text-2xl font-bold <?php echo $student->records > 0 ? 'text-green-600' : 'text-red-500'; ?>">
                      <?php echo $student->records; ?>
                    </p>
                  </div>
                  <div class="text-right">
                    <?php if($student->records > 0): ?>
                      <button type="button" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md transition-colors"
                              data-bs-toggle="modal" data-bs-target="#sitInModal" data-bs-dismiss="modal" 
                              data-student-id="<?php echo $student->id; ?>" 
                              data-student-name="<?php echo $student->name; ?>" 
                              data-sessions="<?php echo $student->records; ?>">
                        <i class="fas fa-laptop-code mr-2"></i>
                        Start Sit-in
                      </button>
                    <?php else: ?>
                      <button disabled class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-500 font-medium rounded-md cursor-not-allowed">
                        <i class="fas fa-times-circle mr-2"></i>
                        No Available Sessions
                      </button>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Actions -->
            <div class="mt-4 flex flex-wrap justify-end gap-2">
              <button type="button" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-md transition-all" 
                      onclick="window.location.href='Students.php'">
                <i class="fas fa-list mr-1"></i> View All Students
              </button>
              <button type="button" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md transition-all" 
                      data-bs-dismiss="modal">
                <i class="fas fa-times mr-1"></i> Close
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- Sit-in Modal -->
<div class="modal fade" id="sitInModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-lg shadow-lg border-0">
      <div class="modal-header bg-green-50 py-3">
        <h5 class="modal-title font-semibold text-green-800">
          <i class="fas fa-laptop-code mr-2"></i>New Sit-in Session
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="" method="POST">
        <div class="modal-body p-4">
          <div class="mb-3">
            <div class="font-medium text-gray-800 mb-2">Student Information</div>
            <div class="bg-gray-50 p-3 rounded-lg mb-3">
              <p id="sitInStudentName" class="font-semibold"></p>
              <p id="sitInStudentId" class="text-sm text-gray-500"></p>
              <p class="text-sm mt-1">Available Sessions: <span id="sitInSessions" class="font-semibold text-green-600"></span></p>
            </div>
          </div>
          
          <input type="hidden" name="studentID" id="sitInStudentIdInput">
          
          <div class="mb-3">
            <label for="purpose" class="form-label font-medium text-gray-700">Purpose</label>
            <select name="purpose" id="purpose" class="form-select rounded-md border-gray-300 focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-20" required>
              <option value="">Select purpose</option>
              <optgroup label="Programming Languages">
                <option value="C Programming">C Programming</option>
                <option value="Java Programming">Java Programming</option>
                <option value="C# Programming">C# Programming</option>
                <option value="PHP Programming">PHP Programming</option>
                <option value="Python Programming">Python Programming</option>
                <option value="ASP.Net Programming">ASP.Net Programming</option>
              </optgroup>
              <optgroup label="Computer Science">
                <option value="Database">Database</option>
                <option value="Digital Logic & Design">Digital Logic & Design</option>
                <option value="Embedded Systems & IoT">Embedded Systems & IoT</option>
                <option value="Systems Integration & Architecture">Systems Integration & Architecture</option>
                <option value="Computer Application">Computer Application</option>
                <option value="Web Design & Development">Web Design & Development</option>
              </optgroup>
              <optgroup label="General">
                <option value="Assignment">Assignment</option>
                <option value="Research">Research</option>
                <option value="Project">Project</option>
                <option value="Online Class">Online Class</option>
                <option value="Others">Others</option>
              </optgroup>
            </select>
          </div>
          
          <div class="mb-3">
            <label for="lab" class="form-label font-medium text-gray-700">Laboratory</label>
            <select name="lab" id="lab" class="form-select rounded-md border-gray-300 focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-20" required>
              <option value="">Select laboratory</option>
              <optgroup label="5th Floor Labs">
                <option value="Lab 517">Lab 517</option>
                <option value="Lab 524">Lab 524</option>
                <option value="Lab 526">Lab 526</option>
                <option value="Lab 528">Lab 528</option>
                <option value="Lab 530">Lab 530</option>
              </optgroup>
              <optgroup label="Other Labs">  
                <option value="Lab 542">Lab 542</option>
                <option value="Lab 544">Lab 544</option>
              </optgroup>
            </select>
          </div>
        </div>
        <div class="modal-footer flex justify-end border-0 bg-gray-50">
          <button type="button" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md" data-bs-dismiss="modal">
            Cancel
          </button>
          <button type="submit" name="sitIn" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md">
            <i class="fas fa-check-circle mr-1"></i> Start Session
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// Automatically show student results modal when search returns results
<?php if(isset($displayModal) && $displayModal): ?>
  document.addEventListener('DOMContentLoaded', function() {
    var studentResultsModal = new bootstrap.Modal(document.getElementById('studentResultsModal'));
    studentResultsModal.show();
  });
<?php endif; ?>

// Set up sit-in modal data
document.addEventListener('DOMContentLoaded', function() {
  const sitInModal = document.getElementById('sitInModal');
  if (sitInModal) {
    sitInModal.addEventListener('show.bs.modal', function(event) {
      const button = event.relatedTarget;
      const studentId = button.getAttribute('data-student-id');
      const studentName = button.getAttribute('data-student-name');
      const sessions = button.getAttribute('data-sessions');
      
      document.getElementById('sitInStudentName').textContent = studentName;
      document.getElementById('sitInStudentId').textContent = 'ID: ' + studentId;
      document.getElementById('sitInSessions').textContent = sessions;
      document.getElementById('sitInStudentIdInput').value = studentId;
    });
  }
  
  // Handle transitions between modals
  const studentProfileModal = document.getElementById('studentProfileModal');
  if (studentProfileModal) {
    studentProfileModal.addEventListener('show.bs.modal', function() {
      // This ensures proper stacking when going from one modal to another
      document.body.classList.add('modal-open');
    });
    
    // Fix for backdrop issues when chaining modals
    studentProfileModal.addEventListener('hidden.bs.modal', function(event) {
      // If we're going to another modal directly, prevent backdrop from disappearing
      setTimeout(function() {
        if (document.querySelector('.modal.show')) {
          document.body.classList.add('modal-open');
        }
      }, 10);
    });
  }
});

// Improve image error handling
document.addEventListener('DOMContentLoaded', function() {
  const profileImages = document.querySelectorAll('img[onerror]');
  profileImages.forEach(img => {
    img.addEventListener('error', function() {
      this.src = '../../assets/images/default-profile.jpg';
    });
  });
  
  // Enhance select dropdowns with better UX
  const purposeSelect = document.getElementById('purpose');
  const labSelect = document.getElementById('lab');
  
  if (purposeSelect && labSelect) {
    // Add keypress event for searching in dropdown
    [purposeSelect, labSelect].forEach(select => {
      select.addEventListener('keypress', function(e) {
        // Don't capture Enter or Tab to allow for normal form submission
        if (e.key === 'Enter' || e.key === 'Tab') return;
        
        const searchChar = e.key.toLowerCase();
        const options = Array.from(this.options).slice(1); // Skip the first "Select..." option
        
        // Find the first option that starts with the pressed key
        const matchingOption = options.find(option => {
          if (option.disabled) return false; // Skip optgroup labels
          return option.text.toLowerCase().startsWith(searchChar);
        });
        
        if (matchingOption) {
          this.value = matchingOption.value;
          // Create a custom change event to trigger any change listeners
          this.dispatchEvent(new Event('change'));
        }
      });
    });
    
    // Add animation when selecting purpose
    purposeSelect.addEventListener('change', function() {
      if (this.value) {
        this.classList.add('bg-green-50');
        setTimeout(() => this.classList.remove('bg-green-50'), 300);
        
        // Auto-focus the lab select after selecting purpose
        if (!labSelect.value) {
          labSelect.focus();
        }
      }
    });
    
    // Add animation when selecting lab
    labSelect.addEventListener('change', function() {
      if (this.value) {
        this.classList.add('bg-green-50');
        setTimeout(() => this.classList.remove('bg-green-50'), 300);
      }
    });
  }
});

function toggleNotificationDropdown() {
    const dropdown = document.getElementById('notificationDropdown');
    dropdown.classList.toggle('hidden');
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function closeDropdown(e) {
        if (!dropdown.contains(e.target) && !e.target.closest('#notificationDropdownButton')) {
            dropdown.classList.add('hidden');
            document.removeEventListener('click', closeDropdown);
        }
    });
}

// Close dropdown when clicking escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const dropdown = document.getElementById('notificationDropdown');
        if (!dropdown.classList.contains('hidden')) {
            dropdown.classList.add('hidden');
        }
    }
});
</script>

</html>