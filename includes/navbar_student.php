<?php
include '../../api/api_student.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CCS | Student Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="icon" href="../../assets/images/ccsLogo.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
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
        display: block;
        pointer-events: none;
      }
      
      .dropdown-wrapper:hover .dropdown-menu,
      .dropdown-wrapper:focus-within .dropdown-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
        pointer-events: auto;
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
      
      .notification-badge {
        position: absolute;
        top: -2px;
        right: -5px;
        background-color: #ef4444;
        color: white;
        border-radius: 9999px;
        font-size: 0.65rem;
        padding: 2px 5px;
        font-weight: 600;
      }
      
      /* Notification dropdown */
      .notification-dropdown {
        right: 0;
        left: auto;
        width: 320px;
        max-height: 400px;
        overflow-y: auto;
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
      
      /* Fade-in animation */
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
      
      /* Logo animation */
      .logo-container {
        transition: transform 0.3s ease, filter 0.3s ease;
      }
      
      .logo-container:hover {
        transform: scale(1.05);
        filter: drop-shadow(0 4px 3px rgba(0, 0, 0, 0.07));
      }
      
      /* Navbar shadow on scroll */
      .navbar-shadow {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        transition: box-shadow 0.3s ease;
      }
    </style>
</head>

<body class="bg-gray-50 font-sans fade-in">
  <nav class="bg-white sticky top-0 z-50 navbar-shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <!-- Logo section -->
        <div class="flex-shrink-0 flex items-center logo-container">
          <a href="Homepage.php" class="flex items-center space-x-2" aria-label="Student dashboard home">
            <img src="../../assets/images/uc.png" class="h-9 w-auto" alt="University of Cebu logo">
            <span class="text-lg font-semibold text-gray-800">|</span>
            <img src="../../assets/images/ccs.png" class="h-10 w-auto" alt="College of Computer Studies logo">
            <span class="hidden md:block text-primary-700 font-semibold text-lg tracking-tight">Student Portal</span>
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
          <!-- Home -->
          <a href="Homepage.php" class="px-3 py-2 text-sm font-medium text-gray-700 nav-link-hover transition duration-200 <?php echo basename($_SERVER['PHP_SELF']) == 'Homepage.php' ? 'nav-link-active' : ''; ?>">
            <i class="fas fa-home mr-1"></i>
            <span>Home</span>
          </a>
          
          <!-- Edit Profile -->
          <a href="Profile.php" class="px-3 py-2 text-sm font-medium text-gray-700 nav-link-hover transition duration-200 <?php echo basename($_SERVER['PHP_SELF']) == 'Profile.php' ? 'nav-link-active' : ''; ?>">
            <i class="fas fa-user-edit mr-1"></i>
            <span>Edit Profile</span>
          </a>
          
          <!-- History -->
          <a href="history.php" class="px-3 py-2 text-sm font-medium text-gray-700 nav-link-hover transition duration-200 <?php echo basename($_SERVER['PHP_SELF']) == 'history.php' ? 'nav-link-active' : ''; ?>">
            <i class="fas fa-history mr-1"></i>
            <span>History</span>
          </a>
          
          <!-- Reservation -->
          <a href="reservation.php" class="px-3 py-2 text-sm font-medium text-gray-700 nav-link-hover transition duration-200 <?php echo basename($_SERVER['PHP_SELF']) == 'reservation.php' ? 'nav-link-active' : ''; ?>">
            <i class="fas fa-calendar-check mr-1"></i>
            <span>Reservation</span>
          </a>
          
          <!-- Notifications -->
          <div class="relative dropdown-wrapper">
            <button type="button" class="px-3 py-2 text-sm font-medium text-gray-700 nav-link-hover rounded-md inline-flex items-center transition duration-200">
              <i class="fas fa-bell mr-1"></i>
              <span>Notifications</span>
              <?php $notifications = retrieve_notification($_SESSION['id_number']); 
              if (count($notifications) > 0): ?>
                <span class="notification-badge"><?php echo count($notifications); ?></span>
              <?php endif; ?>
              <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </button>
            <div class="dropdown-menu notification-dropdown">
              <div class="p-3">
                <h3 class="text-sm font-semibold text-gray-900 mb-2">Notifications</h3>
                <hr class="mb-2">
                <?php if (count($notifications) > 0): ?>
                  <div class="space-y-2">
                    <?php foreach($notifications as $row) : ?>
                      <div class="p-2 hover:bg-gray-50 rounded-lg transition-colors">
                        <p class="text-sm text-gray-700"><?php echo $row['message']; ?></p>
                        <p class="text-xs text-gray-500 mt-1"><?php echo isset($row['created_at']) ? date('M d, Y h:i A', strtotime($row['created_at'])) : ''; ?></p>
                      </div>
                    <?php endforeach; ?>
                  </div>
                <?php else: ?>
                  <div class="text-center py-4">
                    <p class="text-sm text-gray-500">No new notifications</p>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
          
          <!-- Logout Button -->
          <a href="../../auth/logout.php" class="ml-4 px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-md shadow-sm transition duration-200 flex items-center" onclick="handleLogout(event)" aria-label="Logout from student dashboard">
            <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Log out
          </a>
        </div>
      </div>
    </div>

    <!-- Mobile menu -->
    <div class="md:hidden bg-white border-t border-gray-100" id="mobile-menu" style="display: none;">
      <div class="px-2 pt-2 pb-3 space-y-1">
        <a href="Homepage.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) == 'Homepage.php' ? 'bg-primary-50 text-primary-600 font-semibold' : ''; ?>">
          <i class="fas fa-home mr-2 w-5 text-center"></i> Home
        </a>
        
        <a href="Profile.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) == 'Profile.php' ? 'bg-primary-50 text-primary-600 font-semibold' : ''; ?>">
          <i class="fas fa-user-edit mr-2 w-5 text-center"></i> Edit Profile
        </a>
        
        <a href="history.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) == 'history.php' ? 'bg-primary-50 text-primary-600 font-semibold' : ''; ?>">
          <i class="fas fa-history mr-2 w-5 text-center"></i> History
        </a>
        
        <a href="reservation.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-all duration-200 <?php echo basename($_SERVER['PHP_SELF']) == 'reservation.php' ? 'bg-primary-50 text-primary-600 font-semibold' : ''; ?>">
          <i class="fas fa-calendar-check mr-2 w-5 text-center"></i> Reservation
        </a>
        
        <!-- Mobile Notifications -->
        <div class="pt-2 pb-1">
          <p class="px-3 text-xs uppercase tracking-wider font-semibold text-gray-500">Notifications</p>
        </div>
        
        <div class="px-3 py-2 max-h-60 overflow-y-auto">
          <?php if (count($notifications) > 0): ?>
            <?php foreach($notifications as $row) : ?>
              <div class="p-2 mb-2 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-700"><?php echo $row['message']; ?></p>
                <p class="text-xs text-gray-500 mt-1"><?php echo isset($row['created_at']) ? date('M d, Y h:i A', strtotime($row['created_at'])) : ''; ?></p>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="text-center py-2">
              <p class="text-sm text-gray-500">No new notifications</p>
            </div>
          <?php endif; ?>
        </div>
        
        <div class="pt-4 pb-3 border-t border-gray-200">
          <a href="../../auth/logout.php" class="flex items-center justify-center w-full text-center px-4 py-2 text-base font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-md transition-all duration-200" onclick="handleLogout(event)">
            <i class="fas fa-sign-out-alt mr-2"></i> Log out
          </a>
        </div>
      </div>
    </div>
  </nav>

  <script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
      const menu = document.getElementById('mobile-menu');
      if (menu.style.display === 'none' || menu.style.display === '') {
        menu.style.display = 'block';
        this.setAttribute('aria-expanded', 'true');
      } else {
        menu.style.display = 'none';
        this.setAttribute('aria-expanded', 'false');
      }
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
        backdrop: true
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = href;
        }
      });
    }
  </script>
</body>
</html>
