<?php
include '../../includes/navbar_admin.php';

$listPerson = retrieve_students();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Information</title>
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <!-- DataTables CSS -->
  <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
  <!-- SweetAlert2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
  <!-- Inter Font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Animation Library - AOS -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
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
          keyframes: {
            fadeIn: {
              '0%': { opacity: '0' },
              '100%': { opacity: '1' },
            },
            slideUp: {
              '0%': { transform: 'translateY(20px)', opacity: '0' },
              '100%': { transform: 'translateY(0)', opacity: '1' },
            },
            pulse: {
              '0%, 100%': { transform: 'scale(1)' },
              '50%': { transform: 'scale(1.05)' },
            },
            shimmer: {
              '0%': { backgroundPosition: '-1000px 0' },
              '100%': { backgroundPosition: '1000px 0' },
            },
          },
          animation: {
            fadeIn: 'fadeIn 0.5s ease-out',
            slideUp: 'slideUp 0.5s ease-out',
            pulse: 'pulse 2s infinite',
            shimmer: 'shimmer 2s infinite linear',
          },
        }
      }
    }
  </script>
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
    
    /* DataTables Custom Styling */
    .dataTables_wrapper {
      background-color: white;
      border-radius: 0.5rem;
      padding: 1.5rem;
    }
    
    .dataTables_filter input {
      border: 1px solid #e5e7eb;
      border-radius: 0.5rem;
      padding: 0.5rem 0.75rem;
      margin-left: 0.5rem;
      font-size: 0.875rem;
      transition: all 0.2s;
    }
    
    .dataTables_filter input:focus {
      outline: none;
      border-color: #0284c7;
      box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.2);
    }
    
    .dataTables_length select {
      border: 1px solid #e5e7eb;
      border-radius: 0.5rem;
      padding: 0.5rem 2rem 0.5rem 0.75rem;
      font-size: 0.875rem;
      background-position: right 0.5rem center;
      transition: all 0.2s;
    }
    
    .dataTables_length select:focus {
      outline: none;
      border-color: #0284c7;
      box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.2);
    }
    
    .dataTables_info, .dataTables_length, .dataTables_filter {
      margin-bottom: 1rem;
      font-size: 0.875rem;
      color: #4b5563;
    }
    
    .dataTables_paginate {
      margin-top: 1rem;
    }
    
    .dataTables_paginate .paginate_button {
      padding: 0.5rem 0.75rem;
      margin: 0 0.25rem;
      border-radius: 0.375rem;
      border: 1px solid #e5e7eb;
      background-color: #fff;
      color: #374151;
      transition: all 0.2s;
    }
    
    .dataTables_paginate .paginate_button.current {
      background-color: #0284c7 !important;
      border-color: #0284c7 !important;
      color: white !important;
      font-weight: 500;
    }
    
    .dataTables_paginate .paginate_button:hover:not(.current):not(.disabled) {
      background-color: #f3f4f6 !important;
      color: #111827 !important;
      border-color: #e5e7eb !important;
    }
    
    .dataTables_paginate .paginate_button.disabled {
      opacity: 0.5;
      cursor: not-allowed;
    }
    
    table.dataTable {
      border-collapse: separate;
      border-spacing: 0;
      width: 100%;
    }
    
    table.dataTable thead th {
      background: #f9fafb;
      color: #374151;
      font-weight: 600;
      padding: 1rem;
      text-align: left;
      border-bottom: 2px solid #e5e7eb;
      white-space: nowrap;
    }
    
    table.dataTable tbody tr {
      transition: all 0.3s ease;
    }
    
    table.dataTable tbody tr:hover {
      background-color: #f3f4f6;
      transform: translateY(-2px);
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    table.dataTable tbody td {
      padding: 1rem;
      border-bottom: 1px solid #e5e7eb;
      vertical-align: middle;
      transition: all 0.2s ease;
    }
    
    /* Status Badges */
    .status-badge {
      display: inline-block;
      padding: 0.25rem 0.75rem;
      border-radius: 9999px;
      font-size: 0.75rem;
      font-weight: 500;
      text-align: center;
      transition: all 0.3s ease;
    }
    
    .status-badge.low {
      background-color: #fee2e2;
      color: #b91c1c;
    }
    
    .status-badge.medium {
      background-color: #fef3c7;
      color: #92400e;
    }
    
    .status-badge.good {
      background-color: #d1fae5;
      color: #047857;
    }
    
    /* Button Animations */
    .btn-animated {
      position: relative;
      overflow: hidden;
      transform: translateZ(0);
    }
    
    .btn-animated::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 300%;
      height: 300%;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      transform: translate(-50%, -50%) scale(0);
      transition: transform 0.6s ease-out;
    }
    
    .btn-animated:hover::before {
      transform: translate(-50%, -50%) scale(1);
    }
    
    /* Card hover effects */
    .stat-card {
      transition: all 0.3s ease;
    }
    
    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    .stat-card:hover .icon-container {
      transform: scale(1.1);
    }
    
    .icon-container {
      transition: transform 0.3s ease;
    }
    
    /* Shimmer effect */
    .shimmer {
      background: linear-gradient(to right, #f6f7f8 0%, #edeef1 20%, #f6f7f8 40%, #f6f7f8 100%);
      background-size: 1000px 100%;
      animation: shimmer 2s infinite linear;
    }
    
    /* Counter Animation */
    .counter-value {
      display: inline-block;
      transition: all 0.5s ease;
    }
    
    /* Custom table row animations */
    .row-enter {
      opacity: 0;
      transform: translateY(10px);
    }
    
    .row-enter-active {
      opacity: 1;
      transform: translateY(0px);
      transition: opacity 0.3s, transform 0.3s;
    }
  </style>
</head>

<body class="bg-gray-50 font-sans text-gray-800">
  <div class="container mx-auto px-4 py-8 max-w-7xl">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6" data-aos="fade-down">
      <div>
        <h1 class="text-2xl font-bold text-gray-800 flex items-center">
          <i class="fas fa-user-graduate mr-3 text-primary-600"></i>
          Student Information
        </h1>
        <p class="text-gray-500 mt-1">Manage student records and sessions</p>
      </div>
      <div class="flex space-x-3 mt-4 md:mt-0">
        <a href="Add.php" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-300 flex items-center shadow-sm btn-animated">
          <i class="fas fa-plus mr-2"></i>
          Add Student
        </a>
        <button id="resetButton" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-2.5 px-4 rounded-lg transition duration-300 flex items-center shadow-sm btn-animated">
          <i class="fas fa-sync-alt mr-2 text-gray-500"></i>
          Reset Sessions
        </button>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-6">
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 stat-card" data-aos="fade-up" data-aos-delay="100">
        <div class="flex items-center">
          <div class="rounded-full bg-blue-100 p-3 mr-4 icon-container">
            <i class="fas fa-users text-blue-600"></i>
          </div>
          <div>
            <p class="text-sm text-gray-500 font-medium">Total Students</p>
            <p class="text-2xl font-bold counter" data-target="<?php echo count($listPerson); ?>">0</p>
          </div>
        </div>
      </div>
      
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 stat-card" data-aos="fade-up" data-aos-delay="200">
        <div class="flex items-center">
          <div class="rounded-full bg-green-100 p-3 mr-4 icon-container">
            <i class="fas fa-user-check text-green-600"></i>
          </div>
          <div>
            <p class="text-sm text-gray-500 font-medium">Active Students</p>
            <p class="text-2xl font-bold counter" data-target="<?php 
                $activeCount = 0;
                foreach($listPerson as $person) {
                  if($person['session'] > 0) $activeCount++;
                }
                echo $activeCount;
              ?>">0</p>
          </div>
        </div>
      </div>
      
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 stat-card" data-aos="fade-up" data-aos-delay="300">
        <div class="flex items-center">
          <div class="rounded-full bg-yellow-100 p-3 mr-4 icon-container">
            <i class="fas fa-clock text-yellow-600"></i>
          </div>
          <div>
            <p class="text-sm text-gray-500 font-medium">Avg. Sessions</p>
            <p class="text-2xl font-bold counter-decimal" data-target="<?php 
                $totalSessions = 0;
                foreach($listPerson as $person) {
                  $totalSessions += $person['session'];
                }
                echo count($listPerson) > 0 ? round($totalSessions / count($listPerson), 1) : 0;
              ?>">0</p>
          </div>
        </div>
      </div>
      
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 stat-card" data-aos="fade-up" data-aos-delay="400">
        <div class="flex items-center">
          <div class="rounded-full bg-purple-100 p-3 mr-4 icon-container">
            <i class="fas fa-graduation-cap text-purple-600"></i>
          </div>
          <div>
            <p class="text-sm text-gray-500 font-medium">Programs</p>
            <p class="text-2xl font-bold counter" data-target="<?php 
                $courses = array();
                foreach($listPerson as $person) {
                  if(!in_array($person['course'], $courses)) {
                    $courses[] = $person['course'];
                  }
                }
                echo count($courses);
              ?>">0</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
      <div class="p-6">
        <table id="studentTable" class="w-full">
          <thead>
            <tr>
              <th>ID Number</th>
              <th>Name</th>
              <th>Year Level</th>
              <th>Course</th>
              <th>Remaining Sessions</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($listPerson as $person) : ?>
              <tr class="row-animation">
                <td class="font-medium"><?php echo $person['id_number']; ?></td>
                <td><?php echo $person['firstName'] . " " . $person['middleName'] . ". " . $person['lastName']; ?></td>
                <td>
                  <?php 
                    $yearLevel = $person['yearLevel'];
                    $yearText = "";
                    
                    switch($yearLevel) {
                      case "1st Year":
                        $yearText = "Freshmen";
                        break;
                      case "2nd Year":
                        $yearText = "Sophomore";
                        break;
                      case "3rd Year":
                        $yearText = "Junior";
                        break;
                      case "4th Year":
                        $yearText = "Senior";
                        break;
                      default:
                        $yearText = $yearLevel;
                    }
                    
                    echo $yearText;
                  ?>
                </td>
                <td><?php echo $person['course']; ?></td>
                <td>
                  <?php 
                    $session = $person['session'];
                    $statusClass = '';
                    
                    if($session <= 2) {
                      $statusClass = 'low';
                    } else if($session <= 5) {
                      $statusClass = 'medium';
                    } else {
                      $statusClass = 'good';
                    }
                  ?>
                  <span class="status-badge <?php echo $statusClass; ?>">
                    <?php echo $session; ?> sessions
                  </span>
                </td>
                <td>
                  <div class="flex space-x-2 justify-center">
                    <form action="Admin.php" method="POST" class="inline-block">
                      <input type="hidden" name="idNum" value="<?php echo $person['id_number']; ?>" />
                      <button type="submit" name="edit" class="bg-primary-600 hover:bg-primary-700 text-white p-2 rounded-lg transition-all duration-300 transform hover:scale-110 hover:rotate-3" title="Edit Student">
                        <i class="fas fa-edit"></i>
                      </button>
                    </form>
                    <form action="Students.php" method="POST" class="inline-block delete-form">
                      <input type="hidden" name="idNum" value="<?php echo $person['id_number']; ?>" />
                      <button type="submit" name="deleteStudent" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-all duration-300 transform hover:scale-110 hover:-rotate-3" title="Delete Student">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                    <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 p-2 rounded-lg transition-all duration-300 transform hover:scale-110 view-details" data-id="<?php echo $person['id_number']; ?>" title="View Details">
                      <i class="fas fa-eye"></i>
                    </button>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- jQuery (required for DataTables) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- AOS Animation Library -->
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script>
    $(document).ready(function() {
      // Initialize AOS animations
      AOS.init({
        duration: 800,
        once: true
      });
      
      // Animate counter numbers
      function animateCounter() {
        $('.counter').each(function() {
          const $this = $(this);
          const target = parseInt($this.attr('data-target'));
          
          $({ Counter: 0 }).animate({
            Counter: target
          }, {
            duration: 1000,
            easing: 'swing',
            step: function() {
              $this.text(Math.ceil(this.Counter));
            }
          });
        });
        
        $('.counter-decimal').each(function() {
          const $this = $(this);
          const target = parseFloat($this.attr('data-target'));
          
          $({ Counter: 0 }).animate({
            Counter: target
          }, {
            duration: 1000,
            easing: 'swing',
            step: function() {
              $this.text(this.Counter.toFixed(1));
            }
          });
        });
      }
      
      // Call counter animation after a short delay
      setTimeout(animateCounter, 500);
      
      // Initialize DataTable with row animation
      const table = $('#studentTable').DataTable({
        responsive: true,
        language: {
          search: "_INPUT_",
          searchPlaceholder: "Search students...",
          paginate: {
            first: '<i class="fas fa-angle-double-left"></i>',
            previous: '<i class="fas fa-angle-left"></i>',
            next: '<i class="fas fa-angle-right"></i>',
            last: '<i class="fas fa-angle-double-right"></i>'
          }
        },
        order: [[0, 'asc']],
        "drawCallback": function() {
          // Animate rows when table is drawn or redrawn
          $('.row-animation').each(function(i) {
            const $row = $(this);
            $row.css('opacity', 0);
            
            setTimeout(function() {
              $row.animate({
                opacity: 1,
                transform: 'translateY(0)'
              }, {
                duration: 300,
                start: function() {
                  $row.css('transform', 'translateY(0px)');
                }
              });
            }, 50 * i); // Stagger the animations
          });
        }
      });
      
      // Add shimmer effect to search when typing
      $('.dataTables_filter input').on('input', function() {
        $(this).addClass('shimmer');
        setTimeout(() => {
          $(this).removeClass('shimmer');
        }, 500);
      });
      
      // Reset Sessions Button with animation
      $('#resetButton').on('click', function() {
        $(this).addClass('animate-pulse');
        
        Swal.fire({
          title: 'Reset All Sessions?',
          text: "This will reset the session count for all students. This action cannot be undone!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#0284c7',
          cancelButtonColor: '#6b7280',
          confirmButtonText: 'Yes, reset all',
          showClass: {
            popup: 'animate__animated animate__fadeInDown'
          },
          hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
          }
        }).then((result) => {
          $(this).removeClass('animate-pulse');
          
          if (result.isConfirmed) {
            // Implement your reset logic here
            Swal.fire({
              title: 'Reset Complete!',
              text: 'All student sessions have been reset successfully.',
              icon: 'success',
              showClass: {
                popup: 'animate__animated animate__fadeInDown'
              }
            });
            
            // Reset all counters
            $('.counter, .counter-decimal').text('0');
            setTimeout(animateCounter, 500);
          }
        });
      });
      
      // Delete confirmation with SweetAlert and animations
      $('.delete-form').on('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        Swal.fire({
          title: 'Delete Student?',
          text: "You are about to delete this student. This action cannot be undone!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#ef4444',
          cancelButtonColor: '#6b7280',
          confirmButtonText: 'Yes, delete',
          showClass: {
            popup: 'animate__animated animate__zoomIn'
          },
          hideClass: {
            popup: 'animate__animated animate__zoomOut'
          }
        }).then((result) => {
          if (result.isConfirmed) {
            // Animate row removal before actual submission
            const row = $(form).closest('tr');
            row.animate({
              opacity: 0,
              height: 0,
              padding: 0
            }, 400, function() {
              form.submit();
            });
          }
        });
      });
      
      // View Details Button with animated modal
      $('.view-details').on('click', function() {
        const studentId = $(this).data('id');
        
        $(this).addClass('animate-spin');
        setTimeout(() => {
          $(this).removeClass('animate-spin');
        }, 500);
        
        Swal.fire({
          title: 'Student Details',
          html: `
            <div class="text-left">
              <p class="mb-2"><strong>Student ID:</strong> ${studentId}</p>
              <p class="mb-2"><strong>Email:</strong> student${studentId}@example.com</p>
              <p class="mb-2"><strong>Phone:</strong> (123) 456-7890</p>
              <p class="mb-2"><strong>Address:</strong> 123 University Ave, Campus City</p>
              <p class="mb-2"><strong>Last Session:</strong> March 20, 2025</p>
            </div>
          `,
          confirmButtonColor: '#0284c7',
          confirmButtonText: 'Close',
          showClass: {
            popup: 'animate__animated animate__fadeIn'
          },
          hideClass: {
            popup: 'animate__animated animate__fadeOut'
          }
        });
      });
    });
  </script>
</body>

</html>