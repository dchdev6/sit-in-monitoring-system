<?php
// Buffer output to prevent "headers already sent" errors
ob_start();

include '../../includes/navbar_admin.php';

// Define upload directory
$upload_dir = '../../uploads/resources/';

// Process file upload
if (isset($_POST['upload_resource']) && isset($_FILES['resource_file'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $posted_by = $_SESSION['admin_id_number']; // Admin ID
    
    $file = $_FILES['resource_file'];
    $file_name = basename($file['name']);
    $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    // Generate unique filename
    $new_filename = uniqid() . '.' . $file_type;
    $file_path = $upload_dir . $new_filename;
    
    // Allow certain file types
    $allowed_types = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'zip', 'txt'];
    
    if (!in_array($file_type, $allowed_types)) {
        $_SESSION['error_message'] = "Sorry, only PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, ZIP, and TXT files are allowed.";
    } elseif ($file['size'] > 10000000) { // 10MB max
        $_SESSION['error_message'] = "Sorry, your file is too large. Maximum size is 10MB.";
    } elseif (move_uploaded_file($file['tmp_name'], $file_path)) {
        // File uploaded successfully, now add to database
        if (add_resource($title, $description, $file_path, $file_type, $category, $posted_by)) {
            $_SESSION['success_message'] = "Resource uploaded successfully!";
        } else {
            $_SESSION['error_message'] = "Failed to add resource to database. Please try again.";
            // Delete the uploaded file since database entry failed
            @unlink($file_path);
        }
    } else {
        $_SESSION['error_message'] = "Failed to upload file. Please try again.";
    }
    
    // Redirect to prevent form resubmission
    header("Location: resources.php");
    exit();
}

// Process resource deletion
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    
    if (delete_resource($id)) {
        $_SESSION['success_message'] = "Resource deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Failed to delete resource. Please try again.";
    }
    
    // Redirect to prevent resubmission
    header("Location: resources.php");
    exit();
}

// Fetch all resources
$resources = get_all_resources();

// Check for success/error messages
$successMessage = '';
$errorMessage = '';

if (isset($_SESSION['success_message'])) {
    $successMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    $errorMessage = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning Resources Management</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <!-- Inter font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
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
                            950: '#082f49',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    boxShadow: {
                        'smooth': '0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)',
                        'hover': '0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.03)',
                        'card': '0 1px 3px 0 rgba(0, 0, 0, 0.04), 0 1px 2px 0 rgba(0, 0, 0, 0.02)',
                    },
                    transitionProperty: {
                        'height': 'height',
                        'spacing': 'margin, padding',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
        }
        .card {
            transition: all 0.2s ease;
            border-radius: 0.75rem;
        }
        .card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            border-bottom: 1px solid #f3f4f6;
            padding: 1rem 1.5rem;
        }
        .card-body {
            padding: 1.5rem;
        }
        .animate-spin {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); filter: blur(5px); }
            to { opacity: 1; transform: translateY(0); filter: blur(0); }
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
            transition: background 0.3s ease;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* DataTables Custom Styling */
        .dataTables_wrapper {
            background-color: transparent;
            padding: 0.5rem;
        }
        
        .dataTables_filter input {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            margin-left: 0.5rem;
            font-size: 0.875rem;
            transition: all 0.2s;
        }
        
        .dataTables_filter input:focus {
            outline: none;
            border-color: #0ea5e9;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15);
        }
        
        .dataTables_length select {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            transition: all 0.2s;
            background-image: none;
            -webkit-appearance: auto;
            appearance: auto;
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
    </style>
</head>

<body class="font-sans text-gray-800 transition-opacity duration-300 opacity-0">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                        <div class="bg-primary-100 p-2 rounded-lg mr-3 shadow-sm">
                            <i class="fas fa-file-alt text-primary-600"></i>
                        </div>
                        Learning Resources Management
                    </h1>
                    <p class="text-gray-500 mt-1 ml-12">Upload and manage learning materials for students</p>
                </div>
                <div class="flex space-x-3 mt-4 md:mt-0">
                    <button id="refreshButton" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-2.5 px-4 rounded-lg transition duration-300 flex items-center shadow-sm">
                        <i class="fas fa-sync-alt mr-2 text-gray-500"></i>
                        Refresh
                    </button>
                    <button id="newResourceBtn" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-300 flex items-center shadow-sm">
                        <i class="fas fa-upload mr-2"></i>
                        Upload Resource
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
                            <span class="text-primary-600 font-medium">Learning Resources</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Upload Resource Form (Initially Hidden) -->
        <div id="resourceForm" class="bg-white rounded-xl shadow-sm border border-gray-100 card mb-8 hidden fade-in">
            <div class="card-header flex items-center">
                <i class="fas fa-file-upload text-primary-600 mr-2"></i>
                <h2 class="font-semibold text-gray-800">Upload New Resource</h2>
            </div>
            <div class="card-body">
                <form action="resources.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                            <input type="text" id="title" name="title" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" required placeholder="e.g., Programming Guide">
                        </div>
                        
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select id="category" name="category" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" required>
                                <option value="">Select Category</option>
                                <option value="C Programming">C Programming</option>
                                <option value="Java Programming">Java Programming</option>
                                <option value="C# Programming">C# Programming</option>
                                <option value="PHP Programming">PHP Programming</option>
                                <option value="ASP.NET Programming">ASP.NET Programming</option>
                                <option value="General">General Resources</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="description" name="description" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Provide details about this resource..."></textarea>
                    </div>
                    
                    <div>
                        <label for="resource_file" class="block text-sm font-medium text-gray-700 mb-1">Upload File</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="resource_file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-2"></i>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                    <p class="text-xs text-gray-500">PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, ZIP, TXT (MAX 10MB)</p>
                                </div>
                                <input id="resource_file" name="resource_file" type="file" class="hidden" required />
                            </label>
                        </div>
                        <div id="file-selected" class="text-sm text-gray-600 mt-2"></div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" id="cancelResource" class="bg-white border border-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-50 transition duration-200">Cancel</button>
                        <button type="submit" name="upload_resource" class="bg-primary-600 hover:bg-primary-700 text-white py-2 px-4 rounded-md transition duration-200">
                            <i class="fas fa-upload mr-2"></i> Upload Resource
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Resources Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 card">
            <div class="card-header flex items-center">
                <i class="fas fa-list text-primary-600 mr-2"></i>
                <h2 class="font-semibold text-gray-800">All Resources</h2>
            </div>
            <div class="card-body overflow-x-auto">
                <?php if (empty($resources)): ?>
                <div class="text-center py-6 text-gray-500">
                    <div class="bg-gray-50 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-file-excel text-gray-300 text-3xl"></i>
                    </div>
                    <h3 class="font-medium text-gray-800 mb-1">No Resources Found</h3>
                    <p class="text-sm">Start by uploading your first learning resource.</p>
                </div>
                <?php else: ?>
                <table id="resourcesTable" class="min-w-full divide-y divide-gray-200 table-auto">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Downloads</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($resources as $resource): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900"><?php echo htmlspecialchars($resource['title']); ?></div>
                                <div class="text-xs text-gray-500 mt-1 max-w-xs truncate"><?php echo htmlspecialchars($resource['description']); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-primary-100 text-primary-800 text-xs font-medium px-2.5 py-0.5 rounded"><?php echo htmlspecialchars($resource['category']); ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                $icon_class = 'fas fa-file';
                                $type_class = 'bg-gray-100 text-gray-800';
                                
                                switch ($resource['file_type']) {
                                    case 'pdf':
                                        $icon_class = 'fas fa-file-pdf';
                                        $type_class = 'bg-red-100 text-red-800';
                                        break;
                                    case 'doc':
                                    case 'docx':
                                        $icon_class = 'fas fa-file-word';
                                        $type_class = 'bg-blue-100 text-blue-800';
                                        break;
                                    case 'ppt':
                                    case 'pptx':
                                        $icon_class = 'fas fa-file-powerpoint';
                                        $type_class = 'bg-orange-100 text-orange-800';
                                        break;
                                    case 'xls':
                                    case 'xlsx':
                                        $icon_class = 'fas fa-file-excel';
                                        $type_class = 'bg-green-100 text-green-800';
                                        break;
                                    case 'zip':
                                        $icon_class = 'fas fa-file-archive';
                                        $type_class = 'bg-yellow-100 text-yellow-800';
                                        break;
                                    case 'txt':
                                        $icon_class = 'fas fa-file-alt';
                                        $type_class = 'bg-gray-100 text-gray-800';
                                        break;
                                }
                                ?>
                                <span class="<?php echo $type_class; ?> text-xs font-medium px-2.5 py-0.5 rounded">
                                    <i class="<?php echo $icon_class; ?> mr-1"></i>
                                    <?php echo strtoupper($resource['file_type']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                <?php echo date('M d, Y', strtotime($resource['created_at'])); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                <?php echo $resource['download_count']; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="<?php echo $resource['file_path']; ?>" download class="text-primary-600 hover:text-primary-800 mr-3 transition-colors">
                                    <i class="fas fa-download"></i> Download
                                </a>
                                <a href="#" class="text-red-600 hover:text-red-800 transition-colors" onclick="confirmDelete(<?php echo $resource['id']; ?>)">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Page load animation
        document.addEventListener('DOMContentLoaded', function() {
            // Fade in the body
            setTimeout(() => {
                document.body.style.opacity = "1";
            }, 100);
            
            // Initialize DataTable if table exists
            if (document.getElementById('resourcesTable')) {
                $('#resourcesTable').DataTable({
                    "order": [[3, "desc"]], // Sort by date column descending
                    "pageLength": 10,
                    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    "responsive": true,
                    language: {
                        search: "",
                        searchPlaceholder: "Search resources...",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ resources",
                        paginate: {
                            first: '<i class="fas fa-angle-double-left"></i>',
                            previous: '<i class="fas fa-angle-left"></i>',
                            next: '<i class="fas fa-angle-right"></i>',
                            last: '<i class="fas fa-angle-double-right"></i>'
                        }
                    },
                    "drawCallback": function() {
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
                        $('.dataTables_filter input').addClass('focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-20');
                        $('.dataTables_length select').addClass('focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-20');
                        
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
            }
            
            // File upload preview
            const fileInput = document.getElementById('resource_file');
            const fileSelected = document.getElementById('file-selected');
            
            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    const fileName = e.target.files[0]?.name;
                    if (fileName) {
                        fileSelected.textContent = `Selected file: ${fileName}`;
                    } else {
                        fileSelected.textContent = '';
                    }
                });
            }
        });
        
        // Show/hide the resource form
        document.getElementById('newResourceBtn')?.addEventListener('click', function() {
            const form = document.getElementById('resourceForm');
            form.classList.remove('hidden');
        });
        
        document.getElementById('cancelResource')?.addEventListener('click', function() {
            const form = document.getElementById('resourceForm');
            form.classList.add('hidden');
        });
        
        // Refresh Button functionality
        document.getElementById('refreshButton').addEventListener('click', function() {
            // Add rotate animation to the icon
            const icon = this.querySelector('i');
            icon.classList.add('animate-spin');
            
            // Disable the button temporarily
            this.disabled = true;
            
            // Reload the page after a short delay
            setTimeout(() => {
                window.location.reload();
            }, 500);
        });
        
        // Confirm deletion
        function confirmDelete(id) {
            event.preventDefault();
            
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `resources.php?action=delete&id=${id}`;
                }
            });
        }
        
        // Display success message if exists
        <?php if (!empty($successMessage)): ?>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '<?php echo $successMessage; ?>',
            timer: 3000,
            timerProgressBar: true
        });
        <?php endif; ?>
        
        // Display error message if exists
        <?php if (!empty($errorMessage)): ?>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '<?php echo $errorMessage; ?>',
            timer: 3000,
            timerProgressBar: true
        });
        <?php endif; ?>
    </script>
</body>
</html> 