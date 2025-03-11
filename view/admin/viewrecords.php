<?php
include '../../includes/navbar_admin.php';

$listPerson = retrieve_current_sit_in();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sit In Records</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

    <h1 class="text-center">Current Sit In Records</h1>
    <div class="container container-fluid d-flex flex-row gap-3">
        <div class="col-4 me-4"><canvas id="myChart"></canvas></div>
        <div class="col-4"><canvas id="students"></canvas></div>
    </div>
    <div class="container">
        <table id="example" class="table table-striped display compact" style="width:100%">
            <thead style="background-color:#144c94">
                <tr>
                    <th>Sit-in Number</th>
                    <th>ID Number</th>
                    <th>Name</th>
                    <th>Purpose</th>
                    <th>Lab</th>
                    <th>Login</th>
                    <th>Logout</th>
                    <th>Date</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($listPerson as $person) : ?>
                    <tr>
                        <td><?php echo $person['sit_id']; ?></td>
                        <td><?php echo $person['id_number']; ?></td>
                        <td><?php echo $person['firstName'] . " " . $person['lastName']; ?></td>
                        <td><?php echo $person['sit_purpose']; ?></td>
                        <td><?php echo $person['sit_lab']; ?></td>
                        <td><?php echo $person['sit_login']; ?></td>
                        <td><?php echo $person['sit_logout']; ?></td>
                        <td><?php echo $person['sit_date']; ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($listPerson)) : ?>
                    <tr>
                        <td colspan="8">No data available</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

    <script>
        // Initialize DataTable
        $(document).ready(function() {
            $('#example').DataTable();
        });

        // Initialize Chart.js
        const ctx = document.getElementById('myChart').getContext('2d');
        const stud = document.getElementById('students').getContext('2d');

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['C#', 'C', 'Java', 'ASP.Net', 'Php'],
                datasets: [{
                    label: 'Programming Languages',
                    data: [
                        <?php echo retrieve_c_sharp_programming_current(); ?>,
                        <?php echo retrieve_c_programming_current(); ?>,
                        <?php echo retrieve_java_programming_current(); ?>,
                        <?php echo retrieve_asp_programming_current(); ?>,
                        <?php echo retrieve_php_programming_current(); ?>
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Programming Languages Usage'
                    }
                }
            }
        });

        new Chart(stud, {
            type: 'pie',
            data: {
                labels: ['524', '526', '528', '530', '542', 'Mac'],
                datasets: [{
                    label: 'Laboratories',
                    data: [
                        <?php echo retrieve_lab_524(); ?>,
                        <?php echo retrieve_lab_526(); ?>,
                        <?php echo retrieve_lab_528(); ?>,
                        <?php echo retrieve_lab_530(); ?>,
                        <?php echo retrieve_lab_542(); ?>,
                        <?php echo retrieve_lab_Mac(); ?>
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Laboratory Usage'
                    }
                }
            }
        });
    </script>

</body>

</html>