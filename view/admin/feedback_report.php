<?php
include '../../includes/navbar_admin.php';

// Include backend files
require_once '../../backend/backend_admin.php'; 
require_once '../../backend/database_connection.php';

// Fetch feedback data
$feedbackData = view_feedback();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Report</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/2.0.2/css/dataTables.bootstrap5.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            color: #0d6efd;
            font-weight: 600;
            margin-top: 2rem;
            text-align: center;
        }
        .table-container {
            max-width: 90%;
            margin: 2rem auto;
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

    <h1>Feedback Report</h1>

    <div class="table-container">
        <table id="feedbackTable" class="table table-striped table-bordered table-responsive w-100">
            <thead>
                <tr>
                    <th>Student ID Number</th>
                    <th>Laboratory</th>
                    <th>Date</th>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody>
    <?php if (!empty($feedbackData)) : ?>
        <?php foreach ($feedbackData as $person) : ?>
            <tr>
                <td><?php echo isset($person['id_number']) ? htmlspecialchars($person['id_number']) : 'N/A'; ?></td>
                <td><?php echo isset($person['lab']) ? htmlspecialchars($person['lab']) : 'N/A'; ?></td>
                <td><?php echo isset($person['date']) ? htmlspecialchars($person['date']) : 'N/A'; ?></td>
                <td><?php echo isset($person['message']) ? htmlspecialchars($person['message']) : 'N/A'; ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else : ?>
        <tr>
            <td colspan="4" class="text-center">No data available</td>
        </tr>
    <?php endif; ?>
</tbody>
        </table>
    </div>

    <!-- JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- DataTables Buttons CSS -->
    <link href="https://cdn.datatables.net/buttons/3.0.1/css/buttons.bootstrap5.min.css" rel="stylesheet">

    <!-- DataTables and Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.print.min.js"></script>
</body>
</html>
