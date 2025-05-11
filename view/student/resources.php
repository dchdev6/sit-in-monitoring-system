<?php
// Buffer output to prevent "headers already sent" errors
ob_start();

include '../../includes/navbar_student.php';

// Fetch all resources
$resources = get_all_resources();

// Add filter by category if needed
$filter_category = isset($_GET['category']) ? $_GET['category'] : '';

// Filter resources if category filter is active
if (!empty($filter_category)) {
    $filtered_resources = [];
    foreach ($resources as $resource) {
        if ($resource['category'] == $filter_category) {
            $filtered_resources[] = $resource;
        }
    }
    $resources = $filtered_resources;
}

// Check for any success messages
$successMessage = '';
if(isset($_SESSION['success_message'])) {
    $successMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CCS | Learning Resources</title>
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
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        body {
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
            from { opacity: 0; }
            to { opacity: 1; }
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
                            <i class="fas fa-book text-primary-600"></i>
                        </div>
                        Learning Resources
                    </h1>
                    <p class="text-gray-500 mt-1 ml-12">Browse and download educational materials</p>
                </div>
                <div class="flex space-x-3 mt-4 md:mt-0">
                    <button id="refreshButton" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-2.5 px-4 rounded-lg transition duration-300 flex items-center shadow-sm">
                        <i class="fas fa-sync-alt mr-2 text-gray-500"></i>
                        Refresh
                    </button>
                </div>
            </div>
            
            <!-- Breadcrumbs -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm">
                    <li class="inline-flex items-center">
                        <a href="homepage.php" class="text-gray-500 hover:text-primary-600 transition-colors inline-flex items-center">
                            <i class="fas fa-home mr-2"></i>
                            Home
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

        <!-- Category Filter Pills -->
        <div class="flex flex-wrap gap-2 mb-6">
            <a href="resources.php" class="px-4 py-2 rounded-full <?php echo empty($filter_category) ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'; ?> transition duration-300 text-sm font-medium">
                All Categories
            </a>
            <a href="resources.php?category=C Programming" class="px-4 py-2 rounded-full <?php echo $filter_category == 'C Programming' ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'; ?> transition duration-300 text-sm font-medium">
                C Programming
            </a>
            <a href="resources.php?category=Java Programming" class="px-4 py-2 rounded-full <?php echo $filter_category == 'Java Programming' ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'; ?> transition duration-300 text-sm font-medium">
                Java Programming
            </a>
            <a href="resources.php?category=C# Programming" class="px-4 py-2 rounded-full <?php echo $filter_category == 'C# Programming' ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'; ?> transition duration-300 text-sm font-medium">
                C# Programming
            </a>
            <a href="resources.php?category=PHP Programming" class="px-4 py-2 rounded-full <?php echo $filter_category == 'PHP Programming' ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'; ?> transition duration-300 text-sm font-medium">
                PHP Programming
            </a>
            <a href="resources.php?category=ASP.NET Programming" class="px-4 py-2 rounded-full <?php echo $filter_category == 'ASP.NET Programming' ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'; ?> transition duration-300 text-sm font-medium">
                ASP.NET
            </a>
            <a href="resources.php?category=General" class="px-4 py-2 rounded-full <?php echo $filter_category == 'General' ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'; ?> transition duration-300 text-sm font-medium">
                General Resources
            </a>
        </div>
        
        <!-- Resources Grid -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 card">
            <div class="card-header flex items-center">
                <i class="fas fa-file-alt text-primary-600 mr-2"></i>
                <h2 class="font-semibold text-gray-800">Available Learning Materials</h2>
            </div>
            <div class="card-body">
                <?php if (empty($resources)): ?>
                <div class="text-center py-12">
                    <div class="bg-gray-50 rounded-full w-20 h-20 mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-file-excel text-gray-300 text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-800 mb-2">No Resources Found</h3>
                    <p class="text-gray-500 max-w-md mx-auto">There are no learning resources available for the selected category at this time.</p>
                </div>
                <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($resources as $resource): ?>
                        <?php
                        // Determine icon and color based on file type
                        $icon_class = 'fas fa-file-alt';
                        $bg_color = 'bg-gray-100';
                        $text_color = 'text-gray-700';
                        
                        switch ($resource['file_type']) {
                            case 'pdf':
                                $icon_class = 'fas fa-file-pdf';
                                $bg_color = 'bg-red-100';
                                $text_color = 'text-red-700';
                                break;
                            case 'doc':
                            case 'docx':
                                $icon_class = 'fas fa-file-word';
                                $bg_color = 'bg-blue-100';
                                $text_color = 'text-blue-700';
                                break;
                            case 'ppt':
                            case 'pptx':
                                $icon_class = 'fas fa-file-powerpoint';
                                $bg_color = 'bg-orange-100';
                                $text_color = 'text-orange-700';
                                break;
                            case 'xls':
                            case 'xlsx':
                                $icon_class = 'fas fa-file-excel';
                                $bg_color = 'bg-green-100';
                                $text_color = 'text-green-700';
                                break;
                            case 'zip':
                                $icon_class = 'fas fa-file-archive';
                                $bg_color = 'bg-yellow-100';
                                $text_color = 'text-yellow-700';
                                break;
                        }
                        ?>
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300">
                            <div class="p-5 <?php echo $bg_color; ?> rounded-t-lg flex justify-center">
                                <i class="<?php echo $icon_class; ?> <?php echo $text_color; ?> text-4xl"></i>
                            </div>
                            <div class="p-5">
                                <h3 class="text-lg font-medium text-gray-800 mb-2"><?php echo htmlspecialchars($resource['title']); ?></h3>
                                
                                <div class="flex items-center mb-3">
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                        <?php echo htmlspecialchars($resource['category']); ?>
                                    </span>
                                    <span class="text-xs text-gray-500 ml-2">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        <?php echo date('M d, Y', strtotime($resource['created_at'])); ?>
                                    </span>
                                </div>
                                
                                <?php if (!empty($resource['description'])): ?>
                                <p class="text-gray-600 text-sm mb-4"><?php echo htmlspecialchars($resource['description']); ?></p>
                                <?php endif; ?>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500">
                                        <i class="fas fa-download mr-1"></i>
                                        <?php echo $resource['download_count']; ?> downloads
                                    </span>
                                    
                                    <a href="<?php echo $resource['file_path']; ?>" download class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-primary-600 rounded-md hover:bg-primary-700 transition-colors">
                                        <i class="fas fa-download mr-2"></i>
                                        Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
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
        });
    </script>
</body>
</html> 