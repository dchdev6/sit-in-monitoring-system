<?php
include '../../includes/navbar_admin.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CCS | Reservation</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        h1 {
            color: #007bff;;
            font-weight: 600;
            margin-top: 2rem;
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
            background-color: #007bff;;
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

        .btn-danger {
            background-color: #dc3545;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn-danger:hover {
            background-color: #bb2d3b;
        }

        .scrollable {
            overflow-y: auto;
            max-height: 390px;
            padding-right: 10px;
        }

        .scrollable p {
            margin-bottom: 0.5rem;
        }

        .scrollable hr {
            margin: 1rem 0;
            border-top: 1px solid #eee;
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #ced4da;
            padding: 0.75rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center mb-4">Reservation Management</h1>

        <!-- Main Content -->
        <div class="row g-4">
            <!-- Computer Control Card -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header text-center">Computer Control</div>
                    <div class="card-body">
                        <form action="Reservation.php" method="POST">
                            <div class="mb-3">
                                <label for="lab" class="form-label">Lab</label>
                                <select name="lab" id="lab" class="form-control">
                                    <option value="524">524</option>
                                    <option value="526">526</option>
                                    <option value="528">528</option>
                                    <option value="542">542</option>
                                    <option value="Mac">Mac</option>
                                </select>
                            </div>
                            <button type="submit" name="labSubmit" class="btn btn-primary w-100">Filter</button>
                        </form>

                        <form action="Reservation.php" method="POST">
                            <div class="mt-4 scrollable">
                                <?php foreach ($data as $row) : ?>
                                    <div class="mb-2">
                                        <input type="hidden" name="filter_lab" value="<?php echo $lab_final ?>">
                                        <input type="checkbox" id="PC<?php echo $row['pc_id']; ?>" name="pc[]" value="<?php echo $row['pc_id']; ?>">
                                        <label for="PC<?php echo $row['pc_id']; ?>">
                                            <?php if ($row['lab2'] == '1') : ?>
                                                <p style='color:green;'>PC <?php echo $row['pc_id'] . " (Available)"; ?></p>
                                            <?php else : ?>
                                                <p style='color:red;'>PC <?php echo $row['pc_id'] . " (Used)"; ?></p>
                                            <?php endif; ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="d-flex gap-2 mt-3">
                                <button type="submit" name="submitAvail" class="btn btn-success w-50">Available</button>
                                <button type="submit" name="submitDecline" class="btn btn-danger w-50">Used</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Reservation Request Card -->
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header text-center">Reservation Request</div>
                    <div class="card-body">
                        <div class="scrollable">
                            <?php foreach (retrieve_reservation() as $row) : ?>
                                <p><strong>ID Number: </strong><?php echo $row['id_number'] ?></p>
                                <p><strong>Reservation Date: </strong><?php echo $row['reservation_date'] ?></p>
                                <p><strong>Reservation Time: </strong><?php echo $row['reservation_time'] ?></p>
                                <p><strong>Laboratory: </strong><?php echo $row['lab'] ?></p>
                                <p><strong>Computer Number: </strong><?php echo $row['pc_number'] ?></p>
                                <p><strong>Purpose: </strong><?php echo $row['purpose'] ?></p>
                                <div class="d-flex gap-2">
                                    <form action="Reservation.php" method="POST">
                                        <input name="reservation_id" value="<?php echo $row['reservation_id'] ?>" type="hidden">
                                        <input name="pc_number" value="<?php echo $row['pc_number'] ?>" type="hidden">
                                        <input name="lab" value="<?php echo "lab_" . $row['lab'] ?>" type="hidden">
                                        <input name="id_number" value="<?php echo $row['id_number'] ?>" type="hidden">
                                        <button type="submit" name="accept_reservation" class="btn btn-success">Accept</button>
                                        <button type="submit" name="deny_reservation" class="btn btn-danger">Deny</button>
                                    </form>
                                </div>
                                <hr>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logs Card -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header text-center">Logs</div>
                    <div class="card-body">
                        <div class="scrollable">
                            <?php foreach (retrieve_reservation_logs() as $row) : ?>
                                <p><strong>ID Number: </strong><?php echo $row['id_number'] ?></p>
                                <p><strong>Reservation Date: </strong><?php echo $row['reservation_date'] ?></p>
                                <p><strong>Reservation Time: </strong><?php echo $row['reservation_time'] ?></p>
                                <p><strong>Laboratory: </strong><?php echo $row['lab'] ?></p>
                                <p><strong>Computer Number: </strong><?php echo $row['pc_number'] ?></p>
                                <p><strong>Purpose: </strong><?php echo $row['purpose'] ?></p>
                                <p style="<?php if ($row['status'] == 'Approve') echo 'color:green;'; else if ($row['status'] == 'Decline') echo "color:red;" ?>">
                                    <strong>Status: </strong><?php echo $row['status'] ?>
                                </p>
                                <hr>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-a lpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>