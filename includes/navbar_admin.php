<?php 
include '../../api/api_admin.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha384-nrZw6K5mh7Pdc0eo+eX+56dD5w0UKqNwKAU9PIO8KjkvRdK2AZzU6vXfb4rIWj80" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.6/css/dataTables.bootstrap5.css">
  <link rel="icon" href="ccsLogo.ico" type="image/x-icon">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <title>CCS | Home</title>

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
      <a class="navbar-brand d-flex align-items-center" href="Admin.php">
        <img src="../../assets/images/uc.png" class="img-fluid me-2" style="height: 50px; width: auto;" alt="UC Logo">
        <img src="../../assets/images/ccs.png" class="img-fluid" style="height: 50px; width: auto;" alt="CCS Logo">
      </a>
      
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <!-- Navigation Links -->
          <li class="nav-item">
            <a class="nav-link text-dark" href="Admin.php">Home</a>
          </li>
          <li class="nav-item">
            <a type="button" class="nav-link text-dark" data-toggle="modal" data-target="#exampleModal"> Search </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark" href="Students.php">Students</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark" href="Sit_in.php">Sit-in</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark" href="ViewRecords.php">View Sit-in Records</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark" href="Report.php">Sit-in Reports</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark" href="Feedback_Report.php">Feedback Reports</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark" href="Reservation.php">Reservation</a>
          </li>
          <!-- Logout Button -->
          <li class="nav-item">
            <a class="btn btn-primary" href="../../auth/logout.php">Log out</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Your HTML content goes here -->

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
  <script src="https://cdn.datatables.net/2.0.6/js/dataTables.bootstrap5.js"></script>
  <script src="https://cdn.datatables.net/2.0.6/js/dataTables.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.dataTables.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.print.min.js"></script>
</body>

</html>
