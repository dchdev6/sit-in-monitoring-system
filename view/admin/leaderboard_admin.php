<?php
// Include the navbar which includes the API
include '../../includes/navbar_admin.php';
require_once '../../includes/points_functions.php';

// Set number of students to display
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 25;
if ($limit <= 0) $limit = 25;

// Get leaderboard data
$leaderboard = get_leaderboard($limit);
?>

<style>
    body {
        opacity: 0;
        transition: opacity 0.5s ease;
    }
    
    body.opacity-100 {
        opacity: 1;
    }
    
    .animate-fadeIn {
        animation: fadeIn 0.5s ease forwards;
    }
    
    .animate-slideIn {
        animation: slideIn 0.5s ease forwards;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    #leaderboardTable tbody tr {
        opacity: 0;
        transition: background-color 0.2s ease;
    }
    
    /* Improved modal animation */
    #pointHistoryModal {
        transition: opacity 0.3s ease;
    }
    
    #pointHistoryModal.hidden {
        opacity: 0;
        pointer-events: none;
    }
    
    #pointHistoryModal:not(.hidden) {
        opacity: 1;
        pointer-events: auto;
    }
    
    #pointHistoryModal .transform {
        transition: transform 0.3s ease;
    }
</style>

<div class="container max-w-7xl mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Student Leaderboard</h1>
        <p class="text-gray-600 mt-2">Top students with the highest reward points</p>
    </div>
    
    <!-- Filter and Limit Controls -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form action="" method="get" class="flex flex-col md:flex-row items-center justify-between">
            <div class="flex items-center mb-4 md:mb-0">
                <h3 class="text-lg font-semibold text-gray-700 mr-4">Leaderboard Settings</h3>
                <select name="limit" class="form-select rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="10" <?php echo $limit == 10 ? 'selected' : ''; ?>>Top 10 Students</option>
                    <option value="25" <?php echo $limit == 25 ? 'selected' : ''; ?>>Top 25 Students</option>
                    <option value="50" <?php echo $limit == 50 ? 'selected' : ''; ?>>Top 50 Students</option>
                    <option value="100" <?php echo $limit == 100 ? 'selected' : ''; ?>>Top 100 Students</option>
                </select>
            </div>
            
            <div>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Apply Filters
                </button>
                <a href="reward_points.php" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Reward Points
                </a>
                <a href="pending_points.php" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:border-yellow-900 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Pending Requests
                </a>
            </div>
        </form>
    </div>
    
    <!-- Leaderboard Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Top <?php echo $limit; ?> Students</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="leaderboardTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Points</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($leaderboard)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                No students have earned points yet
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($leaderboard as $index => $student): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <?php if ($index < 3): ?>
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full 
                                                <?php echo $index === 0 ? 'bg-yellow-400 text-yellow-900' : 
                                                        ($index === 1 ? 'bg-gray-300 text-gray-900' : 
                                                        'bg-yellow-700 text-yellow-100'); ?>
                                                font-bold text-sm">
                                                <?php echo $index + 1; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-sm text-gray-900"><?php echo $index + 1; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo htmlspecialchars($student['id_number']); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500"><?php echo htmlspecialchars($student['program'] ?? 'N/A'); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900"><?php echo number_format($student['points']); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Point History Modal -->
<div id="pointHistoryModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalTitle">
                            Point History
                        </h3>
                        <div class="mt-4">
                            <div class="max-h-96 overflow-y-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Points</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200" id="historyTableBody">
                                        <!-- History data will be inserted here dynamically -->
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                                Loading history...
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function viewPointHistory(studentId, studentName) {
        // Set modal title
        document.getElementById('modalTitle').textContent = "Point History for " + studentName;
        
        // Show loading state with SweetAlert2
        Swal.fire({
            title: 'Loading...',
            html: 'Fetching point history data',
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Fetch point history from API
        fetch('../../api/get_point_history.php?student_id=' + studentId)
            .then(response => {
                return response.json();
            })
            .then(data => {
                Swal.close();
                
                // Show the modal
                const modal = document.getElementById('pointHistoryModal');
                modal.classList.remove('hidden');
                
                // Get the table body element
                const historyTableBody = document.getElementById('historyTableBody');
                historyTableBody.innerHTML = '';
                
                if (data.error) {
                    historyTableBody.innerHTML = `
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                ${data.error}
                            </td>
                        </tr>
                    `;
                    return;
                }
                
                if (data.length === 0) {
                    historyTableBody.innerHTML = `
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                No point history found for this student
                            </td>
                        </tr>
                    `;
                    return;
                }
                
                // Add each history record to the table
                data.forEach((record, index) => {
                    const date = new Date(record.created_at).toLocaleString();
                    const pointsValue = record.points;
                    const reason = record.reason || 'N/A';
                    
                    const pointsDisplay = pointsValue >= 0 ? 
                        `<span class="text-green-600">+${pointsValue}</span>` : 
                        `<span class="text-red-600">${pointsValue}</span>`;
                    
                    const row = document.createElement('tr');
                    row.className = index % 2 === 0 ? 'bg-white' : 'bg-gray-50';
                    row.innerHTML = `
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">${date}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm font-medium">${pointsDisplay}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">${reason}</td>
                    `;
                    
                    // Add animation delay based on index
                    row.style.opacity = "0";
                    setTimeout(() => {
                        row.style.transition = "opacity 0.3s ease";
                        row.style.opacity = "1";
                    }, 10);
                    
                    historyTableBody.appendChild(row);
                });
            })
            .catch(error => {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch point history data',
                });
                console.error('Error fetching point history:', error);
            });
    }

    function closeModal() {
        const modal = document.getElementById('pointHistoryModal');
        const modalContent = modal.querySelector('.inline-block');
        
        // Animate out
        modalContent.style.transform = "scale(0.9)";
        modalContent.style.opacity = "0";
        
        // Hide after animation completes
        setTimeout(() => {
            modal.classList.add('hidden');
            modalContent.style.transform = "";
            modalContent.style.opacity = "";
        }, 300);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Fade in the body
        document.body.classList.add('opacity-100');
        
        // Animate table rows with staggered effect
        const tableRows = document.querySelectorAll('#leaderboardTable tbody tr');
        tableRows.forEach((row, index) => {
            row.style.opacity = "0";
            
            setTimeout(() => {
                row.style.transition = "opacity 0.3s ease";
                row.style.opacity = "1";
            }, 100 + (index * 50)); // 50ms delay between each row
        });
        
        // Add hover effects to buttons and links
        const buttons = document.querySelectorAll('button, a.inline-flex');
        buttons.forEach(button => {
            button.addEventListener('mouseenter', () => {
                button.style.transform = "translateY(-2px)";
            });
            
            button.addEventListener('mouseleave', () => {
                button.style.transform = "";
            });
        });
    });
    
    // Ensure the page content is visible immediately if DOMContentLoaded doesn't fire
    setTimeout(() => {
        document.body.classList.add('opacity-100');
    }, 500);
</script>

<?php
// Include footer if needed
// include '../../includes/footer.php';
?>