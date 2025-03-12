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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            margin-top: 2rem;
            margin-bottom: 2rem;
            text-align: center;
            color: #0d6efd;
            font-weight: bold;
        }

        .table-container {
            margin: 2rem auto;
            max-width: 95%;
        }

        .table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .table thead th {
            background-color: #0d6efd !important;
            color: white !important;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container table-container">
        <h1>Current Sit In Records</h1>
        <table id="example" class="table table-striped table-bordered" style="width:100%">
            <thead>
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
                <?php if (!empty($listPerson)) : ?>
                    <?php foreach ($listPerson as $person) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($person['sit_id']); ?></td>
                            <td><?php echo htmlspecialchars($person['id_number']); ?></td>
                            <td><?php echo htmlspecialchars($person['firstName'] . " " . $person['lastName']); ?></td>
                            <td><?php echo htmlspecialchars($person['sit_purpose']); ?></td>
                            <td><?php echo htmlspecialchars($person['sit_lab']); ?></td>
                            <td><?php echo htmlspecialchars($person['sit_login']); ?></td>
                            <td><?php echo htmlspecialchars($person['sit_logout']); ?></td>
                            <td><?php echo htmlspecialchars($person['sit_date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="8" class="text-center">No data available</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
