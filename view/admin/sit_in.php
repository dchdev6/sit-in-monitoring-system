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
        /* Custom styles to complement Tailwind */
        .animate-staggered {
            opacity: 0;
            animation: slideUp 0.5s ease-out forwards;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #0ea5e9 !important;
            color: white !important;
            border: none !important;
            border-radius: 0.375rem !important;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #0284c7 !important;
            color: white !important;
            border: none !important;
        }
        
        .dataTables_wrapper .dataTables_length select, 
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 0.375rem !important;
            border-color: #d1d5db !important;
            padding: 0.375rem 0.75rem !important;
        }
        
        .dataTables_wrapper .dataTables_filter input:focus {
            outline: none !important;
            border-color: #0ea5e9 !important;
        }
        
        table.dataTable tbody tr:hover {
            background-color: #f0f9ff !important;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans">

    <h1 class="text-primary-600 text-3xl font-semibold text-center mt-12 mb-8 animate-fadeIn">Current Sit In</h1>
    
    <div class="container mx-auto px-4 max-w-7xl animate-fadeIn">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="overflow-x-auto p-1">
                <table id="example" class="w-full">
                    <thead>
                        <tr>
                            <th class="bg-primary-600 text-white p-4 font-semibold text-center">Sit ID Number</th>
                            <th class="bg-primary-600 text-white p-4 font-semibold text-center">ID Number</th>
                            <th class="bg-primary-600 text-white p-4 font-semibold text-center">Name</th>
                            <th class="bg-primary-600 text-white p-4 font-semibold text-center">Purpose</th>
                            <th class="bg-primary-600 text-white p-4 font-semibold text-center">Sit Lab</th>
                            <th class="bg-primary-600 text-white p-4 font-semibold text-center">Session</th>
                            <th class="bg-primary-600 text-white p-4 font-semibold text-center">Status</th>
                            <th class="bg-primary-600 text-white p-4 font-semibold text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($listPerson as $index => $person) : ?>
                            <tr class="border-b border-gray-200 text-gray-700 table-row-animate">
                                <td class="p-4 text-center"><?php echo $person['sit_id']; ?></td>
                                <td class="p-4 text-center"><?php echo $person['id_number']; ?></td>
                                <td class="p-4"><?php echo $person['firstName'] . " " . $person['middleName'] . ". " . $person['lastName']; ?></td>
                                <td class="p-4"><?php echo $person['sit_purpose']; ?></td>
                                <td class="p-4 text-center"><?php echo $person['sit_lab']; ?></td>
                                <td class="p-4 text-center"><?php echo $person['session']; ?></td>
                                <td class="p-4 text-center"><?php echo $person['status']; ?></td>
                                <td class="p-4 text-center">
                                    <form action="../../api/api_admin.php" method="POST">
                                        <input type="hidden" name="session" value="<?php echo $person['session']; ?>" />
                                        <input type="hidden" name="idNum" value="<?php echo $person['id_number']; ?>" />
                                        <input type="hidden" name="sitLab" value="<?php echo $person['sit_lab']; ?>" />
                                        <input type="hidden" name="sitId" value="<?php echo $person['sit_id']; ?>" />
                                        <button type="submit" name="logout" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                                            Logout
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($listPerson)) : ?>
                            <tr>
                                <td colspan="8" class="p-4 text-center text-gray-500">No data available</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.tailwind.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#example').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                language: {
                    search: "Search records:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "Showing 0 to 0 of 0 entries",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "›",
                        previous: "‹"
                    }
                },
                initComplete: function() {
                    // Apply staggered animation to rows
                    animateTableRows();
                }
            });
            
            // Function to animate table rows with staggered effect
            function animateTableRows() {
                $('.table-row-animate').each(function(index) {
                    var $row = $(this);
                    setTimeout(function() {
                        $row.addClass('animate-staggered');
                    }, index * 50); // 50ms delay between each row
                });
            }
            
            // Button hover effect
            $('.bg-red-600').hover(
                function() { $(this).addClass('animate-pulse'); },
                function() { $(this).removeClass('animate-pulse'); }
            );
        });
    </script>

</body>

</html>