<?php
include '../../api/api_student.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CCS | Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
<style>
    .navbar-brand:hover,
    .nav-link:hover {
      color: #007bff !important;
    }
  </style>

</head>

<body>


<nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container">
        <!-- Logos replacing "Dashboard" -->
        <a class="navbar-brand d-flex align-items-center" href="Homepage.php">
            <img src="../../assets/images/uc.png" class="img-fluid me-2" style="height: 50px; width: auto;" alt="UC Logo">
            <img src="../../assets/images/ccs.png" class="img-fluid" style="height: 50px; width: auto;" alt="CCS Logo">
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- Notification Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-dark" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Notifications
                    </a>
                    <div class="dropdown-menu bg-light" aria-labelledby="navbarDropdown">
                        <div style="overflow-y: auto; max-height: 390px; width:20rem">
                            <?php foreach(retrieve_notification($_SESSION['id_number']) as $row) : ?>
                                <ul>
                                    <li>
                                        <small class="dropdown-item" style="word-wrap: break-word; white-space: normal;">
                                            <strong><?php echo $row['message']; ?></strong>
                                        </small>
                                        <hr>
                                    </li>
                                </ul>
                                <br>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </li>

                <!-- Navigation Links -->
                <li class="nav-item">
                    <a class="nav-link text-dark" href="Homepage.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="Profile.php">Edit Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="history.php">History</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="reservation.php">Reservation</a>
                </li>

                <!-- Logout Button -->
                <li class="nav-item">
                    <a class="nav-link text-dark" href="../../auth/logout.php">Log out</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

</body>
</html>
