<?php
include '../../includes/navbar_admin.php';

// Include required backend files
require_once '../../backend/backend_admin.php'; 
require_once '../../backend/database_connection.php';

$listPerson = retrieve_current_sit_in();

// Format time function to convert database timestamps to hours:minutes AM/PM
function formatTime($timeString) {
  if (empty($timeString) || $timeString == 'N/A') {
    return $timeString;
  }
  
  // Parse the timestamp
  $timestamp = strtotime($timeString);
  
  // Format to hours and minutes with AM/PM
  return date('h:i A', $timestamp);
}

// Format date function to ensure two-digit day format
function formatDate($dateString) {
  if (empty($dateString)) {
    return '';
  }
  
  // Parse the date
  $timestamp = strtotime($dateString);
  
  // Format to ensure two-digit day
  return date('m/d/Y', $timestamp);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sit In Records</title>
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
  <!-- Animate.css -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  
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
          }
        }
      }
    }
  </script>
  
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
    
    .stat-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    .stat-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 0.75rem;
        box-shadow: 0 0 0 2px rgba(14, 165, 233, 0);
        transition: box-shadow 0.3s ease;
        pointer-events: none;
    }
    .stat-card:hover::after {
        box-shadow: 0 0 0 2px rgba(14, 165, 233, 0.3);
    }
    .stat-card .icon-wrapper {
        transition: transform 0.5s ease;
    }
    .stat-card:hover .icon-wrapper {
        transform: scale(1.1) rotate(5deg);
    }
    
    .animate-fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }
    .animate-slide-in-right {
        animation: slideInRight 0.5s ease-in-out;
    }
    .animate-slide-in-left {
        animation: slideInLeft 0.5s ease-in-out;
    }
    .animate-slide-in-up {
        animation: slideInUp 0.5s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(30px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-30px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes slideInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .stagger-item {
        opacity: 0;
    }
    
    .hover-scale {
        transition: transform 0.3s ease;
    }
    .hover-scale:hover {
        transform: scale(1.02);
    }
    
    /* DataTables customization - similar to history.php */
    .dataTables_wrapper {
        background-color: white;
        border-bottom-left-radius: 0.75rem;
        border-bottom-right-radius: 0.75rem;
        overflow: hidden;
        padding-bottom: 1rem;
    }
    
    /* Search bar styling - reduced padding */
    .dataTables_filter {
        margin-bottom: 0;
        padding: 0.5rem 1.5rem;
    }
    
    /* More compact filter layout */
    .dataTables_filter label {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        font-size: 0.875rem;
        color: #4b5563;
        font-weight: 500;
    }
    
    /* Adjust top margin for table to remove extra space */
    table.dataTable {
        margin-top: 0 !important;
        margin-bottom: 0 !important;
    }
    
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 0.5rem 0.75rem;
        outline: none;
        transition: box-shadow 0.2s ease, border-color 0.2s ease;
    }
    
    .dataTables_wrapper .dataTables_length select:focus,
    .dataTables_wrapper .dataTables_filter input:focus {
        box-shadow: 0 0 0 2px rgba(14, 165, 233, 0.5);
        border-color: transparent;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background-color: #0284c7 !important;
        color: white !important;
        border: 0 !important;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button:not(.current) {
        color: #374151 !important;
        background-color: #ffffff !important;
        border: 1px solid #d1d5db;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        transition: background-color 0.3s ease;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button:not(.current):hover {
        background-color: #f3f4f6 !important;
        color: #1f2937 !important;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
        background-color: #ffffff !important;
        border-color: #d1d5db !important;
    }
    
    .dataTables_wrapper .dataTables_info {
        font-size: 0.875rem;
        color: #4B5563;
        padding: 0.5rem 0;
    }
    
    /* Modern pagination styling */
    .modern-pagination {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding: 0.5rem 0;
        margin-top: 0.5rem;
    }
    
    .modern-pagination .paginate_button {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 2.25rem;
        height: 2.25rem;
        margin: 0 0.125rem;
        padding: 0 0.5rem;
        border-radius: 0.375rem;
        font-weight: 500;
        font-size: 0.875rem;
        color: #4b5563 !important;
        background: transparent !important;
        border: none !important;
        transition: all 0.2s ease-in-out;
        cursor: pointer;
        overflow: hidden;
    }
    
    .modern-pagination .paginate_button.current {
        background: #0284c7 !important;
        color: white !important;
        font-weight: 600;
        box-shadow: 0 2px 5px rgba(2, 132, 199, 0.3);
    }
    
    .modern-pagination .paginate_button:not(.current):not(.disabled):hover {
        background: rgba(14, 165, 233, 0.1) !important;
        color: #0284c7 !important;
    }
    
    .modern-pagination .paginate_button.disabled {
        opacity: 0.35;
        cursor: not-allowed;
    }
    
    .modern-pagination .ellipsis {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 2.25rem;
        color: #6b7280;
        margin: 0 0.25rem;
        font-weight: 600;
        letter-spacing: 1px;
    }
    
    .modern-pagination .paginate_button.current::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 50%;
        width: 20px;
        height: 3px;
        background: rgba(255, 255, 255, 0.7);
        border-radius: 3px;
        transform: translateX(-50%);
    }
  </style>
</head>

<body class="bg-gray-50 font-sans text-gray-800 opacity-0 transition-opacity duration-500">
  <div class="container mx-auto px-4 py-8 max-w-7xl">
    <!-- Page Header (simplified - removed export/refresh buttons) -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6" data-aos="fade-down">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                    <div class="bg-primary-100 p-2 rounded-lg mr-3 shadow-sm">
                        <i class="fas fa-users text-primary-600"></i>
                    </div>
                    Sit In Records
                </h1>
                <p class="text-gray-500 mt-1 ml-12">Comprehensive view of all laboratory users</p>
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
                        <span class="text-primary-600 font-medium">View Records</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
    
    <!-- Table Card - with history.php style -->
    <div class="max-w-7xl mx-auto bg-white rounded-xl shadow-md border border-gray-100 mb-8 transition duration-300 hover:border-primary-200 hover-scale stagger-item overflow-hidden">
      <div class="p-6">
        <div class="overflow-x-auto">
          <table id="sitInTable" class="w-full table-auto border-collapse text-sm text-gray-700">
            <thead>
              <tr class="bg-primary-600 text-white">
                <th class="px-4 py-3 text-left">Sit-in ID</th>
                <th class="px-4 py-3 text-left">ID Number</th>
                <th class="px-4 py-3 text-left">Name</th>
                <th class="px-4 py-3 text-left">Purpose</th>
                <th class="px-4 py-3 text-left">Laboratory</th>
                <th class="px-4 py-3 text-left">Time-in</th>
                <th class="px-4 py-3 text-left">Time-out</th>
                <th class="px-4 py-3 text-left">Date</th>
                <th class="px-4 py-3 text-center">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <?php if (!empty($listPerson)) : ?>
                <?php foreach ($listPerson as $person) : ?>
                  <tr>
                    <td class="px-4 py-3 font-medium"><?php echo htmlspecialchars($person['sit_id']); ?></td>
                    <td class="px-4 py-3"><?php echo htmlspecialchars($person['id_number']); ?></td>
                    <td class="px-4 py-3"><?php echo htmlspecialchars($person['firstName'] . " " . $person['lastName']); ?></td>
                    <td class="px-4 py-3"><?php echo htmlspecialchars($person['sit_purpose']); ?></td>
                    <td class="px-4 py-3">
                      <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-medium">
                        <?php echo htmlspecialchars($person['sit_lab']); ?>
                      </span>
                    </td>
                    <td class="px-4 py-3">
                      <?php echo formatTime($person['sit_login']); ?>
                    </td>
                    <td class="px-4 py-3">
                      <?php if (empty($person['sit_logout']) || $person['sit_logout'] == 'N/A'): ?>
                        <span class="px-2 py-1 bg-green-50 text-green-700 rounded-full text-xs font-medium">
                          Active
                        </span>
                      <?php else: ?>
                        <?php echo formatTime($person['sit_logout']); ?>
                      <?php endif; ?>
                    </td>
                    <td class="px-4 py-3"><?php echo formatDate($person['sit_date']); ?></td>
                    <td class="px-4 py-3 text-center">
                      <button class="bg-primary-600 hover:bg-primary-700 text-white py-1 px-3 rounded view-details" data-id="<?php echo htmlspecialchars($person['sit_id']); ?>">
                        View
                      </button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="9" class="px-4 py-6 text-center text-gray-500">
                    <p>No records found.</p>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    
    <!-- Footer -->
    <div class="text-center mt-6">
      <p class="text-xs text-gray-500">© <?php echo date("Y"); ?> Sit-in Monitoring System</p>
    </div>
  </div>

  <!-- jQuery (required for DataTables) -->
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- AOS Animation Library -->
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  
  <script>
    $(document).ready(function() {
      // Fade in the body
      setTimeout(() => {
          document.body.style.opacity = "1";
      }, 100);
      
      // Initialize AOS animations
      AOS.init({
        duration: 800,
        once: true
      });
      
      // Initialize DataTable with row animation - copied from report.php
      const table = $('#sitInTable').DataTable({
        responsive: true,
        language: {
          search: "_INPUT_",
          searchPlaceholder: "Search records...",
          paginate: {
            first: '«',
            previous: '‹',
            next: '›',
            last: '»'
          },
          info: "",
          infoEmpty: "",
          infoFiltered: ""
        },
        order: [[0, 'desc']],
        dom: 'rt<"flex justify-end bg-white px-6 py-4 border-t border-gray-100"<"modern-pagination"p>>',
        drawCallback: function() {
          // Animate rows when table is drawn or redrawn
          $('tbody tr').each(function(i) {
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
          
          // Style the ellipsis
          $('.ellipsis').html('•••');
        }
      });
      
      // Add custom styling to search input
      setTimeout(() => {
        // Hide the empty info element that takes up space
        $('.dataTables_info').css('display', 'none');
        
        // Style the ellipsis
        $('.ellipsis').html('•••');
        
        // Add title tooltips for accessibility
        $('.paginate_button.first').attr('title', 'First Page');
        $('.paginate_button.previous').attr('title', 'Previous Page');
        $('.paginate_button.next').attr('title', 'Next Page');
        $('.paginate_button.last').attr('title', 'Last Page');
      }, 100);
      
      // Stagger in elements with class .stagger-item
      const staggerItems = document.querySelectorAll('.stagger-item');
      staggerItems.forEach((item, index) => {
        setTimeout(() => {
            item.style.opacity = "1";
            item.classList.add('animate-slide-in-up');
        }, 300 + (index * 150));
      });
      
      // View Details Button - keep this functionality
      $('.view-details').on('click', function() {
        const recordId = $(this).data('id');
        
        // Find the corresponding row data
        let recordData = null;
        <?php if (!empty($listPerson)): ?>
          <?php foreach ($listPerson as $person): ?>
            if (recordId == <?php echo json_encode($person['sit_id']); ?>) {
              recordData = {
                sit_id: <?php echo json_encode($person['sit_id']); ?>,
                id_number: <?php echo json_encode($person['id_number']); ?>,
                name: <?php echo json_encode($person['firstName'] . ' ' . $person['lastName']); ?>,
                purpose: <?php echo json_encode($person['sit_purpose']); ?>,
                lab: <?php echo json_encode($person['sit_lab']); ?>,
                login: <?php echo json_encode(formatTime($person['sit_login'])); ?>,
                logout: <?php echo json_encode(empty($person['sit_logout']) || $person['sit_logout'] == 'N/A' ? 'Active' : formatTime($person['sit_logout'])); ?>,
                date: <?php echo json_encode(formatDate($person['sit_date'])); ?>
              };
            }
          <?php endforeach; ?>
        <?php endif; ?>
        
        if (recordData) {
          Swal.fire({
            title: 'Sit-in Record Details',
            html: `
              <div class="text-left">
                <div class="grid grid-cols-2 gap-2 mb-4">
                  <div class="bg-blue-50 p-3 rounded-lg">
                    <p class="text-xs text-blue-600 mb-1">ID Number</p>
                    <p class="font-semibold">${recordData.id_number}</p>
                  </div>
                  <div class="bg-blue-50 p-3 rounded-lg">
                    <p class="text-xs text-blue-600 mb-1">Lab</p>
                    <p class="font-semibold">${recordData.lab}</p>
                  </div>
                </div>
                
                <div class="mb-4">
                  <p class="text-xs text-gray-500 mb-1">Student Name</p>
                  <p class="font-semibold">${recordData.name}</p>
                </div>
                
                <div class="bg-gray-50 p-3 rounded-lg mb-4">
                  <p class="text-xs text-gray-500 mb-1">Purpose</p>
                  <p>${recordData.purpose}</p>
                </div>
                
                <div class="grid grid-cols-3 gap-2">
                  <div>
                    <p class="text-xs text-gray-500 mb-1">Time in</p>
                    <p class="font-medium text-blue-600">${recordData.login}</p>
                  </div>
                  <div>
                    <p class="text-xs text-gray-500 mb-1">Time out</p>
                    <p class="font-medium ${recordData.logout === 'Active' ? 'text-green-600' : 'text-blue-600'}">${recordData.logout}</p>
                  </div>
                  <div>
                    <p class="text-xs text-gray-500 mb-1">Date</p>
                    <p class="font-medium">${recordData.date}</p>
                  </div>
                </div>
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
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Record Not Found',
            text: 'The selected record could not be found.',
            confirmButtonColor: '#0284c7'
          });
        }
      });
    });
  </script>
</body>
</html>