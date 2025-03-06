<?php
include '../../includes/navbar_admin.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Report</title>
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
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        h1 {
            color: #144c94;
            font-weight: 600;
            margin-top: 2rem;
        }

        .table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .table thead th {
            background-color: #144c94;
            color: white;
            font-weight: 600;
        }

        .table tbody tr {
            transition: background-color 0.3s ease;
        }


        .dataTables_wrapper .dataTables_filter input {
            border-radius: 5px;
            border: 1px solid #ced4da;
            padding: 0.375rem 0.75rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 5px;
            margin: 0 2px;
            padding: 0.375rem 0.75rem;
            border: 1px solid #dee2e6;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #144c94;
            color: white !important;
            border: 1px solid #144c94;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center mb-4">Feedback Report</h1>

        <!-- Feedback Table -->
        <table id="example" class="table table-striped display compact table-responsive" style="width:100%">
            <thead>
                <tr>
                    <th>Student ID Number</th>
                    <th>Laboratory</th>
                    <th>Date</th>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (view_feedback() as $person) : ?>
                    <tr>
                        <td><?php echo $person['id_number']; ?></td>
                        <td><?php echo $person['lab']; ?></td>
                        <td><?php echo $person['date']; ?></td>
                        <td><?php echo $person['message']; ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty(view_feedback())) : ?>
                    <tr>
                        <td colspan="4" class="text-center">No data available</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.bootstrap5.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Initialize the DataTable
        let myDataTable = new DataTable('#example', {
            layout: {
                topStart: {
                    buttons: ['print']
                }
            },
            language: {
                search: "Filter:"
            }
        });

        // Function to show the SweetAlert popup
        function showSweetAlert() {
            let timerInterval;
            Swal.fire({
                title: "Downloading Data!",
                html: "Processing in <b></b> milliseconds.",
                timer: 2000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                    const timer = Swal.getPopup().querySelector("b");
                    timerInterval = setInterval(() => {
                        timer.textContent = `${Swal.getTimerLeft()}`;
                    }, 100);
                },
                willClose: () => {
                    clearInterval(timerInterval);
                }
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.timer) {
                    console.log("I was closed by the timer");
                }
            });
        }

        // Attach click event listener to DataTable buttons
        document.addEventListener('DOMContentLoaded', function() {
            let buttons = document.querySelectorAll('.dt-button');
            buttons.forEach(button => {
                button.addEventListener('click', showSweetAlert);
            });
        });
    </script>
</body>

</html>