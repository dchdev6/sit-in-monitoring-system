<?php
include '../../includes/navbar_admin.php';

$listPerson = retrieve_sit_in();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sit In Records</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.tailwind.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <script>
        // Include your Tailwind config
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
                        spin: {
                            '0%': { transform: 'rotate(0deg)' },
                            '100%': { transform: 'rotate(360deg)' },
                        }
                    },
                    animation: {
                        fadeIn: 'fadeIn 0.5s ease-out',
                        slideUp: 'slideUp 0.5s ease-out',
                        pulse: 'pulse 2s infinite',
                        shimmer: 'shimmer 2s infinite linear',
                        spin: 'spin 1s linear infinite',
                    },
                    boxShadow: {
                        card: '0 2px 5px 0 rgba(0,0,0,0.05), 0 1px 2px 0 rgba(0,0,0,0.07)',
                        'card-hover': '0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05)',
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom styles to complement Tailwind */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
        }
        
        .animate-staggered {
            opacity: 0;
            animation: slideUp 0.5s ease-out forwards;
        }
        
        /* DataTable styling improvements */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #0ea5e9 !important;
            color: white !important;
            border: none !important;
            border-radius: 0.375rem !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled) {
            background-color: #bae6fd !important;
            color: #0c4a6e !important;
            border: none !important;
        }
        
        .dataTables_wrapper .dataTables_length select, 
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 0.375rem !important;
            border-color: #d1d5db !important;
            padding: 0.5rem 0.75rem !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            transition: all 0.2s;
        }
        
        .dataTables_wrapper .dataTables_filter input {
            width: 250px !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23999' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z' /%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: 0.625rem 0.75rem;
            background-size: 1rem;
            padding-left: 2.5rem !important;
        }
        
        .dataTables_wrapper .dataTables_filter input:focus {
            outline: none !important;
            border-color: #0ea5e9 !important;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.2);
        }
        
        table.dataTable {
            border-collapse: separate !important;
            border-spacing: 0 4px !important;
            margin-top: 0 !important;
        }
        
        table.dataTable thead th {
            position: relative;
            background-image: none !important;
            padding-right: 30px !important;
        }
        
        table.dataTable thead th::after {
            position: absolute;
            right: 10px;
            opacity: 0.4;
            font-family: 'Font Awesome 6 Free';
            content: '\f0dc';
            font-weight: 900;
        }
        
        table.dataTable thead th.sorting_asc::after {
            content: '\f0de';
            opacity: 1;
        }
        
        table.dataTable thead th.sorting_desc::after {
            content: '\f0dd';
            opacity: 1;
        }
        
        table.dataTable tbody tr {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
            transition: all 0.2s;
            border-radius: 0.5rem;
            background-color: white;
        }
        
        table.dataTable tbody tr:hover {
            background-color: #f0f9ff !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
            transform: translateY(-2px);
        }
        
        table.dataTable tbody td {
            border-top: none !important;
            border-bottom: none !important;
        }
        
        table.dataTable tbody tr td:first-child {
            border-top-left-radius: 0.5rem;
            border-bottom-left-radius: 0.5rem;
        }
        
        table.dataTable tbody tr td:last-child {
            border-top-right-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
        }
        
        /* Status styling */
        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-weight: 500;
            font-size: 0.75rem;
        }
        
        .status-active {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .status-inactive {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        /* Button styling */
        .logout-btn {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .logout-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .logout-btn:hover::before {
            width: 300px;
            height: 300px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }

        /* Loading spinner */
        .spinner {
            width: 40px;
            height: 40px;
            margin: 0 auto;
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-left-color: #0ea5e9;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans">

    <div class="container mx-auto px-4 max-w-7xl animate-fadeIn py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6" data-aos="fade-down">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                        <div class="bg-primary-100 p-2 rounded-lg mr-3 shadow-sm">
                            <i class="fas fa-desktop text-primary-600"></i>
                        </div>
                        Current Sit-In Records
                    </h1>
                    <p class="text-gray-500 mt-1 ml-12">Monitor active laboratory sessions in real-time</p>
                </div>
                
                <div class="flex items-center space-x-2 mt-4 md:mt-0">
                    <button id="refreshBtn" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300 flex items-center shadow-sm btn-animated">
                        <i class="fas fa-sync-alt mr-2"></i> Refresh Data
                    </button>
                    
                    <a href="javascript:void(0)" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-2 px-4 rounded-lg transition duration-300 flex items-center shadow-sm btn-animated" data-bs-toggle="modal" data-bs-target="#helpModal">
                        <i class="fas fa-question-circle mr-2 text-gray-500"></i> Help
                    </a>
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
                            <span class="text-primary-600 font-medium">Current Sit-In</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Main data table container -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
            <!-- Table loader placeholder -->
            <div id="tableLoader" class="py-12 text-center">
                <div class="spinner"></div>
                <p class="text-gray-500 mt-4">Loading sit-in records...</p>
            </div>
            
            <div id="tableContent" class="overflow-x-auto p-6" style="display: none;">
                <table id="sitInTable" class="w-full">
                    <thead>
                        <tr>
                            <th class="bg-primary-600 text-white p-4 font-semibold text-center">Sit ID</th>
                            <th class="bg-primary-600 text-white p-4 font-semibold text-center">ID Number</th>
                            <th class="bg-primary-600 text-white p-4 font-semibold">Name</th>
                            <th class="bg-primary-600 text-white p-4 font-semibold">Purpose</th>
                            <th class="bg-primary-600 text-white p-4 font-semibold text-center">Lab</th>
                            <th class="bg-primary-600 text-white p-4 font-semibold text-center">Session</th>
                            <th class="bg-primary-600 text-white p-4 font-semibold text-center">Status</th>
                            <th class="bg-primary-600 text-white p-4 font-semibold text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($listPerson as $index => $person) : ?>
                            <tr class="border-b border-gray-200 text-gray-700 table-row-animate">
                                <td class="p-4 text-center font-medium text-primary-600"><?php echo $person['sit_id']; ?></td>
                                <td class="p-4 text-center"><?php echo $person['id_number']; ?></td>
                                <td class="p-4 font-medium">
                                    <?php echo $person['firstName'] . " " . (!empty($person['middleName']) ? $person['middleName'][0] . ". " : "") . $person['lastName']; ?>
                                </td>
                                <td class="p-4"><?php echo $person['sit_purpose']; ?></td>
                                <td class="p-4 text-center">
                                    <span class="bg-primary-50 text-primary-700 px-2 py-1 rounded-md font-medium">
                                        <?php echo $person['sit_lab']; ?>
                                    </span>
                                </td>
                                <td class="p-4 text-center"><?php echo $person['session']; ?></td>
                                <td class="p-4 text-center">
                                    <span class="status-badge <?php echo ($person['status'] === 'Active') ? 'status-active' : 'status-inactive'; ?>">
                                        <i class="fas <?php echo ($person['status'] === 'Active') ? 'fa-circle-check' : 'fa-circle-xmark'; ?> mr-1"></i>
                                        <?php echo $person['status']; ?>
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <form action="../../api/api_admin.php" method="POST" class="inline-block">
                                        <input type="hidden" name="session" value="<?php echo $person['session']; ?>" />
                                        <input type="hidden" name="idNum" value="<?php echo $person['id_number']; ?>" />
                                        <input type="hidden" name="sitLab" value="<?php echo $person['sit_lab']; ?>" />
                                        <input type="hidden" name="sitId" value="<?php echo $person['sit_id']; ?>" />
                                        <button type="submit" name="logout" class="logout-btn bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 flex items-center mx-auto">
                                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($listPerson)) : ?>
                            <tr>
                                <td colspan="8" class="p-8 text-center">
                                    <div class="flex flex-col items-center justify-center py-6">
                                        <i class="fas fa-laptop-code text-gray-300 text-5xl mb-4"></i>
                                        <p class="text-gray-500 text-xl font-medium">No active sit-in sessions</p>
                                        <p class="text-gray-400 mt-2">When students log in to a sit-in session, they will appear here.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Help Modal -->
    <div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-lg shadow-xl border-0">
                <div class="modal-header bg-primary-50 border-b border-primary-100">
                    <h5 class="modal-title text-primary-700 font-semibold" id="helpModalLabel">
                        <i class="fas fa-question-circle mr-2"></i> Sit-In Management Help
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-6">
                    <div class="space-y-4">
                        <div>
                            <h6 class="font-medium text-gray-800"><i class="fas fa-info-circle text-primary-500 mr-2"></i> What is this page?</h6>
                            <p class="text-gray-600 ml-8">This page displays all current sit-in sessions across all computer labs.</p>
                        </div>
                        
                        <div>
                            <h6 class="font-medium text-gray-800"><i class="fas fa-search text-primary-500 mr-2"></i> Using the search</h6>
                            <p class="text-gray-600 ml-8">Use the search box to filter records by any field - name, ID number, lab, etc.</p>
                        </div>
                        
                        <div>
                            <h6 class="font-medium text-gray-800"><i class="fas fa-sign-out-alt text-primary-500 mr-2"></i> Logging out students</h6>
                            <p class="text-gray-600 ml-8">Click the "Logout" button to end a student's sit-in session.</p>
                        </div>
                        
                        <div>
                            <h6 class="font-medium text-gray-800"><i class="fas fa-sync-alt text-primary-500 mr-2"></i> Refreshing data</h6>
                            <p class="text-gray-600 ml-8">Click the "Refresh Data" button to update the table with the latest information.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-gray-50">
                    <button type="button" class="bg-primary-500 hover:bg-primary-600 text-white font-medium py-2 px-4 rounded-md transition duration-300 ease-in-out" data-bs-dismiss="modal">Got it</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.tailwind.min.js"></script>
    <script>
        $(document).ready(function() {
            // Show loading spinner
            $('#tableLoader').show();
            $('#tableContent').hide();
            
            // Simulate loading time (remove this in production)
            setTimeout(function() {
                $('#tableLoader').fadeOut(300, function() {
                    $('#tableContent').fadeIn(300);
                });
                
                // Initialize DataTable with advanced options
                var table = $('#sitInTable').DataTable({
                    responsive: true,
                    pageLength: 10,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    order: [[0, 'desc']], // Sort by sit ID by default
                    language: {
                        search: "",
                        searchPlaceholder: "Search records...",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        infoEmpty: "Showing 0 to 0 of 0 entries",
                        paginate: {
                            first: "<i class='fas fa-angle-double-left'></i>",
                            last: "<i class='fas fa-angle-double-right'></i>",
                            next: "<i class='fas fa-angle-right'></i>",
                            previous: "<i class='fas fa-angle-left'></i>"
                        },
                        emptyTable: "No sit-in records available"
                    },
                    drawCallback: function() {
                        // Apply staggered animation to rows after each draw
                        animateTableRows();
                        
                        // Update summary statistics whenever the table is redrawn (filtered)
                        updateStats();
                    }
                });
                
                // Custom positioning of the length and filter controls
                $(".dataTables_length").addClass("mb-4");
                $(".dataTables_filter").addClass("mb-4");
                
                // Function to update statistics based on visible rows
                function updateStats() {
                    const visibleRows = table.rows({search:'applied'}).data();
                    let labCounts = {};
                    let purposeCounts = {};
                    
                    // Count labs and purposes from visible rows
                    for (let i = 0; i < visibleRows.length; i++) {
                        const lab = $(visibleRows[i][4]).text().trim();
                        const purpose = visibleRows[i][3];
                        
                        if (!labCounts[lab]) labCounts[lab] = 0;
                        labCounts[lab]++;
                        
                        if (!purposeCounts[purpose]) purposeCounts[purpose] = 0;
                        purposeCounts[purpose]++;
                    }
                    
                    // Update displayed stats
                    $('#totalActiveSessions').text(visibleRows.length);
                    
                    const labCount = Object.keys(labCounts).length;
                    $('#labUsage').text(labCount + (labCount === 1 ? ' Lab' : ' Labs'));
                    
                    // Find top purpose
                    let topPurpose = 'None';
                    let maxCount = 0;
                    
                    for (const purpose in purposeCounts) {
                        if (purposeCounts[purpose] > maxCount) {
                            maxCount = purposeCounts[purpose];
                            topPurpose = purpose;
                        }
                    }
                    
                    $('#topPurpose').text(topPurpose !== '' ? topPurpose : 'None');
                }
            }, 800);
            
            // Function to animate table rows with staggered effect
            function animateTableRows() {
                $('.table-row-animate').each(function(index) {
                    const $row = $(this);
                    $row.css('opacity', 0);
                    
                    setTimeout(function() {
                        $row.css('opacity', 1).addClass('animate-staggered');
                    }, index * 50); // 50ms delay between each row
                });
            }
            
            // Enhanced logout button effects
            $(document).on({
                mouseenter: function() {
                    $(this).find('i').addClass('fa-bounce');
                },
                mouseleave: function() {
                    $(this).find('i').removeClass('fa-bounce');
                }
            }, '.logout-btn');
            
            // Refresh button functionality
            $('#refreshBtn').on('click', function() {
                const $btn = $(this);
                const originalText = $btn.html();
                
                // Disable button and show loading state
                $btn.prop('disabled', true)
                   .html('<i class="fas fa-spinner fa-spin mr-2"></i> Refreshing...')
                   .addClass('opacity-75');
                
                // Reload the page after a brief delay
                setTimeout(function() {
                    location.reload();
                }, 500);
            });
        });
    </script>

</body>

</html>