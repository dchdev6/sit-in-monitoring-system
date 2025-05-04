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
    <!-- Animation Library - AOS -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'Segoe UI', 'Tahoma', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0f9ff', 100: '#e0f2fe', 200: '#bae6fd', 300: '#7dd3fc',
                            400: '#38bdf8', 500: '#0ea5e9', 600: '#0284c7', 700: '#0369a1',
                            800: '#075985', 900: '#0c4a6e', 950: '#082f49'
                        }
                    },
                    keyframes: {
                        fadeIn: { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                        slideUp: { 
                            '0%': { transform: 'translateY(20px)', opacity: '0' }, 
                            '100%': { transform: 'translateY(0)', opacity: '1' } 
                        },
                        pulse: {
                            '0%, 100%': { transform: 'scale(1)' },
                            '50%': { transform: 'scale(1.05)' },
                        },
                        shimmer: {
                            '0%': { backgroundPosition: '-1000px 0' },
                            '100%': { backgroundPosition: '1000px 0' },
                        }
                    },
                    animation: {
                        fadeIn: 'fadeIn 0.5s ease-out',
                        slideUp: 'slideUp 0.5s ease-out',
                        pulse: 'pulse 2s infinite',
                        shimmer: 'shimmer 2s infinite linear',
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
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
        
        /* Active page highlight glow */
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
        
        /* Enhanced table styling */
        table.dataTable {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }
        
        table.dataTable thead th {
            background: #0284c7;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1rem;
            text-align: left;
            white-space: nowrap;
            border: none;
        }
        
        table.dataTable tbody tr {
            transition: all 0.3s ease;
            background-color: transparent;
        }
        
        table.dataTable tbody tr:hover {
            background-color: #f3f4f6;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        table.dataTable tbody td {
            padding: 0.75rem 1rem;
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
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6" data-aos="fade-down">
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
                    <button id="refreshBtn" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-300 flex items-center shadow-sm btn-animated">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Refresh
                    </button>
                    
                    <button id="exportBtn" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-2.5 px-4 rounded-lg transition duration-300 flex items-center shadow-sm btn-animated">
                        <i class="fas fa-download mr-2 text-gray-500"></i>
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
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
            <div class="p-6">
                <table id="feedbackTable" class="w-full table-auto border-collapse text-sm text-gray-700">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left">User ID</th>
                            <th class="px-4 py-3 text-left">Lab Room</th>
                            <th class="px-4 py-3 text-left">Date</th>
                            <th class="px-4 py-3 text-left">Feedback</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php if (!empty($feedbackList)) : ?>
                            <?php foreach ($feedbackList as $index => $feedback) : ?>
                                <tr class="row-animation">
                                    <td class="px-4 py-3 font-medium"><?php echo htmlspecialchars($feedback['id_number']); ?></td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-medium">
                                            <?php echo htmlspecialchars($feedback['lab']); ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3"><?php echo htmlspecialchars($feedback['date']); ?></td>
                                    <td class="px-4 py-3">
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
        
        <!-- Footer -->
        <div class="text-center mt-6">
            <p class="text-xs text-gray-500">© <?php echo date("Y"); ?> Sit-in Monitoring System</p>
        </div>
    </div>

    <!-- JS Libraries -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    
    <!-- DataTables Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize AOS animations
            AOS.init({
                duration: 800,
                once: true
            });
            
            // Initialize DataTable with export buttons
            const table = $('#feedbackTable').DataTable({
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search feedback...",
                    paginate: {
                        first: '«',
                        previous: '‹',
                        next: '›',
                        last: '»'
                    },
                    emptyTable: "No feedback reports available",
                    info: "",
                    infoEmpty: "",
                    infoFiltered: ""
                },
                order: [[2, 'desc']], // Order by date column descending
                dom: 'rt<"flex justify-end bg-white px-6 py-4 border-t border-gray-100"<"modern-pagination"p>>',
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
                    
                    // Style the ellipsis
                    $('.ellipsis').html('•••');
                }
            });
            
            // Add shimmer effect to search when typing
            $('.dataTables_filter input').on('input', function() {
                $(this).addClass('shimmer');
                setTimeout(() => {
                    $(this).removeClass('shimmer');
                }, 500);
            });
            
            // Export button functionality with animation
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
            
            // Refresh button functionality with animation
            $('#refreshBtn').on('click', function() {
                const $icon = $(this).find('i');
                $icon.addClass('fa-spin');
                $(this).addClass('animate-pulse');
                
                // Show loading message with SweetAlert2
                Swal.fire({
                    title: 'Refreshing Data',
                    text: 'Getting the latest feedback reports...',
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Simulate refresh with animation
                setTimeout(function() {
                    location.reload();
                }, 800);
            });
        });
    </script>
</body>
</html>