<?php
require_once '../../includes/navbar_student.php';

$listPerson = retrieve_student_history($_SESSION['id_number']);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.bootstrap5.css">
    <style>
        /* Custom table header style */
        .table-custom thead {
            background-color: #0d6efd; /* Bootstrap's btn-primary color */
            color: white; /* White text */
            border-radius: 10px; /* Rounded corners */
        }

        /* Override Bootstrap's bold text for table headers */
        .table-custom thead th {
            font-weight: normal; /* Make text not bold */
            color: white; /* Ensure text is white */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            padding: 15px; /* Increase padding */
            text-align: center; /* Center align text */
        }

        /* Add hover effect to table header cells */
        .table-custom thead th:hover {
            background-color: #0b5ed7; /* Darker blue on hover */
            transition: background-color 0.3s ease; /* Smooth transition */
        }

        /* Add a border to the table */
        .table-custom {
            border: 1px solid #dee2e6; /* Light gray border */
            border-radius: 10px; /* Rounded corners for the table */
            overflow: hidden; /* Ensure rounded corners are visible */
        }

        /* Ensure the table takes up the full width */
        #example_wrapper {
            width: 100%;
        }

        /* Ensure the container takes up the full width */
        .container-fluid {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }

        /* Ensure the table header takes up the full width */
        .table-custom thead th {
            width: auto; /* Allow columns to adjust */
        }
    </style>
</head>
<body>
    <h1 class="text-center">History Information</h1>
    <div class="container-fluid px-5">
        <table id="example" class="table table-striped table-custom display compact" style="width:100%">
            <thead>
                <tr>
                    <th>ID Number</th>
                    <th>Name</th>
                    <th>Sit Purpose</th>
                    <th>Laboratory</th>
                    <th>Login</th>
                    <th>Logout</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listPerson as $person) : ?>
                    <tr>
                        <td><?php echo $person['id_number']; ?></td>
                        <td><?php echo $person['firstName'] . " " . $person['lastName']; ?></td>
                        <td><?php echo $person['sit_purpose']; ?></td>
                        <td><?php echo $person['sit_lab']; ?></td>
                        <td><?php echo $person['sit_login']; ?></td>
                        <td><?php echo $person['sit_logout'] ?? 'N/A'; ?></td>
                        <td><?php echo $person['sit_date']; ?></td>
                        <td>
                            <button type="button" class="btn btn-success mx-3 feed-btn" data-toggle="modal" data-target="#feedback_modal" data-id_number="<?php echo $person['id_number']; ?>" data-sit_lab="<?php echo $person['sit_lab']; ?>">Feedback</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Feedback Modal -->
    <div class="modal fade" id="feedback_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="history.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Feedback Experience</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id_number" name="id_number">
                        <input type="hidden" id="sit_lab" name="sit_lab">
                        <textarea class="form-control" name="feedback_text" placeholder="Tell us what you experience..."></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit_feedback" class="btn btn-primary">Submit Feedback</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.bootstrap5.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                responsive: true, // Enable responsive behavior
                autoWidth: true,  // Automatically adjust column widths
                scrollX: true,    // Enable horizontal scrolling if needed
            });
        });

        document.querySelectorAll('.feed-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                var idNum = this.getAttribute('data-id_number');
                var lab = this.getAttribute('data-sit_lab');
                document.getElementById('id_number').value = idNum;
                document.getElementById('sit_lab').value = lab;
            });
        });
    </script>
</body>
</html>