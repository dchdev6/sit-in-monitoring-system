<?php
require_once '../../includes/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sit-in Monitoring System | College of Computer Studies</title>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
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
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .hero-section {
            background: linear-gradient(rgba(8, 47, 73, 0.8), rgba(8, 47, 73, 0.9)), url('../../assets/images/campus.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        
        .feature-card {
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .feature-icon {
            transition: all 0.3s ease;
        }
        
        .feature-card:hover .feature-icon {
            transform: scale(1.2);
            color: #0ea5e9;
        }
        
        .btn-primary {
            background-color: #0284c7;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: #0369a1;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .scroll-down {
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-20px);
            }
            60% {
                transform: translateY(-10px);
            }
        }
        
        .shield-logo {
            width: 180px;
            height: 200px;
            position: relative;
        }

        .shield-outer {
            position: relative;
            width: 100%;
            height: 100%;
            filter: drop-shadow(0px 5px 15px rgba(14, 165, 233, 0.3));
            transition: all 0.5s ease;
        }

        .shield-outer:hover {
            transform: scale(1.05);
            filter: drop-shadow(0px 8px 20px rgba(14, 165, 233, 0.4));
        }

        .shield-inner {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Shield emblem animation */
        @keyframes glowing {
            0% { filter: drop-shadow(0px 0px 5px rgba(255, 255, 255, 0.3)); }
            50% { filter: drop-shadow(0px 0px 20px rgba(255, 255, 255, 0.5)); }
            100% { filter: drop-shadow(0px 0px 5px rgba(255, 255, 255, 0.3)); }
        }

        .shield-inner i {
            animation: glowing 2s infinite;
        }
        
        .stat-card {
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .counter-animation {
            transition: all 0.3s ease;
        }
        
        .testimonial-card {
            transition: all 0.3s ease;
        }
        
        .testimonial-card:hover {
            transform: translateY(-5px) scale(1.02);
        }
        
        .wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
            transform: rotate(180deg);
        }

        .wave svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 80px;
            transform: rotateY(180deg);
        }
        
        .pulse-animation {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }

        svg {
            display: block;
            max-width: 100%;
            height: auto;
        }

        .shield-outer svg {
            position: relative;
            z-index: 0;
        }

        /* Ensure Font Awesome icons display correctly */
        .fas, .fab {
            font-family: 'Font Awesome 6 Free', 'Font Awesome 6 Brands';
            font-weight: 900;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans">
    <!-- Hero Section -->
    <section class="hero-section relative text-white min-h-screen flex flex-col justify-center items-center text-center px-4 py-20">
        <div class="container mx-auto max-w-5xl" data-aos="fade-up" data-aos-duration="1000">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="md:w-1/2 mb-10 md:mb-0 text-left">
                    <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">Sit-in Monitoring System</h1>
                    <p class="text-xl text-gray-200 mb-8">Empowering educators with comprehensive tools to monitor, track, and enhance educational quality through efficient sit-in assessment.</p>
                    <div class="flex flex-wrap gap-4">
                        <a href="../../auth/login.php" class="btn-primary px-8 py-3 rounded-lg font-medium inline-flex items-center">
                            <i class="fas fa-sign-in-alt mr-2"></i> Login
                        </a>
                        <a href="../../auth/register.php" class="bg-white text-primary-700 hover:bg-gray-100 px-8 py-3 rounded-lg font-medium transition-all duration-300 inline-flex items-center">
                            <i class="fas fa-user-plus mr-2"></i> Register
                        </a>
                    </div>
                </div>
                
                <div class="md:w-1/2 flex justify-center">
                    <!-- Shield Logo - Fixed Version -->
                    <div class="shield-logo relative mb-4" data-aos="zoom-in" data-aos-delay="300">
                        <div class="shield-outer">
                            <!-- Using a more reliable SVG implementation -->
                            <svg width="180" height="200" viewBox="0 0 180 200" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: block; max-width: 100%;">
                                <path d="M90 0L180 40V90C180 146.5 142 180 90 200C38 180 0 146.5 0 90V40L90 0Z" fill="#0284c7"></path>
                            </svg>
                            <div class="shield-inner absolute inset-0 flex items-center justify-center">
                                <div class="text-white flex flex-col items-center p-4">
                                    <i class="fas fa-university text-4xl mb-2"></i>
                                    <span class="font-bold text-xl">UC</span>
                                    <div class="w-16 h-1 bg-white opacity-70 rounded-full my-2"></div>
                                    <i class="fas fa-laptop-code text-3xl mb-1"></i>
                                    <span class="font-bold">CCS</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="absolute bottom-10 left-0 right-0 text-center">
            <a href="#features" class="scroll-down text-white text-opacity-80 hover:text-opacity-100 transition-all duration-300">
                <span class="block mb-2 text-sm">Scroll Down</span>
                <i class="fas fa-chevron-down text-xl"></i>
            </a>
        </div>
        
        <!-- Replace the problematic wave SVG with this fixed version -->
        <div class="wave">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none" style="display: block; width: 100%; height: 80px;">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" fill="#FFFFFF"></path>
            </svg>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Key Features</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Our system provides a comprehensive set of tools to streamline the sit-in monitoring process</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="feature-card bg-white p-6 rounded-xl" data-aos="fade-up" data-aos-delay="100">
                    <div class="bg-primary-50 w-16 h-16 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-chart-line text-primary-600 text-2xl feature-icon"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3 text-center">Real-time Monitoring</h3>
                    <p class="text-gray-600 text-center">Track sit-in sessions in real-time with comprehensive dashboards and notifications.</p>
                </div>
                
                <div class="feature-card bg-white p-6 rounded-xl" data-aos="fade-up" data-aos-delay="200">
                    <div class="bg-primary-50 w-16 h-16 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-clipboard-check text-primary-600 text-2xl feature-icon"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3 text-center">Detailed Assessments</h3>
                    <p class="text-gray-600 text-center">Create and manage detailed assessment criteria for comprehensive evaluation.</p>
                </div>
                
                <div class="feature-card bg-white p-6 rounded-xl" data-aos="fade-up" data-aos-delay="300">
                    <div class="bg-primary-50 w-16 h-16 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-file-alt text-primary-600 text-2xl feature-icon"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3 text-center">Report Generation</h3>
                    <p class="text-gray-600 text-center">Generate comprehensive reports with actionable insights for educational improvement.</p>
                </div>
                
                <div class="feature-card bg-white p-6 rounded-xl" data-aos="fade-up" data-aos-delay="400">
                    <div class="bg-primary-50 w-16 h-16 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-calendar-alt text-primary-600 text-2xl feature-icon"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3 text-center">Scheduling System</h3>
                    <p class="text-gray-600 text-center">Plan and schedule sit-in sessions with ease using our intuitive calendar system.</p>
                </div>
                
                <div class="feature-card bg-white p-6 rounded-xl" data-aos="fade-up" data-aos-delay="500">
                    <div class="bg-primary-50 w-16 h-16 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-bell text-primary-600 text-2xl feature-icon"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3 text-center">Notifications</h3>
                    <p class="text-gray-600 text-center">Stay informed with timely notifications about upcoming sessions and feedback.</p>
                </div>
                
                <div class="feature-card bg-white p-6 rounded-xl" data-aos="fade-up" data-aos-delay="600">
                    <div class="bg-primary-50 w-16 h-16 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-shield-alt text-primary-600 text-2xl feature-icon"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3 text-center">Secure Access</h3>
                    <p class="text-gray-600 text-center">Role-based access control ensures data security and appropriate permissions.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Stats Section -->
    <section class="py-20 bg-primary-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">System Impact</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Making a difference in educational quality and assessment</p>
            </div>
            
            <div class="grid md:grid-cols-4 gap-6">
                <div class="stat-card bg-white p-6 text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-primary-600 text-4xl font-bold mb-2 counter-animation" data-target="500">0</div>
                    <p class="text-gray-600">Active Users</p>
                </div>
                
                <div class="stat-card bg-white p-6 text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-primary-600 text-4xl font-bold mb-2 counter-animation" data-target="1250">0</div>
                    <p class="text-gray-600">Sit-in Sessions</p>
                </div>
                
                <div class="stat-card bg-white p-6 text-center" data-aos="fade-up" data-aos-delay="300">
                    <div class="text-primary-600 text-4xl font-bold mb-2 counter-animation" data-target="30">0</div>
                    <p class="text-gray-600">Departments</p>
                </div>
                
                <div class="stat-card bg-white p-6 text-center" data-aos="fade-up" data-aos-delay="400">
                    <div class="text-primary-600 text-4xl font-bold mb-2 counter-animation" data-target="98">0</div>
                    <p class="text-gray-600">Satisfaction Rate (%)</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Testimonials Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">What People Say</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Feedback from faculty and administrators using our system</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="testimonial-card bg-gray-50 p-6 rounded-xl" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-user text-primary-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Dr. Anna Santos</h4>
                            <p class="text-gray-500 text-sm">Department Head, Computer Science</p>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"This system has revolutionized how we conduct sit-in evaluations. It's intuitive, efficient, and provides valuable insights for improving teaching methods."</p>
                    <div class="mt-4 text-primary-500">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                
                <div class="testimonial-card bg-gray-50 p-6 rounded-xl" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-user text-primary-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Prof. Michael Garcia</h4>
                            <p class="text-gray-500 text-sm">Faculty, Information Technology</p>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"The detailed reports have helped me identify areas where I can improve my teaching strategies. The feedback mechanism is exceptionally well-designed."</p>
                    <div class="mt-4 text-primary-500">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>
                
                <div class="testimonial-card bg-gray-50 p-6 rounded-xl" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-user text-primary-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Dean Patricia Reyes</h4>
                            <p class="text-gray-500 text-sm">College Administrator</p>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"The analytics provided by this system have been instrumental in our accreditation process. It's a comprehensive solution for educational quality assurance."</p>
                    <div class="mt-4 text-primary-500">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="py-20 bg-primary-600 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-6" data-aos="fade-up">Ready to Get Started?</h2>
            <p class="text-xl mb-8 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="100">Join our Sit-in Monitoring System and transform how you evaluate and improve educational quality</p>
            <div class="flex flex-wrap justify-center gap-4" data-aos="fade-up" data-aos-delay="200">
                <a href="../../auth/register.php" class="bg-white text-primary-700 hover:bg-gray-100 px-8 py-3 rounded-lg font-medium transition-all duration-300 inline-flex items-center">
                    <i class="fas fa-user-plus mr-2"></i> Register Now
                </a>
                <a href="../index/help.php" class="bg-transparent border-2 border-white text-white hover:bg-white hover:text-primary-700 px-8 py-3 rounded-lg font-medium transition-all duration-300 inline-flex items-center">
                    <i class="fas fa-info-circle mr-2"></i> Learn More
                </a>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-semibold mb-4">About Us</h3>
                    <p class="text-gray-400">The Sit-in Monitoring System is developed by the College of Computer Studies to streamline and enhance the educational evaluation process.</p>
                    <div class="mt-4 flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-xl font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="../../auth/login.php" class="text-gray-400 hover:text-white transition-colors duration-300">Login</a></li>
                        <li><a href="../../auth/register.php" class="text-gray-400 hover:text-white transition-colors duration-300">Register</a></li>
                        <li><a href="../index/help.php" class="text-gray-400 hover:text-white transition-colors duration-300">Help & Support</a></li>
                        <li><a href="../index/privacy.php" class="text-gray-400 hover:text-white transition-colors duration-300">Privacy Policy</a></li>
                        <li><a href="../index/terms.php" class="text-gray-400 hover:text-white transition-colors duration-300">Terms of Service</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-xl font-semibold mb-4">Contact Us</h3>
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-3 text-primary-500"></i>
                            <span class="text-gray-400">College of Computer Studies, University of Cebu, Cebu City</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone-alt mr-3 text-primary-500"></i>
                            <span class="text-gray-400">(032) 255-7777</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3 text-primary-500"></i>
                            <span class="text-gray-400">ccs@uc.edu.ph</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-10 pt-6 text-center">
                <p class="text-gray-500">&copy; <?php echo date('Y'); ?> College of Computer Studies, University of Cebu. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        // Initialize AOS animations
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                once: true,
                duration: 800,
                offset: 50
            });
            
            // Counter animation
            const counters = document.querySelectorAll('.counter-animation');
            const speed = 200;
            
            counters.forEach(counter => {
                const animate = () => {
                    const value = +counter.getAttribute('data-target');
                    const data = +counter.innerText;
                    const time = value / speed;
                    
                    if (data < value) {
                        counter.innerText = Math.ceil(data + time);
                        setTimeout(animate, 1);
                    } else {
                        counter.innerText = value;
                    }
                }
                
                // Only start counting when element is in viewport
                const counterObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            animate();
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.5 });
                
                counterObserver.observe(counter);
            });
            
            // Shield logo interaction
            const shieldLogo = document.querySelector('.shield-outer');
            if (shieldLogo) {
                shieldLogo.addEventListener('mouseenter', function() {
                    const icons = this.querySelectorAll('i');
                    icons.forEach(icon => {
                        icon.style.transform = 'scale(1.2)';
                        icon.style.transition = 'transform 0.3s ease';
                    });
                });
                
                shieldLogo.addEventListener('mouseleave', function() {
                    const icons = this.querySelectorAll('i');
                    icons.forEach(icon => {
                        icon.style.transform = 'scale(1)';
                    });
                });
            }
            
            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    const targetElement = document.querySelector(targetId);
                    
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 80,
                            behavior: 'smooth'
                        });
                    }
                });
            });
            
            // Fix UC/CCS logos in navbar
            const logoImages = document.querySelectorAll('img[src^="assets/"]');
            logoImages.forEach(img => {
                const src = img.getAttribute('src');
                img.setAttribute('src', '../../' + src);
            });
            
            // Specifically target UC and CCS logos
            const ucLogo = document.querySelector('img[alt="University of Cebu logo"]');
            const ccsLogo = document.querySelector('img[alt="College of Computer Studies logo"]');
            
            if (ucLogo) ucLogo.setAttribute('src', '../../assets/images/uc.png');
            if (ccsLogo) ccsLogo.setAttribute('src', '../../assets/images/ccs.png');
        });
    </script>
</body>
</html>