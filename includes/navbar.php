<?php

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CCS | Home</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  <link rel="icon" href="assets/images/ccsLogo.ico" type="image/x-icon">

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
          <a href="index.php" class="flex items-center space-x-2" aria-label="Home">
            <img src="assets/images/uc.png" class="h-9 w-auto" alt="University of Cebu logo">
            <span class="text-lg font-semibold text-gray-800">|</span>
            <img src="assets/images/ccs.png" class="h-10 w-auto" alt="College of Computer Studies logo">
            <span class="hidden md:block text-primary-700 font-semibold text-lg tracking-tight">Sit-in Monitoring</span>
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
          <a href="index.php" class="px-3 py-2 text-sm font-medium text-gray-700 nav-link-hover transition duration-200">
            <i class="fas fa-home mr-1"></i>
            <span>Home</span>
          </a>
          
          <a href="login.php" class="px-3 py-2 text-sm font-medium text-gray-700 nav-link-hover transition duration-200 flex items-center">
            <i class="fas fa-sign-in-alt mr-1"></i>
            <span>Login</span>
          </a>
          
          <a href="register.php" class="ml-4 px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-md shadow-sm transition duration-200 flex items-center">
            <i class="fas fa-user-plus mr-2"></i>
            Register
          </a>
        </div>
      </div>
    </div>

    <!-- Mobile menu -->
    <div class="md:hidden bg-white border-t border-gray-100" id="mobile-menu">
      <div class="px-2 pt-2 pb-3 space-y-1">
        <a href="index.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-all duration-200">
          <i class="fas fa-home mr-2 w-5 text-center"></i> Home
        </a>
        
        <a href="auth/login.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-all duration-200">
          <i class="fas fa-sign-in-alt mr-2 w-5 text-center"></i> Login
        </a>
        
        <a href="auth/register.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-all duration-200">
          <i class="fas fa-user-plus mr-2 w-5 text-center"></i> Register
        </a>
      </div>
    </div>
  </nav>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  
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
    
    // Fix path issues for nested pages
    document.addEventListener('DOMContentLoaded', function() {
      // Get the current path
      const path = window.location.pathname;
      
      // Check if we're in a subdirectory
      const isInSubDir = path.includes('/auth/') || path.includes('/view/');
      
      if (isInSubDir) {
        // Fix image paths for logos
        const images = document.querySelectorAll('img[src^="assets/"]');
        images.forEach(img => {
          let src = img.getAttribute('src');
          img.setAttribute('src', '../' + src);
        });
        
        // Fix home link
        const homeLinks = document.querySelectorAll('a[href="index.php"]');
        homeLinks.forEach(link => {
          link.setAttribute('href', '../index.php');
        });
      }
    });
  </script>
</body>

</html>