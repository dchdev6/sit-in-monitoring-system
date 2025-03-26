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
  <!-- Animation Library - Animate.css -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  
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
      background-color: transparent;
      padding: 0.5rem;
    }
    
    .dataTables_filter input {
      border: 1px solid #e5e7eb;
      border-radius: 0.5rem;
      padding: 0.5rem 1rem 0.5rem 2.5rem;
      margin-left: 0.5rem;
      font-size: 0.875rem;
      transition: all 0.2s;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%230ea5e9'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'%3E%3C/path%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: 0.75rem center;
      background-size: 1rem;
    }
    
    .dataTables_filter input:focus {
      outline: none;
      border-color: #0ea5e9;
      box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15);
    }
    
    .dataTables_length select {
      border: 1px solid #e5e7eb;
      border-radius: 0.5rem;
      padding: 0.5rem 2rem 0.5rem 0.75rem;
      font-size: 0.875rem;
      background-position: right 0.5rem center;
      transition: all 0.2s;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%230ea5e9'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-size: 1rem;
      -webkit-appearance: none;
      appearance: none;
    }
    
    .dataTables_length select:focus {
      outline: none;
      border-color: #0ea5e9;
      box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15);
    }
    
    .dataTables_info, .dataTables_length, .dataTables_filter {
      margin-bottom: 1rem;
      font-size: 0.875rem;
      color: #4b5563;
    }
    
    .dataTables_paginate {
      margin-top: 1.5rem;
      display: flex;
      justify-content: center;
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
      background-color: #0ea5e9 !important;
      border-color: #0ea5e9 !important;
      color: white !important;
      font-weight: 500;
      box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
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
      border-radius: 0.5rem;
      overflow: hidden;
    }
    
    table.dataTable thead th {
      background: #f9fafb;
      color: #374151;
      font-weight: 600;
      padding: 1rem;
      text-align: left;
      border-bottom: 2px solid #e5e7eb;
      white-space: nowrap;
      position: relative;
    }
    
    table.dataTable thead th::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      height: 2px;
      width: 0;
      background-color: #0ea5e9;
      transition: width 0.3s ease;
    }
    
    table.dataTable thead th:hover::after {
      width: 100%;
    }
    
    table.dataTable tbody tr {
      transition: all 0.3s ease;
    }
    
    table.dataTable tbody tr:hover {
      background-color: #f0f9ff;
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
      display: inline-flex;
      align-items: center;
      padding: 0.375rem 0.875rem;
      border-radius: 9999px;
      font-size: 0.75rem;
      font-weight: 500;
      text-align: center;
      transition: all 0.3s ease;
      white-space: nowrap;
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
    
    .status-badge i {
      margin-right: 0.375rem;
      font-size: 0.75rem;
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
    
    /* Action buttons */
    .action-btn {
      width: 2.25rem;
      height: 2.25rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 0.5rem;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    
    .action-btn::after {
      content: "";
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      pointer-events: none;
      background-image: radial-gradient(circle, #fff 10%, transparent 10.01%);
      background-repeat: no-repeat;
      background-position: 50%;
      transform: scale(10, 10);
      opacity: 0;
      transition: transform 0.5s, opacity 1s;
    }
    
    .action-btn:active::after {
      transform: scale(0, 0);
      opacity: 0.3;
      transition: 0s;
    }
    
    /* Action button tooltips */
    .tooltip-container {
      position: relative;
    }
    
    .tooltip {
      position: absolute;
      bottom: 100%;
      left: 50%;
      transform: translateX(-50%) translateY(10px);
      background-color: #1f2937;
      color: white;
      padding: 0.5rem 0.75rem;
      border-radius: 0.375rem;
      font-size: 0.75rem;
      white-space: nowrap;
      opacity: 0;
      visibility: hidden;
      transition: all 0.2s ease;
      z-index: 10;
    }
    
    .tooltip::after {
      content: "";
      position: absolute;
      top: 100%;
      left: 50%;
      transform: translateX(-50%);
      border-width: 5px;
      border-style: solid;
      border-color: #1f2937 transparent transparent transparent;
    }
    
    .tooltip-container:hover .tooltip {
      opacity: 1;
      visibility: visible;
      transform: translateX(-50%) translateY(0);
    }
  </style>
</head>

<body class="bg-gray-50 font-sans text-gray-800">
  <div class="container mx-auto px-4 py-8 max-w-7xl">
    <!-- Page Header -->
    <div class="mb-8">
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6" data-aos="fade-down">
        <div>
          <h1 class="text-2xl font-bold text-gray-800 flex items-center">
            <div class="bg-primary-100 p-2 rounded-lg mr-3 shadow-sm">
              <i class="fas fa-user-graduate text-primary-600"></i>
            </div>
            Student Information
          </h1>
          <p class="text-gray-500 mt-1 ml-12">Manage student records and laboratory sessions</p>
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
      
      <!-- Breadcrumbs -->
      <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm">
          <li class="inline-flex items-center">
            <a href="Admin.php" class="text-gray-500 hover:text-primary-600 transition-colors inline-flex items-center">
              <i class="fas fa-home mr-2"></i>
              Dashboard
            </a>
          </li>
          <li>
            <div class="flex items-center">
              <span class="text-gray-400 mx-2">/</span>
              <span class="text-primary-600 font-medium">Students</span>
            </div>
          </li>
        </ol>
      </nav>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 stat-card animate__animated animate__fadeInUp animate__faster" style="animation-delay: 0.1s">
        <div class="flex items-center">
          <div class="rounded-full bg-blue-100 p-3 mr-4 icon-container">
            <i class="fas fa-users text-blue-600"></i>
          </div>
          <div>
            <p class="text-sm text-gray-500 font-medium">Total Students</p>
            <div class="flex items-end">
              <p class="text-2xl font-bold counter" data-target="<?php echo count($listPerson); ?>">0</p>
              <span class="text-xs text-blue-600 ml-1.5 font-medium">students</span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 stat-card animate__animated animate__fadeInUp animate__faster" style="animation-delay: 0.2s">
        <div class="flex items-center">
          <div class="rounded-full bg-green-100 p-3 mr-4 icon-container">
            <i class="fas fa-user-check text-green-600"></i>
          </div>
          <div>
            <p class="text-sm text-gray-500 font-medium">Active Students</p>
            <div class="flex items-end">
              <p class="text-2xl font-bold counter" data-target="<?php 
                  $activeCount = 0;
                  foreach($listPerson as $person) {
                    if($person['session'] > 0) $activeCount++;
                  }
                  echo $activeCount;
                ?>">0</p>
              <span class="text-xs text-green-600 ml-1.5 font-medium">with sessions</span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 stat-card animate__animated animate__fadeInUp animate__faster" style="animation-delay: 0.3s">
        <div class="flex items-center">
          <div class="rounded-full bg-yellow-100 p-3 mr-4 icon-container">
            <i class="fas fa-clock text-yellow-600"></i>
          </div>
          <div>
            <p class="text-sm text-gray-500 font-medium">Avg. Sessions</p>
            <div class="flex items-end">
              <p class="text-2xl font-bold counter-decimal" data-target="<?php 
                  $totalSessions = 0;
                  foreach($listPerson as $person) {
                    $totalSessions += $person['session'];
                  }
                  echo count($listPerson) > 0 ? round($totalSessions / count($listPerson), 1) : 0;
                ?>">0</p>
              <span class="text-xs text-yellow-600 ml-1.5 font-medium">per student</span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 stat-card animate__animated animate__fadeInUp animate__faster" style="animation-delay: 0.4s">
        <div class="flex items-center">
          <div class="rounded-full bg-purple-100 p-3 mr-4 icon-container">
            <i class="fas fa-graduation-cap text-purple-600"></i>
          </div>
          <div>
            <p class="text-sm text-gray-500 font-medium">Programs</p>
            <div class="flex items-end">
              <p class="text-2xl font-bold counter" data-target="<?php 
                  $courses = array();
                  foreach($listPerson as $person) {
                    if(!in_array($person['course'], $courses)) {
                      $courses[] = $person['course'];
                    }
                  }
                  echo count($courses);
                ?>">0</p>
              <span class="text-xs text-purple-600 ml-1.5 font-medium">course types</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8 animate__animated animate__fadeInUp animate__faster" style="animation-delay: 0.5s">
      <div class="border-b border-gray-100 px-6 py-4">
        <h2 class="text-lg font-semibold text-gray-800 flex items-center">
          <i class="fas fa-table text-primary-500 mr-2"></i>
          Student Records
        </h2>
      </div>
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
                </td>
                <td>
                  <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    <?php echo $person['course']; ?>
                  </span>
                </td>
                <td>
                  <?php 
                    $session = $person['session'];
                    $statusClass = '';
                    $statusIcon = '';
                    
                    if($session <= 6) {
                      $statusClass = 'low';
                      $statusIcon = 'fa-exclamation-circle';
                    } else if($session <= 15) {
                      $statusClass = 'medium';
                      $statusIcon = 'fa-clock';
                    } else {
                      $statusClass = 'good';
                      $statusIcon = 'fa-check-circle';
                    }
                  ?>
                  <span class="status-badge <?php echo $statusClass; ?>">
                    <i class="fas <?php echo $statusIcon; ?>"></i>
                    <?php echo $session; ?> sessions
                  </span>
                </td>
                <td>
                  <div class="flex justify-center space-x-2">
                    <div class="tooltip-container">
                      <form action="Admin.php" method="POST" class="inline-block">
                        <input type="hidden" name="idNum" value="<?php echo $person['id_number']; ?>" />
                        <button type="submit" name="edit" class="action-btn bg-primary-50 hover:bg-primary-100 text-primary-600">
                          <i class="fas fa-edit"></i>
                        </button>
                      </form>
                      <div class="tooltip">Edit Student</div>
                    </div>
                    
                    <div class="tooltip-container">
                      <form action="Students.php" method="POST" class="inline-block delete-form">
                        <input type="hidden" name="idNum" value="<?php echo $person['id_number']; ?>" />
                        <button type="submit" name="deleteStudent" class="action-btn bg-red-50 hover:bg-red-100 text-red-600">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </form>
                      <div class="tooltip">Delete Student</div>
                    </div>
                    
                    <div class="tooltip-container">
                      <button class="action-btn bg-gray-50 hover:bg-gray-100 text-gray-700 view-details" data-id="<?php echo $person['id_number']; ?>">
                        <i class="fas fa-eye"></i>
                      </button>
                      <div class="tooltip">View Details</div>
                    </div>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    
    <!-- Quick Help Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8 animate__animated animate__fadeInUp animate__faster" style="animation-delay: 0.6s">
      <div class="flex items-start">
        <div class="flex-shrink-0 bg-primary-50 rounded-lg p-3 mr-4">
          <i class="fas fa-lightbulb text-primary-500 text-xl"></i>
        </div>
        <div>
          <h3 class="text-lg font-medium text-gray-800 mb-2">Quick Tips</h3>
          <div class="text-sm text-gray-600 space-y-2">
            <p>• Use the search box to quickly find students by name or ID number</p>
            <p>• Students with fewer than 3 sessions remaining are marked in yellow or red</p>
            <p>• Reset all sessions at once using the "Reset Sessions" button above</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- jQuery (required for DataTables) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Animate.css JS -->
  <script>
    $(document).ready(function() {
      // Initialize animations for page elements
      function animateElements() {
        $('.animate__animated').each(function(i) {
          $(this).css('opacity', '0');
          
          setTimeout(() => {
            $(this).css('opacity', '1');
          }, i * 100);
        });
      }
      
      animateElements();
      
      // Animate counter numbers
      function animateCounter() {
        $('.counter').each(function() {
          const $this = $(this);
          const target = parseInt($this.attr('data-target'));
          
          $({ Counter: 0 }).animate({
            Counter: target
          }, {
            duration: 1200,
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
            duration: 1200,
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
          search: "",
          searchPlaceholder: "Search students...",
          lengthMenu: "Show _MENU_ entries",
          info: "Showing _START_ to _END_ of _TOTAL_ students",
          paginate: {
            first: '<i class="fas fa-angle-double-left"></i>',
            previous: '<i class="fas fa-angle-left"></i>',
            next: '<i class="fas fa-angle-right"></i>',
            last: '<i class="fas fa-angle-double-right"></i>'
          }
        },
        order: [[0, 'asc']],
        columnDefs: [
          { orderable: false, targets: 5 } // Disable sorting on actions column
        ],
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
        },
        initComplete: function() {
          // Add custom classes to DataTable elements
          $('.dataTables_filter').addClass('relative');
          $('.dataTables_filter label').addClass('flex items-center');
          $('.dataTables_length select').addClass('focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500');
          
          // Reposition search icon
          $('.dataTables_filter input').removeClass('pl-10').addClass('pl-9');
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
          title: '<div class="flex items-center mb-2"><i class="fas fa-sync-alt text-yellow-500 mr-3"></i>Reset All Sessions?</div>',
          html: `
            <div class="text-left">
              <p class="mb-3">This will reset the session count for all students to the default value.</p>
              <div class="bg-yellow-50 text-yellow-800 p-3 rounded-lg text-sm flex items-start">
                <i class="fas fa-exclamation-triangle mr-2 mt-0.5"></i>
                <span>This action cannot be undone. All current session counts will be lost.</span>
              </div>
            </div>
          `,
          showCancelButton: true,
          confirmButtonColor: '#0ea5e9',
          cancelButtonColor: '#6b7280',
          confirmButtonText: '<i class="fas fa-check mr-2"></i>Yes, reset all',
          cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
          showClass: {
            popup: 'animate__animated animate__fadeInDown animate__faster'
          },
          hideClass: {
            popup: 'animate__animated animate__fadeOutUp animate__faster'
          }
        }).then((result) => {
          $(this).removeClass('animate-pulse');
          
          if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
              title: 'Processing...',
              html: 'Resetting session counts for all students',
              timer: 2000,
              timerProgressBar: true,
              didOpen: () => {
                Swal.showLoading();
              },
              showClass: {
                popup: 'animate__animated animate__fadeIn animate__faster'
              }
            }).then(() => {
              // Success state
              Swal.fire({
                icon: 'success',
                title: 'Reset Complete!',
                html: `
                  <div class="text-left">
                    <p class="mb-2">All student sessions have been reset successfully.</p>
                    <div class="bg-green-50 text-green-800 p-3 rounded-lg text-sm flex items-start mt-3">
                      <i class="fas fa-check-circle mr-2 mt-0.5"></i>
                      <span>Session counts have been set to the default value.</span>
                    </div>
                  </div>
                `,
                confirmButtonColor: '#0ea5e9',
                showClass: {
                  popup: 'animate__animated animate__fadeInDown animate__faster'
                }
              });
              
              // Reset all counters and update UI
              $('.counter, .counter-decimal').text('0');
              setTimeout(animateCounter, 500);
              
              // Update status badges
              $('.status-badge').removeClass('low medium').addClass('good');
              $('.status-badge i').removeClass('fa-exclamation-circle fa-clock').addClass('fa-check-circle');
              $('.status-badge').text('30 sessions');
              
              // Redraw table to show updated data
              table.draw();
            });
          }
        });
      });
      
      // Delete confirmation with SweetAlert and animations
      $('.delete-form').on('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const studentId = $(this).find('input[name="idNum"]').val();
        
        Swal.fire({
          title: '<div class="flex items-center text-red-600"><i class="fas fa-exclamation-triangle mr-3"></i>Delete Student?</div>',
          html: `
            <div class="text-left">
              <p class="mb-3">You are about to delete student with ID: <strong>${studentId}</strong></p>
              <div class="bg-red-50 text-red-800 p-3 rounded-lg text-sm flex items-start">
                <i class="fas fa-trash mr-2 mt-0.5"></i>
                <span>This action cannot be undone. All student data and session records will be permanently deleted.</span>
              </div>
            </div>
          `,
          showCancelButton: true,
          confirmButtonColor: '#ef4444',
          cancelButtonColor: '#6b7280',
          confirmButtonText: '<i class="fas fa-trash-alt mr-2"></i>Yes, delete',
          cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
          showClass: {
            popup: 'animate__animated animate__zoomIn animate__faster'
          },
          hideClass: {
            popup: 'animate__animated animate__zoomOut animate__faster'
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
              // Show loading state
              Swal.fire({
                title: 'Deleting...',
                html: `Removing student ${studentId} from the system`,
                timer: 1500,
                timerProgressBar: true,
                didOpen: () => {
                  Swal.showLoading();
                }
              }).then(() => {
                form.submit();
              });
            });
          }
        });
      });
      
      // View Details Button with enhanced modal
      $('.view-details').on('click', function() {
        const studentId = $(this).data('id');
        
        $(this).addClass('animate-spin');
        setTimeout(() => {
          $(this).removeClass('animate-spin');
        }, 500);
        
        Swal.fire({
          title: '<div class="flex items-center justify-center mb-2"><div class="bg-primary-100 p-2 rounded-lg mr-3"><i class="fas fa-user-graduate text-primary-600"></i></div><span>Student Details</span></div>',
          html: `
            <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
              <div class="space-y-3">
                <div class="bg-white rounded-lg p-3 flex items-center shadow-sm">
                  <div class="bg-primary-50 p-2 rounded-lg mr-3">
                    <i class="fas fa-id-card text-primary-500"></i>
                  </div>
                  <div>
                    <div class="text-xs text-gray-500">Student ID</div>
                    <div class="font-medium">${studentId}</div>
                  </div>
                </div>
                
                <div class="bg-white rounded-lg p-3 flex items-center shadow-sm">
                  <div class="bg-primary-50 p-2 rounded-lg mr-3">
                    <i class="fas fa-envelope text-primary-500"></i>
                  </div>
                  <div>
                    <div class="text-xs text-gray-500">Email Address</div>
                    <div class="font-medium">student${studentId}@example.com</div>
                  </div>
                </div>
                
                <div class="bg-white rounded-lg p-3 flex items-center shadow-sm">
                  <div class="bg-primary-50 p-2 rounded-lg mr-3">
                    <i class="fas fa-phone text-primary-500"></i>
                  </div>
                  <div>
                    <div class="text-xs text-gray-500">Phone Number</div>
                    <div class="font-medium">(123) 456-7890</div>
                  </div>
                </div>
                
                <div class="bg-white rounded-lg p-3 flex items-center shadow-sm">
                  <div class="bg-primary-50 p-2 rounded-lg mr-3">
                    <i class="fas fa-map-marker-alt text-primary-500"></i>
                  </div>
                  <div>
                    <div class="text-xs text-gray-500">Address</div>
                    <div class="font-medium">123 University Ave, Campus City</div>
                  </div>
                </div>
                
                <div class="bg-white rounded-lg p-3 flex items-center shadow-sm">
                  <div class="bg-primary-50 p-2 rounded-lg mr-3">
                    <i class="fas fa-calendar-alt text-primary-500"></i>
                  </div>
                  <div>
                    <div class="text-xs text-gray-500">Last Session</div>
                    <div class="font-medium">March 20, 2025</div>
                  </div>
                </div>
              </div>
              
              <div class="mt-4 flex justify-center">
                <button type="button" class="text-primary-600 border border-primary-300 bg-primary-50 hover:bg-primary-100 text-sm font-medium py-2 px-4 rounded-lg transition-all duration-300 flex items-center mr-2" onclick="showEditStudent('${studentId}')">
                  <i class="fas fa-edit mr-2"></i>
                  Edit Student
                </button>
                <button type="button" class="text-red-600 border border-red-300 bg-red-50 hover:bg-red-100 text-sm font-medium py-2 px-4 rounded-lg transition-all duration-300 flex items-center" onclick="showDeleteConfirm('${studentId}')">
                  <i class="fas fa-trash mr-2"></i>
                  Delete
                </button>
              </div>
            </div>
          `,
          showConfirmButton: false,
          showCancelButton: true,
          cancelButtonText: 'Close',
          cancelButtonColor: '#64748b',
          showClass: {
            popup: 'animate__animated animate__fadeInDown animate__faster'
          },
          hideClass: {
            popup: 'animate__animated animate__fadeOutUp animate__faster'
          },
          customClass: {
            container: 'student-details-modal',
            popup: 'rounded-xl border border-gray-100',
            header: 'border-b border-gray-100 pb-3',
            closeButton: 'focus:outline-none focus:shadow-none',
            cancelButton: 'rounded-lg px-4 py-2'
          },
          buttonsStyling: true,
          footer: `<div class="text-xs text-gray-400 text-center w-full pb-2">Last updated: ${new Date().toLocaleDateString()}</div>`
        });
      });
    });

    // Helper functions for the modal actions
    function showEditStudent(studentId) {
      // Submit form to navigate to edit page
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = 'Admin.php';
      
      const idInput = document.createElement('input');
      idInput.type = 'hidden';
      idInput.name = 'idNum';
      idInput.value = studentId;
      
      const editInput = document.createElement('input');
      editInput.type = 'hidden';
      editInput.name = 'edit';
      editInput.value = '1';
      
      form.appendChild(idInput);
      form.appendChild(editInput);
      document.body.appendChild(form);
      form.submit();
    }

    function showDeleteConfirm(studentId) {
      Swal.fire({
        title: '<div class="flex items-center text-red-600"><i class="fas fa-exclamation-triangle mr-3"></i>Delete Student?</div>',
        html: `
          <div class="text-left">
            <p class="mb-3">You are about to delete student with ID: <strong>${studentId}</strong></p>
            <div class="bg-red-50 text-red-800 p-3 rounded-lg text-sm flex items-start">
              <i class="fas fa-trash mr-2 mt-0.5"></i>
              <span>This action cannot be undone. All student data and session records will be permanently deleted.</span>
            </div>
          </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<i class="fas fa-trash-alt mr-2"></i>Yes, delete',
        cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
        showClass: {
          popup: 'animate__animated animate__zoomIn animate__faster'
        },
        hideClass: {
          popup: 'animate__animated animate__zoomOut animate__faster'
        }
      }).then((result) => {
        if (result.isConfirmed) {
          // Create and submit the delete form
          const form = document.createElement('form');
          form.method = 'POST';
          form.action = 'Students.php';
          
          const idInput = document.createElement('input');
          idInput.type = 'hidden';
          idInput.name = 'idNum';
          idInput.value = studentId;
          
          const deleteInput = document.createElement('input');
          deleteInput.type = 'hidden';
          deleteInput.name = 'deleteStudent';
          deleteInput.value = '1';
          
          form.appendChild(idInput);
          form.appendChild(deleteInput);
          document.body.appendChild(form);
          
          // Show deletion animation
          Swal.fire({
            title: 'Deleting...',
            html: 'Removing student from the system',
            timer: 1500,
            timerProgressBar: true,
            didOpen: () => {
              Swal.showLoading();
            }
          }).then(() => {
            form.submit();
          });
        }
      });
    }
    
    // Success message if coming back from a successful operation
    <?php if (isset($_GET['success']) && $_GET['success'] == 'delete'): ?>
    Swal.fire({
      icon: 'success',
      title: 'Student Deleted',
      text: 'The student has been successfully removed from the system.',
      confirmButtonColor: '#0ea5e9',
      showClass: {
        popup: 'animate__animated animate__fadeInDown animate__faster'
      }
    });
    <?php endif; ?>
  </script>
</body>
</html>