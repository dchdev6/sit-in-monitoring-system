<?php
require_once '../../includes/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service | Sit-in Monitoring System</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
            }
          }
        }
      }
    </script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <style>
        .content-container {
            min-height: calc(100vh - 64px - 80px);
            padding: 2rem 0;
        }
        
        .content-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }
        
        .content-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
        }
        
        .section-title {
            position: relative;
            padding-bottom: 0.75rem;
            margin-bottom: 1.5rem;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            height: 3px;
            width: 60px;
            background-color: #0ea5e9;
            border-radius: 3px;
        }
        
        .list-item {
            position: relative;
            padding-left: 1.75rem;
            margin-bottom: 0.75rem;
        }
        
        .list-item::before {
            content: '•';
            position: absolute;
            left: 0;
            color: #0ea5e9;
            font-weight: bold;
            font-size: 1.25rem;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans">
    <main class="content-container">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-4xl mx-auto">
                <div class="mb-8" data-aos="fade-up">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Terms of Service</h1>
                    <p class="text-gray-500">Last updated: <?php echo date('F d, Y'); ?></p>
                </div>
                
                <div class="content-card p-6 md:p-8 mb-8" data-aos="fade-up" data-aos-delay="100">
                    <p class="text-gray-700 mb-6">
                        Please read these Terms of Service ("Terms", "Terms of Service") carefully before using the Sit-in Monitoring System operated by the College of Computer Studies, University of Cebu.
                    </p>
                    
                    <p class="text-gray-700 mb-6">
                        Your access to and use of the Service is conditioned on your acceptance of and compliance with these Terms. These Terms apply to all visitors, users, and others who access or use the Service.
                    </p>
                    
                    <p class="text-gray-700 mb-6">
                        By accessing or using the Service, you agree to be bound by these Terms. If you disagree with any part of the terms, then you may not access the Service.
                    </p>
                    
                    <h2 class="text-xl font-semibold text-gray-800 section-title">1. Use of the System</h2>
                    <p class="text-gray-700 mb-4">
                        The Sit-in Monitoring System is provided as a tool to monitor and track sit-in sessions within the College of Computer Studies. By using this system, you agree to:
                    </p>
                    
                    <div class="ml-2 mb-6">
                        <p class="list-item text-gray-700">Submit accurate and truthful information</p>
                        <p class="list-item text-gray-700">Maintain the confidentiality of your account credentials</p>
                        <p class="list-item text-gray-700">Not share your account with any third party</p>
                        <p class="list-item text-gray-700">Use the system for its intended educational purposes only</p>
                        <p class="list-item text-gray-700">Comply with all applicable laws and regulations</p>
                    </div>
                    
                    <h2 class="text-xl font-semibold text-gray-800 section-title">2. Intellectual Property</h2>
                    <p class="text-gray-700 mb-6">
                        The Sit-in Monitoring System and its original content, features, and functionality are and will remain the exclusive property of the University of Cebu, College of Computer Studies. The system is protected by copyright, trademark, and other intellectual property laws.
                    </p>
                    
                    <h2 class="text-xl font-semibold text-gray-800 section-title">3. Accounts</h2>
                    <p class="text-gray-700 mb-4">
                        When you create an account with us, you must provide accurate, complete, and up-to-date information. Failure to do so constitutes a breach of the Terms, which may result in immediate termination of your account.
                    </p>
                    
                    <p class="text-gray-700 mb-6">
                        You are responsible for safeguarding the password and for all activities that occur under your account. You agree to notify us immediately of any unauthorized use of your account or any other breach of security.
                    </p>
                    
                    <h2 class="text-xl font-semibold text-gray-800 section-title">4. Termination</h2>
                    <p class="text-gray-700 mb-6">
                        We may terminate or suspend your account immediately, without prior notice or liability, for any reason, including, without limitation, if you breach the Terms.
                    </p>
                    
                    <h2 class="text-xl font-semibold text-gray-800 section-title">5. Limitation of Liability</h2>
                    <p class="text-gray-700 mb-6">
                        In no event shall the University of Cebu, College of Computer Studies, be liable for any indirect, incidental, special, consequential or punitive damages, including without limitation, loss of data or other intangible losses, resulting from your access to or use of or inability to access or use the system.
                    </p>
                    
                    <h2 class="text-xl font-semibold text-gray-800 section-title">6. Changes</h2>
                    <p class="text-gray-700 mb-6">
                        We reserve the right, at our sole discretion, to modify or replace these Terms at any time. It is your responsibility to review these Terms periodically for changes. Your continued use of the Service following the posting of any changes constitutes acceptance of those changes.
                    </p>
                    
                    <h2 class="text-xl font-semibold text-gray-800 section-title">7. Contact Us</h2>
                    <p class="text-gray-700">
                        If you have any questions about these Terms, please contact us at:
                    </p>
                    <p class="text-primary-600 font-medium mt-2">
                        <i class="fas fa-envelope mr-2"></i> ccs@uc.edu.ph
                    </p>
                </div>
                
                <div class="flex justify-center mb-8" data-aos="fade-up" data-aos-delay="200">
                    <a href="../../view/homepage/index.php" class="bg-primary-600 hover:bg-primary-700 text-white py-2 px-6 rounded-lg transition duration-300 shadow-sm flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Home
                    </a>
                </div>
            </div>
        </div>
    </main>
    
    <footer class="bg-white py-6 border-t border-gray-200">
        <div class="container mx-auto px-4">
            <div class="text-center text-gray-500 text-sm">
                &copy; <?php echo date('Y'); ?> College of Computer Studies, University of Cebu. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Add this right before the AOS script in each file -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- AOS Animation Library -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                once: true,
                duration: 800
            });
        });
    </script>
    <script>
    // Fix image and path issues
    document.addEventListener('DOMContentLoaded', function() {
      // Fix logo images
      const logoImages = document.querySelectorAll('img[src^="assets/"]');
      logoImages.forEach(img => {
        const src = img.getAttribute('src');
        img.setAttribute('src', '../../' + src);
      });
      
      // Fix UC/CCS logo images specifically
      const ucLogo = document.querySelector('img[alt="University of Cebu logo"]');
      const ccsLogo = document.querySelector('img[alt="College of Computer Studies logo"]');
      
      if (ucLogo) ucLogo.setAttribute('src', '../../assets/images/uc.png');
      if (ccsLogo) ccsLogo.setAttribute('src', '../../assets/images/ccs.png');
      
      // Ensure mobile menu works
      const mobileMenuButton = document.getElementById('mobile-menu-button');
      const mobileMenu = document.getElementById('mobile-menu');
      
      if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
          mobileMenu.classList.toggle('show');
          const expanded = mobileMenu.classList.contains('show');
          this.setAttribute('aria-expanded', expanded);
        });
      }
    });
    </script>
</body>
</html>