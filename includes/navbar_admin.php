<?php 
include '../../api/api_admin.php';
// Include the points functions - fixing the path
include_once __DIR__ . '/points_functions.php';

// Initialize pending_count variable
$pending_count = 0;

// Get count of pending point requests if the function exists
if (function_exists('get_pending_point_requests')) {
  try {
    $pending_requests = get_pending_point_requests();
    $pending_count = is_array($pending_requests) ? count($pending_requests) : 0;
  } catch (Exception $e) {
    error_log("Error getting pending requests count: " . $e->getMessage());
    $pending_count = 0;
  }
}
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
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a 1 1 0 01-1.414 0l-4-4a 1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </button>
            <div class="dropdown-menu">
              <a href="ViewRecords.php" class="dropdown-item <?php echo basename($_SERVER['PHP_SELF']) == 'ViewRecords.php' ? 'bg-primary-50 text-primary-700 font-medium' : ''; ?>">
                <i class="fas fa-list-ul"></i> View Sit-in Records
              </a>
              <a href="Report.php" class="dropdown-item <?php echo basename($_SERVER['PHP_SELF']) == 'Report.php' ? 'bg-primary-50 text-primary-700 font-medium' : ''; ?>">
                <i class="fas fa-file-lines"></i> Sit-in Reports
              </a>
              <a href="Feedback_Report.php" class="dropdown-item <?php echo basename($_SERVER['PHP_SELF']) == 'Feedback_Report.php' ? 'bg-primary-50 text-primary-700 font-medium' : ''; ?>">
                <i class="fas fa-comments"></i> Feedback Reports
              </a>
              <a href="Reservation.php" class="dropdown-item <?php echo basename($_SERVER['PHP_SELF']) == 'Reservation.php' ? 'bg-primary-50 text-primary-700 font-medium' : ''; ?>">
                <i class="fas fa-calendar-check"></i> Reservation
              </a>
            </div>
          </div>
          
          <!-- Rewards Dropdown -->
          <div class="relative dropdown-wrapper">
            <button type="button" class="px-3 py-2 text-sm font-medium text-gray-700 nav-link-hover rounded-md inline-flex items-center transition duration-200 <?php echo in_array(basename($_SERVER['PHP_SELF']), ['reward_points.php', 'pending_points.php', 'leaderboard_admin.php']) ? 'nav-link-active' : ''; ?>">
              <i class="fas fa-award mr-1"></i>
              <span>Rewards</span>
              <svg class="ml-1 h-4 w-4 transform transition-transform duration-200 group-hover:rotate-180" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a 1 1 0 01-1.414 0l-4-4a 1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
              
              <!-- Pending approval count badge -->
              <?php if($pending_count > 0): ?>
              <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"><?php echo $pending_count; ?></span>
              <?php endif; ?>
            </button>
            <div class="dropdown-menu">
              <a href="pending_points.php" class="dropdown-item <?php echo basename($_SERVER['PHP_SELF']) == 'pending_points.php' ? 'bg-primary-50 text-primary-700 font-medium' : ''; ?>">
                <i class="fas fa-clock"></i> Pending Approvals
                <?php if($pending_count > 0): ?>
                <span class="ml-auto bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"><?php echo $pending_count; ?></span>
                <?php endif; ?>
              </a>
              <a href="reward_points.php" class="dropdown-item <?php echo basename($_SERVER['PHP_SELF']) == 'reward_points.php' ? 'bg-primary-50 text-primary-700 font-medium' : ''; ?>">
                <i class="fas fa-gift"></i> Give Points
              </a>
              <a href="leaderboard_admin.php" class="dropdown-item <?php echo basename($_SERVER['PHP_SELF']) == 'leaderboard_admin.php' ? 'bg-primary-50 text-primary-700 font-medium' : ''; ?>">
                <i class="fas fa-trophy"></i> Leaderboard
              </a>
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
          <p class="px-3 text-xs uppercase tracking-wider font-semibold text-gray-500">Rewards</p>
        </div>

        <a href="pending_points.php" class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) == 'pending_points.php' ? 'bg-primary-50 text-primary-600 font-semibold' : ''; ?>">
          <i class="fas fa-clock mr-2 w-5 text-center"></i> Pending Approvals
          <?php if($pending_count > 0): ?>
          <span class="ml-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 inline-flex items-center justify-center"><?php echo $pending_count; ?></span>
          <?php endif; ?>
        </a>

        <a href="reward_points.php" class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) == 'reward_points.php' ? 'bg-primary-50 text-primary-600 font-semibold' : ''; ?>">
          <i class="fas fa-gift mr-2 w-5 text-center"></i> Give Points
        </a>

        <a href="leaderboard_admin.php" class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) == 'leaderboard_admin.php' ? 'bg-primary-50 text-primary-600 font-semibold' : ''; ?>">
          <i class="fas fa-trophy mr-2 w-5 text-center"></i> Leaderboard
        </a>
        
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

</html>