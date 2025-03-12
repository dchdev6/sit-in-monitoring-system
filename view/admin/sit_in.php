<?php
include '../../includes/navbar_admin.php';

$listPerson = retrieve_sit_in();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sit In Records</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            margin-top: 2rem;
            color: #0d6efd;
            text-align: center;
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

        .btn-danger {
            background-color: #c82333;
            border-color: #bd2130;
            border-radius: 8px;
        }

        .btn-danger:hover {
            background-color: #a71d2a;
            border-color: #981b25;
        }
    </style>
</head>

<body>

    <h1>Current Sit In</h1>
    
    <div class="container table-container">
        <table id="example" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>Sit ID Number</th>
                    <th>ID Number</th>
                    <th>Name</th>
                    <th>Purpose</th>
                    <th>Sit Lab</th>
                    <th>Session</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listPerson as $person) : ?>
                    <tr>
                        <td><?php echo $person['sit_id']; ?></td>
                        <td><?php echo $person['id_number']; ?></td>
                        <td><?php echo $person['firstName'] . " " . $person['middleName'] . ". " . $person['lastName']; ?></td>
                        <td><?php echo $person['sit_purpose']; ?></td>
                        <td><?php echo $person['sit_lab']; ?></td>
                        <td><?php echo $person['session']; ?></td>
                        <td><?php echo $person['status']; ?></td>
                        <td class="text-center">
                            <form action="../../api/api_admin.php" method="POST">
                                <input type="hidden" name="session" value="<?php echo $person['session']; ?>" />
                                <input type="hidden" name="idNum" value="<?php echo $person['id_number']; ?>" />
                                <input type="hidden" name="sitLab" value="<?php echo $person['sit_lab']; ?>" />
                                <input type="hidden" name="sitId" value="<?php echo $person['sit_id']; ?>" />
                                <button type="submit" name="logout" class="btn btn-danger">Logout</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($listPerson)) : ?>
                    <tr>
                        <td colspan="8" class="text-center">No data available</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>

</body>

</html>