<?php
include '../../includes/navbar_admin.php';

// Include backend files
require_once '../../backend/backend_admin.php'; 
require_once '../../backend/database_connection.php';

// Get feedback data instead of sit-in data
$feedbackList = view_feedback();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Reports</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
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
            padding: 0.5rem 1rem; /* Remove left padding that was accommodating the icon */
            margin-left: 0.5rem;
            font-size: 0.875rem;
            transition: all 0.2s;
            background-image: none; /* Remove the background image */
        }
        
        .dataTables_filter input:focus {
            outline: none;
            border-color: #0ea5e9;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15);
        }
        
        .dataTables_length select {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem; /* Adjust padding (remove extra right padding) */
            font-size: 0.875rem;
            transition: all 0.2s;
            background-image: none;
            -webkit-appearance: auto; /* Reset to browser default */
            appearance: auto; /* Reset to browser default */
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
            /* Override any potential DataTables internal hover styles */
            background: #f3f4f6 !important;
            background-image: none !important;
            box-shadow: none !important;
        }
        
        /* Additional specificity to override DataTables defaults */
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #f3f4f6 !important;
            color: #111827 !important;
            border-color: #e5e7eb !important;
            background: #f3f4f6 !important;
            background-image: none !important;
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
            height: 0;
            width: 0;
            background-color: transparent;
            transition: none;
        }
        
        table.dataTable thead th:hover::after {
            width: 0;
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
        
        /* Feedback message styling */
        .feedback-message {
            max-height: 80px;
            overflow-y: auto;
            line-height: 1.5;
        }
        
        .feedback-message::-webkit-scrollbar {
            width: 6px;
        }
        
        .feedback-message::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 8px;
        }
        
        .feedback-message::-webkit-scrollbar-thumb {
            background: #c5c5c5;
            border-radius: 8px;
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
        
        /* Shimmer effect */
        .shimmer {
            background: linear-gradient(to right, #f6f7f8 0%, #edeef1 20%, #f6f7f8 40%, #f6f7f8 100%);
            background-size: 1000px 100%;
            animation: shimmer 2s infinite linear;
        }
        
        /* Row animations */
        .row-animation {
            opacity: 0;
            transform: translateY(10px);
        }
    </style>
</head>

<body class="bg-gray-50 font-sans text-gray-800">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                        <div class="bg-primary-100 p-2 rounded-lg mr-3 shadow-sm">
                            <i class="fas fa-comment-alt text-primary-600"></i>
                        </div>
                        Feedback Reports
                    </h1>
                    <p class="text-gray-500 mt-1 ml-12">Review and analyze user feedback submissions</p>
                </div>
                
                <div class="flex space-x-3 mt-4 md:mt-0">
                    <button id="refreshBtn" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-2.5 px-4 rounded-lg transition duration-300 flex items-center shadow-sm btn-animated">
                        <i class="fas fa-sync-alt mr-2 text-gray-500"></i>
                        Refresh Data
                    </button>
                    
                    <button id="exportBtn" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-300 flex items-center shadow-sm btn-animated">
                        <i class="fas fa-download mr-2"></i>
                        Export
                    </button>
                </div>
            </div>
            
            <!-- Breadcrumbs -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm">
                    <li class="inline-flex items-center">
                        <a href="admin.php" class="text-gray-500 hover:text-primary-600 transition-colors inline-flex items-center">
                            <i class="fas fa-home mr-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <span class="text-gray-400 mx-2">/</span>
                            <span class="text-primary-600 font-medium">Feedback Reports</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8 animate__animated animate__fadeInUp animate__faster">
            <div class="border-b border-gray-100 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-comment-dots text-primary-500 mr-2"></i>
                    Feedback Submissions
                </h2>
            </div>
            <div class="p-6">
                <table id="feedbackTable" class="w-full">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Lab Room</th>
                            <th>Date</th>
                            <th>Feedback</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($feedbackList)) : ?>
                            <?php foreach ($feedbackList as $index => $feedback) : ?>
                                <tr class="row-animation">
                                    <td class="font-medium"><?php echo htmlspecialchars($feedback['id_number']); ?></td>
                                    <td>
                                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                            <?php echo htmlspecialchars($feedback['lab']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($feedback['date']); ?></td>
                                    <td>
                                        <div class="feedback-message">
                                            <?php echo htmlspecialchars($feedback['message']); ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center text-gray-500">
                                        <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mb-3">
                                            <i class="fas fa-comment-slash text-gray-400 text-2xl"></i>
                                        </div>
                                        <p class="font-medium">No feedback reports found</p>
                                        <p class="text-sm text-gray-400 mt-1">Feedback submitted by users will appear here</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Quick Help Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8 animate__animated animate__fadeInUp animate__faster">
            <div class="flex items-start">
                <div class="flex-shrink-0 bg-primary-50 rounded-lg p-3 mr-4">
                    <i class="fas fa-lightbulb text-primary-500 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-800 mb-2">Quick Tips</h3>
                    <div class="text-sm text-gray-600 space-y-2">
                        <p>• Use the search box to quickly find feedback by user ID or laboratory</p>
                        <p>• Export reports in Excel or PDF format for your records</p>
                        <p>• Regular review of feedback helps improve laboratory services</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-6">
            <p class="text-xs text-gray-500">© <?php echo date("Y"); ?> Sit-in Monitoring System</p>
        </div>
    </div>

    <!-- JS Libraries -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- DataTables Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.print.min.js"></script>

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
            
            // Initialize DataTable with row animation
            const table = $('#feedbackTable').DataTable({
                responsive: true,
                language: {
                    search: "",
                    searchPlaceholder: "Search feedback...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ feedback reports",
                    paginate: {
                        first: '<i class="fas fa-angle-double-left"></i>',
                        previous: '<i class="fas fa-angle-left"></i>',
                        next: '<i class="fas fa-angle-right"></i>',
                        last: '<i class="fas fa-angle-double-right"></i>'
                    }
                },
                order: [[2, 'desc']], // Order by date column descending
                buttons: [
                    {
                        extend: 'excel',
                        className: 'hidden',
                        exportOptions: { columns: ':visible' },
                        title: 'Feedback Reports - ' + new Date().toLocaleDateString()
                    },
                    {
                        extend: 'pdf',
                        className: 'hidden',
                        exportOptions: { columns: ':visible' },
                        title: 'Feedback Reports',
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
                        className: 'hidden',
                        exportOptions: { columns: ':visible' }
                    }
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
                    
                    // Enhance pagination
                    $('.dataTables_paginate .paginate_button').addClass('hover:shadow-sm');
                    $('.dataTables_paginate .paginate_button.current').css('background-color', '#0284c7').css('border-color', '#0284c7');
                    
                    // Add icons to pagination buttons if not already present
                    if ($('.dataTables_paginate .previous i').length === 0) {
                        $('.dataTables_paginate .previous').html('<i class="fas fa-angle-left"></i>');
                        $('.dataTables_paginate .next').html('<i class="fas fa-angle-right"></i>');
                        $('.dataTables_paginate .first').html('<i class="fas fa-angle-double-left"></i>');
                        $('.dataTables_paginate .last').html('<i class="fas fa-angle-double-right"></i>');
                    }
                },
                initComplete: function() {
                    // Add custom classes to DataTable elements
                    $('.dataTables_filter').addClass('relative');
                    $('.dataTables_filter label').addClass('flex items-center');
                    $('.dataTables_filter input').addClass('focus:border-[#0284c7] focus:ring focus:ring-[#0284c7] focus:ring-opacity-20');
                    $('.dataTables_length select').addClass('focus:border-[#0284c7] focus:ring focus:ring-[#0284c7] focus:ring-opacity-20');
                    
                    // Add icon to search input
                    $('.dataTables_filter label').prepend('<i class="fas fa-search text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>');
                    $('.dataTables_filter input').addClass('pl-10').css('padding-left', '2.5rem');
                }
            });
            
            // Add shimmer effect to search when typing
            $('.dataTables_filter input').on('input', function() {
                $(this).addClass('shimmer');
                setTimeout(() => {
                    $(this).removeClass('shimmer');
                }, 500);
            });
            
            // Refresh button functionality
            $('#refreshBtn').on('click', function() {
                const $icon = $(this).find('i');
                $icon.addClass('fa-spin');
                $(this).addClass('animate-pulse');
                
                // Show loading indicator
                Swal.fire({
                    title: 'Refreshing...',
                    html: 'Updating feedback report data',
                    timer: 1000,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    willClose: () => {
                        $(this).removeClass('animate-pulse');
                        // Reload the page to fetch fresh data
                        window.location.reload();
                    }
                });
            });
            
            // Export button functionality
            $('#exportBtn').on('click', function() {
                $(this).addClass('animate-pulse');
                
                Swal.fire({
                    title: 'Export Options',
                    html: `
                        <div class="grid grid-cols-1 gap-3 mt-4">
                            <button id="btnExcelExport" class="bg-green-100 hover:bg-green-200 text-green-700 font-medium py-3 px-4 rounded-lg transition duration-300 flex items-center justify-center">
                                <i class="fas fa-file-excel mr-2"></i> Export to Excel
                            </button>
                            <button id="btnPdfExport" class="bg-red-100 hover:bg-red-200 text-red-700 font-medium py-3 px-4 rounded-lg transition duration-300 flex items-center justify-center">
                                <i class="fas fa-file-pdf mr-2"></i> Export to PDF
                            </button>
                            <button id="btnPrintExport" class="bg-blue-100 hover:bg-blue-200 text-blue-700 font-medium py-3 px-4 rounded-lg transition duration-300 flex items-center justify-center">
                                <i class="fas fa-print mr-2"></i> Print Table
                            </button>
                        </div>
                    `,
                    showConfirmButton: false,
                    showCloseButton: true,
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    },
                    didOpen: () => {
                        $('#btnExcelExport').on('click', function() {
                            table.button(0).trigger();
                            Swal.close();
                        });
                        
                        $('#btnPdfExport').on('click', function() {
                            table.button(1).trigger();
                            Swal.close();
                        });
                        
                        $('#btnPrintExport').on('click', function() {
                            table.button(2).trigger();
                            Swal.close();
                        });
                    }
                }).then(() => {
                    $(this).removeClass('animate-pulse');
                });
            });
        });
    </script>
</body>
</html>