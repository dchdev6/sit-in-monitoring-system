<?php
include '../../includes/navbar_admin.php';

$listPerson = retrieve_students();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Information</title>
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
    
    /* Status Badges */
    .status-badge {
      display: inline-flex;
      align-items: center;
      padding: 0.375rem 0.875rem;
      border-radius: 9999px;
      font-size: 0.75rem;
      font-weight: 500;
      text-align: center;
      transition: all 0.3s ease;
      white-space: nowrap;
    }
    
    .status-badge.low {
      background-color: #fee2e2;
      color: #b91c1c;
    }
    
    .status-badge.medium {
      background-color: #fef3c7;
      color: #92400e;
    }
    
    .status-badge.good {
      background-color: #d1fae5;
      color: #047857;
    }
    
    .status-badge i {
      margin-right: 0.375rem;
      font-size: 0.75rem;
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
    
    /* Action buttons */
    .action-btn {
      width: 2.25rem;
      height: 2.25rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 0.5rem;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    
    .action-btn::after {
      content: "";
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      pointer-events: none;
      background-image: radial-gradient(circle, #fff 10%, transparent 10.01%);
      background-repeat: no-repeat;
      background-position: 50%;
      transform: scale(10, 10);
      opacity: 0;
      transition: transform 0.5s, opacity 1s;
    }
    
    .action-btn:active::after {
      transform: scale(0, 0);
      opacity: 0.3;
      transition: 0s;
    }
    
    /* Action button tooltips */
    .tooltip-container {
      position: relative;
    }
    
    .tooltip {
      position: absolute;
      bottom: 100%;
      left: 50%;
      transform: translateX(-50%) translateY(10px);
      background-color: #1f2937;
      color: white;
      padding: 0.5rem 0.75rem;
      border-radius: 0.375rem;
      font-size: 0.75rem;
      white-space: nowrap;
      opacity: 0;
      visibility: hidden;
      transition: all 0.2s ease;
      z-index: 10;
    }
    
    .tooltip::after {
      content: "";
      position: absolute;
      top: 100%;
      left: 50%;
      transform: translateX(-50%);
      border-width: 5px;
      border-style: solid;
      border-color: #1f2937 transparent transparent transparent;
    }
    
    .tooltip-container:hover .tooltip {
      opacity: 1;
      visibility: visible;
      transform: translateX(-50%) translateY(0);
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
              <i class="fas fa-user-graduate text-primary-600"></i>
            </div>
            Student Information
          </h1>
          <p class="text-gray-500 mt-1 ml-12">Manage student records and laboratory sessions</p>
        </div>
        <div class="flex space-x-3 mt-4 md:mt-0">
          <a href="Add.php" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-300 flex items-center shadow-sm btn-animated">
            <i class="fas fa-plus mr-2"></i>
            Add Student
          </a>
          <button id="resetButton" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-2.5 px-4 rounded-lg transition duration-300 flex items-center shadow-sm btn-animated">
            <i class="fas fa-sync-alt mr-2 text-gray-500"></i>
            Reset Sessions
          </button>
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
              <span class="text-primary-600 font-medium">Students</span>
            </div>
          </li>
        </ol>
      </nav>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 stat-card animate__animated animate__fadeInUp animate__faster" style="animation-delay: 0.1s">
        <div class="flex items-center">
          <div class="rounded-full bg-blue-100 p-3 mr-4 icon-container">
            <i class="fas fa-users text-blue-600"></i>
          </div>
          <div>
            <p class="text-sm text-gray-500 font-medium">Total Students</p>
            <div class="flex items-end">
              <p class="text-2xl font-bold counter" data-target="<?php echo count($listPerson); ?>">0</p>
              <span class="text-xs text-blue-600 ml-1.5 font-medium">students</span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 stat-card animate__animated animate__fadeInUp animate__faster" style="animation-delay: 0.2s">
        <div class="flex items-center">
          <div class="rounded-full bg-green-100 p-3 mr-4 icon-container">
            <i class="fas fa-user-check text-green-600"></i>
          </div>
          <div>
            <p class="text-sm text-gray-500 font-medium">Active Students</p>
            <div class="flex items-end">
              <p class="text-2xl font-bold counter" data-target="<?php 
                  $activeCount = 0;
                  foreach($listPerson as $person) {
                    if($person['session'] > 0) $activeCount++;
                  }
                  echo $activeCount;
                ?>">0</p>
              <span class="text-xs text-green-600 ml-1.5 font-medium">with sessions</span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 stat-card animate__animated animate__fadeInUp animate__faster" style="animation-delay: 0.3s">
        <div class="flex items-center">
          <div class="rounded-full bg-yellow-100 p-3 mr-4 icon-container">
            <i class="fas fa-clock text-yellow-600"></i>
          </div>
          <div>
            <p class="text-sm text-gray-500 font-medium">Avg. Sessions</p>
            <div class="flex items-end">
              <p class="text-2xl font-bold counter-decimal" data-target="<?php 
                  $totalSessions = 0;
                  foreach($listPerson as $person) {
                    $totalSessions += $person['session'];
                  }
                  echo count($listPerson) > 0 ? round($totalSessions / count($listPerson), 1) : 0;
                ?>">0</p>
              <span class="text-xs text-yellow-600 ml-1.5 font-medium">per student</span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 stat-card animate__animated animate__fadeInUp animate__faster" style="animation-delay: 0.4s">
        <div class="flex items-center">
          <div class="rounded-full bg-purple-100 p-3 mr-4 icon-container">
            <i class="fas fa-graduation-cap text-purple-600"></i>
          </div>
          <div>
            <p class="text-sm text-gray-500 font-medium">Programs</p>
            <div class="flex items-end">
              <p class="text-2xl font-bold counter" data-target="<?php 
                  $courses = array();
                  foreach($listPerson as $person) {
                    if(!in_array($person['course'], $courses)) {
                      $courses[] = $person['course'];
                    }
                  }
                  echo count($courses);
                ?>">0</p>
              <span class="text-xs text-purple-600 ml-1.5 font-medium">course types</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8 animate__animated animate__fadeInUp animate__faster" style="animation-delay: 0.5s">
      <div class="border-b border-gray-100 px-6 py-4">
        <h2 class="text-lg font-semibold text-gray-800 flex items-center">
          <i class="fas fa-table text-primary-500 mr-2"></i>
          Student Records
        </h2>
      </div>
      <div class="p-6">
        <table id="studentTable" class="w-full">
          <thead>
            <tr>
              <th>ID Number</th>
              <th>Name</th>
              <th>Year Level</th>
              <th>Course</th>
              <th>Remaining Sessions</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($listPerson as $person) : ?>
              <tr class="row-animation">
                <td class="font-medium"><?php echo $person['id_number']; ?></td>
                <td><?php echo $person['firstName'] . " " . $person['middleName'] . ". " . $person['lastName']; ?></td>
                <td>
                  <?php 
                    $yearLevel = $person['yearLevel'];
                    $yearText = "";
                    
                    switch($yearLevel) {
                      case "1st Year":
                      case "1":
                        $yearText = "Freshmen";
                        break;
                      case "2nd Year":
                      case "2":
                        $yearText = "Sophomore";
                        break;
                      case "3rd Year":
                      case "3":
                        $yearText = "Junior";
                        break;
                      case "4th Year":
                      case "4":
                        $yearText = "Senior";
                        break;
                      default:
                        $yearText = $yearLevel;
                    }
                    
                    echo $yearText;
                  ?>
                </td>
                <td>
                  <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    <?php echo $person['course']; ?>
                  </span>
                </td>
                <td>
                  <?php 
                    $session = $person['session'];
                    $statusClass = '';
                    $statusIcon = '';
                    
                    if($session <= 6) {
                      $statusClass = 'low';
                      $statusIcon = 'fa-exclamation-circle';
                    } else if($session <= 15) {
                      $statusClass = 'medium';
                      $statusIcon = 'fa-clock';
                    } else {
                      $statusClass = 'good';
                      $statusIcon = 'fa-check-circle';
                    }
                  ?>
                  <span class="status-badge <?php echo $statusClass; ?>">
                    <i class="fas <?php echo $statusIcon; ?>"></i>
                    <?php echo $session; ?> sessions
                  </span>
                </td>
                <td>
                  <div class="flex justify-center space-x-2">
                    <div class="tooltip-container">
                      <button type="button" class="action-btn bg-primary-50 hover:bg-primary-100 text-primary-600 edit-student" data-id="<?php echo $person['id_number']; ?>">
                        <i class="fas fa-edit"></i>
                      </button>
                      <div class="tooltip">Edit Student</div>
                    </div>
                    
                    <div class="tooltip-container">
                      <form action="Students.php" method="POST" class="inline-block delete-form">
                        <input type="hidden" name="idNum" value="<?php echo $person['id_number']; ?>" />
                        <button type="submit" name="deleteStudent" class="action-btn bg-red-50 hover:bg-red-100 text-red-600">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </form>
                      <div class="tooltip">Delete Student</div>
                    </div>
                    
                    <div class="tooltip-container">
                      <button class="action-btn bg-gray-50 hover:bg-gray-100 text-gray-700 view-details" data-id="<?php echo $person['id_number']; ?>">
                        <i class="fas fa-eye"></i>
                      </button>
                      <div class="tooltip">View Details</div>
                    </div>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    
    <!-- Quick Help Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8 animate__animated animate__fadeInUp animate__faster" style="animation-delay: 0.6s">
      <div class="flex items-start">
        <div class="flex-shrink-0 bg-primary-50 rounded-lg p-3 mr-4">
          <i class="fas fa-lightbulb text-primary-500 text-xl"></i>
        </div>
        <div>
          <h3 class="text-lg font-medium text-gray-800 mb-2">Quick Tips</h3>
          <div class="text-sm text-gray-600 space-y-2">
            <p>• Use the search box to quickly find students by name or ID number</p>
            <p>• Students with fewer than 3 sessions remaining are marked in yellow or red</p>
            <p>• Reset all sessions at once using the "Reset Sessions" button above</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Student Modal -->
  <div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content rounded-lg shadow-lg border-0">
        <div class="modal-header bg-primary-50 border-0">
          <h5 class="modal-title text-primary-800 font-semibold" id="editStudentModalLabel">
            <i class="fas fa-user-edit mr-2"></i>Edit Student Information
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <form id="editStudentForm">
          <div class="modal-body p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- Student ID (Hidden) -->
              <input type="hidden" id="editStudentId" name="idNumber">
              
              <!-- Last Name -->
              <div class="form-group">
                <label for="editLastName" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="editLastName" name="lName" required>
              </div>
              
              <!-- First Name -->
              <div class="form-group">
                <label for="editFirstName" class="form-label">First Name</label>
                <input type="text" class="form-control" id="editFirstName" name="fName" required>
              </div>
              
              <!-- Middle Name -->
              <div class="form-group">
                <label for="editMiddleName" class="form-label">Middle Name</label>
                <input type="text" class="form-control" id="editMiddleName" name="mName">
              </div>
              
              <!-- Year Level -->
              <div class="form-group">
                <label for="editYearLevel" class="form-label">Year Level</label>
                <select class="form-select" id="editYearLevel" name="courseLevel" required>
                  <option value="1">1st Year</option>
                  <option value="2">2nd Year</option>
                  <option value="3">3rd Year</option>
                  <option value="4">4th Year</option>
                </select>
              </div>
              
              <!-- Email -->
              <div class="form-group">
                <label for="editEmail" class="form-label">Email</label>
                <input type="email" class="form-control" id="editEmail" name="email" required>
              </div>
              
              <!-- Course -->
              <div class="form-group">
                <label for="editCourse" class="form-label">Course</label>
                <select class="form-select" id="editCourse" name="course" required>
                  <optgroup label="Computing & IT">
                    <option value="BSIT">BS in Information Technology</option>
                    <option value="BSCS">BS in Computer Science</option>
                    <option value="BSIS">BS in Information Systems</option>
                  </optgroup>
                  <optgroup label="Business & Management">
                    <option value="BSA">BS in Accountancy</option>
                    <option value="BSBA">BS in Business Administration</option>
                    <option value="BSAIS">BS in Accounting Information System</option>
                  </optgroup>
                  <!-- Add more course options as needed -->
                </select>
              </div>
              
              <!-- Address -->
              <div class="col-span-1 md:col-span-2">
                <label for="editAddress" class="form-label">Address</label>
                <textarea class="form-control" id="editAddress" name="address" rows="3"></textarea>
              </div>
            </div>
          </div>
          
          <div class="modal-footer flex justify-end border-0 bg-gray-50 rounded-b-lg">
            <button type="button" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md transition duration-200 flex items-center" data-bs-dismiss="modal">
              <i class="fas fa-times-circle mr-2"></i>Cancel
            </button>
            <button type="submit" id="editStudentSubmitBtn" class="px-4 py-2 bg-[#0ea5e9] hover:bg-[#0284c7] text-white rounded-md transition duration-200 flex items-center shadow-sm">
              <i class="fas fa-save mr-2"></i>Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- jQuery (required for DataTables) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Animate.css JS -->
  <script>
    // Define the resetStudentSessions function in the global scope so it's accessible from HTML
    function resetStudentSessions(studentId) {
      Swal.fire({
        title: '<div class="flex items-center"><i class="fas fa-sync-alt text-yellow-500 mr-3"></i>Reset Sessions</div>',
        html: `
          <div class="text-left">
            <p class="mb-3">Reset session count for student ID: <strong>${studentId}</strong>?</p>
            <div class="bg-yellow-50 text-yellow-800 p-3 rounded-lg text-sm flex items-start">
              <i class="fas fa-info-circle mr-2 mt-0.5"></i>
              <span>This will reset the student's session count to 30 sessions. This action cannot be undone.</span>
            </div>
          </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#0ea5e9',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-check mr-2"></i>Yes, reset',
        cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
        showClass: {
          popup: 'animate__animated animate__fadeInDown animate__faster'
        },
        hideClass: {
          popup: 'animate__animated animate__fadeOutUp animate__faster'
        }
      }).then((result) => {
        if (result.isConfirmed) {
          // Show loading state
          Swal.fire({
            title: 'Processing...',
            html: `Resetting sessions for student ID: ${studentId}`,
            timer: 1500,
            timerProgressBar: true,
            didOpen: () => {
              Swal.showLoading();
              
              // Send AJAX request to reset sessions for this student
              $.ajax({
                url: '../../includes/reset_single_student_session.php',
                method: 'POST',
                data: { studentId: studentId },
                dataType: 'json',
                timeout: 5000,
                cache: false,
                success: function(response) {
                  if (response.success) {
                    // Success state
                    Swal.fire({
                      icon: 'success',
                      title: 'Reset Complete!',
                      html: `
                        <div class="text-left">
                          <p class="mb-2">Student sessions have been reset successfully.</p>
                          <div class="bg-green-50 text-green-800 p-3 rounded-lg text-sm flex items-start mt-3">
                            <i class="fas fa-check-circle mr-2 mt-0.5"></i>
                            <span>Session count has been set to ${response.defaultValue} sessions.</span>
                          </div>
                        </div>
                      `,
                      confirmButtonColor: '#0ea5e9',
                      showClass: {
                        popup: 'animate__animated animate__fadeInDown animate__faster'
                      }
                    }).then(() => {
                      // Reload the page to refresh the data
                      window.location.reload();
                    });
                  } else {
                    // Error state
                    Swal.fire({
                      icon: 'error',
                      title: 'Reset Failed',
                      text: 'There was an error resetting the session count: ' + response.message,
                      confirmButtonColor: '#0ea5e9'
                    });
                  }
                },
                error: function(xhr, textStatus, errorThrown) {
                  console.error("AJAX Error:", textStatus, errorThrown);
                  
                  // Error state for AJAX failure
                  Swal.fire({
                    icon: 'error',
                    title: 'Connection Error',
                    text: 'Could not connect to the server. Error: ' + textStatus + ' - ' + errorThrown,
                    confirmButtonColor: '#0ea5e9'
                  });
                }
              });
            },
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false
          });
        }
      });
    }

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
      
      // Animate counter numbers
      function animateCounter() {
        $('.counter').each(function() {
          const $this = $(this);
          const target = parseInt($this.attr('data-target'));
          
          $({ Counter: 0 }).animate({
            Counter: target
          }, {
            duration: 1200,
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
            duration: 1200,
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
      const table = $('#studentTable').DataTable({
        responsive: true,
        language: {
          search: "",
          searchPlaceholder: "Search students...",
          lengthMenu: "Show _MENU_ entries",
          info: "Showing _START_ to _END_ of _TOTAL_ students",
          paginate: {
            first: '<i class="fas fa-angle-double-left"></i>',
            previous: '<i class="fas fa-angle-left"></i>',
            next: '<i class="fas fa-angle-right"></i>',
            last: '<i class="fas fa-angle-double-right"></i>'
          }
        },
        order: [[0, 'asc']],
        columnDefs: [
          { orderable: false, targets: 5 } // Disable sorting on actions column
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
        },
        initComplete: function() {
          // Add custom classes to DataTable elements
          $('.dataTables_filter').addClass('relative');
          $('.dataTables_filter label').addClass('flex items-center');
          $('.dataTables_length select').addClass('focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500');
          
          // Remove any padding adjustments that were for the icon
          $('.dataTables_filter input').removeClass('pl-9');
        }
      });
      
      // Add shimmer effect to search when typing
      $('.dataTables_filter input').on('input', function() {
        $(this).addClass('shimmer');
        setTimeout(() => {
          $(this).removeClass('shimmer');
        }, 500);
      });
      
      // Reset Sessions Button with animation
      $('#resetButton').on('click', function() {
        $(this).addClass('animate-pulse');
        
        Swal.fire({
          title: '<div class="flex items-center mb-2"><i class="fas fa-sync-alt text-yellow-500 mr-3"></i>Reset All Sessions?</div>',
          html: `
            <div class="text-left">
              <p class="mb-3">This will reset the session count for all students to 30 sessions.</p>
              <div class="bg-yellow-50 text-yellow-800 p-3 rounded-lg text-sm flex items-start">
                <i class="fas fa-exclamation-triangle mr-2 mt-0.5"></i>
                <span>This action cannot be undone. All current session counts will be lost.</span>
              </div>
            </div>
          `,
          showCancelButton: true,
          confirmButtonColor: '#0ea5e9',
          cancelButtonColor: '#6b7280',
          confirmButtonText: '<i class="fas fa-check mr-2"></i>Yes, reset all',
          cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
          showClass: {
            popup: 'animate__animated animate__fadeInDown animate__faster'
          },
          hideClass: {
            popup: 'animate__animated animate__fadeOutUp animate__faster'
          }
        }).then((result) => {
          $(this).removeClass('animate-pulse');
          
          if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
              title: 'Processing...',
              html: 'Resetting session counts for all students',
              timer: 2000,
              timerProgressBar: true,
              didOpen: () => {
                Swal.showLoading();
                
                // Send AJAX request to reset sessions
                $.ajax({
                  url: '../../includes/reset_sessions.php', // Verify this path is correct
                  method: 'POST',
                  dataType: 'json',
                  timeout: 5000, // Add timeout to catch network errors
                  cache: false, // Prevent caching
                  success: function(response) {
                    console.log("Response received:", response); // Log the response
                    
                    if (response.success) {
                      const defaultValue = response.defaultValue;
                      
                      // Success state
                      Swal.fire({
                        icon: 'success',
                        title: 'Reset Complete!',
                        html: `
                          <div class="text-left">
                            <p class="mb-2">All student sessions have been reset successfully.</p>
                            <div class="bg-green-50 text-green-800 p-3 rounded-lg text-sm flex items-start mt-3">
                              <i class="fas fa-check-circle mr-2 mt-0.5"></i>
                              <span>Session counts have been set to ${defaultValue} sessions.</span>
                            </div>
                          </div>
                        `,
                        confirmButtonColor: '#0ea5e9',
                        showClass: {
                          popup: 'animate__animated animate__fadeInDown animate__faster'
                        }
                      }).then(() => {
                        // Reload the page to refresh the data
                        window.location.reload();
                      });
                    } else {
                      // Error state
                      Swal.fire({
                        icon: 'error',
                        title: 'Reset Failed',
                        text: 'There was an error resetting the session counts: ' + response.message,
                        confirmButtonColor: '#0ea5e9'
                      });
                    }
                  },
                  error: function(xhr, textStatus, errorThrown) {
                    console.error("AJAX Error:", textStatus, errorThrown); // Log detailed error info
                    
                    // Error state for AJAX failure
                    Swal.fire({
                      icon: 'error',
                      title: 'Connection Error',
                      text: 'Could not connect to the server. Error: ' + textStatus + ' - ' + errorThrown,
                      confirmButtonColor: '#0ea5e9'
                    });
                  }
                });
              },
              showConfirmButton: false,
              allowOutsideClick: false,
              allowEscapeKey: false,
              allowEnterKey: false
            });
          }
        });
      });
      
      // Delete confirmation with SweetAlert and animations
      $('.delete-form').on('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const studentId = $(this).find('input[name="idNum"]').val();
        
        Swal.fire({
          title: '<div class="flex items-center text-red-600"><i class="fas fa-exclamation-triangle mr-3"></i>Delete Student?</div>',
          html: `
            <div class="text-left">
              <p class="mb-3">You are about to delete student with ID: <strong>${studentId}</strong></p>
              <div class="bg-red-50 text-red-800 p-3 rounded-lg text-sm flex items-start">
                <i class="fas fa-trash mr-2 mt-0.5"></i>
                <span>This action cannot be undone. All student data and session records will be permanently deleted.</span>
              </div>
            </div>
          `,
          showCancelButton: true,
          confirmButtonColor: '#ef4444',
          cancelButtonColor: '#6b7280',
          confirmButtonText: '<i class="fas fa-trash-alt mr-2"></i>Yes, delete',
          cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
          showClass: {
            popup: 'animate__animated animate__zoomIn animate__faster'
          },
          hideClass: {
            popup: 'animate__animated animate__zoomOut animate__faster'
          }
        }).then((result) => {
          if (result.isConfirmed) {
            // Animate row removal before actual submission
            const row = $(form).closest('tr');
            row.animate({
              opacity: 0,
              height: 0,
              padding: 0
            }, 400, function() {
              // Show loading state
              Swal.fire({
                title: 'Deleting...',
                html: `Removing student ${studentId} from the system`,
                timer: 1500,
                timerProgressBar: true,
                didOpen: () => {
                  Swal.showLoading();
                }
              }).then(() => {
                form.submit();
              });
            });
          }
        });
      });
      
      // Enhanced compact single-row student details modal with improved spacing
      $('.view-details').on('click', function() {
        const studentId = $(this).data('id');
        
        // Add spin animation
        $(this).addClass('animate-spin');
        setTimeout(() => {
          $(this).removeClass('animate-spin');
        }, 500);
        
        // Extract data from the table row
        const rowData = $(this).closest('tr').find('td');
        const studentName = rowData.eq(1).text().trim();
        const yearLevel = rowData.eq(2).text().trim();
        const course = rowData.eq(3).text().trim();
        const sessions = rowData.eq(4).text().trim();
        
        // Use a flatter, single-line design with all key details
        Swal.fire({
          title: '<div class="flex items-center justify-between w-full pr-6"><span class="flex items-center"><i class="fas fa-user-graduate text-primary-600 mr-2"></i>Student Details</span><span class="text-sm font-normal text-gray-500">ID: ' + studentId + '</span></div>',
          html: `
            <div class="bg-white rounded-lg border border-gray-200">
              <!-- Name and badge row -->
              <div class="px-4 py-3 flex items-center justify-between border-b border-gray-200">
                <div class="flex items-center">
                  <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center mr-3">
                    <i class="fas fa-user text-primary-600"></i>
                  </div>
                  <h3 class="font-bold text-gray-800">${studentName}</h3>
                </div>
                <div class="flex items-center space-x-2">
                  <span class="bg-blue-50 px-2 py-0.5 rounded text-xs text-blue-700">${course}</span>
                  <span class="bg-purple-50 px-2 py-0.5 rounded text-xs text-purple-700">${yearLevel}</span>
                  <span class="status-badge ${parseInt(sessions) <= 6 ? 'low' : (parseInt(sessions) <= 15 ? 'medium' : 'good')}">
                    <i class="fas ${parseInt(sessions) <= 6 ? 'fa-exclamation-circle' : (parseInt(sessions) <= 15 ? 'fa-clock' : 'fa-check-circle')}"></i>
                    ${sessions}
                  </span>
                </div>
              </div>
              
              <!-- Information row - all in one line -->
              <div class="grid grid-cols-4 divide-x divide-gray-200">
                <div class="p-3 flex items-center">
                  <i class="fas fa-envelope text-primary-500 mr-2"></i>
                  <div class="overflow-hidden">
                    <div class="text-xs text-gray-500">Email</div>
                    <div class="font-medium text-sm truncate">student${studentId}@example.com</div>
                  </div>
                </div>
                
                <div class="p-3 flex items-center">
                  <i class="fas fa-phone text-primary-500 mr-2"></i>
                  <div>
                    <div class="text-xs text-gray-500">Phone</div>
                    <div class="font-medium text-sm">(123) 456-7890</div>
                  </div>
                </div>
                
                <div class="p-3 flex items-center">
                  <i class="fas fa-calendar-alt text-primary-500 mr-2"></i>
                  <div>
                    <div class="text-xs text-gray-500">Last Session</div>
                    <div class="font-medium text-sm">Mar 20, 2025</div>
                  </div>
                </div>
                
                <div class="p-3 flex justify-center">
                  <button type="button" class="text-yellow-600 border border-yellow-300 bg-yellow-50 hover:bg-yellow-100 text-sm font-medium py-1.5 px-4 rounded-lg transition-all duration-300 flex items-center" onclick="resetStudentSessions('${studentId}')">
                    <i class="fas fa-sync-alt mr-2"></i> Reset Sessions
                  </button>
                </div>
              </div>
            </div>
          `,
          showConfirmButton: false,
          showCancelButton: false,
          showCloseButton: true,
          padding: '0.5rem',
          width: 'auto',
          showClass: {
            popup: 'animate__animated animate__fadeInDown animate__faster'
          },
          hideClass: {
            popup: 'animate__animated animate__fadeOutUp animate__faster'
          },
          customClass: {
            container: 'student-details-modal',
            popup: 'rounded-lg shadow-md max-w-4xl',
            closeButton: 'focus:outline-none text-gray-500 hover:text-gray-700',
            title: 'pr-8' // Add right padding to the title
          }
        });
      });

      // Add the reset student sessions function
      function resetStudentSessions(studentId) {
        Swal.fire({
          title: '<div class="flex items-center"><i class="fas fa-sync-alt text-yellow-500 mr-3"></i>Reset Sessions</div>',
          html: `
            <div class="text-left">
              <p class="mb-3">Reset session count for student ID: <strong>${studentId}</strong>?</p>
              <div class="bg-yellow-50 text-yellow-800 p-3 rounded-lg text-sm flex items-start">
                <i class="fas fa-info-circle mr-2 mt-0.5"></i>
                <span>This will reset the student's session count to 30 sessions. This action cannot be undone.</span>
              </div>
            </div>
          `,
          showCancelButton: true,
          confirmButtonColor: '#0ea5e9',
          cancelButtonColor: '#6b7280',
          confirmButtonText: '<i class="fas fa-check mr-2"></i>Yes, reset',
          cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
          showClass: {
            popup: 'animate__animated animate__fadeInDown animate__faster'
          },
          hideClass: {
            popup: 'animate__animated animate__fadeOutUp animate__faster'
          }
        }).then((result) => {
          if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
              title: 'Processing...',
              html: `Resetting sessions for student ID: ${studentId}`,
              timer: 1500,
              timerProgressBar: true,
              didOpen: () => {
                Swal.showLoading();
                
                // Send AJAX request to reset sessions for this student
                $.ajax({
                  url: '../../includes/reset_single_student_session.php',
                  method: 'POST',
                  data: { studentId: studentId },
                  dataType: 'json',
                  timeout: 5000,
                  cache: false,
                  success: function(response) {
                    if (response.success) {
                      // Success state
                      Swal.fire({
                        icon: 'success',
                        title: 'Reset Complete!',
                        html: `
                          <div class="text-left">
                            <p class="mb-2">Student sessions have been reset successfully.</p>
                            <div class="bg-green-50 text-green-800 p-3 rounded-lg text-sm flex items-start mt-3">
                              <i class="fas fa-check-circle mr-2 mt-0.5"></i>
                              <span>Session count has been set to ${response.defaultValue} sessions.</span>
                            </div>
                          </div>
                        `,
                        confirmButtonColor: '#0ea5e9',
                        showClass: {
                          popup: 'animate__animated animate__fadeInDown animate__faster'
                        }
                      }).then(() => {
                        // Reload the page to refresh the data
                        window.location.reload();
                      });
                    } else {
                      // Error state
                      Swal.fire({
                        icon: 'error',
                        title: 'Reset Failed',
                        text: 'There was an error resetting the session count: ' + response.message,
                        confirmButtonColor: '#0ea5e9'
                      });
                    }
                  },
                  error: function(xhr, textStatus, errorThrown) {
                    console.error("AJAX Error:", textStatus, errorThrown);
                    
                    // Error state for AJAX failure
                    Swal.fire({
                      icon: 'error',
                      title: 'Connection Error',
                      text: 'Could not connect to the server. Error: ' + textStatus + ' - ' + errorThrown,
                      confirmButtonColor: '#0ea5e9'
                    });
                  }
                });
              },
              showConfirmButton: false,
              allowOutsideClick: false,
              allowEscapeKey: false,
              allowEnterKey: false
            });
          }
        });
      }
      
      // Success message if coming back from a successful operation
      <?php if (isset($_GET['success']) && $_GET['success'] == 'delete'): ?>
      Swal.fire({
        icon: 'success',
        title: 'Student Deleted',
        text: 'The student has been successfully removed from the system.',
        confirmButtonColor: '#0ea5e9',
        showClass: {
          popup: 'animate__animated animate__fadeInDown animate__faster'
        }
      });
      <?php endif; ?>

      // Modified action buttons section in the student table to use a modal for editing
      $('.edit-student').on('click', function(e) {
        e.preventDefault();
        const studentId = $(this).data('id');
        
        // Add loading spinner to the button
        $(this).html('<i class="fas fa-spinner fa-spin"></i>');
        const $button = $(this);
        
        // Fetch student data via AJAX
        $.ajax({
          url: '../../includes/fetch_student_data.php',
          method: 'POST',
          data: { studentId: studentId },
          dataType: 'json',
          success: function(student) {
            // Reset button state
            $button.html('<i class="fas fa-edit"></i>');
            
            // Populate and show the modal
            $('#editStudentId').val(student.id_number);
            $('#editLastName').val(student.lastName);
            $('#editFirstName').val(student.firstName);
            $('#editMiddleName').val(student.middleName);
            $('#editYearLevel').val(student.yearLevel);
            $('#editEmail').val(student.email);
            $('#editCourse').val(student.course);
            $('#editAddress').val(student.address);
            
            $('#editStudentModal').modal('show');
          },
          error: function(xhr, textStatus, errorThrown) {
            // Reset button state
            $button.html('<i class="fas fa-edit"></i>');
            
            // Show error
            Swal.fire({
              icon: 'error',
              title: 'Error Loading Student Data',
              text: 'Could not retrieve student information. Please try again.',
              confirmButtonColor: '#0ea5e9'
            });
          }
        });
      });
      
      // Handle edit student form submission
      $('#editStudentForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        
        // Show loading state
        $('#editStudentSubmitBtn').html('<i class="fas fa-spinner fa-spin mr-2"></i>Saving...');
        $('#editStudentSubmitBtn').prop('disabled', true);
        
        // Submit form via AJAX
        $.ajax({
          url: '../../includes/update_student.php',
          method: 'POST',
          data: formData,
          dataType: 'json',
          success: function(response) {
            // Hide modal
            $('#editStudentModal').modal('hide');
            
            if (response.success) {
              // Show success message
              Swal.fire({
                icon: 'success',
                title: 'Student Updated',
                text: 'The student information has been successfully updated.',
                confirmButtonColor: '#0ea5e9',
                showClass: {
                  popup: 'animate__animated animate__fadeInDown animate__faster'
                },
                hideClass: {
                  popup: 'animate__animated animate__fadeOutUp animate__faster'
                }
              }).then(() => {
                // Reload page to show updated data
                window.location.reload();
              });
            } else {
              // Show error message
              Swal.fire({
                icon: 'error',
                title: 'Update Failed',
                text: response.message || 'There was a problem updating the student information.',
                confirmButtonColor: '#0ea5e9'
              });
            }
          },
          error: function(xhr, textStatus, errorThrown) {
            // Hide modal
            $('#editStudentModal').modal('hide');
            
            // Show error message
            Swal.fire({
              icon: 'error',
              title: 'Connection Error',
              text: 'Could not connect to the server. Please try again later.',
              confirmButtonColor: '#0ea5e9'
            });
          },
          complete: function() {
            // Reset button state
            $('#editStudentSubmitBtn').html('<i class="fas fa-save mr-2"></i>Save Changes');
            $('#editStudentSubmitBtn').prop('disabled', false);
          }
        });
      });
    });
  </script>
</body>
</html>