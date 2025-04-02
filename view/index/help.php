<?php
// Fix the include path - it should point to the root includes folder
require_once '../../includes/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help & Support | Sit-in Monitoring System</title>
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
        
        .faq-item {
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 1.25rem;
            margin-bottom: 1.25rem;
        }
        
        .faq-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        
        .faq-question {
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            color: #374151;
            padding: 0.75rem 0;
            transition: all 0.3s ease;
        }
        
        .faq-question:hover {
            color: #0ea5e9;
        }
        
        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
            padding: 0 0.5rem;
        }
        
        .faq-answer.active {
            max-height: 1000px;
            padding: 0.75rem 0.5rem;
        }
        
        .contact-card {
            border-left: 4px solid #0ea5e9;
            background-color: #f0f9ff;
            padding: 1.5rem;
            border-radius: 0 8px 8px 0;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
        }
        
        .help-category {
            cursor: pointer;
            transition: all 0.2s ease;
            border-radius: 8px;
        }
        
        .help-category:hover {
            background-color: #f0f9ff;
            transform: translateY(-2px);
        }
        
        .help-category.active {
            background-color: #e0f2fe;
            border-left: 3px solid #0ea5e9;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans">
    <main class="content-container">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-5xl mx-auto">
                <div class="mb-8 text-center" data-aos="fade-up">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Help & Support</h1>
                    <p class="text-gray-500 max-w-2xl mx-auto">
                        Find answers to common questions and learn how to use the Sit-in Monitoring System effectively
                    </p>
                </div>
                
                <div class="flex justify-center mb-8" data-aos="fade-up" data-aos-delay="200">
                    <a href="../../view/homepage/index.php" class="bg-primary-600 hover:bg-primary-700 text-white py-2 px-6 rounded-lg transition duration-300 shadow-sm flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Home
                    </a>
                </div>
                
                <div class="grid md:grid-cols-3 gap-8 mb-8">
                    <div class="md:col-span-1" data-aos="fade-right" data-aos-delay="100">
                        <div class="content-card p-4 sticky top-20">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Help Categories</h2>
                            
                            <div class="space-y-2">
                                <div class="help-category active p-3" data-target="general">
                                    <i class="fas fa-info-circle text-primary-600 mr-2"></i> General Information
                                </div>
                                <div class="help-category p-3" data-target="account">
                                    <i class="fas fa-user-circle text-primary-600 mr-2"></i> Account Management
                                </div>
                                <div class="help-category p-3" data-target="sitin">
                                    <i class="fas fa-clipboard-list text-primary-600 mr-2"></i> Sit-in Monitoring
                                </div>
                                <div class="help-category p-3" data-target="reports">
                                    <i class="fas fa-chart-bar text-primary-600 mr-2"></i> Reports & Analytics
                                </div>
                                <div class="help-category p-3" data-target="technical">
                                    <i class="fas fa-cogs text-primary-600 mr-2"></i> Technical Issues
                                </div>
                            </div>
                            
                            <div class="mt-8">
                                <h3 class="text-md font-medium text-gray-700 mb-3">Need more help?</h3>
                                <a href="#contact-support" class="bg-primary-600 hover:bg-primary-700 text-white py-2 px-4 rounded-lg transition duration-300 shadow-sm w-full flex items-center justify-center">
                                    <i class="fas fa-headset mr-2"></i> Contact Support
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="md:col-span-2" data-aos="fade-left" data-aos-delay="200">
                        <div class="content-card p-6 md:p-8 mb-8">
                            <!-- General Information Section -->
                            <div class="faq-section active" id="general">
                                <h2 class="text-xl font-semibold text-gray-800 section-title">General Information</h2>
                                
                                <div class="faq-item">
                                    <div class="faq-question">
                                        <span>What is the Sit-in Monitoring System?</span>
                                        <i class="fas fa-chevron-down text-gray-500 transition-transform duration-300"></i>
                                    </div>
                                    <div class="faq-answer">
                                        <p class="text-gray-700">
                                            The Sit-in Monitoring System is a digital platform designed to track and manage sit-in sessions within the College of Computer Studies. It allows faculty members, administrators, and students to record attendance, monitor participation, and generate reports for educational assessment and improvement.
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="faq-item">
                                    <div class="faq-question">
                                        <span>Who can use the Sit-in Monitoring System?</span>
                                        <i class="fas fa-chevron-down text-gray-500 transition-transform duration-300"></i>
                                    </div>
                                    <div class="faq-answer">
                                        <p class="text-gray-700">
                                            The system is available to:
                                        </p>
                                        <ul class="list-disc ml-6 mt-2 text-gray-700">
                                            <li>Faculty members who conduct and observe sit-in sessions</li>
                                            <li>Department heads and administrators who oversee educational quality</li>
                                            <li>Students who participate in sit-in sessions</li>
                                            <li>Authorized staff members involved in educational assessment</li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="faq-item">
                                    <div class="faq-question">
                                        <span>What are the benefits of using this system?</span>
                                        <i class="fas fa-chevron-down text-gray-500 transition-transform duration-300"></i>
                                    </div>
                                    <div class="faq-answer">
                                        <p class="text-gray-700">
                                            The Sit-in Monitoring System provides several benefits:
                                        </p>
                                        <ul class="list-disc ml-6 mt-2 text-gray-700">
                                            <li>Streamlined attendance tracking and record keeping</li>
                                            <li>Real-time monitoring of educational sessions</li>
                                            <li>Data-driven insights for improving teaching methods</li>
                                            <li>Automated report generation for assessment purposes</li>
                                            <li>Increased transparency in the educational process</li>
                                            <li>Reduced administrative workload through digitization</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Account Management Section (hidden by default) -->
                            <div class="faq-section hidden" id="account">
                                <h2 class="text-xl font-semibold text-gray-800 section-title">Account Management</h2>
                                
                                <div class="faq-item">
                                    <div class="faq-question">
                                        <span>How do I create an account?</span>
                                        <i class="fas fa-chevron-down text-gray-500 transition-transform duration-300"></i>
                                    </div>
                                    <div class="faq-answer">
                                        <p class="text-gray-700">
                                            To create an account, click on the "Register" button on the homepage. You'll need to provide your university ID number, name, email address, and create a password. Faculty members may need to provide additional credentials for verification purposes.
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="faq-item">
                                    <div class="faq-question">
                                        <span>How do I reset my password?</span>
                                        <i class="fas fa-chevron-down text-gray-500 transition-transform duration-300"></i>
                                    </div>
                                    <div class="faq-answer">
                                        <p class="text-gray-700">
                                            If you've forgotten your password, click on the "Forgot Password?" link on the login page. You'll be prompted to enter your email address, and a password reset link will be sent to you. Click the link in the email and follow the instructions to create a new password.
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="faq-item">
                                    <div class="faq-question">
                                        <span>How do I update my profile information?</span>
                                        <i class="fas fa-chevron-down text-gray-500 transition-transform duration-300"></i>
                                    </div>
                                    <div class="faq-answer">
                                        <p class="text-gray-700">
                                            After logging in, click on your profile icon in the top-right corner and select "Profile Settings" from the dropdown menu. From there, you can update your personal information, contact details, and notification preferences.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Additional sections would go here -->
                            
                        </div>
                        
                        <!-- Contact Support Section -->
                        <div class="content-card p-6 md:p-8" id="contact-support" data-aos="fade-up" data-aos-delay="300">
                            <h2 class="text-xl font-semibold text-gray-800 section-title">Contact Support</h2>
                            
                            <p class="text-gray-700 mb-6">
                                If you couldn't find the answer to your question, please feel free to reach out to our support team. We're here to help!
                            </p>
                            
                            <div class="grid md:grid-cols-2 gap-6">
                                <div class="contact-card">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Technical Support</h3>
                                    <p class="text-gray-600 mb-4">For system issues, bugs, or technical problems</p>
                                    <div class="flex items-center text-primary-600">
                                        <i class="fas fa-envelope text-xl mr-3"></i>
                                        <span>ccs-support@uc.edu.ph</span>
                                    </div>
                                    <div class="flex items-center text-primary-600 mt-2">
                                        <i class="fas fa-phone text-xl mr-3"></i>
                                        <span>(032) 255-7777 ext. 123</span>
                                    </div>
                                </div>
                                
                                <div class="contact-card">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Administrative Support</h3>
                                    <p class="text-gray-600 mb-4">For account access, permissions, or policy questions</p>
                                    <div class="flex items-center text-primary-600">
                                        <i class="fas fa-envelope text-xl mr-3"></i>
                                        <span>ccs-admin@uc.edu.ph</span>
                                    </div>
                                    <div class="flex items-center text-primary-600 mt-2">
                                        <i class="fas fa-phone text-xl mr-3"></i>
                                        <span>(032) 255-7777 ext. 456</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-8 p-6 bg-gray-50 rounded-lg border border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Submit a Support Ticket</h3>
                                <form action="#" method="POST" class="space-y-4">
                                    <div class="grid md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Your Name</label>
                                            <input type="text" id="name" name="name" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition duration-200" required>
                                        </div>
                                        <div>
                                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                            <input type="email" id="email" name="email" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition duration-200" required>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                                        <input type="text" id="subject" name="subject" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition duration-200" required>
                                    </div>
                                    
                                    <div>
                                        <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                                        <textarea id="message" name="message" rows="4" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition duration-200" required></textarea>
                                    </div>
                                    
                                    <div class="flex justify-end">
                                        <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white py-2 px-6 rounded-lg transition duration-300 shadow-sm flex items-center">
                                            <i class="fas fa-paper-plane mr-2"></i> Submit Ticket
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
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
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <!-- Add this right before the AOS script in each file -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                once: true,
                duration: 800
            });
            
            // FAQ accordion functionality
            const faqQuestions = document.querySelectorAll('.faq-question');
            faqQuestions.forEach(question => {
                question.addEventListener('click', function() {
                    const answer = this.nextElementSibling;
                    const icon = this.querySelector('i');
                    
                    // Toggle the answer
                    answer.classList.toggle('active');
                    
                    // Rotate the icon
                    if (answer.classList.contains('active')) {
                        icon.style.transform = 'rotate(180deg)';
                    } else {
                        icon.style.transform = 'rotate(0)';
                    }
                });
            });
            
            // Help category switching
            const helpCategories = document.querySelectorAll('.help-category');
            const faqSections = document.querySelectorAll('.faq-section');
            
            helpCategories.forEach(category => {
                category.addEventListener('click', function() {
                    // Remove active class from all categories
                    helpCategories.forEach(cat => cat.classList.remove('active'));
                    
                    // Add active class to clicked category
                    this.classList.add('active');
                    
                    // Hide all FAQ sections
                    faqSections.forEach(section => section.classList.add('hidden'));
                    
                    // Show the selected FAQ section
                    const targetSection = document.getElementById(this.dataset.target);
                    if (targetSection) {
                        targetSection.classList.remove('hidden');
                    }
                });
            });
            
            // Initialize with first section visible
            if (faqSections.length > 0) {
                faqSections[0].classList.remove('hidden');
            }
            
            // Support form submission with SweetAlert
            const supportForm = document.querySelector('form');
            if (supportForm) {
                supportForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Support Ticket Submitted',
                        text: 'Thank you for contacting us. We will respond to your inquiry as soon as possible.',
                        confirmButtonText: 'Close',
                        confirmButtonColor: '#0ea5e9'
                    }).then(() => {
                        supportForm.reset();
                    });
                });
            }
            
            // Fix image and path issues
            const logoImages = document.querySelectorAll('img[src^="assets/"]');
            logoImages.forEach(img => {
                const src = img.getAttribute('src');
                img.setAttribute('src', '../../' + src);
            });
            
            const ucLogo = document.querySelector('img[alt="University of Cebu logo"]');
            const ccsLogo = document.querySelector('img[alt="College of Computer Studies logo"]');
            
            if (ucLogo) ucLogo.setAttribute('src', '../../assets/images/uc.png');
            if (ccsLogo) ccsLogo.setAttribute('src', '../../assets/images/ccs.png');
            
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