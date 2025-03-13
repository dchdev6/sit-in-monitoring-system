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

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

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

        .dt-buttons {
            margin-bottom: 15px;
        }

        .dt-buttons .btn {
            margin-right: 5px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container table-container">
        <h1>Generate Reports</h1>

        <form action="report.php" method="POST" class="mb-3">
            <input type="date" name="date" class="form-control d-inline w-auto" required />
            <button type="submit" class="btn btn-primary" name="dateSubmit">Search</button>
            <button type="submit" class="btn btn-danger" name="resetSubmit">Reset</button>
        </form>

        <table id="exportTable" class="table table-striped table-bordered" style="width:100%">
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
                <?php if (!empty($sql)) : ?>
                    <?php foreach ($sql as $person) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($person['id_number'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars(($person['firstName'] ?? '') . " " . ($person['lastName'] ?? '')); ?></td>
                            <td><?php echo htmlspecialchars($person['sit_purpose'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($person['sit_lab'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($person['sit_login'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($person['sit_logout'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($person['sit_date'] ?? ''); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7" class="text-center">No data available</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- jQuery (MUST be first) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables 1.13.6 -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- DataTables Buttons 2.4.2 -->
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>

    <!-- File Export Dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <!-- Buttons for Export -->
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function() {
            console.log("Checking jQuery Version:", $.fn.jquery);
            console.log("Checking DataTables Version:", $.fn.dataTable.version);

            let myDataTable = $('#exportTable').DataTable({
                paging: true,
                dom: "<'row'<'col-md-6'l><'col-md-6 text-end'B>>" +
                     "<'row'<'col-sm-12'tr>>" +
                     "<'row'<'col-md-5'i><'col-md-7'p>>",
                buttons: [
                    {
                        extend: 'copy',
                        text: '<i class="fas fa-copy"></i> Copy',
                        className: 'btn btn-primary rounded-pill px-3'
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fas fa-file-csv"></i> CSV',
                        className: 'btn btn-primary rounded-pill px-3'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-primary rounded-pill px-3'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-primary rounded-pill px-3'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print',
                        className: 'btn btn-primary rounded-pill px-3'
                    }
                ],
                language: {
                    search: "🔍 Search",
                    lengthMenu: "Show _MENU_ entries",
                }
            });

            setTimeout(function() {
                if ($('.dt-buttons').length === 0) {
                    alert("Export buttons failed to load. Check console for errors.");
                }
            }, 2000);
        });
    </script>
</body>

</html>
