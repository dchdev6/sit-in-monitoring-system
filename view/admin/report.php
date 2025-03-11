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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body>

    <h1 class="text-center">Generate Reports</h1>

    <div class="container">
        <form action="Report.php" method="POST">
            <input class="" type="date" name="date" />
            <button type="submit" class="btn btn-primary" name="dateSubmit">Search</button>
            <button type="submit" class="btn btn-danger" name="resetSubmit">Reset</button>
        </form>
        <table id="example" class="table table-striped display compact" style="width:100%">
            <thead style="background-color: #144c94">
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
                        <td><?php echo $person['id_number']; ?></td>
                        <td><?php echo $person['firstName'] . " " . $person['lastName']; ?></td>
                        <td><?php echo $person['sit_purpose']; ?></td>
                        <td><?php echo $person['sit_lab']; ?></td>
                        <td><?php echo $person['sit_login']; ?></td>
                        <td><?php echo $person['sit_logout']; ?></td>
                        <td><?php echo $person['sit_date']; ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($sql)) : ?>
                    <tr>
                        <td colspan="7">No data available</td>
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
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Initialize the DataTable
        $(document).ready(function() {
            let myDataTable = $('#example').DataTable({
                layout: {
                    topStart: {
                        buttons: ['csv', 'excel', 'pdf', 'print']
                    }
                },
                "oLanguage": {
                    "sSearch": "Filter"
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

            // Attach click event listener to each button
            myDataTable.buttons().nodes().each(function() {
                $(this).on('click', function() {
                    showSweetAlert();
                });
            });
        });
    </script>

</body>

</html>