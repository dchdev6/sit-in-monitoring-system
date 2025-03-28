<?php
include '../../includes/navbar_admin.php';

// Include backend files
require_once '../../backend/backend_admin.php'; 
require_once '../../backend/database_connection.php';

$listPerson = retrieve_current_sit_in();
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
    
    .status-badge.active {
      background-color: #d1fae5;
      color: #047857;
    }
    
    .status-badge.completed {
      background-color: #e0f2fe;
      color: #0369a1;
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

    /* Hidden button styling */
    .hidden-button {
      position: absolute;
      left: -9999px;
      top: -9999px;
      visibility: hidden;
    }
  </style>
</head>

<body class="bg-gray-50 font-sans text-gray-800">
  <div class="container mx-auto px-4 py-8 max-w-7xl">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6" data-aos="fade-down">
      <div>
        <h1 class="text-2xl font-bold text-gray-800 flex items-center">
          <i class="fas fa-users mr-3 text-primary-600"></i>
          Sit In Records
        </h1>
        <p class="text-gray-500 mt-1">Comprehensive view of all laboratory users</p>
      </div>
      <div class="flex space-x-3 mt-4 md:mt-0">
        <button id="refreshBtn" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-300 flex items-center shadow-sm btn-animated">
          <i class="fas fa-sync-alt mr-2"></i>
          Refresh
        </button>
        
        <button id="excelBtn" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-300 flex items-center shadow-sm btn-animated">
          <i class="fas fa-file-excel mr-2"></i>
          Excel
        </button>
        
        <button id="pdfBtn" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-300 flex items-center shadow-sm btn-animated">
          <i class="fas fa-file-pdf mr-2"></i>
          PDF
        </button>
        
        <button id="csvBtn" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-300 flex items-center shadow-sm btn-animated">
          <i class="fas fa-file-csv mr-2"></i>
          CSV
        </button>
        
        <button id="printBtn" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-300 flex items-center shadow-sm btn-animated">
          <i class="fas fa-print mr-2"></i>
          Print
        </button>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-6">
      <!-- Total Users Card -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 stat-card" data-aos="fade-up" data-aos-delay="100">
        <div class="flex items-center">
          <div class="rounded-full bg-blue-100 p-3 mr-4 icon-container">
            <i class="fas fa-users text-blue-600"></i>
          </div>
          <div>
            <p class="text-sm text-gray-500 font-medium">Total Users</p>
            <p class="text-2xl font-bold counter" data-target="<?php echo count($listPerson); ?>">0</p>
          </div>
        </div>
      </div>
      
      <!-- Lab Utilization Card -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 stat-card" data-aos="fade-up" data-aos-delay="200">
        <div class="flex items-center">
          <div class="rounded-full bg-green-100 p-3 mr-4 icon-container">
            <i class="fas fa-desktop text-green-600"></i>
          </div>
          <div>
            <p class="text-sm text-gray-500 font-medium">Lab Utilization</p>
            <p class="text-2xl font-bold counter" data-target="<?php 
                // Get count of unique labs
                $uniqueLabs = array();
                if (!empty($listPerson)) {
                    foreach ($listPerson as $person) {
                        if (isset($person['sit_lab']) && !in_array($person['sit_lab'], $uniqueLabs)) {
                            $uniqueLabs[] = $person['sit_lab'];
                        }
                    }
                }
                echo count($uniqueLabs);
            ?>">0</p>
          </div>
        </div>
      </div>
      
      <!-- Active Users Card -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 stat-card" data-aos="fade-up" data-aos-delay="300">
        <div class="flex items-center">
          <div class="rounded-full bg-purple-100 p-3 mr-4 icon-container">
            <i class="fas fa-clock text-purple-600"></i>
          </div>
          <div>
            <p class="text-sm text-gray-500 font-medium">Active Users</p>
            <p class="text-2xl font-bold counter" data-target="<?php 
                // Count users with no logout time
                $activeUsers = 0;
                if (!empty($listPerson)) {
                    foreach ($listPerson as $person) {
                        if (empty($person['sit_logout']) || $person['sit_logout'] == 'N/A') {
                            $activeUsers++;
                        }
                    }
                }
                echo $activeUsers;
            ?>">0</p>
          </div>
        </div>
      </div>
      
      <!-- Today's Date Card -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 stat-card" data-aos="fade-up" data-aos-delay="400">
        <div class="flex items-center">
          <div class="rounded-full bg-yellow-100 p-3 mr-4 icon-container">
            <i class="fas fa-calendar-day text-yellow-600"></i>
          </div>
          <div>
            <p class="text-sm text-gray-500 font-medium">Today's Date</p>
            <p class="text-lg font-bold"><?php echo date("M d, Y"); ?></p>
          </div>
        </div>
      </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
      <div class="p-6">
        <table id="sitInTable" class="w-full">
          <thead>
            <tr>
              <th>Sit-in ID</th>
              <th>ID Number</th>
              <th>Name</th>
              <th>Purpose</th>
              <th>Lab</th>
              <th>Login</th>
              <th>Logout</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($listPerson)) : ?>
              <?php foreach ($listPerson as $person) : ?>
                <tr class="row-animation">
                  <td class="font-medium"><?php echo htmlspecialchars($person['sit_id']); ?></td>
                  <td><?php echo htmlspecialchars($person['id_number']); ?></td>
                  <td><?php echo htmlspecialchars($person['firstName'] . " " . $person['lastName']); ?></td>
                  <td><?php echo htmlspecialchars($person['sit_purpose']); ?></td>
                  <td class="text-center">
                    <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-medium">
                      <?php echo htmlspecialchars($person['sit_lab']); ?>
                    </span>
                  </td>
                  <td><?php echo htmlspecialchars($person['sit_login']); ?></td>
                  <td>
                    <?php if (empty($person['sit_logout']) || $person['sit_logout'] == 'N/A'): ?>
                      <span class="status-badge active">Active</span>
                    <?php else: ?>
                      <span class="status-badge completed"><?php echo htmlspecialchars($person['sit_logout']); ?></span>
                    <?php endif; ?>
                  </td>
                  <td><?php echo htmlspecialchars($person['sit_date']); ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
    
    <!-- Footer -->
    <div class="text-center mt-6">
      <p class="text-xs text-gray-500">© <?php echo date("Y"); ?> Sit-in Monitoring System</p>
    </div>
  </div>
  
  <!-- Hidden container for DataTables buttons -->
  <div id="exportButtons" style="display:none;"></div>

  <!-- jQuery (required for DataTables) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- AOS Animation Library -->
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

  <!-- DataTables Buttons JS - SPECIFIC VERSIONS -->
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

  <!-- FileSaver for CSV -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
  <!-- SheetJS for direct Excel export -->
  <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
  
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
      const table = $('#sitInTable').DataTable({
        responsive: true,
        language: {
          search: "_INPUT_",
          searchPlaceholder: "Search records...",
          paginate: {
            first: '<i class="fas fa-angle-double-left"></i>',
            previous: '<i class="fas fa-angle-left"></i>',
            next: '<i class="fas fa-angle-right"></i>',
            last: '<i class="fas fa-angle-double-right"></i>'
          }
        },
        order: [[0, 'desc']],
        dom: '<"flex justify-between items-center mb-4"lf>rt<"flex justify-between items-center mt-4"ip>',
        buttons: [
          {
            extend: 'excel',
            text: 'Excel',
            exportOptions: { columns: ':visible' },
            title: 'Sit-in Records - ' + new Date().toLocaleDateString(),
            className: 'hidden-button'
          },
          {
            extend: 'pdf',
            text: 'PDF',
            exportOptions: { columns: ':visible' },
            title: 'Sit-in Records',
            className: 'hidden-button',
            customize: function(doc) {
              doc.pageMargins = [20, 30, 20, 30];
              doc.defaultStyle.fontSize = 10;
              doc.styles.tableHeader.fontSize = 11;
              doc.styles.tableHeader.alignment = 'left';
              
              // Add header
              doc.content.splice(0, 0, {
                margin: [0, 0, 0, 12],
                alignment: 'center',
                text: 'Sit-in Monitoring System',
                style: { fontSize: 18, bold: true, color: '#0284c7' }
              });
              
              // Add date
              doc.content.splice(1, 0, {
                margin: [0, 0, 0, 12],
                alignment: 'center',
                text: 'Generated on: ' + new Date().toLocaleDateString(),
                style: { fontSize: 10, color: '#666666' }
              });
              
              // Add footer
              doc.footer = function(currentPage, pageCount) {
                return { 
                  text: currentPage.toString() + ' of ' + pageCount,
                  alignment: 'center', fontSize: 8, margin: [0, 10, 0, 0]
                };
              };
            }
          },
          {
            extend: 'print',
            text: 'Print',
            exportOptions: { columns: ':visible' },
            className: 'hidden-button'
          },
          {
            extend: 'csv',
            text: 'CSV',
            exportOptions: { columns: ':visible' },
            title: 'Sit-in Records - ' + new Date().toLocaleDateString(),
            className: 'hidden-button'
          }
        ],
        drawCallback: function() {
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

      // Make sure table is initialized BEFORE setting up the export button
      table.buttons().container().appendTo('#exportButtons');
      
      // Add shimmer effect to search when typing
      $('.dataTables_filter input').on('input', function() {
        $(this).addClass('shimmer');
        setTimeout(() => {
          $(this).removeClass('shimmer');
        }, 500);
      });

      // Debug info
      console.log('Table object:', table);
      console.log('Table buttons object:', table.buttons);

      // Excel export button
      $('#excelBtn').off('click').on('click', function() {
        // Add visual feedback
        $(this).addClass('animate-pulse');
        
        try {
          // Show loading
          Swal.fire({
            title: 'Exporting to Excel',
            text: 'Please wait...',
            timerProgressBar: true,
            didOpen: () => {
              Swal.showLoading();
              
              setTimeout(() => {
                // Manual Excel Export
                const tableData = [];
                const headers = [];
                
                $('#sitInTable thead th').each(function() {
                  headers.push($(this).text());
                });
                tableData.push(headers);
                
                $('#sitInTable tbody tr').each(function() {
                  const rowData = [];
                  $(this).find('td').each(function() {
                    rowData.push($(this).text().trim());
                  });
                  tableData.push(rowData);
                });
                
                // Create workbook
                const wb = XLSX.utils.book_new();
                const ws = XLSX.utils.aoa_to_sheet(tableData);
                XLSX.utils.book_append_sheet(wb, ws, "Sit-in Records");
                
                // Save file
                XLSX.writeFile(wb, 'Sit-in_Records_' + new Date().toLocaleDateString().replace(/\//g, '-') + '.xlsx');
                
                Swal.close();
                Swal.fire({
                  icon: 'success',
                  title: 'Success!',
                  text: 'Excel file has been generated and downloaded.',
                  timer: 2000,
                  showConfirmButton: false
                });
              }, 500); // Short delay to ensure UI responsiveness
            }
          });
        } catch(e) {
          console.error("Excel export failed:", e);
          Swal.fire({
            icon: 'error',
            title: 'Export Failed',
            text: 'Excel export could not be completed. ' + e.message
          });
        } finally {
          setTimeout(() => {
            $(this).removeClass('animate-pulse');
          }, 1000);
        }
      });

      // PDF export button
      $('#pdfBtn').off('click').on('click', function() {
        // Add visual feedback
        $(this).addClass('animate-pulse');
        
        try {
          // Show loading
          Swal.fire({
            title: 'Generating PDF',
            text: 'Please wait...',
            timerProgressBar: true,
            didOpen: () => {
              Swal.showLoading();
              
              setTimeout(() => {
                const tableData = [];
                const headers = [];
                
                $('#sitInTable thead th').each(function() {
                  headers.push($(this).text());
                });
                
                $('#sitInTable tbody tr').each(function() {
                  const rowData = [];
                  $(this).find('td').each(function() {
                    rowData.push($(this).text().trim());
                  });
                  tableData.push(rowData);
                });
                
                // Create PDF document definition
                const docDefinition = {
                  pageMargins: [20, 60, 20, 40],
                  header: {
                    text: 'Sit-in Monitoring System',
                    alignment: 'center',
                    margin: [0, 20, 0, 0],
                    fontSize: 18,
                    bold: true
                  },
                  footer: function(currentPage, pageCount) {
                    return { 
                      text: currentPage.toString() + ' of ' + pageCount,
                      alignment: 'center', 
                      fontSize: 8,
                      margin: [0, 10, 0, 0]
                    };
                  },
                  content: [
                    { text: 'Generated on: ' + new Date().toLocaleDateString(), alignment: 'center', fontSize: 10, margin: [0, 0, 0, 20] },
                    {
                      table: {
                        headerRows: 1,
                        widths: Array(headers.length).fill('*'),
                        body: [headers, ...tableData]
                      },
                      layout: 'lightHorizontalLines'
                    }
                  ]
                };
                
                // Generate PDF
                pdfMake.createPdf(docDefinition).download('Sit-in_Records_' + new Date().toLocaleDateString().replace(/\//g, '-') + '.pdf');
                
                Swal.close();
                Swal.fire({
                  icon: 'success',
                  title: 'Success!',
                  text: 'PDF has been generated and downloaded.',
                  timer: 2000,
                  showConfirmButton: false
                });
              }, 500);
            }
          });
        } catch(e) {
          console.error("PDF export failed:", e);
          Swal.fire({
            icon: 'error',
            title: 'Export Failed',
            text: 'PDF export could not be completed. ' + e.message
          });
        } finally {
          setTimeout(() => {
            $(this).removeClass('animate-pulse');
          }, 1000);
        }
      });

      // CSV export button
      $('#csvBtn').off('click').on('click', function() {
        // Add visual feedback
        $(this).addClass('animate-pulse');
        
        try {
          // Show loading
          Swal.fire({
            title: 'Generating CSV',
            text: 'Please wait...',
            timerProgressBar: true,
            didOpen: () => {
              Swal.showLoading();
              
              setTimeout(() => {
                // Get table data
                const tableData = [];
                const headers = [];
                
                $('#sitInTable thead th').each(function() {
                  headers.push($(this).text());
                });
                
                // Add headers as first row
                tableData.push(headers.join(','));
                
                // Add data rows
                $('#sitInTable tbody tr').each(function() {
                  const rowData = [];
                  $(this).find('td').each(function() {
                    // Escape commas in the cell data
                    let cellData = $(this).text().trim();
                    // If cell contains commas, wrap in quotes
                    if (cellData.includes(',')) {
                      cellData = '"' + cellData + '"';
                    }
                    rowData.push(cellData);
                  });
                  tableData.push(rowData.join(','));
                });
                
                // Join with newlines
                const csvContent = tableData.join('\n');
                
                // Download directly without FileSaver dependency
                const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                const link = document.createElement('a');
                const url = URL.createObjectURL(blob);
                
                link.setAttribute('href', url);
                link.setAttribute('download', 'Sit-in_Records_' + new Date().toLocaleDateString().replace(/\//g, '-') + '.csv');
                link.style.visibility = 'hidden';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                Swal.close();
                Swal.fire({
                  icon: 'success',
                  title: 'Success!',
                  text: 'CSV file has been generated and downloaded.',
                  timer: 2000,
                  showConfirmButton: false
                });
              }, 500);
            }
          });
        } catch(e) {
          console.error("CSV export failed:", e);
          Swal.fire({
            icon: 'error',
            title: 'Export Failed',
            text: 'CSV export could not be completed. ' + e.message
          });
        } finally {
          setTimeout(() => {
            $(this).removeClass('animate-pulse');
          }, 1000);
        }
      });

      // Print button
      $('#printBtn').off('click').on('click', function() {
        // Add visual feedback
        $(this).addClass('animate-pulse');
        
        try {
          let printWindow = window.open('', '_blank');
          let tableHTML = document.getElementById('sitInTable').outerHTML;
          
          printWindow.document.write(`
            <html>
              <head>
                <title>Sit-in Records</title>
                <style>
                  body { font-family: Arial, sans-serif; margin: 20px; }
                  table { border-collapse: collapse; width: 100%; margin-top: 20px; }
                  th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                  th { background-color: #f2f2f2; }
                  h1, h2 { text-align: center; }
                  @media print {
                    table { page-break-inside: auto; }
                    tr { page-break-inside: avoid; page-break-after: auto; }
                    thead { display: table-header-group; }
                  }
                </style>
              </head>
              <body>
                <h1>Sit-in Monitoring System</h1>
                <h2>Sit-in Records - ${new Date().toLocaleDateString()}</h2>
                ${tableHTML}
              </body>
            </html>
          `);
          
          printWindow.document.close();
          printWindow.focus();
          setTimeout(() => {
            printWindow.print();
            printWindow.close();
            $(this).removeClass('animate-pulse');
          }, 1000);
        } catch(e) {
          console.error("Print failed:", e);
          Swal.fire({
            icon: 'error',
            title: 'Print Failed',
            text: 'Print could not be initiated. ' + e.message
          });
          $(this).removeClass('animate-pulse');
        }
      });

      // Refresh button functionality with animation
      $('#refreshBtn').on('click', function() {
        const $icon = $(this).find('i');
        const $button = $(this);
        
        // Disable button to prevent multiple clicks
        $button.prop('disabled', true);
        $icon.addClass('fa-spin');
        $button.addClass('animate-pulse');
        
        // Show loading message with SweetAlert2
        Swal.fire({
          title: 'Refreshing Data',
          text: 'Getting the latest records...',
          timerProgressBar: true,
          didOpen: () => {
            Swal.showLoading();
          },
          allowOutsideClick: false
        });
        
        // Simple refresh after a brief timeout to show the loading animation
        setTimeout(function() {
          window.location.reload();
        }, 1000);
      });
    });
  </script>
</body>
</html>