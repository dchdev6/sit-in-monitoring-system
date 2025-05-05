<?php
$listPerson = [];
$data = []; // Initialize $data as empty array
$lab_final = isset($_POST['lab']) ? 'lab_' . $_POST['lab'] : 'lab_524'; // Default lab if not set

// Include necessary files and get data when lab is selected
if (isset($_POST['labSubmit']) && isset($_POST['lab'])) {
    $lab = $_POST['lab'];
    $lab_final = 'lab_' . $lab;
    $data = retrieve_pc($lab_final);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CCS | Reservation Management</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        // Include Tailwind config to match the sit-in page
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
                    },
                    animation: {
                        fadeIn: 'fadeIn 0.5s ease-out',
                        slideUp: 'slideUp 0.5s ease-out',
                        pulse: 'pulse 2s infinite',
                    },
                }
            }
        }
    </script>
    <style>
        /* Custom styles to complement Tailwind */
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .animate-staggered {
            opacity: 0;
            animation: slideUp 0.5s ease-out forwards;
        }
        
        @keyframes slideUp {
            0% { transform: translateY(20px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }
        
        .scrollable-area::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        .scrollable-area::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .scrollable-area::-webkit-scrollbar-thumb {
            background: #0ea5e9;
            border-radius: 10px;
        }
        
        .scrollable-area::-webkit-scrollbar-thumb:hover {
            background: #0284c7;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans">
    <!-- Include navbar -->
    <?php include '../../includes/navbar_admin.php'; ?>

    <!-- Page Header -->
    <div class="container mx-auto px-4 py-4 max-w-7xl">
        <div class="mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6" data-aos="fade-down">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                        <div class="bg-primary-100 p-2 rounded-lg mr-3 shadow-sm">
                            <i class="fas fa-laptop-code text-primary-600"></i>
                        </div>
                        Reservation Management
                    </h1>
                    <p class="text-gray-500 mt-1 ml-12">Manage laboratory reservations and computer availability</p>
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
                            <span class="text-primary-600 font-medium">Reservation Management</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container mx-auto px-4 max-w-7xl">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            <!-- Computer Control Card -->
            <div class="md:col-span-3 animate-staggered" style="animation-delay: 100ms;">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden h-full">
                    <div class="bg-primary-600 text-white p-4 font-semibold text-center">
                        <i class="fas fa-desktop mr-2"></i>
                        <span>Computer Control</span>
                    </div>
                    <div class="p-6">
                        <form action="Reservation.php" method="POST">
                            <div class="mb-4">
                                <label for="lab" class="block text-sm font-medium text-gray-700 mb-2">Laboratory</label>
                                <select name="lab" id="lab" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                                <option value="524">Lab 524</option>
                                <option value="524">Lab 524</option>
                                    <option value="526">Lab 526</option>
                                    <option value="528">Lab 528</option>
                                    <option value="530">Lab 530</option>
                                    <option value="542">Lab 542</option>
                                    <option value="542">Lab 542</option>
                                </select>
                            </div>
                            <button type="submit" name="labSubmit" class="w-full bg-primary-500 hover:bg-primary-600 text-white font-medium py-2 px-4 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-opacity-50">
                                <i class="fas fa-filter mr-2"></i> Apply Filter
                            </button>
                        </form>

                        <form action="Reservation.php" method="POST" class="mt-6">
                            <div class="max-h-64 overflow-y-auto scrollable-area pr-2">
                                <?php foreach ($data as $index => $row) : ?>
                                    <div class="mb-3 p-2 border border-gray-100 rounded-lg hover:bg-primary-50 transition-all table-row-animate" style="animation-delay: <?php echo $index * 50; ?>ms;">
                                        <input type="hidden" name="filter_lab" value="<?php echo $lab_final ?>">
                                        <div class="flex items-center">
                                            <input type="checkbox" id="PC<?php echo $row['pc_id']; ?>" name="pc[]" value="<?php echo $row['pc_id']; ?>" class="w-4 h-4 text-primary-600 bg-gray-100 rounded border-gray-300 focus:ring-primary-500 focus:ring-2">
                                            <label for="PC<?php echo $row['pc_id']; ?>" class="ml-2 text-sm font-medium">
                                                <?php if ($row['lab2'] == '1') : ?>
                                                    <div class="flex items-center">
                                                        <span class="h-2 w-2 bg-green-500 rounded-full mr-2"></span>
                                                        <span class="text-green-600">PC <?php echo $row['pc_id']; ?> (Available)</span>
                                                    </div>
                                                <?php else : ?>
                                                    <div class="flex items-center">
                                                        <span class="h-2 w-2 bg-red-500 rounded-full mr-2"></span>
                                                        <span class="text-red-600">PC <?php echo $row['pc_id']; ?> (Used)</span>
                                                    </div>
                                                <?php endif; ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="flex gap-2 mt-4">
                                <button type="submit" name="submitAvail" class="w-1/2 bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                                    <i class="fas fa-check-circle mr-2"></i> Set Available
                                </button>
                                <button type="submit" name="submitDecline" class="w-1/2 bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                                    <i class="fas fa-times-circle mr-2"></i> Set Used
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Reservation Request Card -->
            <div class="md:col-span-5 animate-staggered" style="animation-delay: 200ms;">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden h-full">
                    <div class="bg-primary-600 text-white p-4 font-semibold text-center">
                        <i class="fas fa-clipboard-list mr-2"></i>
                        <span>Reservation Requests</span>
                    </div>
                    <div class="p-6">
                        <div class="max-h-96 overflow-y-auto scrollable-area pr-2">
                            <?php foreach (retrieve_reservation() as $index => $row) : ?>
                                <div class="p-4 border border-gray-200 rounded-lg mb-4 table-row-animate hover:bg-primary-50 transition-all" style="animation-delay: <?php echo $index * 50; ?>ms;">
                                    <div class="grid grid-cols-2 gap-2 mb-3">
                                        <div>
                                            <p class="text-xs text-gray-500">ID Number</p>
                                            <p class="font-semibold"><?php echo $row['id_number'] ?></p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Date</p>
                                            <p class="font-semibold"><?php echo $row['reservation_date'] ?></p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Time</p>
                                            <p class="font-semibold"><?php echo $row['reservation_time'] ?></p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Laboratory</p>
                                            <p class="font-semibold"><?php echo $row['lab'] ?></p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">PC Number</p>
                                            <p class="font-semibold"><?php echo $row['pc_number'] ?></p>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <p class="text-xs text-gray-500">Purpose</p>
                                        <p class="text-sm"><?php echo $row['purpose'] ?></p>
                                    </div>
                                    <div class="flex gap-2">
                                        <form action="Reservation.php" method="POST" class="flex gap-2 w-full">
                                            <input name="reservation_id" value="<?php echo $row['reservation_id'] ?>" type="hidden">
                                            <input name="pc_number" value="<?php echo $row['pc_number'] ?>" type="hidden">
                                            <input name="lab" value="<?php echo "lab_" . $row['lab'] ?>" type="hidden">
                                            <input name="id_number" value="<?php echo $row['id_number'] ?>" type="hidden">
                                            <button type="button" onclick="confirmAccept(this.form)" class="w-1/2 bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                                                <i class="fas fa-check mr-1"></i> Accept
                                            </button>
                                            <button type="button" onclick="confirmDeny(this.form)" class="w-1/2 bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                                                <i class="fas fa-times mr-1"></i> Deny
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logs Card -->
            <div class="md:col-span-4 animate-staggered" style="animation-delay: 300ms;">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden h-full">
                    <div class="bg-primary-600 text-white p-4 font-semibold text-center">
                        <i class="fas fa-history mr-2"></i>
                        <span>Activity Logs</span>
                    </div>
                    <div class="p-6">
                        <div class="max-h-96 overflow-y-auto scrollable-area pr-2">
                            <?php foreach (retrieve_reservation_logs() as $index => $row) : ?>
                                <div class="p-4 border-l-4 <?php echo ($row['status'] == 'Approve') ? 'border-l-green-500' : 'border-l-red-500'; ?> bg-gray-50 rounded-r-lg mb-4 hover:shadow-md transition-all table-row-animate" style="animation-delay: <?php echo $index * 50; ?>ms;">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="font-semibold"><?php echo $row['id_number'] ?></div>
                                        <div class="px-2 py-1 text-xs font-medium rounded-full <?php echo ($row['status'] == 'Approve') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                            <?php echo $row['status'] ?>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-2 mb-2 text-sm">
                                        <div>
                                            <p class="text-xs text-gray-500">Date</p>
                                            <p><?php echo $row['reservation_date'] ?></p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Time</p>
                                            <p><?php echo $row['reservation_time'] ?></p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Laboratory</p>
                                            <p><?php echo $row['lab'] ?></p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">PC Number</p>
                                            <p><?php echo $row['pc_number'] ?></p>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Purpose</p>
                                        <p class="text-sm"><?php echo $row['purpose'] ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="py-8"></div>

    <!-- JavaScript for Interactions -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Animate table rows with staggered effect
            function animateTableRows() {
                $('.table-row-animate').each(function(index) {
                    var $row = $(this);
                    setTimeout(function() {
                        $row.addClass('animate-staggered');
                    }, index * 50); // 50ms delay between each row
                });
            }
            
            // Initialize animations
            animateTableRows();
            
            // Button hover effects
            $('.transition').hover(
                function() { $(this).addClass('animate-pulse'); },
                function() { $(this).removeClass('animate-pulse'); }
            );
        });
        
        // Function to show loading state with SweetAlert2
        function showLoading(message) {
            Swal.fire({
                title: 'Processing',
                html: message,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
        
        // Function to confirm accept with SweetAlert2
        function confirmAccept(form) {
            Swal.fire({
                title: 'Accept Reservation?',
                text: 'This will approve the reservation request and notify the user.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, accept',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'accept_reservation';
                    input.value = 'true';
                    form.appendChild(input);
                    form.submit();
                    
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Reservation Accepted',
                        showConfirmButton: false,
                        timer: 1500,
                        toast: true
                    });
                }
            });
        }
        
        // Function to confirm deny with SweetAlert2
        function confirmDeny(form) {
            Swal.fire({
                title: 'Deny Reservation?',
                text: 'This will reject the reservation request and notify the user.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, deny',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'deny_reservation';
                    input.value = 'true';
                    form.appendChild(input);
                    form.submit();
                    
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Reservation Denied',
                        showConfirmButton: false,
                        timer: 1500,
                        toast: true
                    });
                }
            });
        }
    </script>
</body>
</html>