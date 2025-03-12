<?php
include '../../includes/navbar_admin.php';

// Initialize $sql variable
$sql = [];

// Handle form submission for filtering by date
if (isset($_POST["dateSubmit"])) {
    $date = $_POST["date"];
    $sql = get_date_report(filter_date($date));
} else {
    $sql = get_date_report(reset_date());
}

// Handle form submission for resetting the filter
if (isset($_POST['resetSubmit'])) {
    $sql = get_date_report(reset_date());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Report</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            margin-top: 2rem;
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
        <h1>Generate Reports</h1>

        <form action="Report.php" method="POST" class="mb-3">
            <input type="date" name="date" class="form-control d-inline w-auto" />
            <button type="submit" class="btn btn-primary" name="dateSubmit">Search</button>
            <button type="submit" class="btn btn-danger" name="resetSubmit">Reset</button>
        </form>

        <table id="example" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>ID Number</th>
                    <th>Name</th>
                    <th>Purpose</th>
                    <th>Laboratory</th>
                    <th>Login</th>
                    <th>Logout</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sql as $person) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($person['id_number']); ?></td>
                        <td><?php echo htmlspecialchars($person['firstName'] . " " . $person['lastName']); ?></td>
                        <td><?php echo htmlspecialchars($person['sit_purpose']); ?></td>
                        <td><?php echo htmlspecialchars($person['sit_lab']); ?></td>
                        <td><?php echo htmlspecialchars($person['sit_login']); ?></td>
                        <td><?php echo htmlspecialchars($person['sit_logout']); ?></td>
                        <td><?php echo htmlspecialchars($person['sit_date']); ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($sql)) : ?>
                    <tr>
                        <td colspan="7" class="text-center">No data available</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Initialize the DataTable
        $(document).ready(function() {
            let myDataTable = $('#example').DataTable({
                paging: true,
                lengthMenu: [5, 10, 25, 50, 100],
                language: {
                    search: "Filter"
                }
            });
        });
    </script>
</body>

</html>
