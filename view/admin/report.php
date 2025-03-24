<?php
include '../../includes/navbar_admin.php';

// Handle form submission for filtering and resetting
$sql = [];
if (isset($_POST["dateSubmit"]) && !empty($_POST["date"])) {
    $date = $_POST["date"];
    $sql = get_date_report(filter_date($date));
} else if (isset($_POST['resetSubmit'])) {
    $sql = get_date_report(reset_date());
} else {
    $sql = get_date_report(reset_date());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Report | Admin Dashboard</title>

    <!-- External CSS & JS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.datatables.net/2.0.2/css/dataTables.tailwind.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0f9ff', 100: '#e0f2fe', 200: '#bae6fd', 300: '#7dd3fc',
                            400: '#38bdf8', 500: '#0ea5e9', 600: '#0284c7', 700: '#0369a1',
                            800: '#075985', 900: '#0c4a6e', 950: '#082f49'
                        },
                        secondary: {
                            50: '#f5f3ff', 100: '#ede9fe', 200: '#ddd6fe', 300: '#c4b5fd',
                            400: '#a78bfa', 500: '#8b5cf6', 600: '#7c3aed', 700: '#6d28d9',
                            800: '#5b21b6', 900: '#4c1d95', 950: '#2e1065'
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'scale-in': 'scaleIn 0.4s ease-out',
                        'pulse-slow': 'pulse 3s infinite',
                    },
                    keyframes: {
                        fadeIn: { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                        slideUp: { 
                            '0%': { transform: 'translateY(30px)', opacity: '0' }, 
                            '100%': { transform: 'translateY(0)', opacity: '1' } 
                        },
                        scaleIn: {
                            '0%': { transform: 'scale(0.95)', opacity: '0' },
                            '100%': { transform: 'scale(1)', opacity: '1' }
                        }
                    },
                    boxShadow: {
                        'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
                        'card': '0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.01)',
                        'button': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                        'inner-highlight': 'inset 0 1px 0 0 rgba(255, 255, 255, 0.1)'
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            background-image: linear-gradient(135deg, #f0f9ff 0%, #f8fafc 100%);
            background-attachment: fixed;
        }
        
        /* DataTables styling */
        .dataTables_wrapper .dataTables_length, 
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            margin: 1rem 0;
            color: #374151;
            font-size: 0.95rem;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.35rem 0.85rem;
            margin: 0 0.25rem;
            border-radius: 0.375rem;
            border: 1px solid #e5e7eb;
            background-color: white;
            transition: all 0.2s;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #e0f2fe;
            border-color: #0ea5e9;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transform: translateY(-1px);
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #0284c7;
            color: white !important;
            border-color: #0284c7;
            font-weight: 500;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background-color: #0369a1;
        }
        
        /* Table styling */
        table.dataTable thead th {
            position: relative;
            font-weight: 600;
            letter-spacing: 0.025em;
        }
        
        table.dataTable tbody tr:nth-child(odd) {
            background-color: rgba(240, 249, 255, 0.5);
        }
        
        /* Placeholder pulse animation */
        .placeholder-pulse {
            animation: placeholderPulse 1.5s infinite;
        }
        
        @keyframes placeholderPulse {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 0.8; }
        }
        
        /* Row animations */
        .staggered-animation > tr {
            opacity: 0;
            animation: fadeInRow 0.5s ease forwards;
        }
        
        @keyframes fadeInRow {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Button effects */
        .btn-effect {
            position: relative;
            overflow: hidden;
            transform: translateY(0);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .btn-effect:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px -3px rgba(0, 0, 0, 0.1);
        }
        
        .btn-effect:active {
            transform: translateY(0);
        }
        
        .btn-effect:after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 10px;
            height: 10px;
            background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
            transform: translate(-50%, -50%) scale(0);
            opacity: 0;
            transition: transform 0.5s, opacity 0.5s;
        }
        
        .btn-effect:active:after {
            transform: translate(-50%, -50%) scale(20);
            opacity: 1;
            transition: transform 0.3s, opacity 0.3s;
        }
        
        /* Form input focus effect */
        .focus-effect {
            transition: all 0.3s;
        }
        
        .focus-effect:focus {
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.2);
            border-color: #38bdf8;
        }
    </style>
</head>

<body class="min-h-screen font-sans text-gray-800">
    <div class="container mx-auto px-4 py-10 max-w-7xl">
        <div class="flex items-center justify-center mb-10 animate-fade-in">
            <div class="bg-gradient-to-r from-primary-600 to-primary-800 rounded-xl py-2 px-4 shadow-lg">
                <h1 class="text-3xl font-bold text-white flex items-center">
                    <i class="fas fa-chart-line mr-3"></i>
                    <span>Analytics Dashboard</span>
                </h1>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-card p-8 mb-8 animate-slide-up backdrop-blur-sm bg-opacity-95">
            <!-- Action Cards Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <!-- Filter Card -->
                <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-lg p-5 shadow-soft border border-primary-200">
                    <h2 class="text-lg font-semibold text-primary-800 mb-3 flex items-center">
                        <i class="fas fa-filter text-primary-600 mr-2"></i>Filter Data
                    </h2>
                    
                    <form action="report.php" method="POST" class="space-y-3">
                        <div class="relative">
                            <label for="dateFilter" class="block text-sm font-medium text-gray-700 mb-1">Select Date:</label>
                            <div class="relative">
                                <input 
                                    id="dateFilter"
                                    type="date" 
                                    name="date" 
                                    class="w-full px-4 py-2.5 pr-10 border border-gray-300 rounded-lg focus-effect bg-white" 
                                    required 
                                />
                                <i class="fas fa-calendar-alt absolute right-3 top-1/2 transform -translate-y-1/2 text-primary-500"></i>
                            </div>
                        </div>
                        
                        <div class="flex space-x-2 pt-2">
                            <button 
                                type="submit" 
                                name="dateSubmit" 
                                class="flex-1 px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-all duration-300 flex items-center justify-center btn-effect shadow-button"
                            >
                                <i class="fas fa-search mr-2"></i> Apply Filter
                            </button>
                            
                            <button 
                                type="submit" 
                                name="resetSubmit" 
                                class="px-4 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-all duration-300 flex items-center justify-center btn-effect shadow-button"
                            >
                                <i class="fas fa-undo"></i>
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Stats Card 1 -->
                <div class="bg-gradient-to-br from-secondary-50 to-secondary-100 rounded-lg p-5 shadow-soft border border-secondary-200">
                    <h2 class="text-lg font-semibold text-secondary-800 mb-3 flex items-center">
                        <i class="fas fa-users text-secondary-600 mr-2"></i>Total Entries
                    </h2>
                    <div class="flex items-center justify-between">
                        <div class="text-3xl font-bold text-secondary-900">
                            <?php echo count($sql); ?>
                        </div>
                        <div class="bg-secondary-200 p-3 rounded-full text-secondary-600">
                            <i class="fas fa-chart-bar text-xl"></i>
                        </div>
                    </div>
                    <div class="text-sm text-secondary-600 mt-3">
                        <i class="fas fa-clock mr-1"></i> Based on your selected filters
                    </div>
                </div>
                
                <!-- Export Card -->
                <div class="bg-gradient-to-br from-green-50 to-emerald-100 rounded-lg p-5 shadow-soft border border-emerald-200">
                    <h2 class="text-lg font-semibold text-emerald-800 mb-3 flex items-center">
                        <i class="fas fa-file-export text-emerald-600 mr-2"></i>Export Options
                    </h2>
                    <p class="text-sm text-emerald-700 mb-3">Export your filtered data in various formats</p>
                    <div class="grid grid-cols-3 gap-2 pt-1">
                        <button id="btnExportCsv" class="px-2 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-all duration-300 flex items-center justify-center btn-effect shadow-button text-sm">
                            <i class="fas fa-file-csv"></i>
                        </button>
                        <button id="btnExportExcel" class="px-2 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-all duration-300 flex items-center justify-center btn-effect shadow-button text-sm">
                            <i class="fas fa-file-excel"></i>
                        </button>
                        <button id="btnExportPdf" class="px-2 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-all duration-300 flex items-center justify-center btn-effect shadow-button text-sm">
                            <i class="fas fa-file-pdf"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Table Section -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-soft overflow-hidden animate-scale-in" style="animation-delay: 0.2s">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-table text-primary-600 mr-2"></i>Attendance Records
                </h2>
                
                <div class="overflow-x-auto rounded-lg">
                    <table id="reportTable" class="w-full text-sm text-left text-gray-700 border-collapse">
                        <thead>
                            <tr class="bg-gradient-to-r from-primary-600 to-primary-700 text-white">
                                <th class="px-6 py-3.5 text-center font-semibold">ID Number</th>
                                <th class="px-6 py-3.5 text-left font-semibold">Name</th>
                                <th class="px-6 py-3.5 text-left font-semibold">Purpose</th>
                                <th class="px-6 py-3.5 text-center font-semibold">Laboratory</th>
                                <th class="px-6 py-3.5 text-center font-semibold">Login Time</th>
                                <th class="px-6 py-3.5 text-center font-semibold">Logout Time</th>
                                <th class="px-6 py-3.5 text-center font-semibold">Date</th>
                            </tr>
                        </thead>
                        <tbody class="staggered-animation">
                            <?php if (!empty($sql)) : ?>
                                <?php foreach ($sql as $person) : ?>
                                    <tr class="border-b hover:bg-primary-50 transition-colors duration-200 group">
                                        <td class="px-6 py-4 text-center group-hover:font-medium"><?php echo htmlspecialchars($person['id_number'] ?? ''); ?></td>
                                        <td class="px-6 py-4 font-medium text-primary-800"><?php echo htmlspecialchars(($person['firstName'] ?? '') . " " . ($person['lastName'] ?? '')); ?></td>
                                        <td class="px-6 py-4"><?php echo htmlspecialchars($person['sit_purpose'] ?? ''); ?></td>
                                        <td class="px-6 py-4 text-center"><?php echo htmlspecialchars($person['sit_lab'] ?? ''); ?></td>
                                        <td class="px-6 py-4 text-center"><?php echo htmlspecialchars($person['sit_login'] ?? ''); ?></td>
                                        <td class="px-6 py-4 text-center"><?php echo htmlspecialchars($person['sit_logout'] ?? ''); ?></td>
                                        <td class="px-6 py-4 text-center"><?php echo htmlspecialchars($person['sit_date'] ?? ''); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center">
                                        <div class="flex flex-col items-center text-gray-500 placeholder-pulse">
                                            <i class="fas fa-database text-4xl mb-3 text-gray-300"></i>
                                            <p class="text-lg">No records found</p>
                                            <p class="text-sm mt-1">Try adjusting your filters or adding new data</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4 text-sm text-gray-500 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-primary-500"></i> 
                    <p>Showing attendance records based on selected date. Use the filter options to refine your search.</p>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-10 opacity-0 animate-fade-in" style="animation-delay: 1s; animation-fill-mode: forwards;">
            <div class="inline-block px-6 py-2 bg-gray-800 bg-opacity-80 rounded-full shadow-lg text-white text-sm">
                <p>© <?php echo date("Y"); ?> Laboratory Management System. All rights reserved.</p>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.tailwind.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function() {
            // Apply row animations dynamically
            $('#reportTable tbody tr').each(function(index) {
                $(this).css('animation-delay', (index * 0.05) + 's');
            });
            
            // Initialize DataTable with export features
            const dataTable = $('#reportTable').DataTable({
                paging: true,
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                dom: "<'flex flex-col md:flex-row justify-between items-start md:items-center mb-4'<'flex-1 mb-2 md:mb-0'l><'flex-1 md:text-right'f>>" +
                     "<'overflow-x-auto'tr>" +
                     "<'flex flex-col md:flex-row justify-between items-center mt-4'<'mb-2 md:mb-0'i><'flex-none'p>>",
                responsive: true,
                buttons: [
                    {
                        extend: 'copy',
                        className: 'hidden'
                    },
                    {
                        extend: 'csv',
                        className: 'hidden',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'excel',
                        className: 'hidden',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdf',
                        className: 'hidden',
                        exportOptions: {
                            columns: ':visible'
                        },
                        customize: function(doc) {
                            doc.pageMargins = [20, 30, 20, 30];
                            doc.defaultStyle.fontSize = 10;
                            doc.styles.tableHeader.fontSize = 11;
                            doc.styles.tableHeader.alignment = 'center';
                            doc.content[1].table.widths = Array(doc.content[1].table.body[0].length).fill('*');
                            
                            // Add header
                            doc.content.splice(0, 0, {
                                margin: [0, 0, 0, 12],
                                alignment: 'center',
                                text: 'Laboratory Attendance Report',
                                style: {
                                    fontSize: 16,
                                    bold: true,
                                    color: '#0284c7'
                                }
                            });
                            
                            // Add footer
                            doc.footer = function(currentPage, pageCount) {
                                return {
                                    margin: [20, 0],
                                    columns: [
                                        {
                                            text: 'Generated on: ' + new Date().toLocaleDateString(),
                                            alignment: 'left',
                                            fontSize: 8,
                                            color: '#666'
                                        },
                                        {
                                            text: 'Page ' + currentPage.toString() + ' of ' + pageCount,
                                            alignment: 'right',
                                            fontSize: 8,
                                            color: '#666'
                                        }
                                    ]
                                };
                            };
                        }
                    },
                    {
                        extend: 'print',
                        className: 'hidden'
                    }
                ],
                language: {
                    search: '<i class="fas fa-search mr-2 text-primary-500"></i>',
                    lengthMenu: '<i class="fas fa-list mr-2 text-primary-500"></i> _MENU_ per page',
                    info: "Showing _START_ to _END_ of _TOTAL_ records",
                    paginate: {
                        first: '<i class="fas fa-angle-double-left"></i>',
                        last: '<i class="fas fa-angle-double-right"></i>',
                        previous: '<i class="fas fa-angle-left"></i>',
                        next: '<i class="fas fa-angle-right"></i>'
                    },
                    emptyTable: '<div class="text-center py-4"><i class="fas fa-file-alt text-4xl text-gray-300 mb-3"></i><p>No data available in table</p></div>'
                },
                drawCallback: function() {
                    $('.paginate_button').addClass('btn-effect');
                }
            });
            
            // Connect custom export buttons to DataTables buttons
            $('#btnExportCsv').on('click', function() {
                $('.buttons-csv').click();
            });
            
            $('#btnExportExcel').on('click', function() {
                $('.buttons-excel').click();
            });
            
            $('#btnExportPdf').on('click', function() {
                $('.buttons-pdf').click();
            });
            
            // Form input enhancement
            $('input[name="date"]').on('change', function() {
                if ($(this).val()) {
                    $(this).addClass('border-primary-400');
                } else {
                    $(this).removeClass('border-primary-400');
                }
            });
            
            // Add ripple effect to buttons
            $('.btn-effect').on('mousedown', function(e) {
                const button = $(this);
                const x = e.pageX - button.offset().left;
                const y = e.pageY - button.offset().top;
                
                $('<span></span>').appendTo(button).css({
                    left: x + 'px',
                    top: y + 'px'
                });
                
                setTimeout(function() {
                    button.find('span').remove();
                }, 500);
            });
            
            // Tooltip initialization (if needed)
            // Add table row hover effect
            $('#reportTable tbody tr').hover(
                function() {
                    $(this).addClass('bg-primary-50');
                },
                function() {
                    $(this).removeClass('bg-primary-50');
                }
            );
        });
    </script>
</body>
</html>