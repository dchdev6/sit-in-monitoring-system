<?php
require_once '../../includes/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy | Sit-in Monitoring System</title>
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
        
        .highlight-box {
            background-color: rgba(14, 165, 233, 0.08);
            border-left: 3px solid #0ea5e9;
            padding: 1rem;
            border-radius: 0 6px 6px 0;
            margin-bottom: 1.5rem;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans">
    <main class="content-container">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-4xl mx-auto">
                <div class="mb-8" data-aos="fade-up">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Privacy Policy</h1>
                    <p class="text-gray-500">Last updated: <?php echo date('F d, Y'); ?></p>
                </div>
                
                <div class="content-card p-6 md:p-8 mb-8" data-aos="fade-up" data-aos-delay="100">
                    <p class="text-gray-700 mb-6">
                        The College of Computer Studies at the University of Cebu ("we", "our", or "us") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our Sit-in Monitoring System.
                    </p>
                    
                    <div class="highlight-box" data-aos="fade-up" data-aos-delay="150">
                        <p class="text-gray-700 font-medium">
                            Please read this Privacy Policy carefully. By accessing or using the Sit-in Monitoring System, you acknowledge that you have read, understood, and agree to the practices described in this policy.
                        </p>
                    </div>
                    
                    <h2 class="text-xl font-semibold text-gray-800 section-title">1. Information We Collect</h2>
                    <p class="text-gray-700 mb-4">
                        We collect several types of information for various purposes to provide and improve our service to you:
                    </p>
                    
                    <h3 class="text-lg font-medium text-gray-800 mb-2">Personal Information:</h3>
                    <div class="ml-2 mb-6">
                        <p class="list-item text-gray-700">Name and identification number</p>
                        <p class="list-item text-gray-700">Contact information (email address, phone number)</p>
                        <p class="list-item text-gray-700">Academic information (course, year level, subject)</p>
                        <p class="list-item text-gray-700">Sit-in session data (date, time, duration, location)</p>
                    </div>
                    
                    <h3 class="text-lg font-medium text-gray-800 mb-2">Usage Data:</h3>
                    <div class="ml-2 mb-6">
                        <p class="list-item text-gray-700">IP address and browser information</p>
                        <p class="list-item text-gray-700">Access times and pages viewed</p>
                        <p class="list-item text-gray-700">Device information</p>
                    </div>
                    
                    <h2 class="text-xl font-semibold text-gray-800 section-title">2. How We Use Your Information</h2>
                    <p class="text-gray-700 mb-4">
                        We use the collected information for various purposes, including:
                    </p>
                    
                    <div class="ml-2 mb-6">
                        <p class="list-item text-gray-700">To provide and maintain our service</p>
                        <p class="list-item text-gray-700">To track sit-in attendance and generate reports</p>
                        <p class="list-item text-gray-700">To notify you about changes to our service</p>
                        <p class="list-item text-gray-700">To provide support and assistance</p>
                        <p class="list-item text-gray-700">To detect, prevent, and address technical issues</p>
                        <p class="list-item text-gray-700">To improve the educational experience and outcomes</p>
                    </div>
                    
                    <h2 class="text-xl font-semibold text-gray-800 section-title">3. Data Security</h2>
                    <p class="text-gray-700 mb-6">
                        The security of your data is important to us. We strive to use commercially acceptable means to protect your personal information. However, no method of transmission over the Internet or method of electronic storage is 100% secure. While we strive to use commercially acceptable means to protect your personal information, we cannot guarantee its absolute security.
                    </p>
                    
                    <h2 class="text-xl font-semibold text-gray-800 section-title">4. Data Sharing and Disclosure</h2>
                    <p class="text-gray-700 mb-4">
                        We may share your information in the following situations:
                    </p>
                    
                    <div class="ml-2 mb-6">
                        <p class="list-item text-gray-700">With university administrators and faculty members for academic purposes</p>
                        <p class="list-item text-gray-700">To comply with legal obligations</p>
                        <p class="list-item text-gray-700">To protect and defend our rights or property</p>
                        <p class="list-item text-gray-700">With your consent or at your direction</p>
                    </div>
                    
                    <h2 class="text-xl font-semibold text-gray-800 section-title">5. Your Data Protection Rights</h2>
                    <p class="text-gray-700 mb-4">
                        You have certain data protection rights, including:
                    </p>
                    
                    <div class="ml-2 mb-6">
                        <p class="list-item text-gray-700">The right to access, update or delete your information</p>
                        <p class="list-item text-gray-700">The right to rectification if your information is inaccurate or incomplete</p>
                        <p class="list-item text-gray-700">The right to object to our processing of your personal data</p>
                        <p class="list-item text-gray-700">The right to be informed about how your data is being used</p>
                    </div>
                    
                    <h2 class="text-xl font-semibold text-gray-800 section-title">6. Cookies and Tracking</h2>
                    <p class="text-gray-700 mb-6">
                        We use cookies and similar tracking technologies to track activity on our system and store certain information. You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent.
                    </p>
                    
                    <h2 class="text-xl font-semibold text-gray-800 section-title">7. Changes to This Privacy Policy</h2>
                    <p class="text-gray-700 mb-6">
                        We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last updated" date. You are advised to review this Privacy Policy periodically for any changes.
                    </p>
                    
                    <h2 class="text-xl font-semibold text-gray-800 section-title">8. Contact Us</h2>
                    <p class="text-gray-700">
                        If you have any questions about this Privacy Policy, please contact us at:
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

    <!-- AOS Animation Library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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