<?php
include '../../includes/navbar_admin.php';

$announce = view_announcement();
$feedback = view_feedback();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background-color: #007bff;
            color: white;
            font-size: 1.25rem;
            font-weight: 600;
            border-radius: 10px 10px 0 0;
            padding: 1rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        textarea.form-control {
            resize: none;
            border-radius: 5px;
            border: 1px solid #ced4da;
            padding: 0.75rem;
        }

        .announcement-list {
            max-height: 390px;
            overflow-y: auto;
            padding-right: 10px;
        }

        .announcement-list p {
            margin-bottom: 0.5rem;
        }

        .announcement-list hr {
            margin: 1rem 0;
            border-top: 1px solid #eee;
        }

        .chart-container {
            margin-top: 1.5rem;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <!-- Statistics and Announcement Section -->
        <div class="row g-4">
            <!-- Statistics Card -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-chart-column me-2"></i> Statistics
                    </div>
                    <div class="card-body">
                        <p class="card-text"><strong>Students Registered: </strong> <?php echo retrieve_students_dashboard(); ?></p>
                        <p class="card-text"><strong>Currently Sit-in: </strong> <?php echo retrieve_current_sit_in_dashboard(); ?></p>
                        <p class="card-text"><strong>Total Sit-in: </strong> <?php echo retrieve_total_sit_in_dashboard(); ?></p>
                        <div class="chart-container">
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Announcement Card -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-bullhorn me-2"></i> Announcement
                    </div>
                    <div class="card-body">
                        <label for="an" class="form-label">New Announcement</label>
                        <form action="Admin.php" method="POST">
                            <textarea name="announcement_text" id="an" class="form-control mb-3" rows="3"></textarea>
                            <button type="submit" name="post_announcement" class="btn btn-success">Submit</button>
                        </form>

                        <h3 class="mt-4"><strong>Posted Announcement</strong></h3>
                        <hr>

                        <div class="announcement-list">
                            <?php foreach ($announce as $row) : ?>
                                <p><strong><?php echo $row['admin_name'] . " | " . $row['date']; ?></strong></p>
                                <p><?php echo $row['message']; ?></p>
                                <hr>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students Year Level Card -->
        <div class="card mt-4">
            <div class="card-header">
                <i class="fas fa-chalkboard-user me-2"></i> Students Year Level
            </div>
            <div class="card-body">
                <canvas id="students"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('myChart');
        const stud = document.getElementById('students');

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['C#', 'C', 'Java', 'ASP.Net', 'Php'],
                datasets: [{
                    label: 'Programming Languages',
                    data: [<?php echo retrieve_c_sharp_programming(); ?>, <?php echo retrieve_c_programming(); ?>, <?php echo retrieve_java_programming(); ?>, <?php echo retrieve_asp_programming(); ?>, <?php echo retrieve_php_programming(); ?>],
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });

        new Chart(stud, {
            type: 'bar',
            data: {
                labels: ['Freshmen', 'Sophomore', 'Junior', 'Senior'],
                datasets: [{
                    label: 'College of Computer Studies Students Year Level',
                    data: [<?php echo retrieve_first(); ?>, <?php echo retrieve_second(); ?>, <?php echo retrieve_third(); ?>, <?php echo retrieve_fourth(); ?>],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>